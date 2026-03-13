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
class AdminModulePermissionsHelper extends BaseHelper
{
    const schema = [
        "module_id" => SmartConst::SCHEMA_INTEGER,
        "action" => SmartConst::SCHEMA_VARCHAR,
    ];
    /**
     * 
     */
    const validations = [
        "module_id" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Specify module_id"
            ]
        ],
        "action" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Specify action"
            ]
        ],
    ];

    /**
     * 
     */
    public function insert(array $columns, array $data)
    {
        return $this->insertDb(self::schema, Table::ADMIN_MODULE_PERMISSION, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::ADMIN_MODULE_PERMISSION, $columns, $data, $id);
    }
    /**
     * 
     */
    public function getAllData($sql = "", $data_in = [], $select = [], $limit = [], $group_by = "", $count = false, $single = false)
    {
        $from = Table::ADMIN_MODULE_PERMISSION . " t1 
          LEFT JOIN " . Table::ADMIN_MODULES . " t2 ON t1.module_id=t2.ID";
        $select_default = [
            "t1.*",
            "t2.module_name",
        ];
        $select = !empty($select) ? $select : $select_default;
        return $this->getAll($select, $from, $sql, $group_by, "", $data_in, $single, $limit, $count);
    }

    /**
     * 
     */
    public function getOneData($id)
    {
        $from = Table::ADMIN_MODULE_PERMISSION . " t1";
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
        $from = Table::ADMIN_MODULE_PERMISSION;
        $this->deleteId($from, $id);
    }
    public function insert_data($_id, $data)
    {
        // Delete existing entries for the module
        $this->deleteWithParentId($_id);

        foreach ($data as $_data) {
            if (isset($_data["action"])) {
                $_data_in = [
                    "module_id" => $_id,
                    "action" => $_data["action"]
                ];
                $this->insertSingle($_id, $_data_in);
            }
        }
    }


    public function getAllWithParentId($_id)
    {
        $sql = "t1.module_id=:id";
        $data_in = ["id" => $_id];
        return $this->getAllData($sql, $data_in);
    }

    public function deleteWithParentId($_id)
    {
        $sql = "module_id=:id";
        $data_in = ["id" => $_id];
        $this->deleteBySql(Table::ADMIN_MODULE_PERMISSION, $sql, $data_in);
    }


    public function insertSingle($_id, $_data)
    {
        $columns = [
            "module_id",
            "action"
        ];
        $_data["module_id"] = $_id;
        $this->insert($columns, $_data);
    }


    public function getAllPermissionsWithModuleId($module_id)
    {
        $from = Table::ADMIN_MODULE_PERMISSION . " t1 
         INNER JOIN " . Table::ADMIN_ROLE_PERMISSION . " t2 ON t1.module_id=t2.permission_id 
         INNER JOIN " . Table::ROLES . " t3 ON t2.role_id=t3.ID ";
        $select_default = [
            "t1.ID,t1.action",
            "t3.role_name",
        ];
        $sql = "t1.module_id=:id";
        $data_in = ["id" => $module_id];
        $select = !empty($select) ? $select : $select_default;
        return $this->getAll($select, $from, $sql, "", "", $data_in, false, [], false);
    }
}
