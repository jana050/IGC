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
use stdClass;

/**
 * Description of Data
 * 
 *  class helps to get the data from post with specified type 
 *
 * @author kms
 */
class DocumentPermissionHelper extends BaseHelper
{

    const schema = [
        "sd_doc_list_id" => SmartConst::SCHEMA_INTEGER,
        "permission_type" => SmartConst::SCHEMA_INTEGER,
        "type" => SmartConst::SCHEMA_INTEGER,
        "sd_mt_userdb_id" => SmartConst::SCHEMA_INTEGER,
        "sd_mt_role_id" => SmartConst::SCHEMA_INTEGER
    ];
    /**
     * 
     */
    const validations = [
        "sd_doc_list_id" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Doc ID Required"
            ]
        ],
        "permission_type" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Permission Type Required"
            ]
        ],
        "type" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Type Required"
            ]
        ],


    ];
    /**
     * 
     */
    public function insert(array $columns, array $data)
    {
        return $this->insertDb(self::schema, Table::DOCS_PERMISSION, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::DOCS_PERMISSION, $columns, $data, $id);
    }
    /**
     * 
     */
    public function getAllData($sql = "", $data_in = [], $select_in = [], $group_by = "", $count = false)
    {
        $from = Table::DOCS_PERMISSION . " t1 
        LEFT JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id=t2.ID 
        LEFT JOIN " . Table::ROLES . " t3 ON t1.sd_mt_role_id=t3.ID";
        $select = ["t1.*", "t3.role_name", "t2.ename"];
        $order_by = "";
        if (!empty($select_in)) {
            $select = $select_in;
        }
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], $count);
    }
    /**
     * 
     */
    public function getOneData($id)
    {
        $from = Table::DOCS_PERMISSION . " t1 
        LEFT JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id=t2.ID 
        LEFT JOIN " . Table::ROLES . " t3 ON t1.sd_mt_role_id=t3.ID";
        $select = ["t1.*,t2.ename as created_by"];
        $sql = "t1.ID=:ID";
        $data_in = ["ID" => $id];
        $group_by = "";
        $order_by = "";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, true, []);
    }
    /**
     * 
     */
    public function getDocPermWithUserID($id)
    {
        $from = Table::DOCS_PERMISSION;
        $select = ["*"];
        $sql = "sd_mt_userdb_id=:ID";
        $data_in = ["ID" => $id];
        $group_by = "";
        $order_by = "";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, true, []);
    }
    /**
     * 
     */
    public function getDocPermWithDocID($sql, $data_in)
    {
        $from = Table::DOCS_PERMISSION;
        $select = ["*"];
        return $this->getAll($select, $from, $sql, "", "", $data_in, false, [], true);
    }
    /**
     * 
     */
    public function deleteOneId($id)
    {
        $from = Table::DOCS_PERMISSION;
        $this->deleteId($from, $id);
    }
    /**
     * 
     */
    public function insertPermission(string $permission_type, string $type, int $st_doc_id, array $data)
    {
        $ptype = $permission_type == "VIEW" ? 1 : 2;
        $type_id = $type == "USER" ? 1 : 2;
        // delete existing roles with user id
        $delete_sql = "sd_doc_list_id=:doc_id AND permission_type=:p_type AND type=:type";
        $delete_data = ["doc_id" => $st_doc_id, "p_type" => $ptype, "type" => $type_id];
        $this->deleteBySql(Table::DOCS_PERMISSION, $delete_sql, $delete_data);
        // columns
        $columns = ["sd_doc_list_id", "permission_type", "type"];
        foreach ($data as $single_data) {
            $data_in = [];
            $data_in["sd_doc_list_id"] = $st_doc_id;
            $data_in["permission_type"] = $ptype;
            $data_in["type"] = $type_id;
            if ($type_id == 1) {
                $columns[] = "sd_mt_userdb_id";
                $data_in["sd_mt_userdb_id"] = isset($single_data["value"]) ? $single_data["value"] : 0;
            } else {
                $columns[] = "sd_mt_role_id";
                $data_in["sd_mt_role_id"] = isset($single_data["value"]) ? $single_data["value"] : 0;
            }
            //var_dump($data_in);exit();
            //$data_in["sd_mt_role_id"] = isset($single_data["value"]) ? $single_data["value"] : 0;          
            $this->insert($columns, $data_in);
        }
        // return $this->insertDb(self::schema, Table::USERROLE, $columns, $data);
    }
    /**
     * 
     */
    public function getData(string $permission_type, string $type, int $st_doc_id)
    {
        $ptype = $permission_type == "VIEW" ? 1 : 2;
        $type_id = $type == "USER" ? 1 : 2;
        $sql = "sd_doc_list_id=:doc_id AND permission_type=:p_type AND type=:type";
        $data = ["doc_id" => $st_doc_id, "p_type" => $ptype, "type" => $type_id];
        return $this->getAllData($sql, $data);
    }

    public function getAllPermissions($docid)
    {
        // view permisions user & role 
        $view_role = $this->getData("VIEW", "ROLES", $docid);
        $view_users = $this->getData("VIEW", "USER", $docid);
        $download_roles =  $this->getData("DOWNLOAD", "ROLES", $docid);
        $download_users =  $this->getData("DOWNLOAD", "USER", $docid);
        $db = new stdClass();
        $db->view_role = $view_role;
        $db->view_users = $view_users;
        $db->download_users = $download_users;
        $db->download_roles = $download_roles;
        return $db;
        // "VIEW"
    }

    /**
     * 
     */
    public function check_permission(
        string $permission_type,
        string $type,
        int $st_doc_id,
        int $userid,
        $roles
    ) {
        $ptype = $permission_type == "VIEW" ? 1 : 2;
        $type_id = $type == "USER" ? 1 : 2;
        $count = $this->check_permission_available($st_doc_id, $ptype);
        if ($count == 0) {
            return true;
        }
        $sql = "sd_doc_list_id=:doc_id AND permission_type=:p_type AND type=:type";
        $data = ["doc_id" => $st_doc_id, "p_type" => $ptype, "type" => $type_id];
        if ($type_id == 1) {
            // this is user check
            $sql .= " AND (sd_mt_userdb_id=:user_id OR sd_mt_userdb_id=10000000)";
            $data["user_id"] = $userid;
        } else {
            if (sizeof($roles) < 1) {
                return false;
            }
            $roles_arr = [];
            // var_dump($roles);
            foreach ($roles as $obj) {
                $roles_arr[] = $obj->value;
            }

            $sql .= " AND sd_mt_role_id IN (" . implode(",", $roles_arr) . ")";
        }
        $data_out = $this->getAllData($sql, $data);
        if ($data_out) {
            return true;
        } else {
            return false;
        }
    }

    public function check_permission_available($doc_id, $type)
    {
        $sql = "sd_doc_list_id=:doc_id AND permission_type=:type";
        $data = ["doc_id" => $doc_id, "type" => $type];
        $doc_count = $this->getDocPermWithDocID($sql, $data);
        return isset($doc_count[0]->total_count) ? intval($doc_count[0]->total_count) : 0;
    }
}
