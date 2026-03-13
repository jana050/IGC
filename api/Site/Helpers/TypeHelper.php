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
class TypeHelper extends BaseHelper
{
    const schema = [
        "type_name" => SmartConst::SCHEMA_VARCHAR,
        "type_value" => SmartConst::SCHEMA_VARCHAR,
        "order_number" => SmartConst::SCHEMA_INTEGER
    ];
    /**
     * 
     */
    const validations = [
      
        "type_name" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Type name"
            ],
            [
                "type" => SmartConst::VALID_MAX_LENGTH,
                "max"=>255,
                "msg"=>"Type Name Max character 255"
            ]
        ],
        "type_value" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Type value"
            ],
            [
                "type" => SmartConst::VALID_MAX_LENGTH,
                "max"=>1000,
                "msg"=>"Type Value Max character 1000"
            ]
            ],
          
        
       
    ];
    /**
     * 
     */
    public function insert(array $columns, array $data)
    {
        return $this->insertDb(self::schema, Table::TYPE, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::TYPE, $columns, $data, $id);
    }
    /**
     * 
     */
    public function getAllData($sql = "", $data_in = [], $select_in=[],$group_by = "", $order_by = "", $count = false)
    {
        $from = Table::TYPE;
        $select = ["*,type_value as label, type_value as value"];
        if(!empty($select_in)){
            $select = $select_in;
        }
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], $count);
    }
    /**
     * 
     */
    public function getOneData($id)
    {
        $from = Table::TYPE;
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
        $from = Table::TYPE;
        $this->deleteId($from,$id);
    }

    public function getWithTypeNameTypeValue(string $type_name,string $type_value){    
        $sql = "type_name=:typeName AND type_value=:typeVal";
        $data_in = ["typeName" => $type_name,"typeVal"=>$type_value];       
        $out = $this->getAllData($sql,$data_in,[],"","",true);
        return $out[0]->total_count > 0 ? true : false;
    }

   
    
}
