<?php 

namespace Site\Controller;

use Core\BaseController;
use Core\Helpers\SmartAuthHelper;

use Site\Helpers\DocTypeHelper;


class DocTypeController extends BaseController{
    private DocTypeHelper $_doc_type_helper;

    function __construct($params)
    {
        parent::__construct($params);
        // 
        $this->_doc_type_helper = new DocTypeHelper($this->db);
    }

   /**
     * 
     */
    public function insert(){
        $columns = [ "doc_type","doc_category","show_chart"];
        // do validations
        $this->_doc_type_helper->validate(DocTypeHelper::validations,$columns,$this->post);
        //var_dump($this->post);
      /*  $cat = isset($this->post["doc_category"]) ?
         (($this->post["doc_category"] === "1")? "Knowledge" : "Work") : "Knowledge";
        $this->post["doc_category"] = $cat;*/
        // add other columns
        $columns[] = "created_time";
        // insert and get id
        $id = $this->_doc_type_helper->insert($columns,$this->post);
        // add log
        $this->addLog("ADDED A DOCUMENT TYPE","",SmartAuthHelper::getLoggedInUserName());
        //
        $this->response($id);
    }
    /**
     * 
     */
    public function update(){
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if($id < 1){
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $columns = [ "doc_type","doc_category","show_chart"];
        // do validations
        $this->_doc_type_helper->validate(DocTypeHelper::validations,$columns,$this->post);
        // add columns
        // insert and get id
        $id = $this->_doc_type_helper->update($columns,$this->post,$id);
        // add log
        $this->addLog("UPDATED DOCUMENT TYPE","",SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }

    public function getAll(){ 
        $data = $this->_doc_type_helper->getAllData();
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
        $data = $this->_doc_type_helper->getOneData($id);
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
        $this->_doc_type_helper->deleteOneId($id);
        // add log
        $this->addLog("DELETED DOCUMENT TYPE","",SmartAuthHelper::getLoggedInUserName());
        //
        $out = new \stdClass();
        $out->msg = "Deleted Successfully";
        $this->response($out);
    }


 public function getAllDocType(){
    $category = isset($this->post["doc_category"]) ? intval($this->post["doc_category"]) : 0;
    $data = $this->_doc_type_helper->getAllDocTypeDropDown($category);
    $this->response($data);
 }
 public function getAllDocTypeCategory(){   
        $data = $this->_doc_type_helper->getAllDocType();
        $this->response($data);
    }
}