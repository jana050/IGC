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
use Core\Helpers\SmartData;
use Core\Helpers\SmartDateHelper;
use Core\Helpers\SmartFileHelper;
use Core\Helpers\SmartCurl;
use Core\Helpers\SmartPdfHelper;
use Site\view\MbookIssuePdf;

//
use Site\Helpers\TableHelper as Table;

/**
 * Description of Data
 * 
 *  class helps to get the data from post with specified type 
 *
 * @author kms
 */
class MbookIssueHelper extends BaseHelper
{

    const schema = [
        "mbook_number" => SmartConst::SCHEMA_VARCHAR,
        "date_of_issue" => SmartConst::SCHEMA_DATE,
        "title" => SmartConst::SCHEMA_VARCHAR,
        "description" => SmartConst::SCHEMA_TEXT,
        "sd_mt_userdb_id" => SmartConst::SCHEMA_CUSER_ID,
        "app_id" => SmartConst::SCHEMA_INTEGER,
        "app_time" => SmartConst::SCHEMA_CDATETIME,
        "app_remarks" => SmartConst::SCHEMA_VARCHAR,
        "admin_id" => SmartConst::SCHEMA_INTEGER,
        "admin_time" => SmartConst::SCHEMA_CDATETIME,
        "admin_remarks" => SmartConst::SCHEMA_VARCHAR,
        "created_time" => SmartConst::SCHEMA_CDATETIME,
        "status" => SmartConst::SCHEMA_INTEGER,
        "last_modified_by" => SmartConst::SCHEMA_CUSER_ID,
        "last_modified_remarks" => SmartConst::SCHEMA_TEXT,
        "last_modified_time" => SmartConst::SCHEMA_CTIME,
        "doc_loc" => SmartConst::SCHEMA_VARCHAR,
        //
        "file_type" => SmartConst::SCHEMA_STRING,
        "work_order_number" => SmartConst::SCHEMA_VARCHAR,
        "date_of_work_order" => SmartConst::SCHEMA_DATE,
        "work_order_value" => SmartConst::SCHEMA_VARCHAR,
        "budget_type" => SmartConst::SCHEMA_STRING,
        "budget_pin" => SmartConst::SCHEMA_VARCHAR,
        "contact_name" => SmartConst::SCHEMA_VARCHAR,
        "technical_sanction_number" => SmartConst::SCHEMA_VARCHAR,    
        "start_date" => SmartConst::SCHEMA_DATE,
        "end_date" => SmartConst::SCHEMA_DATE,
        "cc_number" => SmartConst::SCHEMA_VARCHAR,
        "pan_number" => SmartConst::SCHEMA_VARCHAR,
        "email" => SmartConst::SCHEMA_VARCHAR,
        "mobile_no" => SmartConst::SCHEMA_INTEGER,
        "wages_count" => SmartConst::SCHEMA_INTEGER,
        "salary_amount" => SmartConst::SCHEMA_INTEGER,
        //
        "head_of_account" => SmartConst::SCHEMA_VARCHAR,
        "paid_amount" => SmartConst::SCHEMA_INTEGER,
        "balance_amount" => SmartConst::SCHEMA_INTEGER,
        //
        "indentor" => SmartConst::SCHEMA_VARCHAR,
        "technical_sanction_amount" => SmartConst::SCHEMA_VARCHAR,
        "procurement_stage" => SmartConst::SCHEMA_VARCHAR,
        "contacter_address" => SmartConst::SCHEMA_VARCHAR,
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
        // "description" => [
        //     [
        //         "type" => SmartConst::VALID_REQUIRED,
        //         "msg" => "Please Enter Description"
        //     ]
        // ],
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
        "mbook_number" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter mbook_number"
            ]
        ],
        "date_of_issue" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter date_of_issue"
            ]
        ],
        //  "designation" => [
        //     [
        //         "type" => SmartConst::VALID_REQUIRED,
        //         "msg" => "Please Enter designation"
        //     ]
        // ],
         "file_type" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter file_type"
            ]
        ],
        "name_of_work" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter name_of_work"
            ]
        ],
        "work_order_number" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter work_order_number"
            ]
        ],
        "date_of_work_order" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter date_of_work_order"
            ]
        ],
        "work_order_value" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter work_order_value"
            ]
        ],
        "budget_type" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter budget_type"
            ]
        ],
        "budget_pin" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter budget_pin"
            ]
        ],
        "contact_name" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter contact_name"
            ]
        ],
        "technical_sanction_number" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter technical_sanction_number"
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
        "cc_number" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter cc_number"
            ]
        ],
        "pan_number" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter pan_number"
            ]
        ],
        "email" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter email"
            ]
        ],
        "mobile_no" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter mobile_no"
            ]
        ],
         "wages_count" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter wages_count"
            ]
        ],
         "salary_amount" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter salary_amount"
            ]
        ],
        "head_of_account" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter head_of_account"
            ]
        ],
        "indentor" => [
         [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Select Indentor"
         ],
         "technical_sanction_amount" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter technical_sanction_amount"
            ]
        ],
        "procurement_stage" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter procurement_stage"
            ]
        ],

],

    ];
    // file handling 
   const FILE_FOLDER = "mbookissue";
   const FILE_NAME = "file";
    //
    public function getFullFile($id){
        return self::FILE_FOLDER . DS . $id . DS . self::FILE_NAME;
    }

    /**
     * 
     */
    public function insert(array $columns, array $data)
    {
        return $this->insertDb(self::schema, Table::MBOOK_ISSUE, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::MBOOK_ISSUE, $columns, $data, $id);
    }
    /**
     * 
     */
    public function getAllData($sql = "", $data_in = [], $group_by = "", $count = false)
    {
        $from = Table::MBOOK_ISSUE . " t1 
        INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID
        LEFT JOIN " . Table::BUDGET_TYPE . " t15 ON t15.ID = t1.head_of_account
        LEFT JOIN " . Table::USERS . " t16 ON t16.ID = t1.indentor
        LEFT JOIN " . Table::ORGANISATION . " t23 ON t16.sd_org_id = t23.ID";


       $select = ["t1.*","t2.ename as created_by","t15.budget_no as head_of_account","t15.amount as head_of_account_amount" ,  "t16.ename as indentor_name","t23.sd_org_name as indentor_designation_name"];

    //    $select[] = "(SELECT SUM(t3.ra_amount) FROM " . Table::MBOOK_ENTRY . " t3 WHERE t3.sd_mbook_issue_id = t1.ID AND t3.entry_status = 30) as paid_amount_approved";

    //    $select[] = "(SELECT SUM(t3.ra_amount) FROM " . Table::MBOOK_ENTRY . " t3 WHERE t3.sd_mbook_issue_id = t1.ID AND t3.entry_status IN (30)) as paid_amount_processing";
    $select[] = "(SELECT SUM(t3.ra_amount) 
              FROM " . Table::MBOOK_ENTRY . " t3 
              WHERE t3.sd_mbook_issue_id = t1.ID 
              AND t3.entry_status IN (30,35)) as paid_amount_approved";

    $select[] = "(SELECT SUM(t3.ra_amount) 
              FROM " . Table::MBOOK_ENTRY . " t3 
              WHERE t3.sd_mbook_issue_id = t1.ID 
              AND t3.entry_status IN (10,15,20,25)) as paid_amount_processing";


        $order_by = "t1.created_time DESC";

        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], $count);
    }

    /**
     * 
     */
    /*
    public function getOneData($id)
    {
        $from = Table::MBOOK_ISSUE . " t1 
        INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID 
        LEFT JOIN " . Table::BUDGET_TYPE . " t15 ON t15.ID = t1.head_of_account";
        $select = ["t1.*,t2.ename as created_by","t15.budget_no as head_of_account"];
        $sql = "t1.ID=:ID";
        $data_in = ["ID" => $id];
        $group_by = "";
        $order_by = "";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, true, []);
    }
        */
    public function getOneData($id)
    {
    $from = Table::MBOOK_ISSUE . " t1 
    INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID 
    LEFT JOIN " . Table::BUDGET_TYPE . " t15 ON t15.ID = t1.head_of_account
    LEFT JOIN " . Table::USERS . " t16 ON t16.ID = t1.indentor
   LEFT JOIN " . Table::ORGANISATION . " t23 ON t16.sd_org_id = t23.ID";


    $select = [
        "t1.*",
        "t2.ename as created_by",
        "t15.budget_no as head_of_account",
        "t15.amount as head_of_account_amount",
        "t16.ename as indentor_name",
        "t23.sd_org_name as indentor_designation_name"
    ];

    $select[] = "(SELECT SUM(t3.ra_amount) 
                FROM " . Table::MBOOK_ENTRY . " t3 
                WHERE t3.sd_mbook_issue_id = t1.ID 
                AND t3.entry_status IN (30,35)) as paid_amount_approved";

    $select[] = "(SELECT SUM(t3.ra_amount) 
                FROM " . Table::MBOOK_ENTRY . " t3 
                WHERE t3.sd_mbook_issue_id = t1.ID 
                AND t3.entry_status IN (10,15,20,25)) as paid_amount_processing";

    $sql = "t1.ID=:ID";
    $data_in = ["ID" => $id];
    $group_by = "";
    $order_by = "";

    return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, true, []);
   }

    /**
     * 
     */
    public function getElecWithUserID($id)
    {
        $from = Table::MBOOK_ISSUE;
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
        $from = Table::MBOOK_ISSUE;
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
        $select = ["COUNT(title) AS mbook_issue, MONTH(last_modified_time) AS month"];
        $from = Table::MBOOK_ISSUE;
        $sql = "YEAR(last_modified_time) =:year";
        $group_by = "MONTH(last_modified_time)";    // No GROUP BY keyword
        $order_by = "month";
        $data_in = ["year" => $year];
        $count = $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], false);
        $mbook_issue_count = array_fill(0, 12, 0);
        foreach ($count as $issue) {
            $month = intval($issue->month);
            $issueCount = intval($issue->mbook_issue);
            $mbook_issue_count[$month - 1] = $issueCount;
        }
        $issue_count_by_year = array_values($mbook_issue_count);
        return $issue_count_by_year;
    }


    public function getCountByStatus()
    {
        $sql = "status=10";
        $data = $this->getAllData($sql, [], "", true);
        return isset($data) && isset($data[0]) ? $data[0]->total_count : 0;
    }
    
    public function generateMbookNumber()
    {
        $prefix = "MCMFCG";
        $departmentCode = "CFED";
        $year = date("Y");

        $from = Table::MBOOK_ISSUE; // Make sure you have this defined in your Table class
        $select = ["COUNT(*) as count"];

        // Count how many were created this year
        $sql = "YEAR(created_time) = YEAR(CURDATE())";

        $result = $this->getAll($select, $from . " t1", $sql, "", "", [], true);
        $count = (int) ($result->count ?? 0) + 1;

        // Pad NN to two digits (e.g., 01, 02...)
        $serialNumber = str_pad($count, 2, '0', STR_PAD_LEFT);

        return "$prefix/$departmentCode/$year/$serialNumber";
    }
    


    public function getAllSelect($sql = "", $data_in = [], $group_by = "", $count = false)
    {
        $from = Table::MBOOK_ISSUE;
        $select = ["ID as value,mbook_number as label"];
        $order_by = "created_time DESC";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], $count);
    }
    
   
    public function generateMbookIssuePdf($id, $data)
     {
    //  Generate HTML from your PDF layout class
    $html = MbookIssuePdf::getHtml($data);
    //  Send HTML to cURL for PDF generation
    // $this->initiate_curl($html, $id);
    // echo $html;
    }


    public function getMbookIssuePath($id)
    {
        return "mbookissue" . DS . $id . DS . "mbookissue.pdf";
    }

   private function initiate_curl($html, $id)
    {
        $data = new \stdClass();
        $data->content = base64_encode($html);
        $curl = new SmartCurl();
        $_output = $curl->post("/taskapi/html_to_pdf", $data);
        $_output_obj = json_decode($_output);
        if (isset($_output_obj->data)) {
            $path = "mbookissue" . DS . $id . DS . "mbookissue.pdf";
            SmartFileHelper::storeFile($_output_obj->data, $path);
        }
    }
    
  public function getCountStatus($status_sql)
    {
        $select = ["COUNT(*) AS total_count"];
        $from = Table::MBOOK_ISSUE;
        $sql = "status IN (" . $status_sql . ")";
        $data = $this->getAll($select, $from, $sql, "", "", [], true);
        return  isset($data->total_count) ? (int)$data->total_count : 0;
    }
    /*
  public static $MBOOK_STATUS_GROUPED = [
    'User Submission' => [10],
    'Supervisor'      => [15,14],      // approved/rejected
];
    public function getMbookStatusTracker($currentStatus, $createdBy = null)
    { 
    $tracker = [];
    $foundCurrent = false;

    foreach (self::$MBOOK_STATUS_GROUPED as $label => $codes) {
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
            'status'       => $codes[0],  // representative code
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
    public static $MBOOK_STATUS_GROUPED = [
    'User Submission' => [10],
    'Supervisor'      => [15,14], // 15 = approved, 14 = rejected
    ];

    public function getMbookStatusTracker($currentStatus, $createdBy = null)
    { 
    $tracker = [];
    $foundCurrent = false;
    $rejectedFound = false;

    foreach (self::$MBOOK_STATUS_GROUPED as $label => $codes) {
        $isCurrent = in_array($currentStatus, $codes);

        // check if rejected
        $isRejected = ($currentStatus == 14 && in_array(14, $codes));
        if ($isRejected) {
            $rejectedFound = true;
        }

        // mark completed logic
        if ($isRejected) {
            $isCompleted = false;
        } elseif ($rejectedFound) {
            $isCompleted = false; // future steps after reject
        } elseif ($isCurrent) {
            $isCompleted = true;
            $foundCurrent = true;
        } elseif (!$foundCurrent) {
            $isCompleted = true; // previous steps completed
        } else {
            $isCompleted = false; // future steps pending
        }

        // show submitter name
        $displayLabel = ($label === 'User Submission' && $createdBy) ? $createdBy : $label;

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
   //mbook issue report
   /*
 public function MbookIssueReport( $start_date, $end_date, $file_type,$work_order_value,$head_of_account) {
    $select = [
        "id",
        "mbook_number",
        "date_of_issue",
        "title",
        "description",
        "sd_mt_userdb_id",
        "app_id",
        "app_time",
        "app_remarks",
        "admin_id",
        "admin_time",
        "admin_remarks",
        "created_time",
        "status",
        "last_modified_by",
        "last_modified_remarks",
        "last_modified_time",
        "doc_loc",
        "file_type",
        "work_order_number",
        "date_of_work_order",
        "work_order_value",
        "budget_type",
        "budget_pin",
        "contact_name",
        "technical_sanction_number",
        "start_date",
        "end_date",
        "cc_number",
        "pan_number",
        "email",
        "mobile_no",
        "wages_count",
        "salary_amount",
        "head_of_account"
    ];

    $from = Table::MBOOK_ISSUE. " t1 
        INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID
        LEFT JOIN " . Table::BUDGET_TYPE . " t15 ON t15.ID = t1.head_of_account";

    // ===== Dynamic Filters =====
    $conditions = [];
    $data_in = [];

    if (!empty($start_date) && !empty($end_date)) {
        $conditions[] = "DATE(created_time) BETWEEN :start_date AND :end_date";
        $data_in["start_date"] = $start_date;
        $data_in["end_date"]   = $end_date;
    }

    if (!empty($file_type)) {
        $conditions[] = "file_type = :file_type";
        $data_in["file_type"] = $file_type;
    }

    if (!empty($work_order_value)) {
        $conditions[] = "work_order_value = :work_order_value";
        $data_in["work_order_value"] = $work_order_value;
    }
    if (!empty($head_of_account)) {
        $conditions[] = "head_of_account = :head_of_account";
        $data_in["head_of_account"] = $head_of_account;
    }

    $sql = count($conditions) ? implode(" AND ", $conditions) : "1=1";

    $order_by = "created_time DESC";

    return $this->getAll($select, $from, $sql, "", $order_by, $data_in, false, [], false);
}
*/
public function MbookIssueReport($start_date, $end_date, $file_type, $work_order_value, $head_of_account)
{
    $select = [
        "t1.id",
        "t1.mbook_number",
        "t1.date_of_issue",
        "t1.title",
        "t1.description",
        "t1.sd_mt_userdb_id",
        "t1.app_id",
        "t1.app_time",
        "t1.app_remarks",
        "t1.admin_id",
        "t1.admin_time",
        "t1.admin_remarks",
        "t1.created_time",
        "t1.status",
        "t1.last_modified_by",
        "t1.last_modified_remarks",
        "t1.last_modified_time",
        "t1.doc_loc",
        "t1.file_type",
        "t1.work_order_number",
        "t1.date_of_work_order",
        "t1.work_order_value",
        "t1.budget_type",
        "t1.budget_pin",
        "t1.contact_name",
        "t1.technical_sanction_number",
        "t1.start_date",
        "t1.end_date",
        "t1.cc_number",
        "t1.pan_number",
        "t1.email",
        "t1.mobile_no",
        "t1.wages_count",
        "t1.salary_amount",
        "t1.head_of_account",
        "t1.indentor",
        "t1.technical_sanction_amount",
        "t1.procurement_stage",
        "t1.contacter_address",


        // extra from joined tables (optional but useful)
        "t2.ename as created_by",
        "t15.budget_no as head_of_account",
        "t15.amount as head_of_account_amount",
        "t16.ename as indentor_name",
        "t23.sd_org_name as indentor_designation_name"
       
    ];

    $from = Table::MBOOK_ISSUE . " t1
            INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID
            LEFT JOIN " . Table::BUDGET_TYPE . " t15 ON t15.ID = t1.head_of_account
               LEFT JOIN " . Table::USERS . " t16 ON t16.ID = t1.indentor
              LEFT JOIN " . Table::ORGANISATION . " t23 ON t16.sd_org_id = t23.ID
        ";
            // Approved
     $select[] = "(SELECT SUM(t3.ra_amount)
              FROM " . Table::MBOOK_ENTRY . " t3
              WHERE t3.sd_mbook_issue_id = t1.ID
              AND t3.entry_status IN (30,35)) as paid_amount_approved";

    // Processing
    $select[] = "(SELECT SUM(t3.ra_amount)
              FROM " . Table::MBOOK_ENTRY . " t3
              WHERE t3.sd_mbook_issue_id = t1.ID
              AND t3.entry_status IN (10,15,20,25)) as paid_amount_processing";

   // Total Paid
    $select[] = "(
                IFNULL(
                    (SELECT SUM(t3.ra_amount)
                     FROM " . Table::MBOOK_ENTRY . " t3
                     WHERE t3.sd_mbook_issue_id = t1.ID
                     AND t3.entry_status IN (10,15,20,25,30,35)
                    ),0)
            ) as paid_amount";

    // Balance
     $select[] = "(
                t1.work_order_value -
                IFNULL(
                    (SELECT SUM(t3.ra_amount)
                     FROM " . Table::MBOOK_ENTRY . " t3
                     WHERE t3.sd_mbook_issue_id = t1.ID
                     AND t3.entry_status IN (10,15,20,25,30,35)
                    ),0)
            ) as balance_amount";
