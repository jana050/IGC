<?php

namespace Site\Controller;

use Core\BaseController;
use Core\Helpers\SmartGeneral;
use Core\Helpers\SmartAuthHelper;
use Core\Helpers\SmartSiteSettings;
use Core\Helpers\SmartFileHelper;
use Core\Helpers\SmartPdfHelper;
use Core\Helpers\SmartData;
use Site\Helpers\CommiteeIibccHelper;
use Site\Helpers\OrganisationHelper;
use Site\view\CommiteeIibccPdf;
use Site\Helpers\CommiteeHistoryHelper;


class CommiteeIibccController extends BaseController
{
    private CommiteeIibccHelper $_commitee_iibcc_helper;
    private OrganisationHelper $_org_helper;
    private CommiteeHistoryHelper $_commitee_history_helper;

    function __construct($params)
    {
        parent::__construct($params);
        // 
        $this->_commitee_iibcc_helper = new CommiteeIibccHelper($this->db);
        $this->_org_helper = new OrganisationHelper($this->db);
        $this->_commitee_history_helper = new CommiteeHistoryHelper($this->db);
    }
    // 
    /** 10 = submitted-techwait  , 10=hos_wait,14=hos_reject,15=hos_process , 15=hod wait,hod process=20
     * 
     */
    public function insert()
    {
        $columns = [
            "indent_no",
            "iibcc_no",
            "name_of_item",
            "item_quantity",
            "estimate_source",
            "amount",
            "head_of_account",
            "item_belongs_to",
            "item_source",
            "pdi_required",
            "item_to_purchased",
            // "delivery_date",
            "description",
            "nature_of_item",
            "technical_sanction_number",
        ];

        // Generate number
        $this->post["iibcc_no"] = $this->_commitee_iibcc_helper->generateCommiteeIibccNumber();


        // validations
        $this->_commitee_iibcc_helper->validate(
            CommiteeIibccHelper::validations,
            $columns,
            $this->post
        );

        // additional fields
        $columns[] = "created_time";
        $columns[] = "sd_mt_userdb_id";
        $columns[] = "iibcc_approver_id";
        $columns[] = "iibcc_approver_remarks";
        $columns[] = "status";
        $this->post["status"] = 10;
        $columns[] = "delivery_date";
        $columns[] = "supplier_1";
        $columns[] = "supplier_2";
        $columns[] = "supplier_3";
        $columns[] = "gem_id_flag";
        $columns[] = "gem_number";
        $columns[] = "gem_approvals";
        $columns[] = "store_certificate";
        $columns[] = "technical_sanction_amount";
        $columns[]="unit";
    
        // insert
        $id = $this->_commitee_iibcc_helper->insert($columns, $this->post);
        
        // process the file
        $file_path = $this->_commitee_iibcc_helper->getFullFile($id);
        if( isset($_FILES["uploaded_file"])){
        // move the uploaded file to path 
        $stored_file_path = SmartFileHelper::moveSingleFile("uploaded_file",$file_path);
        // update the file path in table
        $update_columns = ["doc_loc"];
        $update_data = ["doc_loc"=>$stored_file_path];
           $this->_commitee_iibcc_helper->update($update_columns,$update_data,$id);   
        } 
// ✅ ADD HISTORY FOR USER SUBMIT 👇
$this->addcommiteeIibccHistory(
    $id,
    "EMPLOYEEE",
    "SUBMITTED",
    "IIBCC request submitted"
);
        // log
        $this->addLog(
            "RAISED AN IIBCC COMPLAINT", "", SmartAuthHelper::getLoggedInUserName() );


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
        $this->_commitee_iibcc_helper->validate(CommiteeIibccHelper::validations, $columns, $this->post);
        // add columns
        // $columns[] = "admin_remarks";
        $columns[] = "last_modified_by";
        $columns[] = "last_modified_time";
      

        // insert and get id
        $id = $this->_commitee_iibcc_helper->update($columns, $this->post, $id);
         // process the file
        $file_path = $this->_commitee_iibcc_helper->getFullFile($id);
        if( isset($_FILES["uploaded_file"])){
        // move the uploaded file to path 
        $stored_file_path = SmartFileHelper::moveSingleFile("uploaded_file",$file_path);
        // update the file path in table
        $update_columns = ["doc_loc"];
        $update_data = ["doc_loc"=>$stored_file_path];
           $this->_commitee_iibcc_helper->update($update_columns,$update_data,$id);   
        } // ✅ ADD HISTORY FOR USER SUBMIT 👇
$this->addcommiteeIibccHistory(
    $id,
    "EMPLOYEE",
    "UPDATED",
    "IIBCC request updated"
);

        // add log
        $this->addLog("UPDATED AN IIBCC COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());
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
            //approval      
            // case 'hod':
            //     $org_ids = $this->_org_helper->getSubOrdIds($logged_id, "DH");
            //     $sql = "t1.status IN (" . implode(",", $status) . ") AND t2.sd_org_id IN (" . implode(",", $org_ids) . ")";
            //     break;
            case 'hod':
                $sql = "t1.status IN (" . implode(",", $status) . ")";
                break;
            // case 'iibcc_approver':
            //     $sql = "t1.status IN (" . implode(",", $status) . ")";
            //     break;
            case 'iibcc_approver':
                $sql = "t1.iibcc_approver_id = :iibcc_approver_id AND status IN (" . implode(",", $status) . ")";
                $data_in = ["iibcc_approver_id" => SmartAuthHelper::getLoggedInId()];
                break;
            case 'iibcc_chairman':
                $sql = "t1.status IN (" . implode(",", $status) . ")";
                break;
            default:
                break;
        }
        $data = $this->_commitee_iibcc_helper->getAllData($sql, $data_in);
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
        $data = $this->_commitee_iibcc_helper->getOneData($id);

        if (!$data) {
            \CustomErrorHandler::triggerInvalid("Record not found");
        }

        // Get tracker (pass created_by also)
        $statusTracker = $this->_commitee_iibcc_helper->getStatusTracker($data->status, $data->created_by);

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
        $this->_commitee_iibcc_helper->deleteOneId($id);
        // add log
        $this->addLog("DELETED AN IIBCC COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());
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

        $status = isset($this->post["status"]) ? $this->post["status"] : "";
        $remarks = isset($this->post["remarks"]) ? $this->post["remarks"] : "";

        // HOD approval = 35, rejection = 6
        // $status = ($status === "approve") ? 15 : (($status === "reject") ? 14 : 0);
        // $status = ($status === "approve") ? 15 : (($status === "reject") ? 10 : 0);
        // NEW
        $status = ($status === "approve") ? 15 : (($status === "rework") ? 40 : 0);

        if ($status < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid Action");
        }
        $columns = ["status", "hos_id", "hos_remarks", "hos_time"];
        $dt = [
            "status" => $status,
            "hos_remarks" => $remarks
        ];
        

        $id = $this->_commitee_iibcc_helper->update($columns, $dt, $id);
         // ✅ ADD HISTORY HERE 👇
        // $this->addcommiteeIibccHistory( $id, "hos", ($status == 15 ? "APPROVED" : "REJECTED"),  $remarks);
        $this->addcommiteeIibccHistory( $id, "HOS", ($status == 15 ? "APPROVED" : "REWORK"), $remarks);


        // $logMsg = $status == 15 ? "APPROVED IIBCC CARD   BY HOS" : "REJECTED IIBCC CARD   BY HOS TO REWORK";
        $logMsg = $status == 15? "APPROVED IIBCC CARD BY HOS": "SENT IIBCC CARD FOR REWORK BY HOS";

        $this->addLog($logMsg, $remarks, SmartAuthHelper::getLoggedInUserName());

        $this->response($id);
    }

    //APPROVAL FOR HOD
    public function updateApprovalHod()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }

