<?php

namespace Site\Helpers;

use Core\BaseHelper;
use Core\Helpers\SmartConst;

use Site\Helpers\TableHelper as Table;

/**
 * Payment stage for a GEM Direct purchase.
 * One row per approved sd_gem_direct (unique key on sd_gem_direct_id).
 */
class GemDirectPaymentHelper extends BaseHelper
{
    const schema = [
        "sd_gem_direct_id" => SmartConst::SCHEMA_INTEGER,

        // Contract / delivery
        "gem_contract_no" => SmartConst::SCHEMA_VARCHAR,
        "gem_contract_date" => SmartConst::SCHEMA_VARCHAR,
        "schedule_delivery_date" => SmartConst::SCHEMA_VARCHAR,
        "tax_invoice_no" => SmartConst::SCHEMA_VARCHAR,
        "tax_invoice_date" => SmartConst::SCHEMA_VARCHAR,
        "actual_delivery_date" => SmartConst::SCHEMA_VARCHAR,

        // Money
        "payment_value" => SmartConst::SCHEMA_VARCHAR,
        "liquidated_damages" => SmartConst::SCHEMA_VARCHAR,
        "payable_value" => SmartConst::SCHEMA_VARCHAR,

        // Firm
        "firm_name" => SmartConst::SCHEMA_VARCHAR,
        "firm_address" => SmartConst::SCHEMA_TEXT,
        "firm_contact" => SmartConst::SCHEMA_VARCHAR,
        "firm_email" => SmartConst::SCHEMA_VARCHAR,
        "firm_bank_account" => SmartConst::SCHEMA_VARCHAR,
        "firm_ifsc" => SmartConst::SCHEMA_VARCHAR,

        // Comments / authorities
        "user_comments" => SmartConst::SCHEMA_TEXT,
        "approving_authority" => SmartConst::SCHEMA_VARCHAR,
        "consignee_id" => SmartConst::SCHEMA_INTEGER,
        "buyer_id" => SmartConst::SCHEMA_INTEGER,

        // Files
        "file_gem_indent_approved" => SmartConst::SCHEMA_TEXT,
        "file_seller_invoice" => SmartConst::SCHEMA_TEXT,
        "file_gem_invoice" => SmartConst::SCHEMA_TEXT,
        "file_gem_contract_order" => SmartConst::SCHEMA_TEXT,
        "file_gem_sanction_order" => SmartConst::SCHEMA_TEXT,
        "file_gem_crac" => SmartConst::SCHEMA_TEXT,
        "file_material_inspection" => SmartConst::SCHEMA_TEXT,

        // HOS approval step (added with payment HOS workflow)
        "hos_id"      => SmartConst::SCHEMA_INTEGER,
        "hos_remarks" => SmartConst::SCHEMA_TEXT,
        "hos_time"    => SmartConst::SCHEMA_CTIME,

        // Lifecycle
        "status" => SmartConst::SCHEMA_INTEGER,
        "sd_mt_userdb_id" => SmartConst::SCHEMA_CUSER_ID,
        "created_time" => SmartConst::SCHEMA_CDATETIME,
        "last_modified_by" => SmartConst::SCHEMA_CUSER_ID,
        "last_modified_time" => SmartConst::SCHEMA_CTIME,
    ];

    const validations = [
        "gem_contract_no"   => [[ "type" => SmartConst::VALID_REQUIRED, "msg" => "GeM Contract No required" ]],
        "gem_contract_date" => [[ "type" => SmartConst::VALID_REQUIRED, "msg" => "GeM Contract Date required" ]],
        "tax_invoice_no"    => [[ "type" => SmartConst::VALID_REQUIRED, "msg" => "Tax Invoice No required" ]],
        "tax_invoice_date"  => [[ "type" => SmartConst::VALID_REQUIRED, "msg" => "Tax Invoice Date required" ]],
        "payment_value"     => [[ "type" => SmartConst::VALID_REQUIRED, "msg" => "Value required" ]],
        "firm_name"         => [[ "type" => SmartConst::VALID_REQUIRED, "msg" => "Firm Name required" ]],
        "firm_bank_account" => [[ "type" => SmartConst::VALID_REQUIRED, "msg" => "Bank Account Number required" ]],
        "firm_ifsc"         => [[ "type" => SmartConst::VALID_REQUIRED, "msg" => "IFSC required" ]],
        // user_comments is intentionally optional — it's the only free-text
        // field the requester may leave blank.
        // approving_authority is no longer collected — single-stage HOS approval.
    ];

    // File upload target folder (under data path).
    // Files live at: gemdirect_payment/<sd_gem_direct_id>/<field>
    const FILE_FOLDER = "gemdirect_payment";

    public function getFileFolder($gem_direct_id)
    {
        return self::FILE_FOLDER . DS . intval($gem_direct_id);
    }

