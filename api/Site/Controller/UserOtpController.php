<?php 

namespace Site\Controller;

use Core\BaseController;
use Core\Helpers\SmartGeneral;
use Core\Helpers\SmartAuthHelper;
use Site\Helpers\UserOtpHelper;


class UserOtpController extends BaseController{
    private UserOtpHelper $_user_otp_helper;

    function __construct($params)
    {
        parent::__construct($params);
        // 
        $this->_user_otp_helper = new UserOtpHelper($this->db);
    }

   /**
     * 
     */
    public function insert(){
        $columns = ["sd_mt_userdb_id","mobile_no","otp_code_hast"];
        // do validations
        $this->_user_otp_helper->validate(UserOtpHelper::validations,$columns,$this->post);
        // add other columns
        $columns[]="otp_created_time"; 
        // insert and get id
        $id = $this->_user_otp_helper->insert($columns,$this->post);
        // add log
        $this->addLog("OTP SENT TO USER","",SmartAuthHelper::getLoggedInUserName());
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
        $columns = ["mobile_no","otp_code_hast"];
        // do validations
        $this->_user_otp_helper->validate(UserOtpHelper::validations,$columns,$this->post);
        // insert and get id
        $id = $this->_user_otp_helper->update($columns,$this->post,$id);
        // add log
        $this->addLog("OTP RESENT TO USER","",SmartAuthHelper::getLoggedInUserName());
        //
        $this->response($id);
    }

    public function getAll(){      
        // insert and get id
        $data = $this->_user_otp_helper->getAllData();
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
        $data = $this->_user_otp_helper->getOneData($id);
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
        $this->_user_otp_helper->deleteOneId($id);
        // add log
        $this->addLog("OTP FACILITY TERMINATED FOR THIS USER","",SmartAuthHelper::getLoggedInUserName());
        //
        $out = new \stdClass();
        $out->msg = "Deleted Successfully";
        $this->response($out);
    }

   


}