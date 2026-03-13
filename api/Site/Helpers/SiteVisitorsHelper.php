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
class SiteVisitorsHelper extends BaseHelper
{

    const schema = [
        "ip_address" => SmartConst::SCHEMA_VARCHAR,
        "visit_date" => SmartConst::SCHEMA_DATE,
        "created_at" => SmartConst::SCHEMA_CDATETIME
    ];
    /**
     * 
     */
    const validations = [
        "visit_date" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter visit_date"
            ],
        
        ],
        
    ];

      

    /**
     * 
     */
    public function insert(array $columns, array $data)
    {
        return $this->insertDb(self::schema, Table::SITE_VISITORS, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::SITE_VISITORS, $columns, $data, $id);
    }
    /**
     * 
     */
    public function getAllData($sql = "", $data_in = [],$select=[],$group_by = "", $count = false,$single=false)
    {
        $from = Table::SITE_VISITORS;
        $select = !empty($select) ? $select : ["*"];
       // $order_by="last_modified_time DESC";
        return $this->getAll($select, $from, $sql, $group_by, "", $data_in, $single, [], $count);
    }
    

    /**
     * 
     */
    public function getOneData($id)
    {
        $from = Table::SITE_VISITORS;
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
        $from = Table::SITE;
        $this->deleteId($from,$id);
    }

   
public function logUniqueVisitor($ip)
{
    $today = date('Y-m-d');

    // Check if visitor already exists for today
    $sql = "ip_address = :ip AND visit_date = :visit_date";
    $data_in = [
        "ip" => $ip,
        "visit_date" => $today
    ];

    $existing = $this->getAll(["*"], Table::SITE_VISITORS, $sql, "", "", $data_in, true);

    // If not exists, insert new
    if (!$existing) {
        $columns = ["ip_address", "visit_date"];
        $data = [
            "ip_address" => $ip,
            "visit_date" => $today
        ];
        $this->insert($columns, $data);
    }
}
public function getTodayVisitorCount()
{
    $today = date('Y-m-d');

    $sql = "visit_date = :visit_date";
    $data_in = ["visit_date" => $today];

    return $this->getAll(["COUNT(*) as count"], Table::SITE_VISITORS, $sql, "", "", $data_in, true);
}

public function getTotalVisitorCount()
{
    $today = date('Y-m-d');

    $sql = "visit_date = :visit_date";
    $data_in = ["visit_date" => $today];

    return $this->getAll(["COUNT(*) as count"], Table::SITE_VISITORS, "", "", "", [], true);
}



  
}
