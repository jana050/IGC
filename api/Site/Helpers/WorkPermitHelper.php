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
class WorkPermitHelper extends BaseHelper
{

    const schema = [
        "permit_no" => SmartConst::SCHEMA_VARCHAR,
        "system" => SmartConst::SCHEMA_VARCHAR,
        "description_of_location" => SmartConst::SCHEMA_INTEGER,
        "team_involved" => SmartConst::SCHEMA_VARCHAR,
        "shutdown_jobs" => SmartConst::SCHEMA_VARCHAR,
        "ac_venitilation_type" => SmartConst::SCHEMA_VARCHAR,
        "description_of_work" => SmartConst::SCHEMA_VARCHAR,
        "start_date" => SmartConst::SCHEMA_DATE,
        "end_date" => SmartConst::SCHEMA_DATE,
        "necessary_disconnection" => SmartConst::SCHEMA_VARCHAR,
        "isolation" => SmartConst::SCHEMA_VARCHAR,
        "health_physical_instructions" => SmartConst::SCHEMA_VARCHAR,
        "industrial_safety_permit" => SmartConst::SCHEMA_VARCHAR,
        "welding_cutting_permit" => SmartConst::SCHEMA_VARCHAR,
        "sd_mt_userdb_id" => SmartConst::SCHEMA_CUSER_ID,
        "admin_id" => SmartConst::SCHEMA_INTEGER,
        "status" => SmartConst::SCHEMA_INTEGER,
        "created_time" => SmartConst::SCHEMA_CDATE,
        "last_modified_by" => SmartConst::SCHEMA_CUSER_ID,
        "last_modified_remarks" => SmartConst::SCHEMA_TEXT,
        "app_id" => SmartConst::SCHEMA_TEXT,
        "app_time" => SmartConst::SCHEMA_TEXT,
        "app_remarks" => SmartConst::SCHEMA_TEXT,
        "admin_time" => SmartConst::SCHEMA_TEXT,
        "admin_remarks" => SmartConst::SCHEMA_TEXT,
        "last_modified_time" => SmartConst::SCHEMA_CTIME,
        "supervisor_description" => SmartConst::SCHEMA_TEXT,
        "supervisor" => SmartConst::SCHEMA_INTEGER,
        "supervisor_time" => SmartConst::SCHEMA_CTIME,
        "work_all_completed" => SmartConst::SCHEMA_VARCHAR,
        "defect_rectified" => SmartConst::SCHEMA_VARCHAR,
        "work_area_cleared" => SmartConst::SCHEMA_INTEGER,
        "tags_cleared" => SmartConst::SCHEMA_VARCHAR,
        "protection_available" => SmartConst::SCHEMA_VARCHAR,
        "operation_test" => SmartConst::SCHEMA_VARCHAR,
        "system_return_normal" => SmartConst::SCHEMA_VARCHAR,
        "comments" => SmartConst::SCHEMA_VARCHAR,
        "details_work" => SmartConst::SCHEMA_VARCHAR,
        "hos_id" => SmartConst::SCHEMA_CUSER_ID,
        "hos_remarks" => SmartConst::SCHEMA_TEXT,
        "hos_time" => SmartConst::SCHEMA_CTIME,
    ];
    /**
     * 
     */
    const validations = [
        "app_id" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Select Approver"
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
        "permit_no" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter permit_no"
            ]
        ],
        "system" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter system"
            ]
        ],
        "description_of_location" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter description_of_location"
            ]
        ],
        "team_involved" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter team_involved"
            ]
        ],
        "shutdown_jobs" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter shutdown_jobs"
            ]
        ],
        "supervisor_description" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter supervisor_description"
            ]
        ],
        "supervisor" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter supervisor"
            ]
        ],
        "ac_venitilation_type" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter ac_venitilation_type"
            ]
        ],
        "description_of_work" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter description_of_work"
            ]
        ],
        "start_date" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter start_date"
            ]
        ],
        "end_date" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter end_date"
            ]
        ],
        "necessary_disconnection" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter necessary_disconnection"
            ]
        ],
        "isolation" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter isolation"
            ]
        ],
        "health_physical_instructions" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter health_physical_instructions"
            ]
        ],
        "industrial_safety_permit" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter industrial_safety_permit"
            ]
        ],
        "welding_cutting_permit" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter welding_cutting_permit"
            ]
        ],
        "work_all_completed" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter work_all_completed"
            ]
        ],
        "defect_rectified" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter defect_rectified"
            ]
        ],
        "work_area_cleared" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter work_area_cleared"
            ]
        ],
        "tags_cleared" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter tags_cleared"
            ]
        ],
        "protection_available" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter protection_available"
            ]
        ],
        "operation_test" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter operation_test"
            ]
        ],
        "system_return_normal" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter system_return_normal"
            ]
        ],
        // "comments" => [
        //     [
        //         "type" => SmartConst::VALID_REQUIRED,
        //         "msg" => "Please Enter comments"
        //     ]
        // ],

        // "details_work" => [
        //     [
        //         "type" => SmartConst::VALID_REQUIRED,
        //         "msg" => "Please Enter details_work"
        //     ]
        // ],




    ];
    /**
     * 
     */
    public function insert(array $columns, array $data)
    {
        return $this->insertDb(self::schema, Table::WORK_PERMIT, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::WORK_PERMIT, $columns, $data, $id);
    }
    /**
     * 
     */
    public function getAllData($sql = "", $data_in = [], $group_by = "", $count = false)
    {
        $from = Table::WORK_PERMIT . " t1 
        INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID 
         LEFT JOIN " . Table::USERS . " t10 ON t10.ID = t1.supervisor
        LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.hos_id";
        $select = [
            "t1.*,t2.ename as created_by",
            "t10.ename as supervisor_name",
            "t11.ename as hos_name",
        ];
        $order_by = "t1.created_time DESC";
        return $this->getAll($select, $from, "", $group_by, $order_by, $data_in, false, [], $count);
    }
    /**
     * 
     */
    public function getOneData($id)
    {
        $from = Table::WORK_PERMIT . " t1 
        INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID 
        LEFT JOIN " . Table::USERS . " t10 ON t10.ID = t1.supervisor
        LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.hos_id";
        $select = [
            "t1.*,t2.ename as created_by",
            "t10.ename as supervisor_name",
            "t11.ename as hos_name",
        ];
        $sql = "t1.ID=:ID";
        $data_in = ["ID" => $id];
        $group_by = "";
        $order_by = "";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, true, []);
    }
    /**
     * 
     */
    public function getTempWithUserID($id)
    {
        $from = Table::WORK_PERMIT;
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
        $from = Table::WORK_PERMIT;
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
        $data = $this->getAllData($sql, [], "", true);
        return isset($data) && isset($data[0]) ? $data[0]->total_count : 0;
    }


    public function getCountByYear($year)
    {
        $select = ["COUNT(system) AS work_permit, MONTH(last_modified_time) AS month"];
        $from = Table::WORK_PERMIT;
        $sql = "YEAR(last_modified_time) =:year";
        $group_by = "MONTH(last_modified_time)";    // No GROUP BY keyword
        $order_by = "month";
        $data_in = ["year" => $year];
        $count = $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], false);
        $work_permit_count = array_fill(0, 12, 0);
        foreach ($count as $work) {
            $month = intval($work->month);
            $workCount = intval($work->work_permit);
            $work_permit_count[$month - 1] = $workCount;
        }
        $work_count_by_year = array_values($work_permit_count);
        return $work_count_by_year;
    }


    public function getCountByStatus()
    {
        $sql = "status=10";
        $data = $this->getAllData($sql, [], "", true);
        return isset($data) && isset($data[0]) ? $data[0]->total_count : 0;
    }
   public function getCountStatus($status_sql)
    {
        $select = ["COUNT(*) AS total_count"];
        $from = Table::WORK_PERMIT;
        $sql = "status IN (" . $status_sql . ")";
        $data = $this->getAll($select, $from, $sql, "", "", [], true);
        return  isset($data->total_count) ? (int)$data->total_count : 0;
    }
}
