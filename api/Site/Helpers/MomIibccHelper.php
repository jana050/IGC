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
class MomIibccHelper extends BaseHelper
{

    const schema = [
        "mom_type" => SmartConst::SCHEMA_VARCHAR,
        "meet_desc" => SmartConst::SCHEMA_VARCHAR,
        "meet_no" => SmartConst::SCHEMA_VARCHAR,
        "meet_date" => SmartConst::SCHEMA_DATE,
        "mom_file" => SmartConst::SCHEMA_VARCHAR,
        "created_by" => SmartConst::SCHEMA_CUSER_ID,  
        "created_time" => SmartConst::SCHEMA_CDATETIME,

    ];
    /**
     * 
     */
    const validations = [
        "mom_type" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Select Meet Type"
            ]
        ],

        "meet_no" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Meet Number"
            ]
        ],
        "meet_desc" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Meet Description"
            ]
        ],
        "meet_date" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Date"
            ]
        ],
        "mom_file" => [
            [
                "type" => SmartConst::VALID_FILE_REQUIRED,
                "msg" => "Please Upload the Document"
            ],
            /*
        [
            "type" => SmartConst::VALID_FILE_SIZE,
            "msg" => "The Document Size Should be maximum 0.1 MB",
            "size"=>[0,2]
        ],*/
            [
                "type" => SmartConst::VALID_FILE_TYPE,
                "msg" => "Only pdf is allowed",
                "ext" => ["pdf"]
            ]
        ],



    ];

    // file handling 
    const FILE_FOLDER = "momIibcc";
    const FILE_NAME = "file";

    //
    public function getFullFile($id)
    {
        return self::FILE_FOLDER . DS . $id . DS . self::FILE_NAME;
    }

    /**
     * 
     */

 
    /*
    -----
    -----
    MOM TABLE
    -----
    */
    /**
     * 
     */
    public function insertMom(array $columns, array $data)
    {
        return $this->insertDb(self::schema, Table::MOM_IIBCC, $columns, $data);
    }
    /**
     * 
     */
    public function updateMom(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::MOM_IIBCC, $columns, $data, $id);
    }
    /**
     * 
     */
    public function getAllDataMom($sql = "", $data_in = [], $group_by = "", $count = false)
    {
        $from = Table::MOM_IIBCC;
        $select = ["*"];
        $order_by = "created_time DESC";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], $count);
    }
    /**
     * 
     */
    public function getOneDataMom($id)
    {
        $from = Table::MOM_IIBCC;
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
    public function deleteOneIdMom($id)
    {
        $from = Table::MOM_IIBCC;
        $this->deleteId($from, $id);
    }
    public function getByMomType($type)
    {
        $from = Table::MOM_IIBCC;
        $select = ["*"];
        $sql = "mom_type=:type";
        $data_in = ["type" => $type];
        $group_by = "";
        $order_by = "meet_no DESC";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, []);
    }
        public function getOneByMomType($mom_type)
{

     $from = Table::MOM_IIBCC;
      $select = ["*"];
    $sql = "mom_type = :mom_type";
    $data_in = ["mom_type" => $mom_type];

    $group_by = "";
    $order_by = "created_time DESC"; // latest record

    return $this->getAll(
        $select,
        $from,
        $sql,
        $group_by,
        $order_by,
        $data_in,
        true,
        []
    );
}



}
