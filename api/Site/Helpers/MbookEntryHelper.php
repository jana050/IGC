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
class MbookEntryHelper extends BaseHelper
{

    const schema = [
        "sd_mbook_issue_id" => SmartConst::SCHEMA_INTEGER,
        "sd_mt_userdb_id" => SmartConst::SCHEMA_CUSER_ID,
        "app_id" => SmartConst::SCHEMA_INTEGER,
        "app_time" => SmartConst::SCHEMA_CDATETIME,
        "app_remarks" => SmartConst::SCHEMA_VARCHAR,
        "admin_id" => SmartConst::SCHEMA_INTEGER,
        "admin_time" => SmartConst::SCHEMA_CDATETIME,
        "admin_remarks" => SmartConst::SCHEMA_VARCHAR,
        "created_time" => SmartConst::SCHEMA_CDATETIME,
        "entry_status" => SmartConst::SCHEMA_INTEGER,
        "last_modified_by" => SmartConst::SCHEMA_CUSER_ID,
        "last_modified_remarks" => SmartConst::SCHEMA_TEXT,
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
        "ra_number" => SmartConst::SCHEMA_VARCHAR,
        "ra_amount" => SmartConst::SCHEMA_INTEGER,
        "title" => SmartConst::SCHEMA_TEXT,
        "date_of_issue" => SmartConst::SCHEMA_DATE,
        "technical_sanction_amount" => SmartConst::SCHEMA_VARCHAR
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
        "entry_status" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter entry_status"
            ]
        ],

        "last_modified_remarks" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter remarks"
            ]
        ],
        "sd_mbook_issue_id" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter sd_mbook_issue_id"
            ]
        ],
        "ra_number" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter ra_number"
            ]
        ],
        "ra_amount" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter ra_amount"
            ]
        ],

        "date_of_issue" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter date_of_issue"
            ]
        ],
          "technical_sanction_amount" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter technical_sanction_amount"
            ]
        ],






    ];
    /**
     * 
     */
    public function insert(array $columns, array $data)
    {
        return $this->insertDb(self::schema, Table::MBOOK_ENTRY, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::MBOOK_ENTRY, $columns, $data, $id);
    }
    /**
     * 
     */
    public function getAllData($sql = "", $data_in = [], $group_by = "", $count = false)
    {
        $from = Table::MBOOK_ENTRY . " t1 
        INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID
        LEFT JOIN " . Table::MBOOK_ISSUE . " t3 ON t1.sd_mbook_issue_id = t3.ID  
        LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.hos_id
         LEFT JOIN " . Table::USERS . " t12 ON t12.ID = t1.hod_id
          LEFT JOIN " . Table::USERS . " t13 ON t13.ID = t1.ad_id
         LEFT JOIN " . Table::USERS . " t14 ON t14.ID = t1.gd_id
        ";
        $select = [
            "t1.*,t2.ename as created_by",
            "t3.mbook_number",
            "t3.file_type",
            "t3.work_order_value",
            "t11.ename as hos_name",
            "t12.ename as hod_name",
            "t13.ename as ad_name",
            "t14.ename as gd_name"
        ];
        $order_by = "t1.created_time DESC";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], $count);
    }
    /**
     * 
     */

    public function getOneData($id)
    {
        $from = Table::MBOOK_ENTRY . " t1 
                INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID
                LEFT JOIN " . Table::MBOOK_ISSUE . " t3 ON t1.sd_mbook_issue_id = t3.ID 
                LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.hos_id
         LEFT JOIN " . Table::USERS . " t12 ON t12.ID = t1.hod_id
          LEFT JOIN " . Table::USERS . " t13 ON t13.ID = t1.ad_id
         LEFT JOIN " . Table::USERS . " t14 ON t14.ID = t1.gd_id";
        $select = [
            "t1.*, t2.ename as created_by",
            "t3.mbook_number",
            "t3.file_type",
            "t3.work_order_value",
            "t11.ename as hos_name",
            "t12.ename as hod_name",
            "t13.ename as ad_name",
            "t14.ename as gd_name"
        ];
        $sql = "t1.ID = :ID";
        $data_in = ["ID" => $id];
        $group_by = "";
        $order_by = "";
        // Fetch the data using getAll
        $data = $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, true, []);

        if ($data && isset($data->ID)) {

            $sd_mbook_issue_id = $data->sd_mbook_issue_id;
            $data->sd_mbook_issue_id = [
                "value" => $sd_mbook_issue_id,
                "label" => $data->mbook_number
            ];
        }

        return $data;
    }
    /**
     * 
     */
    public function getElecWithUserID($id)
    {
        $from = Table::MBOOK_ENTRY;
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
        $from = Table::MBOOK_ENTRY;
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
        $select = ["COUNT(title) AS Mbook_entry, MONTH(last_modified_time) AS month"];
        $from = Table::MBOOK_ENTRY;
        $sql = "YEAR(last_modified_time) =:year";
        $group_by = "MONTH(last_modified_time)";    // No GROUP BY keyword
        $order_by = "month";
        $data_in = ["year" => $year];
        $count = $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], false);
        $electrical_count = array_fill(0, 12, 0);
        foreach ($count as $elec) {
            $month = intval($elec->month);
            $eleCount = intval($elec->Mbook_entry);
            $electrical_count[$month - 1] = $eleCount;
        }
        $elec_count_by_year = array_values($electrical_count);
        return $elec_count_by_year;
    }


    public function getCountByStatus()
    {
        $sql = "entry_status=10";
        $data = $this->getAllData($sql, [], "", true);
        return isset($data) && isset($data[0]) ? $data[0]->total_count : 0;
    }
    /*
public function MbookIssueReport($start_date, $end_date, $file_type, $budget_type, $technical_sanction_number, $contact_name)
{
    $select = [
        "t1.sd_mbook_issue_id",
        "t1.sd_mt_userdb_id",
        "t1.app_id",
        "t1.app_time",
        "t1.app_remarks",
        "t1.admin_id",
        "t1.admin_time",
        "t1.admin_remarks",
        "t1.created_time",
        "t1.entry_status",
        "t1.last_modified_by",
        "t1.last_modified_remarks",
        "t1.last_modified_time",
        "t1.hos_id",
        "t1.hos_remarks",
        "t1.hos_time",
        "t1.hod_id",
        "t1.hod_remarks",
        "t1.hod_time",
        "t1.ad_id",
        "t1.ad_remarks",
        "t1.ad_time",
        "t1.gd_id",
        "t1.gd_remarks",
        "t1.gd_time",
        "t1.ra_number",
        "t1.ra_amount",
        "t1.title",

        // From sd_mbook_issue table
        "t2.mbook_number",
        "t2.date_of_issue",
        "t2.designation",
        "t2.approved_by_head_cfed",
        "t2.description",
        "t2.doc_loc",
        "t2.file_type",
        "t2.work_order_number",
        "t2.date_of_work_order",
        "t2.work_order_value",
        "t2.budget_type",
        "t2.budget_pin",
        "t2.contact_name",
        "t2.technical_sanction_number"
    ];

    $from = Table::MBOOK_ENTRY . " t1
             LEFT JOIN " . Table::MBOOK_ISSUE . " t2 ON t1.sd_mbook_issue_id = t2.id";

    $sql = "DATE(t2.date_of_issue) BETWEEN :start_date AND :end_date
            AND t2.file_type = :file_type
            AND t2.budget_type = :budget_type
            AND t2.technical_sanction_number = :technical_sanction_number
            AND t2.contact_name = :contact_name";

    $order_by = "t2.date_of_issue DESC";

    $data_in = [
        "start_date" => $start_date,
        "end_date" => $end_date,
        "file_type" => $file_type,
        "budget_type" => $budget_type,
        "technical_sanction_number" => $technical_sanction_number,
        "contact_name" => $contact_name
    ];

    $single = false;
    $limit = [];
    $count = false;

    $data = $this->getAll($select, $from, $sql, "", $order_by, $data_in, $single, $limit, $count);
    return $data;
}
    */
    public function MbookEntryReport($start_date, $end_date, $file_type, $budget_type, $technical_sanction_number, $contact_name)
    {
        $select = [
            "t1.sd_mbook_issue_id",
            "t1.sd_mt_userdb_id",
            "t1.app_id",
            "t1.app_time",
            "t1.app_remarks",
            "t1.admin_id",
            "t1.admin_time",
            "t1.admin_remarks",
            "t1.created_time",
            "t1.entry_status",
            "t1.last_modified_by",
            "t1.last_modified_remarks",
            "t1.last_modified_time",
            "t1.hos_id",
            "t1.hos_remarks",
            "t1.hos_time",
            "t1.hod_id",
            "t1.hod_remarks",
            "t1.hod_time",
            "t1.ad_id",
            "t1.ad_remarks",
            "t1.ad_time",
            "t1.gd_id",
            "t1.gd_remarks",
            "t1.gd_time",
            "t1.ra_number",
            "t1.ra_amount",
            "t1.title",
            "t2.mbook_number",
            "t2.date_of_issue",
            "t2.description",
            "t2.doc_loc",
            "t2.file_type",
            "t2.work_order_number",
            "t2.date_of_work_order",
            "t2.work_order_value",
            "t2.budget_type",
            "t2.budget_pin",
            "t2.contact_name",
            "t2.technical_sanction_number",
            "t1.technical_sanction_amount"
        ];

        $from = Table::MBOOK_ENTRY . " t1
             LEFT JOIN " . Table::MBOOK_ISSUE . " t2 ON t1.sd_mbook_issue_id = t2.id";

        // Build conditions dynamically
        $conditions = [];
        $data_in = [];

        if (!empty($start_date) && !empty($end_date)) {
            $conditions[] = "DATE(t2.date_of_issue) BETWEEN :start_date AND :end_date";
            $data_in["start_date"] = $start_date;
            $data_in["end_date"] = $end_date;
        }
        if (!empty($file_type)) {
            $conditions[] = "t2.file_type = :file_type";
            $data_in["file_type"] = $file_type;
        }
        if (!empty($budget_type)) {
            $conditions[] = "t2.budget_type = :budget_type";
            $data_in["budget_type"] = $budget_type;
        }
        if (!empty($technical_sanction_number)) {
            $conditions[] = "t2.technical_sanction_number = :technical_sanction_number";
            $data_in["technical_sanction_number"] = $technical_sanction_number;
        }
        if (!empty($contact_name)) {
            $conditions[] = "t2.contact_name = :contact_name";
            $data_in["contact_name"] = $contact_name;
        }

        // If no conditions, just set 1=1 to get all
        $sql = count($conditions) ? implode(" AND ", $conditions) : "1=1";

        $order_by = "t2.date_of_issue DESC";

        $single = false;
        $limit = [];
        $count = false;

        $data = $this->getAll($select, $from, $sql, "", $order_by, $data_in, $single, $limit, $count);
        return $data;
    }
    public function getFirstNonWaitingEntryId($mbook_issue_id)
    {
        $from = Table::MBOOK_ENTRY . " t1
    LEFT JOIN " . Table::MBOOK_ISSUE . " t2 ON t1.sd_mbook_issue_id = t2.ID";
        $select = ["t1.ID",];
        $sql = "t1.sd_mbook_issue_id = :id AND t1.entry_status IN (30,35) and t2.file_type='NIQ'";
        $data_in = ["id" => $mbook_issue_id];
        $group_by = "";
        $order_by = "";

        // true => fetch only one row
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, []);
    }
    public function checkWaitingEntry($mbook_issue_id)
    {
        $from = Table::MBOOK_ENTRY;
        $select = ["ID"];
        $sql = "sd_mbook_issue_id=:id AND entry_status IN (10,15,20,25)";
        $data_in = ["id" => $mbook_issue_id];        // true => fetch one row only
        return $this->getAll($select, $from, $sql, "", "", $data_in, true, []);
    }


    public function getCountStatus($status_sql)
    {
        $select = ["COUNT(*) AS total_count"];
        $from = Table::MBOOK_ENTRY;
        $sql = "entry_status IN (" . $status_sql . ")";
        $data = $this->getAll($select, $from, $sql, "", "", [], true);
        return  isset($data->total_count) ? (int)$data->total_count : 0;
    }
   /*
   public static $TA_STATUS_GROUPED = [
    'User Submission' => [10],             // initial submit
    'HOS Review'      => [15,14],         // HOS pending/approved
    'HOD Review'      => [20,19],         // HOD pending/approved
    'AD Review'       => [25, 24],         // AD pending/approved
    'GD Review'       => [30,29],         // GD pending/approved
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
    // getone status tarcker purpose
    public static $TA_STATUS_GROUPED = [
    'User Submission' => [10],             // initial submit
    'HOS Review'      => [15,14],         // 15=approved, 14=rejected
    'HOD Review'      => [20,19],         // 20=approved, 19=rejected
    'AD Review'       => [25, 24],        // 25=approved, 24=rejected
    'GD Review'       => [30,29],         // 30=approved, 29=rejected
    ];

    public function getTAStatusTracker($currentStatus, $createdBy = null)
    {
    $tracker = [];
    $foundCurrent = false;
    $rejectedFound = false;

    foreach (self::$TA_STATUS_GROUPED as $label => $codes) {
        $isCurrent = in_array($currentStatus, $codes);

        // check if current status is a rejection
        $isRejected = in_array($currentStatus, array_filter($codes, fn($c) => $c % 5 === 4 || $c % 10 === 9));
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
//for report status tarcker purpose
public static $MBOOK_STATUS_GROUPED_NEW = [

    'User Submission' => [10],

    'HOS Approved'    => [15],
    'HOS Rejected'    => [14],

    'HOD Approved'    => [20],
    'HOD Rejected'    => [19],

    'AD Approved'     => [25],
    'AD Rejected'     => [24],

    'GD Approved'     => [30],
    'GD Rejected'     => [29],
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
