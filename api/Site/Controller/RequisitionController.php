<?php

namespace Site\Controller;

use Core\BaseController;
use Core\Helpers\SmartAuthHelper;
use Core\Helpers\SmartFileHelper;

use Site\Helpers\RequisitionHelper;

/**
 * Ticket-level Custom Requisition workflow. The Admin/Supervisor
 * processing side mirrors CommanComplaintController's Complaint Table
 * section; the submission side (insert) mirrors WorkshopController's
 * Name/Nature of Work/Free Issue Material/file-upload form instead of
 * the generic Title/Description/Location shape.
 */
class RequisitionController extends BaseController
{
    private RequisitionHelper $_requisition_helper;

    function __construct($params)
    {
        parent::__construct($params);
        $this->_requisition_helper = new RequisitionHelper($this->db);
    }

    public function insert()
    {
        $columns = ["title", "description", "free_material", "type"];
        $this->_requisition_helper->validate(RequisitionHelper::validations, $columns, $this->post);
        $columns[] = "free_description";
        $columns[] = "created_time";
        $columns[] = "sd_mt_userdb_id";
        $columns[] = "status";
        $this->post["status"] = 10;
        $columns[] = "supervisor";
        // begin transaction
        $this->db->_db->Begin();
        $id = $this->_requisition_helper->insert($columns, $this->post);
        // upload the attached form
        $file_path = $this->_requisition_helper->getFullFile($id);
        if (isset($_FILES["uploaded_file"])) {
            $stored_file_name = SmartFileHelper::moveSingleFile("uploaded_file", $file_path);
            $update_columns = ["doc_loc"];
            $update_data = ["doc_loc" => $stored_file_name];
            $this->_requisition_helper->update($update_columns, $update_data, $id);
        }
        $this->db->_db->commit();
        $this->addLog("RAISED A NEW REQUISITION", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }

    public function update()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $columns = ["status"];
        $this->_requisition_helper->validate(RequisitionHelper::validations, $columns, $this->post);
        $columns[] = "admin_remarks";
        $columns[] = "last_modified_by";
        $columns[] = "last_modified_time";
        $columns[] = "supervisor";
        $columns[] = "date_of_closure";
        $columns[] = "supervisor_description";
        $id = $this->_requisition_helper->update($columns, $this->post, $id);
        $this->addLog("UPDATED A REQUISITION", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }

    public function getAll()
    {
        $sql = "";
        $data_in = [];
        $type = isset($this->post["type"]) ? intval($this->post["type"]) : 0;
        $mode = isset($this->params["mode"]) ? $this->params["mode"] : "user";
        $status = isset($this->params["status"]) ? $this->params["status"] : [100];
        switch ($mode) {
            case 'user':
                $sql = "t1.sd_mt_userdb_id=:user_id AND t1.type=:type";
                $data_in = ["user_id" => SmartAuthHelper::getLoggedInId(), "type" => $type];
                break;
            case 'app':
                $sql = "t1.app_id=:user_id AND t1.type=:type AND status IN (" . implode(",", $status) . ")";
                $data_in = ["user_id" => SmartAuthHelper::getLoggedInId(), "type" => $type];
                break;
            case 'admin':
                $sql = "status IN (" . implode(",", $status) . ") AND t1.type=:type";
                $data_in = ["type" => $type];
                break;
            default:
                break;
        }
        $data = $this->_requisition_helper->getAllData($sql, $data_in);
        $this->response($data);
    }

    public function getOne()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $data = $this->_requisition_helper->getOneData($id);
        $this->response($data);
    }

    public function deleteOne()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $this->_requisition_helper->deleteOneId($id);
        $this->addLog("DELETED A REQUISITION", "", SmartAuthHelper::getLoggedInUserName());
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
        $columns = ["status", "app_time"];
        $dt = ["status" => $action == "approve" ? 10 : 6];
        $columns[] = "last_modified_by";
        $columns[] = "last_modified_time";
        $id = $this->_requisition_helper->update($columns, $dt, $id);
        $this->addLog("UPDATED A REQUISITION", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }

    public function supervisorGetAll()
    {
        $supervisor_id = SmartAuthHelper::getLoggedInId();
        $sql = "t1.supervisor = :supervisor_id AND t1.status = 30";
        $data_in = ["supervisor_id" => $supervisor_id];
        $data = $this->_requisition_helper->getAllData($sql, $data_in);
        $this->response($data);
    }

    // Streams the attached form back, using its real stored extension
    // (doc_loc) rather than assuming .pdf like WorkshopController does.
    public function getOneFile()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $data = $this->_requisition_helper->getOneData($id);
        if (!isset($data->ID) || empty($data->doc_loc)) {
            \CustomErrorHandler::triggerInvalid("File not found");
        }
        $folder = RequisitionHelper::FILE_FOLDER . DS . $id . DS;
        $file_path = SmartFileHelper::getDataPath() . $folder . $data->doc_loc;
        if (!file_exists($file_path)) {
            \CustomErrorHandler::triggerInvalid("File not found");
        }
        $this->responseFileBase64($file_path);
    }
}
