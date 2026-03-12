<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Site\Helpers;

use Core\BaseHelper;
use Core\Helpers\SmartConst;
use Core\Helpers\SmartGeneral;

//
use Site\Helpers\TableHelper as Table;

/**
 * Description of Data
 * 
 *  class helps to get the data from post with specified type 
 *
 * @author kms
 */
class MeetRoomHelper extends BaseHelper
{

    const schema = [
        "room_name" => SmartConst::SCHEMA_VARCHAR,
        "meet_purpose" => SmartConst::SCHEMA_TEXT,
        "meet_date" => SmartConst::SCHEMA_DATE,
        "meet_start_time" => SmartConst::SCHEMA_CTIME,
        "meet_end_time" => SmartConst::SCHEMA_CTIME,
        // this is to force logged in user id
        "sd_mt_userdb_id" => SmartConst::SCHEMA_CUSER_ID,
        "created_time" => SmartConst::SCHEMA_CDATETIME,
        "status" => SmartConst::SCHEMA_INTEGER,
        // last modified user logged in id
        "last_modified_by" => SmartConst::SCHEMA_CUSER_ID,
        "last_modified_remarks" => SmartConst::SCHEMA_TEXT,
        "last_modified_time" => SmartConst::SCHEMA_CTIME,
        // mom table schema
        "mom_type" => SmartConst::SCHEMA_VARCHAR,
        "meet_desc" => SmartConst::SCHEMA_VARCHAR,
        "meet_no" => SmartConst::SCHEMA_INTEGER,
        "date" => SmartConst::SCHEMA_DATE,
        "mom_file" => SmartConst::SCHEMA_VARCHAR,
        "created_by" => SmartConst::SCHEMA_CUSER_ID,
        // mom types table schema
        "type" => SmartConst::SCHEMA_VARCHAR,
        "member_role_id" => SmartConst::SCHEMA_INTEGER,
        "amdin_role_id" => SmartConst::SCHEMA_INTEGER,       

    ];
    /**
     * 
     */
    const validations = [

        "room_name" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Room Name"
            ]
        ],
        "meet_purpose" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Meet Purpose"
            ]
        ],
        "meet_date" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Meet Date"
            ]
        ],
        "meet_start_time" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Meet start time"
            ]
        ],
        "meet_end_time" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Meet end time"
            ]
        ],
        "status" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter status"
            ]
        ],
        "last_modified_remarks" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter remarks"
            ]
        ],

        // MOM TABLE VALIDATIONS
        "mom_type" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter type"
            ]
        ],
        "meet_no" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Meet Number"
            ]
        ],
        "meet_desc" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Meet Description"
            ]
        ],
        "date" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Date"
            ]
        ],
        "mom_file" => [
            [
                "type" => SmartConst::VALID_FILE_REQUIRED,
                "msg" => "Please Upload the Document"
            ],
            /*
        [
            "type" => SmartConst::VALID_FILE_SIZE,
            "msg" => "The Document Size Should be maximum 0.1 MB",
            "size"=>[0,2]
        ],*/
            [
                "type" => SmartConst::VALID_FILE_TYPE,
                "msg" => "Only pdf is allowed",
                "ext" => ["pdf"]
            ]
        ],



        // MOM TYPES TABLE VALIDATIONS
        "type" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter type"
            ]
        ],
        "member_role_id" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Member Role"
            ]
        ],
        "admin_role_id" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Admin Role"
            ]
        ],
        "uploaded_file" => [
            [
                "type" => SmartConst::VALID_FILE_REQUIRED,
                "msg" => "Please Upload the Document"
            ],
            /*
            [
                "type" => SmartConst::VALID_FILE_SIZE,
                "msg" => "The Document Size Should be maximum 0.1 MB",
                "size"=>[0,2]
            ],*/
            [
                "type" => SmartConst::VALID_FILE_TYPE,
                "msg" => "Only pdf is allowed",
                "ext" => ["pdf"]
            ]
        ],
    ];

    // file handling 
    const FILE_FOLDER = "mom";
    const FILE_NAME = "file";

    const FILE_FOLDER_TYPE = "momtype";
    const FILE_NAME_TYPE = "file";
    //
    public function getFullFile($id)
    {
        return self::FILE_FOLDER . DS . $id . DS . self::FILE_NAME;
    }

    public function getFullMeetFile($id)
    {
        return self::FILE_FOLDER_TYPE . DS . $id . DS . self::FILE_NAME_TYPE;
    }

    /**
     * 
     */
    public function insert(array $columns, array $data)
    {
        return $this->insertDb(self::schema, Table::MEETROOM, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::MEETROOM, $columns, $data, $id);
    }
    /**
     * 
     */
    public function getAllData($sql = "", $data_in = [], $group_by = "", $count = false)
    {
        $from = Table::MEETROOM . " t1 INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID LEFT JOIN " . Table::TYPE . " t3 ON t1.room_name = t3.ID";
        $select = ["t1.*,t3.type_value as room_name,t1.meet_date as date,t1.meet_start_time as start,t1.meet_end_time as end,t1.meet_purpose as title,t2.ename as created_by"];
        $order_by = "t1.created_time DESC";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], $count);
    }
    /**
     * 
     */
    public function getOneData($id)
    {
        $from = Table::MEETROOM . " t1 INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID LEFT JOIN " . Table::TYPE . " t3 ON t1.room_name = t3.ID";
        $select = ["t1.*,t2.ename as created_by,t3.type_value as room_name"];
        $sql = "t1.ID=:ID";
        $data_in = ["ID" => $id];
        $group_by = "";
        $order_by = "";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, true, []);
    }

    public function checkMeetAlreadyExist($room, $date, $start_time, $end_time)
    {
        $from = Table::MEETROOM ;
        $select = ["ID"];
        $sql = "room_name=:room AND meet_date=:meet_date AND status=5 
        AND (
            ('$start_time' >= meet_start_time AND '$start_time' < meet_end_time)
            OR ('$end_time' > meet_start_time AND '$end_time' <= meet_end_time)
            OR ('$start_time' <= meet_start_time AND '$end_time' >= meet_end_time)
        )";
    $data_in = ["room" =>$room,"meet_date" => $date];
        return $this->getAll($select, $from, $sql, "", "", $data_in, true, []);
    }
    /**
     * 
     */
    public function checkMeetTiming($start_time, $given_timestamp, $current_timestamp)
    {
        $current_time = time();
        $provided_timestamp = strtotime($start_time);     
        if (($provided_timestamp < $current_time) && ($given_timestamp == $current_timestamp)) {
            \CustomErrorHandler::triggerInvalid("Please Pick a Valid Time for Meeting");
        }
    }


    /**
     * 
     */
    public function deleteOneId($id)
    {
        $from = Table::MEETROOM;
        $this->deleteId($from, $id);
    }

    public function getCount($type)
    {
        $sql = "DATE(t1.meet_date)=CURRENT_DATE()";
        if ($type == 1) {
            $sql = "MONTH(t1.meet_date)=" . SmartGeneral::getMonth();
        } else if ($type == 2) {
            $sql = "YEAR(t1.meet_date)=" . SmartGeneral::getYear();
        }
        $data =  $this->getAllData($sql, [], "", true);
        return isset($data) && isset($data[0]) ? $data[0]->total_count : 0;
    }


    public function getCountByYear($year)
    {
        $select = ["COUNT(room_name) AS meet, MONTH(last_modified_time) AS month"];
        $from = Table::MEETROOM;
        $sql = "YEAR(last_modified_time) =:year";
        $group_by = "MONTH(last_modified_time)";    // No GROUP BY keyword
        $order_by = "month";
        $data_in = ["year" => $year];
        $count = $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], false);
        $meet_count = array_fill(0, 12, 0);
        foreach ($count as $meet) {
            $month = intval($meet->month);
            $meetCount = intval($meet->meet);
            $meet_count[$month - 1] = $meetCount;
        }
        $meet_count_by_year = array_values($meet_count);
        return $meet_count_by_year;
    }


    public function getCountByStatus()
    {
        $sql = "status=10";
        $data =  $this->getAllData($sql, [], "", true);
        return isset($data) ? count($data) : 0;
    }


    public function getRoomName($id)
    {
        $from = Table::MEETROOM;
        $select = ["*"];
        $sql = "room_name=:ID";
        $data_in = ["ID" => $id];
        $group_by = "";
        $order_by = "";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, true, []);
    }
    //MeetingRoomReport
    public function MeetingRoomReport($start_date, $end_date, $subcategory)
    {
    $select = [
        "t1.room_name",
        "t1.meet_purpose",
        "t1.meet_date",
        "t1.meet_start_time",
        "t1.meet_end_time",
        "t1.sd_mt_userdb_id",
        "t1.created_time",
        "t1.status",
        "t1.last_modified_by",
        "t1.last_modified_remarks",
        "t1.last_modified_time",
        "t2.ename",
        "t3.type_value as room_name"
    ];

    $from = Table::MEETROOM . " t1 
            LEFT JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID LEFT JOIN " . Table::TYPE . " t3 ON t1.room_name = t3.ID";

    $sql = "DATE(t1.meet_date) BETWEEN :start_date AND :end_date AND t1.room_name = :subcategory";

    $order_by = "t1.meet_date, t1.meet_start_time";

    $data_in = [
        "start_date" => $start_date,
        "end_date" => $end_date,
        "subcategory" => $subcategory
    ];

    $single = false;
    $limit = [];
    $count = false;

    $data = $this->getAll($select, $from, $sql, "", $order_by, $data_in, $single, $limit, $count);
    return $data;
    }


    /*
    -----
    -----
    MOM TABLE
    -----
    -----
    */
    /**
     * 
     */
    public function insertMom(array $columns, array $data)
    {
        return $this->insertDb(self::schema, Table::MOM, $columns, $data);
    }
    /**
     * 
     */
    public function updateMom(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::MOM, $columns, $data, $id);
    }
    /**
     * 
     */
    public function getAllDataMom($sql = "", $data_in = [], $group_by = "", $count = false)
    {
        $from = Table::MOM;
        $select = ["*"];
        $order_by = "created_time DESC";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], $count);
    }
    /**
     * 
     */
    public function getOneDataMom($id)
    {
        $from = Table::MOM;
        $select = ["*"];
        $sql = "ID=:ID";
        $data_in = ["ID" => $id];
        $group_by = "";
        $order_by = "";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, true, []);
    }
    /**
     * 
     */
    public function deleteOneIdMom($id)
    {
        $from = Table::MOM;
        $this->deleteId($from, $id);
    }
    /**
     * 
     */
    public function getByMomType($type)
    {
        $from = Table::MOM;
        $select = ["*"];
        $sql = "mom_type=:type";
        $data_in = ["type" => $type];
        $group_by = "";
        $order_by = "meet_no DESC";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, []);
    }

    /*
    -----
    -----
    MOM TYPES TABLE
    -----
    -----
    */
    /**
     * 
     */
    public function insertMomTypes(array $columns, array $data)
    {

        return $this->insertDb(self::schema, Table::MOMTYPES, $columns, $data);
    }
    /**
     * 
     */
    public function updateMomTypes(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::MOMTYPES, $columns, $data, $id);
    }
    /**
     * 
     */
    public function getAllDataMomTypes($sql = "", $data_in = [], $group_by = "", $count = false)
    {
        $from = Table::MOMTYPES;
        $select = ["*"];
        $order_by = "created_time DESC";
        $data =  $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], $count);
        $out = [];
        if ($data) {
            foreach ($data as $obj) {
                $user_role_helper = new UserRoleHelper($this->db);
                $obj->members = $user_role_helper->getSelectedUsersWithRoleId($obj->member_role_id);
                $obj->admin = $user_role_helper->getSelectedUsersWithRoleId($obj->admin_role_id);
                $out[] = $obj;
            }
        }
        return $out;
    }
    /**
     * 
     */
    public function getAllDataMomTypesDropDown($sql = "", $data_in = [], $group_by = "", $count = false)
    {
        $from = Table::MOMTYPES;
        $select = ["ID as value,type as label"];
        $order_by = "created_time DESC";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], $count);
    }
     public function getAllDataMomTypesDropDownTypeReport($sql = "", $data_in = [], $group_by = "", $count = false)
    {
        $from = Table::MOMTYPES;
        $select = ["type as value,type as label"];
        $order_by = "created_time DESC";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], $count);
    }
    /**
     * 
     */
    public function getOneDataMomTypes($id)
    {
        $from = Table::MOMTYPES;
        $select = ["*"];
        $sql = "ID=:ID";
        $data_in = ["ID" => $id];
        $group_by = "";
        $order_by = "";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, true, []);
    }

    public function getOneDataMomTypesWithType($type)
    {
        $from = Table::MOMTYPES;
        $select = ["*"];
        $sql = "type=:type";
        $data_in = ["type" => $type];
        $group_by = "";
        $order_by = "";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, true, []);
    }
    /**
     * 
     */
    public function checkTypeExist($type)
    {
        $from = Table::MOM;
        $select = ["ID"];
        $sql = "mom_type=:type";
        $data_in = ["type" => $type];
        $group_by = "";
        $order_by = "";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, true, []);
    }
    /**
     * 
     */
    public function deleteOneIdMomTypes($id)
    {
        $from = Table::MOMTYPES;
        $this->deleteId($from, $id);
    }

    public function getMeetTypesWithRole($role_id, $role_type)
    {
        $from = Table::MOMTYPES;
        $select = ["*"];
        $sql = $role_type . "=:ID";
        $data_in = ["ID" => $role_id];
        $group_by = "";
        $order_by = "";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, []);
    }
    /**
     * 
     */
    // public function insertMomTypesMembers(int $type, array $data)
    // {
    //     // delete existing roles with role id
    //     $this->deleteBySql(Table::MOMTYPES,"type=:type",["uid"=>$type]);
    //     // columns
    //     $columns = ["type","member_role_id"];       
    //     foreach($data as $single_data){
    //         $data_in = [];
    //         $data_in["type"] = $type;
    //         $data_in["member_role_id"] = isset($single_data["value"]) ? $single_data["value"] : 0;          
    //         $this->insert($columns,$data_in);
    //     }

    // }
 


}
