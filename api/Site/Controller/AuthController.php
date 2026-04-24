<?php

namespace Site\Controller;

use Core\BaseController;
use Core\Helpers\SmartGeneral;
use Core\Helpers\SmartData as Data;
use Core\Helpers\SmartLogger as Logger;
use Site\Helpers\BackupHelper;
use Site\Helpers\MeetRoomHelper;
// site helpers
use Site\Helpers\UserHelper;
use Site\Helpers\UserRoleHelper;
use Site\Helpers\SiteHelper;
use Site\Helpers\CommanComplaintHelper;
use Site\Helpers\OrganisationHelper;


class AuthController extends BaseController
{

    private UserHelper $_user_helper;
    private UserRoleHelper $_user_role_helper;
    private SiteHelper $_site_helper;
    private MeetRoomHelper $_meet_room_helper;
    private CommanComplaintHelper $_complaint_helper;
    private OrganisationHelper $_org_helper;

    function __construct($params)
    {
        parent::__construct($params);
        // 
        $this->_user_helper = new UserHelper($this->db);
        //
        $this->_user_role_helper = new UserRoleHelper($this->db);
        //
        $this->_site_helper = new SiteHelper($this->db);
        // 
        $this->_meet_room_helper = new MeetRoomHelper($this->db);
        //
        $this->_complaint_helper = new CommanComplaintHelper($this->db);
        //
        $this->_org_helper = new OrganisationHelper($this->db);
    }

    private function checkMeetType($role_id)
    {
        $role_memebers = $this->_meet_room_helper->getMeetTypesWithRole($role_id, "member_role_id");
        $roles = [];
        foreach ($role_memebers as $obj) {
            $roles[] = "SD_" . $obj->type . "_MEMBERS";
        }
        $role_admin = $this->_meet_room_helper->getMeetTypesWithRole($role_id, "admin_role_id");
        foreach ($role_admin as $obj) {
            $roles[] = "SD_" . $obj->type . "_ADMIN";
        }
        return $roles;
    }

