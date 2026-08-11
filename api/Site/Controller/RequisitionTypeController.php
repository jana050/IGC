<?php

namespace Site\Controller;

use Core\BaseController;
use Core\Helpers\SmartAuthHelper;

use Site\Helpers\RequisitionTypeHelper;

class RequisitionTypeController extends BaseController
{
    private RequisitionTypeHelper $_requisition_type_helper;

    function __construct($params)
    {
        parent::__construct($params);
        $this->_requisition_type_helper = new RequisitionTypeHelper($this->db);
    }

    public function insert()
    {
        $columns = ["requisition_type", "requisition_admin", "requisition_supervisor"];
        $this->_requisition_type_helper->validate(RequisitionTypeHelper::validations, $columns, $this->post);
        $columns[] = "created_time";
        $this->db->_db->Begin();
        $id = $this->_requisition_type_helper->insertRequisitionTypes($columns, $this->post);
        $this->addLog("ADDED A NEW REQUISITION TYPE", "", SmartAuthHelper::getLoggedInUserName());
        $this->db->_db->commit();
        $this->response($id);
    }

    public function update()
    {
        $id = isset($this->post["ID"]) ? intval($this->post["ID"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $columns = ["requisition_type", "requisition_admin", "requisition_supervisor"];
        $this->_requisition_type_helper->validate(RequisitionTypeHelper::validations, $columns, $this->post);
        $columns[] = "created_time";
        $id = $this->_requisition_type_helper->updateRequisitionTypes($columns, $this->post, $id);
        $this->addLog("UPDATED A REQUISITION TYPE", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }

    public function getOne()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $data = $this->_requisition_type_helper->getOneDataRequisitionTypes($id);
        $this->response($data);
    }

    public function getAll()
    {
        $data = $this->_requisition_type_helper->getAllDataRequisitionTypes();
        $this->response($data);
    }

    public function deleteOne()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $this->_requisition_type_helper->deleteOneIdRequisitionTypes($id);
        $this->addLog("DELETED A REQUISITION TYPE", "", SmartAuthHelper::getLoggedInUserName());
        $out = new \stdClass();
        $out->msg = "Deleted Successfully";
        $this->response($out);
    }

    public function getAllDropDown()
    {
        $data = $this->_requisition_type_helper->getAllDataRequisitionTypesDropDown();
        $this->response($data);
    }
}
