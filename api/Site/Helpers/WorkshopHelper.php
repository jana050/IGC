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
class WorkshopHelper extends BaseHelper
{

    const schema = [
        "name" => SmartConst::SCHEMA_VARCHAR,
        "description" => SmartConst::SCHEMA_TEXT,
        "location" => SmartConst::SCHEMA_TEXT,
        "sd_mt_userdb_id" => SmartConst::SCHEMA_CUSER_ID,
        "admin_id" => SmartConst::SCHEMA_INTEGER,
        "admin_time" => SmartConst::SCHEMA_DATE,
        "admin_remarks" => SmartConst::SCHEMA_VARCHAR,
        "free_material " => SmartConst::SCHEMA_INTEGER,
        "completion_date " => SmartConst::SCHEMA_DATE,
        "free_description " => SmartConst::SCHEMA_VARCHAR,
        "status" => SmartConst::SCHEMA_INTEGER,
        "created_time" => SmartConst::SCHEMA_CDATETIME,
        "last_modified_by" => SmartConst::SCHEMA_CUSER_ID,
        "last_modified_remarks" => SmartConst::SCHEMA_TEXT,
        "last_modified_time" => SmartConst::SCHEMA_CTIME,
        "supervisor" => SmartConst::SCHEMA_INTEGER,
        "supervisor_description" => SmartConst::SCHEMA_TEXT,
        "supervisor_time" => SmartConst::SCHEMA_CTIME

    ];
    /**
     * 
     */
    const validations = [
        "name" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Title"
            ],
            [
                "type" => SmartConst::VALID_MAX_LENGTH,
                "max" => 1000,
                "msg" => "Title Max character 1000"
            ]
        ],
        "uploaded_file" => [
            [
                "type" => SmartConst::VALID_FILE_REQUIRED,
                "msg" => "Please Upload the Document"
            ],
            [
                "type" => SmartConst::VALID_FILE_TYPE,
                "msg" => "Only pdf is allowed",
                "ext"=>["pdf"]
            ]
        ],
        "description" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Description"
            ]
        ],
        "admin_id" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Select Approver"
            ]
        ],
        "admin_remarks" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Remarks"
            ]
        ],
        "free_material" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please choose Free Issue Material"
            ]
        ],
          "completion_date" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Specify Likely Date Of Completion"
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
        ]


    ];
       // file handling 
   const FILE_FOLDER = "workshop";
   const FILE_NAME = "file";
    //
    public function getFullFile($id){
        return self::FILE_FOLDER . DS . $id . DS . self::FILE_NAME;
    }
    /**
     * 
     */
    /**
     * 
     */
    public function insert(array $columns, array $data)
    {
        return $this->insertDb(self::schema, Table::WORKSHOP, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::WORKSHOP, $columns, $data, $id);
    }
    /**
     * 
     */
    public function getAllData($sql = "", $data_in = [], $group_by = "", $count = false)
    {
        $from = Table::WORKSHOP . " t1
        INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID
        LEFT JOIN " . Table::USERS . " t10 ON t10.ID = t1.supervisor";
        $select = ["t1.*,t2.ename as created_by", "t10.ename as supervisor_name"];
        $order_by = "t1.created_time DESC";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], $count);
    }
    /**
     *
     */
    public function getOneData($id)
    {
        $from = Table::WORKSHOP . " t1
        INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID
        LEFT JOIN " . Table::USERS . " t10 ON t10.ID = t1.supervisor
        ";
        $select = ["t1.*,t2.ename as created_by", "t10.ename as supervisor_name"];
        $sql = "t1.ID=:ID";
        $data_in = ["ID" => $id];
        $group_by = "";
        $order_by = "";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, true, []);
    }

    /**
     * Users assigned to the role stored under a given site-settings key
     * (e.g. 'workshop_supervisor'). Workshop has no per-request "type" to
     * hang a supervisor role off of (unlike Custom Requisition/Complaints),
     * so the pool comes from one fixed site setting instead. Mirrors
     * GemDirectHelper::getUsersByRoleKey().
     */
    public function getUsersByRoleKey($role_key)
    {
        $role_id = \Core\Helpers\SmartSiteSettings::getSetting($role_key);
        if (empty($role_id)) return [];

        $from = Table::USERS . " t1
            INNER JOIN " . Table::USERROLE . " t3 ON t1.ID = t3.sd_mt_userdb_id";
        $select = ["t1.ID as value", "t1.ename as label"];
        $sql = "t3.sd_mt_role_id = :ID";
        $data_in = ["ID" => $role_id];
        return $this->getAll($select, $from, $sql, "", "t1.ename", $data_in, false, []);
    }
    /**
     * 
     */
    public function getWorkshopWithUserID($id)
    {
        $from = Table::WORKSHOP;
        $select = ["*"];
        $sql = "sd_mt_userdb_id=:ID";
        $data_in = ["ID" => $id];
        $group_by = "";
        $order_by = "";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, true, []);
    }
    /**
     * 
     */
    public function deleteOneId($id)
    {
        $from = Table::WORKSHOP;
        $this->deleteId($from, $id);
    }

    public function getCount($type)
    {
        $sql = "DATE(t1.created_time)=CURRENT_DATE()";
        if ($type == 1) {
            $sql = "MONTH(t1.created_time)=" . SmartGeneral::getMonth();
        } else if ($type == 2) {
            $sql = "YEAR(t1.created_time)=" . SmartGeneral::getYear();
        }
        $data =  $this->getAllData($sql, [], "", true);
        return isset($data) && isset($data[0]) ? $data[0]->total_count : 0;
    }


    public function getCountByYear($year)
    {
        $select = ["COUNT(name) AS workshop, MONTH(last_modified_time) AS month"];
        $from = Table::WORKSHOP;
        $sql = "YEAR(last_modified_time) =:year";
        $group_by = "MONTH(last_modified_time)";    // No GROUP BY keyword
        $order_by = "month";
        $data_in = ["year" => $year];
        $count = $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], false);
        $workshop_count = array_fill(0, 12, 0);
        foreach ($count as $work) {
            $month = intval($work->month);
            $workCount = intval($work->workshop);
            $workshop_count[$month - 1] = $workCount;
        }
        $work_count_by_year = array_values($workshop_count);
        return $work_count_by_year;
    }

    
    public function getCountByStatus()
    {
        $sql = "status=10";
        $data =  $this->getAllData($sql,[], "", true);
        return isset($data) && isset($data[0]) ? $data[0]->total_count : 0;
    }
    //RequisitionReport
    public function RequisitionReport($start_date, $end_date)
    {
    $select = [
        "t1.name",
        "t1.description",
        "t1.location",
        "t1.sd_mt_userdb_id",
        "t1.admin_id",
        "t1.admin_time",
        "t1.admin_remarks",
        "t1.free_material",
        "t1.completion_date",
        "t1.free_description",
        "t1.status",
        "t1.created_time",
        "t1.last_modified_by",
        "t1.last_modified_remarks",
        "t1.last_modified_time",
        "t2.ename"
    ];

    $from = Table::WORKSHOP . " t1 
            LEFT JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID";

    $sql = "DATE(t1.created_time) BETWEEN :start_date AND :end_date";
    $order_by = "t1.created_time DESC"; 

    $data_in = [
        "start_date" => $start_date,
        "end_date" => $end_date
    ];

    $single = false;
    $limit = [];
    $count = false;

    $data = $this->getAll($select, $from, $sql, "", $order_by, $data_in, $single, $limit, $count);
    return $data;
    }


   
}
