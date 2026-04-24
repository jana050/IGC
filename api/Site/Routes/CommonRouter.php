<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Site\Routes;

use Core\Helpers\SmartGeneral as General;
use Core\Helpers\SmartConst;

class CommonRouter
{

    private $_routes = [];
    private $_admin_only = ["ADMIN", "USER"];
    private $_user_only = ["USER"];
    private $_admin_user = ["ADMIN", "USER"];

    /**
     * 
     */
    private function auth_routes()
    {
        $controller = "AuthController";
        $this->_routes["/auth/login"] = [SmartConst::REQUEST_POST, $controller, "login"];
        $this->_routes["/auth/user_reset"] = [SmartConst::REQUEST_POST, $controller, "userReset"];
        $this->_routes["/auth/get_log"] = [SmartConst::REQUEST_POST, $controller, "getLog"];
        $this->_routes["/auth/get_settings"] = [SmartConst::REQUEST_GET, $controller, "getSiteSettings"];
        $this->_routes["/auth/do_backup"] = [SmartConst::REQUEST_GET, $controller, "takeBackup"];
    }

    private function dashboard_routes()
    {
        $controller = "DashboardController";
        $this->_routes["/dashboard/getcount/{type}"] = [SmartConst::REQUEST_GET, $this->_admin_only, $controller, "getCounts"];
        $this->_routes["/dashboard/getcountbyyear/{year}"] = [SmartConst::REQUEST_GET, $this->_admin_only, $controller, "getCountsByYear"];
        $this->_routes["/dashboard/getshtdown"] = [SmartConst::REQUEST_GET, [], $controller, "getShutDownMsg"];
    }

