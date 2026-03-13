<?php

namespace Site\Controller;

use Core\BaseController;
use Core\Helpers\SmartGeneral;
use Core\Helpers\SmartAuthHelper;
use Core\Helpers\SmartData;
use Site\Helpers\AcVentilationComplaintHelper;


class AcVentilationComplaintController extends BaseController
{
    private AcVentilationComplaintHelper $_acVentilationComplaint_helper;

    function __construct($params)
    {
        parent::__construct($params);
        // 
        $this->_acVentilationComplaint_helper = new AcVentilationComplaintHelper($this->db);
    }

    /**
     * 
     */
    public function insert()
    {
        $columns = ["title", "description", "location", "authority_type"];
        $this->post["authority_type"] = SmartData::post_select_string("authority_type");
        // do validations
        $this->_acVentilationComplaint_helper->validate(AcVentilationComplaintHelper::validations, $columns, $this->post);
        // add other columns
        $columns[] = "created_time";
        $columns[] = "sd_mt_userdb_id";
        $columns[] = "status";
        $this->post["status"] = 10;
        $columns[] = "supervisor";
        // insert and get id
        $id = $this->_acVentilationComplaint_helper->insert($columns, $this->post);
        // add log
        $this->addLog("RAISED AN ACVENTILATION COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());
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
        $this->_acVentilationComplaint_helper->validate(AcVentilationComplaintHelper::validations, $columns, $this->post);
        // add columns
        $columns[] = "admin_remarks";
        $columns[] = "last_modified_by";
        $columns[] = "last_modified_time";
        $columns[] = "supervisor";
        // insert and get id
        $id = $this->_acVentilationComplaint_helper->update($columns, $this->post, $id);
        // add log
        $this->addLog("UPDATED AN ACVENTILATION COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }

    public function getAll()
    {
        // check the mode received from router
        $sql = "";
        $data_in = [];
        // $mode = isset($this->params["mode"]) ? $this->params["mode"] : "user";
        // $mode = isset($this->params["mode"]) ? $this->params["mode"] : "supervisor";
        $mode = isset($this->params["mode"]) ? $this->params["mode"] : "user";
        $mode = isset($this->params["mode"]) ? $this->params["mode"] : "supervisor";

        $status = isset($this->params["status"]) ? $this->params["status"] : [100];
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
                $sql = "status IN (" . implode(",", $status) . ")";
                break;

            default:
                break;
        }
        $data = $this->_acVentilationComplaint_helper->getAllData($sql, $data_in);
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
        $data = $this->_acVentilationComplaint_helper->getOneData($id);
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
        $this->_acVentilationComplaint_helper->deleteOneId($id);
        // add log
        $this->addLog("DELETED AN ACVENTILATION COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());
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
        // $this->_acVentilationComplaint_helper->validate(AcVentilationComplaintHelper::validations, $columns, $dt);
        // add columns
        $columns[] = "last_modified_by";
        $columns[] = "last_modified_time";
        // insert and get id
        $id = $this->_acVentilationComplaint_helper->update($columns, $dt, $id);
        // add log
        $this->addLog("UPDATED AN ACVENTILATION COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }
     public function supervisorGetAll()
   {
    $supervisor_id = SmartAuthHelper::getLoggedInId();

    $sql = "t1.supervisor = :supervisor_id AND status = 30";
    $data_in = ["supervisor_id" => $supervisor_id];

    $data = $this->_acVentilationComplaint_helper->getAllData($sql, $data_in);
    $this->response($data);
    }
      
    
        //supervisor update
    public function updateSupervisor()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $columns = ["status", "supervisor_description"];
        // do validations
        $this->_acVentilationComplaint_helper->validate(AcVentilationComplaintHelper::validations, $columns, $this->post);
        // add columns
        $columns[] = "supervisor_time";

        // insert and get id
        $id = $this->_acVentilationComplaint_helper->update($columns, $this->post, $id);
        // add log
        $this->addLog("UPDATED AN  ACVENTILATION BY SUPERVIOSR", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }

    
}
