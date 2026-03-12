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
class MeetProposalHelper extends BaseHelper
{ 
    const schema = [
        "title" => SmartConst::SCHEMA_VARCHAR,
        "description" => SmartConst::SCHEMA_VARCHAR,
        "doc_loc" => SmartConst::SCHEMA_VARCHAR,
        "app_id" => SmartConst::SCHEMA_INTEGER,
        "app_date" => SmartConst::SCHEMA_CDATE,
        "app_remarks" => SmartConst::SCHEMA_VARCHAR,
        "status" => SmartConst::SCHEMA_INTEGER,
        "mom_type" => SmartConst::SCHEMA_VARCHAR,
        "created_by" => SmartConst::SCHEMA_CUSER_ID,
        "created_time" => SmartConst::SCHEMA_CDATETIME,
    ];
    /**
     * 
     */
    const validations = [
      
        "title" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Tile"
            ],
            [
                "type" => SmartConst::VALID_MAX_LENGTH,
                "max"=>55,
                "msg"=>"Title Max character 55"
            ]
        ],
        "description" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Description"
            ],
            [
                "type" => SmartConst::VALID_MAX_LENGTH,
                "max"=>255,
                "msg"=>"Title Max character 255"
            ]
            ],
        "app_id" =>[
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please select Approving Authority"
            ]
        ], 
        "app_date" =>[
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please select Approving Date"
            ]
        ], 
        "app_remarks" =>[
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Remarks"
            ]
        ], 
        "status" =>[
            [
                "type" => SmartConst::VALID_NUM,
                "msg" => "Please select a status"
            ]
        ], 
        "meet_type_id" =>[
            [
                "type" => SmartConst::VALID_NUM,
                "msg" => "Please specify Meet Type"
            ]
        ],  
        "uploaded_file" => [
            [
                "type" => SmartConst::VALID_FILE_REQUIRED,
                "msg" => "Please Upload the Document"
            ],
            [
                "type" => SmartConst::VALID_FILE_TYPE,
                "msg" => "Only pdf is allowed",
                "ext"=>["pdf"]
            ]
        ],
       
    ];
    
     // file handling 
     const FILE_FOLDER = "meetproposal";
     const FILE_NAME = "file";
     public function getFullFile($id)
     {
         return self::FILE_FOLDER . DS . $id . DS . self::FILE_NAME;
     }
    /**
     * 
     */
    public function insert(array $columns, array $data)
    {
        return $this->insertDb(self::schema, Table::MEET_PROPOSAL, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::MEET_PROPOSAL, $columns, $data, $id);
    }
    /**
     * 
     */
    public function getAllData($sql = "", $data_in = [], $select_in=[],$group_by = "", $order_by = "", $count = false)
    {
        $from = Table::MEET_PROPOSAL . " t1 
        INNER JOIN " . Table::USERS . " t2 ON t1.created_by = t2.ID ";
        $select = ["t1.*,t2.ename as created_by"];
        if(!empty($select_in)){
            $select = $select_in;
        }
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], $count);
    }
    /**
     * 
     */
    public function getOneData($id)
    {
        $from = Table::MEET_PROPOSAL . " t1 
        INNER JOIN " . Table::USERS . " t2 ON t1.created_by = t2.ID ";
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
        $from = Table::MEET_PROPOSAL;
        $this->deleteId($from,$id);
    }
    //CommitteeMomReport
//     public function CommitteeMomReport($start_date, $end_date, $subcategory)
// {
//     $select = [
//         "t1.title",
//         "t1.description",
//         "t1.doc_loc",
//         "t1.app_id",
//         "t1.app_date",
//         "t1.app_remarks",
//         "t1.status",
//         "t1.mom_type",
//         "t1.created_by",
//         "t1.created_time",
//         "t2.ename"
//     ];

//     $from = Table::MEET_PROPOSAL. " t1 
//             LEFT JOIN " . Table::USERS . " t2 ON t1.created_by = t2.ID"; 

//     $sql = "DATE(t1.created_time) BETWEEN :start_date AND :end_date AND mom_type = :subcategory";

//     $order_by = "t1.created_time DESC";

//     $data_in = [
//         "start_date" => $start_date,
//         "end_date" => $end_date,
//         "subcategory" => $subcategory
//     ];

//     $single = false;
//     $limit = [];
//     $count = false;

//     $data = $this->getAll($select, $from, $sql, "", $order_by, $data_in, $single, $limit, $count);
//     return $data;
// }
public function CommitteeMomReport($start_date, $end_date, $subcategory)
{
    $select = [
        "t1.title",
        "t1.description",
        "t1.doc_loc",
        "t1.app_id",
        "t1.app_date",
        "t1.app_remarks",
        "t1.status",
        "t1.mom_type",
        "t1.created_by",
        "t1.created_time",
        "t2.ename",
        "t3.member_role_id",
        "t3.admin_role_id"
    ];

    $from = Table::MEET_PROPOSAL . " t1
            LEFT JOIN " . Table::USERS . " t2 ON t1.created_by = t2.ID
            LEFT JOIN " . Table::MOMTYPES . " t3 ON t1.mom_type = t3.type";

    $sql = "DATE(t1.created_time) BETWEEN :start_date AND :end_date AND t1.mom_type = :subcategory";

    $order_by = "t1.created_time DESC";

    $data_in = [
        "start_date" => $start_date,
        "end_date" => $end_date,
        "subcategory" => $subcategory
    ];

    $single = false;
    $limit = [];
    $count = false;

    $data = $this->getAll($select, $from, $sql, "", $order_by, $data_in, $single, $limit, $count);
    return $data;
}


   
    
}
