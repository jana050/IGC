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
class CommiteeHistoryHelper extends BaseHelper
{

    const schema = [
        "commitee_name" => SmartConst::SCHEMA_VARCHAR,
        "commitee_id" => SmartConst::SCHEMA_INTEGER,
        "role_name" => SmartConst::SCHEMA_VARCHAR,
        "remarks" => SmartConst::SCHEMA_TEXT,
        "action" => SmartConst::SCHEMA_VARCHAR,
        "created_time" => SmartConst::SCHEMA_CDATETIME,
    ];
    /**
     * 
     */
    const validations = [
        "commitee_name" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter Commitee Name"
            ]
        ],
        "commitee_id" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter Commitee ID"
            ]
        ],
        "role_name" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter Role Name"
            ]
        ],
        "remarks" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter Remarks"
            ]
        ],
        "action" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please enter Action"
            ]
        ],


    ];

    /**
     * 
     */
    public function insert(array $columns, array $data)
    {
        return $this->insertDb(self::schema, Table::COMMITTEE_HISTORY, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::COMMITTEE_HISTORY, $columns, $data, $id);
    }
    /**
     * 
     */
    public function getAllData($sql = "", $data_in = [], $select = [], $group_by = "", $count = false, $single = false)
    {
        $from = Table::COMMITTEE_HISTORY;
        $select = ["*"];
        $order_by = "created_time DESC";

        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, $single, [], $count);
    }
    /**
     * 
     */


    /**
     * 
     */
    public function getOneData($id)
    {
        $from = Table::COMMITTEE_HISTORY;
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
        $from = Table::COMMITTEE_HISTORY;
        $this->deleteId($from, $id);
    }
    /**
     * 
     */
    /* ---------- GET BY COMMITTEE ---------- */
    public function getByCommittee($commitee_name, $commitee_id)
    {
        $sql = "commitee_name = :cname AND commitee_id = :cid";
        $data_in = [
            "cname" => $commitee_name,
            "cid" => $commitee_id
        ];

        return $this->getAllData($sql, $data_in);
    }
    /*----------------------------------------*/
    public function getByCommiteeId($commitee_name, $commitee_id)
    {
        $from = Table::COMMITTEE_HISTORY;

        $select = [
            "role_name",
            "action",
            "remarks",
            "created_time"
        ];

        $sql = "commitee_name = :commitee_name AND commitee_id = :commitee_id";

        $data_in = [
            "commitee_name" => $commitee_name,
            "commitee_id" => $commitee_id
        ];

        $group_by = "";
        $order_by = "created_time ASC";

        return $this->getAll(
            $select,
            $from,
            $sql,
            $group_by,
            $order_by,
            $data_in,
            false,
            []
        );
    }

}
