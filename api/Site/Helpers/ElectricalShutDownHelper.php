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
class ElectricalShutDownHelper extends BaseHelper
{

    const schema = [
        "from_date" => SmartConst::SCHEMA_DATE,
        "to_date" => SmartConst::SCHEMA_DATE,
        "from_time" => SmartConst::SCHEMA_CTIME,
        "to_time" => SmartConst::SCHEMA_CTIME,
        "description" => SmartConst::SCHEMA_TEXT,
        "location" => SmartConst::SCHEMA_TEXT,
        "sd_mt_userdb_id" => SmartConst::SCHEMA_CUSER_ID,
        "admin_id" => SmartConst::SCHEMA_INTEGER,
        "admin_time" => SmartConst::SCHEMA_CDATETIME,
        "admin_remarks" => SmartConst::SCHEMA_VARCHAR,
        "status" => SmartConst::SCHEMA_INTEGER,
        "created_time" => SmartConst::SCHEMA_CDATETIME,
        "last_modified_by" => SmartConst::SCHEMA_CUSER_ID,
        "last_modified_remarks" => SmartConst::SCHEMA_TEXT,
        "last_modified_time" => SmartConst::SCHEMA_CTIME,
        "hos_id" => SmartConst::SCHEMA_CUSER_ID,
        "hos_remarks" => SmartConst::SCHEMA_TEXT,
        "hos_time" => SmartConst::SCHEMA_CTIME,
        "hod_id" => SmartConst::SCHEMA_CUSER_ID,
        "hod_remarks" => SmartConst::SCHEMA_TEXT,
        "hod_time" => SmartConst::SCHEMA_CTIME,
        "shutdown_type" => SmartConst::SCHEMA_VARCHAR
    ];
    /**
     * 
     */
    const validations = [
        "from_date" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Electrical Shutdown Start Date"
            ]
        ],
        "to_date" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Electrical Shutdown End Date"
            ]
        ],
        "from_time" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Electrical Shutdown Start Time"
            ]
        ],
        "to_time" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Electrical Shutdown End Time"
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
                "ext" => ["pdf"]
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
    const FILE_FOLDER = "ElectricalShutDown";
    const FILE_NAME = "file";
    //
    public function getFullFile($id)
    {
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
        return $this->insertDb(self::schema, Table::ELECTRICAL_SHUTDOWN, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::ELECTRICAL_SHUTDOWN, $columns, $data, $id);
    }
    /**
     * 
     */
    public function getAllData($sql = "", $data_in = [], $group_by = "", $count = false)
    {
        $from = Table::ELECTRICAL_SHUTDOWN . " t1 
        INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID 
         LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.hos_id
         LEFT JOIN " . Table::USERS . " t12 ON t12.ID = t1.hod_id";
        $select = ["t1.*,t2.ename as created_by", "t11.ename as hos_name", "t12.ename as hod_name"];
        $order_by = "t1.created_time DESC";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], $count);
    }
    /**
     * 
     */
    public function getOneData($id)
    {
        $from = Table::ELECTRICAL_SHUTDOWN . " t1 
        INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID 
         LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.hos_id
         LEFT JOIN " . Table::USERS . " t12 ON t12.ID = t1.hod_id
        ";
        $select = ["t1.*,t2.ename as created_by", "t11.ename as hos_name", "t12.ename as hod_name"];
        $sql = "t1.ID=:ID";
        $data_in = ["ID" => $id];
        $group_by = "";
        $order_by = "";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, true, []);
    }
    /**
     * 
     */
    public function getElecShutDownWithUserID($id)
    {
        $from = Table::ELECTRICAL_SHUTDOWN;
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
        $from = Table::ELECTRICAL_SHUTDOWN;
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
        $select = ["COUNT(description) AS elec_shutdown, MONTH(last_modified_time) AS month"];
        $from = Table::ELECTRICAL_SHUTDOWN;
        $sql = "YEAR(last_modified_time) =:year";
        $group_by = "MONTH(last_modified_time)";    // No GROUP BY keyword
        $order_by = "month";
        $data_in = ["year" => $year];
        $count = $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], false);
        $elec_shutdown_count = array_fill(0, 12, 0);
        foreach ($count as $elec_shut) {
            $month = intval($elec_shut->month);
            $elecShutCount = intval($elec_shut->elec_shutdown);
            $elec_shutdown_count[$month - 1] = $elecShutCount;
        }
        $elec_shut_count_by_year = array_values($elec_shutdown_count);
        return $elec_shut_count_by_year;
    }


    public function getCountByStatus()
    {
        $sql = "status=10";
        $data =  $this->getAllData($sql, [], "", true);
        return isset($data) && isset($data[0]) ? $data[0]->total_count : 0;
    }


    public function scrollingData()
    {
        $sql = "CURRENT_DATE() BETWEEN from_date AND to_date";
        $data = $this->getAllData($sql);
        return $data;
    }
    /*
    public static $STATUS_GROUPED = [
    'User Submission' => [15],
    'HOS' => [25, 24],
    'HOD' => [35, 34],
     ];
 
   public function getStatusTracker($currentStatus, $createdBy = null)
    { 
    $tracker = [];
    $isCurrentFound = false;

    foreach (self::$STATUS_GROUPED as $label => $codes) {
        $isCurrent = in_array($currentStatus, $codes);

        if ($isCurrent) {
            $isCompleted = true; // current step also completed
            $isCurrentFound = true;
        } elseif (!$isCurrentFound) {
            $isCompleted = true; // previous steps completed
        } else {
            $isCompleted = false; // future steps not completed
        }

        // For User Submission, replace label with name
        if ($label === 'User Submission' && $createdBy) {
            $displayLabel = $createdBy;
        } else {
            $displayLabel = $label;
        }

        $tracker[] = [
            'status' => $codes[0],   // main status representative
            'label' => $displayLabel,
            'is_current' => $isCurrent,
            'is_completed' => $isCompleted
        ];
    }

    return [
        'current_status' => $currentStatus,
        'status_list' => $tracker
    ];
   }
   */
    public static $STATUS_GROUPED = [
    'User Submission' => [15],
    'HOS' => [25, 24], // 24 = reject
    'HOD' => [35, 34], // 34 = reject
  ];

    public function getStatusTracker($currentStatus, $createdBy = null)
   {   
    $tracker = [];
    $isCurrentFound = false;
    $isRejectedFound = false;

    foreach (self::$STATUS_GROUPED as $label => $codes) {
        $isCurrent = in_array($currentStatus, $codes);

        // Check if this step is rejected
        $isRejected = false;
        if (in_array(24, $codes) && $currentStatus == 24) {
            $isRejected = true;
            $isRejectedFound = true;
        }
        if (in_array(34, $codes) && $currentStatus == 34) {
            $isRejected = true;
            $isRejectedFound = true;
        }

        if ($isRejected) {
            $isCompleted = false; // reject means not completed
        } elseif ($isRejectedFound) {
            $isCompleted = false; // all steps after reject = false
        } elseif ($isCurrent) {
            $isCompleted = true; 
            $isCurrentFound = true;
        } elseif (!$isCurrentFound) {
            $isCompleted = true; // previous steps completed
        } else {
            $isCompleted = false; // future steps not completed
        }

        // For User Submission, replace label with creator name
        if ($label === 'User Submission' && $createdBy) {
            $displayLabel = $createdBy;
        } else {
            $displayLabel = $label;
        }

        $tracker[] = [
            'status' => $codes[0],
            'label' => $displayLabel,
            'is_current' => $isCurrent,
            'is_completed' => $isCompleted,
            'is_rejected' => $isRejected
        ];
    }

    return [
        'current_status' => $currentStatus,
        'status_list' => $tracker
    ];
    }
}