    public function insert(array $columns, array $data)
    {
        return $this->insertDb(self::schema, Table::GEM_DIRECT_PAYMENT, $columns, $data);
    }

    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::GEM_DIRECT_PAYMENT, $columns, $data, $id);
    }

    /**
     * Joined view combining payment row with the underlying proposal +
     * consignee / buyer / submitter names for the PDF template.
     */
    private function baseFrom()
    {
        return Table::GEM_DIRECT_PAYMENT . " t1
            INNER JOIN " . Table::GEM_DIRECT . " g1 ON g1.ID = t1.sd_gem_direct_id
            INNER JOIN " . Table::USERS . " u1 ON u1.ID = g1.sd_mt_userdb_id
            LEFT JOIN " . Table::USERS . " u2 ON u2.ID = t1.consignee_id
            LEFT JOIN " . Table::USERS . " u3 ON u3.ID = t1.buyer_id
            LEFT JOIN " . Table::BUDGET_TYPE . " b1 ON b1.ID = g1.head_of_account
            LEFT JOIN " . Table::ORGANISATION . " o1 ON u1.sd_org_id = o1.ID
        ";
    }

    private function baseSelect()
    {
        return [
            "t1.*",
            "g1.indent_no",
            "g1.iibcc_no",
            "g1.item_brief_description",
            "g1.cost",
            "g1.quantity",
            "g1.unit",
            "g1.total_cost",
            "g1.status as proposal_status",
            "u1.ename as created_by",
            "u1.designation as created_by_designation",
            "u1.emailid as created_by_email",
            "u1.mobile_no as created_by_mobile_no",
            "u2.ename as consignee_name",
            "u2.designation as consignee_designation",
            "u3.ename as buyer_name",
            "u3.designation as buyer_designation",
            "b1.budget_no as head_of_account",
            "o1.sd_org_name as sd_org_id_desc",
        ];
    }

    /**
     * List entries for the payment page. Primary source is sd_gem_direct
     * (status >= 20 = approved) so rows without a payment entry still show
     * as "Pending Payment". LEFT JOIN sd_gem_direct_payment for the rest.
     *
     * Modes:
     *   'user'  → only proposals owned by the logged-in user
     *   'admin' → all proposals
     *   'hos'   → only proposals with a payment row awaiting HOS action
     *
     * Payment status codes (sd_gem_direct_payment.status):
     *   null/empty  Pending Payment  (no row yet)
     *   10          Submitted — Waiting HOS
     *   20          HOS Approved / Completed
     *   21          HOS Rejected
     *   22          HOS Rework (user can re-edit and re-submit)
     */
    public function getAllData($mode = "admin", $user_id = 0)
    {
        $from = Table::GEM_DIRECT . " g1
            INNER JOIN " . Table::USERS . " u1 ON u1.ID = g1.sd_mt_userdb_id
            LEFT JOIN " . Table::GEM_DIRECT_PAYMENT . " t1 ON t1.sd_gem_direct_id = g1.ID
            LEFT JOIN " . Table::USERS . " u2 ON u2.ID = t1.consignee_id
            LEFT JOIN " . Table::USERS . " u3 ON u3.ID = t1.buyer_id
            LEFT JOIN " . Table::USERS . " u4 ON u4.ID = t1.hos_id
            LEFT JOIN " . Table::BUDGET_TYPE . " b1 ON b1.ID = g1.head_of_account
        ";
        $select = [
            "g1.ID as sd_gem_direct_id",
            "t1.ID as payment_id",
            "g1.indent_no",
            "g1.iibcc_no",
            "g1.item_brief_description",
            "g1.cost",
            "g1.quantity",
            "g1.unit",
            "g1.status as proposal_status",
            "g1.total_cost",
            "g1.created_time as proposal_created_time",
            "u1.ename as created_by",
            "b1.budget_no as head_of_account",
            "t1.gem_contract_no",
            "t1.gem_contract_date",
            "t1.tax_invoice_no",
            "t1.tax_invoice_date",
            "t1.schedule_delivery_date",
            "t1.actual_delivery_date",
            "t1.payment_value",
            "t1.liquidated_damages",
            "t1.payable_value",
            "t1.firm_name",
            "t1.status as payment_status",
            "t1.hos_id",
            "t1.hos_remarks",
            "t1.hos_time",
            "u4.ename as hos_name",
            "t1.created_time",
            "u2.ename as consignee_name",
            "u3.ename as buyer_name",
        ];
        $sql = "g1.status >= 20";
        $data_in = [];
        if ($mode === "user" && $user_id > 0) {
            $sql .= " AND g1.sd_mt_userdb_id = :uid";
            $data_in["uid"] = $user_id;
        } else if ($mode === "hos") {
            // HOS only sees payment rows at status 10 (waiting for their action)
            $sql .= " AND t1.status = 10";
        }
        return $this->getAll(
            $select,
            $from,
            $sql,
            "",
            "g1.created_time DESC",
            $data_in,
            false,
            []
        );
    }

    /**
     * Report query for the Budget Reports → "GEM Direct Payment" view.
     * Same joined source as getAllData("admin"), with optional filters:
     *   - start_date / end_date: anchored on g1.created_time (proposal date)
     *     so rows still pending payment (no t1 row yet) remain visible.
     *   - payment_status: "" / null = no filter, "pending" = no payment row,
     *     numeric (10/20/21/22) = exact payment status match.
     *   - indent_no / firm_name: case-insensitive LIKE.
     *   - head_of_account: matched as either the budget id (b1.ID) or its
     *     budget_no string, since the frontend dropdown returns the id but
     *     the row already carries the textual budget_no.
     */
    public function GemDirectPaymentReport(
        $start_date,
        $end_date,
        $payment_status = "",
        $indent_no = "",
        $firm_name = "",
        $head_of_account = ""
    ) {
        $from = Table::GEM_DIRECT . " g1
            INNER JOIN " . Table::USERS . " u1 ON u1.ID = g1.sd_mt_userdb_id
            LEFT JOIN " . Table::GEM_DIRECT_PAYMENT . " t1 ON t1.sd_gem_direct_id = g1.ID
            LEFT JOIN " . Table::USERS . " u2 ON u2.ID = t1.consignee_id
            LEFT JOIN " . Table::USERS . " u3 ON u3.ID = t1.buyer_id
            LEFT JOIN " . Table::USERS . " u4 ON u4.ID = t1.hos_id
            LEFT JOIN " . Table::BUDGET_TYPE . " b1 ON b1.ID = g1.head_of_account
        ";
        $select = [
            "g1.ID as sd_gem_direct_id",
            "t1.ID as payment_id",
            "g1.indent_no",
            "g1.iibcc_no",
            "g1.item_brief_description",
            "g1.cost",
            "g1.quantity",
            "g1.unit",
            "g1.status as proposal_status",
            "g1.total_cost",
            "g1.created_time as proposal_created_time",
            "u1.ename as created_by",
            "b1.budget_no as head_of_account",
            "t1.gem_contract_no",
            "t1.gem_contract_date",
            "t1.tax_invoice_no",
            "t1.tax_invoice_date",
            "t1.schedule_delivery_date",
            "t1.actual_delivery_date",
            "t1.payment_value",
            "t1.liquidated_damages",
            "t1.payable_value",
            "t1.firm_name",
            "t1.status as payment_status",
            "t1.hos_id",
            "t1.hos_remarks",
            "t1.hos_time",
            "u4.ename as hos_name",
            "t1.created_time",
            "u2.ename as consignee_name",
            "u3.ename as buyer_name",
        ];

        $conditions = ["g1.status >= 20"];
        $data_in = [];

        if (!empty($start_date) && !empty($end_date)) {
            $conditions[] = "DATE(g1.created_time) BETWEEN :start_date AND :end_date";
            $data_in["start_date"] = $start_date;
            $data_in["end_date"] = $end_date;
        }

        if ($payment_status !== "" && $payment_status !== null) {
            if ($payment_status === "pending") {
                $conditions[] = "t1.ID IS NULL";
            } else {
                $conditions[] = "t1.status = :payment_status";
                $data_in["payment_status"] = intval($payment_status);
            }
        }

        if (!empty($indent_no)) {
            $conditions[] = "g1.indent_no LIKE :indent_no";
            $data_in["indent_no"] = "%" . $indent_no . "%";
        }

        if (!empty($firm_name)) {
            $conditions[] = "t1.firm_name LIKE :firm_name";
            $data_in["firm_name"] = "%" . $firm_name . "%";
        }

        if (!empty($head_of_account)) {
            // Budget id from the select dropdown OR the textual budget_no.
            $conditions[] = "(b1.ID = :hoa_id OR b1.budget_no = :hoa_no)";
            $data_in["hoa_id"] = intval($head_of_account);
            $data_in["hoa_no"] = $head_of_account;
        }

        $sql = implode(" AND ", $conditions);

        return $this->getAll(
            $select,
            $from,
            $sql,
            "",
            "g1.created_time DESC",
            $data_in,
            false,
            []
        );
    }

    /**
     * Pending counts for the HOS payment dashboard card.
     */
    public function getHosPendingCount()
    {
        $select = ["COUNT(*) as total_count"];
        $from = Table::GEM_DIRECT_PAYMENT;
        $sql = "status = 10";
        $row = $this->getAll($select, $from, $sql, "", "", [], true);
        return isset($row->total_count) ? (int) $row->total_count : 0;
    }

    public function getOneByGemDirectId($gem_direct_id)
    {
        return $this->getAll(
            $this->baseSelect(),
            $this->baseFrom(),
            "t1.sd_gem_direct_id = :gid",
            "",
            "",
            ["gid" => $gem_direct_id],
            true,
            []
        );
    }

    public function getOneData($id)
    {
        return $this->getAll(
            $this->baseSelect(),
            $this->baseFrom(),
            "t1.ID = :ID",
            "",
            "",
            ["ID" => $id],
            true,
            []
        );
    }
}
