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
class TemporaryAdvanceHelper extends BaseHelper
{

    const schema = [
        "temporary_advance_number" => SmartConst::SCHEMA_VARCHAR,
        "raised_by" => SmartConst::SCHEMA_VARCHAR,
        "advance_amount" => SmartConst::SCHEMA_INTEGER,
        "settlement" => SmartConst::SCHEMA_VARCHAR,
        "balance" => SmartConst::SCHEMA_INTEGER,
        "applied_on_date" => SmartConst::SCHEMA_DATE,
        "sanction_date" => SmartConst::SCHEMA_DATE,
        "stock_reg_no" => SmartConst::SCHEMA_INTEGER,
        "physically_verified_by" => SmartConst::SCHEMA_VARCHAR,
        "title" => SmartConst::SCHEMA_VARCHAR,
        "description" => SmartConst::SCHEMA_TEXT,
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
        "hos_id" => SmartConst::SCHEMA_CUSER_ID,
        "hos_remarks" => SmartConst::SCHEMA_TEXT,
        "hos_time" => SmartConst::SCHEMA_CTIME,
        "hod_id" => SmartConst::SCHEMA_CUSER_ID,
        "hod_remarks" => SmartConst::SCHEMA_TEXT,
        "hod_time" => SmartConst::SCHEMA_CTIME,
        "ad_id" => SmartConst::SCHEMA_CUSER_ID,
        "ad_remarks" => SmartConst::SCHEMA_TEXT,
        "ad_time" => SmartConst::SCHEMA_CTIME,
        "gd_id" => SmartConst::SCHEMA_CUSER_ID,
        "gd_remarks" => SmartConst::SCHEMA_TEXT,
        "gd_time" => SmartConst::SCHEMA_CTIME,
        "supervisor_description" => SmartConst::SCHEMA_TEXT,
        "supervisor" => SmartConst::SCHEMA_INTEGER,
        "supervisor_time" => SmartConst::SCHEMA_CTIME,
        "purchase_amount" => SmartConst::SCHEMA_INTEGER,

    ];
    /**
     * 
     */
    const validations = [
        "title" => [
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
        "description" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Description"
            ]
        ],
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
        "temporary_advance_number" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter temporary_advance_number"
            ]
        ],
        "advance_amount" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter advance_amount"
            ]
        ],
        "balance" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter balance"
            ]
        ],
        "stock_reg_no" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter stock_reg_no"
            ]
        ],
        "settlement" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter settlement"
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
        "purchase_amount" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter purchase_amount"
            ]
        ],



    ];
    /**
     * 
     */
    public function insert(array $columns, array $data)
    {
        return $this->insertDb(self::schema, Table::TEMPRORARY_ADVANCE, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::TEMPRORARY_ADVANCE, $columns, $data, $id);
    }
    /**
     * 
     */
    public function getAllData($sql = "", $data_in = [], $group_by = "", $count = false)
    {
        $from = Table::TEMPRORARY_ADVANCE . " t1 
        INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID 
         LEFT JOIN " . Table::USERS . " t10 ON t10.ID = t1.supervisor
         LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.hos_id
         LEFT JOIN " . Table::USERS . " t12 ON t12.ID = t1.hod_id
          LEFT JOIN " . Table::USERS . " t13 ON t13.ID = t1.ad_id
         LEFT JOIN " . Table::USERS . " t14 ON t14.ID = t1.gd_id
        ";
        $select = [
            "t1.*,t2.ename as created_by",
            "t11.ename as hos_name",
            "t12.ename as hod_name",
            "t13.ename as ad_name",
            "t14.ename as gd_name",
            "t10.ename as supervisor_name"
        ];
        $order_by = "t1.created_time DESC";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], $count);
    }
    /**
     * 
     */
    public function getOneData($id)
    {
        $from = Table::TEMPRORARY_ADVANCE . " t1 
        INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID 
        LEFT JOIN " . Table::USERS . " t10 ON t10.ID = t1.supervisor
        LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.hos_id
         LEFT JOIN " . Table::USERS . " t12 ON t12.ID = t1.hod_id
          LEFT JOIN " . Table::USERS . " t13 ON t13.ID = t1.ad_id
         LEFT JOIN " . Table::USERS . " t14 ON t14.ID = t1.gd_id
        ";
        $select = [
            "t1.*,t2.ename as created_by",
            "t10.ename as supervisor_name",
            "t11.ename as hos_name",
            "t12.ename as hod_name",
            "t13.ename as ad_name",
            "t14.ename as gd_name"
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
        $from = Table::TEMPRORARY_ADVANCE;
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
        $from = Table::TEMPRORARY_ADVANCE;
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
        $select = ["COUNT(title) AS temporary_advance, MONTH(last_modified_time) AS month"];
        $from = Table::TEMPRORARY_ADVANCE;
        $sql = "YEAR(last_modified_time) =:year";
        $group_by = "MONTH(last_modified_time)";    // No GROUP BY keyword
        $order_by = "month";
        $data_in = ["year" => $year];
        $count = $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], false);
        $electrical_count = array_fill(0, 12, 0);
        foreach ($count as $elec) {
            $month = intval($elec->month);
            $eleCount = intval($elec->temporary_advance);
            $electrical_count[$month - 1] = $eleCount;
        }
        $elec_count_by_year = array_values($electrical_count);
        return $elec_count_by_year;
    }


    public function getCountByStatus()
    {
        $sql = "status=10";
        $data = $this->getAllData($sql, [], "", true);
        return isset($data) && isset($data[0]) ? $data[0]->total_count : 0;
    }
    //generateTemporaryAdvanceNumber
    public function generateTemporaryAdvanceNumber()
    {
    $from = Table::TEMPRORARY_ADVANCE;
    $select = ["COUNT(*) as count"];
    // Filter only by current year
    $year = date("Y");
    $sql = "YEAR(created_time) = {$year} AND settlement IS NOT NULL";
    $result = $this->getAll($select, $from . " t1", $sql, "", "", [], true);
    $count = (int) ($result->count ?? 0) + 1;
    $number = str_pad($count, 3, '0', STR_PAD_LEFT);
    return "TA-{$year}-{$number}";
    }

    //generateSettlementNumber
    public function generateSettlementNumber()
    {
    $from = Table::TEMPRORARY_ADVANCE;
    $select = ["COUNT(*) as count"];
    // Filter only by current year
    $year = date("Y");
    $sql = "YEAR(created_time) = {$year} AND settlement IS NOT NULL";
    $result = $this->getAll($select, $from . " t1", $sql, "", "", [], true);
    $count = (int) ($result->count ?? 0) + 1;
    $number = str_pad($count, 3, '0', STR_PAD_LEFT);
    return "SET-{$year}-{$number}";
    }

    public function TemporaryAdvanceReport($start_date, $end_date)
    {
        $select = [
            "t1.temporary_advance_number",
            "t1.raised_by",
            "t1.advance_amount",
            "t1.settlement",
            "t1.balance",
            "t1.applied_on_date",
            "t1.sanction_date",
            "t1.stock_reg_no",
            "t1.physically_verified_by",
            "t1.title",
            "t1.description",
            "t1.sd_mt_userdb_id",
            "t1.admin_id",
            "t1.status",
            "t1.created_time",
            "t1.last_modified_by",
            "t1.last_modified_remarks",
            "t1.app_id",
            "t1.app_time",
            "t1.app_remarks",
            "t1.admin_time",
            "t1.admin_remarks",
            "t1.last_modified_time",
            "t2.ename"
        ];

        $from = Table::TEMPRORARY_ADVANCE . " t1
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

     public function getCountStatus($status_sql)
    {
        $select = ["COUNT(*) AS total_count"];
        $from = Table::TEMPRORARY_ADVANCE;
        $sql = "status IN (" . $status_sql . ")";
        $data = $this->getAll($select, $from, $sql, "", "", [], true);
        return  isset($data->total_count) ? (int)$data->total_count : 0;
    }

    //
    /*
    public static $TA_STATUS_GROUPED = [
    'User Submission' => [5],
    'Supervisor'      => [10],  // app rejected/approved/invalid
    'HOS'             => [15, 14], // approved/rejected/auto-approved
    'HOD'             => [20, 19],     // approved/rejected
    'AD'              => [25, 24],     // approved/rejected
    'GD'              => [30, 29],     // approved/rejected
    ];
    
    public function getTAStatusTracker($currentStatus, $createdBy = null)
    {
    $tracker = [];
    $foundCurrent = false;

    foreach (self::$TA_STATUS_GROUPED as $label => $codes) {
        $isCurrent = in_array($currentStatus, $codes);

        // mark completed until current step
        if (!$foundCurrent) {
            $isCompleted = true;
        } else {
            $isCompleted = false;
        }

        // once current step found, lock further steps
        if ($isCurrent) {
            $foundCurrent = true;
        }

        // show submitter name instead of "User Submission"
        if ($label === 'User Submission' && $createdBy) {
            $displayLabel = $createdBy;
        } else {
            $displayLabel = $label;
        }

        $tracker[] = [
            'status'       => $codes[0],   // representative code
            'label'        => $displayLabel,
            'is_current'   => $isCurrent,
            'is_completed' => $isCompleted
        ];
    }

    return [
        'current_status' => $currentStatus,
        'status_list'    => $tracker
    ];
    }
    */
    // getone status tracker 
    public static $TA_STATUS_GROUPED = [
    'User Submission' => [5],
    'Supervisor'      => [10],          // app rejected/approved/invalid
    'HOS'             => [15, 14],      // 15=approved, 14=rejected
    'HOD'             => [20, 19],      // 20=approved, 19=rejected
    'AD'              => [25, 24],      // 25=approved, 24=rejected
    'GD'              => [30, 29],      // 30=approved, 29=rejected
];

public function getTAStatusTracker($currentStatus, $createdBy = null)
{
    $tracker = [];
    $foundCurrent = false;
    $rejectedFound = false;

    foreach (self::$TA_STATUS_GROUPED as $label => $codes) {
        $isCurrent = in_array($currentStatus, $codes);

        // check rejection
        $isRejected = false;
        if (($currentStatus == 14 && in_array(14, $codes)) ||
            ($currentStatus == 19 && in_array(19, $codes)) ||
            ($currentStatus == 24 && in_array(24, $codes)) ||
            ($currentStatus == 29 && in_array(29, $codes))) {
            $isRejected = true;
            $rejectedFound = true;
        }

        // status logic
        if ($isRejected) {
            $isCompleted = false;
        } elseif ($rejectedFound) {
            $isCompleted = false; // all after reject = false
        } elseif ($isCurrent) {
            $isCompleted = true;
            $foundCurrent = true;
        } elseif (!$foundCurrent) {
            $isCompleted = true; // previous steps completed
        } else {
            $isCompleted = false; // future not completed
        }

        // show submitter name
        if ($label === 'User Submission' && $createdBy) {
            $displayLabel = $createdBy;
        } else {
            $displayLabel = $label;
        }

        $tracker[] = [
            'status'       => $codes[0],
            'label'        => $displayLabel,
            'is_current'   => $isCurrent,
            'is_completed' => $isCompleted,
            'is_rejected'  => $isRejected
        ];
    }

    return [
        'current_status' => $currentStatus,
        'status_list'    => $tracker
    ];
}
    //report status tracker purpose
    public static $TA_STATUS_GROUPED_NEW = [
    'Submitted (Waiting for Supervisor)' => [5],

    'Supervisor Processing' => [10],

    'HOS Processing' => [15],
    'HOS Rejected'   => [14],

    'HOD Processing' => [20],
    'HOD Rejected'   => [19],

    'AD Processing' => [25],
    'AD Rejected'   => [24],

    'GD Processing' => [30],
    'GD Rejected'   => [29],
];
     public function getTAStatusTrackerNew($currentStatus, $createdBy = null)
{
    $tracker = [];
    $foundCurrent = false;
    $rejectedFound = false;

    foreach (self::$TA_STATUS_GROUPED_NEW as $label => $codes) {

        $isCurrent = in_array($currentStatus, $codes);

        // rejected statuses = codes ending with 4 or 9
        $isRejected = ($codes[0] % 5 == 4);

        if ($isRejected && $isCurrent) {
            $rejectedFound = true;
        }

        // completion logic
        if ($isRejected && $isCurrent) {
            $isCompleted = false;
        } elseif ($rejectedFound) {
            $isCompleted = false;
        } elseif ($isCurrent) {
            $isCompleted = true;
            $foundCurrent = true;
        } elseif (!$foundCurrent) {
            $isCompleted = true;
        } else {
            $isCompleted = false;
        }

        // For submission, show username
        if ($codes[0] == 5 && $createdBy) {
            $label = $createdBy . " (Submitted)";
        }

        $tracker[] = [
            'status'       => $codes[0],
            'label'        => $label,
            'is_current'   => $isCurrent,
            'is_completed' => $isCompleted,
            'is_rejected'  => $isRejected
        ];
    }

    return [
        'current_status' => $currentStatus,
        'status_list'    => $tracker
    ];
}


}
