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
class OrganisationHelper extends BaseHelper
{

    const schema = [
        "sd_org_name" => SmartConst::SCHEMA_VARCHAR,
        "sd_org_type_id" => SmartConst::SCHEMA_INTEGER,
        "sd_org_id" => SmartConst::SCHEMA_INTEGER,
        "sd_mt_userdb_id" => SmartConst::SCHEMA_INTEGER,
        "created_by" => SmartConst::SCHEMA_CUSER_ID,
        "created_time" => SmartConst::SCHEMA_CDATETIME,
        "last_modified_time" => SmartConst::SCHEMA_CTIME
    ];
    /**
     * 
     */
    const validations = [
        "sd_org_name" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Organiasation Name"
            ],
            [
                "type" => SmartConst::VALID_STRING,
                "msg" => "Please Enter a valid string"
            ],
        ],
        "sd_org_type_id" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Organsation Type ID"
            ]

        ],

        "sd_org_id" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Order"
            ]

        ],
        "sd_org_short_label" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter short label"
            ]

        ],
        "sd_mt_userdb_id" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Assigned Employee ID"
            ]

        ]

    ];
    /**
     * 
     */
    public function insert(array $columns, array $data)
    {
        return $this->insertDb(self::schema, Table::ORGANISATION, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::ORGANISATION, $columns, $data, $id);
    }
    /**
     * 
     */
    public function getAllData($sql = "", $data_in = [], $select = [], $group_by = "", $count = false, $single = false)
    {
        $from = Table::ORGANISATION . " t1 LEFT JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id=t2.ID LEFT JOIN " . Table::TYPE . " t3 ON t1.sd_org_type_id=t3.ID";
        $select = ["t1.ID,t1.sd_org_name,t2.ename,t2.designation,t2.profile_img,t3.type_value,t1.sd_org_type_id,t1.sd_mt_userdb_id"];
        return $this->getAll($select, $from, $sql, $group_by, "", $data_in, $single, [], $count);
    }
    /**
     * 
     */
    public function getAllAuthParent($sql = "", $data_in = [], $select = [], $group_by = "", $count = false, $single = false)
    {
        $from = Table::ORGANISATION . " t1 
        LEFT JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id=t2.ID";
        $select = ["t1.sd_org_id,t2.ID as value,t2.ename as label"];
        return $this->getAll($select, $from, $sql, $group_by, "", $data_in, true, [], $count);
    }
    /**
     * 
     */
    public function getOneData($id)
    {
        $from = Table::ORGANISATION . " t1 
        LEFT JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id=t2.ID 
        LEFT JOIN " . Table::ORGANISATION . " t7 ON t1.ID=t7.sd_org_id LEFT JOIN " . Table::TYPE . " t3 ON t1.sd_org_type_id=t3.ID";
        $select = ["t1.*,COALESCE(t7.sd_org_name,'no parent') AS parent_name,t2.ename,t2.profile_img,t3.type_value,t1.sd_mt_userdb_id"];
        $sql = "t1.ID=:ID";
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
        $from = Table::ORGANISATION;
        $this->deleteId($from, $id);
    }


    public function getAllParents($sql = "", $data_in = [], $select = [], $group_by = "", $count = false, $single = false)
    {
        $from = Table::ORGANISATION;
        $select = ["ID as value,sd_org_full_label as label"];
        return $this->getAll($select, $from, $sql, $group_by, "", $data_in, $single, [], $count);
    }
    /**
     * 
     */
    public function getUserWithChild($id, $type)
    {
        $from = "";
        $from .= isset($type) && ($type == "org") ? Table::ORGANISATION : Table::USERS;
        $select = ["ID"];
        $sql = "sd_org_id=:ID";
        $data_in = ["ID" => $id];
        return $this->getAll($select, $from, $sql, "", "", $data_in, true, [], false);
    }
    /**
     * 
     */
    public function checkUserExist($id)
    {
        $from = Table::ORGANISATION;
        $select = ["ID"];
        $sql = "sd_mt_userdb_id=:ID";
        $data_in = ["ID" => $id];
        return $this->getAll($select, $from, $sql, "", "", $data_in, true, [], false);
    }
    /**
     * 
     */
    public function update_label(int $id, string $label)
    {
        $columns = ["sd_org_full_label"];
        $data = ["sd_org_full_label" => $label];
        return $this->updateDb(self::schema, Table::ORGANISATION, $columns, $data, $id);
    }


    public function getAllOrg($sql = "", $data_in = [], $select = [], $group_by = "", $count = false, $single = false)
    {
        $from = Table::ORGANISATION;
        $select = ["*"];
        return $this->getAll($select, $from, $sql, $group_by, "", $data_in, $single, [], $count);
    }


    public function getTypeId($desc)
    {
        $types =  [
            "GD" => 18,
            "AD" => 16,
            "DH" => 14,
            "SH" => 12,
        ];
        return isset($types[$desc]) ? $types[$desc] : 0;
    }

    public function checkRole($user_id, $type_name)
    {
        $type_id = $this->getTypeId($type_name);
        $from = Table::ORGANISATION;
        $select = ["ID"];
        $sql = "sd_mt_userdb_id=:ID AND 	sd_org_type_id=:type_id";
        $data_in = ["ID" => $user_id, "type_id" => $type_id];
        $out =  $this->getAll($select, $from, $sql, "", "", $data_in, true, [], false);
        return isset($out->ID) ? true : false;
    }

    public function getOrgId($org_id, $org_arr = [])
    {
        $from = "";
        $from .=  Table::ORGANISATION;
        $select = ["ID"];
        $sql = "sd_org_id=:ID";
        $data_in = ["ID" => $org_id];
        $out = $this->getAll($select, $from, $sql, "", "", $data_in, false, [], false);
        foreach ($out as $obj) {
            $org_arr[] = $obj->ID;
            $org_arr = $this->getOrgId($obj->ID, $org_arr);
        }
        return $org_arr;
    }

    public function getOrgIdWithUser($user_id, $type_name)
    {
        $type_id = $this->getTypeId($type_name);
        $from = Table::ORGANISATION;
        $select = ["ID"];
        $sql = "sd_mt_userdb_id=:ID AND 	sd_org_type_id=:type_id";
        $data_in = ["ID" => $user_id, "type_id" => $type_id];
        $out =  $this->getAll($select, $from, $sql, "", "", $data_in, false, [], false);
        $org_arr = [];
        foreach ($out as $obj) {
            $org_arr[] = $obj->ID;
        }
        return  $org_arr;
    }

    public function getSubOrdIds($user_id, $type_name)
    {
        $org_arr = [];
        $head_org = $this->getOrgIdWithUser($user_id, $type_name);
        foreach ($head_org as $org_id) {
            $org_arr[] = $org_id;
            $org_arr = $this->getOrgId($org_id, $org_arr);
        }
        return $org_arr;
    }

    public function getOrgTotal()
    {
        // get from orgtable left join with user table where sd_mt_userdb_id is joined user table id
        $from = Table::ORGANISATION . " t1 
        LEFT JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id=t2.ID 
        LEFT JOIN " . Table::USERS . " t3 ON t1.ID=t3.sd_org_id";
        $select = ["t1.ID", "t1.sd_org_full_label", "t1.sd_org_type_id", "t2.euserid as head_id", "t3.ename as emp_name", "t3.euserid as emp_euserid"];
        $data = $this->getAll($select, $from, "", "", "t1.sd_org_full_label ASC", [], false, [], false);
        return $data;
    }

    public function getOrgHead($type)
    {
        $sql = "t1.sd_org_type_id=:type_id";
        $data_in = ["type_id" => $this->getTypeId($type)];
        // get from orgtable left join with user table where sd_mt_userdb_id is joined user table id
        $from = Table::ORGANISATION . " t1 
        LEFT JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id=t2.ID ";
        $select = ["t1.sd_mt_userdb_id as value", "t2.ename as label"];
        $data = $this->getAll($select, $from, $sql, "", "t1.sd_org_full_label ASC", $data_in, false, [], false);
        return $data;
    }
}
