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
class BudgetTypeHelper extends BaseHelper
{
    const schema = [
        "budget_type" => SmartConst::SCHEMA_VARCHAR,
        "budget_no" => SmartConst::SCHEMA_VARCHAR,
        "amount" => SmartConst::SCHEMA_INTEGER,
    ];
    /**
     *
     */
    const validations = [

        "budget_type" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Select Budget Type"
            ]
        ],
        "budget_no" => [
            [                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Budget No"
            ]
        ],
        "amount" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Amount"
            ]
        ],

    ];
    /**
     * 
     */
    public function insert(array $columns, array $data)
    {
        return $this->insertDb(self::schema, Table::BUDGET_TYPE, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::BUDGET_TYPE, $columns, $data, $id);
    }
    /**
     * 
     */
    public function getAllData($sql = "", $data_in = [], $select_in = [], $group_by = "", $order_by = "", $count = false)
    {
        $from = Table::BUDGET_TYPE;
        $select = ["ID", "budget_type", "budget_no", "amount"];
        if (!empty($select_in)) {
            $select = $select_in;
        }
        if (empty($order_by)) {
            $order_by = "budget_type ASC, budget_no ASC";
        }
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], $count);
    }
    /**
     * 
     */
    public function getOneData($id)
    {
        $from = Table::BUDGET_TYPE;
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
        $from = Table::BUDGET_TYPE;
        $this->deleteId($from, $id);
    }
    /**
     * 
     */

    public function getAllSelect($sql = "", $data_in = [], $group_by = "", $count = false)
    {
        $from = Table::BUDGET_TYPE;
        // Label format: "CAPITAL - 540100401020052" when budget_type is set,
        // otherwise just the budget_no so old rows don't show a dangling "-".
        // budget_type is also returned as a separate field for any caller
        // that wants to show/filter on it independently.
        $select = [
            "ID as value",
            "CASE
                WHEN budget_type IS NOT NULL AND budget_type <> ''
                THEN CONCAT(budget_type, ' - ', budget_no)
                ELSE budget_no
            END as label",
            "budget_type",
            "budget_no",
        ];
        $order_by = "budget_type ASC, budget_no ASC";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], $count);
    }

    public function isUsedInOtherTables($id)
    {
        $tables = [
            Table::GEM_DIRECT,
            Table::COMMITTEE_IIBCC,
            Table::COMMITTEE_LPC,
            Table::MBOOK_ISSUE
        ];

        foreach ($tables as $table) {
            $sql = "head_of_account = :id";
            $data_in = ["id" => $id];
            $result = $this->getAll(["ID"], $table, $sql, "", "", $data_in, true, []);
            if ($result) {
                return true;
            }
        }
        return false;
    }
    




}
