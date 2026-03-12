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
class AdminModulesHelper extends BaseHelper
{
    const schema = [
        "module_name" => SmartConst::SCHEMA_VARCHAR,
        "status" => SmartConst::SCHEMA_INTEGER,
    ];
    /**
     * 
     */
    const validations = [
        "module_name" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Specify module_name"
            ]
        ],
    ];

    /**
     * 
     */
    public function insert(array $columns, array $data)
    {
        return $this->insertDb(self::schema, Table::ADMIN_MODULES, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::ADMIN_MODULES, $columns, $data, $id);
    }
    /**
     * 
     */
    public function getAllData($sql = "", $data_in = [], $select = [], $limit = [], $group_by = "", $count = false, $single = false)
    {
        $from = Table::ADMIN_MODULES . " t1 ";
        $select_default = [
            "t1.*",
        ];
        $select = !empty($select) ? $select : $select_default;
        return $this->getAll($select, $from, $sql, $group_by, "", $data_in, $single, $limit, $count);
    }

    // public function getAllActiveModules()
    // {
    //     $sql  = "t1.status=:5";
    //     return $this->getAllData($sql);
    // }
    public function getAllActiveModules()
    {
    $sql  = "t1.status=:status";
    $data_in = ["status" => 5];  // Correct binding
    return $this->getAllData($sql, $data_in);
    }

    /**
     * 
     */
    public function getOneData($id)
    {
        $from = Table::ADMIN_MODULES . " t1";
        $select = ["t1.*",];
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
        $from = Table::ADMIN_MODULES;
        $this->deleteId($from, $id);
    }
}
