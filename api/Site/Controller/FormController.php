<?php 

namespace Site\Controller;

use Core\BaseController;

use Core\Helpers\SmartFileHelper;
use Core\Helpers\SmartAuthHelper;
use Site\Helpers\FormHelper;


class FormController extends BaseController{
    
    private FormHelper $_form_helper;

    function __construct($params)
    {
        parent::__construct($params);
        // 
        $this->_form_helper = new FormHelper($this->db);
    }

   /**
     * 
     */
    public function insert(){
        $columns = [ "form_name"];
        // do validations
        $this->_form_helper->validate(FormHelper::validations,$columns,$this->post);
         // columns to be inserted
         $columns = ["form_name","created_by","form_status"];       
         $this->post["form_status"] = 5;
         // begin transation
         $this->db->_db->Begin();
         // insert and get id
         $id = $this->_form_helper->insert($columns,$this->post);
         // commit the transaction and 
         $this->db->_db->commit();
         // add log
        $this->addLog("UPLOADED A FORM","",SmartAuthHelper::getLoggedInUserName());
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
        $columns = ["form_status"];
        // do validations
        $this->_form_helper->validate(FormHelper::validations,$columns,$this->post);
         // add other columns
         $columns[]="created_by";
        // insert and get id
        $id = $this->_form_helper->update($columns,$this->post,$id);
        // add log
        $this->addLog("UPDATED A FORM","",SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }

    public function getAll(){      
        // insert and get id
        $data = $this->_form_helper->getAllData();
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
        $data = $this->_form_helper->getOneData($id);
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
        $this->_form_helper->deleteOneId($id);
        // add log
        $this->addLog("DELETED A FORM","",SmartAuthHelper::getLoggedInUserName());
        //
        $out = new \stdClass();
        $out->msg = "Deleted Successfully";
        $this->response($out);
    }
    /**
     * 
     */
    public function get_pdf(){
        $id = isset($this->post["id"]) ? $this->post["id"] : 0;
        if($id < 0){
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $data = $this->_form_helper->getOneData($id);
        // 
        $pdf_path =  $this->_form_helper->getFullFile($id) .".pdf";
        //echo $pdf_path;
        $this->responsePdf($pdf_path);
    }
}