<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Site\Helpers;

use Core\BaseHelper;
use Core\Helpers\SmartConst;
use Core\Helpers\SmartGeneral;
use Site\view\GemDirectPdf;


//
use Site\Helpers\TableHelper as Table;

/**
 * Description of Data
 * 
 *  class helps to get the data from post with specified type 
 *
 * @author kms
 */
class GemDirectHelper extends BaseHelper
{

    const schema = [

        "indent_no" => SmartConst::SCHEMA_VARCHAR,
        "item_brief_description" => SmartConst::SCHEMA_VARCHAR,
        "head_of_account" => SmartConst::SCHEMA_VARCHAR,
        "estimate_source" => SmartConst::SCHEMA_VARCHAR,
        "cost" => SmartConst::SCHEMA_VARCHAR,
        "gem_id_item" => SmartConst::SCHEMA_VARCHAR,
        "justification_purchase" => SmartConst::SCHEMA_VARCHAR,
        "cctv_system" => SmartConst::SCHEMA_VARCHAR,
        "dvr_cctv_system" => SmartConst::SCHEMA_VARCHAR,
        "quantity" => SmartConst::SCHEMA_VARCHAR,
        "unit" => SmartConst::SCHEMA_VARCHAR,
        "total_cost" => SmartConst::SCHEMA_VARCHAR,
        "sd_mt_userdb_id" => SmartConst::SCHEMA_CUSER_ID,
        "hos_id" => SmartConst::SCHEMA_CUSER_ID,
        "hos_remarks" => SmartConst::SCHEMA_TEXT,
        "hos_time" => SmartConst::SCHEMA_CTIME,
        "hod_id" => SmartConst::SCHEMA_CUSER_ID,
        "hod_remarks" => SmartConst::SCHEMA_TEXT,
        "hod_time" => SmartConst::SCHEMA_CTIME,
        "ad_id" => SmartConst::SCHEMA_CUSER_ID,
        "ad_remarks" => SmartConst::SCHEMA_TEXT,
        "ad_time" => SmartConst::SCHEMA_CTIME,
        "gd_id" => SmartConst::SCHEMA_CUSER_ID,
        "gd_remarks" => SmartConst::SCHEMA_TEXT,
        "gd_time" => SmartConst::SCHEMA_CTIME,
        "financial_approval_id" => SmartConst::SCHEMA_CUSER_ID,
        "financial_approval_remarks" => SmartConst::SCHEMA_TEXT,
        "financial_approval_time" => SmartConst::SCHEMA_CTIME,
        // IIBCC chairman
        "iibcc_no" => SmartConst::SCHEMA_VARCHAR,
        "iibcc_chairman_id" => SmartConst::SCHEMA_CUSER_ID,
        "iibcc_chairman_remarks" => SmartConst::SCHEMA_TEXT,
        "iibcc_chairman_time" => SmartConst::SCHEMA_CTIME,
        // Vetter
        "vetter_user_id" => SmartConst::SCHEMA_INTEGER,
        "vetter_assigned_by" => SmartConst::SCHEMA_CUSER_ID,
        "vetter_assigned_time" => SmartConst::SCHEMA_CTIME,
        "vetter_remarks" => SmartConst::SCHEMA_TEXT,
        "vetter_time" => SmartConst::SCHEMA_CTIME,

        "status" => SmartConst::SCHEMA_INTEGER,
        "last_modified_by" => SmartConst::SCHEMA_CUSER_ID,
        "last_modified_time" => SmartConst::SCHEMA_CTIME,
        "created_time" => SmartConst::SCHEMA_CDATETIME,
        "doc_loc" => SmartConst::SCHEMA_TEXT,
        "detailed_specification" => SmartConst::SCHEMA_VARCHAR,
        // Optional free-text remarks from the requester. Shown on the
        // View / Edit dialogs alongside justification.
        "remarks" => SmartConst::SCHEMA_TEXT,


    ];

