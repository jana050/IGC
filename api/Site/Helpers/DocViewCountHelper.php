<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Site\Helpers;

use Core\BaseHelper;
use Core\Helpers\SmartConst;
use Core\Helpers\SmartGeneral;

//
use Site\Helpers\TableHelper as Table;

/**
 * Description of Data
 * 
 *  class helps to get the data from post with specified type 
 *
 * @author kms
 */
class DocViewCountHelper extends BaseHelper
{

    const schema = [
        "doc_id" => SmartConst::SCHEMA_INTEGER,
        "sd_mt_userdb_id" => SmartConst::SCHEMA_INTEGER,
        "action_type" => SmartConst::SCHEMA_STRING,
        "created_by" => SmartConst::SCHEMA_CUSER_ID,   
        "created_time" => SmartConst::SCHEMA_CDATETIME      
    ];
    /**
     * 
     */
    const validations = [
        "doc_id" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Specify doc_id"
            ]
        ],
        "action_type" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Specify action_type('VIEW', 'DOWNLOAD)"
            ]
        ]
        
    ];
    /**
     * 
     */
    public function insert(array $columns, array $data)
    {
        return $this->insertDb(self::schema, Table::DOC_VIEW_COUNT, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::DOC_VIEW_COUNT, $columns, $data, $id);
    }
    /**
     * 
     */
    public function getAllData($sql = "", $data_in = [], $group_by = "", $count = false)
    {
        $from = Table::DOC_VIEW_COUNT;
        $select = ["*"];
        $order_by="";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], $count);
    }
    /**
     * 
     */
    public function getOneData($id)
    {
        $from = Table::DOC_VIEW_COUNT;
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
        $from = Table::DOC_VIEW_COUNT;
        $this->deleteId($from,$id);
    }

    /**
     * get count of actions (VIEW/DOWNLOAD) for a document
     */
    public function getActionCount(int $doc_id, string $actionType = 'VIEW')
    {
        if (empty($doc_id)) {
            \CustomErrorHandler::triggerInvalid("Invalid Document ID");
        }

        $from = Table::DOC_VIEW_COUNT;
        $select = ["COUNT(*) as count"];
        $sql = "doc_id = :doc_id AND action_type = :actionType";
        $data_in = [
            "doc_id" => $doc_id,
            "actionType" => $actionType
        ];

        $result = $this->getAll($select, $from, $sql, "", "", $data_in, true, []);
        return isset($result->count) ? intval($result->count) : 0;
    }
    
        /**
     * get count of actions (VIEW/DOWNLOAD) for a document
     */
    public function getCountByAction(int $doc_id, string $actionType = 'VIEW')
    {
        if (empty($doc_id)) {
            \CustomErrorHandler::triggerInvalid("Invalid Document ID");
        }

        $from = Table::DOC_VIEW_COUNT;
        $select = ["COUNT(*) as cnt"];
        $sql = "doc_id = :doc_id AND action_type = :action_type";
        $data_in = [
            "doc_id" => $doc_id,
            "action_type" => $actionType
        ];

        $result = $this->getAll($select, $from, $sql, "", "", $data_in, true, []);
        return isset($result->cnt) ? intval($result->cnt) : 0;
    }


}

