-- ============================================================
-- sd_gem_direct + sd_gem_direct_payment — migration to support
-- the new proposal workflow (IIBCC Chairman + Vetter) and a
-- SEPARATE payment table.
-- ============================================================
--
-- Proposal-stage status codes (sd_gem_direct.status):
--   10  Submitted          → waiting HOS
--   14  HOS Rejected       → close file
--   40  HOS Rework         → back to user (restart)
--   15  HOS Approved       → waiting IIBCC Chairman
--   29  Chairman Rework    → back to user (restart)
--   19  Chairman Rejected  → close file
--   16  Sent to Vetter     → only when total cost > 50,000
--   24  Vetter Rework      → back to user (restart)
--   17  Vetter Approved    → back to Chairman
--   20  Chairman Approved  → IIBCC number generated, ready for print
--
-- Payment-stage status codes (sd_gem_direct_payment.status):
--   10  Payment submitted (payment release letter PDF ready)
--   20  Payment completed
--    6  Cancelled
-- ============================================================

START TRANSACTION;

-- ==========================================================
-- sd_gem_direct — proposal-stage-only columns (no payment)
-- ==========================================================
ALTER TABLE `sd_gem_direct`
  -- IIBCC chairman + vetter workflow
  ADD COLUMN `iibcc_no` VARCHAR(50) NULL AFTER `gem_id_item`,
  ADD COLUMN `iibcc_chairman_id` INT(11) NULL,
  ADD COLUMN `iibcc_chairman_remarks` TEXT NULL,
  ADD COLUMN `iibcc_chairman_time` DATETIME NULL,
  ADD COLUMN `vetter_user_id` INT(11) NULL,
  ADD COLUMN `vetter_assigned_by` INT(11) NULL,
  ADD COLUMN `vetter_assigned_time` DATETIME NULL,
  ADD COLUMN `vetter_remarks` TEXT NULL,
  ADD COLUMN `vetter_time` DATETIME NULL,

  -- calculated column shown in the list (cost * quantity)
  ADD COLUMN `total_cost` DECIMAL(14,2) NULL;

ALTER TABLE `sd_gem_direct`
  ADD INDEX `idx_gem_direct_status`   (`status`),
  ADD INDEX `idx_gem_direct_chairman` (`iibcc_chairman_id`),
  ADD INDEX `idx_gem_direct_vetter`   (`vetter_user_id`);

UPDATE `sd_gem_direct`
   SET `total_cost` = CAST(`cost` AS DECIMAL(14,2)) * CAST(`quantity` AS DECIMAL(14,2))
 WHERE `total_cost` IS NULL
   AND `cost` IS NOT NULL
   AND `quantity` IS NOT NULL;

