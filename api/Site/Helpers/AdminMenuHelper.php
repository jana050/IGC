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
class AdminMenuHelper extends BaseHelper
{
    const schema = [
        "label" => SmartConst::SCHEMA_VARCHAR,
        "parent_id" => SmartConst::SCHEMA_INTEGER,
        "link" => SmartConst::SCHEMA_VARCHAR,
        "icon" => SmartConst::SCHEMA_VARCHAR,
        "roles" => SmartConst::SCHEMA_TEXT,
        "order_number" => SmartConst::SCHEMA_INTEGER,
        "created_by" => SmartConst::SCHEMA_CUSER_ID,
        "sd_admin_modules_id" => SmartConst::SCHEMA_INTEGER,
    ];
    /**
     * 
     */
    const validations = [
        "label" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Specify label"
            ]
        ],
        "link" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Specify link"
            ]
        ],
        "icon" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Specify icon"
            ]
        ],
        //     "sd_admin_modules_id" => [
        //         [
        //             "type" => SmartConst::VALID_REQUIRED,
        //             "msg" => "Please Specify sd_admin_modules_id"
        //         ]
        //     ],
    ];

    public function getRecursive()
    {
        $rec = "
       WITH RECURSIVE " . Table::AMDIN_MENU . "_hierarchy AS (
    SELECT 
        ID, 
        label AS label, 
        parent_id, 
        icon,
        link,
        roles,
        order_number, 
        sd_admin_modules_id,  
        label AS full_label 
    FROM 
        " . Table::AMDIN_MENU . " 
    WHERE 
        parent_id IS NULL OR parent_id=0
    UNION ALL
    SELECT 
        c.ID, 
        c.label AS label, 
        c.parent_id, 
        c.icon, 
        c.link,
        c.roles,
        c.order_number,
        c.sd_admin_modules_id, 
        CONCAT(ch.full_label, ' > ', c.label) AS full_label 
    FROM 
        " . Table::AMDIN_MENU . " c 
    INNER JOIN 
        " . Table::AMDIN_MENU . "_hierarchy ch 
    ON 
        c.parent_id = ch.ID
    )
    ";
        return $rec;
    }


    /**
     * 
     */
    public function insert(array $columns, array $data)
    {
        return $this->insertDb(self::schema, Table::AMDIN_MENU, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::AMDIN_MENU, $columns, $data, $id);
    }
    /**
     * 
     */
    public function getAllData($sql = "", $data_in = [], $select = [], $limit = [], $group_by = "", $count = false, $single = false)
    {
        $from = Table::AMDIN_MENU . " t1 
        LEFT JOIN " . Table::AMDIN_MENU . "  t3 ON t3.ID=t1.parent_id
        LEFT JOIN " . Table::ADMIN_MODULES . "  t4 ON t4.ID=t1.sd_admin_modules_id";
        $select_default = [
            "t1.*",
            "t3.label as parent_name",
            "(SELECT COUNT(t2.ID) FROM " . Table::AMDIN_MENU . " t2 WHERE t2.parent_id=t1.ID) as sub_count",
            "t4.module_name",
            "t4.status as module_status"
        ];
        $select = !empty($select) ? $select : $select_default;
        return $this->getAll($select, $from, $sql, $group_by, "order_number ASC", $data_in, $single, $limit, $count);
    }


    /**
     * 
     */
    public function getOneData($id)
    {
        $from = Table::AMDIN_MENU . " t1 
        LEFT JOIN " . Table::AMDIN_MENU . "  t3 ON t3.ID=t1.parent_id
        LEFT JOIN " . Table::ADMIN_MODULES . "  t4 ON t4.ID=t1.sd_admin_modules_id";
        $select = ["t1.*", "t3.label as parent_name", "t4.module_name"];
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
        $from = Table::AMDIN_MENU;
        $this->deleteId($from, $id);
    }
    /**
     * 
     */

    public function getAllDataRecursive($sql, $data_in, $selectin = [])
    {
        $from = Table::AMDIN_MENU . "_hierarchy t1 
        LEFT JOIN " . Table::ADMIN_MODULES . " t4 ON t4.ID = t1.sd_admin_modules_id";

        $select = ["t1.ID", "t1.full_label as label", "t1.parent_id", "t1.icon", "t1.link", "t1.roles", "t1.order_number", "t4.module_name"];

        if (!empty($selectin)) {
            $select = $selectin;
        }

        $this->db->Recursive($this->getRecursive());

        return $this->getAll($select, $from, $sql, "", "label", $data_in, false, [], false);
    }


}