;

    // ===== Dynamic Filters =====
    $conditions = [];
    $data_in = [];

    if (!empty($start_date) && !empty($end_date)) {
        $conditions[] = "DATE(t1.created_time) BETWEEN :start_date AND :end_date";
        $data_in["start_date"] = $start_date;
        $data_in["end_date"] = $end_date;
    }

    if (!empty($file_type)) {
        $conditions[] = "t1.file_type = :file_type";
        $data_in["file_type"] = $file_type;
    }

    if (!empty($work_order_value)) {
        $conditions[] = "t1.work_order_value = :work_order_value";
        $data_in["work_order_value"] = $work_order_value;
    }

    if (!empty($head_of_account)) {
        // using joined table alias
        $conditions[] = "t15.ID = :head_of_account";
        $data_in["head_of_account"] = $head_of_account;
    }

    $sql = count($conditions) ? implode(" AND ", $conditions) : "1=1";

    $order_by = "t1.created_time DESC";

    return $this->getAll($select, $from, $sql, "", $order_by, $data_in, false, [], false);
}

//for report status tarcker purpose
public static $MBOOK_STATUS_GROUPED_NEW = [

    'User Submission' => [10],
    'Supervisor'      => [15,14], // 15 = approved, 14 = rejected
];
public function getTAStatusTrackerNew($currentStatus, $createdBy = null)
    {
    $tracker = [];
    $foundCurrent = false;
    $rejectedFound = false;

    foreach (self::$MBOOK_STATUS_GROUPED_NEW as $label => $codes) {
        $isCurrent = in_array($currentStatus, $codes);

        // check if current status is a rejection
        $isRejected = ($currentStatus == $codes[0] && ($currentStatus % 5 === 4 || $currentStatus % 10 === 9));

        if ($isRejected) {
            $rejectedFound = true;
        }

        // determine completion
        if ($isRejected) {
            $isCompleted = false;
        } elseif ($rejectedFound) {
            $isCompleted = false; // future steps locked
        } elseif ($isCurrent) {
            $isCompleted = true;
            $foundCurrent = true;
        } elseif (!$foundCurrent) {
            $isCompleted = true; // previous steps completed
        } else {
            $isCompleted = false; // future steps pending
        }

        // replace label for User Submission
        $displayLabel = ($label === 'User Submission' && $createdBy) ? $createdBy : $label;

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



}
