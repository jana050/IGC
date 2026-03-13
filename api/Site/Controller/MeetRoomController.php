<?php

namespace Site\Controller;

use Core\BaseController;
use Core\Helpers\SmartData as Data;
use Core\Helpers\SmartAuthHelper;
use Core\Helpers\SmartFileHelper;


use Site\Helpers\MeetRoomHelper;


class MeetRoomController extends BaseController
{
    private MeetRoomHelper $_meetroom_helper;

    function __construct($params)
    {
        parent::__construct($params);
        // 
        $this->_meetroom_helper = new MeetRoomHelper($this->db);
    }

    /**
     * 
     */
    public function insert()
    {
        $columns = ["room_name", "meet_purpose", "meet_date", "meet_start_time", "meet_end_time"];
        // do validations
        $this->_meetroom_helper->validate(MeetRoomHelper::validations, $columns, $this->post);
        // add other columns
        $columns[] = "created_time";
        $columns[] = "sd_mt_userdb_id";
        $columns[] = "status";
        $this->post["status"] = 5;
        // schedule checking 
        $room = Data::post_data("room_name","INTEGER");
        $date = Data::post_data("meet_date", "DATE");
        $start_time = Data::post_data("meet_start_time", "STRING"); 
        $end_time = Data::post_data("meet_end_time", "STRING"); 
        $current_date = date("Y-m-d");
        $given_timestamp = strtotime($date);
        $current_timestamp = strtotime($current_date);
        if (($given_timestamp < $current_timestamp)) {
            \CustomErrorHandler::triggerInvalid("Please Pick a Valid Date for Meeting");
        }
        // check past time for today meeting
        $this->_meetroom_helper->checkMeetTiming($start_time, $given_timestamp, $current_timestamp);
        // check meet exist
        $data  = $this->_meetroom_helper->checkMeetAlreadyExist($room, $date, $start_time, $end_time);
        if (!empty($data)) {
            \CustomErrorHandler::triggerInvalid("Another Meet had already booked at that time");
        }
        // insert and get id
        $id = $this->_meetroom_helper->insert($columns, $this->post);
        // add log
        $this->addLog("BOOKED A MEET", "", SmartAuthHelper::getLoggedInUserName());
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
        $this->_meetroom_helper->validate(MeetRoomHelper::validations, $columns, $this->post);
        // add columns
        $columns[] = "last_modified_by";
        $columns[] = "last_modified_time";
        // insert and get id
        $id = $this->_meetroom_helper->update($columns, $this->post, $id);
        // add log
        $this->addLog("UPDATED THE MEET", "", SmartAuthHelper::getLoggedInUserName());
        //
        $this->response($id);
    }
    /**
     * 
     */
    public function getAll()
    {
        // check the mode received from router
        $sql = "";
        $data_in = [];
        $mode = isset($this->params["mode"]) ? $this->params["mode"] : "user";
        switch ($mode) {
                // indicates the logged user data
            case 'user':
                $sql = "t1.sd_mt_userdb_id=:user_id";
                $data_in = ["user_id" => SmartAuthHelper::getLoggedInId()];
                break;
            default:
                break;
        }
        // insert and get id
        $data = $this->_meetroom_helper->getAllData($sql, $data_in);
        if (empty($data)) {
            // \CustomErrorHandler::triggerInvalid("no data available for this userid");
        }
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
        $data = $this->_meetroom_helper->getOneData($id);
        $this->response($data);
    }

