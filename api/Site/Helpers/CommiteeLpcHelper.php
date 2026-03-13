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
use Site\view\CommiteeLpcPdf;


//
use Site\Helpers\TableHelper as Table;

/**
 * Description of Data
 * 
 *  class helps to get the data from post with specified type 
 *
 * @author kms
 */
class CommiteeLpcHelper extends BaseHelper
{

    const schema = [

        "indent_no" => SmartConst::SCHEMA_VARCHAR,
        "justification_purchase" => SmartConst::SCHEMA_VARCHAR,
        "total_estimated_cost" => SmartConst::SCHEMA_VARCHAR,
        "item_category" => SmartConst::SCHEMA_VARCHAR,
        "stores_unit" => SmartConst::SCHEMA_VARCHAR,
        "gem_non_availablity" => SmartConst::SCHEMA_VARCHAR,
        "estimated_quantity_cost" => SmartConst::SCHEMA_VARCHAR,
        "items_purchased" => SmartConst::SCHEMA_VARCHAR,
        "availability_funds" => SmartConst::SCHEMA_VARCHAR,
        "head_of_account" => SmartConst::SCHEMA_VARCHAR,
        "status" => SmartConst::SCHEMA_INTEGER,
        "created_time" => SmartConst::SCHEMA_CDATE,
        "hos_id" => SmartConst::SCHEMA_CUSER_ID,
        "hos_remarks" => SmartConst::SCHEMA_TEXT,
        "hos_time" => SmartConst::SCHEMA_CTIME,
        "hod_id" => SmartConst::SCHEMA_CUSER_ID,
        "hod_remarks" => SmartConst::SCHEMA_TEXT,
        "hod_time" => SmartConst::SCHEMA_CTIME,
        "last_modified_by" => SmartConst::SCHEMA_CUSER_ID,
        "last_modified_time" => SmartConst::SCHEMA_CTIME,
        "sd_mt_userdb_id" => SmartConst::SCHEMA_CUSER_ID,
        "lpc_approver_id" => SmartConst::SCHEMA_INTEGER,
        "lpc_approver_remarks" => SmartConst::SCHEMA_TEXT,
        "lpc_approver_time" => SmartConst::SCHEMA_CTIME,
        "lpc_chairman_id" => SmartConst::SCHEMA_CUSER_ID,
        "lpc_chairman_remarks" => SmartConst::SCHEMA_TEXT,      
        "lpc_chairman_time" => SmartConst::SCHEMA_CTIME,
        "doc_loc" => SmartConst::SCHEMA_TEXT,
        "fund_available" => SmartConst::SCHEMA_VARCHAR,
      

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

    "justification_purchase" => [
        [
            "type" => SmartConst::VALID_REQUIRED,
            "msg" => "Please enter justification for purchase"
        ]
    ],

    "total_estimated_cost" => [
        [
            "type" => SmartConst::VALID_REQUIRED,
            "msg" => "Please enter total estimated cost"
        ]
    ],

    "item_category" => [
        [
            "type" => SmartConst::VALID_REQUIRED,
            "msg" => "Please select item category"
        ]
    ],

    "stores_unit" => [
        [
            "type" => SmartConst::VALID_REQUIRED,
            "msg" => "Please enter stores unit"
        ]
    ],

    "gem_non_availablity" => [
        [
            "type" => SmartConst::VALID_REQUIRED,
            "msg" => "Please specify GEM non-availability"
        ]
    ],

    "estimated_quantity_cost" => [
        [
            "type" => SmartConst::VALID_REQUIRED,
            "msg" => "Please enter estimated quantity cost"
        ]
    ],

    "items_purchased" => [
        [
            "type" => SmartConst::VALID_REQUIRED,
            "msg" => "Please enter items to be purchased"
        ]
    ],

    "head_of_account" => [
        [
            "type" => SmartConst::VALID_REQUIRED,
            "msg" => "Please enter head of account"
        ]
    ],
    "availability_funds" => [
        [
            "type" => SmartConst::VALID_REQUIRED,
            "msg" => "Please specify availability of funds"
        ]
    ],
    "status" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter status"
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
        "lpc_approver_id" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please select LPC Approver"
            ]
        ],
        "fund_available" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please select fund availability"
            ]
        ],


];
     // file handling 
     const FILE_FOLDER = "commiteelpc";
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
        return $this->insertDb(self::schema, Table::COMMITTEE_LPC, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::COMMITTEE_LPC, $columns, $data, $id);
    }
    /**
     * 
     */
    public function getAllData($sql = "", $data_in = [], $group_by = "", $count = false)
    {
        // $from = Table::COMMITTEE_LPC . " t1 
        // INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID 
        //  LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.hos_id
        //  LEFT JOIN " . Table::USERS . " t12 ON t12.ID = t1.hod_id
        //  LEFT JOIN " . Table::USERS . " t13 ON t13.ID = t1.lpc_approver_id
        //  LEFT JOIN " . Table::USERS . " t14 ON t14.ID = t1.lpc_chairman_id
        // LEFT JOIN " . Table::BUDGET_TYPE . " t15 ON t15.ID = t1.head_of_account
        // ";
         $from = Table::COMMITTEE_LPC . " t1 
          INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID 

          LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.hos_id
          LEFT JOIN " . Table::ORGANISATION . " t21 ON t11.sd_org_id = t21.ID

          LEFT JOIN " . Table::USERS . " t12 ON t12.ID = t1.hod_id
          LEFT JOIN " . Table::ORGANISATION . " t22 ON t12.sd_org_id = t22.ID

          LEFT JOIN " . Table::USERS . " t13 ON t13.ID = t1.lpc_approver_id
          LEFT JOIN " . Table::ORGANISATION . " t23 ON t13.sd_org_id = t23.ID

          LEFT JOIN " . Table::USERS . " t14 ON t14.ID = t1.lpc_chairman_id
          LEFT JOIN " . Table::ORGANISATION . " t24 ON t14.sd_org_id = t24.ID

          LEFT JOIN " . Table::BUDGET_TYPE . " t15 ON t15.ID = t1.head_of_account
           ";
        
        // $select = [
        //     "t1.*,t2.ename as created_by",
        //     "t11.ename as hos_name",
        //     "t12.ename as hod_name",
        //     "t13.ename as lpc_approver_name",
        //     "t14.ename as lpc_chairman_name",
        //     "t15.budget_no as head_of_account",
        //     "t2.emailid as created_by_email",
        //     "t2.mobile_no as created_by_mobile_no",
        //     "t2.designation as created_by_designation",
        // ];
        $select = [
    "t1.*",
    "t2.ename as created_by",
    "t11.ename as hos_name",
    "t12.ename as hod_name",
    "t13.ename as lpc_approver_name",
    "t14.ename as lpc_chairman_name",
    "t21.sd_org_name as hos_org_desc",
    "t22.sd_org_name as hod_org_desc",
    "t23.sd_org_name as lpc_approver_org_desc",
    "t24.sd_org_name as lpc_chairman_org_desc",
    "t15.budget_no as head_of_account",
    "t2.emailid as created_by_email",
    "t2.mobile_no as created_by_mobile_no",
    "t2.designation as created_by_designation",
    "t2.intercome_number as created_by_intercome"
    ];
        $order_by = "t1.created_time DESC";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], $count);
    }
    /**
     * 
     */
    public function getOneData($id)
    {
        // $from = Table::COMMITTEE_LPC . " t1 
        // INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID 
        // LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.hos_id
        // LEFT JOIN " . Table::USERS . " t12 ON t12.ID = t1.hod_id
        // LEFT JOIN " . Table::USERS . " t13 ON t13.ID = t1.lpc_approver_id
        // LEFT JOIN " . Table::USERS . " t14 ON t14.ID = t1.lpc_chairman_id
        // LEFT JOIN " . Table::BUDGET_TYPE . " t15 ON t15.ID = t1.head_of_account       
        // ";
           $from = Table::COMMITTEE_LPC . " t1 
          INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID 

          LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.hos_id
          LEFT JOIN " . Table::ORGANISATION . " t21 ON t11.sd_org_id = t21.ID

          LEFT JOIN " . Table::USERS . " t12 ON t12.ID = t1.hod_id
          LEFT JOIN " . Table::ORGANISATION . " t22 ON t12.sd_org_id = t22.ID

          LEFT JOIN " . Table::USERS . " t13 ON t13.ID = t1.lpc_approver_id
          LEFT JOIN " . Table::ORGANISATION . " t23 ON t13.sd_org_id = t23.ID

          LEFT JOIN " . Table::USERS . " t14 ON t14.ID = t1.lpc_chairman_id
          LEFT JOIN " . Table::ORGANISATION . " t24 ON t14.sd_org_id = t24.ID

          LEFT JOIN " . Table::BUDGET_TYPE . " t15 ON t15.ID = t1.head_of_account
           ";

        // $select = [
        //     "t1.*,t2.ename as created_by",
        //     "t11.ename as hos_name",
        //     "t12.ename as hod_name",
        //     "t13.ename as lpc_approver_name",
        //     "t14.ename as lpc_chairman_name"
        //     ,"t15.budget_no as head_of_account"
        //     ,"t2.emailid as created_by_email",
        //     "t2.mobile_no as created_by_mobile_no",
        //     "t2.designation as created_by_designation"
        // ];
             $select = [
    "t1.*",
    "t2.ename as created_by",
    "t11.ename as hos_name",
    "t12.ename as hod_name",
    "t13.ename as lpc_approver_name",
    "t14.ename as lpc_chairman_name",
    "t21.sd_org_name as hos_org_desc",
    "t22.sd_org_name as hod_org_desc",
    "t23.sd_org_name as lpc_approver_org_desc",
    "t24.sd_org_name as lpc_chairman_org_desc",
    "t15.budget_no as head_of_account",
    "t2.emailid as created_by_email",
    "t2.mobile_no as created_by_mobile_no",
    "t2.designation as created_by_designation",
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
        $from = Table::COMMITTEE_LPC;
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
        $from = Table::COMMITTEE_LPC;
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
        $select = ["COUNT(name) AS rfid_card, MONTH(last_modified_time) AS month"];
        $from = Table::COMMITTEE_LPC;
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
        $from = Table::COMMITTEE_LPC;
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
        $from = Table::COMMITTEE_LPC;
        $sql = "status IN (" . $status_sql . ")";
        $data = $this->getAll($select, $from, $sql, "", "", [], true);
        return isset($data->total_count) ? (int) $data->total_count : 0;
    }


    // getone status tarcker purpose
    public static $STATUS_GROUPED = [
        'User Submission' => [10],
        'HOS' => [15,40],          // added 40 for rework
        'HOD' => [20,40],          // added 40 for rework
        'Approver' => [25,40],  // added 40 for rework
       'Chairman' => [30,40],   // added 40 for rework

    ];

    public function getStatusTracker($currentStatus, $createdBy = null)
    {
        $tracker = [];
        $foundCurrent = false;
        $reworkFound = false;

        foreach (self::$STATUS_GROUPED as $label => $codes) {
            $isCurrent = in_array($currentStatus, $codes);

            // Check if rework
            $isRework = in_array($currentStatus, array_filter($codes, fn($c) => $c % 5 === 4)); // all rework codes end with 4 or 9
            if ($isRework) {
                $reworkFound = true;
            }

            // Mark completed logic
            if ($isRework) {
                $isCompleted = false;
            } elseif ($reworkFound) {
                $isCompleted = false; // all future steps after rework = false
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
                'is_rework' => $isRework
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
        'HOS Rework' => [40],

        'HOD Processing' => [20],
        'HOD Rework' => [40],

        'Approver Processing' => [25],
        'Approver Rework' => [40],
        
        'Chairman Processing' => [30],
        'Chairman Rework' => [40],

    ];
    public function getStatusTrackerNew($currentStatus, $createdBy = null)
    {
        $tracker = [];
        $foundCurrent = false;
        $reworkFound = false;

        foreach (self::$STATUS_GROUPED_NEW as $label => $codes) {

            $isCurrent = in_array($currentStatus, $codes);

            // Check rework (all odd -1 values like 14,19,24,29)
            $isRework = ($codes[0] % 5 == 4);

            if ($reworkFound && $isCurrent) {
                $reworkFound = true;
            }

            // Completed logic
            if ($reworkFound && $isCurrent) {
                $isCompleted = false;
            } elseif ($reworkFound) {
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
                'is_rework' => $isRework
            ];
        }

        return [
            'current_status' => $currentStatus,
            'status_list' => $tracker
        ];
    }
    //html generation for pdf
    //html generation for pdf
    public function generateCommiteeLpcPdf($id, $data)
    {
    //  Generate HTML from your PDF layout class
    $html = CommiteeLpcPdf::getHtml($data);
    //  Send HTML to cURL for PDF generation
    // $this->initiate_curl($html, $id);
    // echo $html;
    }
    // In your CommiteeLpcHelper class

 public function CommiteeLpcReport($start_date, $end_date, $head_of_account, $indent_no, $estimate_source, )
    {
        $select = [
        "t1.ID",     
        "t1.indent_no",
        "t1.justification_purchase",
        "t1.total_estimated_cost",
        "t1.item_category",
        "t1.stores_unit",
        "t1.gem_non_availablity",
        "t1.estimated_quantity_cost",
        "t1.items_purchased",
        "t1.availability_funds",
        "t1.head_of_account",
        "t1.status",
        "t1.created_time",
        "t1.hos_remarks",
        "t1.hos_time",
        "t1.hod_remarks",
        "t1.hod_time",
        "t1.last_modified_by",
        "t1.last_modified_time",
        "t1.sd_mt_userdb_id",
        "t2.ename as created_by",
        "t11.ename as hos_name",
        "t12.ename as hod_name",
        "t13.ename as lpc_approver_name",
        "t14.ename as lpc_chairman_name",
        "t1.doc_loc",
        "t15.budget_no as head_of_account",
        "t2.emailid as created_by_email",
        "t2.mobile_no as created_by_mobile_no",
        "t2.designation as created_by_designation",
        "t1.fund_available"
        ];

        $from = Table::COMMITTEE_LPC . " t1 
        INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID 
         LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.hos_id
         LEFT JOIN " . Table::USERS . " t12 ON t12.ID = t1.hod_id
         LEFT JOIN " . Table::USERS . " t13 ON t13.ID = t1.lpc_approver_id
         LEFT JOIN " . Table::USERS . " t14 ON t14.ID = t1.lpc_chairman_id
        LEFT JOIN " . Table::BUDGET_TYPE . " t15 ON t15.ID = t1.head_of_account
        ";

        // Build conditions dynamically
        $conditions = [];
        $data_in = [];

        if (!empty($start_date) && !empty($end_date)) {
            $conditions[] = "DATE(t1.created_time) BETWEEN :start_date AND :end_date";
            $data_in["start_date"] = $start_date;
            $data_in["end_date"] = $end_date;
        }
        if (!empty($head_of_account)) {
            $conditions[] = "t1.head_of_account = :head_of_account";
            $data_in["head_of_account"] = $head_of_account;
        }
        if (!empty($indent_no)) {
            $conditions[] = "t1.indent_no = :indent_no";
            $data_in["indent_no"] = $indent_no;
        }
        if (!empty($item_category)) {
            $conditions[] = "t1.item_category = :item_category";
            $data_in["item_category"] = $item_category;
        }


        // If no conditions, just set 1=1 to get all
        $sql = count($conditions) ? implode(" AND ", $conditions) : "1=1";

        $order_by = "t1.created_time DESC";

        $single = false;
        $limit = [];
        $count = false;

        $data = $this->getAll($select, $from, $sql, "", $order_by, $data_in, $single, $limit, $count);
        return $data;
    }



}
