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
use Core\Helpers\SmartData as Data;
use Core\Helpers\SmartSiteSettings as Settings;

//
use Site\Helpers\TableHelper as Table;

/**
 * Description of Data
 * 
 *  class helps to get the data from post with specified type 
 *
 * @author kms
 */
class DocumentHelper extends BaseHelper
{
    
    const schema = [
        "doc_title" => SmartConst::SCHEMA_VARCHAR,
        "doc_type" => SmartConst::SCHEMA_VARCHAR,
        "doc_loc" => SmartConst::SCHEMA_VARCHAR,
        "doc_category"=>SmartConst::SCHEMA_VARCHAR,
        "doc_main_cat"=>SmartConst::SCHEMA_VARCHAR,
        "wo_no"=>SmartConst::SCHEMA_VARCHAR,
        "wo_value"=>SmartConst::SCHEMA_VARCHAR,
        "wo_name"=>SmartConst::SCHEMA_VARCHAR,
        "wo_type"=>SmartConst::SCHEMA_VARCHAR,
        "wo_start_from"=>SmartConst::SCHEMA_VARCHAR,
        "wo_start_to"=>SmartConst::SCHEMA_VARCHAR,
        "contractor_name"=>SmartConst::SCHEMA_VARCHAR,
        "work_type"=>SmartConst::SCHEMA_VARCHAR,
        "doc_status" => SmartConst::SCHEMA_INTEGER,
        "created_by" => SmartConst::SCHEMA_CUSER_ID,
        "app_id" => SmartConst::SCHEMA_INTEGER,
        "created_time" => SmartConst::SCHEMA_CDATETIME,
        "updated_by" => SmartConst::SCHEMA_CUSER_ID,
        "last_modified_time" => SmartConst::SCHEMA_CTIME,
        "entering_keyword"=>SmartConst::SCHEMA_VARCHAR,
    ];
    /**
     * 
     */
   const validations = [
      "doc_title" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Document Title"
            ],
            [
                "type" => SmartConst::VALID_MAX_LENGTH,
                "max"=>1000,
                "msg"=>"Document Title Max character 1000"
            ]
        ],
        "doc_type" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Document Type"
            ]
        ],
        "doc_category" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter doc_category"
            ]
        ],
        "doc_status" =>[
            [
                "type" => SmartConst::VALID_NUM,
                "msg" => "Please enter a status"
            ]
        ],
        "app_id" =>[
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please select Approving Authority"
            ]
        ],    
        "doc_main_cat" =>[
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Select Main Categeory"
            ]
        ], 
        "doc_auth" =>[
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Select Authors"
            ],
            [
                "type" => SmartConst::VALID_MULTIPLE,
                "msg" => "Please Select Authors"
            ]
        ],    
        "uploaded_file" => [
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
                "ext"=>["pdf"]
            ]
        ],
        "entering_keyword" =>[
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Select entering_keyword"
            ]
        ], 
    ];

    public function getValidations(){
        $validations = self::validations;
        // push dynamic size validations
        $size = Settings::getSetting("doc_max_size",2);
        $validation_size = [
            "type" => SmartConst::VALID_FILE_SIZE,
            "msg" => "The Document Size Should be maximum ".$size." MB",
            "size"=>[0,$size]
        ];
        $validations["uploaded_file"] = array_merge($validations["uploaded_file"],$validation_size);
        return $validations;
    }

     // file handling 
   const FILE_FOLDER = "docs";
   const FILE_NAME = "file";
    //
    public function getFullFile($id){
        return self::FILE_FOLDER . DS . $id . DS . self::FILE_NAME;
    }
    /**
     * 
     */
    public function insert(array $columns, array $data)
    {
        return $this->insertDb(self::schema, Table::DOCS, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::DOCS, $columns, $data, $id);
    }
    /**
     * 
     */
    public function getAllData($sql = "", $data_in = [], $group_by = "", $count = false)
    {
        $from = Table::DOCS ." t1 
        LEFT JOIN ".Table::DOCCATEGORY." t2 ON t1.doc_type=t2.ID 
        INNER JOIN " . Table::USERS . " t3 ON t1.created_by = t3.ID";
        $select = ["t1.*,t2.doc_type as doc_type,t3.ename as created_by"];
        $order_by="t1.created_time DESC";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], $count);
    }
    /**
     * 
     */
    public function getOneData($id)
    {
        $from = Table::DOCS ." t1 
        LEFT JOIN ".Table::DOCCATEGORY." t2 ON t1.doc_type=t2.ID 
        INNER JOIN " . Table::USERS . " t3 ON t1.created_by = t3.ID";
        $select = ["t1.*,t2.doc_type as doc_type,t3.ename as user_name"];
        $sql = "t1.ID=:ID";
        $data_in = ["ID" => $id];
        $group_by = "";
        $order_by="t1.created_time DESC";
        $data =  $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, true, []);
        if(isset($data->ID)){
            $data->authors = $this->getAuthors($data->ID);
        }
        return $data;
    }
    /**
     * 
     */
    public function getDocWithUserID($id)
    {
        $from = Table::DOCS;
        $select = ["*"];
        $sql = "created_by=:ID";
        $data_in = ["ID" => $id];
        $group_by = "";
        $order_by="created_time DESC";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, true, []);
    }
     /**
     * 
     */
    public function deleteOneId($id)
    {
        $from = Table::DOCS;
        $this->deleteId($from,$id);
    }

    public function getCount($type){
        $sql = "DATE(t1.created_time)=CURRENT_DATE()";
        if($type==1){
            $sql = "MONTH(t1.created_time)=" . SmartGeneral::getMonth();
        }else if($type==2){
            $sql = "YEAR(t1.created_time)=" . SmartGeneral::getYear();
        }
        $data =  $this->getAllData($sql,[],"",true);
        return isset($data) && isset($data[0]) ? $data[0]->total_count : 0;
    }


    public function getCountByYear($year){
        $select = ["COUNT(doc_title) AS doc, MONTH(last_modified_time) AS month"];
        $from = Table::DOCS;
        $sql ="YEAR(last_modified_time) =:year";
        $group_by = "MONTH(last_modified_time)";    // No GROUP BY keyword
        $order_by ="month";
        $data_in =["year" => $year];
        $count = $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], false);
        $doc_count = array_fill(0, 12, 0);
        foreach ($count as $doc) {
            $month = intval($doc->month);
            $docCount = intval($doc->doc);
            $doc_count[$month - 1] = $docCount;
        }
        $doc_count_by_year = array_values($doc_count);
        return $doc_count_by_year;
    }
    
    public function getCountByStatus()
    {
        $sql = "doc_status=10";
        $data =  $this->getAllData($sql,[] , "", true);
        return isset($data) && isset($data[0]) ? $data[0]->total_count : 0;
    }

    
    public function getDocType($id,$doc_cat)
    {
        $from = Table::DOCS ." t1 LEFT JOIN 
        ".Table::TYPE." t2 ON t1.doc_type=t2.ID 
        INNER JOIN " . Table::USERS . " t3 ON t1.created_by = t3.ID";
        $select = ["t1.*,t2.type_value as doc_type,t3.ename as created_by"];
       // $from = Table::DOCS;
      //  $select = ["*"];
        $sql = "t1.doc_status=10 AND doc_main_cat=:main_cat";
        $data_in = ["main_cat"=>$doc_cat];
        if($id > 0){
            $sql .= " AND t1.doc_type=:ID";
            $data_in = ["main_cat"=>$doc_cat,"ID" => $id];
        }
        $order_by="t1.created_time DESC";
        return $this->getAll($select, $from, $sql, "", $order_by, $data_in, false, []);
    }

    
    public function getDocByCategoryAndType($category,$type)
    {
        $from = Table::DOCS ." t1 LEFT JOIN ".Table::TYPE." t2 ON t1.doc_type=t2.ID 
        INNER JOIN " . Table::USERS . " t3 ON t1.created_by = t3.ID";
        $select = ["t1.*,t2.type_value as doc_type,t3.ename as created_by"];
        $order_by="t1.created_time DESC";
        $sql = "t1.doc_category=:category AND t1.doc_status=10 AND t1.doc_type=:type";
        $data_in = ["category" => $category,"type"=> $type];
        $group_by = "";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, []);
    }



    public function reportCount($year){
        $select = ["COUNT(doc_title) AS doc, MONTH(last_modified_time) AS month"];
        $from = Table::DOCS;
        $sql ="YEAR(last_modified_time) =:year AND doc_type =:type";
        $group_by = "MONTH(last_modified_time)";    // No GROUP BY keyword
        $order_by ="month";
        $data_in =["year" => $year,"type"=>33];
        $count = $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], false);
        $doc_count = array_fill(0, 12, 0);
        foreach ($count as $doc) {
            $month = intval($doc->month);
            $docCount = intval($doc->doc);
            $doc_count[$month - 1] = $docCount;
        }
        $doc_count_by_year = array_values($doc_count);
        return $doc_count_by_year;
    }


    public function journalCount($year){
        $select = ["COUNT(doc_title) AS doc, MONTH(last_modified_time) AS month"];
        $from = Table::DOCS;
        $sql ="YEAR(last_modified_time) =:year AND doc_type =:type";
        $group_by = "MONTH(last_modified_time)";    // No GROUP BY keyword
        $order_by ="month";
        $data_in =["year" => $year,"type"=>34];
        $count = $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], false);
        $doc_count = array_fill(0, 12, 0);
        foreach ($count as $doc) {
            $month = intval($doc->month);
            $docCount = intval($doc->doc);
            $doc_count[$month - 1] = $docCount;
        }
        $doc_count_by_year = array_values($doc_count);
        return $doc_count_by_year;
    }




    public function conferenceCount($year){
        $select = ["COUNT(doc_title) AS doc, MONTH(last_modified_time) AS month"];
        $from = Table::DOCS;
        $sql ="YEAR(last_modified_time) =:year AND doc_type =:type";
        $group_by = "MONTH(last_modified_time)";    // No GROUP BY keyword
        $order_by ="month"; 
        $data_in =["year" => $year,"type"=>35];
        $count = $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], false);
        $doc_count = array_fill(0, 12, 0);
        foreach ($count as $doc) {
            $month = intval($doc->month);
            $docCount = intval($doc->doc);
            $doc_count[$month - 1] = $docCount;
        }
        $doc_count_by_year = array_values($doc_count);
        return $doc_count_by_year;
    }

    public function getTypeCount($year,$type){
        $select = ["COUNT(t1.doc_title) AS doc, MONTH(t1.last_modified_time) AS month"];      
        $from = Table::DOCS ." t1";
        $sql ="YEAR(t1.last_modified_time) =:year AND t1.doc_type =:type";
        $group_by = "MONTH(last_modified_time)";    // No GROUP BY keyword
        $order_by ="month"; 
        $data_in =["year" => $year,"type"=>$type];
        $count = $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], false);
        $doc_count = array_fill(0, 12, 0);
        foreach ($count as $doc) {
            $month = intval($doc->month);
            $docCount = intval($doc->doc);
            $doc_count[$month - 1] = $docCount;
        }
        $doc_count_by_year = array_values($doc_count);
        return $doc_count_by_year;
    }

    public function insertAuthors(int $doc_id,$data)
    {
        // delete existing authors with doc id
        $this->deleteBySql(Table::DOC_AUTHORS,"doc_id=:uid",["uid"=>$doc_id]);
        // columns
        $columns = ["doc_id","author_id","created_time","created_by"];   
        // var_dump($data);    
        foreach($data as $single_data){
            $data_in = [];
            $data_in["doc_id"] = $doc_id;
            $data_in["author_id"] = isset($single_data["value"]) ? $single_data["value"] : 0;          
            $this->insertDb(self::schema, Table::DOC_AUTHORS, $columns, $data_in);
        }       
    }

    public function getAuthors(int $doc_id){
        $from = Table::DOC_AUTHORS ." t1 
        INNER JOIN ".Table::USERS." t2 ON t1.author_id=t2.ID" ;
        $select = ["t1.*,t2.ename as author_name"];
        $sql = "t1.doc_id=:doc_id";
        $data_in = ["doc_id"=>$doc_id];
        return $this->getAll($select, $from, $sql, "", "", $data_in, false, []);
    }

    public function checkAuthor(int $doc_id,int $auth_id){
        $from = Table::DOC_AUTHORS ." t1" ;
        $select = ["t1.ID"];
        $sql = "t1.doc_id=:doc_id AND t1.author_id=:auth_id";
        $data_in = ["doc_id"=>$doc_id,"auth_id"=>$auth_id];
        $data_out =  $this->getAll($select, $from, $sql, "", "", $data_in, true, []);
        return isset($data_out->ID) ? true : false;
    }
    //
    public function DocumentReport($start_date, $end_date)
    {
    $select = [
        "t1.doc_title",
        "t1.doc_type",
        "t1.doc_loc",
        // "t1.doc_category",
        "t1.doc_main_cat",
        "t1.wo_no",
        "t1.wo_value",
        "t1.wo_name",
        "t1.wo_type",
        "t1.wo_start_from",
        "t1.wo_start_to",
        "t1.contractor_name",
        "t1.work_type",
        "t1.doc_status",
        "t1.created_by",
        "t1.app_id",
        "t1.created_time",
        "t1.updated_by",
        "t1.last_modified_time",
        "t2.ename"
    ];

    $from = Table::DOCS . " t1
            LEFT JOIN " . Table::USERS . " t2 ON t1.created_by = t2.ID";

    $sql = "DATE(t1.created_time) BETWEEN :start_date AND :end_date";

    $order_by = "t1.created_time DESC";

    $data_in = [
        "start_date" => $start_date,
        "end_date" => $end_date
    ];

    $single = false;
    $limit = [];
    $count = false;

    $data = $this->getAll($select, $from, $sql, "", $order_by, $data_in, $single, $limit, $count);
    return $data;
    }


}