    /**
     * 
     */
    const validations = [


        // indent_no is generated server-side on insert, so no user validation.

        "item_brief_description" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter item brief description"
            ]
        ],

        "head_of_account" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please select head of account"
            ]
        ],

        "estimate_source" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter estimate source"
            ]
        ],

        "cost" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter cost"
            ]
        ],

        // gem_id_item is optional — only filled when the item is actually on GeM.

        "justification_purchase" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter justification for purchase"
            ]
        ],

        "quantity" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter quantity"
            ]
        ],

        "unit" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter unit"
            ]
        ],


        "status" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Status is required"
            ]
        ],
        "uploaded_file" => [
            [
                "type" => SmartConst::VALID_FILE_REQUIRED,
                "msg" => "Please Upload the Document"
            ],
            [
                "type" => SmartConst::VALID_FILE_TYPE,
                "msg" => "Only pdf is allowed",
                "ext" => ["pdf"]
            ]
        ],



    ];
    // file handling 
    const FILE_FOLDER = "gemdirect";
    const FILE_NAME = "file";
    public function getFullFile($id)
    {
        return self::FILE_FOLDER . DS . $id . DS . self::FILE_NAME;
    }

    /**
     * 
     */
    public function insert(array $columns, array $data)
    {
        return $this->insertDb(self::schema, Table::GEM_DIRECT, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::GEM_DIRECT, $columns, $data, $id);
    }
    /**
     * 
     */
    public function getAllData($sql = "", $data_in = [], $group_by = "", $count = false)
    {
        // $from = Table::GEM_DIRECT . " t1 
        // INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID 
        //  LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.hos_id  
        //  LEFT JOIN " . Table::USERS . " t13 ON t13.ID = t1.financial_approval_id
        //  LEFT JOIN " . Table::BUDGET_TYPE . " t15 ON t15.ID = t1.head_of_account
        // ";
         $from = Table::GEM_DIRECT . " t1
         INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID

         LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.hos_id
         LEFT JOIN " . Table::ORGANISATION . " t21 ON t11.sd_org_id = t21.ID

         LEFT JOIN " . Table::USERS . " t13 ON t13.ID = t1.financial_approval_id
         LEFT JOIN " . Table::ORGANISATION . " t23 ON t13.sd_org_id = t23.ID

         LEFT JOIN " . Table::USERS . " t30 ON t30.ID = t1.iibcc_chairman_id
         LEFT JOIN " . Table::USERS . " t31 ON t31.ID = t1.vetter_user_id

         LEFT JOIN " . Table::USERS . " t40 ON t40.ID = t1.hod_id
         LEFT JOIN " . Table::USERS . " t41 ON t41.ID = t1.ad_id
         LEFT JOIN " . Table::USERS . " t42 ON t42.ID = t1.gd_id

         LEFT JOIN " . Table::BUDGET_TYPE . " t15 ON t15.ID = t1.head_of_account
         LEFT JOIN " . Table::ORGANISATION . " t20 ON t2.sd_org_id = t20.ID
         LEFT JOIN " . Table::ORGANISATION . " t25 ON t25.ID = t20.sd_org_id
        ";


        // $select = [
        //     "t1.*,t2.ename as created_by",
        //     "t11.ename as hos_name",
        //     "t13.ename as financial_approval_name",
        //     "t15.budget_no as head_of_account",
        //     "t2.emailid as created_by_email",
        //     "t2.mobile_no as created_by_mobile_no",
        //     "t2.designation as created_by_designation",
        // ];
          $select = [
    // all base proposal columns (indent_no, cost, quantity, status, etc.)
    "t1.*",
    // columns the list table needs explicitly — guarantees they're always returned
    "t1.ID as ID",
    "t1.indent_no",
    "t1.iibcc_no",
    "t1.gem_id_item",
    "t1.cost",
    "t1.quantity",
    "t1.unit",
    "t1.total_cost",
    "t1.estimate_source",
    "t1.status",
    "t1.created_time",
    "t1.iibcc_chairman_id",
    "t1.iibcc_chairman_remarks",
    "t1.iibcc_chairman_time",
    "t1.vetter_user_id",
    "t1.vetter_remarks",
    "t1.vetter_time",
    // joined user / org / budget labels
    "t2.ename as created_by",
    "t11.ename as hos_name",
    "t13.ename as financial_approval_name",
    "t30.ename as iibcc_chairman_name",
    "t31.ename as vetter_user_name",
    "t40.ename as hod_name",
    "t41.ename as ad_name",
    "t42.ename as gd_name",
    "t21.sd_org_name as hos_org_desc",
    "t23.sd_org_name as financial_approval_org_desc",
    "CASE
        WHEN t15.budget_type IS NOT NULL AND t15.budget_type <> ''
        THEN CONCAT(t15.budget_type, ' - ', t15.budget_no)
        ELSE t15.budget_no
     END as head_of_account",
    "t15.budget_type as head_of_account_type",
    "t15.budget_no as head_of_account_no",
    "t2.emailid as created_by_email",
    "t2.mobile_no as created_by_mobile_no",
    "t2.designation as created_by_designation",
    "t20.sd_org_name as sd_org_id_desc",
    "t25.sd_org_name as hod_org_desc",
     "t2.intercome_number as created_by_intercome",
     "t2.euserid as created_by_icno",
     ];

        $order_by = "t1.created_time DESC";
        // Ensure $sql is not empty to avoid SQL syntax errors
        if (empty($sql)) {
            $sql = "1=1";
        }
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], $count);

    }
    /**
     * 
     */
    public function getOneData($id)
    {
        // $from = Table::GEM_DIRECT . " t1 
        // INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID 
        //  LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.hos_id  
        //  LEFT JOIN " . Table::USERS . " t13 ON t13.ID = t1.financial_approval_id
        //  LEFT JOIN " . Table::BUDGET_TYPE . " t15 ON t15.ID = t1.head_of_account
        // ";
         $from = Table::GEM_DIRECT . " t1
         INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID

         LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.hos_id
         LEFT JOIN " . Table::ORGANISATION . " t21 ON t11.sd_org_id = t21.ID

         LEFT JOIN " . Table::USERS . " t13 ON t13.ID = t1.financial_approval_id
         LEFT JOIN " . Table::ORGANISATION . " t23 ON t13.sd_org_id = t23.ID

         LEFT JOIN " . Table::USERS . " t30 ON t30.ID = t1.iibcc_chairman_id
         LEFT JOIN " . Table::USERS . " t31 ON t31.ID = t1.vetter_user_id

         LEFT JOIN " . Table::USERS . " t40 ON t40.ID = t1.hod_id
         LEFT JOIN " . Table::USERS . " t41 ON t41.ID = t1.ad_id
         LEFT JOIN " . Table::USERS . " t42 ON t42.ID = t1.gd_id

         LEFT JOIN " . Table::BUDGET_TYPE . " t15 ON t15.ID = t1.head_of_account
         LEFT JOIN " . Table::ORGANISATION . " t20 ON t2.sd_org_id = t20.ID
         LEFT JOIN " . Table::ORGANISATION . " t25 ON t25.ID = t20.sd_org_id
        ";


        $select = [
            "t1.*",
            "t1.iibcc_no",
            "t1.total_cost",
            "t2.ename as created_by",
            "t11.ename as hos_name",
            "t13.ename as financial_approval_name",
            "t30.ename as iibcc_chairman_name",
            "t31.ename as vetter_user_name",
            "t40.ename as hod_name",
            "t41.ename as ad_name",
            "t42.ename as gd_name",
            "t21.sd_org_name as hos_org_desc",
            "t23.sd_org_name as financial_approval_org_desc",
            "CASE
                WHEN t15.budget_type IS NOT NULL AND t15.budget_type <> ''
                THEN CONCAT(t15.budget_type, ' - ', t15.budget_no)
                ELSE t15.budget_no
             END as head_of_account",
            "t15.budget_type as head_of_account_type",
            "t15.budget_no as head_of_account_no",
            "t2.emailid as created_by_email",
            "t2.mobile_no as created_by_mobile_no",
            "t2.designation as created_by_designation",
            "t20.sd_org_name as sd_org_id_desc",
            "t25.sd_org_name as hod_org_desc",
            "t2.intercome_number as created_by_intercome",
            "t2.euserid as created_by_icno",
        ];



        $sql = "t1.ID=:ID";
        $data_in = ["ID" => $id];
        $group_by = "";
        $order_by = "";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, true, []);
    }
    /**
     * 
     */
    public function getTempWithUserID($id)
    {
        $from = Table::GEM_DIRECT;
        $select = ["*"];
        $sql = "sd_mt_userdb_id=:ID";
        $data_in = ["ID" => $id];
        $group_by = "";
        $order_by = "";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, true, []);
    }
    /**
     * 
     */
    public function deleteOneId($id)
    {
        $from = Table::GEM_DIRECT;
        $this->deleteId($from, $id);
    }

    public function getCount($type)
    {
        $sql = "DATE(t1.created_time)=CURRENT_DATE()";
        if ($type == 1) {
            $sql = "MONTH(t1.created_time)=" . SmartGeneral::getMonth();
        } else if ($type == 2) {
            $sql = "YEAR(t1.created_time)=" . SmartGeneral::getYear();
        }
        $data = $this->getAllData($sql, [], "", true);
        return isset($data) && isset($data->total_count) ? $data->total_count : 0;
    }


    public function getCountByYear($year)
    {
        $select = ["COUNT(name) AS rfid_card, MONTH(last_modified_time) AS month"];
        $from = Table::GEM_DIRECT;
        $sql = "YEAR(last_modified_time) =:year";
        $group_by = "MONTH(last_modified_time)";    // No GROUP BY keyword
        $order_by = "month";
        $data_in = ["year" => $year];
        $count = $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], false);
        $electrical_count = array_fill(0, 12, 0);
        foreach ($count as $elec) {
            $month = intval($elec->month);
            $eleCount = intval($elec->rfid_card);
            $electrical_count[$month - 1] = $eleCount;
        }
        $elec_count_by_year = array_values($electrical_count);
        return $elec_count_by_year;
    }


    public function getCountByStatus()
    {
        $sql = "status=10";
        $data = $this->getAllData($sql, [], "", true);
        return isset($data) && isset($data[0]) ? $data[0]->total_count : 0;
    }
    //generateCommiteeIibccNumber
    public function generateCommiteeIibccNumber()
    {
        $from = Table::GEM_DIRECT;
        $select = ["COUNT(*) as count"];
        $sql = "DATE(created_time) = CURDATE()";
        $result = $this->getAll($select, $from . " t1", $sql, "", "", [], true);
        $count = (int) ($result->count ?? 0) + 1; // Changed from array to object access
        $number = str_pad($count, 3, '0', STR_PAD_LEFT);
        $date = date("Ymd");
        return "IIB-$date-$number";
    }

    public function getCountStatus($status_sql)
    {
        $select = ["COUNT(*) AS total_count"];
        $from = Table::GEM_DIRECT;
        $sql = "status IN (" . $status_sql . ")";
        $data = $this->getAll($select, $from, $sql, "", "", [], true);
        return isset($data->total_count) ? (int) $data->total_count : 0;
    }


    // getone status tracker (proposal stage only; payment lives in a separate table)
    //
    // Each role's `codes` is the set of statuses where the proposal is
    // "at" that role. Rework codes (40, 24, 29) belong to the role that
    // ISSUED the rework — that way the tracker can highlight which step
    // bounced the proposal back, while the proposal itself is back at the
    // user (handled separately in the loop below).
    // HOD/AD/GD only enter the chain when total_cost exceeds the lower
    // level's own approval limit (see GemDirectController::updateApprovalHos/
    // Hod/Ad — escalation is amount-driven, cascading through the org
    // hierarchy). When a proposal is approved by a lower level within its
    // own limit it jumps straight to 15 (waiting Chairman), skipping the
    // higher levels entirely — those groups then never match 15 and are
    // simply never "current" for that proposal, same as any role a
    // proposal never passes through.
    //
    // The IIBCC Chairman decides TWICE whenever a Vetter is involved: once
    // to assign the Vetter (status 16), then again for the final decision
    // once the Vetter approves (status 17 -> 19/20/29). Those two decisions
    // are modelled as separate tracker steps ('IIBCC Chairman' and 'IIBCC
    // Chairman - Final Approval') built dynamically in getStatusTracker(),
    // not as a static property, because which one owns the terminal codes
    // 19/20/29 depends on whether a Vetter was actually engaged for this
    // proposal — see buildChairmanVetterGroups().
    public static $REWORK_CODES = [40, 24, 29, 43, 46, 49];
    public static $REJECT_CODES = [14, 19, 42, 45, 48];

    // Codes that mean "awaiting this role's decision" rather than "this
    // role already decided". A step showing as `is_current` on one of
    // these must NOT also show as `is_completed` — that combination would
    // render as a plain finished/green step, hiding the fact that nothing
    // has actually happened here yet. (14/19/20/29/40/42/43/45/46/48/49
    // etc. are all *outcomes* — the role in question already acted — so
    // completed-on-current is correct for those and left alone.)
    public static $WAITING_CODES = [16, 17, 41, 44, 47];

    // Builds the Chairman/Vetter portion of the tracker. A Vetter is NOT a
    // default step in the workflow — the Chairman decides, per proposal,
    // whether to approve/reject/rework directly or route it through a
    // Vetter first (see the "Assign to Vetter" option on the Chairman's
    // decision form). So the Vetter + "Final Approval" steps only appear
    // once that choice has actually been made (a Vetter is assigned, or
    // the proposal is currently sitting at/through that stage) — never as
    // a speculative "might happen" step while the Chairman's first review
    // is still pending.
    //
    // 'IIBCC Chairman' (first) is given NO codes of its own once a Vetter
    // is in play — by that point its own decision (to assign) is already
    // in the past, so it should never itself become `is_current` again.
    // Using 16 here (deliberately, in an earlier version) collided with
    // Vetter's own "waiting" code and double-lit both steps at once.
    private function buildChairmanVetterGroups($currentStatus, $vetterEngaged)
    {
        $terminal = [19, 20, 29];
        $vetterInPlay = $vetterEngaged || in_array($currentStatus, [16, 17, 24], true);

        if (!$vetterInPlay) {
            return ['IIBCC Chairman' => $terminal];
        }
        return [
            'IIBCC Chairman' => [],
            'Vetter' => [16, 24],
            'IIBCC Chairman - Final Approval' => array_merge([17], $terminal),
        ];
    }

    // $totalCost, when given, drops the HOD/AD/GD steps the proposal will
    // never actually need — e.g. a ₹50,000 proposal only ever needs HOS
    // before the Chairman, so showing HOD/AD/GD as upcoming steps would be
    // misleading. Thresholds mirror the escalation checks in
    // updateApprovalHos/Hod/Ad (same SmartSiteSettings keys/defaults).
    // $vetterUserId, when set, means a Vetter was assigned at some point —
    // see buildChairmanVetterGroups().
    public function getStatusTracker($currentStatus, $createdBy = null, $totalCost = null, $vetterUserId = null)
    {
        $tracker = [];
        $foundCurrent = false;
        $rejectFound = false;

        $isReworkOverall = in_array($currentStatus, self::$REWORK_CODES);
        $isRejectOverall = in_array($currentStatus, self::$REJECT_CODES);

        $skipLabels = [];
        if ($totalCost !== null) {
            $hos_max = floatval(\Core\Helpers\SmartSiteSettings::getSetting("gem_direct_hos_max", 50000));
            $hod_max = floatval(\Core\Helpers\SmartSiteSettings::getSetting("gem_direct_hod_max", 100000));
            $ad_max = floatval(\Core\Helpers\SmartSiteSettings::getSetting("gem_direct_ad_max", 250000));
            if ($totalCost <= $hos_max) $skipLabels = ['HOD', 'AD', 'GD'];
            else if ($totalCost <= $hod_max) $skipLabels = ['AD', 'GD'];
            else if ($totalCost <= $ad_max) $skipLabels = ['GD'];
        }

        $groups = array_merge(
            [
                'User Submission' => [10],                      // 10 = submitted
                'HOS' => [15, 14, 40],                          // 15 approve, 14 reject, 40 rework
                'HOD' => [41, 42, 43],                          // 41 waiting, 42 reject, 43 rework
                'AD' => [44, 45, 46],                           // 44 waiting, 45 reject, 46 rework
                'GD' => [47, 48, 49],                           // 47 waiting, 48 reject, 49 rework
            ],
            $this->buildChairmanVetterGroups($currentStatus, !empty($vetterUserId))
        );
        $vetterFinalLabel = 'IIBCC Chairman - Final Approval';

        foreach ($groups as $label => $codes) {
            // Skip steps this proposal's amount will never reach, UNLESS
            // it's already sitting there (e.g. thresholds changed after
            // submission) — never hide the proposal's actual current/reject
            // step.
            if (in_array($label, $skipLabels, true) && !in_array($currentStatus, $codes, true)) {
                continue;
            }
            $isAtRole = in_array($currentStatus, $codes);

            // Mark this role as the rejecter when the current code is a
            // reject/rework that originates from this role. (Prevents
            // Vetter / Chairman both lighting up on shared codes 16/17.)
            $isReject = false;
            if (in_array($currentStatus, self::$REJECT_CODES) && in_array($currentStatus, $codes)) {
                $isReject = true;
            } else if ($currentStatus === 40 && $label === 'HOS') {
                $isReject = true;
            } else if ($currentStatus === 43 && $label === 'HOD') {
                $isReject = true;
            } else if ($currentStatus === 46 && $label === 'AD') {
                $isReject = true;
            } else if ($currentStatus === 49 && $label === 'GD') {
                $isReject = true;
            } else if ($currentStatus === 29 && in_array($label, ['IIBCC Chairman', $vetterFinalLabel], true) && in_array(29, $codes, true)) {
                $isReject = true;
            } else if ($currentStatus === 24 && $label === 'Vetter') {
                $isReject = true;
            }

            // Tracker "current" indicator:
            //  - On a rework status the proposal is back at User Submission,
            //    so User is current AND the bouncing role is current
            //    (highlighted as reject).
            //  - On a reject status only the rejecting role is current.
            //  - Otherwise the role(s) holding the code are current.
            if ($isReworkOverall) {
                $isCurrent = ($label === 'User Submission') || $isReject;
            } else if ($isRejectOverall) {
                $isCurrent = $isReject;
            } else {
                $isCurrent = $isAtRole;
            }

            if ($isReject) {
                $rejectFound = true;
            }

            // Completion: roles before the current/reject point are done,
            // anything after is pending. A role that's current because
            // we're merely WAITING on its decision (16/17/41/44/47) is not
            // "completed" — nothing has happened there yet — so it stays
            // is_current + NOT completed until it actually decides.
            if ($isReject) {
                $isCompleted = false;
            } elseif ($rejectFound) {
                $isCompleted = false;
            } elseif ($isCurrent) {
                $isCompleted = !in_array($currentStatus, self::$WAITING_CODES, true);
                $foundCurrent = true;
            } elseif (!$foundCurrent) {
                $isCompleted = true;
            } else {
                $isCompleted = false;
            }

            // Custom label for User Submission
            $displayLabel = ($label === 'User Submission' && $createdBy) ? $createdBy : $label;

            $tracker[] = [
                'status' => $codes[0] ?? $currentStatus,
                'label' => $displayLabel,
                'is_current' => $isCurrent,
                'is_completed' => $isCompleted,
                // Both spellings supplied — different frontends bind to
                // different names. Keep them in sync.
                'is_reject' => $isReject,
                'is_rejected' => $isReject,
            ];
        }

        return [
            'current_status' => $currentStatus,
            'status_list' => $tracker
        ];
    }

    //report status tracker purpose
    public static $STATUS_GROUPED_NEW = [
        'Submitted (Waiting for HOS)' => [10],
        'HOS Approved' => [15],
        'HOS Reject' => [14],
        'HOS Rework' => [40],
        'Waiting for HOD' => [41],
        'HOD Reject' => [42],
        'HOD Rework' => [43],
        'Waiting for AD' => [44],
        'AD Reject' => [45],
        'AD Rework' => [46],
        'Waiting for GD' => [47],
        'GD Reject' => [48],
        'GD Rework' => [49],
        'Sent to Vetter' => [16],
        'Vetter Approved' => [17],
        'Vetter Rework' => [24],
        'Chairman Rework' => [29],
        'Chairman Reject' => [19],
        'Chairman Approved' => [20],
    ];
    public function getStatusTrackerNew($currentStatus, $createdBy = null)
    {
        $tracker = [];
        $foundCurrent = false;
        $rejectFound = false;

        foreach (self::$STATUS_GROUPED_NEW as $label => $codes) {

            $isCurrent = in_array($currentStatus, $codes);

            // Check rework (all odd -1 values like 14,19,24,29)
            $isReject = ($codes[0] % 5 == 4);

            if ($isReject && $isCurrent) {
                $rejectFound = true;
            }

            // Completed logic
            if ($isReject && $isCurrent) {
                $isCompleted = false;
            } elseif ($rejectFound) {
                $isCompleted = false;
            } elseif ($isCurrent) {
                $isCompleted = true;
                $foundCurrent = true;
            } elseif (!$foundCurrent) {
                $isCompleted = true;
            } else {
                $isCompleted = false;
            }

            // Replace label for first stage (Submitted)
            if ($codes[0] == 10 && $createdBy) {
                $label = $createdBy . " (Submitted)";
            }

            $tracker[] = [
                'status' => $codes[0],
                'label' => $label,
                'is_current' => $isCurrent,
                'is_completed' => $isCompleted,
                'is_reject' => $isReject
            ];
        }

        return [
            'current_status' => $currentStatus,
            'status_list' => $tracker
        ];
    }
    //html generation for pdf
    public function generateRfidCardPdf($id, $data)
    {
        //  Generate HTML from your PDF layout class
        $html = GemDirectPdf::getHtml($data);
        //  Send HTML to cURL for PDF generation
        // $this->initiate_curl($html, $id);
        // echo $html;
    }
    
    //gem direct report
    public function GemDirectReport($start_date, $end_date, $indent_no, $head_of_account)
{
    $select = [
        "t1.*",
        "t2.ename AS created_by",
        "t11.ename AS hos_name",
        "t13.ename AS financial_approval_name",
        "t15.budget_no AS head_of_account",
        "t2.emailid AS created_by_email",
        "t2.mobile_no AS created_by_mobile_no",
        "t2.designation AS created_by_designation"
    ];

    $from = Table::GEM_DIRECT . " t1
        INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID
        LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.hos_id
        LEFT JOIN " . Table::USERS . " t13 ON t13.ID = t1.financial_approval_id
        LEFT JOIN " . Table::BUDGET_TYPE . " t15 ON t15.ID = t1.head_of_account
    ";

    $conditions = [];
    $data_in = [];

    if (!empty($start_date) && !empty($end_date)) {
        $conditions[] = "DATE(t1.created_time) BETWEEN :start_date AND :end_date";
        $data_in["start_date"] = $start_date;
        $data_in["end_date"]   = $end_date;
    }

    if (!empty($indent_no)) {
        $conditions[] = "t1.indent_no = :indent_no";
        $data_in["indent_no"] = $indent_no;
    }

    if (!empty($head_of_account)) {
        $conditions[] = "t1.head_of_account = :head_of_account";
        $data_in["head_of_account"] = $head_of_account;
    }

    $sql = count($conditions) ? implode(" AND ", $conditions) : "1=1";

    $order_by = "t1.created_time DESC";

    return $this->getAll($select, $from, $sql, "", $order_by, $data_in, false, [], false);
}

    /**
     * Get users assigned to the role stored under a given site-settings key
     * (e.g. 'gem_direct_vetter'). Mirrors the existing user-role lookup
     * pattern used by UserHelper::getUsersFromRoleIndex.
     */
    public function getUsersByRoleKey($role_key)
    {
        $role_id = \Core\Helpers\SmartSiteSettings::getSetting($role_key);
        if (empty($role_id)) return [];

        $from = Table::USERS . " t1
            INNER JOIN " . Table::USERROLE . " t3 ON t1.ID = t3.sd_mt_userdb_id";
        $select = ["t1.ID as value", "t1.ename as label"];
        $sql = "t3.sd_mt_role_id = :ID";
        $data_in = ["ID" => $role_id];
        return $this->getAll($select, $from, $sql, "", "t1.ename", $data_in, false, []);
    }

    /**
     * Pending counts per role dashboard, for the home screen cards.
     *
     * $hos_org_ids/$hod_org_ids/$ad_org_ids/$gd_org_ids are the sets of org
     * IDs the logged-in user heads as SH/DH/AD/GD respectively; pass an
     * empty array for users who don't hold that role so the count falls to 0.
     */
    public function getPendingCounts($user_id, array $hos_org_ids = [], array $hod_org_ids = [], array $ad_org_ids = [], array $gd_org_ids = [])
    {
        $out = new \stdClass();
        $out->user_pending = $this->countByOwnerAndStatuses($user_id, [10, 40, 29, 24, 43, 46, 49]);
        $out->hos_pending = $this->countHosPending($user_id, $hos_org_ids, [10]);
        $out->hod_pending = $this->countHosPending($user_id, $hod_org_ids, [41]);
        $out->ad_pending = $this->countHosPending($user_id, $ad_org_ids, [44]);
        $out->gd_pending = $this->countHosPending($user_id, $gd_org_ids, [47]);
        $out->chairman_pending = $this->countByStatuses([15, 17]);
        $out->vetter_pending = $this->countVetterPending($user_id);
        // proposals approved (20) that have no payment row yet
        $out->payment_pending = $this->countPaymentPending($user_id);
        return $out;
    }

    // Shared by HOS/HOD/AD/GD pending counts — filters proposals raised by
    // users under the given org-head's sub-org tree, plus proposals the
    // org-head raised themselves (self-raised proposals still need to clear
    // that head's own approval queue).
    private function countHosPending($user_id, array $org_ids, array $statuses)
    {
        if (empty($statuses)) return 0;
        // Mirror the getAll() case 'hos' filter: proposals from users in the
        // HOS's sub-org tree, plus proposals the HOS raised themselves. If
        // the user heads no orgs and no $user_id is given, count is 0.
        if (empty($org_ids) && $user_id < 1) return 0;
        $org_ids_sql = empty($org_ids) ? "-1" : implode(",", array_map('intval', $org_ids));
        $select = ["COUNT(*) AS total_count"];
        $from = Table::GEM_DIRECT . " t1
            INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID";
        $sql = "t1.status IN (" . implode(",", array_map('intval', $statuses)) . ")
            AND (t2.sd_org_id IN (" . $org_ids_sql . ") OR t1.sd_mt_userdb_id = :uid)";
        $data = $this->getAll($select, $from, $sql, "", "", ["uid" => $user_id], true);
        return isset($data->total_count) ? (int) $data->total_count : 0;
    }

    private function countPaymentPending($user_id)
    {
        if ($user_id < 1) return 0;
        $select = ["COUNT(*) AS total_count"];
        $from = Table::GEM_DIRECT . " t1
            LEFT JOIN " . Table::GEM_DIRECT_PAYMENT . " p1 ON p1.sd_gem_direct_id = t1.ID";
        $sql = "t1.sd_mt_userdb_id = :uid AND t1.status = 20 AND p1.ID IS NULL";
        $data = $this->getAll($select, $from, $sql, "", "", ["uid" => $user_id], true);
        return isset($data->total_count) ? (int) $data->total_count : 0;
    }

    private function countByStatuses(array $statuses)
    {
        if (empty($statuses)) return 0;
        $select = ["COUNT(*) AS total_count"];
        $from = Table::GEM_DIRECT;
        $sql = "status IN (" . implode(",", array_map('intval', $statuses)) . ")";
        $data = $this->getAll($select, $from, $sql, "", "", [], true);
        return isset($data->total_count) ? (int) $data->total_count : 0;
    }

    private function countByOwnerAndStatuses($user_id, array $statuses)
    {
        if (empty($statuses) || $user_id < 1) return 0;
        $select = ["COUNT(*) AS total_count"];
        $from = Table::GEM_DIRECT;
        $sql = "sd_mt_userdb_id = :uid AND status IN (" . implode(",", array_map('intval', $statuses)) . ")";
        $data = $this->getAll($select, $from, $sql, "", "", ["uid" => $user_id], true);
        return isset($data->total_count) ? (int) $data->total_count : 0;
    }

    private function countVetterPending($user_id)
    {
        if ($user_id < 1) return 0;
        $select = ["COUNT(*) AS total_count"];
        $from = Table::GEM_DIRECT;
        $sql = "vetter_user_id = :uid AND status = 16";
        $data = $this->getAll($select, $from, $sql, "", "", ["uid" => $user_id], true);
        return isset($data->total_count) ? (int) $data->total_count : 0;
    }

    /**
     * Indent number issued at proposal creation time.
     * Format: GEM/MC&MFCG/YYYY/MM/NNN   (serial resets every month)
     *
     * Next serial is the highest trailing number for the current YYYY/MM
     * prefix + 1, so we never clash even if rows were deleted.
     */
    public function generateGemDirectIndentNumber()
    {
        $year  = date("Y");
        $month = date("m");
        $prefix = "GEM/MC&MFCG/$year/$month/";
        $serial = $this->nextSerialForPrefix("indent_no", $prefix);
        return $prefix . str_pad($serial, 3, '0', STR_PAD_LEFT);
    }

    /**
     * IIBCC number allotted after chairman's final approval.
     * Format: {CAP|REV}/GEM/YYYY/MM/NNN
     *
     * Each head-of-account TYPE keeps its OWN monthly counter — a
     * CAPITAL approval will never bump the REV serial and vice-versa.
     * The serial is computed by counting rows that satisfy BOTH:
     *   1. iibcc_no LIKE '<prefix>%'
     *   2. the proposal's actual head_of_account.budget_type == this type
     * That dual check protects against legacy / mismatched-prefix rows
     * (e.g. data created when the prefix lookup was buggy and CAPITAL
     * proposals got a CAP prefix that no longer matches their HOA).
     *
     * $headOfAccountId is the FK to sd_budget_type. Pass `0` and provide
     * $budgetType directly when the caller already knows it (common
     * because getOneData() rewrites the head_of_account column to a
     * label string and loses the raw FK in the process).
     */
    public function generateGemDirectIibccNumber($headOfAccountId = 0, $budgetType = '')
    {
        $bt = $budgetType !== '' ? $budgetType : $this->getBudgetTypeForHead($headOfAccountId);
        $bt = strtoupper(trim((string)$bt));
        $code = ($bt === 'REVENUE' || $bt === 'REV') ? 'REV' : 'CAP';
        $typeMatch = ($code === 'REV') ? 'REVENUE' : 'CAPITAL';

        $year  = date("Y");
        $month = date("m");
        $prefix = "$code/GEM/$year/$month/";
        $serial = $this->nextSerialForPrefixAndType("iibcc_no", $prefix, $typeMatch);
        return $prefix . str_pad($serial, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Variant of nextSerialForPrefix() that also requires the proposal's
     * head-of-account budget_type to match $typeMatch. Used by IIBCC
     * number allotment so that even if a legacy row has a CAP prefix on
     * a REVENUE-type HOA, it doesn't inflate the CAP counter.
     */
    private function nextSerialForPrefixAndType($column, $prefix, $typeMatch)
    {
        $offset = strlen($prefix) + 1;
        $result = $this->getAll(
            ["MAX(CAST(SUBSTRING(t1.$column, $offset) AS UNSIGNED)) as max_serial"],
            Table::GEM_DIRECT . " t1
                LEFT JOIN " . Table::BUDGET_TYPE . " t15 ON t15.ID = t1.head_of_account",
            "t1.$column LIKE :pfx AND UPPER(TRIM(t15.budget_type)) = :bt",
            "", "",
            ["pfx" => $prefix . "%", "bt" => strtoupper($typeMatch)],
            true
        );
        $max = isset($result->max_serial) ? intval($result->max_serial) : 0;
        return $max + 1;
    }

    private function getBudgetTypeForHead($id)
    {
        $id = intval($id);
        if ($id < 1) return '';
        $row = $this->getAll(
            ["budget_type"], Table::BUDGET_TYPE . " t1",
            "t1.ID = :id", "", "", ["id" => $id], true
        );
        return isset($row->budget_type) ? $row->budget_type : '';
    }

    /**
     * Looks at all {$column} values starting with $prefix and returns the
     * next serial number (max trailing integer + 1, or 1 when none match).
     * Prefix length is inlined into the SQL (derived server-side, never
     * from user input) so we don't bind a numeric SUBSTRING offset.
     */
    private function nextSerialForPrefix($column, $prefix)
    {
        $offset = strlen($prefix) + 1;
        $result = $this->getAll(
            ["MAX(CAST(SUBSTRING(t1.$column, $offset) AS UNSIGNED)) as max_serial"],
            Table::GEM_DIRECT . " t1",
            "t1.$column LIKE :pfx",
            "", "",
            ["pfx" => $prefix . "%"],
            true
        );
        $max = isset($result->max_serial) ? intval($result->max_serial) : 0;
        return $max + 1;
    }

}
