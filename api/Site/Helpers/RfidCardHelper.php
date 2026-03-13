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
use Site\view\RfidCardPdf;

//
use Site\Helpers\TableHelper as Table;

/**
 * Description of Data
 * 
 *  class helps to get the data from post with specified type 
 *
 * @author kms
 */
class RfidCardHelper extends BaseHelper
{

    const schema = [
        "from_date" => SmartConst::SCHEMA_DATE,
        "to_date" => SmartConst::SCHEMA_DATE,
        "nature_of_visitor" => SmartConst::SCHEMA_VARCHAR,
        "igcar_entry_permit_no" => SmartConst::SCHEMA_VARCHAR,
        "name" => SmartConst::SCHEMA_VARCHAR,
        "gender" => SmartConst::SCHEMA_VARCHAR,
        "age" => SmartConst::SCHEMA_INTEGER,
        "area_of_visit" => SmartConst::SCHEMA_VARCHAR,
        "intercom_no" => SmartConst::SCHEMA_INTEGER,
        "institute_address" => SmartConst::SCHEMA_VARCHAR,
        "mobile_no" => SmartConst::SCHEMA_VARCHAR,
        "card_no" => SmartConst::SCHEMA_VARCHAR,
        "card_status" => SmartConst::SCHEMA_INTEGER,
        "card_date" => SmartConst::SCHEMA_DATE,
        "rfid_req_number" => SmartConst::SCHEMA_VARCHAR,
        "igcar_entry_date_of_validity" => SmartConst::SCHEMA_DATE,
        "sd_mt_userdb_id" => SmartConst::SCHEMA_CUSER_ID,
        "admin_id" => SmartConst::SCHEMA_INTEGER,
        "status" => SmartConst::SCHEMA_INTEGER,
        "created_time" => SmartConst::SCHEMA_CDATE,
        "last_modified_by" => SmartConst::SCHEMA_CUSER_ID,
        "last_modified_remarks" => SmartConst::SCHEMA_TEXT,
        "admin_time" => SmartConst::SCHEMA_TEXT,
        "admin_remarks" => SmartConst::SCHEMA_TEXT,
        "last_modified_time" => SmartConst::SCHEMA_CTIME,
        "hos_id" => SmartConst::SCHEMA_CUSER_ID,
        "hos_remarks" => SmartConst::SCHEMA_TEXT,
        "hos_time" => SmartConst::SCHEMA_CTIME,
        "hod_id" => SmartConst::SCHEMA_CUSER_ID,
        "hod_remarks" => SmartConst::SCHEMA_TEXT,
        "hod_time" => SmartConst::SCHEMA_CTIME,
        "supervisor" => SmartConst::SCHEMA_CUSER_ID,
        "supervisor_remarks" => SmartConst::SCHEMA_TEXT,
        "supervisor_time" => SmartConst::SCHEMA_CTIME,
        "approver" => SmartConst::SCHEMA_VARCHAR

    ];
    /**
     * 
     */
    const validations = [
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
        "from_date" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter from_date"
            ]
        ],
        "to_date" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter to_date"
            ]
        ],
        "nature_of_visitor" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter nature_of_visitor"
            ]
        ],
        "igcar_entry_permit_no" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter igcar_entry_permit_no"
            ]
        ],
        "name" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter name"
            ]
        ],
        "area_of_visit" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter 	area_of_visit"
            ]
        ],
        "intercom_no" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter 	intercom_no"
            ]
        ],
        "mobile_no" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter 	mobile_no"
            ]
        ],
        "card_no" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter 	card_no"
            ]
        ],
        "card_date" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter 	card_date"
            ]
        ],

        "rfid_req_number" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter 	rfidcard_number"
            ]
        ],

        "gender" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter 	gender"
            ]
        ],

        "age" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter 	age"
            ]
        ],
        "igcar_entry_date_of_validity" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter 	igcar_entry_date_of_validity"
            ]
        ],
        "hos_id" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter 	hos_id"
            ]
        ],
        "institute_address" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter 	institute_address"
            ]
        ],
        "supervisor_remarks" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter supervisor_remarks"
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
        return $this->insertDb(self::schema, Table::RFID_CARD, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::RFID_CARD, $columns, $data, $id);
    }
    /**
     * 
     */
    public function getAllData($sql = "", $data_in = [], $group_by = "", $count = false)
    {
        $from = Table::RFID_CARD . " t1 
        INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID 
         LEFT JOIN " . Table::USERS . " t10 ON t10.ID = t1.supervisor
         LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.hos_id
         LEFT JOIN " . Table::USERS . " t12 ON t12.ID = t1.hod_id

        ";
        $select = [
            "t1.*,t2.ename as created_by",
            "t11.ename as hos_name",
            "t12.ename as hod_name",
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
        $from = Table::RFID_CARD . " t1 
        INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID 
        LEFT JOIN " . Table::USERS . " t10 ON t10.ID = t1.supervisor
        LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.hos_id
         LEFT JOIN " . Table::USERS . " t12 ON t12.ID = t1.hod_id
        ";
        $select = [
            "t1.*,t2.ename as created_by",
            "t11.ename as hos_name",
            "t10.ename as supervisor_name",
            "t12.ename as hod_name"
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
        $from = Table::RFID_CARD;
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
        $from = Table::RFID_CARD;
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
        $from = Table::RFID_CARD;
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
    //generateRfidNumber
    public function generateRfidNumber()
    {
        $from = Table::RFID_CARD;
        $select = ["COUNT(*) as count"];
        $sql = "DATE(created_time) = CURDATE()";
        $result = $this->getAll($select, $from . " t1", $sql, "", "", [], true);
        $count = (int) ($result->count ?? 0) + 1; // Changed from array to object access
        $number = str_pad($count, 3, '0', STR_PAD_LEFT);
        $date = date("Ymd");
        return "RF-$date-$number";
    }

    public function getCountStatus($status_sql)
    {
        $select = ["COUNT(*) AS total_count"];
        $from = Table::RFID_CARD;
        $sql = "status IN (" . $status_sql . ")";
        $data = $this->getAll($select, $from, $sql, "", "", [], true);
        return isset($data->total_count) ? (int) $data->total_count : 0;
    }
    public function RfidCardExpiryReport($to_date)
    {
        $select = [
            "t1.card_no",
            "t1.from_date",
            "t1.to_date",
            "t1.nature_of_visitor",
            "t1.igcar_entry_permit_no",
            "t1.name",
            "t1.gender",
            "t1.age",
            "t1.area_of_visit",
            "t1.intercom_no",
            "t1.mobile_no",
            "t1.rfid_req_number",
            "t1.institute_address",
            "t1.hos_id",
            "t1.igcar_entry_date_of_validity",
            "t1.card_status",
            "t1.supervisor",
            "t1.supervisor_remarks",
            "t1.supervisor_time",
            "t1.status",
            "t1.created_time",
            "t1.last_modified_time",
            "t1.last_modified_remarks",
            "t2.ename AS created_by",
            "t10.ename AS supervisor_name",
            "t12.ename AS hod_name"
        ];

        $from = Table::RFID_CARD . " t1
        INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID
        LEFT JOIN " . Table::USERS . " t10 ON t10.ID = t1.supervisor
        LEFT JOIN " . Table::USERS . " t12 ON t12.ID = t1.hod_id";

        $sql = "DATE(t1.to_date) <= :to_date";
        $order_by = "t1.date_of_validity DESC";

        $data_in = [
            "to_date" => $to_date
        ];

        $single = false;
        $limit = [];
        $count = false;

        $data = $this->getAll($select, $from, $sql, "", $order_by, $data_in, $single, $limit, $count);
        return $data;
    }
    public function generateRfidCardPdf($id, $data)
    {
    //  Generate HTML from your PDF layout class
    $html = RfidCardPdf::getHtml($data);
    //  Send HTML to cURL for PDF generation
    // $this->initiate_curl($html, $id);
    // echo $html;
    }
    /*
   public static $STATUS_GROUPED = [
    'User Submission' => [10],
    'HOS' => [15, 14],
    'HOD' => [20, 19],
    'Card Issued' => [25],
    'Card Returned' => [30]
    ];

public function getStatusTracker($currentStatus, $createdBy = null)
{
    $tracker = [];
    $foundCurrent = false;

    foreach (self::$STATUS_GROUPED as $label => $codes) {
        $isCurrent = in_array($currentStatus, $codes);

        // Mark completed if already passed OR current
        if (!$foundCurrent) {
            $isCompleted = true;
        } else {
            $isCompleted = false;
        }

        // If current found, lock further steps as not completed
        if ($isCurrent) {
            $foundCurrent = true;
        }

        // Custom label for user submission
        if ($label === 'User Submission' && $createdBy) {
            $displayLabel = $createdBy;
        } else {
            $displayLabel = $label;
        }

        $tracker[] = [
            'status' => $codes[0],
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
   // getone status tarcker purpose
    public static $STATUS_GROUPED = [
    'User Submission' => [10],
    'HOS' => [15, 14],          // 14 = rejected
    'HOD' => [20, 19],          // 19 = rejected
    'Card Issued' => [25, 24],  // 24 = rejected
    'Card Returned' => [30, 29] // 29 = rejected
    ];

    public function getStatusTracker($currentStatus, $createdBy = null)
    {
    $tracker = [];
    $foundCurrent = false;
    $rejectedFound = false;

    foreach (self::$STATUS_GROUPED as $label => $codes) {
        $isCurrent = in_array($currentStatus, $codes);

        // Check if rejected
        $isRejected = in_array($currentStatus, array_filter($codes, fn($c) => $c % 5 === 4)); // all reject codes end with 4 or 9
        if ($isRejected) {
            $rejectedFound = true;
        }

        // Mark completed logic
        if ($isRejected) {
            $isCompleted = false;
        } elseif ($rejectedFound) {
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



/*   
   public function getExpiredUnreturnedCards()
   {
    $select = ["COUNT(*) AS expired_unreturned_count"];
    $from = Table::RFID_CARD . " t1";

    // expired = to_date < today
    // unreturned = status != 30 (since your $STATUS_GROUPED shows 30 = "Card Returned")
    // $sql = "DATE(t1.to_date) < CURDATE() AND t1.status != 25";
    $sql = "t1.to_date < :today AND t1.status = 25";

    $data = $this->getAll($select, $from, $sql, "", "", [], true);

    return isset($data->expired_unreturned_count) ? (int) $data->expired_unreturned_count : 0;
   }
*/
public function getExpiredUnreturnedCards()
{
    $select = ["COUNT(*) AS expired_unreturned_count"];
    $from = Table::RFID_CARD . " t1";

    $today = date("Y-m-d"); // today's date

    $sql = "t1.to_date < :today AND t1.status = 25";
    $data_in = ["today" => $today];

    $data = $this->getAll($select, $from, $sql, "", "", $data_in, true);

    return isset($data->expired_unreturned_count) ? (int) $data->expired_unreturned_count : 0;
}
public function RfidCardReport($from_date, $to_date)
{
    $select = [
        "t1.from_date",
        "t1.to_date",
        "t1.nature_of_visitor",
        "t1.igcar_entry_permit_no",
        "t1.name",
        "t1.gender",
        "t1.age",
        "t1.area_of_visit",
        "t1.intercom_no",
        "t1.institute_address",
        "t1.mobile_no",
        "t1.card_no",
        "t1.card_date",
        "t1.rfid_req_number",
        "t1.igcar_entry_date_of_validity",
        "t1.sd_mt_userdb_id",
        "t1.status",
        "t1.created_time",
        "t1.last_modified_by",
        "t1.last_modified_time",
        "t1.hos_id",
        "t1.hos_remarks",
        "t1.hos_time",
        "t1.hod_id",
        "t1.hod_remarks",
        "t1.hod_time",
        "t1.supervisor",
        "t1.supervisor_remarks",
        "t1.supervisor_time",
        "t2.ename"
    ];

    $from = Table::RFID_CARD . " t1
        LEFT JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID";

    // Date filter based on created_time like your TemporaryAdvanceReport
    $sql = "DATE(t1.created_time) BETWEEN :from_date AND :to_date";
    $order_by = "t1.created_time DESC";

    $data_in = [
        "from_date" => $from_date,
        "to_date" => $to_date
    ];

    $single = false;
    $limit = [];
    $count = false;

    $data = $this->getAll($select, $from, $sql, "", $order_by, $data_in, $single, $limit, $count);
    return $data;
}
//report status tracker purpose
public static $STATUS_GROUPED_NEW = [
    'Submitted (Waiting for HOS)' => [10],

    'HOS Processing' => [15],
    'HOS Rejected'   => [14],

    'HOD Processing' => [20],
    'HOD Rejected'   => [19],

    'Card Issued' => [25],
    'Card Issued Rejected' => [24],

    'Card Returned' => [30],
    'Card Return Rejected' => [29]
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