-- ==========================================================
-- sd_gem_direct_payment — payment-stage data kept separately
-- (one-to-one with sd_gem_direct via sd_gem_direct_id)
-- ==========================================================
CREATE TABLE IF NOT EXISTS `sd_gem_direct_payment` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `sd_gem_direct_id` INT(11) NOT NULL,

  -- Contract / delivery
  `gem_contract_no` VARCHAR(100) NULL,
  `gem_contract_date` DATE NULL,
  `schedule_delivery_date` DATE NULL,
  `tax_invoice_no` VARCHAR(100) NULL,
  `tax_invoice_date` DATE NULL,
  `actual_delivery_date` DATE NULL,

  -- Money
  `payment_value` DECIMAL(14,2) NULL,
  `liquidated_damages` DECIMAL(14,2) NOT NULL DEFAULT 0,
  `payable_value` DECIMAL(14,2) NULL,

  -- Firm / seller
  `firm_name` VARCHAR(200) NULL,
  `firm_address` TEXT NULL,
  `firm_contact` VARCHAR(50) NULL,
  `firm_email` VARCHAR(100) NULL,
  `firm_bank_account` VARCHAR(50) NULL,
  `firm_ifsc` VARCHAR(20) NULL,

  -- Comments / authorities
  `user_comments` TEXT NULL,
  `approving_authority` VARCHAR(20) NULL,  -- HOS / HOD / AD / GD
  `consignee_id` INT(11) NULL,
  `buyer_id` INT(11) NULL,

  -- Uploaded documents (paths)
  `file_gem_indent_approved` TEXT NULL,
  `file_seller_invoice` TEXT NULL,
  `file_gem_invoice` TEXT NULL,
  `file_gem_contract_order` TEXT NULL,
  `file_gem_sanction_order` TEXT NULL,
  `file_gem_crac` TEXT NULL,
  `file_material_inspection` TEXT NULL,

  -- Lifecycle
  `status` INT(11) NOT NULL DEFAULT 10,   -- 10 submitted, 20 completed, 6 cancelled
  `sd_mt_userdb_id` INT(11) NOT NULL,     -- who submitted
  `created_time` DATETIME NOT NULL DEFAULT current_timestamp(),
  `last_modified_by` INT(11) NULL,
  `last_modified_time` DATETIME NULL ON UPDATE current_timestamp(),

  PRIMARY KEY (`ID`),
  UNIQUE KEY `uq_payment_gem_direct` (`sd_gem_direct_id`),
  KEY `idx_payment_status` (`status`),
  KEY `idx_payment_consignee` (`consignee_id`),
  KEY `idx_payment_buyer` (`buyer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ==========================================================
-- Site-settings rows for the new role keys
-- (GEM Direct IIBCC Chairman + Vetter + Payment Consignee/Buyer).
--
-- NOTE: sd_site_settings only had PRIMARY KEY(ID), so earlier runs
-- of "INSERT IGNORE ... (setting_name, '', '1')" inserted duplicate
-- rows with empty values even when the key already held a real value.
-- SITE_GET_ALL iterates rows and the last one wins, so the form would
-- render those dropdowns blank even though a good row existed.
--
-- Remediation (safe to run multiple times):
--   1. Dedup: for any setting_name with multiple rows, keep the one
--      with a non-empty setting_value (largest MAX(LENGTH)); delete
--      the rest. If all rows are empty, keep the smallest ID.
--   2. Add a UNIQUE key on setting_name so future INSERT IGNORE
--      actually ignores.
--   3. Seed the 4 new keys idempotently.
-- ==========================================================

-- 1. Dedup — prefer rows with a non-empty setting_value
DELETE s1 FROM `sd_site_settings` s1
INNER JOIN `sd_site_settings` s2
   ON s1.setting_name = s2.setting_name
  AND (
        -- s2 has a longer (non-empty) value → drop s1
        LENGTH(COALESCE(s2.setting_value, '')) > LENGTH(COALESCE(s1.setting_value, ''))
        -- tiebreaker: same length, prefer smaller ID → drop the larger
        OR ( LENGTH(COALESCE(s2.setting_value, '')) = LENGTH(COALESCE(s1.setting_value, ''))
             AND s2.ID < s1.ID )
      );

-- 2. Unique key so INSERT IGNORE behaves correctly next time
ALTER TABLE `sd_site_settings`
  ADD UNIQUE KEY `uq_site_settings_name` (`setting_name`);

-- 3. Idempotent seed
INSERT IGNORE INTO `sd_site_settings` (`setting_name`, `setting_value`, `created_by`)
VALUES
  ('gem_direct_chairman',  '', '1'),
  ('gem_direct_vetter',    '', '1'),
  ('gem_direct_consignee', '', '1'),
  ('gem_direct_buyer',     '', '1');

-- ==========================================================
-- sd_budget_type — add a budget_type column (CAPITAL / REVENUE)
--
-- Existing rows often have the type baked into budget_no like
--   "CAPITAL : 540100401020052 (MCMFCG - V433A - M&E)"
-- so we extract the prefix into the new column AND strip it from
-- budget_no so dropdown labels don't read "CAPITAL - CAPITAL : ...".
-- Rows that don't match are left with type=CAPITAL as a safe default.
-- ==========================================================
ALTER TABLE `sd_budget_type`
  ADD COLUMN `budget_type` VARCHAR(20) NULL AFTER `ID`;

-- 1. Extract CAPITAL prefix
UPDATE `sd_budget_type`
   SET `budget_type` = 'CAPITAL',
       `budget_no` = TRIM(SUBSTRING(`budget_no`, LOCATE(':', `budget_no`) + 1))
 WHERE `budget_type` IS NULL
   AND UPPER(TRIM(`budget_no`)) LIKE 'CAPITAL%:%';

-- 2. Extract REVENUE prefix
UPDATE `sd_budget_type`
   SET `budget_type` = 'REVENUE',
       `budget_no` = TRIM(SUBSTRING(`budget_no`, LOCATE(':', `budget_no`) + 1))
 WHERE `budget_type` IS NULL
   AND UPPER(TRIM(`budget_no`)) LIKE 'REVENUE%:%';

-- 3. Anything left that had no prefix → default to CAPITAL
UPDATE `sd_budget_type`
   SET `budget_type` = 'CAPITAL'
 WHERE `budget_type` IS NULL OR `budget_type` = '';

-- ==========================================================
-- sd_gem_direct_payment — HOS approval step
--
-- After a user submits payment details, the row now sits at status 10
-- ("Submitted — Waiting HOS"). The HOS either approves (→ 20 Completed),
-- rejects (→ 21), or sends it back for rework (→ 22). On rework the
-- user re-edits and re-submits, which flips status back to 10.
-- ==========================================================
ALTER TABLE `sd_gem_direct_payment`
  ADD COLUMN `hos_id`      INT(11) NULL AFTER `buyer_id`,
  ADD COLUMN `hos_remarks` TEXT    NULL AFTER `hos_id`,
  ADD COLUMN `hos_time`    DATETIME NULL AFTER `hos_remarks`;

ALTER TABLE `sd_gem_direct_payment`
  ADD INDEX `idx_payment_hos` (`hos_id`);

-- ==========================================================
-- Legacy value rewrites
-- Old sd_gem_direct rows have free-text indent_no (e.g. "123") and
-- daily-serial iibcc_no (e.g. "GEM-20260422-002"). Convert them to the
-- new formats:
--   indent_no → GEM/MC&MFCG/YYYY/MM/NNN  (serial per month of created_time)
--   iibcc_no  → {CAP|REV}/GEM/YYYY/MM/NNN (serial per month of iibcc_chairman_time,
--                                          CAP/REV from sd_budget_type.budget_type)
-- Only rows that don't already match the new format are touched, so
-- re-running this block is safe.
-- ==========================================================
UPDATE `sd_gem_direct` AS t1
INNER JOIN (
  SELECT t2.ID,
         CONCAT(
           'GEM/MC&MFCG/',
           DATE_FORMAT(t2.created_time, '%Y/%m/'),
           LPAD(
             (SELECT COUNT(*) FROM `sd_gem_direct` t3
                WHERE YEAR(t3.created_time)  = YEAR(t2.created_time)
                  AND MONTH(t3.created_time) = MONTH(t2.created_time)
                  AND (t3.created_time < t2.created_time
                       OR (t3.created_time = t2.created_time AND t3.ID <= t2.ID))
             ), 3, '0'
           )
         ) AS new_indent
  FROM `sd_gem_direct` t2
  WHERE t2.indent_no IS NULL
     OR t2.indent_no = ''
     OR t2.indent_no NOT LIKE 'GEM/MC&MFCG/%'
) AS src ON src.ID = t1.ID
SET t1.indent_no = src.new_indent;

UPDATE `sd_gem_direct` AS t1
INNER JOIN (
  SELECT t2.ID,
         CONCAT(
           'GEM/',
           DATE_FORMAT(COALESCE(t2.iibcc_chairman_time, t2.created_time), '%Y/%m/'),
           LPAD(
             (SELECT COUNT(*) FROM `sd_gem_direct` t3
                WHERE t3.iibcc_no IS NOT NULL AND t3.iibcc_no <> ''
                  AND YEAR(COALESCE(t3.iibcc_chairman_time, t3.created_time))
                      = YEAR(COALESCE(t2.iibcc_chairman_time, t2.created_time))
                  AND MONTH(COALESCE(t3.iibcc_chairman_time, t3.created_time))
                      = MONTH(COALESCE(t2.iibcc_chairman_time, t2.created_time))
                  AND (
                    COALESCE(t3.iibcc_chairman_time, t3.created_time)
                      < COALESCE(t2.iibcc_chairman_time, t2.created_time)
                    OR ( COALESCE(t3.iibcc_chairman_time, t3.created_time)
                         = COALESCE(t2.iibcc_chairman_time, t2.created_time)
                         AND t3.ID <= t2.ID )
                  )
             ), 3, '0'
           )
         ) AS new_iibcc
  FROM `sd_gem_direct` t2
  WHERE t2.iibcc_no IS NOT NULL
    AND t2.iibcc_no <> ''
    -- Skip rows already in the simple new format: "GEM/YYYY/MM/NNN"
    AND t2.iibcc_no NOT REGEXP '^GEM/[0-9]{4}/[0-9]{2}/[0-9]{3}$'
) AS src ON src.ID = t1.ID
SET t1.iibcc_no = src.new_iibcc;

COMMIT;
