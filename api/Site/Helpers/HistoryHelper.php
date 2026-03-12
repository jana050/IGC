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
class HistoryHelper extends BaseHelper
{

    const schema = [
        "description" => SmartConst::SCHEMA_VARCHAR,
        "file_loc" => SmartConst::SCHEMA_VARCHAR,

    ];
    /**
     * 
     */
    const validations = [
        
        "description" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter Event Name"
            ],

        ],
        "uploaded_file" => [
            [
                "type" => SmartConst::VALID_FILE_REQUIRED,
                "msg" => "Please Upload the Picture"
            ],           
            [
                "type" => SmartConst::VALID_FILE_TYPE,
                "msg" => "Only Picture Format is allowed",
                "ext" => ["jpg","png","jpeg"]
            ]
        ],
    ];
      // file handling 
      const FILE_FOLDER = "historyimages";
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
        return $this->insertDb(self::schema, Table::HISTORY, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::HISTORY, $columns, $data, $id);
    }
    /**
     * 
     */
    public function getAllData($sql = "", $data_in = [], $select = [], $group_by = "", $count = false, $single = false)
    {
        $from = Table::HISTORY;
        $select =["*"];
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
        $from = Table::HISTORY;
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
        $from = Table::HISTORY;
        $this->deleteId($from, $id);
    }
}
