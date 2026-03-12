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
class RadiologicalWorkSubHelper extends BaseHelper
{
    const schema = [
        "sd_radiological_work_id" => SmartConst::SCHEMA_INTEGER,
        "name_of_personnel" => SmartConst::SCHEMA_VARCHAR,
        "tld_no" => SmartConst::SCHEMA_VARCHAR,
        "activity_nature" => SmartConst::SCHEMA_TEXT,
        "signature" => SmartConst::SCHEMA_VARCHAR,
        "drd_no" => SmartConst::SCHEMA_VARCHAR,
        "planned_exposure" => SmartConst::SCHEMA_VARCHAR,
        "dose_as_per" => SmartConst::SCHEMA_VARCHAR,
        "created_time" => SmartConst::SCHEMA_CDATETIME,
    ];

    /**
     * 
     */
    const validations = [
        "sd_radiological_work_id" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Parent ID is required"
            ]
        ],
        "name_of_personnel" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify name_of_personnel"
            ]
        ],
        "tld_no" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify TLD No"
            ]
        ],
        "activity_nature" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify activity_nature"
            ]
        ],
        "signature" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify signature"
            ]
        ],

        "drd_no" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify drd_no"
            ]
        ],
        "planned_exposure" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify drd_no"
            ]
        ],
        "dose_as_per" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please specify dose_as_per"
            ]
        ],

    ];


    /**
     * 
     */
    public function insert(array $columns, array $data)
    {
        return $this->insertDb(self::schema, Table::RADIOLOGICAL_WORK_SUB, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::RADIOLOGICAL_WORK_SUB, $columns, $data, $id);
    }
    /**
     * 
     */

    public function getAllData($sql = "", $data_in = [], $select = [], $group_by = "", $count = false, $single = false)
    {
        $from = Table::RADIOLOGICAL_WORK_SUB . " t1  
         INNER JOIN " . Table::RADIOLOGICAL_WORK . " t2 ON t2.ID = t1.sd_radiological_work_id";
        $select_default = ["t1.*", "t2.name"];
        $select = !empty($select) ? $select : $select_default;
        return $this->getAll($select, $from, $sql, $group_by, "", $data_in, $single, [], $count);
    }

    /**
     * 
     */
    public function getOneData($id)
    {
        $from = Table::RADIOLOGICAL_WORK_SUB . " t1";
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
        $from = Table::RADIOLOGICAL_WORK_SUB;
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
        $sql = "sd_radiological_work_id=:id";
        $data_in = ["id" => $_id];
        $this->deleteBySql(Table::RADIOLOGICAL_WORK_SUB, $sql, $data_in);
    }

    public function checkExists($_id, $sd_radiological_work_id)
    {
        $from = Table::RADIOLOGICAL_WORK_SUB . " t1";
        $select = ["t1.ID"];
        $sql = " t1.sd_radiological_work_id=:pid";
        $data_in = ["ID" => $_id, "pid" => $sd_radiological_work_id];
        $data = $this->getAll($select, $from, $sql, "", "", $data_in, true, []);
        return $data;
    }

    /**
     * insert the batch inforamtion
     */
    public function insertSingle($_id, $_data)
    {
        $other_columns = [
            "sd_radiological_work_id",
            "name_of_personnel",
            "tld_no",
            "activity_nature",
            "signature",
            "drd_no",
            "planned_exposure",
            "dose_as_per"
        ];
        $columns = $other_columns;
        $_data["sd_radiological_work_id"] = $_id;
        $this->insert($columns, $_data);
    }
    public function getAllWithParentId($_id)
    {
        $sql = "t1.sd_radiological_work_id=:id";
        $data_in = ["id" => $_id];
        return $this->getAllData($sql, $data_in);
    }
public function getAllWithRadiologicalId($_id)
    {
        $sql = "t1.sd_radiological_work_id=:id";
        $data_in = ["id" => $_id];
        return $this->getAllData($sql, $data_in);
    }
}
