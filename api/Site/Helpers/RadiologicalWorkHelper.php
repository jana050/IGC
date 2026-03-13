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
use Site\view\RadiologicalPdf;
//
use Site\Helpers\TableHelper as Table;

/**
 * Description of Data
 * 
 *  class helps to get the data from post with specified type 
 *
 * @author kms
 */
class RadiologicalWorkHelper extends BaseHelper
{
    const schema = [
        // "permit_no" => SmartConst::SCHEMA_VARCHAR,
        "name" => SmartConst::SCHEMA_VARCHAR,
        "nature_of_work" => SmartConst::SCHEMA_VARCHAR,
        "signature_medical_officer" => SmartConst::SCHEMA_VARCHAR,
        "cover_all" => SmartConst::SCHEMA_STRING,
        "rubber_glove" => SmartConst::SCHEMA_STRING,
        "dosimeter" => SmartConst::SCHEMA_STRING,
        "complete_dress" => SmartConst::SCHEMA_STRING,
        "surgical_gloves" => SmartConst::SCHEMA_STRING,
        "respirator" => SmartConst::SCHEMA_STRING,
        "change_of_clothes" => SmartConst::SCHEMA_STRING,
        "over_shoes" => SmartConst::SCHEMA_STRING,
        "dust_gas_mask" => SmartConst::SCHEMA_STRING,
        "plastic_suit" => SmartConst::SCHEMA_STRING,
        "rwp_no" => SmartConst::SCHEMA_VARCHAR,
        "air_activity" => SmartConst::SCHEMA_STRING,
        "created_time" => SmartConst::SCHEMA_CDATETIME,
        "status" => SmartConst::SCHEMA_INTEGER,
        "last_modified_by" => SmartConst::SCHEMA_CUSER_ID,
        "last_modified_time" => SmartConst::SCHEMA_CTIME,
        "sd_mt_userdb_id" => SmartConst::SCHEMA_CUSER_ID,
        "app_id" => SmartConst::SCHEMA_INTEGER,
        "app_time" => SmartConst::SCHEMA_CDATETIME,
        "app_remarks" => SmartConst::SCHEMA_VARCHAR,
        "admin_id" => SmartConst::SCHEMA_INTEGER,
        "admin_time" => SmartConst::SCHEMA_CDATETIME,
        "admin_remarks" => SmartConst::SCHEMA_VARCHAR,
        "job_description" => SmartConst::SCHEMA_VARCHAR,
        "airline_breathing_apparatus" => SmartConst::SCHEMA_STRING,
        "rubber_station" => SmartConst::SCHEMA_STRING,
        "single_rubber_area" => SmartConst::SCHEMA_STRING,
        "double_rubber_area" => SmartConst::SCHEMA_STRING,
        "shower_after_work" => SmartConst::SCHEMA_STRING,
        "rubber_instruction" => SmartConst::SCHEMA_TEXT,
        "last_modified_remarks" => SmartConst::SCHEMA_TEXT,
        "lab_incharge_id" => SmartConst::SCHEMA_CUSER_ID,
        "lab_incharge_remarks" => SmartConst::SCHEMA_TEXT,
        "lab_incharge_time" => SmartConst::SCHEMA_CTIME,
        "hp_id" => SmartConst::SCHEMA_CUSER_ID,
        "hp_remarks" => SmartConst::SCHEMA_TEXT,
        "hp_time" => SmartConst::SCHEMA_CTIME,
    ];

