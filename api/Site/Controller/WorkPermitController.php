<?php

namespace Site\Controller;

use Core\BaseController;
use Core\Helpers\SmartGeneral;
use Core\Helpers\SmartAuthHelper;
use Core\Helpers\SmartSiteSettings;
use Site\Helpers\WorkPermitHelper;
use Site\Helpers\OrganisationHelper;

class WorkPermitController extends BaseController
{
    private WorkPermitHelper $_workPermitHelperhelper;
    private OrganisationHelper $_org_helper;
    function __construct($params)
    {
        parent::__construct($params);
        // 
        $this->_workPermitHelperhelper = new WorkPermitHelper($this->db);
        $this->_org_helper = new OrganisationHelper($this->db);
    }
    // 
    /** 5 = submitted-techwait  , 10=hos_wait,14=hos_reject 
     * 
     */
    public function insert()
    {
        $columns = [
            "permit_no",
            "system",
            "description_of_location",
            "team_involved",
            "shutdown_jobs",
            "ac_venitilation_type",
            "description_of_work",
            "start_date",
            "end_date",
            "necessary_disconnection",
            "isolation",
            "health_physical_instructions",
            "industrial_safety_permit",
            "welding_cutting_permit"
        ];

        // do validations
        $this->_workPermitHelperhelper->validate(WorkPermitHelper::validations, $columns, $this->post);
        // add other columns
        $columns[] = "created_time";
        $columns[] = "sd_mt_userdb_id";
        $columns[] = "status";
        $this->post["status"] = 5;
        // insert and get id
        $id = $this->_workPermitHelperhelper->insert($columns, $this->post);
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
        $columns = [
            "work_all_completed",
            "defect_rectified",
            "work_area_cleared",
            "tags_cleared",
            "protection_available",
            "operation_test",
            "system_return_normal"
        ];
        // do validations
        $this->_workPermitHelperhelper->validate(WorkPermitHelper::validations, $columns, $this->post);
        // add columns
        $columns[] = "comments";
        $columns[] = "details_work";
         $columns[] = "admin_remarks";
        $columns[] = "last_modified_by";
        $columns[] = "last_modified_time";
        $columns[] = "status";
        $this->post["status"] = 35;   //completed
        // insert and get id
        $id = $this->_workPermitHelperhelper->update($columns, $this->post, $id);
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
            default:
                break;
        }
        $data = $this->_workPermitHelperhelper->getAllData($sql, $data_in);
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
        // insert and get id
        $data = $this->_workPermitHelperhelper->getOneData($id);
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
        $this->_workPermitHelperhelper->deleteOneId($id);
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
        $id = $this->_workPermitHelperhelper->update($columns, $dt, $id);
        // add log
        $this->addLog("UPDATED AN TEMPORARY ADVANCE COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }

    //APPROVAL FOR HOS
    public function updateApprovalHos()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }

        $status = isset($this->post["action"]) ? $this->post["action"] : "";
        $remarks = isset($this->post["remarks"]) ? $this->post["remarks"] : "";
        // Determine status: HOS approval = 15, rejection = 14
        $status = ($status === "approve") ? 15 : (($status === "reject") ? 14 : 0);
        if ($status < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid Action");
        }
        $columns = ["status", "hos_id", "hos_remarks", "hos_time"];
        $dt = [
            "status" => $status,
            "hos_remarks" => $remarks
        ];

        $id = $this->_workPermitHelperhelper->update($columns, $dt, $id);

        $logMsg = $status == 15 ? "APPROVED TEMPORARY ADVANCE  BY HOS" : "REJECTED TEMPORARY ADVANCE  BY HOS";
        $this->addLog($logMsg, $remarks, SmartAuthHelper::getLoggedInUserName());

        $this->response($id);
    }
    public function supervisorGetAll()
    {
        $supervisor_id = SmartAuthHelper::getLoggedInId();

        $sql = "t1.supervisor = :supervisor_id AND status = 10";
        $data_in = ["supervisor_id" => $supervisor_id];

        $data = $this->_workPermitHelperhelper->getAllData($sql, $data_in);
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
        $this->_workPermitHelperhelper->validate(WorkPermitHelper::validations, $columns, $this->post);
        // add columns
        $columns[] = "supervisor_time";
        $columns[] = "supervisor_description";
        // insert and get id
        $id = $this->_workPermitHelperhelper->update($columns, $this->post, $id);
        // add log
        $this->addLog("UPDATED AN TEMPRORAT ADVANCE BY SUPERVIOSR", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }

}
