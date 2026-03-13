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
class CommiteeLpcSubHelper extends BaseHelper
{
    const schema = [
        "sd_commitee_lpc_id" => SmartConst::SCHEMA_INTEGER,
        "item_description" => SmartConst::SCHEMA_VARCHAR,
        "item_quantity" => SmartConst::SCHEMA_VARCHAR,
        "item_unit" => SmartConst::SCHEMA_VARCHAR,
        "item_estimated_unit_cost" => SmartConst::SCHEMA_VARCHAR,
        "item_total_estimated_cost" => SmartConst::SCHEMA_VARCHAR,
        "end_use_item" => SmartConst::SCHEMA_VARCHAR,
        "created_time" => SmartConst::SCHEMA_CDATETIME,
    ];

    /**
     * 
     */
    const validations = [
        "sd_commitee_lpc_id" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Parent ID is required"
            ]
        ],
        "item_description" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify item description"
            ]
        ],
        "item_quantity" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify item quantity"
            ]
        ],
        "item_unit" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify item unit"
            ]
        ],
        "item_estimated_unit_cost" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify item estimated unit cost"
            ]
        ],
        "item_total_estimated_cost" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify item total estimated cost"
            ]
        ],
        "end_use_item" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify end use item"
            ]
            
        ],
  

    ];


    /**
     * 
     */
    public function insert(array $columns, array $data)
    {
        return $this->insertDb(self::schema, Table::COMMITTEE_LPC_SUB, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::COMMITTEE_LPC_SUB, $columns, $data, $id);
    }
    /**
     * 
     */

    public function getAllData($sql = "", $data_in = [], $select = [], $group_by = "", $count = false, $single = false)
    {
        $from = Table::COMMITTEE_LPC_SUB . " t1  
         INNER JOIN " . Table::COMMITTEE_LPC . " t2 ON t2.ID = t1.sd_commitee_lpc_id";
        $select_default = ["t1.*", "t2.indent_no"];
        $select = !empty($select) ? $select : $select_default;
        return $this->getAll($select, $from, $sql, $group_by, "", $data_in, $single, [], $count);
    }

    /**
     * 
     */
    public function getOneData($id)
    {
        $from = Table::COMMITTEE_LPC_SUB . " t1";
        $select = ["t1.*"];
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
        $from = Table::COMMITTEE_LPC_SUB;
        $this->deleteId($from, $id);
    }

    public function insert_update($_id, $data)
    {
        $this->deleteWithParentId($_id);
        // we nee to delete existing enties
        foreach ($data as $_data) {
            $this->insertSingle($_id, $_data);
        }
    }

    public function deleteWithParentId($_id)
    {
        $sql = "sd_commitee_lpc_id=:id";
        $data_in = ["id" => $_id];
        $this->deleteBySql(Table::COMMITTEE_LPC_SUB, $sql, $data_in);
    }

    public function checkExists($_id, $sd_commitee_lpc_id)
    {
        $from = Table::COMMITTEE_LPC_SUB . " t1";
        $select = ["t1.ID"];
        $sql = " t1.sd_commitee_lpc_id=:pid";
        $data_in = ["ID" => $_id, "pid" => $sd_commitee_lpc_id];
        $data = $this->getAll($select, $from, $sql, "", "", $data_in, true, []);
        return $data;
    }

    /**
     * insert the batch inforamtion
     */
    public function insertSingle($_id, $_data)
    {
        $other_columns = [
            "sd_commitee_lpc_id",
            "item_description",
            "item_quantity",
            "item_unit",
            "item_estimated_unit_cost",
            "item_total_estimated_cost",
            "end_use_item"
        ];
        $columns = $other_columns;
        $_data["sd_commitee_lpc_id"] = $_id;
        $this->insert($columns, $_data);
    }
    public function getAllWithParentId($_id)
    {
        $sql = "t1.sd_commitee_lpc_id=:id";
        $data_in = ["id" => $_id];
        return $this->getAllData($sql, $data_in);
    }
public function getAllWithCommitteeLpcId($_id)
    {
        $sql = "t1.sd_commitee_lpc_id=:id";
        $data_in = ["id" => $_id];
        return $this->getAllData($sql, $data_in);
    }
}