    private function users_routes()
    {
        $controller = "UserController";
        $this->_routes["/user/insert"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "insert"];
        $this->_routes["/user/update"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "update"];
        $this->_routes["/user/update_profile_img"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateUserProfilePic"];
        $this->_routes["/user/update_profile_details"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateUserProfileDetails"];
        $this->_routes["/user/get_all"] = [SmartConst::REQUEST_GET, $this->_admin_only, $controller, "getAll"];
        $this->_routes["/user/get_by_org_id"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "getAllByOrg"];
        $this->_routes["/user/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "getOne"];
        $this->_routes["/user/get_one_user"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getOneUser"];
        $this->_routes["/user/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "deleteOne"];
        $this->_routes["/user/admin_reset"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "adminReset"];
        $this->_routes["/user/user_reset"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "userReset"];
        $this->_routes["/user/get_all_select"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAllSelect"];
        $this->_routes["/user/get_logged_users"] = [SmartConst::REQUEST_GET, $this->_admin_only, $controller, "getRecentLoggedInUsers"];
        $this->_routes["/user/get_one_image"] = [SmartConst::REQUEST_POST, $controller, "getOneImage"];
        //
        $this->_routes["/user/get_all_select_role"] = [SmartConst::REQUEST_POST, $controller, "getAllRoleIndex"];
    }

    private function role_routes()
    {
        $controller = "RoleController";
        $this->_routes["/role/insert"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "insert"];
        $this->_routes["/role/update"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "update"];
        $this->_routes["/role/get_all"] = [SmartConst::REQUEST_GET, $this->_admin_only, $controller, "getAll"];
        $this->_routes["/role/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "getOne"];
        $this->_routes["/role/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "deleteOne"];
        $this->_routes["/role/get_all_select"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAllSelect"];
        $this->_routes["/role/get_all_select_label"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAllSelectLabel"];
    }

    private function document_routes()
    {
        $controller = "DocumentController";
        // 5 = submitted 6=app rejected 10=app approved 15= comlaint resolved 14=pending 11=invalid        
        $this->_routes["/document/insert"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "insert"];
        $this->_routes["/document/update"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "update"];
        $this->_routes["/document/get_all/admin/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_only,
            $controller,
            "getAll",
            ["mode" => "admin", "status" => [10, 14]]
        ];
        $this->_routes["/document/get_all/admin/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_only,
            $controller,
            "getAll",
            ["mode" => "admin", "status" => [11, 15]]
        ];
        $this->_routes["/document/get_all/user"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll", ["mode" => "user"]];
        $this->_routes["/document/get_all/app/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "app", "status" => [5]]
        ];
        $this->_routes["/document/get_all/app/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "app", "status" => [6, 10, 15]]
        ];
        $this->_routes["/document/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getOne"];
        $this->_routes["/document/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "deleteOne"];
        $this->_routes["/document/get_doc_type"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getDocByType"];
        $this->_routes["/document/get_doc_category_and_type"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getDocByCategoryAndType"];
        $this->_routes["/document/get_doc"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getDoc"];
        //$this->_routes["/document/get_doc"] = [SmartConst::REQUEST_GET, $controller, "getDocNew"];

        // approve
        $this->_routes["/document/update_app"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApp"];
    }

    private function telephone_routes()
    {
        $controller = "TelephoneController";
        // 5 = submitted 6=app rejected 10=app approved 15= comlaint resolved 14=pending 11=invalid        
        $this->_routes["/telephone/insert"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "insert"];
        $this->_routes["/telephone/update"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "update"];
        $this->_routes["/telephone/get_all/admin/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "admin", "status" => [10, 14, 19, 30]]
        ];
        $this->_routes["/telephone/get_all/admin/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "admin", "status" => [11, 15, 19]]
        ];
        $this->_routes["/telephone/get_all/user"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll", ["mode" => "user"]];
        // $this->_routes["/telephone/get_all/supervisor"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll", ["mode" => "supervisor"]];
        $this->_routes["/telephone/get_all/supervisor"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "supervisor", "status" => [10, 14, 15, 19]]
        ];
        $this->_routes["/telephone/get_all/app/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "app", "status" => [5]]
        ];
        $this->_routes["/telephone/get_all/app/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "app", "status" => [6, 10, 15]]
        ];
        $this->_routes["/telephone/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getOne"];
        $this->_routes["/telephone/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "deleteOne"];
        // approve
        $this->_routes["/telephone/update_app"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApp"];
        $this->_routes["/telephone/supervisor_get_all"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll", ["mode" => "supervisor", "status" => [10, 14, 15, 19, 30]]];
        $this->_routes["/telephone/update_supervisor"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateSupervisor"];
    }

    private function electrical_routes()
    {
        $controller = "ElectricalController";
        // 5 = submitted 6=app rejected 10=app approved 15= comlaint resolved 14=pending 11=invalid   
        $this->_routes["/electrical/insert"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "insert"];
        $this->_routes["/electrical/update"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "update"];
        $this->_routes["/electrical/get_all/admin/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "admin", "status" => [10, 14, 19, 30]]
        ];
        $this->_routes["/electrical/get_all/admin/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "admin", "status" => [11, 15, 19]]
        ];
        $this->_routes["/electrical/get_all/user"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll", ["mode" => "user"]];
        // $this->_routes["/electrical/get_all/supervisor"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll", ["mode" => "supervisor"]];
        $this->_routes["/electrical/get_all/supervisor"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "supervisor", "status" => [14, 15, 19, 30]]
        ];
        $this->_routes["/electrical/get_all/app/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "app", "status" => [5]]
        ];
        $this->_routes["/electrical/get_all/app/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "app", "status" => [6, 10, 15]]
        ];
        $this->_routes["/electrical/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getOne"];
        $this->_routes["/electrical/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "deleteOne"];
        // approve
        $this->_routes["/electrical/update_app"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApp"];
        $this->_routes["/electrical/supervisor_get_all"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll", ["mode" => "supervisor", "status" => [14, 15, 19, 30]]];
        $this->_routes["/electrical/update_supervisor"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateSupervisor"];
    }

    private function network_routes()
    {
        $controller = "NetworkController";
        // 5 = submitted 6=app rejected 10=app approved 15= comlaint resolved 14=pending 11=invalid   
        $this->_routes["/network/insert"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "insert"];
        $this->_routes["/network/update"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "update"];
        $this->_routes["/network/get_all/admin/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "admin", "status" => [10, 14, 19, 30]]
        ];
        $this->_routes["/network/get_all/admin/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "admin", "status" => [11, 15, 19]]
        ];
        $this->_routes["/network/get_all/user"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll", ["mode" => "user"]];
        // $this->_routes["/network/get_all/supervisor"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll", ["mode" => "supervisor"]];
        $this->_routes["/network/get_all/supervisor"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "supervisor", "status" => [10, 14, 15, 19]]
        ];
        $this->_routes["/network/get_all/app/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "app", "status" => [5]]
        ];
        $this->_routes["/network/get_all/app/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "app", "status" => [6, 10, 15]]
        ];
        $this->_routes["/network/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getOne"];
        $this->_routes["/network/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "deleteOne"];
        // approve
        $this->_routes["/network/update_app"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApp"];
        $this->_routes["/network/supervisor_get_all"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll", ["mode" => "supervisor", "status" => [10, 14, 15, 19, 30]]];
        $this->_routes["/network/update_supervisor"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateSupervisor"];
    }

    private function mechanical_routes()
    {
        $controller = "MechanicalController";
        // 5 = submitted 6=app rejected 10=app approved 15= comlaint resolved 14=pending 11=invalid   
        $this->_routes["/mechanical/insert"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "insert"];
        $this->_routes["/mechanical/update"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "update"];
        $this->_routes["/mechanical/get_all/admin/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "admin", "status" => [10, 14]]
        ];
        $this->_routes["/mechanical/get_all/admin/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "admin", "status" => [11, 15]]
        ];
        $this->_routes["/mechanical/get_all/user"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll", ["mode" => "user"]];
        $this->_routes["/mechanical/get_all/app/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "app", "status" => [5]]
        ];
        $this->_routes["/mechanical/get_all/app/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "app", "status" => [6, 10, 15]]
        ];
        $this->_routes["/mechanical/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getOne"];
        $this->_routes["/mechanical/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "deleteOne"];
        // approve
        $this->_routes["/mechanical/update_app"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApp"];
    }
    private function meetroom_routes()
    {
        $controller = "MeetRoomController";
        $this->_routes["/meetroom/insert"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "insert"];
        $this->_routes["/meetroom/update"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "update"];
        $this->_routes["/meetroom/get_all"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll", ["mode" => "admin"]];
        $this->_routes["/meetroom/get_all/user"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll", ["mode" => "user"]];
        $this->_routes["/meetroom/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getOne"];
        $this->_routes["/meetroom/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "deleteOne"];
        $this->_routes["/meetroom/cancel_meet"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "cancelMeet"];
        $this->_routes["/meetroom/get_all_calender"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getAllMeetWithYearMonthRoom"];
        // MOM TABLE APIS
        $this->_routes["/meetroom/insert_mom"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "insertMom"];
        $this->_routes["/meetroom/update_mom"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateMom"];
        $this->_routes["/meetroom/get_all_mom"] = [SmartConst::REQUEST_GET, $this->_admin_only, $controller, "getAllMom"];
        $this->_routes["/meetroom/get_one_mom"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getOneMom"];
        $this->_routes["/meetroom/delete_one_mom"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "deleteOneMom"];
        $this->_routes["/meetroom/get_by_mom_type"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getAllByMomType"];
        $this->_routes["/meetroom/get_one_pdf"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getMomPdf"];
        // MOM TYPES TABLE APIS
        $this->_routes["/meetroom/insert_mom_types"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "insertMomTypes"];
        $this->_routes["/meetroom/update_mom_types"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateMomTypes"];
        $this->_routes["/meetroom/get_all_mom_types"] = [SmartConst::REQUEST_GET, $this->_admin_only, $controller, "getAllMomTypes"];
        $this->_routes["/meetroom/get_all_select_mom_types"] = [SmartConst::REQUEST_GET, $controller, "getAllMomTypesDropDown"];
        $this->_routes["/meetroom/get_one_mom_types"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getOneMomTypes"];
        $this->_routes["/meetroom/delete_one_mom_types"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "deleteOneMomTypes"];
        $this->_routes["/meetroom/get_mom_type"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getMomType"];
        $this->_routes["/meetroom/get_mom_type_pdf"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getMomTypePdf"];
        // //
        $this->_routes["/meetroom/get_all_select_mom_types_report"] = [SmartConst::REQUEST_GET, $controller, "getAllMomTypesDropDownTypesReport"];
        // //getMomTypePdf           


    }

    private function type_routes()
    {
        $controller = "TypeController";
        $this->_routes["/type/insert"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "insert"];
        $this->_routes["/type/update"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "update"];
        $this->_routes["/type/get_all/{type}"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll"];
        $this->_routes["/type/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "getOne"];
        $this->_routes["/type/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "deleteOne"];
        $this->_routes["/type/get_all_select/{type}"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAllSelect"];
        $this->_routes["/type/get_all_select_types"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAllSelectTypes"];
    }

    private function user_role_routes()
    {
        $controller = "UserRoleController";
        $this->_routes["/userrole/insert"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "insert"];
        $this->_routes["/userrole/update"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "update"];
        $this->_routes["/userrole/get_all"] = [SmartConst::REQUEST_GET, $this->_admin_only, $controller, "getAll"];
        $this->_routes["/userrole/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "getOne"];
        $this->_routes["/userrole/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "deleteOne"];
    }

    private function user_otp_routes()
    {
        $controller = "UserOtpController";
        $this->_routes["/userotp/insert"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "insert"];
        $this->_routes["/userotp/update"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "update"];
        $this->_routes["/userotp/get_all"] = [SmartConst::REQUEST_GET, $this->_admin_only, $controller, "getAll"];
        $this->_routes["/userotp/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "getOne"];
        $this->_routes["/userotp/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "deleteOne"];
    }

    private function site_routes()
    {
        $controller = "SiteController";
        $this->_routes["/site/insert"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "insert"];
        $this->_routes["/site/insert_org"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "insertOrg"];
        //
        $this->_routes["/site/get_all_org"] = [SmartConst::REQUEST_GET, $controller, "getAllOrg"];
        //
        $this->_routes["/site/get_all"] = [SmartConst::REQUEST_GET, $this->_admin_only, $controller, "getAll"];
        $this->_routes["/site/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "getOne"];
        $this->_routes["/site/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "deleteOne"];
        $this->_routes["/site/get_all_select"] = [SmartConst::REQUEST_GET, $this->_admin_only, $controller, "getAllSelect"];
        $this->_routes["/site/get_org_one_pdf"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "getOrgOnePdf"];
        $this->_routes["/site/get_org_two_pdf"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "getOrgTwoPdf"];
        $this->_routes["/site/get_dpr_file_pdf"] = [SmartConst::REQUEST_POST, $controller, "getDprFile"];
        //
        $this->_routes["/site/get_iibcc_file_pdf"] = [SmartConst::REQUEST_POST, $controller, "getIibccFile"];
        $this->_routes["/site/get_lpc_file_pdf"] = [SmartConst::REQUEST_POST, $controller, "getLpcFile"];

        // getOrgOnePdf
    }


    private function form_routes()
    {
        $controller = "FormController";
        $this->_routes["/form/insert"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "insert"];
        $this->_routes["/form/update"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "update"];
        $this->_routes["/form/get_all"] = [SmartConst::REQUEST_GET, $this->_admin_only, $controller, "getAll"];
        $this->_routes["/form/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "getOne"];
        $this->_routes["/form/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "deleteOne"];
        $this->_routes["/form/get_pdf"] = [SmartConst::REQUEST_POST, $controller, "get_pdf"];
    }


    private function activity_routes()
    {
        $controller = "ActivityController";
        $this->_routes["/activity/insert"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "insert"];
        $this->_routes["/activity/update"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "update"];
        $this->_routes["/activity/get_all"] = [SmartConst::REQUEST_GET, $controller, "getAll"];
        $this->_routes["/activity/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "getOne"];
        $this->_routes["/activity/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "deleteOne"];
        $this->_routes["/activity/get_one_image/{id}"] = [SmartConst::REQUEST_GET, [], $controller, "getOneImage"];
    }

    private function home_images()
    {
        $controller = "HomeImagesController";
        $this->_routes["/homeimages/insert"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "insert"];
        $this->_routes["/homeimages/update"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "update"];
        $this->_routes["/homeimages/get_all"] = [SmartConst::REQUEST_GET, $controller, "getAll"];
        $this->_routes["/homeimages/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "getOne"];
        $this->_routes["/homeimages/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "deleteOne"];
        $this->_routes["/homeimages/get_one_image/{id}"] = [SmartConst::REQUEST_GET, [], $controller, "getOneImage"];
        $this->_routes["/homeimages/get_one_image_new/{id}"] = [SmartConst::REQUEST_GET, [], $controller, "getOneImageNew"];
    }
    /**
     * 
     */
    private function org_routes()
    {
        $controller = "OrganisationController";
        $this->_routes["/org/insert"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "insert"];
        $this->_routes["/org/update"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "update"];
        $this->_routes["/org/get_all"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll"];
        $this->_routes["/org/change_label"] = [SmartConst::REQUEST_GET, $controller, "initChangeLabel"];
        $this->_routes["/org/get_all_tree"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getAllTree"];
        $this->_routes["/org/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "getOne"];
        $this->_routes["/org/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "deleteOne"];
        $this->_routes["/org/get_all_parent"] = [SmartConst::REQUEST_GET, $this->_admin_only, $controller, "getAllParent"];
        $this->_routes["/org/get_all_tree_recursive"] = [SmartConst::REQUEST_GET, $this->_admin_only, $controller, "getTreeRecursive"];
        $this->_routes["/org/get_approving_authority"] = [SmartConst::REQUEST_GET, [], $controller, "getAllAssociateParents"];
        //
        $this->_routes["/org/get_all_select_sh"] = [SmartConst::REQUEST_GET, $controller, "getAllSelect", ["mode" => "SH"]];
        $this->_routes["/org/get_all_select_dh"] = [SmartConst::REQUEST_GET, $controller, "getAllSelect", ["mode" => "DH"]];
        $this->_routes["/org/get_all_select_ad"] = [SmartConst::REQUEST_GET, $controller, "getAllSelect", ["mode" => "AD"]];
        $this->_routes["/org/get_all_select_gd"] = [SmartConst::REQUEST_GET, $controller, "getAllSelect", ["mode" => "GD"]];
        $this->_routes["/org/get_excel"] = [SmartConst::REQUEST_GET, $controller, "getOrgExcel"];

        $this->_routes["/org/import_excel"] = [SmartConst::REQUEST_POST, $controller, "importOrganisationExcel"];
    }
    /**
     * 
     */
    private function doc_type_routes()
    {
        $controller = "DocTypeController";
        $this->_routes["/docType/insert"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "insert"];
        $this->_routes["/docType/update"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "update"];
        $this->_routes["/docType/get_all"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll"];
        $this->_routes["/docType/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "getOne"];
        $this->_routes["/docType/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "deleteOne"];
        $this->_routes["/docType/get_all_select"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "getAllDocType"];
        $this->_routes["/docType/get_all_select_category"] = [SmartConst::REQUEST_GET, $controller, "getAllDocTypeCategory"];
    }
    /**
     * 
     */
    private function comman_complaint_routes()
    {
        $controller = "CommanComplaintController";
        // 5 = submitted 6=app rejected 10=app approved 15= comlaint resolved 14=pending 11=invalid   
        $this->_routes["/complaint/insert"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "insert"];
        $this->_routes["/complaint/update"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "update"];

        $this->_routes["/complaint/get_all/admin/wait"] = [
            SmartConst::REQUEST_POST,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "admin", "status" => [10, 14, 19, 30]]
        ];

        $this->_routes["/complaint/get_all/admin/process"] = [
            SmartConst::REQUEST_POST,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "admin", "status" => [11, 15, 19]]
        ];

        $this->_routes["/complaint/get_all/user"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getAll", ["mode" => "user"]];
        // $this->_routes["/complaint/get_all/supervisor"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getAll", ["mode" => "supervisor"]];
        $this->_routes["/complaint/get_all/supervisor"] = [
            SmartConst::REQUEST_POST,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "supervisor", "status" => [10, 14, 15, 19]]
        ];

        /*
        $this->_routes["/complaint/get_all/app/wait"] = [SmartConst::REQUEST_GET,$this->_admin_user,$controller,
        "getAll",["mode"=>"app","status"=>[5]]]; 
        $this->_routes["/complaint/get_all/app/process"] = [SmartConst::REQUEST_GET,$this->_admin_user,$controller,
        "getAll",["mode"=>"app","status"=>[6,10,15]]]; 
       */
        $this->_routes["/complaint/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getOne"];
        $this->_routes["/complaint/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "deleteOne"];
        // approve
        $this->_routes["/complaint/update_app"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApp"];
        $this->_routes["/complaint/supervisor_get_all"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "supervisorGetAll"];
        // COMPLAINT TYPES TABLE APIS
        $this->_routes["/complaintTypes/insert_comp_types"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "insertComplaintTypes"];
        $this->_routes["/complaintTypes/update_comp_types"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateComplaintTypes"];
        $this->_routes["/complaintTypes/get_all_comp_types"] = [SmartConst::REQUEST_GET, $this->_admin_only, $controller, "getAllComplaintTypes"];
        $this->_routes["/complaintTypes/get_all_select_comp_types"] = [SmartConst::REQUEST_GET, $controller, "getAllComplaintTypesDropDown"];
        $this->_routes["/complaintTypes/get_one_comp_types"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getOneComplaintTypes"];
        $this->_routes["/complaintTypes/delete_one_comp_types"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "deleteOneComplaintTypes"];
        $this->_routes["/complaintTypes/get_comp_type"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getComplaintType"];
    }
    /**
     * 
     */
    private function workshop_routes()
    {
        $controller = "WorkshopController";
        // 5 = submitted 6=app rejected 10=app approved 15= comlaint resolved 14=pending 11=invalid   
        $this->_routes["/workshop/insert"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "insert"];
        $this->_routes["/workshop/update"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "update"];
        $this->_routes["/workshop/get_all/admin/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "admin", "status" => [10, 14]]
        ];
        $this->_routes["/workshop/get_all/admin/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "admin", "status" => [11, 15]]
        ];
        $this->_routes["/workshop/get_all/user"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll", ["mode" => "user"]];
        $this->_routes["/workshop/get_all/app/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "app", "status" => [5]]
        ];
        $this->_routes["/workshop/get_all/app/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "app", "status" => [6, 10, 15]]
        ];
        $this->_routes["/workshop/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getOne"];
        $this->_routes["/workshop/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "deleteOne"];
        $this->_routes["/workshop/get_one_pdf"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getOnePdf"];
    }
    /**
     * 
     */
    private function acv_shutdown_routes()
    {
        $controller = "ACVShutDownController";
        // 5 = submitted 6=app rejected 10=app approved 15= comlaint resolved 14=pending 11=invalid   
        $this->_routes["/acv_shutdown/insert"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "insert"];
        $this->_routes["/acv_shutdown/update"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "update"];
        $this->_routes["/acv_shutdown/get_all/admin/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "admin", "status" => [15, 24, 25]]
        ];
        $this->_routes["/acv_shutdown/get_all/admin/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "admin", "status" => [35, 34]]
        ];
        $this->_routes["/acv_shutdown/get_all/user"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll", ["mode" => "user"]];
        $this->_routes["/acv_shutdown/get_all/app/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "app", "status" => [5]]
        ];
        $this->_routes["/acv_shutdown/get_all/app/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "app", "status" => [6, 10, 15]]
        ];
        $this->_routes["/acv_shutdown/get_all/hos/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "hos", "status" => [15]]
        ];
        $this->_routes["/acv_shutdown/get_all/hos/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "hos", "status" => [25, 24]]
        ];
        $this->_routes["/acv_shutdown/get_all/hod/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "hod", "status" => [25]]
        ];
        $this->_routes["/acv_shutdown/get_all/hod/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "hod", "status" => [35, 34]]
        ];


        $this->_routes["/acv_shutdown/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getOne"];
        $this->_routes["/acv_shutdown/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "deleteOne"];
        // approve
        $this->_routes["/acv_shutdown/get_one_pdf"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getOnePdf"];
        //
        $this->_routes["/acv_shutdown/update_approval_hos"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApprovalHos"];
        $this->_routes["/acv_shutdown/update_approval_hod"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApprovalHod"];
        //  $this->_routes["/acv_shutdown/get_all_hos"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "hosGetAll"];
        //  $this->_routes["/acv_shutdown/get_all_hod"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "hodGetAll"];
    }
    /**
     * 
     */
    private function elec_shutdown_routes()
    {
        $controller = "ElectricalShutDownController";
        // 5 = submitted 6=app rejected 10=app approved 15= comlaint resolved 14=pending 11=invalid   
        $this->_routes["/elec_shutdown/insert"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "insert"];
        $this->_routes["/elec_shutdown/update"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "update"];
        $this->_routes["/elec_shutdown/get_all/admin/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "admin", "status" => [15, 24, 25]]
        ];
        $this->_routes["/elec_shutdown/get_all/admin/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "admin", "status" => [35, 34]]
        ];
        $this->_routes["/elec_shutdown/get_all/user"] =
            [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll", ["mode" => "user"]];
        $this->_routes["/elec_shutdown/get_all/app/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "app", "status" => [5]]
        ];
        $this->_routes["/elec_shutdown/get_all/app/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "app", "status" => [6, 10, 15]]
        ];

        $this->_routes["/elec_shutdown/get_all/hos/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "hos", "status" => [15]]
        ];
        $this->_routes["/elec_shutdown/get_all/hos/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "hos", "status" => [25, 24]]
        ];

        $this->_routes["/elec_shutdown/get_all/hod/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "hod", "status" => [25]]
        ];
        $this->_routes["/elec_shutdown/get_all/hod/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "hod", "status" => [35, 34]]
        ];


        $this->_routes["/elec_shutdown/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getOne"];
        $this->_routes["/elec_shutdown/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "deleteOne"];
        // approve
        $this->_routes["/elec_shutdown/get_one_pdf"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getOnePdf"];
        //
        $this->_routes["/elec_shutdown/update_approval_hos"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApprovalHos"];
        $this->_routes["/elec_shutdown/update_approval_hod"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApprovalHod"];
        //   $this->_routes["/elec_shutdown/get_all_hos"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "hosGetAll"];
        //  $this->_routes["/elec_shutdown/get_all_hod"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "hodGetAll"];

    }
    private function license_routes()
    {
        $controller = "LicenseController";
        $this->_routes["/license/insert"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "insert"];
        $this->_routes["/license/get_all"] = [SmartConst::REQUEST_GET, $controller, "getAll"];
        $this->_routes["/license/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getOne"];
        $this->_routes["/license/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "deleteOne"];
        $this->_routes["/license/get_doc"] = [SmartConst::REQUEST_POST, $controller, "getDoc"];
    }
    private function user_site_help_routes()
    {
        $controller = "UserSiteController";
        $this->_routes["/user_site_helper/insert"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "insert"];
        $this->_routes["/user_site_helper/get_all"] = [SmartConst::REQUEST_GET, $controller, "getAll"];
        $this->_routes["/user_site_helper/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getOne"];
        $this->_routes["/user_site_helper/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "deleteOne"];
        $this->_routes["/user_site_helper/get_doc"] = [SmartConst::REQUEST_POST, $controller, "getDoc"];
    }


    private function home_forms_routes()
    {
        $controller = "HomeformsController";
        $this->_routes["/homeforms/insert"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "insert"];
        $this->_routes["/homeforms/get_all"] = [SmartConst::REQUEST_GET, $controller, "getAll"];
        $this->_routes["/homeforms/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getOne"];
        $this->_routes["/homeforms/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "deleteOne"];
        $this->_routes["/homeforms/get_doc"] = [SmartConst::REQUEST_POST, $controller, "getDoc"];
    }
    private function awards_routes()
    {
        $controller = "AwardsController";
        $this->_routes["/awards/insert"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "insert"];
        $this->_routes["/awards/update"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "update"];
        $this->_routes["/awards/get_all"] = [SmartConst::REQUEST_GET, $controller, "getAll"];
        $this->_routes["/awards/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getOne"];
        $this->_routes["/awards/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "deleteOne"];
        $this->_routes["/awards/get_one_image/{id}"] = [SmartConst::REQUEST_GET, [], $controller, "getOneImage"];
    }
    private function meet_proposal_routes()
    {
        $controller = "MeetProposalController";
        $this->_routes["/meet_proposal/insert"] =
            [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "insert"];
        $this->_routes["/meet_proposal/get_all/user"] =
            [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getAll", ["mode" => "user"]];
        $this->_routes["/meet_proposal/get_all/admin/wait"] =
            [
                SmartConst::REQUEST_POST,
                $this->_admin_user,
                $controller,
                "getAll",
                ["mode" => "admin", "status" => [5]]
            ];
        $this->_routes["/meet_proposal/get_all/admin/proc"] =
            [
                SmartConst::REQUEST_POST,
                $this->_admin_user,
                $controller,
                "getAll",
                [
                    "mode" => "admin",
                    "status" =>
                        [6, 10, 15]
                ]
            ];

        $this->_routes["/meet_proposal/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getOne"];
        $this->_routes["/meet_proposal/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "deleteOne"];
        $this->_routes["/meet_proposal/get_doc"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getDoc"];
        $this->_routes["/meet_proposal/update"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApp"];
        $this->_routes["/meet_proposal/update_new"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "update"];
    }
    private function gallery_routes()
    {
        $controller = "GalleryController";
        $this->_routes["/gallery/insert"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "insert"];
        $this->_routes["/gallery/update"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "update"];
        $this->_routes["/gallery/get_all"] = [SmartConst::REQUEST_GET, $controller, "getAll"];
        $this->_routes["/gallery/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getOne"];
        $this->_routes["/gallery/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "deleteOne"];
        $this->_routes["/gallery/get_all_by_event"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getAllWithEventName"];
        $this->_routes["/gallery/get_one_image/{id}"] = [SmartConst::REQUEST_GET, [], $controller, "getOneImage"];
    }

    private function history_images()
    {
        $controller = "HistoryController";
        $this->_routes["/history/insert"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "insert"];
        $this->_routes["/history/update"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "update"];
        $this->_routes["/history/get_all"] = [SmartConst::REQUEST_GET, $controller, "getAll"];
        $this->_routes["/history/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getOne"];
        $this->_routes["/history/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "deleteOne"];
        $this->_routes["/history/get_one_image/{id}"] = [SmartConst::REQUEST_GET, [], $controller, "getOneImage"];
    }
    private function ac_ventilation_complaint_routes()
    {
        $controller = "AcVentilationComplaintController";
        // 5 = submitted 6=app rejected 10=app approved 15= comlaint resolved 14=pending 11=invalid   
        $this->_routes["/ac_ventilation_complaint_routes/insert"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "insert"];
        $this->_routes["/ac_ventilation_complaint_routes/update"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "update"];
        $this->_routes["/ac_ventilation_complaint_routes/get_all/admin/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "admin", "status" => [10, 14, 19, 30]]
        ];
        $this->_routes["/ac_ventilation_complaint_routes/get_all/admin/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "admin", "status" => [11, 15, 19]]
        ];
        $this->_routes["/ac_ventilation_complaint_routes/get_all/user"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll", ["mode" => "user"]];
        // $this->_routes["/ac_ventilation_complaint_routes/get_all/supervisor"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll", ["mode" => "supervisor"]];
        $this->_routes["/ac_ventilation_complaint_routes/get_all/supervisor"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "supervisor", "status" => [10, 14, 15, 19]]
        ];

        $this->_routes["/ac_ventilation_complaint_routes/get_all/app/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "app", "status" => [5]]
        ];
        $this->_routes["/ac_ventilation_complaint_routes/get_all/app/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "app", "status" => [6, 10, 15]]
        ];
        $this->_routes["/ac_ventilation_complaint_routes/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getOne"];
        $this->_routes["/ac_ventilation_complaint_routes/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "deleteOne"];
        // approve
        $this->_routes["/ac_ventilation_complaint_routes/update_app"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApp"];
        $this->_routes["/ac_ventilation_complaint_routes/supervisor_get_all"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll", ["mode" => "supervisor", "status" => [10, 14, 15, 19, 30]]];
        $this->_routes["/ac_ventilation_complaint_routes/update_supervisor"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateSupervisor"];
    }

    private function mis_report_routes()
    {
        $controller = "MisReportsController";
        $this->_routes["/mis_report/complaint_report"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "getComplaintReport"];
        $this->_routes["/mis_report/electrical_complaint_report"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "getElectricalComplaintReport"];
        $this->_routes["/mis_report/telephone_complaint_report"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "getTelephoneComplaintReport"];
        $this->_routes["/mis_report/network_complaint_report"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "getNetworkComplaintReport"];
        $this->_routes["/mis_report/requisition_report"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "getRequisitionReport"];
        $this->_routes["/mis_report/meeting_room_report"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "getMeetingRoomReport"];
        $this->_routes["/mis_report/temporary_advance_report"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "getTemporaryAdvanceReport"];
        $this->_routes["/mis_report/committee_mom_report"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "getCommitteeMomReport"];
        $this->_routes["/mis_report/document_report"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "getDocumentReport"];
        $this->_routes["/mis_report/mbook_report"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "getMbookReport"];
        $this->_routes["/mis_report/rfidcard_report"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "getRFIDCardExpiryReport"];
        $this->_routes["/mis_report/rfid_report"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "getRfidCardReport"];
        $this->_routes["/mis_report/committee_iibcc_report"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "getCommitteeIibccReport"];
        $this->_routes["/mis_report/committee_lpc_report"] = [SmartConst::REQUEST_POST, $controller, "getCommitteeLpcReport"];
        //
        $this->_routes["/mis_report/mbook_issue_report"] = [SmartConst::REQUEST_POST, $controller, "getMbookIssueReport"];
        //
        $this->_routes["/mis_report/gem_direct_report"] = [SmartConst::REQUEST_POST, $controller, "getGemDirectReport"];
        $this->_routes["/mis_report/gem_direct_payment_report"] = [SmartConst::REQUEST_POST, $controller, "getGemDirectPaymentReport"];
    }

    private function radiological_work_routes()
    {
        $controller = "RadiologicalWorkController";
        // 5 = submitted 6=app rejected 10=app approved 15= comlaint resolved 14=pending 11=invalid   
        $this->_routes["/radiological_work/insert"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "insert"];
        $this->_routes["/radiological_work/update"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "update"];
        $this->_routes["/radiological_work/get_all/admin/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "admin", "status" => [10, 14, 15]]
        ];
        $this->_routes["/radiological_work/get_all/admin/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "admin", "status" => [20]]
        ];
        $this->_routes["/radiological_work/get_all/user"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll", ["mode" => "user"]];

        $this->_routes["/radiological_work/get_all/supervisor"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "supervisor", "status" => [10, 14, 15, 19]]
        ];
        $this->_routes["/radiological_work/get_all/app/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "app", "status" => [5]]
        ];
        $this->_routes["/radiological_work/get_all/app/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "app", "status" => [6, 10, 15]]
        ];

        $this->_routes["/radiological_work/get_all/labincharge/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "labincharge", "status" => [10]]
        ];
        $this->_routes["/radiological_work/get_all/labincharge/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "labincharge", "status" => [15, 14, 20, 19]]
        ];

        $this->_routes["/radiological_work/get_all/hp/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "hp", "status" => [15]]
        ];
        $this->_routes["/radiological_work/get_all/hp/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "hp", "status" => [20, 19]]
        ];

        $this->_routes["/radiological_work/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getOne"];
        $this->_routes["/radiological_work/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "deleteOne"];
        // approve
        $this->_routes["/radiological_work/update_app"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApp"];
        $this->_routes["/radiological_work/update_approval_labincharge"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApprovalLabIncharge"];
        $this->_routes["/radiological_work/update_approval_hp"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApprovalHP"];
        $this->_routes["/radiological_work/get_pdf"] = [SmartConst::REQUEST_POST, $controller, "getPdf"];
    }
    private function temporary_advance_routes()
    {
        $controller = "TemporaryAdvanceController";
        // 5 = submitted 6=app rejected 10=app approved 15= comlaint resolved 14=pending 11=invalid   
        $this->_routes["/temporary_advance/insert"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "insert"];
        $this->_routes["/temporary_advance/update"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "update"];
        $this->_routes["/temporary_advance/get_all/admin/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "admin", "status" => [5, 10, 11, 15, 19, 20, 24, 25]]
        ];
        $this->_routes["/temporary_advance/get_all/admin/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "admin", "status" => [35]]
        ];
        $this->_routes["/temporary_advance/get_all/user"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll", ["mode" => "user"]];


        $this->_routes["/temporary_advance/get_all/app/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "app", "status" => [5]]
        ];
        $this->_routes["/temporary_advance/get_all/app/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "app", "status" => [6, 10, 15]]
        ];


        $this->_routes["/temporary_advance/get_all/hos/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "hos", "status" => [10]]
        ];
        $this->_routes["/temporary_advance/get_all/hos/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "hos", "status" => [15, 14, 20, 19, 25, 24, 30, 29, 35]]
        ];

        $this->_routes["/temporary_advance/get_all/hod/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "hod", "status" => [15]]
        ];
        $this->_routes["/temporary_advance/get_all/hod/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "hod", "status" => [20, 19, 25, 24, 30, 29, 35]]
        ];

        $this->_routes["/temporary_advance/get_all/ad/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "ad", "status" => [20]]
        ];
        $this->_routes["/temporary_advance/get_all/ad/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "ad", "status" => [25, 24, 30, 29, 35]]
        ];

        $this->_routes["/temporary_advance/get_all/gd/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "gd", "status" => [25]]
        ];
        $this->_routes["/temporary_advance/get_all/gd/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "gd", "status" => [30, 29, 35]]
        ];




        $this->_routes["/temporary_advance/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getOne"];
        $this->_routes["/temporary_advance/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "deleteOne"];
        // approve
        $this->_routes["/temporary_advance/update_app"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApp"];
        //  $this->_routes["/temporary_advance/get_all_hos"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "hosGetAll"];
        // $this->_routes["/temporary_advance/get_all_hod"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "hodGetAll"];
        // $this->_routes["/temporary_advance/get_all_ad"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "AdDirectorGetAll"];
        $this->_routes["/temporary_advance/update_hos"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApprovalHos"];
        $this->_routes["/temporary_advance/update_hod"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApprovalHod"];
        $this->_routes["/temporary_advance/update_ad"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApprovalAd"];
        $this->_routes["/temporary_advance/update_gd"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApprovalGd"];
        $this->_routes["/temporary_advance/supervisor_get_all"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll", ["mode" => "supervisor", "status" => [5]]];

        $this->_routes["/temporary_advance/update_supervisor"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateSupervisor"];
    }
    private function mbook_routes()
    {
        //MBOOK ISSUE
        $controller = "MbookController";
        // 5 = submitted 6=app rejected 10=app approved 15= comlaint resolved 14=pending 11=invalid   
        $this->_routes["/mbook_issue/insert"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "insert"];
        $this->_routes["/mbook_issue/update"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "update"];
        $this->_routes["/mbook_issue/get_all/admin/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "admin", "status" => [10]]
        ];
        $this->_routes["/mbook_issue/get_all/admin/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "admin", "status" => [15, 20]]
        ];
        $this->_routes["/mbook_issue/get_all/user"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll", ["mode" => "user"]];

        $this->_routes["/mbook_issue/get_all/supervisor"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "supervisor", "status" => [10, 14, 15, 19]]
        ];
        $this->_routes["/mbook_issue/get_all/indentor"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "indentor", "status" => [10, 14, 15, 19]]
        ];

        $this->_routes["/mbook_issue/get_all/app/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "app", "status" => [5]]
        ];
        $this->_routes["/mbook_issue/get_all/app/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "app", "status" => [6, 10, 15]]
        ];

        $this->_routes["/mbook_issue/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getOne"];
        $this->_routes["/mbook_issue/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "deleteOne"];
        $this->_routes["/mbook_issue/update_user"] = [SmartConst::REQUEST_POST, $controller, "updateUser"];
        // approve
        $this->_routes["/mbook_issue/update_app"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApp"];
        $this->_routes["/mbook_issue/update_approval_cfed"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApprovalCfed"];
        $this->_routes["/mbook_entry/get_all_select"] = [SmartConst::REQUEST_GET, $this->_admin_only, $controller, "getAllSelect"];
        //getDoc
        $this->_routes["/mbook_issue/get_doc"] = [SmartConst::REQUEST_POST, $controller, "getDoc"];
        $this->_routes["/mbook_issue/get_pdf"] = [SmartConst::REQUEST_POST, $controller, "getPdf"];
        //MBOOK ENTRY
        // 5 = submitted 6=app rejected 10=app approved 15= comlaint resolved 14=pending 11=invalid   
        $this->_routes["/mbook_entry/insert"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "insertMbookEntry"];
        $this->_routes["/mbook_entry/update"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updatetMbookEntry"];


        $this->_routes["/mbook_entry/get_all/user"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAllMbookEntry", ["mode" => "user"]];

        $this->_routes["/mbook_entry/get_all/admin/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAllMbookEntry",
            ["mode" => "admin", "status" => [10, 14, 15, 19, 20, 24, 25]]
        ];
        $this->_routes["/mbook_entry/get_all/admin/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAllMbookEntry",
            ["mode" => "admin", "status" => [35]]
        ];

        $this->_routes["/mbook_entry/get_all/hos/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAllMbookEntry",
            ["mode" => "hos", "status" => [10]]
        ];
        $this->_routes["/mbook_entry/get_all/hos/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAllMbookEntry",
            ["mode" => "hos", "status" => [15, 14, 20, 19, 25, 24, 30, 29, 35]]
        ];
        $this->_routes["/mbook_entry/get_all/hod/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAllMbookEntry",
            ["mode" => "hod", "status" => [15]]
        ];
        $this->_routes["/mbook_entry/get_all/hod/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAllMbookEntry",
            ["mode" => "hod", "status" => [20, 19, 25, 24, 30, 29, 35]]
        ];
        $this->_routes["/mbook_entry/get_all/ad/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAllMbookEntry",
            ["mode" => "ad", "status" => [20]]
        ];
        $this->_routes["/mbook_entry/get_all/ad/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAllMbookEntry",
            ["mode" => "ad", "status" => [25, 24, 30, 29, 35]]
        ];
        $this->_routes["/mbook_entry/get_all/gd/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAllMbookEntry",
            ["mode" => "gd", "status" => [25]]
        ];
        $this->_routes["/mbook_entry/get_all/gd/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAllMbookEntry",
            ["mode" => "gd", "status" => [30, 29, 35]]
        ];


        $this->_routes["/mbook_entry/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getOnetMbookEntry"];
        $this->_routes["/mbook_entry/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "deleteOnetMbookEntry"];
        // approve
        $this->_routes["/mbook_entry/update_hos"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApprovalHos"];
        $this->_routes["/mbook_entry/update_hod"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApprovalHod"];
        $this->_routes["/mbook_entry/update_ad"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApprovalAd"];
        $this->_routes["/mbook_entry/update_gd"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApprovalGd"];
        $this->_routes["/mbook_entry/get_all_mbook_entries"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getAllMbookEntries"];
        $this->_routes["/mbook_entry/get_approval_count"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "ApprovalCounts"];
        $this->_routes["/mbook_entry/mbook_details"] = [SmartConst::REQUEST_POST, $controller, "getFullByIssueId"];
    }
    private function admin_menu_routes()
    {
        $controller = "AdminMenuController";
        $this->_routes["/admin_menu/insert"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "insert"];
        $this->_routes["/admin_menu/update"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "update"];
        $this->_routes["/admin_menu/get_all"] = [SmartConst::REQUEST_GET, $this->_admin_only, $controller, "getAll"];
        $this->_routes["/admin_menu/get_all_menu"] = [SmartConst::REQUEST_GET, $this->_admin_only, $controller, "getAllMenus"];
        $this->_routes["/admin_menu/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "getOne"];
        $this->_routes["/admin_menu/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "deleteOne"];
        $this->_routes["/admin_menu/get_all_select"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "getAllSelect"];
    }
    private function admin_modules_routes()
    {
        $controller = "AdminModulesController";
        $this->_routes["/admin_modules/insert"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "insert"];
        $this->_routes["/admin_modules/update"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "update"];
        $this->_routes["/admin_modules/get_all"] = [SmartConst::REQUEST_GET, $this->_admin_only, $controller, "getAll"];
        $this->_routes["/admin_modules/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "getOne"];
        $this->_routes["/admin_modules/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "deleteOne"];
        $this->_routes["/admin_modules/status_update"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "updateStatus"];
        $this->_routes["/admin_modules/get_all_select"] = [SmartConst::REQUEST_GET, $this->_admin_only, $controller, "getAllSelect"];
        // AdminModulePermision
        $this->_routes["/admin_modules/insert_module_permission"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "insertAdminModulePermission"];
        $this->_routes["/admin_modules/get_one_module_permission"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "getOneAdminModulePermission"];
        $this->_routes["/admin_modules/get_all_module"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "getAllModule"];
        // AdminRolePermission 
        $this->_routes["/admin_modules/insert_role_permission"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "insertRolePermission"];
        $this->_routes["/admin_modules/get_all_role_permission"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "getAllModulePermissionWithRole"];
    }

    private function work_permit_routes()
    {
        $controller = "WorkPermitController";
        // 5 = submitted 6=app rejected 10=app approved 15= comlaint resolved 14=pending 11=invalid   
        $this->_routes["/work_permit/insert"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "insert"];
        $this->_routes["/work_permit/update"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "update"];
        $this->_routes["/work_permit/get_all/admin/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "admin", "status" => [5, 10, 9]]
        ];
        $this->_routes["/work_permit/get_all/admin/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "admin", "status" => [15]]
        ];
        $this->_routes["/work_permit/get_all/user"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll", ["mode" => "user"]];


        $this->_routes["/work_permit/get_all/app/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "app", "status" => [5]]
        ];
        $this->_routes["/work_permit/get_all/app/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "app", "status" => [6, 10, 15]]
        ];


        $this->_routes["/work_permit/get_all/hos/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "hos", "status" => [5]]
        ];
        $this->_routes["/work_permit/get_all/hos/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "hos", "status" => [10, 15, 14]]
        ];


        $this->_routes["/work_permit/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getOne"];
        $this->_routes["/work_permit/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "deleteOne"];
        // approve
        $this->_routes["/work_permit/update_app"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApp"];
        $this->_routes["/work_permit/update_hos"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApprovalHos"];
        $this->_routes["/work_permit/supervisor_get_all"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll", ["mode" => "supervisor", "status" => [5]]];

        $this->_routes["/work_permit/update_supervisor"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateSupervisor"];
    }
    private function rfid_card_routes()
    {
        $controller = "RfidCardController";
        // 5 = submitted 6=app rejected 10=app approved 15= comlaint resolved 14=pending 11=invalid   
        $this->_routes["/rfid_card/insert"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "insert"];
        $this->_routes["/rfid_card/update"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "update"];
        $this->_routes["/rfid_card/get_all/admin/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "admin", "status" => [5, 15, 14, 20, 19, 25, 24, 30, 29]]
        ];
        $this->_routes["/rfid_card/get_all/admin/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "admin", "status" => [30, 29]]
        ];
        $this->_routes["/rfid_card/get_all/user"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll", ["mode" => "user"]];
        $this->_routes["/rfid_card/get_all/hos/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "hos", "status" => [10]]
        ];
        $this->_routes["/rfid_card/get_all/hos/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "hos", "status" => [15, 14, 20, 19, 25, 30]]
        ];
        $this->_routes["/rfid_card/get_all/hod/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "hod", "status" => [15]]
        ];
        $this->_routes["/rfid_card/get_all/hod/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "hod", "status" => [20, 19, 25, 30]]
        ];
        $this->_routes["/rfid_card/supervisor_get_all/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "supervisor", "status" => [20]]
        ];
        $this->_routes["/rfid_card/supervisor_get_all/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "supervisor", "status" => [25, 24]]
        ];
        $this->_routes["/rfid_card/supervisor_get_all/returned/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "card_return", "status" => [25]]
        ];
        $this->_routes["/rfid_card/supervisor_get_all/returned/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "card_return", "status" => [30, 29, 35]]
        ];
        $this->_routes["/rfid_card/get_all/admin/returned/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "admin", "status" => [20]]
        ];
        $this->_routes["/rfid_card/get_all/admin/returned/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "admin", "status" => [25, 24]]
        ];
        $this->_routes["/rfid_card/supervisor_get_all"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll", ["mode" => "supervisor", "status" => [20, 19, 25, 24, 29, 30, 35]]];
        $this->_routes["/rfid_card/card_retrun_get_all"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll", ["mode" => "card_return", "status" => [25, 30]]];

        $this->_routes["/rfid_card/get_one"] = [SmartConst::REQUEST_POST, $controller, "getOne"];
        $this->_routes["/rfid_card/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "deleteOne"];
        $this->_routes["/rfid_card/update_user"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateUser"];

        // approve 
        $this->_routes["/rfid_card/update_hos"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApprovalHos"];

        $this->_routes["/rfid_card/update_hod"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApprovalHod"];
        $this->_routes["/rfid_card/update_supervisor"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateSupervisor"];
        $this->_routes["/rfid_card/update_card_return"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateCardReturn"];
        $this->_routes["/rfid_card/get_pdf"] = [SmartConst::REQUEST_POST, $controller, "getPdf"];
        $this->_routes["/rfid_card/get_expired"] = [SmartConst::REQUEST_GET, $controller, "getExpiredUnreturnedCards"];
    }
    private function site_visitors_routes()
    {
        $controller = "SiteVisitorsController";
        $this->_routes["/site/visitor_count"] = [SmartConst::REQUEST_GET, $controller, "getVisitorCount"];

        // getOrgOnePdf
    }
    private function commitee_iibcc_routes()
    {
        $controller = "CommiteeIibccController";
        // 5 = submitted 6=app rejected 10=app approved 15= comlaint resolved 14=pending 11=invalid   
        $this->_routes["/commitee_iibcc/insert"] = [SmartConst::REQUEST_POST, $controller, "insert"];
        $this->_routes["/commitee_iibcc/update"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "update"];
        $this->_routes["/commitee_iibcc/get_all/admin/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            // ["mode" => "admin", "status" => [10,15,14, 20, 19, 25,24, 30,29]]
            ["mode" => "admin", "status" => [10, 15, 40, 20, 25, 40]]
        ];
        $this->_routes["/commitee_iibcc/get_all/admin/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            // ["mode" => "admin", "status" => [30,29]]
            ["mode" => "admin", "status" => [30]]


        ];
        $this->_routes["/commitee_iibcc/get_all/user"] = [SmartConst::REQUEST_GET, $controller, "getAll", ["mode" => "user"]];
        $this->_routes["/commitee_iibcc/get_all/hos/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "hos", "status" => [10]]
        ];
        $this->_routes["/commitee_iibcc/get_all/hos/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            // ["mode" => "hos", "status" => [14,15,20, 19, 25, 30]]
            ["mode" => "hos", "status" => [15, 40, 20, 25, 30]]

        ];
        $this->_routes["/commitee_iibcc/get_all/hod/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "hod", "status" => [15]]
        ];
        $this->_routes["/commitee_iibcc/get_all/hod/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            // ["mode" => "hod", "status" => [20, 19, 25, 30]]
            ["mode" => "hod", "status" => [20, 40, 25, 30]]

        ];
        $this->_routes["/commitee_iibcc/get_all/iibcc_approver/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "iibcc_approver", "status" => [20]]
        ];
        $this->_routes["/commitee_iibcc/get_all/iibcc_approver/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            // ["mode" => "iibcc_approver", "status" => [25,24,30,29]]
            ["mode" => "iibcc_approver", "status" => [25, 40, 30]]

        ];

        $this->_routes["/commitee_iibcc/get_all/iibcc_chairman/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "iibcc_chairman", "status" => [25]]
        ];
        $this->_routes["/commitee_iibcc/get_all/iibcc_chairman/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            // ["mode" => "iibcc_chairman", "status" => [30,29]]
            ["mode" => "iibcc_chairman", "status" => [30, 40]]
        ];
        $this->_routes["/commitee_iibcc/get_one"] = [SmartConst::REQUEST_POST, $controller, "getOne"];
        $this->_routes["/commitee_iibcc/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "deleteOne"];
        $this->_routes["/commitee_iibcc/update_user"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateUser"];
        // approve 
        $this->_routes["/commitee_iibcc/update_hos"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApprovalHos"];
        $this->_routes["/commitee_iibcc/update_hod"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApprovalHod"];
        $this->_routes["/commitee_iibcc/update_iibcc_approver"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApprovalIibccApprover"];
        $this->_routes["/commitee_iibcc/update_iibcc_chairman"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApprovalIibccChairman"];
        $this->_routes["/commitee_iibcc/get_pdf"] = [SmartConst::REQUEST_POST, $controller, "getPdf"];
        $this->_routes["/commitee_iibcc/update_user"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateUser"];
        $this->_routes["/commitee_iibcc/get_doc"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getDoc"];
        $this->_routes["/commitee_iibcc/get_iibcc_history"] = [SmartConst::REQUEST_POST, $controller, "getibbcccHistory"];

    }
    private function budget_type_routes()
    {
        $controller = "BudgetTypeController";
        $this->_routes["/budget_type/insert"] = [SmartConst::REQUEST_POST, $controller, "insert"];
        $this->_routes["/budget_type/update"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "update"];
        $this->_routes["/budget_type/get_all"] = [SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll"];
        $this->_routes["/budget_type/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_only, $controller, "getOne"];
        $this->_routes["/budget_type/delete_one"] = [SmartConst::REQUEST_POST, $controller, "deleteOne"];
        $this->_routes["/budget_type/get_all_select"] = [SmartConst::REQUEST_GET, $controller, "getAllSelect"];
    }
    private function commitee_lpc_routes()
    {
        $controller = "CommiteeLpcController";
        // 5 = submitted 6=app rejected 10=app approved 15= comlaint resolved 14=pending 11=invalid   
        $this->_routes["/commitee_lpc/insert"] = [SmartConst::REQUEST_POST, $controller, "insert"];
        $this->_routes["/commitee_lpc/update"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "update"];
        $this->_routes["/commitee_lpc/get_all/admin/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            // ["mode" => "admin", "status" => [10,15,14, 20, 19, 25,24, 30,29]]
            ["mode" => "admin", "status" => [10, 15, 20, 40, 25]]
        ];
        $this->_routes["/commitee_lpc/get_all/admin/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            //  ["mode" => "admin", "status" => [30,29]]
            ["mode" => "admin", "status" => [30]]
        ];
        $this->_routes["/commitee_lpc/get_all/user"] = [SmartConst::REQUEST_GET, $controller, "getAll", ["mode" => "user"]];
        $this->_routes["/commitee_lpc/get_all/hos/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "hos", "status" => [10]]
        ];
        $this->_routes["/commitee_lpc/get_all/hos/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            // ["mode" => "hos", "status" => [14,15,20, 19, 25, 30]]
            ["mode" => "hos", "status" => [15, 40, 20, 25, 30]]
        ];
        $this->_routes["/commitee_lpc/get_all/hod/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "hod", "status" => [15]]
        ];
        $this->_routes["/commitee_lpc/get_all/hod/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            // ["mode" => "hod", "status" => [20, 19, 25, 30]]
            ["mode" => "hod", "status" => [20, 40, 25, 30]]
        ];
        $this->_routes["/commitee_lpc/get_all/lpc_approver/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "lpc_approver", "status" => [20]]
        ];
        $this->_routes["/commitee_lpc/get_all/lpc_approver/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            // ["mode" => "lpc_approver", "status" => [25,24,30,29]]
            ["mode" => "lpc_approver", "status" => [25, 40, 30]]
        ];

        $this->_routes["/commitee_lpc/get_all/lpc_chairman/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "lpc_chairman", "status" => [25]]
        ];
        $this->_routes["/commitee_lpc/get_all/lpc_chairman/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            // ["mode" => "lpc_chairman", "status" => [30,29]]
            ["mode" => "lpc_chairman", "status" => [30, 40]]

        ];
        $this->_routes["/commitee_lpc/get_one"] = [SmartConst::REQUEST_POST, $controller, "getOne"];
        $this->_routes["/commitee_lpc/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "deleteOne"];
        $this->_routes["/commitee_lpc/update_user"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateUser"];
        // approve 
        $this->_routes["/commitee_lpc/update_hos"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApprovalHos"];
        $this->_routes["/commitee_lpc/update_hod"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApprovalHod"];
        $this->_routes["/commitee_lpc/update_lpc_approver"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApprovalLpcApprover"];
        $this->_routes["/commitee_lpc/update_lpc_chairman"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApprovalLpcChairman"];
        $this->_routes["/commitee_lpc/get_pdf"] = [SmartConst::REQUEST_POST, $controller, "getPdf"];
        $this->_routes["/commitee_lpc/update_user"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateUser"];
        $this->_routes["/commitee_lpc/get_doc"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getDoc"];
        $this->_routes["/commitee_lpc/get_lpc_history"] = [SmartConst::REQUEST_POST, $controller, "getCommiteeLpcHistory"];
    }
    private function Mom_Iibcc_routes()
    {
        $controller = "MomIibccController";

        // MOM TABLE APIS
        $this->_routes["/mom_iibcc/insert_mom"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "insertMom"];
        $this->_routes["/mom_iibcc/update_mom"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateMom"];
        $this->_routes["/mom_iibcc/get_all_mom"] = [SmartConst::REQUEST_GET, $this->_admin_only, $controller, "getAllMom"];
        $this->_routes["/mom_iibcc/get_one_mom"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getOneMom"];
        $this->_routes["/mom_iibcc/delete_one_mom"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "deleteOneMom"];
        $this->_routes["/mom_iibcc/get_by_mom_type"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getAllByMomType"];
        $this->_routes["/mom_iibcc/get_one_pdf"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getMomPdf"];

    }
    private function Mom_Lpc_routes()
    {
        $controller = "MomLpcController";

        // MOM TABLE APIS
        $this->_routes["/mom_lpc/insert_mom"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "insertMom"];
        $this->_routes["/mom_lpc/update_mom"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateMom"];
        $this->_routes["/mom_lpc/get_all_mom"] = [SmartConst::REQUEST_GET, $this->_admin_only, $controller, "getAllMom"];
        $this->_routes["/mom_lpc/get_one_mom"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getOneMom"];
        $this->_routes["/mom_lpc/delete_one_mom"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "deleteOneMom"];
        $this->_routes["/mom_lpc/get_by_mom_type"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getAllByMomType"];
        $this->_routes["/mom_lpc/get_one_pdf"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getMomPdf"];

    }
    private function committee_history_images()
    {
        $controller = "CommiteeHistoryController";
        $this->_routes["/committee_history/insert"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "insert"];
        $this->_routes["/committee_history/update"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "update"];
        $this->_routes["/committee_history/get_all"] = [SmartConst::REQUEST_GET, $controller, "getAll"];
        $this->_routes["/committee_history/get_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "getOne"];
        $this->_routes["/committee_history/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "deleteOne"];
    }

    private function gem_direct_routes()
    {
        $controller = "GemDirectController";
        // 5 = submitted 6=app rejected 10=app approved 15= comlaint resolved 14=pending 11=invalid   
        $this->_routes["/gem_direct/insert"] = [SmartConst::REQUEST_POST, $controller, "insert"];
        $this->_routes["/gem_direct/next_indent_no"] = [SmartConst::REQUEST_GET, $controller, "nextIndentNo"];
        $this->_routes["/gem_direct/update"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "update"];
        $this->_routes["/gem_direct/get_all/admin/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "admin", "status" => [10, 15, 14, 16, 17, 24, 29, 40]]

        ];
        $this->_routes["/gem_direct/get_all/admin/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "admin", "status" => [19, 20, 21, 30, 6]]



        ];
        $this->_routes["/gem_direct/get_all/user"] = [SmartConst::REQUEST_GET, $controller, "getAll", ["mode" => "user"]];
        $this->_routes["/gem_direct/get_all/hos/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "hos", "status" => [10]]
        ];
        $this->_routes["/gem_direct/get_all/hos/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "hos", "status" => [14, 15, 16, 17, 19, 20, 21, 24, 29, 30, 40]]


        ];

        $this->_routes["/gem_direct/get_all/financial_approval/wait"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "financial_approval", "status" => [15]]
        ];
        $this->_routes["/gem_direct/get_all/financial_approval/process"] = [
            SmartConst::REQUEST_GET,
            $this->_admin_user,
            $controller,
            "getAll",
            ["mode" => "financial_approval", "status" => [20, 19]]

        ];


        $this->_routes["/gem_direct/get_one"] = [SmartConst::REQUEST_POST, $controller, "getOne"];
        $this->_routes["/gem_direct/delete_one"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "deleteOne"];
        $this->_routes["/gem_direct/update_user"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateUser"];
        // approve 
        $this->_routes["/gem_direct/update_hos"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApprovalHos"];
        $this->_routes["/gem_direct/update_financial_approver"] = [SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApprovalfinancialApprover"];

        $this->_routes["/gem_direct/get_pdf"] = [SmartConst::REQUEST_POST, $controller, "getPdf"];
        $this->_routes["/gem_direct/update_user"] = [SmartConst::REQUEST_POST, $controller, "updateUser"];
        $this->_routes["/gem_direct/get_doc"] = [SmartConst::REQUEST_POST, $controller, "getDoc"];

        // -------- HOS rework restarts to 10 - widen HOS process set --------
        // (hos/process route above shows all completed-or-moved items)

        // -------- IIBCC Chairman --------
        $this->_routes["/gem_direct/get_all/chairman/wait"] = [
            SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll",
            ["mode" => "chairman", "status" => [15, 17]]
        ];
        $this->_routes["/gem_direct/get_all/chairman/process"] = [
            SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll",
            ["mode" => "chairman", "status" => [16, 19, 20, 29, 21, 30]]
        ];
        $this->_routes["/gem_direct/update_chairman"] = [
            SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApprovalChairman"
        ];

        // -------- Vetter --------
        $this->_routes["/gem_direct/get_all/vetter/wait"] = [
            SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll",
            ["mode" => "vetter", "status" => [16]]
        ];
        $this->_routes["/gem_direct/get_all/vetter/process"] = [
            SmartConst::REQUEST_GET, $this->_admin_user, $controller, "getAll",
            ["mode" => "vetter", "status" => [17, 24, 20, 29]]
        ];
        $this->_routes["/gem_direct/update_vetter"] = [
            SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApprovalVetter"
        ];
        $this->_routes["/gem_direct/vetter_user_select"] = [
            SmartConst::REQUEST_POST, $this->_admin_user, $controller, "vetterUserSelect"
        ];

        // -------- Payment stage (separate sd_gem_direct_payment table) --------
        $this->_routes["/gem_direct_payment/submit"] = [
            SmartConst::REQUEST_POST, $this->_admin_user, $controller, "submitPayment"
        ];
        $this->_routes["/gem_direct_payment/get_all/user"] = [
            SmartConst::REQUEST_GET, $this->_admin_user, $controller, "paymentGetAll",
            ["mode" => "user"]
        ];
        $this->_routes["/gem_direct_payment/get_all/admin"] = [
            SmartConst::REQUEST_GET, $this->_admin_user, $controller, "paymentGetAll",
            ["mode" => "admin"]
        ];
        $this->_routes["/gem_direct_payment/get_one"] = [
            SmartConst::REQUEST_POST, $this->_admin_user, $controller, "paymentGetOne"
        ];
        $this->_routes["/gem_direct_payment/pdf_view"] = [
            SmartConst::REQUEST_POST, $controller, "paymentPdfView"
        ];
        $this->_routes["/gem_direct_payment/pdf_download"] = [
            SmartConst::REQUEST_POST, $controller, "paymentPdfDownload"
        ];
        $this->_routes["/gem_direct_payment/file_download"] = [
            SmartConst::REQUEST_POST, $this->_admin_user, $controller, "paymentFileDownload"
        ];
        $this->_routes["/gem_direct_payment/consignee_select"] = [
            SmartConst::REQUEST_GET, $this->_admin_user, $controller, "consigneeUserSelect"
        ];
        $this->_routes["/gem_direct_payment/buyer_select"] = [
            SmartConst::REQUEST_GET, $this->_admin_user, $controller, "buyerUserSelect"
        ];
        // HOS approval flow on payments
        $this->_routes["/gem_direct_payment/get_all/hos"] = [
            SmartConst::REQUEST_GET, $this->_admin_user, $controller, "paymentGetAll",
            ["mode" => "hos"]
        ];
        $this->_routes["/gem_direct_payment/update_hos"] = [
            SmartConst::REQUEST_POST, $this->_admin_user, $controller, "updateApprovalHosPayment"
        ];

        // -------- Dashboard pending counts --------
        $this->_routes["/gem_direct/pending_count"] = [
            SmartConst::REQUEST_GET, $this->_admin_user, $controller, "pendingCount"
        ];

    }


    private function get_all_Routes()
    {
        $this->auth_routes();
        $this->dashboard_routes();
        $this->users_routes();
        $this->role_routes();
        $this->document_routes();
        $this->telephone_routes();
        $this->electrical_routes();
        $this->network_routes();
        $this->mechanical_routes();
        $this->meetroom_routes();
        $this->type_routes();
        $this->user_role_routes();
        $this->user_otp_routes();
        $this->site_routes();
        $this->form_routes();
        $this->activity_routes();
        $this->org_routes();
        $this->doc_type_routes();
        $this->comman_complaint_routes();
        $this->workshop_routes();
        $this->acv_shutdown_routes();
        $this->elec_shutdown_routes();
        $this->home_images();
        $this->license_routes();
        $this->home_forms_routes();
        $this->awards_routes();
        $this->meet_proposal_routes();
        $this->gallery_routes();
        $this->history_images();
        $this->user_site_help_routes();
        //
        $this->ac_ventilation_complaint_routes();

        //
        $this->mis_report_routes();
        $this->radiological_work_routes();
        $this->temporary_advance_routes();
        $this->mbook_routes();
        //
        $this->admin_menu_routes();
        $this->admin_modules_routes();
        $this->work_permit_routes();
        //
        $this->rfid_card_routes();
        //
        $this->site_visitors_routes();
        //
        $this->commitee_iibcc_routes();
        //
        $this->budget_type_routes();
        //
        $this->commitee_lpc_routes();
        //
        $this->Mom_Iibcc_routes();
        //
        $this->Mom_Lpc_routes();
        //
        $this->committee_history_images();
        //
        $this->gem_direct_routes();

        return $this->_routes;
    }


    /**
     * 
     */
    static public function getRoutes()
    {
        $obj = new self();
        return $obj->get_all_routes();
    }
}
