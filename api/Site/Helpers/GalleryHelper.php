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
class GalleryHelper extends BaseHelper
{

    const schema = [
        "event_name" => SmartConst::SCHEMA_VARCHAR,
        "description"=> SmartConst::SCHEMA_VARCHAR,
        "img_loc" => SmartConst::SCHEMA_VARCHAR,
        "created_by" => SmartConst::SCHEMA_CUSER_ID,
        "created_time" => SmartConst::SCHEMA_CDATETIME,
    ];
    /**
     * 
     */
    const validations = [
      
        "event_name" => [
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
        "description" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Description"
            ]          
        ],
        "uploaded_file" => [
            [
                "type" => SmartConst::VALID_FILE_REQUIRED,
                "msg" => "Please Upload the Event Image"
            ],           
            [
                "type" => SmartConst::VALID_FILE_TYPE,
                "msg" => "Only Picture Format is allowed",
                "ext" => ["jpg","png","jpeg"]
            ]
        ]
    ];
    
     // file handling 
     const FILE_FOLDER = "eventimages";
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
        return $this->insertDb(self::schema, Table::GALLERY, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::GALLERY, $columns, $data, $id);
    }
    /**
     * 
     */
    public function getAllData($sql = "", $data_in = [], $select_in=[],$group_by = "", $order_by = "", $count = false)
    {
        $from = Table::GALLERY;
        $select = ["*"];
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], $count);
    }
    /**
     * 
     */
    public function getOneData($id)
    {
        $from = Table::GALLERY;
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
        $from = Table::GALLERY;
        $this->deleteId($from,$id);
    }
   
    
}
