<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Site\Helpers;

use Core\BaseHelper;
use Core\Helpers\SmartConst;

//
use Site\Helpers\TableHelper as Table;

/**
 * Description of Data
 * 
 *  class helps to get the data from post with specified type 
 *
 * @author kms
 */
class ActivityHelper extends BaseHelper
{

    const schema = [
        "activity_title" => SmartConst::SCHEMA_VARCHAR,
        "activity_desc" => SmartConst::SCHEMA_VARCHAR,
        "activity_image" => SmartConst::SCHEMA_TEXT,
        "name" => SmartConst::SCHEMA_VARCHAR,
        "designation" => SmartConst::SCHEMA_VARCHAR,
        "order_no" => SmartConst::SCHEMA_INTEGER,
        "created_by" => SmartConst::SCHEMA_CUSER_ID,
        "created_time" => SmartConst::SCHEMA_CDATETIME,
        "last_modified_time" => SmartConst::SCHEMA_CDATETIME
    ];
    /**
     * 
     */
    const validations = [
        "activity_title" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter Activity Title"
            ],
            [
                "type" => SmartConst::VALID_MAX_LENGTH,
                "max"=>150,
                "msg"=>"Activity Title Max character 150"
            ]
    
            ],
        "activity_desc" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Activity description"
            ],
        ],
        "activity_image" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please upload Activity Image"
            ]
    
            ],
        "name" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter Name"
            ]
            ],
        "designation" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter Designation"
            ]
            ],
        "order_no" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter Order No"
            ]
            ]
    ];
    /**
     * 
     */
    public function insert(array $columns, array $data)
    {
        return $this->insertDb(self::schema, Table::ACTIVITY, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::ACTIVITY, $columns, $data, $id);
    }
    /**
     * 
     */
    /*
    public function getAllData($sql = "", $data_in = [],$select=[],$group_by = "", $count = false,$single=false)
    {
        $from = Table::ACTIVITY;
        $select = !empty($select) ? $select : ["ID,activity_image,activity_title,activity_desc,created_by,created_time,last_modified_time,name,designation,order_no"];
       // $order_by="last_modified_time DESC";
        return $this->getAll($select, $from, $sql, $group_by, "order_no ASC", $data_in, $single, [], $count);
    }
    */
    public function getAllData($sql = "", $data_in = [], $select = [], $group_by = "", $count = false, $single = false)
    {
    $from = Table::ACTIVITY;
    $select = !empty($select) ? $select : [
        "ID,activity_image,activity_title,activity_desc,created_by,created_time,last_modified_time,name,designation,order_no"
    ];

    // Custom order: order_no=1 first, then rest ascending
    $order_by = "CASE WHEN order_no = 1 THEN 0 ELSE 1 END, order_no ASC";

    return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, $single, [], $count);
    }

    /**
     * 
     */


    /**
     * 
     */
    public function getOneData($id)
    {
        $from = Table::ACTIVITY;
        $select = ["*"];
        $sql = "ID=:ID";
        $data_in = ["ID" => $id];
        $group_by = "";
        $order_by = "";
        $data = $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, true, []);
        return $data;
    }
     /**
     * 
     */
    public function deleteOneId($id)
    {
        $from = Table::ACTIVITY;
        $this->deleteId($from,$id);
    }
  
}
