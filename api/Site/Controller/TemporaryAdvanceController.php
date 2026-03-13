<?php

namespace Site\Controller;

use Core\BaseController;
use Core\Helpers\SmartGeneral;
use Core\Helpers\SmartAuthHelper;
use Core\Helpers\SmartSiteSettings;
use Site\Helpers\TemporaryAdvanceHelper;
use Site\Helpers\OrganisationHelper;

class TemporaryAdvanceController extends BaseController
{
    private TemporaryAdvanceHelper $_temporaryAdvance_helper;
    private OrganisationHelper $_org_helper;
    function __construct($params)
    {
        parent::__construct($params);
        // 
        $this->_temporaryAdvance_helper = new TemporaryAdvanceHelper($this->db);
        $this->_org_helper = new OrganisationHelper($this->db);
    }
    // 
    /** 5 = submitted-techwait  , 10=hos_wait,14=hos_reject , 15=hod 20=ad 25=gd 30=gd approve 
     * 
     */
    public function insert()
    {
        $columns = ["title", "description", "advance_amount", "balance", "stock_reg_no", "temporary_advance_number", "settlement","purchase_amount"];
        // Generate numbers
        $this->post["temporary_advance_number"] = $this->_temporaryAdvance_helper->generateTemporaryAdvanceNumber();
        $this->post["settlement"] = $this->_temporaryAdvance_helper->generateSettlementNumber();
        // do validations
        $this->_temporaryAdvance_helper->validate(TemporaryAdvanceHelper::validations, $columns, $this->post);
        // add other columns
        $columns[] = "created_time";
        $columns[] = "sd_mt_userdb_id";
        $columns[] = "status";
        $this->post["status"] = 5;
        // insert and get id
        $id = $this->_temporaryAdvance_helper->insert($columns, $this->post);
        // add log
        $this->addLog("RAISED AN TEMPORARY COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());
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
        $this->_temporaryAdvance_helper->validate(TemporaryAdvanceHelper::validations, $columns, $this->post);
        // add columns
        $columns[] = "admin_remarks";
        $columns[] = "last_modified_by";
        $columns[] = "last_modified_time";
        // insert and get id
        $id = $this->_temporaryAdvance_helper->update($columns, $this->post, $id);
        // add log
        $this->addLog("UPDATED AN TEMPORARY ADVANCE COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }

    public function getAll()
    {
        // check the mode received from router
        $sql = "";
        $data_in = [];
        $mode = isset($this->params["mode"]) ? $this->params["mode"] : "user";
        $status = isset($this->params["status"]) ? $this->params["status"] : [100];
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
            // supervisor view: only requests awaiting this supervisor’s approval
            case 'supervisor':
                $sql = "t1.status IN (" . implode(",", $status) . ")";
                break;
           case 'hos':
                $org_ids = $this->_org_helper->getSubOrdIds($logged_id, "SH");
                $sql = "t1.status IN (" . implode(",", $status) . ") AND t2.sd_org_id IN (" . implode(",", $org_ids) . ")";
                break;
            case 'hod':
                $org_ids = $this->_org_helper->getSubOrdIds($logged_id, "DH");
                $sql = "t1.status IN (" . implode(",", $status) . ") AND t2.sd_org_id IN (" . implode(",", $org_ids) . ")";
                // echo "sql " . $sql;
                //exit();
                break;
            case 'ad':
              $org_ids = $this->_org_helper->getSubOrdIds($logged_id, "AD");
                $sql = "t1.status IN (" . implode(",", $status) . ") AND t2.sd_org_id IN (" . implode(",", array: $org_ids) . ")";
                 break;
            case 'gd':
                 $org_ids = $this->_org_helper->getSubOrdIds($logged_id, "GD");
                $sql = "t1.status IN (" . implode(",", $status) . ") AND t2.sd_org_id IN (" . implode(",", $org_ids) . ")";
                break;
            default:
                break;
        }
        $data = $this->_temporaryAdvance_helper->getAllData($sql, $data_in);
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
        $data = $this->_temporaryAdvance_helper->getOneData($id);
        $this->response($data);
    }
    */
    public function getOne()
   {
    $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
    if ($id < 1) {
        \CustomErrorHandler::triggerInvalid("Invalid ID");
    }

    $data = $this->_temporaryAdvance_helper->getOneData($id);

    if (!$data) {
        \CustomErrorHandler::triggerInvalid("Record not found");
    }

    // Attach tracker
    $statusTracker = $this->_temporaryAdvance_helper->getTAStatusTracker($data->status, $data->created_by);
    $data->status_list = $statusTracker['status_list'];

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
        $this->_temporaryAdvance_helper->deleteOneId($id);
        // add log
        $this->addLog("DELETED AN TEMPORARY ADVANCE COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());
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
        // $this->_electrical_helper->validate(ElectricalHelper::validations, $columns, $dt);
        // add columns
        $columns[] = "last_modified_by";
        $columns[] = "last_modified_time";
        // insert and get id
        $id = $this->_temporaryAdvance_helper->update($columns, $dt, $id);
        // add log
        $this->addLog("UPDATED AN TEMPORARY ADVANCE COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }

    //APPROVAL FOR HOS, HOD, AD/DIRECTOR ,DIRECTOR

    public function updateApprovalHos()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $_data = $this->_temporaryAdvance_helper->getOneData($id);
        $_hos_max_index_amount = intval(SmartSiteSettings::getSetting("temporary_advance_hos_max",0));

      

        $status = isset($this->post["action"]) ? $this->post["action"] : "";
        $remarks = isset($this->post["remarks"]) ? $this->post["remarks"] : "";
        // Determine status: HOS approval = 25, rejection = 6
        $status = ($status === "approve") ? 15 : (($status === "reject") ? 14 : 0);
        if ($status < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid Action");
        }
        // overriding the approval status if approved and limit is less or equal
       if ($status == 15 && $_hos_max_index_amount > 0 && $_data->advance_amount <= $_hos_max_index_amount) {
          $status = 35;
    }
        $columns = ["status", "hos_id", "hos_remarks", "hos_time"];
        $dt = [
            "status" => $status,
            "hos_remarks" => $remarks
        ];

        $id = $this->_temporaryAdvance_helper->update($columns, $dt, $id);

        $logMsg = $status == 15 ? "APPROVED TEMPORARY ADVANCE  BY HOS" : "REJECTED TEMPORARY ADVANCE  BY HOS";
        $this->addLog($logMsg, $remarks, SmartAuthHelper::getLoggedInUserName());

        $this->response($id);
    }
    public function updateApprovalHod()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
     $_data = $this->_temporaryAdvance_helper->getOneData($id);
        $_hod_max_index_amount = intval(SmartSiteSettings::getSetting("temporary_advance_hod_max",0));

        $status = isset($this->post["action"]) ? $this->post["action"] : "";
        $remarks = isset($this->post["remarks"]) ? $this->post["remarks"] : "";

        // HOD approval = 35, rejection = 6
        $status = ($status === "approve") ? 20 : (($status === "reject") ? 19 : 0);
        if ($status < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid Action");
        }
         // overding the approval status if approved and limit is less
        if($status==20 && $_hod_max_index_amount > 0 && $_data->advance_amount <= $_hod_max_index_amount){
            $status=35;
        }
        $columns = ["status", "hod_id", "hod_remarks", "hod_time"];
        $dt = [
            "status" => $status,
            "hod_remarks" => $remarks
        ];

        $id = $this->_temporaryAdvance_helper->update($columns, $dt, $id);

        $logMsg = $status == 20 ? "APPROVED TEMPORARY ADVANCE  BY HOD" : "REJECTED TEMPORARY ADVANCE  BY HOD";
        $this->addLog($logMsg, $remarks, SmartAuthHelper::getLoggedInUserName());

        $this->response($id);
    }
    public function updateApprovalAd()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
       $_data = $this->_temporaryAdvance_helper->getOneData($id);
        $_ad_max_index_amount = intval(SmartSiteSettings::getSetting("temporary_advance_ad_max",0));

        $status = isset($this->post["action"]) ? $this->post["action"] : "";
        $remarks = isset($this->post["remarks"]) ? $this->post["remarks"] : "";

        // AD/Director approval = 45, rejection = 6
        $status = ($status === "approve") ? 25 : (($status === "reject") ? 24 : null);
        if ($status < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid Action");
        }
        // overding the approval status if approved and limit is less
        if($status==25 && $_ad_max_index_amount > 0 && $_data->advance_amount <= $_ad_max_index_amount){
            $status=35;
        }
        $columns = ["status", "ad_id", "ad_remarks", "ad_time"];
        $dt = [
            "status" => $status,
            "ad_remarks" => $remarks
        ];

        $id = $this->_temporaryAdvance_helper->update($columns, $dt, $id);

        $logMsg = $status == 25 ? "APPROVED TEMPORARY ADVANCE BY AD/DIRECTOR" : "REJECTED TEMPORARY ADVANCE BY AD/DIRECTOR";
        $this->addLog($logMsg, $remarks, SmartAuthHelper::getLoggedInUserName());

        $this->response($id);
    }
    public function updateApprovalGd()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }

     
        $status = isset($this->post["action"]) ? $this->post["action"] : "";
        $remarks = isset($this->post["remarks"]) ? $this->post["remarks"] : "";

        // AD/Director approval = 55, rejection = 6
        $status = ($status === "approve") ? 30 : (($status === "reject") ? 29 : null);
        if ($status < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid Action");
        }
        $columns = ["status", "gd_id", "gd_remarks", "gd_time"];
        $dt = [
            "status" => $status,
            "gd_remarks" => $remarks
        ];
        $id = $this->_temporaryAdvance_helper->update($columns, $dt, $id);
        $logMsg = $status == 30 ? "APPROVED TEMPORARY ADVANCE BY DIRECTOR" : "REJECTED TEMPORARY ADVANCE BY DIRECTOR";
        $this->addLog($logMsg, $remarks, SmartAuthHelper::getLoggedInUserName());

        $this->response($id);
    }
        public function supervisorGetAll()
    {
        $supervisor_id = SmartAuthHelper::getLoggedInId();

        $sql = "t1.supervisor = :supervisor_id AND status = 10";
        $data_in = ["supervisor_id" => $supervisor_id];

        $data = $this->_temporaryAdvance_helper->getAllData($sql, $data_in);
        $this->response($data);
    }
    //supervisor update
    public function updateSupervisor()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $columns = ["status"];
        // do validations
        $this->_temporaryAdvance_helper->validate(TemporaryAdvanceHelper::validations, $columns, $this->post);
        // add columns
        $columns[] = "supervisor_time";
        $columns[] = "supervisor_description";
        // insert and get id
        $id = $this->_temporaryAdvance_helper->update($columns, $this->post, $id);
        // add log
        $this->addLog("UPDATED AN TEMPRORAT ADVANCE BY SUPERVIOSR", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }

}
