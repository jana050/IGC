<?php 

namespace Site\Controller;

use Core\BaseController;
use Core\Helpers\SmartData as Data;
use Core\Helpers\SmartFileHelper;
use Core\Helpers\SmartAuthHelper;
use Site\Helpers\AwardsHelper;


class AwardsController extends BaseController{
  
  private AwardsHelper $_awards_helper;
    function __construct($params)
    {
        parent::__construct($params);
        // 
        $this->_awards_helper = new AwardsHelper($this->db);
    }

   /**
     * 
     */
    public function insert(){
        $validate_columns = [ "title","uploaded_file","award_date"];    
        $this->post["award_date"] = Data::post_data("award_date", "DATE");    
        // do validations
        $this->_awards_helper->validate(AwardsHelper::validations,$validate_columns,$this->post);
        $columns = [ "title","award_date","created_by","created_time"]; 
         // insert and get id
         $id = $this->_awards_helper->insert($columns,$this->post);
          // process the file
        $file_path = $this->_awards_helper->getFullFile($id);
        if( isset($_FILES["uploaded_file"])){
        // move the uploaded file to path 
        $stored_file_path = SmartFileHelper::moveSingleFile("uploaded_file",$file_path);
        // update the file path in table
        $update_columns = ["img_loc"];
        $update_data = ["img_loc"=>$stored_file_path];
        $this->_awards_helper->update($update_columns,$update_data,$id);   
        } 
         // add log
        $this->addLog("ADDED AN AWARD IMAGE","",SmartAuthHelper::getLoggedInUserName());
        //
         $this->response($id);
    }
    /**
     * 
     */
    public function update(){
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        // 
        $validate_columns = [ "title","uploaded_file","award_date"];    
        $this->post["award_date"] = Data::post_data("award_date", "DATE");    
        // do validations
        $this->_awards_helper->validate(AwardsHelper::validations,$validate_columns,$this->post);
        $columns = [ "title","award_date","created_by","created_time"]; 
         // insert and get id
         $updateId = $this->_awards_helper->update($columns,$this->post, $id);
          // process the file
        $file_path = $this->_awards_helper->getFullFile($updateId);
        if( isset($_FILES["uploaded_file"])){
        // move the uploaded file to path 
        $stored_file_path = SmartFileHelper::moveSingleFile("uploaded_file",$file_path);
        // update the file path in table
        $update_columns = ["img_loc"];
        $update_data = ["img_loc"=>$stored_file_path];
        $this->_awards_helper->update($update_columns,$update_data,$updateId);   
        } 
         // add log
        $this->addLog("UPDATED AN AWARD IMAGE","",SmartAuthHelper::getLoggedInUserName());
        //
         $this->response($updateId);
    }
    /**
     * 
     */

    public function getAll(){      
        $data = $this->_awards_helper->getAllData();
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
        $data = $this->_awards_helper->getOneData($id);
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
        $this->_awards_helper->deleteOneId($id);
        // add log
        $this->addLog("REMOVED AN AWARD IMAGE","",SmartAuthHelper::getLoggedInUserName());
        //
        $out = new \stdClass();
        $out->msg = "Removed Successfully";
    }    
     /**
     * 
     */
    public function getOneImage(){
        $id = isset($this->params["id"]) ? $this->params["id"] : 0;
        if($id < 0){
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $data = $this->_awards_helper->getOneData($id);
       // var_dump($data);
        // 
        $pdf_path =  $this->_awards_helper->getFolder($id) . $data->img_loc;
       // echo $pdf_path;
       $this->responseImage($pdf_path);
    }


}