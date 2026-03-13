<?php

namespace Site\Controller;

use Core\BaseController;
use Core\Helpers\SmartGeneral;
use Core\Helpers\SmartAuthHelper;

use Site\Helpers\ElectricalHelper;


class ElectricalController extends BaseController
{
    private ElectricalHelper $_electrical_helper;

    function __construct($params)
    {
        parent::__construct($params);
        // 
        $this->_electrical_helper = new ElectricalHelper($this->db);
    }

    /**
     * 
     */
    public function insert()
    {
        $columns = ["title", "description", "location"];
        // do validations
        $this->_electrical_helper->validate(ElectricalHelper::validations, $columns, $this->post);
        // add other columns
        $columns[] = "created_time";
        $columns[] = "sd_mt_userdb_id";
        $columns[] = "status";
        $this->post["status"] = 10;
        $columns[] = "supervisor";
        // insert and get id
        $id = $this->_electrical_helper->insert($columns, $this->post);
        // add log
        $this->addLog("RAISED AN ELECTRIC COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());
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
        $this->_electrical_helper->validate(ElectricalHelper::validations, $columns, $this->post);
        // add columns
        $columns[] = "admin_remarks";
        $columns[] = "last_modified_by";
        $columns[] = "last_modified_time";
        $columns[] = "supervisor";
        // insert and get id
        $id = $this->_electrical_helper->update($columns, $this->post, $id);
        // add log
        $this->addLog("UPDATED AN ELECTRIC COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());
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
            // case 'supervisor':
            //     $sql = "status IN (" . implode(",", $status) . ")";
            case 'supervisor':
                $sql = "t1.supervisor = :supervisor AND status IN (" . implode(",", $status) . ")";
                $data_in = ["supervisor" => SmartAuthHelper::getLoggedInId()];
                break;
            default:
                break;
        }
        $data = $this->_electrical_helper->getAllData($sql, $data_in);
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
        $data = $this->_electrical_helper->getOneData($id);
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
        $this->_electrical_helper->deleteOneId($id);
        // add log
        $this->addLog("DELETED AN ELECTRIC COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());
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
        $id = $this->_electrical_helper->update($columns, $dt, $id);
        // add log
        $this->addLog("UPDATED AN ELECTRIC COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }


    public function supervisorGetAll()
    {
        $supervisor = SmartAuthHelper::getLoggedInId();

        $sql = "t1.supervisor = :supervisor AND status = 30";
        $data_in = ["supervisor" => $supervisor];

        $data = $this->_electrical_helper->getAllData($sql, $data_in);
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
        $this->_electrical_helper->validate(ElectricalHelper::validations, $columns, $this->post);
        // add columns
        $columns[] = "supervisor_time";

        // insert and get id
        $id = $this->_electrical_helper->update($columns, $this->post, $id);
        // add log
        $this->addLog("UPDATED AN ELECTRIC COMPLAINT BY SUPERVIOSR", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }
}
