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
use Site\view\CommiteeIibccPdf;


//
use Site\Helpers\TableHelper as Table;

/**
 * Description of Data
 * 
 *  class helps to get the data from post with specified type 
 *
 * @author kms
 */
class CommiteeIibccHelper extends BaseHelper
{

    const schema = [

        "indent_no" => SmartConst::SCHEMA_VARCHAR,
        "iibcc_no" => SmartConst::SCHEMA_VARCHAR,
        "sd_mt_userdb_id" => SmartConst::SCHEMA_CUSER_ID,
        "name_of_item" => SmartConst::SCHEMA_VARCHAR,
        "nature_of_item" => SmartConst::SCHEMA_VARCHAR,
        "item_quantity" => SmartConst::SCHEMA_VARCHAR,
        "estimate_source" => SmartConst::SCHEMA_VARCHAR,
        "head_of_account" => SmartConst::SCHEMA_VARCHAR,
        "amount" => SmartConst::SCHEMA_INTEGER,
        "item_belongs_to" => SmartConst::SCHEMA_VARCHAR,
        "item_source" => SmartConst::SCHEMA_VARCHAR,
        "pdi_required" => SmartConst::SCHEMA_VARCHAR,
        "item_to_purchased" => SmartConst::SCHEMA_VARCHAR,
        "delivery_date" => SmartConst::SCHEMA_DATE,
        "description" => SmartConst::SCHEMA_VARCHAR,
        "hos_id" => SmartConst::SCHEMA_CUSER_ID,
        "hos_remarks" => SmartConst::SCHEMA_TEXT,
        "hos_time" => SmartConst::SCHEMA_CTIME,
        "hod_id" => SmartConst::SCHEMA_CUSER_ID,
        "hod_remarks" => SmartConst::SCHEMA_TEXT,
        "hod_time" => SmartConst::SCHEMA_CTIME,
        "last_modified_by" => SmartConst::SCHEMA_CUSER_ID,
        "last_modified_time" => SmartConst::SCHEMA_CTIME,
        "status" => SmartConst::SCHEMA_INTEGER,
        "created_time" => SmartConst::SCHEMA_CDATETIME,
        "iibcc_approver_id" => SmartConst::SCHEMA_INTEGER,
        "iibcc_approver_remarks" => SmartConst::SCHEMA_TEXT,
        "iibcc_approver_time" => SmartConst::SCHEMA_CTIME,
        "iibcc_chairman_id" => SmartConst::SCHEMA_CUSER_ID,
        "iibcc_chairman_remarks" => SmartConst::SCHEMA_TEXT,
        "iibcc_chairman_time" => SmartConst::SCHEMA_CTIME,
        "technical_sanction_number" => SmartConst::SCHEMA_VARCHAR,
        "doc_loc" => SmartConst::SCHEMA_TEXT,
        "supplier_1" => SmartConst::SCHEMA_VARCHAR,
        "supplier_2" => SmartConst::SCHEMA_VARCHAR,
        "supplier_3" => SmartConst::SCHEMA_VARCHAR,
        "technical_sanction_amount" => SmartConst::SCHEMA_VARCHAR,
        "gem_id_flag" => SmartConst::SCHEMA_VARCHAR,
        "gem_number" => SmartConst::SCHEMA_VARCHAR,
        "gem_approvals" => SmartConst::SCHEMA_VARCHAR,
        "store_certificate" => SmartConst::SCHEMA_VARCHAR,
        "unit"=>SmartConst::SCHEMA_VARCHAR
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
        "iibcc_no" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter iibcc number"
            ]
        ],

        "name_of_item" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter name of item"
            ]
        ],

        "item_quantity" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter item quantity"
            ]
        ],

        "estimate_source" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter estimate source"
            ]
        ],

        "head_of_account" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter head of account"
            ]
        ],

        "amount" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter amount"
            ]
        ],

        "item_belongs_to" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify item belongs to"
            ]
        ],

        "item_source" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify item source"
            ]
        ],

        "pdi_required" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify whether PDI is required"
            ]
        ],

        "item_to_purchased" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify item to be purchased"
            ]
        ],

        "delivery_date" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please select delivery date"
            ]
        ],

        "description" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter description"
            ]
        ],
        "nature_of_item" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter nature of item"
            ]
        ],
        "status" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter status"
            ]
        ],
        "technical_sanction_number" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter technical sanction number"
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
        "iibcc_approver_id" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please select IIBCC Approver"
            ]
        ],
        "supplier_1" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter supplier 1 details"
            ]
        ],
         "supplier_2" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter supplier 2 details"
            ]
        ],
         "supplier_3" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter supplier 3 details"
            ]
        ],
        "technical_sanction_amount" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter technical_sanction_amount"
            ]
        ],
        "gem_id_flag" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify whether item is GEM registered"
            ]
        ],
        "gem_number" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter GEM number if item is GEM registered"
            ]
        ],
        "gem_approvals" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter details of GEM approvals"
            ]
        ],
        "store_certificate" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter store certificate details"
            ]
        ],
        "unit"=> [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter store certificate details"
            ]
        ],
        


    ];
    // file handling 
     const FILE_FOLDER = "commiteeiibcc";
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
        return $this->insertDb(self::schema, Table::COMMITTEE_IIBCC, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::COMMITTEE_IIBCC, $columns, $data, $id);
    }
    /**
     * 
     */
    public function getAllData($sql = "", $data_in = [], $group_by = "", $count = false)
    {
        // $from = Table::COMMITTEE_IIBCC . " t1 
        // INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID 
        //  LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.hos_id
        //  LEFT JOIN " . Table::USERS . " t12 ON t12.ID = t1.hod_id
        //  LEFT JOIN " . Table::USERS . " t13 ON t13.ID = t1.iibcc_approver_id
        //  LEFT JOIN " . Table::USERS . " t14 ON t14.ID = t1.iibcc_chairman_id
        //  LEFT JOIN " . Table::BUDGET_TYPE . " t15 ON t15.ID = t1.head_of_account
        // ";

        $from = Table::COMMITTEE_IIBCC . " t1 
         INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID 

         LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.hos_id
         LEFT JOIN " . Table::ORGANISATION . " t21 ON t11.sd_org_id = t21.ID

         LEFT JOIN " . Table::USERS . " t12 ON t12.ID = t1.hod_id
         LEFT JOIN " . Table::ORGANISATION . " t22 ON t12.sd_org_id = t22.ID

         LEFT JOIN " . Table::USERS . " t13 ON t13.ID = t1.iibcc_approver_id
         LEFT JOIN " . Table::ORGANISATION . " t23 ON t13.sd_org_id = t23.ID

         LEFT JOIN " . Table::USERS . " t14 ON t14.ID = t1.iibcc_chairman_id
         LEFT JOIN " . Table::ORGANISATION . " t24 ON t14.sd_org_id = t24.ID

         LEFT JOIN " . Table::BUDGET_TYPE . " t15 ON t15.ID = t1.head_of_account
         LEFT JOIN " . Table::ORGANISATION . " t20 ON t2.sd_org_id = t20.ID
        ";
        // $select = [
        //     "t1.*,t2.ename as created_by",
        //     "t11.ename as hos_name",
        //     "t12.ename as hod_name",
        //     "t13.ename as iibcc_approver_name",
        //     "t14.ename as iibcc_chairman_name",
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
    "t13.ename as iibcc_approver_name",
    "t14.ename as iibcc_chairman_name",
    "t21.sd_org_name as hos_org_desc",
    "t22.sd_org_name as hod_org_desc",
    "t23.sd_org_name as iibcc_approver_org_desc",
    "t24.sd_org_name as iibcc_chairman_org_desc",
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
        // $from = Table::COMMITTEE_IIBCC . " t1 
        // INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID 
        // LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.hos_id
        //  LEFT JOIN " . Table::USERS . " t12 ON t12.ID = t1.hod_id
        // LEFT JOIN " . Table::USERS . " t13 ON t13.ID = t1.iibcc_approver_id
        // LEFT JOIN " . Table::USERS . " t14 ON t14.ID = t1.iibcc_chairman_id
        // LEFT JOIN " . Table::BUDGET_TYPE . " t15 ON t15.ID = t1.head_of_account
        // ";
        $from = Table::COMMITTEE_IIBCC . " t1 
         INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID 

         LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.hos_id
         LEFT JOIN " . Table::ORGANISATION . " t21 ON t11.sd_org_id = t21.ID

         LEFT JOIN " . Table::USERS . " t12 ON t12.ID = t1.hod_id
         LEFT JOIN " . Table::ORGANISATION . " t22 ON t12.sd_org_id = t22.ID

         LEFT JOIN " . Table::USERS . " t13 ON t13.ID = t1.iibcc_approver_id
         LEFT JOIN " . Table::ORGANISATION . " t23 ON t13.sd_org_id = t23.ID

         LEFT JOIN " . Table::USERS . " t14 ON t14.ID = t1.iibcc_chairman_id
         LEFT JOIN " . Table::ORGANISATION . " t24 ON t14.sd_org_id = t24.ID

         LEFT JOIN " . Table::BUDGET_TYPE . " t15 ON t15.ID = t1.head_of_account
         LEFT JOIN " . Table::ORGANISATION . " t20 ON t2.sd_org_id = t20.ID
        ";
        // $select = [
        //     "t1.*,t2.ename as created_by",
        //     "t11.ename as hos_name",
        //     "t12.ename as hod_name",
        //     "t13.ename as iibcc_approver_name",
        //     "t14.ename as iibcc_chairman_name",
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
    "t13.ename as iibcc_approver_name",
    "t14.ename as iibcc_chairman_name",
    "t21.sd_org_name as hos_org_desc",
    "t22.sd_org_name as hod_org_desc",
    "t23.sd_org_name as iibcc_approver_org_desc",
    "t24.sd_org_name as iibcc_chairman_org_desc",
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
        $from = Table::COMMITTEE_IIBCC;
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
        $from = Table::COMMITTEE_IIBCC;
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
        $from = Table::COMMITTEE_IIBCC;
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
        $from = Table::COMMITTEE_IIBCC;
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
        $from = Table::COMMITTEE_IIBCC;
        $sql = "status IN (" . $status_sql . ")";
        $data = $this->getAll($select, $from, $sql, "", "", [], true);
        return isset($data->total_count) ? (int) $data->total_count : 0;
    }


    // getone status tarcker purpose
    public static $STATUS_GROUPED = [
        'User Submission' => [10],
        'HOS' => [15,40],      // added 40 for rework
        'HOD' => [20,40],      // added 40 for rework   
        'Vetted by' => [25,40], // added 40 for rework
       'Chairman' => [30,40],  // added 40 for rework
  

    ];

    public function getStatusTracker($currentStatus, $createdBy = null)
    {
        $tracker = [];
        $foundCurrent = false;
        $reworkFound = false;

        foreach (self::$STATUS_GROUPED as $label => $codes) {
            $isCurrent = in_array($currentStatus, $codes);

            // Check if rework
        //    $isRework = ($currentStatus == 40);
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

        'Vetted by Processing' => [25],
        'Vetted by Rework' => [40],
        
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

            if ($isRework && $isCurrent) {
                $reworkFound = true;
            }

            // Completed logic
            if ($isRework && $isCurrent) {
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
     public function generateRfidCardPdf($id, $data)
    {
    //  Generate HTML from your PDF layout class
    $html = CommiteeIibccPdf::getHtml($data);
    //  Send HTML to cURL for PDF generation
    // $this->initiate_curl($html, $id);
    // echo $html;
    }
    // In your helper class

  public function CommiteeIibccReport($start_date, $end_date,$iibcc_no, $head_of_account, $indent_no, $estimate_source, $item_to_purchased)
    {
        $select = [
        "t1.indent_no",
        "t1.iibcc_no",
        "t1.sd_mt_userdb_id",
        "t2.ename AS created_by",
        "t1.name_of_item",
        "t1.nature_of_item",
        "t1.item_quantity",
        "t1.estimate_source",
        "t1.head_of_account",
        "t15.budget_no AS head_of_account",
        "t1.amount",
        "t1.item_belongs_to",
        "t1.item_source",
        "t1.pdi_required",
        "t1.item_to_purchased",
        "t1.delivery_date",
        "t1.description",
        "t11.ename as hos_name",
        "t1.hos_remarks",
        "t1.hos_time",
        "t12.ename as hod_name",
        "t1.hod_remarks",
        "t1.hod_time",
        "t13.ename as lpc_approver_name",
        "t1.iibcc_approver_remarks",
        "t1.iibcc_approver_time",
       "t14.ename as lpc_chairman_name",
        "t1.iibcc_chairman_remarks",
        "t1.iibcc_chairman_time",
        "t1.technical_sanction_number",
        "t1.doc_loc",
        "t1.status",
        "t1.created_time",
        "t1.last_modified_by",
        "t1.last_modified_time",
        "t2.emailid as created_by_email",
        "t2.mobile_no as created_by_mobile_no",
        "t2.designation as created_by_designation",
        "t1.supplier_1",
        "t1.supplier_2",
        "t1.supplier_3",
        "t1.technical_sanction_amount",
        "t1.gem_id_flag",
        "t1.gem_number",
        "t1.gem_approvals",
        "t1.store_certificate",
        "t1.unit"
        ];

 $from = Table::COMMITTEE_IIBCC . " t1 
        INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID 
         LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.hos_id
         LEFT JOIN " . Table::USERS . " t12 ON t12.ID = t1.hod_id
         LEFT JOIN " . Table::USERS . " t13 ON t13.ID = t1.iibcc_approver_id
         LEFT JOIN " . Table::USERS . " t14 ON t14.ID = t1.iibcc_chairman_id
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
        if (!empty($iibcc_no)) {
            $conditions[] = "t1.iibcc_no = :iibcc_no";
            $data_in["iibcc_no"] = $iibcc_no;
        }
        if (!empty($head_of_account)) {
            $conditions[] = "t1.head_of_account = :head_of_account";
            $data_in["head_of_account"] = $head_of_account;
        }
        if (!empty($indent_no)) {
            $conditions[] = "t1.indent_no = :indent_no";
            $data_in["indent_no"] = $indent_no;
        }
        if (!empty($estimate_source)) {
            $conditions[] = "t1.estimate_source = :estimate_source";
            $data_in["estimate_source"] = $estimate_source;
        }
        if (!empty($item_to_purchased)) {
            $conditions[] = "t1.item_to_purchased = :item_to_purchased";
            $data_in["item_to_purchased"] = $item_to_purchased;
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
