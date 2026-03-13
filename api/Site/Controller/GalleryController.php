<?php 

namespace Site\Controller;

use Core\BaseController;
use Core\Helpers\SmartFileHelper;
use Core\Helpers\SmartAuthHelper;
use Site\Helpers\GalleryHelper;


class GalleryController extends BaseController{
  
  private GalleryHelper $_gallery_helper;
    function __construct($params)
    {
        parent::__construct($params);
        // 
        $this->_gallery_helper = new GalleryHelper($this->db);
    }

   /**
     * 
     */
    public function insert(){
        $validate_columns = [ "event_name","uploaded_file","description"];        
        // do validations
        $this->_gallery_helper->validate(GalleryHelper::validations,$validate_columns,$this->post);
        $columns = [ "event_name","created_by","created_time","description"]; 
         // insert and get id
         $id = $this->_gallery_helper->insert($columns,$this->post);
          // process the file
        $file_path = $this->_gallery_helper->getFullFile($id);
        if( isset($_FILES["uploaded_file"])){
        // move the uploaded file to path 
        $stored_file_path = SmartFileHelper::moveSingleFile("uploaded_file",$file_path);
        // update the file path in table
        $update_columns = ["img_loc"];
        $update_data = ["img_loc"=>$stored_file_path];
        $this->_gallery_helper->update($update_columns,$update_data,$id);   
        } 
         // add log
        $this->addLog("ADDED AN EVENT IMAGE","",SmartAuthHelper::getLoggedInUserName());
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
        $validate_columns = [ "event_name","description"];        
        // do validations
        $this->_gallery_helper->validate(GalleryHelper::validations,$validate_columns,$this->post);
        $columns = [ "event_name","created_by","created_time","description"]; 
         // insert and get id
         $updateId = $this->_gallery_helper->update($columns,$this->post, $id);
          // process the file
        $file_path = $this->_gallery_helper->getFullFile($updateId);
        if( isset($_FILES["uploaded_file"])){
        // move the uploaded file to path 
        $stored_file_path = SmartFileHelper::moveSingleFile("uploaded_file",$file_path);
        // update the file path in table
        $update_columns = ["img_loc"];
        $update_data = ["img_loc"=>$stored_file_path];
        $this->_gallery_helper->update($update_columns,$update_data,$updateId);   
        } 
         // add log
        $this->addLog("UPDATED AN EVENT IMAGE","",SmartAuthHelper::getLoggedInUserName());
        //
         $this->response($updateId);
    }
    /**
     * 
     */

    public function getAll(){      
        $data = $this->_gallery_helper->getAllData();
        $this->response($data);
    }
      /**
     * 
     */

     public function getAllWithEventName(){    
        $name = isset($this->post["event_name"]) ? intval($this->post["event_name"]) : "";
        if($name < 1){
            \CustomErrorHandler::triggerInvalid("Invalid Event Name");
        }   
        $sql = "event_name=:name";
        $data_in = ["name" => $name];
        $data = $this->_gallery_helper->getAllData($sql,$data_in);
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
        $data = $this->_gallery_helper->getOneData($id);
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
        $this->_gallery_helper->deleteOneId($id);
        // add log
        $this->addLog("REMOVED AN EVENT IMAGE","",SmartAuthHelper::getLoggedInUserName());
        //
        $out = new \stdClass();
        $out->msg = "Removed Successfully";
    }   
    
    public function getOneImage(){
        $id = isset($this->params["id"]) ? $this->params["id"] : 0;
        if($id < 0){
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $data = $this->_gallery_helper->getOneData($id);
       // var_dump($data);
        // 
        $pdf_path =  $this->_gallery_helper->getFolder($id) . $data->img_loc;
       // echo $pdf_path;
       $this->responseImage($pdf_path);
    }
 

}