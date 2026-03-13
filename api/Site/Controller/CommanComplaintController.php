<?php

namespace Site\Controller;

use Core\BaseController;
use Core\Helpers\SmartAuthHelper;

use Site\Helpers\CommanComplaintHelper;


class CommanComplaintController extends BaseController
{
    private CommanComplaintHelper $_comman_complaint_helper;

    function __construct($params)
    {
        parent::__construct($params);
        // 
        $this->_comman_complaint_helper = new CommanComplaintHelper($this->db);
    }
    /**
   --- 
   ---
   COMPLAINT TYPES TABLE
   ---
   ---
     */

     public function insertComplaintTypes()
     {
         $columns = ["complaint_type", "complaint_admin"];
         // do validations
         $this->_comman_complaint_helper->validate(CommanComplaintHelper::validations, $columns, $this->post);
         // add extra columns
         $columns[] = "created_time";
         $this->db->_db->Begin();
         // insert and get id
         $id = $this->_comman_complaint_helper->insertComplaintTypes($columns, $this->post);
         // add log
         $this->addLog("ADDED A NEW COMPLAINT TYPE", "", SmartAuthHelper::getLoggedInUserName());
          // commit the transaction and 
         $this->db->_db->commit();
         $this->response($id);
     }
     /**
      * 
      */
     public function updateComplaintTypes()
     {
         $id = isset($this->post["ID"]) ? intval($this->post["ID"]) : 0;
         if ($id < 1) {
             \CustomErrorHandler::triggerInvalid("Invalid ID");
         }
         $columns = ["complaint_type", "complaint_admin"];
         // do validations
         $this->_comman_complaint_helper->validate(CommanComplaintHelper::validations, $columns, $this->post);
         // add extra columns
         $columns[] = "created_time";
         $id = $this->_comman_complaint_helper->updateComplaintTypes($columns, $this->post, $id);
         // add log
         $this->addLog("UPDATED A COMPLAINT TYPE", "", SmartAuthHelper::getLoggedInUserName());
         //
         $this->response($id);
     }
     /**
      * 
      */
     public function getOneComplaintTypes()
     {
         $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
         if ($id < 1) {
             \CustomErrorHandler::triggerInvalid("Invalid ID");
         }
         $data = $this->_comman_complaint_helper->getOneDataComplaintTypes($id);
         $this->response($data);
     }
 
     /**
      * 
      */
 
     public function getAllComplaintTypes()
     {
         $data = $this->_comman_complaint_helper->getAllDataComplaintTypes();
         $this->response($data);
     }
     /**
      * 
      */
     public function deleteOneComplaintTypes()
     {
         $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
         if ($id < 1) {
             \CustomErrorHandler::triggerInvalid("Invalid ID");
         }
         // insert and get id
         $this->_comman_complaint_helper->deleteOneIdComplaintTypes($id);
         // add log
         $this->addLog("DELETED A COMPLAINT TYPE", "", SmartAuthHelper::getLoggedInUserName());
         //
         $out = new \stdClass();
         $out->msg = "Deleted Successfully";
         $this->response($out);
     }
 
     public function getAllComplaintTypesDropDown(){   
         $data = $this->_comman_complaint_helper->getAllDataComplaintTypesDropDown();
         $this->response($data);
     }
 
     public function getComplaintType(){
         $id = isset($this->post["id"]) ? $this->post["id"] : 0;
         if($id < 0){
             \CustomErrorHandler::triggerInvalid("Invalid ID");
         }
         $data = $this->_comman_complaint_helper->getOneDataComplaintTypes($id);
         $this->response($data);
     }
  /**
   --- 
   ---
   COMPLAINT TABLE
   ---
   ---
     */
    public function insert()
    {
        $columns = ["title", "description", "location","type"];
        // do validations
        $this->_comman_complaint_helper->validate(CommanComplaintHelper::validations, $columns, $this->post);
        // add other columns
        $columns[] = "created_time";
        $columns[] = "sd_mt_userdb_id";
        $columns[] = "status";
        $this->post["status"] = 10;
        $columns[] = "supervisor";
        // insert and get id
        $id = $this->_comman_complaint_helper->insert($columns, $this->post);
        // add log
        $this->addLog("RAISED A NEW COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());
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
        $this->_comman_complaint_helper->validate(CommanComplaintHelper::validations, $columns, $this->post);
        // add columns
        $columns[] = "admin_remarks";
        $columns[] = "last_modified_by";
        $columns[] = "last_modified_time";
        $columns[] = "supervisor";
        // insert and get id
        $id = $this->_comman_complaint_helper->update($columns, $this->post, $id);
        // add log
        $this->addLog("UPDATED A COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }

    public function getAll()
    {
        // check the mode received from router
        $sql = "";
        $data_in = [];
        $type = isset($this->post["type"]) ? intval($this->post["type"]) : 0;
        $mode = isset($this->params["mode"]) ? $this->params["mode"] : "user";
        $mode = isset($this->params["mode"]) ? $this->params["mode"] : "supervisor";
        $status = isset($this->params["status"]) ? $this->params["status"] : [100];
        switch ($mode) {
                // indicates the logged user data
            case 'user':
                $sql = "t1.sd_mt_userdb_id=:user_id AND t1.type=:type";
                $data_in = ["user_id" => SmartAuthHelper::getLoggedInId(),"type" => $type];
                break;
            case 'app':
                $sql = "t1.app_id=:user_id AND t1.type=:type AND status IN (".implode(",", $status).")";
                $data_in = ["user_id" => SmartAuthHelper::getLoggedInId(),"type" => $type];
                break;
            case 'admin':
                $sql = "status IN (".implode(",", $status).") AND t1.type=:type";
                $data_in = ["type" => $type];
                break;
            // supervisor view: only requests awaiting this supervisor’s approval
            // case 'supervisor':
            // $sql = "status IN (".implode(",", $status).")";
            // $data_in = ["type" => $type];
            // break;
            default:
            break;
        } 
        $data = $this->_comman_complaint_helper->getAllData($sql, $data_in);
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
        $data = $this->_comman_complaint_helper->getOneData($id);
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
        $this->_comman_complaint_helper->deleteOneId($id);
        // add log
        $this->addLog("DELETED A COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());
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

        $columns = ["status","app_time"];
        //
        $dt = ["status"=>$action=="approve" ? 10 : 6];
        // add columns
        $columns[] = "last_modified_by";
        $columns[] = "last_modified_time";
        // insert and get id
        $id = $this->_comman_complaint_helper->update($columns, $dt, $id);
        // add log
        $this->addLog("UPDATED A COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }
    public function supervisorGetAll()
   {
    $supervisor_id = SmartAuthHelper::getLoggedInId();

    $sql = "t1.supervisor = :supervisor_id AND t1.status = 30";
    $data_in = ["supervisor_id" => $supervisor_id];

    $data = $this->_comman_complaint_helper->getAllData($sql, $data_in);
    $this->response($data);
    }

}
