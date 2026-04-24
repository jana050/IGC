<?php 

namespace Site\Controller;

use Core\BaseController;

use Core\Helpers\SmartFileHelper;
use Core\Helpers\SmartAuthHelper;
use Site\Helpers\BudgetTypeHelper;


class BudgetTypeController extends BaseController{
    
    private BudgetTypeHelper $_budget_type_helper;

    function __construct($params)
    {
        parent::__construct($params);
        // 
        $this->_budget_type_helper = new BudgetTypeHelper($this->db);
    }

   /**
     * 
     */
    public function insert(){
        $columns = [ "budget_type", "budget_no", "amount"];
        // do validations
        $this->_budget_type_helper->validate(BudgetTypeHelper::validations,$columns,$this->post);
         // columns to be inserted

         // begin transation
         $this->db->_db->Begin();
         // insert and get id
         $id = $this->_budget_type_helper->insert($columns,$this->post);
         // commit the transaction and 
         $this->db->_db->commit();
         // add log
        $this->addLog("UPLOADED A BUDGET TYPE","",SmartAuthHelper::getLoggedInUserName());
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
        $columns = ["budget_type", "budget_no", "amount"];
        // do validations
        $this->_budget_type_helper->validate(BudgetTypeHelper::validations,$columns,$this->post);

        // insert and get id
        $id = $this->_budget_type_helper->update($columns,$this->post,$id);
        // add log
        $this->addLog("UPDATED A BUDGET TYPE","",SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }

    public function getAll(){      
        // insert and get id
        $data = $this->_budget_type_helper->getAllData();
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
        $data = $this->_budget_type_helper->getOneData($id);
        $this->response($data);
    }
    /**
     * 
     */
    /*
    public function deleteOne(){  
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if($id < 1){
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }    
        // insert and get id
        $this->_budget_type_helper->deleteOneId($id);
        // add log
        $this->addLog("DELETED A BUDGET TYPE","",SmartAuthHelper::getLoggedInUserName());
        //
        $out = new \stdClass();
        $out->msg = "Deleted Successfully";
        $this->response($out);
    }
    */
 public function deleteOne()
{
    $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
    if ($id < 1) {
        \CustomErrorHandler::triggerInvalid("Invalid ID");
    }

    // 🔴 CHECK IF USED
    if ($this->_budget_type_helper->isUsedInOtherTables($id)) {
        \CustomErrorHandler::triggerInvalid("This Budget Type cannot be deleted because it is already used in other records.");
    }

    $this->_budget_type_helper->deleteOneId($id);

    $this->addLog("DELETED A BUDGET TYPE", "", SmartAuthHelper::getLoggedInUserName());

    $out = new \stdClass();
    $out->msg = "Deleted Successfully";
    $this->response($out);
}
    /**
     * 
     */
  public function getAllSelect()
{
    $data = $this->_budget_type_helper->getAllSelect();
    $this->response($data);
}

}