    private function getRoles($id, $initial)
    {
        $roles = $this->_user_role_helper->getSelectedRolesWithUserId($id);
        $role_names = $initial;
        $electrical_admin_role =  $this->_site_helper->getOneValue("electrical_admin");
        $electrical_supervisor_role =  $this->_site_helper->getOneValue("electrical_supervisor");
        $network_admin_role =  $this->_site_helper->getOneValue("network_admin");
        $network_supervisor_role =  $this->_site_helper->getOneValue("network_supervisor");
        $telephone_admin_role =  $this->_site_helper->getOneValue("telephone_admin");
        $telephone_supervisor_role =  $this->_site_helper->getOneValue("telephone_supervisor");
        $mechanical_admin_role =  $this->_site_helper->getOneValue("mechanical_admin");
        $mechanical_supervisor_role =  $this->_site_helper->getOneValue("mechanical_supervisor");
        $workshop_admin_role =  $this->_site_helper->getOneValue("workshop_admin");
        $workshop_supervisor_role =  $this->_site_helper->getOneValue("workshop_supervisor");

        $acv_shutdown_admin_role =  $this->_site_helper->getOneValue("acv_shutdown");
        $acv_shutdown_supervisor_role =  $this->_site_helper->getOneValue("acv_shutdown_supervisor");
        $elec_shutdown_admin_role =  $this->_site_helper->getOneValue("elec_shutdown");
        $elec_shutdown_supervisor_role =  $this->_site_helper->getOneValue("elec_shutdown_supervisor");
        //
        // $iibcc_approver_role =  $this->_site_helper->getOneValue("iibcc_committee_approver");
        $iibcc_approver_role =  $this->_site_helper->getOneValue("iibcc_approver");
        //
        $lpc_approver_role =  $this->_site_helper->getOneValue("lpc_approver");
        // system admin
        $system_admin = $this->_site_helper->getOneValue("system_admin");
        // System supervisor
        $system_supervisor = $this->_site_helper->getOneValue("system_supervisor");
        //
        $temp_advance_supervisor =  $this->_site_helper->getOneValue("physically_approved");
        // 
        $mbook_issue_supervisor =  $this->_site_helper->getOneValue("mbook_issue_supervisor");
        // 
        $radiology_lab =  $this->_site_helper->getOneValue("radiology_lab");
        // 
        $radiology_hp =  $this->_site_helper->getOneValue("radiology_hp");
        //
        $rfidcard_incharge =  $this->_site_helper->getOneValue("rfidcard_incharge");
        //
        $iibcc_committee_member =  $this->_site_helper->getOneValue("iibcc_committee_member");
        //
        $iibcc_committee_approver =  $this->_site_helper->getOneValue("iibcc_committee_approver");
        //
        $iibcc_committee_chairman =  $this->_site_helper->getOneValue("iibcc_committee_chairman");
        //
        $lpc_commiitee_approver =  $this->_site_helper->getOneValue("lpc_committee_approver");
        //
        $lpc_commiitee_chairman =  $this->_site_helper->getOneValue("lpc_committee_chairman");
        //
        $lpc_commiitee_member =  $this->_site_helper->getOneValue("lpc_committee_member");
        //
        $financial_approval =  $this->_site_helper->getOneValue("financial_approval");
        //
        $gem_direct_chairman =  $this->_site_helper->getOneValue("gem_direct_chairman");
        $gem_direct_vetter =  $this->_site_helper->getOneValue("gem_direct_vetter");
        //


        foreach ($roles as $role) {

            $role_names[] = $role->label;
            //
              if ($role->value == intval($financial_approval)) {
                $role_names[] = "SD_FINANIAL_APPROVAL";
            }
            if ($role->value == intval($gem_direct_chairman)) {
                $role_names[] = "SD_GEM_DIRECT_CHAIRMAN";
            }
            if ($role->value == intval($gem_direct_vetter)) {
                $role_names[] = "SD_GEM_DIRECT_VETTER";
            }
            //
            if ($role->value == intval($lpc_commiitee_member)) {
                $role_names[] = "SD_LPC_COMMITTEE_MEMBER";
            }
            if ($role->value == intval($lpc_commiitee_approver)) {
                    $role_names[] = "SD_LPC_COMMITTEE_APPROVER";
            }
             if ($role->value == intval($lpc_commiitee_chairman)) {
                        $role_names[] = "SD_LPC_COMMITTEE_CHAIRMAN";
            }
             if ($role->value == intval($iibcc_committee_member)) {
                $role_names[] = "SD_IIBCC_COMMITTEE_MEMBER";
            }
             if ($role->value == intval($iibcc_committee_approver)) {
                $role_names[] = "SD_IIBCC_COMMITTEE_APPROVER";
            }
             if ($role->value == intval($iibcc_committee_chairman)) {
                $role_names[] = "SD_IIBCC_COMMITTEE_CHAIRMAN";
            }
            if ($role->value == intval($temp_advance_supervisor)) {
                $role_names[] = "SD_TEMP_SUPERVISOR";
            }
            if ($role->value == intval($mbook_issue_supervisor)) {
                $role_names[] = "SD_MBOOK_ISSUE_APPROVAL";
            }
            if ($role->value == intval($radiology_lab)) {
                $role_names[] = "SD_RADIOLOGICAL_LAB_INCHARGE";
            }
            if ($role->value == intval($radiology_hp)) {
                $role_names[] = "SD_RADIOLOGICAL_HP";
            }
            if ($role->value == intval($rfidcard_incharge)) {
                $role_names[] = "SD_RFIDCARD_INCHARGE";
            }
            if ($role->value == intval($electrical_admin_role)) {
                $role_names[] = "SD_ELE_ADMIN";
            }
            if ($role->value == intval($electrical_supervisor_role)) {
                $role_names[] = "SD_ELE_SUPERVISOR";
            }
            if ($role->value == intval($network_admin_role)) {
                $role_names[] = "SD_NW_ADMIN";
            }
            if ($role->value == intval($network_supervisor_role)) {
                $role_names[] = "SD_NW_SUPERVISOR";
            }
            if ($role->value == intval($telephone_admin_role)) {
                $role_names[] = "SD_TP_ADMIN";
            }
            if ($role->value == intval($telephone_supervisor_role)) {
                $role_names[] = "SD_TP_SUPERVISOR";
            }
            if ($role->value == intval($mechanical_admin_role)) {
                $role_names[] = "SD_MECH_ADMIN";
            }
            if ($role->value == intval($mechanical_supervisor_role)) {
                $role_names[] = "SD_MECH_SUPERVISOR";
            }
            if ($role->value == intval($workshop_admin_role)) {
                $role_names[] = "SD_WORK_ADMIN";
            }
            if ($role->value == intval($workshop_supervisor_role)) {
                $role_names[] = "SD_WORK_SUPERVISOR";
            }
            if ($role->value == intval($acv_shutdown_admin_role)) {
                $role_names[] = "SD_ACV_SHUT_ADMIN";
            }
            if ($role->value == intval($acv_shutdown_supervisor_role)) {
                $role_names[] = "SD_ACV_SHUT_SUPERVISOR";
            }
            if ($role->value == intval($elec_shutdown_admin_role)) {
                $role_names[] = "SD_ELEC_SHUT_ADMIN";
            }
            if ($role->value == intval($elec_shutdown_supervisor_role)) {
                $role_names[] = "SD_ELEC_SHUT_SUPERVISOR";
            }
            //
            if ($role->value == intval($iibcc_approver_role)) {
                $role_names[] = "SD_IIBCC_APPROVER";
            }
            //
            if ($role->value == intval($lpc_approver_role)) {
                $role_names[] = "SD_LPC_APPROVER";
            }
            // system admin and supervisor
            if ($role->value == intval($system_admin)) {
                $role_names[] = "ADMIN";
            }
            if ($role->value == intval($system_supervisor)) {
                $role_names[] = "SUPERVISOR";
            }
            $complain_data = $this->_complaint_helper->checkRoleExist($role->value);
            // echo "role of employee " . $role->value;
            // var_dump($complain_data);
            if (isset($complain_data->ID)) {
                $role_names[] = 'SD_COM_' . $complain_data->ID . '_ADMIN';
                $role_names[] = 'SD_COM_' . $complain_data->ID . '_SUPERVISOR';
            }

            // check for all meets 
            $meet_role = $this->checkMeetType($role->value);
            if (count($meet_role) > 0) {
                $role_names = array_merge($role_names, $meet_role);
            }
        }

        // added gor section head role
        if ($this->_org_helper->checkRole($id, "SH")) {
            $role_names[] = "SD_SEC_HEAD";
        }
        if ($this->_org_helper->checkRole($id, "DH")) {
            $role_names[] = "SD_DIV_HEAD";
        }
        if ($this->_org_helper->checkRole($id, "AD")) {
            $role_names[] = "SD_AD_HEAD";
        }
        if ($this->_org_helper->checkRole($id, "GD")) {
            $role_names[] = "SD_GD_HEAD";
        }
        // var_dump($role_names);
        // check for doc status 

        // \CustomErrorHandler::triggerInvalid("Invalid ICNO");
        return $role_names;
    }

