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
class DocTypeHelper extends BaseHelper
{

    const schema = [
        "doc_type" => SmartConst::SCHEMA_VARCHAR,
        "doc_category" => SmartConst::SCHEMA_INTEGER,
        "show_chart" => SmartConst::SCHEMA_INTEGER,
        "created_time" => SmartConst::SCHEMA_CDATETIME        
    ];
    /**
     * 
     */
    const validations = [
        "doc_type" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Specify Document Type "
            ]
        ],
        "doc_category" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Choose Document Category"
            ]
        ],
        "show_chart" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Choose whether it has to be shown in Dashboard"
            ]
        ]
        
    ];
    /**
     * 
     */
    public function insert(array $columns, array $data)
    {
        return $this->insertDb(self::schema, Table::DOCCATEGORY, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::DOCCATEGORY, $columns, $data, $id);
    }
    /**
     * 
     */
    public function getAllData($sql = "", $data_in = [], $group_by = "", $count = false)
    {
        $from = Table::DOCCATEGORY;
        $select = ["*"];
        $order_by="";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], $count);
    }
    /**
     * 
     */
    public function getOneData($id)
    {
        $from = Table::DOCCATEGORY;
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
    public function getDocWithType($id)
    {
        $from = Table::DOCCATEGORY;
        $select = ["*"];
        $sql = "doc_type=:type";
        $data_in = ["type" => $id];
        $group_by = "";
        $order_by = "";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, []);
    }
    /**
     * 
     */
    public function deleteOneId($id)
    {
        $from = Table::DOCCATEGORY;
        $this->deleteId($from,$id);
    }
    /**
     * 
     */
    public function getAllDocTypeDropDown($category){
        $from = Table::DOCCATEGORY;
        $sql = "doc_category=:category";
        $data_in =["category" => $category];
        $select = ["ID AS value,doc_type as label"];
        return $this->getAll($select, $from, $sql, "", "", $data_in, false, []);
    }
       public function getAllDocType($sql = "", $data_in = [], $group_by = "", $count = false)
    {
        $from = Table::DOCCATEGORY;
        $select = ["ID AS value,doc_type as label"];
        // $order_by = "created_time DESC";
        return $this->getAll($select, $from, $sql, $group_by, "", $data_in, false, [], $count);
    }
}

