<?php 

namespace Site\Controller;

use Core\BaseController;


use Site\Helpers\ActivityHelper as ActivityHelper;
use Core\Helpers\SmartAuthHelper;

class ActivityController extends BaseController{
    
    private ActivityHelper $_activiy_helper;

    function __construct($params)
    {
        parent::__construct($params);
        // 
        $this->_activiy_helper = new ActivityHelper($this->db);
    }

   /**
     * 
     */
    public function insert(){
        $columns = [ "activity_title","activity_desc","activity_image","name","designation","order_no"];
        // do validations
        $this->_activiy_helper->validate(ActivityHelper::validations,$columns,$this->post);
        // add other columns
        $columns[]="created_by"; 
        $columns[]="created_time"; 
        // insert and get id
        $id = $this->_activiy_helper->insert($columns,$this->post);
        $this->addLog("INSERTED ACTIVITY","",SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }
  

    public function update(){
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if($id < 1){
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $columns = [ "activity_title","activity_desc","activity_image","name","designation","order_no"];
        // do validations
        $this->_activiy_helper->validate(ActivityHelper::validations,$columns,$this->post);
         // add other columns
         $columns[]="last_modified_time";
        // insert and get id
        $id = $this->_activiy_helper->update($columns,$this->post,$id);
        $this->addLog("UPDATED ACTIVITY","",SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }

    public function getAll(){      
        // insert and get id
        $data = $this->_activiy_helper->getAllData();
        $this->response($data);
    }
    /**
     * 
     */
    public function getOne(){  
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if($id < 1){
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }    
        // insert and get id
        $data = $this->_activiy_helper->getOneData($id);
        $this->response($data);
    }
    /**
     * 
     */
    public function deleteOne(){  
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if($id < 1){
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }    
        // insert and get id
        $this->_activiy_helper->deleteOneId($id);
        //
        $this->addLog("DELETED ACTIVITY","",SmartAuthHelper::getLoggedInUserName());
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
        
         $data =  $this->_activiy_helper->getOneData($id);
         // 
         //$pdf_path =  $this->_helper->getFolder($id) . $data->home_image;
         // echo $pdf_path;
         $this->responseImageDb($data->activity_image);
     }

}