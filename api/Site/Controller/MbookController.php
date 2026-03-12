<?php

namespace Site\Controller;

use Core\BaseController;
use Core\Helpers\SmartGeneral;
use Core\Helpers\SmartAuthHelper;
use Core\Helpers\SmartData;
use Core\Helpers\SmartFileHelper;
use Core\Helpers\SmartPdfHelper;
// use Mpdf\Tag\Table;
use Site\Helpers\MbookIssueHelper;
use Site\Helpers\MbookEntryHelper;
use Site\Helpers\OrganisationHelper;
use Core\Helpers\SmartSiteSettings;
use Site\Helpers\TableHelper as Table;
use Site\view\MbookIssuePdf;

class MbookController extends BaseController
{
    private MbookIssueHelper $_mbookIssue_helper;
    private MbookEntryHelper $_mbookEntry_helper;
    private OrganisationHelper $_org_helper;

    function __construct($params)
    {
        parent::__construct($params);
        // 
        $this->_mbookIssue_helper = new MbookIssueHelper($this->db);
        $this->_mbookEntry_helper = new MbookEntryHelper($this->db);
        $this->_org_helper = new OrganisationHelper($this->db);
    }

    /**
     * 
     */


    public function insert()
    {
        $columns = [
            "mbook_number",
            "date_of_issue",
            "title",
            "cc_number",
            "pan_number",
            "email",
            "mobile_no",
            "wages_count",
            // "salary_amount",
            "head_of_account",
            "indentor" // new column for mbook issue
        ];
        $this->post["mbook_number"] = $this->_mbookIssue_helper->generateMbookNumber();
        $this->post["paid_amount"] = 0;
        $this->post["balance_amount"] = $this->post["work_order_value"];

        // $this->post["designation"] = SmartData::post_select_value("designation");
        // do validations
        $this->_mbookIssue_helper->validate(MbookIssueHelper::validations, $columns, $this->post);
        // add other columns
        $columns[] = "created_time";
        $columns[] = "sd_mt_userdb_id";
        $columns[] = "status";
        $columns[] = "file_type";
        $columns[] = "name_of_work";
        $columns[] = "work_order_number";
        $columns[] = "work_order_value";
        $columns[] = "date_of_work_order";
        $columns[] = "budget_type";
        $columns[] = "budget_pin";
        $columns[] = "contact_name";
        $columns[] = "technical_sanction_number";
        $columns[] = "start_date";
        $columns[] = "end_date";
        $columns[] = "others";
        $columns[] = "technical_sanction_amount";
        $columns[] = "procurement_stage";
        // $this->post["status"] = 10;
         $this->post["status"] = 15; //Mbook Issue will directly be in completed state, It will NOT go to approval flow
        $columns[] = "paid_amount";
        $columns[] = "balance_amount";
        $columns[] = "description";
        $columns[]="contacter_address";
         $columns[]="uploaded_file";


        // insert and get id
        $id = $this->_mbookIssue_helper->insert($columns, $this->post);

        // process the file
        $file_path = $this->_mbookIssue_helper->getFullFile($id);
        if (isset($_FILES["uploaded_file"])) {
            // move the uploaded file to path 
            $stored_file_path = SmartFileHelper::moveSingleFile("uploaded_file", $file_path);
            // update the file path in table
            $update_columns = ["doc_loc"];
            $update_data = ["doc_loc" => $stored_file_path];
            $this->_mbookIssue_helper->update($update_columns, $update_data, $id);
        }

        // add log
        $this->addLog("RAISED AN MBOOK ISSUE", "", SmartAuthHelper::getLoggedInUserName());
        //
        $this->response($id);
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
        $this->_mbookIssue_helper->validate(MbookIssueHelper::validations, $columns, $this->post);
        // add columns
        $columns[] = "admin_remarks";
        $columns[] = "last_modified_by";
        $columns[] = "last_modified_time";
        $columns[] = "head_of_account";
        // insert and get id
        $id = $this->_mbookIssue_helper->update($columns, $this->post, $id);
        // add log
        $this->addLog("UPDATED AN MBOOK ISSUE", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }

    public function getAll()
    {
        // check the mode received from router
        $sql = "";
        $data_in = [];

        $mode = isset($this->params["mode"]) ? $this->params["mode"] : "user";
        $mode = isset($this->params["mode"]) ? $this->params["mode"] : "supervisor";

        $status = isset($this->params["status"]) ? $this->params["status"] : [100];
        switch ($mode) {
            // indicates the logged user data
            case 'user':
                $sql = "1=1";
                // $sql = "t1.sd_mt_userdb_id=:user_id";
                // $data_in = ["user_id" => SmartAuthHelper::getLoggedInId()];
                break;
            case 'app':
                $sql = "t1.app_id=:user_id AND status IN (" . implode(",", $status) . ")";
                $data_in = ["user_id" => SmartAuthHelper::getLoggedInId()];
                break;
            case 'admin':
                $sql = "status IN (" . implode(",", $status) . ")";
                break;
            // supervisor view: only requests awaiting this supervisor’s approval
            case 'supervisor':
                $sql = "status IN (" . implode(",", $status) . ")";
                break;
            // indentor view: only requests created by this indentor
            case 'indentor':
                $sql = "t1.indentor = :indentor AND status IN (" . implode(",", $status) . ")";
                $data_in = ["indentor" => SmartAuthHelper::getLoggedInId()];
                break;

            default:
                break;
        }
        $data = $this->_mbookIssue_helper->getAllData($sql, $data_in);
        // foreach ($data as $obj) {
        //             $obj->paid_amount = $obj->paid_amount_approved + $obj->paid_amount_processing;
        //              $obj->balance_amount = $obj->total_amount - $obj->paid_amount;
        //  }
        foreach ($data as $obj) {
        $approved = (int)($obj->paid_amount_approved ?? 0);
        $processing = (int)($obj->paid_amount_processing ?? 0);

        $obj->paid_amount = $approved + $processing;
        $obj->balance_amount = (int)$obj->work_order_value - $obj->paid_amount;
      }

        $this->response($data);
    }
    /**
     * 
     */
    /*
    public function getOne()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        // insert and get id
        $data = $this->_mbookIssue_helper->getOneData($id);
        $this->response($data);
    }
    */
   public function getOne()
   {
    $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
    if ($id < 1) {
        \CustomErrorHandler::triggerInvalid("Invalid ID");
    }

    $data = $this->_mbookIssue_helper->getOneData($id);

    if (!$data) {
        \CustomErrorHandler::triggerInvalid("Record not found");
    }

    // Attach tracker
    $statusTracker = $this->_mbookIssue_helper->getMbookStatusTracker($data->status, $data->created_by);
    $data->status_list = $statusTracker['status_list'];
   $approved = (int)($data->paid_amount_approved ?? 0);
  $processing = (int)($data->paid_amount_processing ?? 0);

   $data->paid_amount = $approved + $processing;
   $data->balance_amount = (int)$data->work_order_value - $data->paid_amount;


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
        $this->_mbookIssue_helper->deleteOneId($id);
        // add log
        $this->addLog("DELETED AN MBOOK ISSUE", "", SmartAuthHelper::getLoggedInUserName());
        //
        $out = new \stdClass();
        $out->msg = "Deleted Successfully";
        $this->response($out);
    }


    public function updateApp()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $action = isset($this->post["action"]) ? ($this->post["action"]) : "";

        // echo "action = " . $action;

        $columns = ["status", "app_time"];
        //
        $dt = ["status" => $action == "approve" ? 10 : 6];
        // do validations
        // $this->_mbookIssue_helper->validate(MbookIssueHelper::validations, $columns, $dt);
        // add columns
        $columns[] = "last_modified_by";
        $columns[] = "last_modified_time";
        // insert and get id
        $id = $this->_mbookIssue_helper->update($columns, $dt, $id);
        // add log
        $this->addLog("UPDATED AN MBOOK ISSUE COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }
    public function updateApprovalCfed()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }

        $action = isset($this->post["action"]) ? $this->post["action"] : "";
        $remarks = isset($this->post["remarks"]) ? $this->post["remarks"] : "";

        // Determine status: CFED Head approval = 20, rejection = 6
        $status = ($action === "approve") ? 20 : (($action === "reject") ? 6 : null);
        if ($status === null) {
            \CustomErrorHandler::triggerInvalid("Invalid action");
        }

        $columns = ["status", "app_time", "last_modified_by", "last_modified_time"];
        $dt = [
            "status" => $status,
            "app_time" => date("Y-m-d H:i:s"),
            "last_modified_by",
            "last_modified_time" => date("Y-m-d H:i:s")
        ];

        $id = $this->_mbookIssue_helper->update($columns, $dt, $id);

        $logMsg = $status == 20 ? "APPROVED MBOOK BY CFED HEAD" : "REJECTED MBOOK BY CFED HEAD";
        $this->addLog($logMsg, "", SmartAuthHelper::getLoggedInUserName());

        $this->response($id);
    }
    //doc
    public function getDoc()
    {
        $id = isset($this->post["id"]) ? $this->post["id"] : 0;
        if ($id < 0) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $data = $this->_mbookIssue_helper->getOneData($id);
        // 
        $pdf_path =  $this->_mbookIssue_helper->getFullFile($id) . ".pdf";
        // echo $pdf_path;
        $this->responseFileBase64(SmartFileHelper::getDataPath()  . $pdf_path);
    }
    public function getPdf()
    {
        // $id = 37;
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $data = $this->_mbookIssue_helper->getOneData($id);
        if (isset($data->ID)) {
            // var_dump($data);
            // get mbook entries from mbook entry helper using mbook_issue_id
            $data->entries = $this->_mbookEntry_helper->getAllData("t1.sd_mbook_issue_id=:id", ["id" => $id]);
        } else {
            \CustomErrorHandler::triggerInvalid("No data found for the given ID");
        }
        // var_dump($data);
        // exit();
        $this->_mbookIssue_helper->generateMbookIssuePdf($id, $data);
        $html = MbookIssuePdf::getHtml((array)$data);
        $path = "mbookIssue" . DS . $id . DS . "mbookIssue.pdf";
        SmartPdfHelper::genPdf($html, $path, ["pagesize" => "A4-L"]);
        $full_path = SmartFileHelper::getDataPath() . $path;
        $this->responseFileBase64($full_path);
    }
    //mbook  issue update user
    public function updateUser()
{
    $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
    if ($id < 1) {
        \CustomErrorHandler::triggerInvalid("Invalid ID");
    }

    // Columns allowed to update
    $columns = [
        //  "mbook_number",
            "date_of_issue",
            "title",
            "cc_number",
            "pan_number",
            "email",
            "mobile_no",
            "wages_count",
            // "salary_amount",
            "head_of_account",
            "indentor" // new column for mbook issue
    ];
//  $this->post["mbook_number"] = $this->_mbookIssue_helper->generateMbookNumber();
    // Do validations
    $this->_mbookIssue_helper->validate(MbookIssueHelper::validations, $columns, $this->post);

    // Additional columns
    $columns[] = "last_modified_by";
    $columns[] = "last_modified_time";
    $columns[] = "status";
    $columns[] = "name_of_work";
    $columns[] = "work_order_number";
    $columns[] = "work_order_value";
    $columns[] = "date_of_work_order";
    $columns[] = "budget_type";
    $columns[] = "budget_pin";
    $columns[] = "contact_name";
    $columns[] = "technical_sanction_number";
    $columns[] = "start_date";
    $columns[] = "end_date";
    $columns[] = "others";
    $columns[] = "technical_sanction_amount";
    $columns[] = "procurement_stage";
    $columns[]="description";
    $columns[]="contacter_address";
    $columns[]="uploaded_file";



    // When user updates → reset to submitted state (if needed)
    $this->post["status"] = 15;  

    // Recalculate paid & balance
    $this->post["paid_amount"] = 0;
    $this->post["balance_amount"] = $this->post["work_order_value"];

    $columns[] = "paid_amount";
    $columns[] = "balance_amount";

    // Update record
    $id = $this->_mbookIssue_helper->update($columns, $this->post, $id);

    // Handle file upload (if new file uploaded)
    $file_path = $this->_mbookIssue_helper->getFullFile($id);
    if (isset($_FILES["uploaded_file"])) {

        $stored_file_path = SmartFileHelper::moveSingleFile("uploaded_file", $file_path);

        $update_columns = ["doc_loc"];
        $update_data = ["doc_loc" => $stored_file_path];

        $this->_mbookIssue_helper->update($update_columns, $update_data, $id);
    }


    // Add log
    $this->addLog("UPDATED AN MBOOK ISSUE", "", SmartAuthHelper::getLoggedInUserName());

    $this->response($id);
}