    // private function get_response($user_data){
    //     $payload = array(
    //         "USER" => $user_data
    //     );   
    //     // jwt     
    //     $jwt = SmartGeneral::jwt_encode($payload);
    //     //    
    //     $db = new \stdClass();
    //     $db->accessToken = $jwt;
    //     $db->ename = $user_data->ename;
    //     $db->euserid = $user_data->euserid;
    //     $db->change_pass = $user_data->change_pass;
    //     $db->expiresInTime=700;
    //     $db->id = $user_data->ID;
    //     $db->roles = $user_data->role;
    //     $roles = ["USER"];
    //     if($user_data->euserid=="admin"){
    //         $roles[] = "ADMIN";
    //     }
    //     $roles = $this->getRoles($user_data->ID,$roles);
    //     $db->role =  $roles;
    //     return $db;
    // }
    private function get_response($user_data)
    {
        $payload = array(
            "USER" => $user_data
        );

        // jwt     
        $jwt = SmartGeneral::jwt_encode($payload);

        $db = new \stdClass();
        $db->accessToken = $jwt;
        $db->ename = $user_data->ename;
        $db->euserid = $user_data->euserid;
        $db->change_pass = $user_data->change_pass;
        $db->expiresInTime = 700;
        $db->id = $user_data->ID;
        $db->roles = $user_data->role;

        // Default role
        $roles = ["USER"];

        if ($user_data->euserid == "admin") {
            $roles[] = "ADMIN";
        }

        // Add Supervisor manually if needed (based on your logic)
        if ($user_data->euserid == "supervisor") {
            $roles[] = "SUPERVISOR";
        }

        // Get dynamic roles
        $roles = $this->getRoles($user_data->ID, $roles);
        $db->role = $roles;

        return $db;
    }


