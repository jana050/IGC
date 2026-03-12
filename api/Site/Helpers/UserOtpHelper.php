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
class UserOtpHelper extends BaseHelper
{

    const schema = [
        "sd_mt_userdb_id" => SmartConst::SCHEMA_INTEGER,
        "mobile_no" => SmartConst::SCHEMA_VARCHAR,
        "otp_code_hast" => SmartConst::SCHEMA_VARCHAR,
        "otp_created_time" => SmartConst::SCHEMA_CDATETIME
    ];
    /**
     * 
     */
    const validations = [
    
        "mobile_no" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Mobile Number"
            ],
            [
                "type" => SmartConst::VALID_MAX_LENGTH,
                "max"=>10,
                "msg"=>"Mobile Number  Max character 10"
            ]
        ],
        "otp_code_hast" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Code hast"
            ]
            ],
        "sd_mt_userdb_id" => [
                [
                    "type" => SmartConst::VALID_REQUIRED,
                    "msg" => "Please Enter user id"
                ]
                ],
        "otp_code" => [
                    [
                        "type" => SmartConst::VALID_REQUIRED,
                        "msg" => "Please Enter Code"
                    ]
                    ],
        
    ];
    /**
     * 
     */
    public function insert(array $columns, array $data)
    {
        return $this->insertDb(self::schema, Table::USEROTP, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::USEROTP, $columns, $data, $id);
    }
    /**
     * 
     */
    public function getAllData($sql = "", $data_in = [], $group_by = "", $count = false)
    {
        $from = Table::USEROTP;
        $select = ["*"];
        $order_by="otp_created_time DESC";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], $count);
    }
    /**
     * 
     */
    public function getOneData($id)
    {
        $from = Table::USEROTP. " t1 INNER JOIN ".Table::USERS." t2 ON t1.sd_mt_userdb_id = t2.ID";
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
    public function deleteOneId($id)
    {
        $from = Table::USEROTP;
        $this->deleteId($from,$id);
    }
}
