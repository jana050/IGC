<?php

namespace Site\Controller;

use Core\BaseController;


use Site\Helpers\HistoryHelper;
use Core\Helpers\SmartAuthHelper;
use Core\Helpers\SmartFileHelper;

class HistoryController extends BaseController
{

    private HistoryHelper $_history_helper;

    function __construct($params)
    {
        parent::__construct($params);
        // 
        $this->_history_helper = new HistoryHelper($this->db);
    }

    /**
     * 
     */
    public function insert()
    {
        $valid_columns = ["description", "uploaded_file"];
        // do validations
        $this->_history_helper->validate(HistoryHelper::validations, $valid_columns, $this->post);
        $columns = ["description"];
        $id = $this->_history_helper->insert($columns, $this->post);

        $file_path = $this->_history_helper->getFullFile($id);
        // move the uploaded file to path 
        $stored_file_path = SmartFileHelper::moveSingleFile("uploaded_file", $file_path);
        // update the file path in table
        $update_columns = ["file_loc"];
        $update_data = ["file_loc" => $stored_file_path];
        $this->_history_helper->update($update_columns, $update_data, $id);
        $this->addLog("ADDED HISTORY DOCUMENT", "", SmartAuthHelper::getLoggedInUserName());
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
        // 
        $valid_columns = ["description"];
        // do validations
        $this->_history_helper->validate(HistoryHelper::validations, $valid_columns, $this->post);
        $columns = ["description"];
        $updateId = $this->_history_helper->update($columns, $this->post, $id);
        if (isset($_FILES["uploaded_file"])) {
            $file_path = $this->_history_helper->getFullFile($updateId);
            // move the uploaded file to path 
            $stored_file_path = SmartFileHelper::moveSingleFile("uploaded_file", $file_path);
            // update the file path in table
            $update_columns = ["file_loc"];
            $update_data = ["file_loc" => $stored_file_path];
            $this->_history_helper->update($update_columns, $update_data, $updateId);
        }
        // $this->addLog("UPDATED HISTORY DOCUMENT", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($updateId);
    }




    public function getAll()
    {
        // insert and get id

        $data = $this->_history_helper->getAllData();
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
        $data = $this->_history_helper->getOneData($id);
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
        $this->_history_helper->deleteOneId($id);
        //
        $this->addLog("DELETED HISTORY DOCUMENT", "", SmartAuthHelper::getLoggedInUserName());
        $out = new \stdClass();
        $out->msg = "Deleted Successfully";
        $this->response($out);
    }
    /**
     * 
     */
    public function getOneImage()
    {
        $id = isset($this->params["id"]) ? $this->params["id"] : 0;

        $data = $this->_history_helper->getOneData($id);
        // 
        $pdf_path = $this->_history_helper->getFolder($id) . $data->file_loc;
        // echo $pdf_path;
        $this->responseImage($pdf_path);
    }
}