    private function updateVisitorCount()
    {
        $key = "SITE_VISITOR_COUNT";
        $exists_data = $this->_site_helper->getOneSettingData($key);
        $data = [];
        if (!$exists_data) {
            $columns = ["setting_name", "setting_value", "created_by"];
            $data["setting_name"] = $key;
            $data["setting_value"] = 1;
            $id = $this->_site_helper->insert($columns, $data);
        } else {
            $id = $exists_data->ID;
            $columns =  ["setting_value", "last_modified_time"];
            $data["setting_value"] = intval($exists_data->setting_value) + 1;
            $this->_site_helper->update($columns, $data, $id);
        }
    }
    /**
     * 
     */
    /*
    public function login(){         
        $columns = ["euserid","epassword"];
         // do validations
         $this->_user_helper->validate(UserHelper::validations,$columns,$this->post);
         // take the data
         $userid = Data::post_data("euserid","STRING"); 
         // get the data
         $user_data = $this->_user_helper->getOneDataWithUserId($userid);
         // 
         if(!isset($user_data->ID)){
            \CustomErrorHandler::triggerInvalid("Invalid ICNO");
         }
         // get status
         $status = $user_data->active_status;
         // check failed password attempts
         if($userid!="admin") {
         $this->_user_helper->checkFailedAttempts($user_data); 
         }
         //
         $password = trim($this->post["epassword"]);
         //         
         if(!password_verify($password,$user_data->epassword)){
            $this->addLog("INVALID PASSWORD","",$user_data->ename); 
            $this->_user_helper->updateFailedAttempts($user_data); 
            \CustomErrorHandler::triggerInvalid("Invalid Password");
         }
         // check status active or inactive
         if($status!=5){
            $this->addLog("USER LOGGED IN BUT IN ACTIVE","",$user_data->ename);    
            \CustomErrorHandler::triggerInvalid("Status inactive");
         }
        // update the last login time 
        $this->_user_helper->updateLastLogin($user_data->ID); 
        // user data
        $user_data->role = $userid!="admin"? ["USER"]:["ADMIN"];  
        // updating the visitor count
        $this->updateVisitorCount();
        //
        $this->addLog("LOGIN","",$user_data->ename); 
        //
        $user_data->profile_img = ""; 
        // update the visitor count
        $this->_site_helper->updateSiteCount();          
         // pay load             
        $this->response($this->get_response($user_data));  
    }
    */
    public function login()
    {
        $columns = ["euserid", "epassword"];
        // do validations
        $this->_user_helper->validate(UserHelper::validations, $columns, $this->post);
        // take the data
        $userid = Data::post_data("euserid", "STRING");
        // get the data
        $user_data = $this->_user_helper->getOneDataWithUserId($userid);
        if (!isset($user_data->ID)) {
            \CustomErrorHandler::triggerInvalid("Invalid ICNO");
        }
        // get status
        $status = $user_data->active_status;
        // check failed password attempts
        if ($userid != "admin") {
            $this->_user_helper->checkFailedAttempts($user_data);
        }
        $password = trim($this->post["epassword"]);
        if (!password_verify($password, $user_data->epassword)) {
            $this->addLog("INVALID PASSWORD", "", $user_data->ename);
            $this->_user_helper->updateFailedAttempts($user_data);
            \CustomErrorHandler::triggerInvalid("Invalid Password");
        }
        // check status active or inactive
        if ($status != 5) {
            $this->addLog("USER LOGGED IN BUT INACTIVE", "", $user_data->ename);
            \CustomErrorHandler::triggerInvalid("Status inactive");
        }
        // update the last login time
        $this->_user_helper->updateLastLogin($user_data->ID);
        // assign roles: if not "admin", assign "USER", otherwise assign "ADMIN" and "SUPERVISOR"
        if ($userid != "admin") {
            $user_data->role = ["USER", "SUPERVISOR"];
        } else {
            $user_data->role = ["ADMIN"];
        }
        // updating the visitor count
        $this->updateVisitorCount();
        $this->addLog("LOGIN", "", $user_data->ename);
        // clearing profile image
        $user_data->profile_img = "";
        // update the site visitor count
        $this->_site_helper->updateSiteCount();
        // return the response
        $this->response($this->get_response($user_data));
    }


    public function userReset()
    {
        $columns = ["euserid", "epassword", "confirmPassword"];
        // do validations
        $this->_user_helper->validate(UserHelper::validations, $columns, $this->post);
        // take the data
        $userid = Data::post_data("euserid", "STRING");
        // get the data
        $user_data = $this->_user_helper->getOneDataWithUserId($userid);
        if (!isset($user_data->ID)) {
            \CustomErrorHandler::triggerInvalid("Invalid ICNO");
        }
        $change_pass = $user_data->change_pass;
        //
        if ($change_pass > 0) {
            \CustomErrorHandler::triggerInternalError("Action Restricted");
        }
        //         
        $post_data = ["epassword" => SmartGeneral::hashPassword($this->post["epassword"])];
        $post_data["change_pass"] = 1;
        $update_columns = ["epassword", "change_pass", "last_reset_time"];
        //
        $this->_user_helper->update($update_columns, $post_data, $user_data->ID);
        //
        $user_data->change_pass = 1;
        // user data
        $user_data->role = $userid != "admin" ? ["USER"] : ["ADMIN"];
        //
        $this->addLog("PASS_RESET", "", $user_data->ename);
        // 
        $user_data->profile_img = "";
        $this->response($this->response($this->get_response($user_data)));
    }
    /**
     * 
     */
    public function getLog()
    {
        $year = isset($this->post["year"]) ? intval($this->post["year"]) : SmartGeneral::getYear();
        $month = isset($this->post["month"]) ? intval($this->post["month"]) : SmartGeneral::getMonth();
        $data = Logger::readLogFile($year, $month);
        $this->response($data);
    }

    public function getSiteSettings()
    {
        $settings = isset($GLOBALS["SD_SITE_SETTINGS"]) ? $GLOBALS["SD_SITE_SETTINGS"] : [];
        $this->response($settings);
    }

    public function takeBackup()
    {
        $backup = new BackupHelper($this->db);
        $backup_file = "test.sql";
        $backup->doBackUp($backup_file);
    }
}
