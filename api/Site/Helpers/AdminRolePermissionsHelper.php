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
class AdminRolePermissionsHelper extends BaseHelper
{
    const schema = [
        "role_id" => SmartConst::SCHEMA_INTEGER,
        "permission_id" => SmartConst::SCHEMA_INTEGER,
    ];
    /**
     * 
     */
    const validations = [
        "role_id" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Specify role_id"
            ]
        ],
        "permission_id" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Specify permission_id"
            ]
        ],
    ];

    /**
     * 
     */
    public function insert(array $columns, array $data)
    {
        return $this->insertDb(self::schema, Table::ADMIN_ROLE_PERMISSION, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::ADMIN_ROLE_PERMISSION, $columns, $data, $id);
    }
    /**
     * 
     */
    public function getAllData($sql = "", $data_in = [], $select = [], $limit = [], $group_by = "", $count = false, $single = false)
    {
        $from = Table::ADMIN_ROLE_PERMISSION . " t1 
        LEFT JOIN " . Table::ROLES . " t2 ON t1.role_id=t2.ID 
         LEFT JOIN " . Table::ADMIN_MODULES . " t3 ON t1.permission_id=t3.ID 
        ";
        $select_default = [
            "t1.*",
            "t2.role_name",
            "t3.module_name"
        ];
        $select = !empty($select) ? $select : $select_default;
        return $this->getAll($select, $from, $sql, $group_by, "", $data_in, $single, $limit, $count);
    }

    /**
     * 
     */
    public function getOneData($id)
    {
        $from = Table::ADMIN_ROLE_PERMISSION . " t1";
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
        $from = Table::ADMIN_ROLE_PERMISSION;
        $this->deleteId($from, $id);
    }

    /**
     * 
     */

    public function insert_data($roleId, $data)
    {
        // Delete existing entries for the role before inserting new ones
        $this->deleteWithParentId($roleId);

        foreach ($data as $_data) {
            if (isset($_data["permission_id"])) {
                $_data_in = [
                    "role_id" => $roleId,
                    "permission_id" => $_data["permission_id"]
                ];
                $this->insert(["role_id", "permission_id"], $_data_in);
            }
        }
    }

    public function getAllWithParentId($roleId)
    {
        $sql = "t1.role_id=:id";
        $data_in = ["id" => $roleId];
        return $this->getAllData($sql, $data_in);
    }

    public function deleteWithParentId($roleId)
    {
        $sql = "role_id=:id";
        $data_in = ["id" => $roleId];
        $this->deleteBySql(Table::ADMIN_ROLE_PERMISSION, $sql, $data_in);
    }


    public function checkOneWithRoleIdPermissionId($_role_id,$permission_id)
    {
        $from = Table::ADMIN_ROLE_PERMISSION . " t1";
        $select = ["t1.*",];
        $sql = " t1.role_id=:role_id AND t1.permission_id=:permission_id";
        $data_in = ["role_id" => $_role_id,"permission_id"=>$permission_id];
        $data = $this->getAll($select, $from, $sql, "", "", $data_in, true, []);
        return isset($data->ID) ? true : false;
    }

    public function insert_single_row($role_id,$permissionId){
        $_data_in = [
            "role_id" => $role_id,
            "permission_id" => $permissionId
        ];
        $this->insert(["role_id", "permission_id"], $_data_in);
    }
   

    

}