    /**

   --- 
   ---
  MBOOK ENTRY TABLE
   ---
   ---
     */
    /*
    public function insertMbookEntry()
    {
        $columns = [
            "sd_mbook_issue_id",
             "ra_number",
             "ra_amount"
        ];
        // do validations
        $this->_mbookEntry_helper->validate(MbookEntryHelper::validations, $columns, $this->post);
        // add extra columns
        $columns[] = "created_time";
        $columns[] = "sd_mt_userdb_id";
        $columns[] = "entry_status";
        $this->post["entry_status"] = 10;
         $columns[] = "title";
          $columns[] = "date_of_issue";
        // $this->db->_db->Begin();
        // insert and get id
        $id = $this->_mbookEntry_helper->insert($columns, $this->post);
        // add log
        $this->addLog("ADDED A MBOOK ENTRY", "", SmartAuthHelper::getLoggedInUserName());
        // commit the transaction and 
        // $this->db->_db->commit();
        $this->response($id);
    }
    */
    /*
    public function insertMbookEntry()
    {
        $columns = [
            "sd_mbook_issue_id",
            "ra_number",
            "ra_amount"
        ];
        // Check if there's already a waiting entry
        $hasWaiting = $this->_mbookEntry_helper->checkWaitingEntry($this->post["sd_mbook_issue_id"]);

        if (!empty($hasWaiting)) {
            \CustomErrorHandler::triggerInternalError("You cannot add a new entry. A previous entry is still in waiting status.");
        }
        // do validations
        $existingdata = $this->_mbookEntry_helper->getFirstNonWaitingEntryId($this->post["sd_mbook_issue_id"]);

        if (($existingdata && count($existingdata) > 1)) {
            \CustomErrorHandler::triggerInternalError("You cannot add a new entry for this MBook. RA already approved or rejected.");
        }
        // Add extra columns
        $columns[] = "created_time";
        $columns[] = "sd_mt_userdb_id";
        $columns[] = "entry_status";
        // $this->post["entry_status"] = 10; // Default entry status (e.g., HOS Waiting)
        $this->post["entry_status"] = 35;  //Mbook Entry also directly completed, It will NOT go to approval flow
        $columns[] = "title";
        $columns[] = "date_of_issue";

        // Insert and get ID
        $id = $this->_mbookEntry_helper->insert($columns, $this->post);
        // =============================
        // Update Paid & Balance Amount
        // =============================

    //     $mbook_issue_id = $this->post["sd_mbook_issue_id"];
    //     $ra_amount = (int)$this->post["ra_amount"];

    //     // get issue
    //     $issue = $this->_mbookIssue_helper->getOneData($mbook_issue_id);

    //    if ($issue) {
    //      $new_paid = (int)$issue->paid_amount + $ra_amount;
    //      $new_balance = (int)$issue->work_order_value - $new_paid;

    // //   if ($new_balance < 0) {
    // //     \CustomErrorHandler::triggerInternalError("RA amount exceeds balance amount");
    // //   }

    // //   $update_columns = ["paid_amount", "balance_amount"];
    // //   $update_data = [
    // //     "balance_amount" => $new_balance
    // //   ];
    //      $update_columns = ["paid_amount", "balance_amount"];
    //      $update_data = [
    //         "paid_amount" => $new_paid,
    //         "balance_amount" => $new_balance
    //      ]; 

    //   $this->_mbookIssue_helper->update($update_columns, $update_data, $mbook_issue_id);
    //   }

        // Add log
        $this->addLog("ADDED A MBOOK ENTRY", "", SmartAuthHelper::getLoggedInUserName());
        // Return response
        $this->response($id);
    }
        */
    public function insertMbookEntry()
{
    $columns = [
        "sd_mbook_issue_id",
        "ra_number",
        "ra_amount"
    ];

    $mbook_issue_id = $this->post["sd_mbook_issue_id"];
    $ra_amount = (int)$this->post["ra_amount"];

    if ($ra_amount <= 0) {
        \CustomErrorHandler::triggerInvalid("RA amount must be greater than zero");
    }

    // Check if there's already a waiting entry
    $hasWaiting = $this->_mbookEntry_helper->checkWaitingEntry($mbook_issue_id);
    if (!empty($hasWaiting)) {
        \CustomErrorHandler::triggerInternalError(
            "You cannot add a new entry. A previous entry is still in waiting status."
        );
    }

    // Check if already approved/rejected exists
    $existingdata = $this->_mbookEntry_helper
                        ->getFirstNonWaitingEntryId($mbook_issue_id);

    if ($existingdata && count($existingdata) > 1) {
        \CustomErrorHandler::triggerInternalError(
            "You cannot add a new entry for this MBook. RA already approved or rejected."
        );
    }

    // 🔥 GET ISSUE BEFORE INSERT
    $issue = $this->_mbookIssue_helper->getOneData($mbook_issue_id);

    if (!$issue) {
        \CustomErrorHandler::triggerInvalid("Invalid MBook Issue");
    }

    $work_order_value = (int)$issue->work_order_value;
    $approved = (int)($issue->paid_amount_approved ?? 0);
    $processing = (int)($issue->paid_amount_processing ?? 0);

    $current_total = $approved + $processing;

    if (($current_total + $ra_amount) > $work_order_value) {
        \CustomErrorHandler::triggerInternalError(
            "RA amount exceeds Work Order Value. Remaining balance is " .
            ($work_order_value - $current_total)
        );
    }

    // Add extra columns
    $columns[] = "created_time";
    $columns[] = "sd_mt_userdb_id";
    $columns[] = "entry_status";
    $columns[] = "title";
    $columns[] = "date_of_issue";

    $this->post["entry_status"] = 35;

    // ✅ Now Insert (Safe)
    $id = $this->_mbookEntry_helper->insert($columns, $this->post);

    $this->addLog(
        "ADDED A MBOOK ENTRY",
        "",
        SmartAuthHelper::getLoggedInUserName()
    );

    $this->response($id);
}