    /**
     * 
     */
    const validations = [
        "name" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify Name of Person"
            ]
        ],


        "nature_of_work" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify nature_of_work"
            ]
        ],
        "cover_all" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify cover_all"
            ]
        ],
        "rubber_glove" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify rubber_glove"
            ]
        ],
        "dosimeter" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify dosimeter"
            ]
        ],
        "complete_dress" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify complete_dress"
            ]
        ],
        "surgical_gloves" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify surgical_gloves"
            ]
        ],
        "respirator" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify respirator"
            ]
        ],
        "change_of_clothes" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify change_of_clothes"
            ]
        ],
        "over_shoes" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify over_shoes"
            ]
        ],
        "dust_gas_mask" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify dust_gas_mask"
            ]
        ],
        "plastic_suit" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify plastic_suit"
            ]
        ],
        "rwp_no" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify rwp_no"
            ]
        ],
        "air_activity" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify air_activity"
            ]
        ],
        "job_description" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify job_description"
            ]
        ],
        "airline_breathing_apparatus" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify airline_breathing_apparatus"
            ]
        ],
        "rubber_station" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify rubber_station"
            ]
        ],
        "single_rubber_area" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify single_rubber_area"
            ]
        ],
        "double_rubber_area" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify double_rubber_area"
            ]
        ],
        "shower_after_work" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify shower_after_work"
            ]
        ],
        "rubber_instruction" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify rubber_instruction"
            ]
        ],

          "status" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter status"
            ]
        ],
            "app_id" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Select Approver"
            ]
        ],
          "last_modified_remarks" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter remarks"
            ]
        ],

    ];


    /**
     * 
     */
    public function insert(array $columns, array $data)
    {
        return $this->insertDb(self::schema, Table::RADIOLOGICAL_WORK, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::RADIOLOGICAL_WORK, $columns, $data, $id);
    }
    /**
     * 
     */

    public function getAllData($sql = "", $data_in = [], $group_by = "", $count = false)
    {
        $from = Table::RADIOLOGICAL_WORK . " t1 
        INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID
         LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.lab_incharge_id
         LEFT JOIN " . Table::USERS . " t12 ON t12.ID = t1.hp_id";
        $select = ["t1.*,t2.ename as created_by","t11.ename as lab_incharge_name", "t12.ename as hp_name"];
        $order_by = "t1.created_time DESC";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], $count);
    }

    /**
     * 
     */
    public function getOneData($id)
    {
        $from = Table::RADIOLOGICAL_WORK . " t1
         INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID 
         LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.lab_incharge_id
         LEFT JOIN " . Table::USERS . " t12 ON t12.ID = t1.hp_id";
        $select = ["t1.*","t2.ename as created_by","t11.ename as lab_incharge_name", "t12.ename as hp_name"];
        $sql = " t1.ID=:ID";
        $data_in = ["ID" => $id];
        $data = $this->getAll($select, $from, $sql, "", "", $data_in, true, []);
        return $data;
    }
    /**
     * 
     */
    public function deleteOneId($id)
    {
        $from = Table::RADIOLOGICAL_WORK;
        $this->deleteId($from, $id);
    }

   public function getCountStatus($status_sql)
    {
        $select = ["COUNT(*) AS total_count"];
        $from = Table::RADIOLOGICAL_WORK;
        $sql = "status IN (" . $status_sql . ")";
        $data = $this->getAll($select, $from, $sql, "", "", [], true);
        return  isset($data->total_count) ? (int)$data->total_count : 0;
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
        $select = ["COUNT(name) AS radiological, MONTH(last_modified_time) AS month"];
        $from = Table::RADIOLOGICAL_WORK;
        $sql = "YEAR(last_modified_time) =:year";
        $group_by = "MONTH(last_modified_time)";    // No GROUP BY keyword
        $order_by = "month";
        $data_in = ["year" => $year];
        $count = $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], false);
        $radiological_count = array_fill(0, 12, 0);
        foreach ($count as $rad) {
            $month = intval($rad->month);
            $radCount = intval($rad->radiological);
            $radiological_count[$month - 1] = $radCount;
        }
        $rad_count_by_year = array_values($radiological_count);
        return $rad_count_by_year;
    }
      
    //generateRwpNo
    public function generateRwpNo()
    {
        $from = Table::RADIOLOGICAL_WORK;
        $select = ["COUNT(*) as count"];
        $sql = "DATE(created_time) = CURDATE()";
        $result = $this->getAll($select, $from . " t1", $sql, "", "", [], true);
        $count = (int) ($result->count ?? 0) + 1; // Changed from array to object access
        $number = str_pad($count, 3, '0', STR_PAD_LEFT);
        $date = date("Ymd");
        return "RWP-$date-$number";
    }

     public function generateRadiologicalPdf($id, $data)
     {
    //  Generate HTML from your PDF layout class
    $html = RadiologicalPdf::getHtml($data);
    //  Send HTML to cURL for PDF generation
    // $this->initiate_curl($html, $id);
    // echo $html;
    }

}