        $status = isset($this->post["status"]) ? $this->post["status"] : "";
        $remarks = isset($this->post["remarks"]) ? $this->post["remarks"] : "";

        // HOD approval = 20, rejection = 19
        // $status = ($status === "approve") ? 20 : (($status === "reject") ? 24 : 0);
        // $status = ($status === "approve") ? 20 : (($status === "reject") ? 10 : 0);
        // NEW
        $status = ($status === "approve") ? 20 : (($status === "rework") ? 40 : 0);
        
        if ($status < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid Action");
        }
        $columns = ["status", "hod_id", "hod_remarks", "hod_time"];
        $dt = [
            "status" => $status,
            "hod_remarks" => $remarks
        ];

        $id = $this->_commitee_iibcc_helper->update($columns, $dt, $id);
        //
        // $this->addcommiteeIibccHistory( $id, "hod", ($status == 20 ? "APPROVED" : "REJECTED"),  $remarks);
        $this->addcommiteeIibccHistory($id,"HOD",($status == 20 ? "APPROVED" : "REWORK"), $remarks);


        // $logMsg = $status == 20 ? "APPROVED IIBCC CARD   BY HOD" : "REJECTED IIBCC CARD   BY HOD TO REWORK";
        $logMsg = $status == 20? "APPROVED IIBCC CARD BY HOD": "SENT IIBCC CARD FOR REWORK BY HOD";

