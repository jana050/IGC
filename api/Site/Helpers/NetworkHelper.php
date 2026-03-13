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
class NetworkHelper extends BaseHelper
{

    const schema = [
        "title" => SmartConst::SCHEMA_VARCHAR,
        "description" => SmartConst::SCHEMA_TEXT,
        "location" => SmartConst::SCHEMA_TEXT,
        "sd_mt_userdb_id" => SmartConst::SCHEMA_CUSER_ID,
        "app_id" => SmartConst::SCHEMA_INTEGER,
        "created_time" => SmartConst::SCHEMA_CDATETIME,
        "status" => SmartConst::SCHEMA_INTEGER,
        "last_modified_by" => SmartConst::SCHEMA_CUSER_ID,
        "last_modified_remarks" => SmartConst::SCHEMA_TEXT,
        "last_modified_time" => SmartConst::SCHEMA_CTIME,
        "date_of_closure" => SmartConst::SCHEMA_DATE,
        "supervisor_description" => SmartConst::SCHEMA_TEXT,
        "supervisor" => SmartConst::SCHEMA_INTEGER,
        "supervisor_time" => SmartConst::SCHEMA_CTIME,
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
        "location" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Location"
            ]
        ],
        "app_id" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please select Approving Authority"
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


    ];
    /**
     * 
     */
    public function insert(array $columns, array $data)
    {
        return $this->insertDb(self::schema, Table::NETWORK, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::NETWORK, $columns, $data, $id);
    }
    /**
     * 
     */
    public function getAllData($sql = "", $data_in = [], $group_by = "", $count = false)
    {
        $from = Table::NETWORK . " t1 
        INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID 
        LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.supervisor";
        $select = ["t1.*,t2.ename as created_by", "t11.ename as supervisor_name"];
        $order_by = "t1.created_time DESC";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], $count);
    }
    /**
     * 
     */
    public function getOneData($id)
    {
        $from = Table::NETWORK . " t1 
        INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID 
         LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.supervisor";
        $select = ["t1.*,t2.ename as created_by", "t11.ename as supervisor_name"];
        $sql = "t1.ID=:ID";
        $data_in = ["ID" => $id];
        $group_by = "";
        $order_by = "";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, true, []);
    }
    /**
     * 
     */
    public function getNetWithUserID($id)
    {
        $from = Table::NETWORK;
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
        $from = Table::NETWORK;
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
        $select = ["COUNT(title) AS network, MONTH(last_modified_time) AS month"];
        $from = Table::NETWORK;
        $sql = "YEAR(last_modified_time) =:year";
        $group_by = "MONTH(last_modified_time)";    // No GROUP BY keyword
        $order_by = "month";
        $data_in = ["year" => $year];
        $count = $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], false);
        $network_count = array_fill(0, 12, 0);
        foreach ($count as $net) {
            $month = intval($net->month);
            $netCount = intval($net->network);
            $network_count[$month - 1] = $netCount;
        }
        $net_count_by_year = array_values($network_count);
        return $net_count_by_year;
    }

    public function getCountByStatus()
    {
        $sql = "status=10";
        $data = $this->getAllData($sql, [], "", true);
        return isset($data) ? count($data) : 0;
    }
    //NetworkComplaintReport
    public function NetworkComplaintReport($start_date, $end_date)
    {
    $select = [
        "t1.title",
        "t1.description",
        "t1.location",
        "t1.sd_mt_userdb_id",
        "t1.app_id",
        "t1.created_time",
        "t1.status",
        "t1.last_modified_by",
        "t1.last_modified_remarks",
        "t1.last_modified_time",
        "t1.date_of_closure",
        "t1.supervisor_description",
        "t1.supervisor",
        "t1.supervisor_time",
        "t2.ename",
        "t11.ename as supervisor_name"
    ];

    $from = Table::NETWORK . " t1
        LEFT JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID
        LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.supervisor";

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
   //report status tracker purpose
    public static $STATUS_GROUPED_NEW = [
   'User Submission' => [10],
        'In Progress'     => [11],
        'Completed'       => [15],
        'Under Process'   => [19],
        'Pending'         => [14],
        'Final completed' => [30],
];
public function getStatusTrackerNew($currentStatus, $createdBy = null)
{
    $tracker = [];
    $foundCurrent = false;
    $rejectedFound = false;

    foreach (self::$STATUS_GROUPED_NEW as $label => $codes) {

        $isCurrent  = in_array($currentStatus, $codes);

        // Check rejected (all odd -1 values like 14,19,24,29)
        $isRejected = ($codes[0] % 5 == 4);

        if ($isRejected && $isCurrent) {
            $rejectedFound = true;
        }

        // Completed logic
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

        // Replace label for first stage (Submitted)
        if ($codes[0] == 10 && $createdBy) {
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
