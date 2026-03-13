<?php

namespace Site\Controller;

use Core\BaseController;
use Core\Helpers\SmartGeneral;
use Core\Helpers\SmartAuthHelper;
use Core\Helpers\SmartSiteSettings;
use Core\Helpers\SmartFileHelper;
use Core\Helpers\SmartPdfHelper;
use Site\Helpers\RfidCardHelper;
use Site\view\RfidCardPdf;
use Site\Helpers\OrganisationHelper;

class RfidCardController extends BaseController
{
    private RfidCardHelper $_rfidCard_helper;
    private OrganisationHelper $_org_helper;
    function __construct($params)
    {
        parent::__construct($params);
        // 
        $this->_rfidCard_helper = new RfidCardHelper($this->db);
        $this->_org_helper = new OrganisationHelper($this->db);
    }
    // 
    /** 5 = submitted-techwait  , 10=hos_wait,14=hos_reject , 15=hod 20=ad 25=gd 30=gd approve 
     * 
     */
    public function insert()
    {
        $columns = [
            "from_date",
            "to_date",
            "nature_of_visitor",
            "igcar_entry_permit_no",
            "name",
            "area_of_visit",
            "intercom_no",
            "mobile_no",
            "rfid_req_number",
            "institute_address",
            // "hos_id",
            "gender",
            "age",
            "igcar_entry_date_of_validity"
        ];
        // Generate numbers
        $this->post["rfid_req_number"] = $this->_rfidCard_helper->generateRfidNumber();

        // do validations
        $this->_rfidCard_helper->validate(RfidCardHelper::validations, $columns, $this->post);
        // add other columns  
        $columns[] = "created_time";
        $columns[] = "sd_mt_userdb_id";
        $columns[] = "status";
        $this->post["status"] = 10;
        // insert and get id
        $id = $this->_rfidCard_helper->insert($columns, $this->post);
        // add log
        $this->addLog("RAISED AN RFID CARD COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());
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
        $this->_rfidCard_helper->validate(RfidCardHelper::validations, $columns, $this->post);
        // add columns
        // $columns[] = "admin_remarks";
        $columns[] = "last_modified_by";
        $columns[] = "last_modified_time";
        // insert and get id
        $id = $this->_rfidCard_helper->update($columns, $this->post, $id);
        // add log
        $this->addLog("UPDATED AN RFID CARD COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());
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
            :    [100]; // fallback if not set or empty

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
                $org_ids = $this->_org_helper->getSubOrdIds($logged_id, "SH");
                $sql = "t1.status IN (" . implode(",", $status) . ") AND t2.sd_org_id IN (" . implode(",", $org_ids) . ")";
                break;
            //approval      
            case 'hod':
                $org_ids = $this->_org_helper->getSubOrdIds($logged_id, "DH");
                $sql = "t1.status IN (" . implode(",", $status) . ") AND t2.sd_org_id IN (" . implode(",", $org_ids) . ")";
                break;
            // case 'hod':
            //     $sql = "t1.status IN (" . implode(",", $status) . ")";
            //     break;
            case 'supervisor':
                $sql = "t1.status IN (" . implode(",", $status) . ")";
                break;
            case 'card_return':
                $sql = "t1.status IN (" . implode(",", $status) . ")";
                break;
            default:
                break;
        }
        $data = $this->_rfidCard_helper->getAllData($sql, $data_in);
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
        $data = $this->_rfidCard_helper->getOneData($id);
        $this->response($data);
    }
    */
public function getOne() 
{
    $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
    if ($id < 1) {
        \CustomErrorHandler::triggerInvalid("Invalid ID");
    }

    // Get the main record
    $data = $this->_rfidCard_helper->getOneData($id);

    if (!$data) {
        \CustomErrorHandler::triggerInvalid("Record not found");
    }

    // Get tracker (pass created_by also)
    $statusTracker = $this->_rfidCard_helper->getStatusTracker($data->status, $data->created_by);

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
        $this->_rfidCard_helper->deleteOneId($id);
        // add log
        $this->addLog("DELETED AN RFID CARD COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());
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
        $status = ($status === "approve") ? 15 : (($status === "reject") ? 14 : 0);
        if ($status < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid Action");
        }
        $columns = ["status", "hos_id", "hos_remarks", "hos_time"];
        $dt = [
            "status" => $status,
            "hos_remarks" => $remarks
        ];

        $id = $this->_rfidCard_helper->update($columns, $dt, $id);

        $logMsg = $status == 15 ? "APPROVED RFID CARD   BY HOS" : "REJECTED RFID CARD   BY HOS";
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

        // HOD approval = 35, rejection = 6
        $status = ($status === "approve") ? 20 : (($status === "reject") ? 19 : 0);
        if ($status < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid Action");
        }
        $columns = ["status", "hod_id", "hod_remarks", "hod_time"];
        $dt = [
            "status" => $status,
            "hod_remarks" => $remarks
        ];

        $id = $this->_rfidCard_helper->update($columns, $dt, $id);

        $logMsg = $status == 20 ? "APPROVED RFID CARD   BY HOD" : "REJECTED RFID CARD   BY HOD";
        $this->addLog($logMsg, $remarks, SmartAuthHelper::getLoggedInUserName());

        $this->response($id);
    }
    //supervisor update (EIC) status=25
    public function updateSupervisor()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $columns = ["card_no"];
        // do validations
        $this->_rfidCard_helper->validate(RfidCardHelper::validations, $columns, $this->post);
        // add columns
        $columns[] = "supervisor_time";
        $columns[] = "supervisor_remarks";
        $columns[] = "supervisor";
        $columns[] = "status";
        $this->post["status"] = 25; // set status to 30 for supervisor approval
        // insert and get id
        $id = $this->_rfidCard_helper->update($columns, $this->post, $id);
        // add log
        $this->addLog("UPDATED AN RFID CARD BY SUPERVIOSR", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }
    //updateCardReturn status=30
    public function updateCardReturn()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        // add columns
        $columns[] = "card_date";
        $columns[] = "status";
        $this->post["status"] = 30; // set status to 30 for supervisor approval
        // insert and get id
        $id = $this->_rfidCard_helper->update($columns, $this->post, $id);
        // add log
        $this->addLog("UPDATED AN RFID CARD RetURN", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }
    //user update
    public function updateUser()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $columns = [
            "from_date",
            "to_date",
            "nature_of_visitor",
            "igcar_entry_permit_no",
            "name",
            "area_of_visit",
            "intercom_no",
            "mobile_no",
            "institute_address",
            // "hos_id",
            "gender",
            "age",
            "igcar_entry_date_of_validity"
        ];
        // do validations
        $this->_rfidCard_helper->validate(RfidCardHelper::validations, $columns, $this->post);
        // add columns
        $columns[] = "last_modified_by";
        $columns[] = "last_modified_time";
        // insert and get id
        $id = $this->_rfidCard_helper->update($columns, $this->post, $id);
        // add log
        $this->addLog("UPDATED AN RFID CARD COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }
      public function getPdf()
    {
        // $id = 16;
          $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
       $data = $this->_rfidCard_helper->getOneData($id);
       
        // var_dump($data);
        // exit();
        $this->_rfidCard_helper->generateRfidCardPdf($id, $data);
        $html = RfidCardPdf::getHtml((array)$data);
        $path = "rfidcard" . DS . $id . DS . "rfidcard.pdf";
        SmartPdfHelper::genPdf($html, $path, ["pagesize" => "A4"]);
        $full_path = SmartFileHelper::getDataPath() . $path;
        $this->responseFileBase64($full_path);
    }
    //
    public function getExpiredUnreturnedCards()
{
    $today = date("Y-m-d");

    $sql = "t1.to_date < :today AND t1.status = 25";
    $data_in = ["today" => $today];

    $data = $this->_rfidCard_helper->getAllData($sql, $data_in);
    $this->response($data);
}

   

}
