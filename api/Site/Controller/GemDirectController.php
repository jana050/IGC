<?php

namespace Site\Controller;

use Core\BaseController;
use Core\Helpers\SmartGeneral;
use Core\Helpers\SmartAuthHelper;
use Core\Helpers\SmartSiteSettings;
use Core\Helpers\SmartFileHelper;
use Core\Helpers\SmartPdfHelper;
use Core\Helpers\SmartData;

use Site\Helpers\OrganisationHelper;
use Site\Helpers\GemDirectHelper;
use Site\Helpers\GemDirectPaymentHelper;
use Site\View\GemDirectPdf;


class GemDirectController extends BaseController
{
    private GemDirectHelper $_gem_direct_helper;
    private GemDirectPaymentHelper $_payment_helper;
    private OrganisationHelper $_org_helper;


    function __construct($params)
    {
        parent::__construct($params);
        //
        $this->_gem_direct_helper = new GemDirectHelper($this->db);
        $this->_payment_helper = new GemDirectPaymentHelper($this->db);
        $this->_org_helper = new OrganisationHelper($this->db);

    }
    // 
    /** 10 = submitted-techwait  , 10=hos_wait,14=hos_reject,15=hos_process , 15=hod wait,hod process=20
     * 
     */
    public function insert()
    {
        // Columns that go through the rule-based validator. Every entry
        // here MUST have a matching rule set in GemDirectHelper::validations
        // or the validator throws "Invalid Rules Set". Optional fields like
        // gem_id_item are deliberately excluded here and added directly to
        // the insert list below.
        $validated_columns = [
            "item_brief_description",
            "head_of_account",
            "estimate_source",
            "cost",
            "justification_purchase",
            "quantity",
            "unit",
        ];
        $this->_gem_direct_helper->validate(
            GemDirectHelper::validations, $validated_columns, $this->post
        );

        // compute total_cost server-side (auto-calculated, not validated)
        $cost = isset($this->post["cost"]) ? floatval($this->post["cost"]) : 0;
        $qty = isset($this->post["quantity"]) ? floatval($this->post["quantity"]) : 0;
        $this->post["total_cost"] = number_format($cost * $qty, 2, '.', '');

        // indent_no is always generated server-side so format & uniqueness
        // are guaranteed (frontend shows a preview but never decides it).
        $this->post["indent_no"] = $this->_gem_direct_helper->generateGemDirectIndentNumber();

        // Full insert list = validated user fields + optional/server-set fields.
        $columns = $validated_columns;
        $columns[] = "gem_id_item";     // optional user field
        $columns[] = "indent_no";       // server-generated
        $columns[] = "total_cost";
        $columns[] = "created_time";
        $columns[] = "sd_mt_userdb_id";
        $columns[] = "status";
        $this->post["status"] = 10;
        $columns[] = "detailed_specification";
        $columns[] = "remarks";         // optional free-text from requester


        // insert
        $id = $this->_gem_direct_helper->insert($columns, $this->post);

        // process the file
        $file_path = $this->_gem_direct_helper->getFullFile($id);
        if (isset($_FILES["uploaded_file"])) {
            // move the uploaded file to path 
            $stored_file_path = SmartFileHelper::moveSingleFile("uploaded_file", $file_path);
            // update the file path in table
            $update_columns = ["doc_loc"];
            $update_data = ["doc_loc" => $stored_file_path];
            $this->_gem_direct_helper->update($update_columns, $update_data, $id);
        }

        // log
        $this->addLog("RAISED AN GEM DIRECT COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());

        $this->response($id);
    }

    /**
     * Preview of the indent number the next insert would receive.
     * Used by the form to show a read-only value before submission. The
     * final value is regenerated at insert() time so two users opening the
     * form simultaneously can't end up with the same serial.
     */
    public function nextIndentNo()
    {
        $this->response([
            "indent_no" => $this->_gem_direct_helper->generateGemDirectIndentNumber()
        ]);
    }

    /**
     *
     */
    public function update()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $columns = ["status"];
        // do validations
        $this->_gem_direct_helper->validate(GemDirectHelper::validations, $columns, $this->post);
        // add columns
        // $columns[] = "admin_remarks";
        $columns[] = "last_modified_by";
        $columns[] = "last_modified_time";


        // insert and get id
        $id = $this->_gem_direct_helper->update($columns, $this->post, $id);
        // process the file
        $file_path = $this->_gem_direct_helper->getFullFile($id);
        if (isset($_FILES["uploaded_file"])) {
            // move the uploaded file to path 
            $stored_file_path = SmartFileHelper::moveSingleFile("uploaded_file", $file_path);
            // update the file path in table
            $update_columns = ["doc_loc"];
            $update_data = ["doc_loc" => $stored_file_path];
            $this->_gem_direct_helper->update($update_columns, $update_data, $id);
        }

        // add log
        $this->addLog("UPDATED AN GEM DIRECT COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }

    public function getAll()
    {
        // check the mode received from router
        $sql = "";
        $data_in = [];
        $mode = isset($this->params["mode"]) ? $this->params["mode"] : "user";
        // $status = isset($this->params["status"]) ? $this->params["status"] : [100];
        $status = isset($this->params["status"]) && is_array($this->params["status"]) && count($this->params["status"]) > 0
            ? $this->params["status"]
            : [100]; // fallback if not set or empty

        $logged_id = SmartAuthHelper::getLoggedInId();
        switch ($mode) {
            // indicates the logged user data
            case 'user':
                $sql = "t1.sd_mt_userdb_id=:user_id";
                $data_in = ["user_id" => SmartAuthHelper::getLoggedInId()];
                break;
            case 'app':
                $sql = "t1.app_id=:user_id AND status IN (" . implode(",", $status) . ")";
                $data_in = ["user_id" => SmartAuthHelper::getLoggedInId()];
                break;
            case 'admin':
                $sql = "status IN (" . implode(",", $status) . ")";
                break;
            case 'hos':
                $sql = "t1.status IN (" . implode(",", $status) . ")";
                break;
            // case 'hos':
            //     $org_ids = $this->_org_helper->getSubOrdIds($logged_id, "SH");
            //     $sql = "t1.status IN (" . implode(",", $status) . ") AND t2.sd_org_id IN (" . implode(",", $org_ids) . ")";
            //     break;

            case 'financial_approval':
                $sql = "t1.status IN (" . implode(",", $status) . ")";
                break;

            case 'chairman':
                // all proposals waiting for chairman action
                $sql = "t1.status IN (" . implode(",", $status) . ")";
                break;

            case 'vetter':
                // only proposals assigned to THIS vetter
                $sql = "t1.vetter_user_id = :vetter_id AND t1.status IN (" . implode(",", $status) . ")";
                $data_in = ["vetter_id" => $logged_id];
                break;

            default:
                break;
        }
        $data = $this->_gem_direct_helper->getAllData($sql, $data_in);
        $this->response($data);
    }


    /**
     * 
     */

    public function getOne()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }

        // Get the main record
        $data = $this->_gem_direct_helper->getOneData($id);

        if (!$data) {
            \CustomErrorHandler::triggerInvalid("Record not found");
        }

        // Get tracker (pass created_by also)
        $statusTracker = $this->_gem_direct_helper->getStatusTracker($data->status, $data->created_by);

        // Add only status_list to the main data
        $data->status_list = $statusTracker['status_list'];

        // Send response
        $this->response($data);
    }





