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
class HomeImagesHelper extends BaseHelper
{

    const schema = [
        "home_image" => SmartConst::SCHEMA_VARCHAR,
        "title" => SmartConst::SCHEMA_VARCHAR,
        "description" => SmartConst::SCHEMA_TEXT,
    ];
    /**
     * 
     */
    const validations = [
        
        "home_image" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Upload Home Image"
            ],

        ],
        "title" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Upload title"
            ],

        ],
        // "description" => [
        //     [
        //         "type" => SmartConst::VALID_REQUIRED,
        //         "msg" => "Please Upload description"
        //     ],

        // ],
        "uploaded_file" => [
            [
                "type" => SmartConst::VALID_FILE_REQUIRED,
                "msg" => "Please Upload the Document"
            ],           
            [
                "type" => SmartConst::VALID_FILE_TYPE,
                "msg" => "Only pdf is allowed",
                "ext" => ['png', 'jpeg', 'jpg', 'mp4', 'avi', 'mov']
            ]
        ],
    ];
      // file handling 
      const FILE_FOLDER = "homeimages";
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
        return $this->insertDb(self::schema, Table::HOME_IMAGES, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::HOME_IMAGES, $columns, $data, $id);
    }
    /**
     * 
     */
    public function getAllData($sql = "", $data_in = [], $select = [], $group_by = "", $count = false, $single = false)
    {
        $from = Table::HOME_IMAGES;
        $select = !empty($select) ? $select : ["*"];
        $sql = "home_image IS NOT NULL";
        // $order_by="last_modified_time DESC";
        return $this->getAll($select, $from, $sql, $group_by, "", $data_in, $single, [], $count);
    }
    /**
     * 
     */


    /**
     * 
     */
    public function getOneData($id)
    {
        $from = Table::HOME_IMAGES;
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
        $from = Table::HOME_IMAGES;
        $this->deleteId($from, $id);
    }
}
