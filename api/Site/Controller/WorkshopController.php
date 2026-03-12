<?php

namespace Site\Controller;

use Core\BaseController;
use Core\Helpers\SmartGeneral;
use Core\Helpers\SmartFileHelper;
use Core\Helpers\SmartAuthHelper;
use Core\Helpers\SmartData as Data;


use Site\Helpers\WorkshopHelper;


class WorkshopController extends BaseController
{
    private WorkshopHelper $_workshop_helper;

    function __construct($params)
    {
        parent::__construct($params);
        // 
        $this->_workshop_helper = new WorkshopHelper($this->db);
    }

    /**
     * 
     */
    public function insert()
    {
        $valid_columns = ["name", "description","free_material"];
        // do validations
        $this->_workshop_helper->validate(WorkshopHelper::validations, $valid_columns, $this->post);
        // add other columns
        $columns[] = "name";
        $columns[] = "description";
        $columns[] = "free_material";
        $columns[] = "free_description";
        $columns[] = "completion_date";
        $columns[] = "created_time";
        $columns[] = "sd_mt_userdb_id";
        $columns[] = "status";
        $this->post["status"] = 10;
        // begin transation
        $this->db->_db->Begin();
        // insert and get id
        $id = $this->_workshop_helper->insert($columns, $this->post);
        // upload the document
        $file_path = $this->_workshop_helper->getFullFile($id);
        if( isset($_FILES["uploaded_file"])){
        // move the uploaded file to path 
        $stored_file_path = SmartFileHelper::moveSingleFile("uploaded_file",$file_path);
        // update the file path in table
        $update_columns = ["location"];
        $update_data = ["location"=>$stored_file_path];
        $this->_workshop_helper->update($update_columns,$update_data,$id);   
        } 
          // commit the transaction and 
          $this->db->_db->commit();
        // add log
        $this->addLog("ADDED A WORKSHOP DOC", "", SmartAuthHelper::getLoggedInUserName());
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
        $valid_columns = ["status"];
        // do validations
        $this->_workshop_helper->validate(WorkshopHelper::validations, $valid_columns, $this->post);
        // add columns
        $columns = ["status","admin_remarks"];
        $columns[] = "last_modified_by";
        $columns[] = "last_modified_time";
        // insert and get id
        $id = $this->_workshop_helper->update($columns, $this->post, $id);
        // add log
        $this->addLog("UPDATED A WORKSHOP DOC", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }

    private function file_check($id){
        $pdf_path =  $this->_workshop_helper->getFullFile($id) .".pdf";
        return file_exists(SmartFileHelper::getDataPath()  . $pdf_path) ? true : false;
    }
    public function getAll()
    {
        // check the mode received from router
        $sql = "";
        $data_in = [];
        $mode = isset($this->params["mode"]) ? $this->params["mode"] : "user";
        $status = isset($this->params["status"]) ? $this->params["status"] : [100];
        switch ($mode) {
                // indicates the logged user data
            case 'user':
                $sql = "t1.sd_mt_userdb_id=:user_id";
                $data_in = ["user_id" => SmartAuthHelper::getLoggedInId()];
                break;
            case 'admin':
                $sql = "status IN (".implode(",", $status).")";
                break;
            default:
                break;
        } 
        $data = $this->_workshop_helper->getAllData($sql, $data_in);
        $out = [];
        foreach($data as $obj){
            $obj->file_check = $this->file_check($obj->ID);
            $out[] = $obj;
        }
        $this->response($out);
        //$this->response($data);
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
        $data = $this->_workshop_helper->getOneData($id);
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
        $this->_workshop_helper->deleteOneId($id);
        // add log
        $this->addLog("DELETED A WORKSHOP DOC", "", SmartAuthHelper::getLoggedInUserName());
        //
        $out = new \stdClass();
        $out->msg = "Deleted Successfully";
        $this->response($out);
    }

    public function getOnePdf()
    {
        $id = isset($this->post["id"]) ? $this->post["id"] : 0;
        if ($id < 0) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
      //  $data =  $this->_workshop_helper->getOneData($id);
        // 
        $pdf_path =  $this->_workshop_helper->getFullFile($id) . ".pdf";
        // echo $pdf_path;
        $this->responseFileBase64(SmartFileHelper::getDataPath()  . $pdf_path);
    }

   
}