    /**
     * 
     */
    public function updatetMbookEntry()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $columns = ["entry_status"];
        // do validations
        $this->_mbookEntry_helper->validate(MbookEntryHelper::validations, $columns, $this->post);
        // add extra columns
        $columns[] = "admin_remarks";
        $columns[] = "last_modified_by";
        $columns[] = "last_modified_time";
        $id = $this->_mbookEntry_helper->update($columns, $this->post, $id);
        // add log
        $this->addLog("UPDATED A MBOOK ENTRY", "", SmartAuthHelper::getLoggedInUserName());
        //
        $this->response($id);
    }
    /**
     * 
     */
    /*
    public function getOnetMbookEntry()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $data = $this->_mbookEntry_helper->getOneData($id);
        $this->response($data);
    }
    */
   public function getOnetMbookEntry()
   {
    $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
    if ($id < 1) {
        \CustomErrorHandler::triggerInvalid("Invalid ID");
    }

    $data = $this->_mbookEntry_helper->getOneData($id);

    if (!$data) {
        \CustomErrorHandler::triggerInvalid("Record not found");
    }

    // Attach tracker
    $statusTracker = $this->_mbookEntry_helper->getTAStatusTracker($data->entry_status, $data->created_by);
    $data->status_list = $statusTracker['status_list'];

    $this->response($data);
    }


    /**
     * 
     */

    public function getAllMbookEntry()
    {
        // check the mode received from router
        $sql = "";
        $data_in = [];
        $logged_id = SmartAuthHelper::getLoggedInId();
        $mode = isset($this->params["mode"]) ? $this->params["mode"] : "user";
        $entry_status = isset($this->params["status"]) ? $this->params["status"] : [100];
        switch ($mode) {
            // indicates the logged user data
            case 'user':
                $sql = "t1.sd_mt_userdb_id=:user_id";
                $data_in = ["user_id" => SmartAuthHelper::getLoggedInId()];
                break;
            case 'admin':
                $sql = "t1.entry_status  IN (" . implode(",", $entry_status) . ")";
                break;
            case 'hos':
                $org_ids = $this->_org_helper->getSubOrdIds($logged_id, "SH");
                $sql = "t1.entry_status IN (" . implode(",", $entry_status) . ") AND t2.sd_org_id IN (" . implode(",", $org_ids) . ")";
                break;
            case 'hod':
                $org_ids = $this->_org_helper->getSubOrdIds($logged_id, "DH");
                $sql = "t1.entry_status IN (" . implode(",", $entry_status) . ") AND t2.sd_org_id IN (" . implode(",", $org_ids) . ")";
                // echo "sql " . $sql;
                //exit();
                break;
            case 'ad':
                $org_ids = $this->_org_helper->getSubOrdIds($logged_id, "AD");
                $sql = "t1.entry_status IN (" . implode(",", $entry_status) . ") AND t2.sd_org_id IN (" . implode(",", array: $org_ids) . ")";
                break;
            case 'gd':
                $org_ids = $this->_org_helper->getSubOrdIds($logged_id, "GD");
                $sql = "t1.entry_status IN (" . implode(",", $entry_status) . ") AND t2.sd_org_id IN (" . implode(",", $org_ids) . ")";
                break;

            default:
                break;
        }
        // var_dump($sql,$data_in);
        $data = $this->_mbookEntry_helper->getAllData($sql, $data_in);
        
        $this->response($data);
    }
    /**
     * 
     */
    public function deleteOnetMbookEntry()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        // insert and get id
        $this->_mbookEntry_helper->deleteOneId($id);
        // add log
        $this->addLog("DELETED A MBOOK ENTRY", "", SmartAuthHelper::getLoggedInUserName());
        //
        $out = new \stdClass();
        $out->msg = "Deleted Successfully";
        $this->response($out);
    }
    public function updateAppMbookEntry()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $action = isset($this->post["action"]) ? ($this->post["action"]) : "";

        // echo "action = " . $action;

        $columns = ["entry_status", "app_time"];
        //
        $dt = ["entry_status" => $action == "approve" ? 10 : 6];
        // do validations
        // $this->_mbookIssue_helper->validate(MbookIssueHelper::validations, $columns, $dt);
        // add columns
        $columns[] = "last_modified_by";
        $columns[] = "last_modified_time";
        // insert and get id
        $id = $this->_mbookEntry_helper->update($columns, $dt, $id);
        // add log
        $this->addLog("UPDATED AN MBOOK ISSUE COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }

    public function getAllSelect()
    {
        $logged_in_id = SmartAuthHelper::getLoggedInId();
        $sql = "status = 15 AND sd_mt_userdb_id=:user_id";
        $data_in = ["user_id" => $logged_in_id];
        $data = $this->_mbookIssue_helper->getAllSelect($sql, $data_in);
        $this->response($data);
    }

    public function updateApprovalHos()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $_data = $this->_mbookEntry_helper->getOneData($id);
        $_hos_max_index_amount = intval(SmartSiteSettings::getSetting("mbook_hos_max", 0));
        $action = isset($this->post["action"]) ? $this->post["action"] : "";
        $remarks = isset($this->post["remarks"]) ? $this->post["remarks"] : "";
        // Determine status: HOS approval = 25, rejection = 6
        $status = ($action === "approve") ? 15 : (($action === "reject") ? 14 : 0);
        if ($status < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid action");
        }

        // overding the approval status if approved and limit is less
        if ($status == 15 && $_hos_max_index_amount > 0 && $_data->work_order_value < $_hos_max_index_amount) {
            $status = 35;
        }

        $columns = ["entry_status", "hos_id", "hos_remarks", "hos_time"];
        $dt = [
            "entry_status" => $status,
            "hos_remarks" => $remarks,
        ];
        $id = $this->_mbookEntry_helper->update($columns, $dt, $id);
        $logMsg = $status == 15 ? "APPROVED MBOOK BY HOS" : "REJECTED MBOOK BY HOS";
        $this->addLog($logMsg, $remarks, SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }
    public function updateApprovalHod()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }

        $_data = $this->_mbookEntry_helper->getOneData($id);
        $_hod_max_index_amount = intval(SmartSiteSettings::getSetting("mbook_hod_max", 0));

        $action = isset($this->post["action"]) ? $this->post["action"] : "";
        $remarks = isset($this->post["remarks"]) ? $this->post["remarks"] : "";
        // Determine status: HOS approval = 25, rejection = 6
        $status = ($action === "approve") ? 20 : (($action === "reject") ? 19 : 0);
        if ($status < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid action");
        }

        // overding the approval status if approved and limit is less
        if ($status == 20 && $_hod_max_index_amount > 0 && $_data->work_order_value < $_hod_max_index_amount) {
            $status = 35;
        }

        $columns = ["entry_status", "hod_id", "hod_remarks", "hod_time"];
        $dt = [
            "entry_status" => $status,
            "hod_remarks" => $remarks,
        ];
        $id = $this->_mbookEntry_helper->update($columns, $dt, $id);
        $logMsg = $status == 20 ? "APPROVED MBOOK BY HOD" : "REJECTED MBOOK BY HOD";
        $this->addLog($logMsg, $remarks, SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }


    public function updateApprovalAd()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }

        $_data = $this->_mbookEntry_helper->getOneData($id);
        $_ad_max_index_amount = intval(SmartSiteSettings::getSetting("mbook_ad_max", 0));

        $action = isset($this->post["action"]) ? $this->post["action"] : "";
        $remarks = isset($this->post["remarks"]) ? $this->post["remarks"] : "";
        // Determine status: HOS approval = 25, rejection = 6
        $status = ($action === "approve") ? 25 : (($action === "reject") ? 24 : 0);
        if ($status < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid action");
        }

        // overding the approval status if approved and limit is less
        if ($status == 25 && $_ad_max_index_amount > 0 && $_data->work_order_value < $_ad_max_index_amount) {
            $status = 35;
        }

        $columns = ["entry_status", "ad_id", "ad_remarks", "ad_time"];
        $dt = [
            "entry_status" => $status,
            "ad_remarks" => $remarks,
        ];
        $id = $this->_mbookEntry_helper->update($columns, $dt, $id);
        $logMsg = $status == 25 ? "APPROVED MBOOK BY AD" : "REJECTED MBOOK BY AD";
        $this->addLog($logMsg, $remarks, SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }

    public function updateApprovalGd()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $action = isset($this->post["action"]) ? $this->post["action"] : "";
        $remarks = isset($this->post["remarks"]) ? $this->post["remarks"] : "";
        // Determine status: HOS approval = 25, rejection = 6
        $status = ($action === "approve") ? 30 : (($action === "reject") ? 29 : 0);
        if ($status < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid action");
        }
        $columns = ["entry_status", "gd_id", "gd_remarks", "gd_time"];
        $dt = [
            "entry_status" => $status,
            "gd_remarks" => $remarks,
        ];
        $id = $this->_mbookEntry_helper->update($columns, $dt, $id);
        $logMsg = $status == 30 ? "APPROVED MBOOK BY GD" : "REJECTED MBOOK BY GD";
        $this->addLog($logMsg, $remarks, SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }
    /*
    public function getAllMbookEntries()
    {
    $mbook_issue_id = isset($this->post["mbook_issue_id"]) ? intval($this->post["mbook_issue_id"]) : 0;

    if ($mbook_issue_id < 1) {
        \CustomErrorHandler::triggerInvalid("Invalid MBook Issue ID");
    }

    // Prepare SQL and parameters
    $sql = "t1.sd_mbook_issue_id = :mbook_issue_id";
    $data_in = ["mbook_issue_id" => $mbook_issue_id];

    // Fetch the entries
    $data = $this->_mbookEntry_helper->getAllData($sql, $data_in);

    // Return the response
    $this->response($data);
   }
   */
    public function getAllMbookEntries()
    {
        $mbook_issue_id = isset($this->post["mbook_issue_id"]) ? intval($this->post["mbook_issue_id"]) : 0;

        if ($mbook_issue_id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid MBook Issue ID");
        }

        // Show only entries with "approve" status
        $sql = "t1.sd_mbook_issue_id = :mbook_issue_id AND t1.entry_status IN (30,35)";
        $data_in = ["mbook_issue_id" => $mbook_issue_id];

        $data = $this->_mbookEntry_helper->getAllData($sql, $data_in);

        $this->response($data);
    }

    public function ApprovalCounts()
    {
        $roles = ['HOS', 'HOD', 'AD', 'GD'];
        $counts = [];

        foreach ($roles as $role) {
            $select = ["COUNT(*) AS total_count"];
            $from = Table::MBOOK_ENTRY;

            // Apply status filter based on role
            switch ($role) {
                case 'HOS':
                    $sql = "entry_status = 10";
                    break;
                case 'HOD':
                    $sql = "entry_status = 15";
                    break;
                case 'AD':
                    $sql = "entry_status = 20";
                    break;
                case 'GD':
                    $sql = "entry_status = 25";
                    break;
                default:
                    break;
            }

            $data = $this->_mbookEntry_helper->getAll($select, $from, $sql, "", "", [], true);
            $counts[$role] = isset($data->total_count) ? (int)$data->total_count : 0;
        }


        $this->response($counts);
    }
    public function getFullByIssueId()
   {
    $mbook_issue_id = isset($this->post["sd_mbook_issue_id"])
                        ? intval($this->post["sd_mbook_issue_id"]) : 0;

    if ($mbook_issue_id < 1) {
        \CustomErrorHandler::triggerInvalid("Invalid MBook Issue ID");
    }

    $sql = "t1.sd_mbook_issue_id = :id";
    $data = $this->_mbookEntry_helper->getAllData($sql, ["id" => $mbook_issue_id]);

    $this->response($data);
   }

}
