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
use Site\view\GemDirectPdf;


//
use Site\Helpers\TableHelper as Table;

/**
 * Description of Data
 * 
 *  class helps to get the data from post with specified type 
 *
 * @author kms
 */
class GemDirectHelper extends BaseHelper
{

    const schema = [

        "indent_no" => SmartConst::SCHEMA_VARCHAR,
        "item_brief_description" => SmartConst::SCHEMA_VARCHAR,
        "head_of_account" => SmartConst::SCHEMA_VARCHAR,
        "estimate_source" => SmartConst::SCHEMA_VARCHAR,
        "cost" => SmartConst::SCHEMA_VARCHAR,
        "gem_id_item" => SmartConst::SCHEMA_VARCHAR,
        "justification_purchase" => SmartConst::SCHEMA_VARCHAR,
        "cctv_system" => SmartConst::SCHEMA_VARCHAR,
        "dvr_cctv_system" => SmartConst::SCHEMA_VARCHAR,
        "quantity" => SmartConst::SCHEMA_VARCHAR,
        "unit" => SmartConst::SCHEMA_VARCHAR,
        "sd_mt_userdb_id" => SmartConst::SCHEMA_CUSER_ID,
        "hos_id" => SmartConst::SCHEMA_CUSER_ID,
        "hos_remarks" => SmartConst::SCHEMA_TEXT,
        "hos_time" => SmartConst::SCHEMA_CTIME,
        "financial_approval_id" => SmartConst::SCHEMA_CUSER_ID,
        "financial_approval_remarks" => SmartConst::SCHEMA_TEXT,
        "financial_approval_time" => SmartConst::SCHEMA_CTIME,
        "status" => SmartConst::SCHEMA_INTEGER,
        "last_modified_by" => SmartConst::SCHEMA_CUSER_ID,
        "last_modified_time" => SmartConst::SCHEMA_CTIME,
        "created_time" => SmartConst::SCHEMA_CDATETIME,
        "doc_loc" => SmartConst::SCHEMA_TEXT,
        "detailed_specification" => SmartConst::SCHEMA_VARCHAR


    ];