    /**
     * 
     */
    public function deleteOne()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        // insert and get id
        $this->_gem_direct_helper->deleteOneId($id);
        // add log
        $this->addLog("DELETED AN GEM DIRECT COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());
        //
        $out = new \stdClass();
        $out->msg = "Deleted Successfully";
        $this->response($out);
    }

    public function updateApprovalHos()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }

        $action = isset($this->post["status"]) ? $this->post["status"] : "";
        $remarks = isset($this->post["remarks"]) ? $this->post["remarks"] : "";

        // approve -> waiting Chairman (15)
        // reject  -> close file (14)
        // rework  -> back to user for restart (40)
        if ($action === "approve") {
            $status = 15;
        } else if ($action === "reject") {
            $status = 14;
        } else if ($action === "rework") {
            $status = 40;
        } else {
            \CustomErrorHandler::triggerInvalid("Invalid Action");
            return;
        }

        $columns = ["status", "hos_id", "hos_remarks", "hos_time"];
        $dt = [
            "status" => $status,
            "hos_remarks" => $remarks
        ];

        $id = $this->_gem_direct_helper->update($columns, $dt, $id);

        $logMap = [
            15 => "APPROVED GEM DIRECT BY HOS",
            14 => "REJECTED GEM DIRECT BY HOS",
            40 => "SENT GEM DIRECT FOR REWORK BY HOS",
        ];
        $this->addLog($logMap[$status], $remarks, SmartAuthHelper::getLoggedInUserName());

        $this->response($id);
    }


    public function updateApprovalfinancialApprover()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }

        $status = isset($this->post["status"]) ? $this->post["status"] : "";
        $remarks = isset($this->post["remarks"]) ? $this->post["remarks"] : "";

        // Determine status: Iibcc approval = 20, rejection = 19
        $status = ($status === "approve") ? 20 : (($status === "reject") ? 19 : 0);


        if ($status < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid Action");
        }
        $columns = ["status", "financial_approval_id", "financial_approval_remarks", "financial_approval_time"];
        $dt = [
            "status" => $status,
            "financial_approval_remarks" => $remarks,
        ];

        $id = $this->_gem_direct_helper->update($columns, $dt, $id);

        $logMsg = $status == 20 ? "APPROVED GEM DIRECT BY IIBCC APPROVER" : "SENT GEM DIRECT CARD BY IIBCC APPROVER TO REWORK";
        $this->addLog($logMsg, $remarks, SmartAuthHelper::getLoggedInUserName());

        $this->response($id);
    }

    public function getPdf()
    {
        // $id = 10;
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $data = $this->_gem_direct_helper->getOneData($id);

        // var_dump($data);
        // exit();
        $this->_gem_direct_helper->generateRfidCardPdf($id, $data);
        $html = GemDirectPdf::getHtml((array) $data);
        // var_dump($html);
        // exit();
        $path = "gemdirect" . DS . $id . DS . "gemdirect.pdf";
        SmartPdfHelper::genPdf($html, $path, ["pagesize" => "A4"]);
        $full_path = SmartFileHelper::getDataPath() . $path;
        $this->responseFileBase64($full_path);
    }

    //
    public function updateUser()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        // Columns the user actually submits AND that have a rule defined in
        // GemDirectHelper::validations. indent_no is intentionally excluded
        // here — it's generated server-side at insert() and has no
        // user-validation rule, so listing it would trip "Invalid Rules Set
        // indent_no" in the validator. The frontend may still send it; we
        // just don't validate it and don't update it (the value is locked
        // to the original at insert).
        $input_columns = [
            "item_brief_description",
            "head_of_account",
            "estimate_source",
            "cost",
            "justification_purchase",
            "quantity",
            "unit",
        ];

        // validations (only for user-submitted fields)
        $this->_gem_direct_helper->validate(GemDirectHelper::validations, $input_columns, $this->post);
        // recompute total_cost (auto-calculated, not validated)
        $cost = isset($this->post["cost"]) ? floatval($this->post["cost"]) : 0;
        $qty = isset($this->post["quantity"]) ? floatval($this->post["quantity"]) : 0;
        $this->post["total_cost"] = number_format($cost * $qty, 2, '.', '');
        // final column list for the update (adds optional + server-set fields)
        $columns = $input_columns;
        $columns[] = "gem_id_item";   // optional user field, no validation rule
        $columns[] = "remarks";       // optional free-text remarks
        $columns[] = "total_cost";
        $columns[] = "last_modified_by";
        $columns[] = "last_modified_time";

        // Status handling — three signals decide whether to reset to 10:
        //  1. Current status must be a "back at user" state (10 = initial,
        //     40/29/24 = HOS/Chairman/Vetter rework). Anything past that
        //     point is mid-workflow (15/16/17/19/20) and editing it should
        //     never silently pull it back to the start.
        //  2. Caller must be the proposal owner (sd_mt_userdb_id match).
        //  3. Caller must NOT have ADMIN role (admin edits are in-place
        //     data fixes; workflow state is theirs to leave alone).
        // All three must be true to reset; otherwise status stays put.
        $existing = $this->_gem_direct_helper->getOneData($id);
        $currentStatus = isset($existing->status) ? intval($existing->status) : 0;
        $ownerId = isset($existing->sd_mt_userdb_id) ? intval($existing->sd_mt_userdb_id) : 0;
        $callerId = intval(SmartAuthHelper::getLoggedInId());
        $userActionableStates = [10, 24, 29, 40];
        $isOwner = ($ownerId > 0 && $ownerId === $callerId);
        $isAdmin = SmartAuthHelper::checkRole(["ADMIN"]);
        $isRework = in_array($currentStatus, $userActionableStates, true);

        if ($isRework && $isOwner && !$isAdmin) {
            $columns[] = "status";
            $this->post["status"] = 10;
        }
        // insert and get id
        $id = $this->_gem_direct_helper->update($columns, $this->post, $id);
        // process the file
        $file_path = $this->_gem_direct_helper->getFullFile($id);
        if (isset($_FILES["uploaded_file"])) {
            // move the uploaded file to path 
            $stored_file_path = SmartFileHelper::moveSingleFile("uploaded_file", $file_path);
            // update the file path in table
            $update_columns = ["doc_loc"];
            $update_data = ["doc_loc" => $stored_file_path];
            $this->_gem_direct_helper->update($update_columns, $update_data, $id);
        }

        // add log
        $this->addLog("UPDATED AN GEM DIRECT COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }
    //
    public function getDoc()
    {
        $id = isset($this->post["id"]) ? $this->post["id"] : 0;
        if ($id < 0) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $data = $this->_gem_direct_helper->getOneData($id);
        // 
        $pdf_path = $this->_gem_direct_helper->getFullFile($id) . ".pdf";
        //    echo $pdf_path;
        $this->responseFileBase64(SmartFileHelper::getDataPath() . $pdf_path);
    }

    // =========================================================
    // IIBCC Chairman approval
    //   15 + approve + total_cost > 50000  → 16 (assign to vetter)
    //   15 + approve + total_cost <= 50000 → 20 (final, generate IIBCC no)
    //   17 + approve                       → 20 (final, generate IIBCC no)
    //   reject → 19    rework → 29
    // =========================================================
    public function updateApprovalChairman()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }

        $action = isset($this->post["status"]) ? $this->post["status"] : "";
        $remarks = isset($this->post["remarks"]) ? $this->post["remarks"] : "";
        $vetter_user_id = isset($this->post["vetter_user_id"]) ? intval($this->post["vetter_user_id"]) : 0;

        $row = $this->_gem_direct_helper->getOneData($id);
        if (!$row) {
            \CustomErrorHandler::triggerInvalid("Record not found");
        }
        $current_status = intval($row->status);
        $total_cost = floatval($row->total_cost ?? 0);
        if ($total_cost <= 0) {
            $total_cost = floatval($row->cost ?? 0) * floatval($row->quantity ?? 0);
        }

        $columns = ["status", "iibcc_chairman_id", "iibcc_chairman_remarks", "iibcc_chairman_time"];
        $dt = ["iibcc_chairman_remarks" => $remarks];

        if ($action === "reject") {
            $dt["status"] = 19;
        } else if ($action === "rework") {
            $dt["status"] = 29;
        } else if ($action === "assign_vetter") {
            // Chairman explicitly routing through a vetter. Only valid from
            // the first chairman review (status 15); otherwise fall through
            // to the invalid-action error below.
            if ($current_status !== 15) {
                \CustomErrorHandler::triggerInvalid("Vetter can only be assigned during initial review");
                return;
            }
            if ($vetter_user_id < 1) {
                \CustomErrorHandler::triggerInvalid("Please select a Vetter");
            }
            $dt["status"] = 16;
            $columns[] = "vetter_user_id";
            $columns[] = "vetter_assigned_by";
            $columns[] = "vetter_assigned_time";
            $dt["vetter_user_id"] = $vetter_user_id;
        } else if ($action === "approve") {
            // Direct approval by the chairman — finalize to status 20 and
            // allot the IIBCC number straight away (no vetter step).
            // getOneData() aliases head_of_account to a "TYPE - budget_no"
            // label, so the raw FK is gone — we pass the already-loaded
            // head_of_account_type value directly to skip the redundant
            // (and broken) ID-based lookup.
            $dt["status"] = 20;
            $columns[] = "iibcc_no";
            $dt["iibcc_no"] = $this->_gem_direct_helper->generateGemDirectIibccNumber(
                0,
                isset($row->head_of_account_type) ? $row->head_of_account_type : ''
            );
        } else {
            \CustomErrorHandler::triggerInvalid("Invalid Action");
            return;
        }

        $id = $this->_gem_direct_helper->update($columns, $dt, $id);

        $logMap = [
            16 => "CHAIRMAN ASSIGNED GEM DIRECT TO VETTER",
            20 => "CHAIRMAN APPROVED GEM DIRECT (IIBCC No allotted)",
            29 => "CHAIRMAN SENT GEM DIRECT FOR REWORK",
            19 => "CHAIRMAN REJECTED GEM DIRECT",
        ];
        $this->addLog($logMap[$dt["status"]] ?? "CHAIRMAN ACTION", $remarks, SmartAuthHelper::getLoggedInUserName());

        $this->response($id);
    }

    // =========================================================
    // Vetter approval
    //   approve → 17 (back to chairman)
    //   rework  → 24 (back to user for restart)
    // =========================================================
    public function updateApprovalVetter()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $action = isset($this->post["status"]) ? $this->post["status"] : "";
        $remarks = isset($this->post["remarks"]) ? $this->post["remarks"] : "";

        if ($action === "approve") {
            $status = 17;
        } else if ($action === "rework") {
            $status = 24;
        } else {
            \CustomErrorHandler::triggerInvalid("Invalid Action");
            return;
        }

        $columns = ["status", "vetter_remarks", "vetter_time"];
        $dt = ["status" => $status, "vetter_remarks" => $remarks];

        $id = $this->_gem_direct_helper->update($columns, $dt, $id);

        $logMsg = $status === 17
            ? "VETTER APPROVED GEM DIRECT - BACK TO CHAIRMAN"
            : "VETTER SENT GEM DIRECT FOR REWORK";
        $this->addLog($logMsg, $remarks, SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }

    // =========================================================
    // Vetter user list for the Chairman's "assign to vetter" dropdown
    // =========================================================
    public function vetterUserSelect()
    {
        $users = $this->_gem_direct_helper->getUsersByRoleKey('gem_direct_vetter');
        $this->response($users);
    }

    // Consignee / Buyer dropdowns on the payment form pull from whichever
    // role the admin pinned under Site Settings → Administrators
    // ('gem_direct_consignee' / 'gem_direct_buyer').
    public function consigneeUserSelect()
    {
        $this->response($this->_gem_direct_helper->getUsersByRoleKey('gem_direct_consignee'));
    }

    public function buyerUserSelect()
    {
        $this->response($this->_gem_direct_helper->getUsersByRoleKey('gem_direct_buyer'));
    }

    // =========================================================
    // Payment stage - stored in sd_gem_direct_payment
    // =========================================================
    //
    // Submits (or updates) a payment row tied to an approved sd_gem_direct.
    // Request param `sd_gem_direct_id` identifies the proposal; the payment
    // row is upserted by that id (one payment row per proposal).
    public function submitPayment()
    {
        $gem_direct_id = isset($this->post["sd_gem_direct_id"]) ? intval($this->post["sd_gem_direct_id"])
                       : (isset($this->post["id"]) ? intval($this->post["id"]) : 0);
        if ($gem_direct_id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid proposal ID");
        }

        // proposal must be approved (status 20+)
        $proposal = $this->_gem_direct_helper->getOneData($gem_direct_id);
        if (!$proposal) {
            \CustomErrorHandler::triggerInvalid("Proposal not found");
        }
        if (intval($proposal->status) < 20) {
            \CustomErrorHandler::triggerInvalid("Proposal must be approved before entering payment details");
        }

        // Validator walks this list and demands a matching rule set for
        // each entry — so ONLY list columns that actually have rules in
        // GemDirectPaymentHelper::validations. Everything else
        // (delivery dates, optional text, server-set fields, computed
        // values) is added directly to the upsert column list below.
        $validated_columns = [
            "gem_contract_no", "gem_contract_date",
            "tax_invoice_no", "tax_invoice_date",
            "payment_value",
            "firm_name", "firm_bank_account", "firm_ifsc",
            // user_comments is optional — listed in the upsert columns
            // below so it still gets persisted when the user does fill it.
        ];
        $this->_payment_helper->validate(
            GemDirectPaymentHelper::validations, $validated_columns, $this->post
        );

        // Full column list for the upsert.
        $columns = array_merge($validated_columns, [
            "sd_gem_direct_id",       // server-set from URL param
            "schedule_delivery_date", // optional / frontend-validated
            "actual_delivery_date",
            "liquidated_damages",     // server-recomputed
            "payable_value",          // server-recomputed
            "firm_address", "firm_contact", "firm_email",
            "consignee_id", "buyer_id",
            "user_comments",          // optional — persisted when present
        ]);

        // SmartDate sends dates as {year, month, day} arrays rather than
        // strings, so normalize to "YYYY-MM-DD" before doing anything with
        // them. This also lets the DB-layer store them correctly.
        foreach (["gem_contract_date", "schedule_delivery_date", "tax_invoice_date", "actual_delivery_date"] as $k) {
            if (isset($this->post[$k]) && is_array($this->post[$k])) {
                $y = (int)($this->post[$k]["year"]   ?? 0);
                $m = (int)($this->post[$k]["month"]  ?? 0);
                $d = (int)($this->post[$k]["day"]    ?? 0);
                $this->post[$k] = ($y && $m && $d)
                    ? sprintf("%04d-%02d-%02d", $y, $m, $d)
                    : "";
            }
        }

        // Server-side recompute LD (only if actual delivery > schedule) + payable
        $value = isset($this->post["payment_value"]) ? floatval($this->post["payment_value"]) : 0;
        $ld = isset($this->post["liquidated_damages"]) ? floatval($this->post["liquidated_damages"]) : 0;
        $schedule = isset($this->post["schedule_delivery_date"]) ? $this->post["schedule_delivery_date"] : "";
        $actual = isset($this->post["actual_delivery_date"]) ? $this->post["actual_delivery_date"] : "";
        if ($schedule && $actual) {
            $s = strtotime($schedule);
            $a = strtotime($actual);
            if ($s !== false && $a !== false && $a <= $s) {
                $ld = 0;
                $this->post["liquidated_damages"] = 0;
            }
        }
        $this->post["sd_gem_direct_id"] = $gem_direct_id;
        $this->post["payable_value"] = number_format(max(0, $value - $ld), 2, '.', '');

        // upsert: one payment row per proposal. Re-submissions after an
        // HOS rework (status 22) must go back to status 10 so the row
        // re-enters the HOS queue. Three conditions ALL required:
        //   1. Payment status must be 10 (just submitted) or 22 (rework).
        //      If it's 20 (completed) or 21 (rejected) we never bounce it.
        //   2. Caller must be the original payment submitter.
        //   3. Caller must NOT have ADMIN role (admin = in-place fix).
        $existing = $this->_payment_helper->getOneByGemDirectId($gem_direct_id);
        $paymentStatus = isset($existing->status) ? intval($existing->status) : 0;
        $ownerId = isset($existing->sd_mt_userdb_id) ? intval($existing->sd_mt_userdb_id) : 0;
        $callerId = intval(SmartAuthHelper::getLoggedInId());
        $userActionablePaymentStates = [10, 22];
        $isOwner = ($ownerId > 0 && $ownerId === $callerId);
        $isAdmin = SmartAuthHelper::checkRole(["ADMIN"]);
        $isRework = in_array($paymentStatus, $userActionablePaymentStates, true);

        if ($existing) {
            $columns[] = "last_modified_by";
            $columns[] = "last_modified_time";
            if ($isRework && $isOwner && !$isAdmin) {
                $columns[] = "status";
                $this->post["status"] = 10;
            }
            $payment_id = intval($existing->ID);
            $this->_payment_helper->update($columns, $this->post, $payment_id);
        } else {
            $columns[] = "status";
            $columns[] = "sd_mt_userdb_id";
            $columns[] = "created_time";
            $this->post["status"] = 10; // payment submitted — waiting HOS
            $payment_id = $this->_payment_helper->insert($columns, $this->post);
        }

        // File uploads under gemdirect_payment/<gem_direct_id>/<field>
        $fileFields = [
            "file_gem_indent_approved",
            "file_seller_invoice",
            "file_gem_invoice",
            "file_gem_contract_order",
            "file_gem_sanction_order",
            "file_gem_crac",
            "file_material_inspection",
        ];
        $fileUpdates = [];
        foreach ($fileFields as $f) {
            if (isset($_FILES[$f]) && !empty($_FILES[$f]["name"])) {
                $target = $this->_payment_helper->getFileFolder($gem_direct_id) . DS . $f;
                $stored = SmartFileHelper::moveSingleFile($f, $target);
                $fileUpdates[$f] = $stored;
            }
        }
        if (!empty($fileUpdates)) {
            $this->_payment_helper->update(array_keys($fileUpdates), $fileUpdates, $payment_id);
        }

        $this->addLog("GEM DIRECT PAYMENT DETAILS SUBMITTED", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($payment_id);
    }

    // Lists payment rows for the user/admin/hos payment pages.
    public function paymentGetAll()
    {
        $mode = isset($this->params["mode"]) ? $this->params["mode"] : "admin";
        $user_id = SmartAuthHelper::getLoggedInId();
        $data = $this->_payment_helper->getAllData($mode, $user_id);
        $this->response($data);
    }

    // =========================================================
    // HOS approval on a submitted payment.
    //   10 + approve → 20 (HOS approved / Payment completed)
    //   10 + reject  → 21 (HOS rejected, close file)
    //   10 + rework  → 22 (back to user to re-edit & re-submit)
    // =========================================================
    public function updateApprovalHosPayment()
    {
        $gem_direct_id = isset($this->post["sd_gem_direct_id"]) ? intval($this->post["sd_gem_direct_id"]) : 0;
        if ($gem_direct_id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid proposal ID");
        }
        $existing = $this->_payment_helper->getOneByGemDirectId($gem_direct_id);
        if (!$existing) {
            \CustomErrorHandler::triggerInvalid("Payment record not found");
        }
        $payment_id = intval($existing->ID);
        // getOneByGemDirectId() aliases the payment status as "status" (t1.*
        // plus t1.status as proposal_status is only in the list view).
        if (intval($existing->status) !== 10) {
            \CustomErrorHandler::triggerInvalid("Payment is not waiting for HOS action");
        }

        $action  = isset($this->post["status"])  ? $this->post["status"]  : "";
        $remarks = isset($this->post["remarks"]) ? $this->post["remarks"] : "";

        // Reject removed from HOS payment workflow — only Approve / Rework
        // are valid outcomes once payment details have been submitted.
        $status_map = ["approve" => 20, "rework" => 22];
        if (!isset($status_map[$action])) {
            \CustomErrorHandler::triggerInvalid("Invalid Action");
            return;
        }

        $columns = ["status", "hos_id", "hos_remarks", "hos_time"];
        $data = [
            "status"      => $status_map[$action],
            "hos_remarks" => $remarks,
        ];
        $this->_payment_helper->update($columns, $data, $payment_id);

        $log_map = [
            20 => "HOS APPROVED GEM DIRECT PAYMENT",
            22 => "HOS SENT GEM DIRECT PAYMENT FOR REWORK",
        ];
        $this->addLog($log_map[$status_map[$action]], $remarks, SmartAuthHelper::getLoggedInUserName());

        $this->response($payment_id);
    }

    // Gets one payment row. Accepts either payment ID or sd_gem_direct_id.
    // When no payment row exists yet, prefill a skeleton from the approved
    // proposal so the form can render the header summary (indent, item,
    // quantity, head_of_account, etc.) and default payment_value to the
    // proposal's total_cost.
    public function paymentGetOne()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        $gem_direct_id = isset($this->post["sd_gem_direct_id"]) ? intval($this->post["sd_gem_direct_id"]) : 0;

        $data = null;
        if ($gem_direct_id > 0) {
            $data = $this->_payment_helper->getOneByGemDirectId($gem_direct_id);
        } elseif ($id > 0) {
            $data = $this->_payment_helper->getOneData($id);
        }
        if (!$data && $gem_direct_id > 0) {
            $proposal = $this->_gem_direct_helper->getOneData($gem_direct_id);
            $data = new \stdClass();
            $data->sd_gem_direct_id = $gem_direct_id;
            if ($proposal) {
                foreach ([
                    "indent_no", "iibcc_no", "item_brief_description",
                    "cost", "quantity", "unit", "total_cost", "head_of_account",
                ] as $k) {
                    if (isset($proposal->$k)) $data->$k = $proposal->$k;
                }
                // Pre-populate the payment Value with the proposal total; the
                // user can still override it in the form.
                if (!empty($proposal->total_cost)) {
                    $data->payment_value = $proposal->total_cost;
                }
            }
        } else if (!$data) {
            $data = new \stdClass();
        }
        $this->response($data);
    }

    // =========================================================
    // Download a single uploaded payment document.
    // Params: sd_gem_direct_id, file (= one of the file_* field names)
    // =========================================================
    public function paymentFileDownload()
    {
        $allowed = [
            "file_gem_indent_approved",
            "file_seller_invoice",
            "file_gem_invoice",
            "file_gem_contract_order",
            "file_gem_sanction_order",
            "file_gem_crac",
            "file_material_inspection",
        ];
        $gem_direct_id = isset($this->post["sd_gem_direct_id"]) ? intval($this->post["sd_gem_direct_id"]) : 0;
        $file          = isset($this->post["file"]) ? $this->post["file"] : "";
        if ($gem_direct_id < 1 || !in_array($file, $allowed, true)) {
            \CustomErrorHandler::triggerInvalid("Invalid request");
        }
        $payment = $this->_payment_helper->getOneByGemDirectId($gem_direct_id);
        if (!$payment || empty($payment->{$file})) {
            \CustomErrorHandler::triggerInvalid("File not found");
        }
        // moveSingleFile() only returns basename(), so rebuild the path:
        // data_root + gemdirect_payment/<gem_direct_id>/<basename>
        $full_path = SmartFileHelper::getDataPath()
                   . $this->_payment_helper->getFileFolder($gem_direct_id)
                   . DS . $payment->{$file};
        $this->responseFileBase64($full_path);
    }

    // =========================================================
    // Payment release letter PDF (identified by sd_gem_direct_id)
    // =========================================================
    public function paymentPdfView()
    {
        $this->generateAndStreamPaymentPdf();
    }

    public function paymentPdfDownload()
    {
        $this->generateAndStreamPaymentPdf();
    }

    private function generateAndStreamPaymentPdf()
    {
        $gem_direct_id = isset($this->post["sd_gem_direct_id"]) ? intval($this->post["sd_gem_direct_id"])
                       : (isset($this->post["id"]) ? intval($this->post["id"]) : 0);
        if ($gem_direct_id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $data = $this->_payment_helper->getOneByGemDirectId($gem_direct_id);
        if (!$data) {
            \CustomErrorHandler::triggerInvalid("Payment record not found");
        }
        $html = \Site\view\GemDirectPaymentPdf::getHtml((array) $data);
        $path = "gemdirect_payment" . DS . $gem_direct_id . DS . "payment_release.pdf";
        SmartPdfHelper::genPdf($html, $path, ["pagesize" => "A4"]);
        $full_path = SmartFileHelper::getDataPath() . $path;
        $this->responseFileBase64($full_path);
    }

    // =========================================================
    // Dashboard pending counts (per role)
    // =========================================================
    public function pendingCount()
    {
        $user_id = SmartAuthHelper::getLoggedInId();
        $counts = $this->_gem_direct_helper->getPendingCounts($user_id);
        $this->response($counts);
    }







}
