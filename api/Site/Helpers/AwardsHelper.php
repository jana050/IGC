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
class AwardsHelper extends BaseHelper
{

    const schema = [
        "title" => SmartConst::SCHEMA_VARCHAR,
        "img_loc" => SmartConst::SCHEMA_VARCHAR,
        "award_date" => SmartConst::SCHEMA_DATE,
        "created_by" => SmartConst::SCHEMA_CUSER_ID,
        "created_time" => SmartConst::SCHEMA_CDATETIME,
    ];
    /**
     * 
     */
    const validations = [
      
        "title" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Tile"
            ],
            [
                "type" => SmartConst::VALID_MAX_LENGTH,
                "max"=>255,
                "msg"=>"Title Max character 255"
            ]
        ],
        "uploaded_file" => [
            [
                "type" => SmartConst::VALID_FILE_REQUIRED,
                "msg" => "Please Upload the Award Image"
            ],           
            [
                "type" => SmartConst::VALID_FILE_TYPE,
                "msg" => "Only Picture Format is allowed",
                "ext" => ["jpg","png","jpeg"]
            ]
        ],
         "award_date" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Specify the Award Date"
            ]
        
        ]
    ];
    
     // file handling 
     const FILE_FOLDER = "awardimages";
     const FILE_NAME = "file";

     public function getFolder($id)
     {
         return self::FILE_FOLDER . DS . $id . DS;
     }
     public function getFullFile($id)
     {
         return self::FILE_FOLDER . DS . $id . DS . self::FILE_NAME;
     }
    /**
     * 
     */
    public function insert(array $columns, array $data)
    {
        return $this->insertDb(self::schema, Table::AWARDS, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::AWARDS, $columns, $data, $id);
    }
    /**
     * 
     */
    public function getAllData($sql = "", $data_in = [], $select_in=[],$group_by = "", $order_by = "", $count = false)
    {
        $from = Table::AWARDS;
        $select = ["*"];
        if(!empty($select_in)){
            $select = $select_in;
        }
        $order_by="created_time DESC";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], $count);
    }
    /**
     * 
     */
    public function getOneData($id)
    {
        $from = Table::AWARDS;
        $select = ["*"];
        $sql = "ID=:ID";
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
        $from = Table::AWARDS;
        $this->deleteId($from,$id);
    }
   
    
}