    /**
     * 
     */
    public function cancelMeet()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $this->post[] = "status";
        $this->post["status"] = 15;
        $columns = ["status"];
        // do validations
        $this->_meetroom_helper->validate(MeetRoomHelper::validations, $columns, $this->post);
        // add columns
        $columns[] = "last_modified_by";
        $columns[] = "last_modified_time";
        $id = $this->_meetroom_helper->update($columns, $this->post, $id);
        // add log
        $this->addLog("CANCELLED A MEET", "", SmartAuthHelper::getLoggedInUserName());
        //
        $out = new \stdClass();
        $out->msg = "Meet Cancelled Successfully";
        $this->response($out);
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
        $this->_meetroom_helper->deleteOneId($id);
        // add log
        $this->addLog("DELETED A MEET", "", SmartAuthHelper::getLoggedInUserName());
        //
        $out = new \stdClass();
        $out->msg = "Meet Deleted Successfully";
        $this->response($out);
    }    /**
     * 
     */
    public function getAllMeetWithYearMonthRoom()
    {
        // take the sanitised inputs from post
        $room_name = Data::post_data("room_name", "STRING");
        $year = Data::post_data("year", "INTEGER");
        $month = Data::post_data("month", "INTEGER");
        // prepare the sql adn data_in
        $sql = "room_name=:room_name AND YEAR(meet_date)=:year AND MONTH(meet_date)=:month AND status<>15";
        $data_in = ["room_name" => $room_name, "year" => $year, "month" => $month];
        // send the sql and data
        $data  = $this->_meetroom_helper->getAllData($sql, $data_in, "", "meet_date ASC");
        $this->response($data);
    }

    /**
   --- 
   ---
   MOM TABLE
   ---
   ---
     */

    public function insertMom()
    {
        $columns = ["mom_type", "meet_no","meet_desc", "meet_date", "mom_file"];
        $this->post["meet_date"] = Data::post_data("meet_date", "DATE");

        // do validations
        $this->_meetroom_helper->validate(MeetRoomHelper::validations, $columns, $this->post);
        // add other columns
        $columns[] = "created_by";
        $columns[] = "created_time";
        // insert and get id
        $id = $this->_meetroom_helper->insertMom($columns, $this->post);
        //
        $file_path = $this->_meetroom_helper->getFullFile($id);
        // move the uploaded file to path 
        $stored_file_path = SmartFileHelper::moveSingleFile("mom_file", $file_path);
        // update the file path in table
        $update_columns = ["mom_file"];
        $update_data = ["mom_file" => $stored_file_path];
        $this->_meetroom_helper->updateMom($update_columns, $update_data, $id);
        // add log
        // $this->addLog("UPLOADED A MOM FILE","",SmartAuthHelper::getLoggedInUserName());
        //
        $this->response($id);
    }
    /**
     * 
     */
    public function updateMom()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $columns = ["mom_type", "meet_no", "meet_date", "date", "mom_file"];
        // do validations
        $this->_meetroom_helper->validate(MeetRoomHelper::validations, $columns, $this->post);
        // insert and get id
        $id = $this->_meetroom_helper->updateMom($columns, $this->post, $id);
        // add log
        $this->addLog("UPDATED MOM FILE", "", SmartAuthHelper::getLoggedInUserName());
        //
        $this->response($id);
    }

    /**
     * 
     */

    public function getAllMom()
    {
        $data = $this->_meetroom_helper->getAllDataMom();
        $this->response($data);
    }
    /**
     * 
     */
    public function getOneMom()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }

        $data = $this->_meetroom_helper->getOneDataMom($id);
        $this->response($data);
    }

    /**
     * 
     */
    public function deleteOneMom()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        // insert and get id
        $this->_meetroom_helper->deleteOneIdMom($id);
        // add log
        $this->addLog("CANCELLED A MEET", "", SmartAuthHelper::getLoggedInUserName());
        //
        $out = new \stdClass();
        $out->msg = "Deleted Successfully";
        $this->response($out);
    }
    /**
     * 
     */
    public function getAllByMomType()
    {
        $type = isset($this->post["mom_type"]) ? ($this->post["mom_type"]) : "##";
        $data = $this->_meetroom_helper->getByMomType($type);
        $this->response($data);
    }

    public function getMomPdf()
    {
        $id = isset($this->post["id"]) ? $this->post["id"] : 0;
        if ($id < 0) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $data =  $this->_meetroom_helper->getOneDataMom($id);
        // 
        $pdf_path =  $this->_meetroom_helper->getFullFile($id) . ".pdf";
        // echo $pdf_path;
        $this->responseFileBase64(SmartFileHelper::getDataPath()  . $pdf_path);
    }

    /**
   --- 
   ---
   MOM TYPES TABLE
   ---
   ---
     */

    public function insertMomTypes()
    {
        $columns = ["type", "member_role_id", "admin_role_id","uploaded_file"];
        // do validations
        $this->_meetroom_helper->validate(MeetRoomHelper::validations, $columns, $this->post);
        //
        $columns = ["type", "member_role_id", "admin_role_id"];

        $this->db->_db->Begin();
        // insert and get id
        $id = $this->_meetroom_helper->insertMomTypes($columns, $this->post);
          // process the file
        $file_path = $this->_meetroom_helper->getFullMeetFile($id);
          // move the uploaded file to path 
        $stored_file_path = SmartFileHelper::moveSingleFile("uploaded_file",$file_path);
          // update the file path in table
        $update_columns = ["mom_file"];
        $update_data = ["mom_file"=>$stored_file_path];
        $this->_meetroom_helper->updateMomTypes($update_columns,$update_data,$id);    
        // add log
        $this->addLog("ADDED A NEW TYPE", "", SmartAuthHelper::getLoggedInUserName());
        //
         // commit the transaction and 
        $this->db->_db->commit();
        $this->response($id);
    }
    /**
     * 
     */
    public function updateMomTypes()
    {
        $id = isset($this->post["ID"]) ? intval($this->post["ID"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $columns_validate = ["type", "member_role_id", "admin_role_id","uploaded_file"];
        // do validations
        $this->_meetroom_helper->validate(MeetRoomHelper::validations,  $columns_validate, $this->post);
        // insert and get id
        $columns = ["type", "member_role_id", "admin_role_id"];

        $id = $this->_meetroom_helper->updateMomTypes($columns, $this->post, $id);
        //
        $file_path = $this->_meetroom_helper->getFullMeetFile($id);
        // move the uploaded file to path 
        $stored_file_path = SmartFileHelper::moveSingleFile("uploaded_file", $file_path);
        // update the file path in table
        $update_columns = ["mom_file"];
        $update_data = ["mom_file" => $stored_file_path];
        $this->_meetroom_helper->updateMom($update_columns, $update_data, $id);
        // add log
        $this->addLog("UPDATED A TYPE", "", SmartAuthHelper::getLoggedInUserName());
        //
        $this->response($id);
    }
    /**
     * 
     */
    public function getOneMomTypes()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }

        $data = $this->_meetroom_helper->getOneDataMomTypes($id);
        $this->response($data);
    }

    /**
     * 
     */

    public function getAllMomTypes()
    {
        $data = $this->_meetroom_helper->getAllDataMomTypes();
        $this->response($data);
    }
    /**
     * 
     */
    public function deleteOneMomTypes()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        // insert and get id
        $this->_meetroom_helper->deleteOneIdMomTypes($id);
        // add log
        $this->addLog("DELETED A TYPE", "", SmartAuthHelper::getLoggedInUserName());
        //
        $out = new \stdClass();
        $out->msg = "Deleted Successfully";
        $this->response($out);
    }

    public function getAllMomTypesDropDown(){   
        $data = $this->_meetroom_helper->getAllDataMomTypesDropDown();
        $this->response($data);
    }
     public function getAllMomTypesDropDownTypesReport(){   
        $data = $this->_meetroom_helper->getAllDataMomTypesDropDownTypeReport();
        $this->response($data);
    }


    public function getMomType(){
        $id = isset($this->post["id"]) ? $this->post["id"] : 0;
        if($id < 0){
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $data = $this->_meetroom_helper->getOneDataMomTypes($id);
        // 
        $pdf_path =  $this->_meetroom_helper->getFullMeetFile($id) .".pdf";
       // echo $pdf_path;
        $this->responseFileBase64(SmartFileHelper::getDataPath()  . $pdf_path);
    }

    public function getMomTypePdf(){
        $type = isset($this->post["type"]) ? $this->post["type"] : "##";
        
        $data = $this->_meetroom_helper->getOneDataMomTypesWithType($type);
        // 
        $pdf_path =  $this->_meetroom_helper->getFullMeetFile($data->ID) .".pdf";
       // echo $pdf_path;
        $this->responseFileBase64(SmartFileHelper::getDataPath()  . $pdf_path);
    }
}