        $this->addLog($logMsg, $remarks, SmartAuthHelper::getLoggedInUserName());

        $this->response($id);
    }
     public function updateApprovalIibccApprover()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }

        $status = isset($this->post["status"]) ? $this->post["status"] : "";
        $remarks = isset($this->post["remarks"]) ? $this->post["remarks"] : "";

        // Determine status: Iibcc approval = 20, rejection = 19
        // $status = ($status === "approve") ? 25 : (($status === "reject") ? 24 : 0);
        // $status = ($status === "approve") ? 25 : (($status === "reject") ? 10 : 0);
        $status = ($status === "approve") ? 25 : (($status === "rework") ? 40 : 0);

        if ($status < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid Action");
        }
        $columns = ["status", "iibcc_approver_id", "iibcc_approver_remarks", "iibcc_approver_time"];
        $dt = [
            "status" => $status,
            "iibcc_approver_remarks" => $remarks
        ];

        $id = $this->_commitee_iibcc_helper->update($columns, $dt, $id);
        // $this->addcommiteeIibccHistory( $id, "iibcc_approver", ($status == 25 ? "APPROVED" : "REJECTED"),  $remarks);
       $this->addcommiteeIibccHistory(  $id, "IIBCC APPROVER",($status == 25 ? "APPROVED" : "REWORK"),$remarks);

        $logMsg = $status == 25 ? "APPROVED IIBCC BY IIBCC APPROVER" : "SENT IIBCC CARD BY IIBCC APPROVER TO REWORK";
        $this->addLog($logMsg, $remarks, SmartAuthHelper::getLoggedInUserName());

        $this->response($id);
    }
         public function updateApprovalIibccChairman()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }

        $status = isset($this->post["status"]) ? $this->post["status"] : "";
        $remarks = isset($this->post["remarks"]) ? $this->post["remarks"] : "";

        // Determine status: Iibcc chairman = 30, rejection = 29
        // $status = ($status === "approve") ? 30 : (($status === "reject") ? 29 : 0);
        // $status = ($status === "approve") ? 30 : (($status === "reject") ? 10 : 0);
        $status = ($status === "approve") ? 30 : (($status === "rework") ? 40 : 0);

        if ($status < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid Action");
        }
        $columns = ["status", "iibcc_chairman_id", "iibcc_chairman_remarks", "iibcc_chairman_time"];
        $dt = [
            "status" => $status,
            "iibcc_chairman_remarks" => $remarks
        ];

        $id = $this->_commitee_iibcc_helper->update($columns, $dt, $id);
        //  $this->addcommiteeIibccHistory( $id, "iibcc_chairman", ($status == 30 ? "APPROVED" : "REJECTED"),  $remarks);
        $this->addcommiteeIibccHistory( $id, "IIBCC CHAIRMAN", ($status == 30 ? "APPROVED" : "REWORK"), $remarks);



        $logMsg = $status == 30 ? "APPROVED IIBCC BY IIBCC CHAIRMAN" : "SENT IIBCC CARD BY IIBCC CHAIRMAN TO REWORK";
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
       $data = $this->_commitee_iibcc_helper->getOneData($id);
       
        // var_dump($data);
        // exit();
        $this->_commitee_iibcc_helper->generateRfidCardPdf($id, $data);
        $html = CommiteeIibccPdf::getHtml((array)$data);
        $path = "commiteeiibcc" . DS . $id . DS . "commiteeiibcc.pdf";
        // SmartPdfHelper::genPdf($html, $path, ["pagesize" => "A4"]);
        SmartPdfHelper::genPdf($html, $path, [
    "pagesize" => "A4",
    "margin_left" => 5,
    "margin_right" => 5,
    "margin_top" => 5,
    "margin_bottom" => 5
]);
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
        $columns = [
            "indent_no",
            "iibcc_no",
            "name_of_item",
            "item_quantity",
            "estimate_source",
            "amount",
            "head_of_account",
            "item_belongs_to",
            "item_source",
            "pdi_required",
            "item_to_purchased",
            // "delivery_date",
            "description",
            "nature_of_item",
            "technical_sanction_number"
        ];
        $this->post["iibcc_no"] = $this->_commitee_iibcc_helper->generateCommiteeIibccNumber();
        // do validations
        $this->_commitee_iibcc_helper->validate(CommiteeIibccHelper::validations, $columns, $this->post);
        // add columns
        $columns[] = "last_modified_by";
        $columns[] = "last_modified_time";
        $columns[] = "status";
        $this->post["status"] = 10;
        $columns[] = "delivery_date";
        $columns[] = "supplier_1";
        $columns[] = "supplier_2";
        $columns[] = "supplier_3";
        $columns[] = "gem_id_flag";
        $columns[] = "gem_number";
        $columns[] = "gem_approvals";
        $columns[] = "store_certificate";
        $columns[] = "technical_sanction_amount";
        $columns[]="unit";

        

        // insert and get id
        $id = $this->_commitee_iibcc_helper->update($columns, $this->post, $id);
        // process the file
        $file_path = $this->_commitee_iibcc_helper->getFullFile($id);
        if( isset($_FILES["uploaded_file"])){
        // move the uploaded file to path 
        $stored_file_path = SmartFileHelper::moveSingleFile("uploaded_file",$file_path);
        // update the file path in table
        $update_columns = ["doc_loc"];
        $update_data = ["doc_loc"=>$stored_file_path];
           $this->_commitee_iibcc_helper->update($update_columns,$update_data,$id);   
        } 
        // ✅ ADD HISTORY FOR USER SUBMIT 
        $this->addcommiteeIibccHistory(
    $id,
    "EMPLOYEEE",
    "UPDATED",
    "IIBCC request updated"
);

        // add log
        $this->addLog("UPDATED AN COMMITEE IIBCC COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }
    //
        public function getDoc(){
        $id = isset($this->post["id"]) ? $this->post["id"] : 0;
        if($id < 0){
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $data = $this->_commitee_iibcc_helper->getOneData($id);
        // 
        $pdf_path =  $this->_commitee_iibcc_helper->getFullFile($id) .".pdf";
    //    echo $pdf_path;
        $this->responseFileBase64(SmartFileHelper::getDataPath()  . $pdf_path);
    }

   public function addcommiteeIibccHistory($commiteeId, $role, $action, $remarks = "")
   {
    $data = [
        "commitee_name" => "IIBCC",
        "commitee_id"   => $commiteeId,
        "role_name"     => $role,
        "remarks"       => $remarks,
        "action"        => $action,
        "created_time"  => date("Y-m-d H:i:s")
    ];

    $columns = array_keys($data);
    $this->_commitee_history_helper->insert($columns, $data);

    }

public function getibbcccHistory()
{
    $commitee_id = isset($this->post["commitee_id"]) ? intval($this->post["commitee_id"]) : 0;

    if ($commitee_id < 1) {
        \CustomErrorHandler::triggerInvalid("Invalid Committee ID");
    }

    $data = $this->_commitee_history_helper->getByCommiteeId("IIBCC", $commitee_id);

    $this->response($data);
}


                

}