    /**
     * 
     */
    const validations = [


        "indent_no" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter indent number"
            ]
        ],

        "item_brief_description" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter item brief description"
            ]
        ],

        "head_of_account" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please select head of account"
            ]
        ],

        "estimate_source" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter estimate source"
            ]
        ],

        "cost" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter cost"
            ]
        ],

        "gem_id_item" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter GeM ID item"
            ]
        ],

        "justification_purchase" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter justification for purchase"
            ]
        ],

        "quantity" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter quantity"
            ]
        ],

        "unit" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter unit"
            ]
        ],


        "status" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Status is required"
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



    ];
    // file handling 
    const FILE_FOLDER = "gemdirect";
    const FILE_NAME = "file";
    public function getFullFile($id)
    {
        return self::FILE_FOLDER . DS . $id . DS . self::FILE_NAME;
    }

    /**
     * 
     */
    public function insert(array $columns, array $data)
    {
        return $this->insertDb(self::schema, Table::GEM_DIRECT, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::GEM_DIRECT, $columns, $data, $id);
    }
    /**
     * 
     */
    public function getAllData($sql = "", $data_in = [], $group_by = "", $count = false)
    {
        // $from = Table::GEM_DIRECT . " t1 
        // INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID 
        //  LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.hos_id  
        //  LEFT JOIN " . Table::USERS . " t13 ON t13.ID = t1.financial_approval_id
        //  LEFT JOIN " . Table::BUDGET_TYPE . " t15 ON t15.ID = t1.head_of_account
        // ";
         $from = Table::GEM_DIRECT . " t1 
         INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID 

         LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.hos_id
         LEFT JOIN " . Table::ORGANISATION . " t21 ON t11.sd_org_id = t21.ID

         LEFT JOIN " . Table::USERS . " t13 ON t13.ID = t1.financial_approval_id
         LEFT JOIN " . Table::ORGANISATION . " t23 ON t13.sd_org_id = t23.ID

         LEFT JOIN " . Table::BUDGET_TYPE . " t15 ON t15.ID = t1.head_of_account
         LEFT JOIN " . Table::ORGANISATION . " t20 ON t2.sd_org_id = t20.ID
        ";


        // $select = [
        //     "t1.*,t2.ename as created_by",
        //     "t11.ename as hos_name",
        //     "t13.ename as financial_approval_name",
        //     "t15.budget_no as head_of_account",
        //     "t2.emailid as created_by_email",
        //     "t2.mobile_no as created_by_mobile_no",
        //     "t2.designation as created_by_designation",
        // ];
          $select = [
    "t1.*",
    "t2.ename as created_by",
    "t11.ename as hos_name",
    "t13.ename as financial_approval_name",
    "t21.sd_org_name as hos_org_desc",
    "t23.sd_org_name as financial_approval_org_desc",
    "t15.budget_no as head_of_account",
    "t2.emailid as created_by_email",
    "t2.mobile_no as created_by_mobile_no",
    "t2.designation as created_by_designation",
    "t20.sd_org_name as sd_org_id_desc",
     "t2.intercome_number as created_by_intercome"
     ];
        
        $order_by = "t1.created_time DESC";
        // Ensure $sql is not empty to avoid SQL syntax errors
        if (empty($sql)) {
            $sql = "1=1";
        }
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], $count);

    }
    /**
     * 
     */
    public function getOneData($id)
    {
        // $from = Table::GEM_DIRECT . " t1 
        // INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID 
        //  LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.hos_id  
        //  LEFT JOIN " . Table::USERS . " t13 ON t13.ID = t1.financial_approval_id
        //  LEFT JOIN " . Table::BUDGET_TYPE . " t15 ON t15.ID = t1.head_of_account
        // ";
         $from = Table::GEM_DIRECT . " t1 
         INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID 

         LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.hos_id
         LEFT JOIN " . Table::ORGANISATION . " t21 ON t11.sd_org_id = t21.ID



         LEFT JOIN " . Table::USERS . " t13 ON t13.ID = t1.financial_approval_id
         LEFT JOIN " . Table::ORGANISATION . " t23 ON t13.sd_org_id = t23.ID


         LEFT JOIN " . Table::BUDGET_TYPE . " t15 ON t15.ID = t1.head_of_account
         LEFT JOIN " . Table::ORGANISATION . " t20 ON t2.sd_org_id = t20.ID
        ";


        // $select = [
        //     "t1.*,t2.ename as created_by",
        //     "t11.ename as hos_name",
        //     "t13.ename as financial_approval_name",
        //     "t15.budget_no as head_of_account",
        //     "t2.emailid as created_by_email",
        //     "t2.mobile_no as created_by_mobile_no",
        //     "t2.designation as created_by_designation",
        // ];
                $select = [
    "t1.*",
    "t2.ename as created_by",
    "t11.ename as hos_name",
    "t13.ename as financial_approval_name",
    "t21.sd_org_name as hos_org_desc",
    "t23.sd_org_name as financial_approval_org_desc",
    "t15.budget_no as head_of_account",
    "t2.emailid as created_by_email",
    "t2.mobile_no as created_by_mobile_no",
    "t2.designation as created_by_designation",
    "t20.sd_org_name as sd_org_id_desc",
     "t2.intercome_number as created_by_intercome"
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
        $from = Table::GEM_DIRECT;
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
        $from = Table::GEM_DIRECT;
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
        return isset($data) && isset($data->total_count) ? $data->total_count : 0;
    }


    public function getCountByYear($year)
    {
        $select = ["COUNT(name) AS rfid_card, MONTH(last_modified_time) AS month"];
        $from = Table::GEM_DIRECT;
        $sql = "YEAR(last_modified_time) =:year";
        $group_by = "MONTH(last_modified_time)";    // No GROUP BY keyword
        $order_by = "month";
        $data_in = ["year" => $year];
        $count = $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], false);
        $electrical_count = array_fill(0, 12, 0);
        foreach ($count as $elec) {
            $month = intval($elec->month);
            $eleCount = intval($elec->rfid_card);
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
    //generateCommiteeIibccNumber
    public function generateCommiteeIibccNumber()
    {
        $from = Table::GEM_DIRECT;
        $select = ["COUNT(*) as count"];
        $sql = "DATE(created_time) = CURDATE()";
        $result = $this->getAll($select, $from . " t1", $sql, "", "", [], true);
        $count = (int) ($result->count ?? 0) + 1; // Changed from array to object access
        $number = str_pad($count, 3, '0', STR_PAD_LEFT);
        $date = date("Ymd");
        return "IIB-$date-$number";
    }

    public function getCountStatus($status_sql)
    {
        $select = ["COUNT(*) AS total_count"];
        $from = Table::GEM_DIRECT;
        $sql = "status IN (" . $status_sql . ")";
        $data = $this->getAll($select, $from, $sql, "", "", [], true);
        return isset($data->total_count) ? (int) $data->total_count : 0;
    }


    // getone status tarcker purpose
    public static $STATUS_GROUPED = [
       

        'User Submission' => [10],
        'HOS' => [15, 14],  // 15 = approved, 14 = rejected
        'Financial Approval' => [20, 19], // 20 = approved, 19 = rejected


    ];

    public function getStatusTracker($currentStatus, $createdBy = null)
    {
        $tracker = [];
        $foundCurrent = false;
        $rejectFound = false;

        foreach (self::$STATUS_GROUPED as $label => $codes) {
            $isCurrent = in_array($currentStatus, $codes);

            // Check if rework
            //    $isRework = ($currentStatus == 40);
            $isReject = in_array($currentStatus, array_filter($codes, fn($c) => $c % 5 === 4)); // all rework codes end with 4 or 9

            if ($isReject) {
                $rejectFound = true;
            }

            // Mark completed logic
            if ($isReject) {
                $isCompleted = false;
            } elseif ($rejectFound) {
                $isCompleted = false; // all future steps after reject = false
            } elseif ($isCurrent) {
                $isCompleted = true;
                $foundCurrent = true;
            } elseif (!$foundCurrent) {
                $isCompleted = true; // previous steps completed
            } else {
                $isCompleted = false; // future steps pending
            }

            // Custom label for User Submission
            $displayLabel = ($label === 'User Submission' && $createdBy) ? $createdBy : $label;

            $tracker[] = [
                'status' => $codes[0],
                'label' => $displayLabel,
                'is_current' => $isCurrent,
                'is_completed' => $isCompleted,
                'is_reject' => $isReject
            ];
        }

        return [
            'current_status' => $currentStatus,
            'status_list' => $tracker
        ];
    }

    //report status tracker purpose
    public static $STATUS_GROUPED_NEW = [
        'Submitted (Waiting for HOS)' => [10],

        'HOS Processing' => [15],
        'HOS Reject' => [14],

        
        'Financial Approval Processing' => [20],
        'Financial Approval Reject' => [19],

    ];
    public function getStatusTrackerNew($currentStatus, $createdBy = null)
    {
        $tracker = [];
        $foundCurrent = false;
        $rejectFound = false;

        foreach (self::$STATUS_GROUPED_NEW as $label => $codes) {

            $isCurrent = in_array($currentStatus, $codes);

            // Check rework (all odd -1 values like 14,19,24,29)
            $isReject = ($codes[0] % 5 == 4);

            if ($isReject && $isCurrent) {
                $rejectFound = true;
            }

            // Completed logic
            if ($isReject && $isCurrent) {
                $isCompleted = false;
            } elseif ($rejectFound) {
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
                'status' => $codes[0],
                'label' => $label,
                'is_current' => $isCurrent,
                'is_completed' => $isCompleted,
                'is_reject' => $isReject
            ];
        }

        return [
            'current_status' => $currentStatus,
            'status_list' => $tracker
        ];
    }
    //html generation for pdf
    public function generateRfidCardPdf($id, $data)
    {
        //  Generate HTML from your PDF layout class
        $html = GemDirectPdf::getHtml($data);
        //  Send HTML to cURL for PDF generation
        // $this->initiate_curl($html, $id);
        // echo $html;
    }
    
    //gem direct report
    public function GemDirectReport($start_date, $end_date, $indent_no, $head_of_account)
{
    $select = [
        "t1.*",
        "t2.ename AS created_by",
        "t11.ename AS hos_name",
        "t13.ename AS financial_approval_name",
        "t15.budget_no AS head_of_account",
        "t2.emailid AS created_by_email",
        "t2.mobile_no AS created_by_mobile_no",
        "t2.designation AS created_by_designation"
    ];

    $from = Table::GEM_DIRECT . " t1
        INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID
        LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.hos_id
        LEFT JOIN " . Table::USERS . " t13 ON t13.ID = t1.financial_approval_id
        LEFT JOIN " . Table::BUDGET_TYPE . " t15 ON t15.ID = t1.head_of_account
    ";

    $conditions = [];
    $data_in = [];

    if (!empty($start_date) && !empty($end_date)) {
        $conditions[] = "DATE(t1.created_time) BETWEEN :start_date AND :end_date";
        $data_in["start_date"] = $start_date;
        $data_in["end_date"]   = $end_date;
    }

    if (!empty($indent_no)) {
        $conditions[] = "t1.indent_no = :indent_no";
        $data_in["indent_no"] = $indent_no;
    }

    if (!empty($head_of_account)) {
        $conditions[] = "t1.head_of_account = :head_of_account";
        $data_in["head_of_account"] = $head_of_account;
    }

    $sql = count($conditions) ? implode(" AND ", $conditions) : "1=1";

    $order_by = "t1.created_time DESC";

    return $this->getAll($select, $from, $sql, "", $order_by, $data_in, false, [], false);
}




}
