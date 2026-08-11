<?php

namespace Site\Helpers;

use Core\BaseHelper;
use Core\Helpers\SmartConst;

use Site\Helpers\TableHelper as Table;

/**
 * Ticket-level Custom Requisition workflow (My Requisition / Admin /
 * Supervisor), mirroring CommanComplaintHelper's Complaint Table section
 * exactly - same columns, same supervisor join pattern - just backed by
 * sd_requisitions instead of sd_complaints.
 */
class RequisitionHelper extends BaseHelper
{

    const schema = [
        "type" => SmartConst::SCHEMA_VARCHAR,
        "title" => SmartConst::SCHEMA_VARCHAR,
        "description" => SmartConst::SCHEMA_TEXT,
        "free_material" => SmartConst::SCHEMA_INTEGER,
        "free_description" => SmartConst::SCHEMA_TEXT,
        "doc_loc" => SmartConst::SCHEMA_VARCHAR,
        "location" => SmartConst::SCHEMA_TEXT,
        "sd_mt_userdb_id" => SmartConst::SCHEMA_CUSER_ID,
        "created_time" => SmartConst::SCHEMA_CDATETIME,
        "app_id" => SmartConst::SCHEMA_INTEGER,
        "status" => SmartConst::SCHEMA_INTEGER,
        "last_modified_by" => SmartConst::SCHEMA_CUSER_ID,
        "last_modified_remarks" => SmartConst::SCHEMA_TEXT,
        "last_modified_time" => SmartConst::SCHEMA_CTIME,
        "date_of_closure" => SmartConst::SCHEMA_DATE,
        "supervisor_description" => SmartConst::SCHEMA_TEXT,
        "supervisor" => SmartConst::SCHEMA_INTEGER,
    ];

    const validations = [
        "type" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Select Requisition's Type"
            ]
        ],
        "title" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Name"
            ],
            [
                "type" => SmartConst::VALID_MAX_LENGTH,
                "max" => 1000,
                "msg" => "Name Max character 1000"
            ]
        ],
        "description" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Nature of Work"
            ]
        ],
        "free_material" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please choose Free Issue Material"
            ]
        ],
        "uploaded_file" => [
            [
                "type" => SmartConst::VALID_FILE_TYPE,
                "msg" => "Only pdf, doc, docx are allowed",
                "ext" => ["pdf", "doc", "docx"]
            ]
        ],
        "app_id" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Select Approver"
            ]
        ],
        "status" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter status"
            ]
        ],
        "last_modified_remarks" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter remarks"
            ]
        ],
    ];

    // file handling - mirrors WorkshopHelper's convention, but the
    // stored filename (with its real extension) is kept in doc_loc so
    // downloads aren't hardcoded to .pdf like Workshop's are.
    const FILE_FOLDER = "requisition";
    const FILE_NAME = "file";

    public function getFullFile($id)
    {
        return self::FILE_FOLDER . DS . $id . DS . self::FILE_NAME;
    }

    public function insert(array $columns, array $data)
    {
        return $this->insertDb(self::schema, Table::REQUISITIONS, $columns, $data);
    }

    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::REQUISITIONS, $columns, $data, $id);
    }

    public function getAllData($sql = "", $data_in = [], $group_by = "", $count = false)
    {
        $from = Table::REQUISITIONS . " t1
        INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID
        LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.supervisor";
        $select = ["t1.*,t2.ename as created_by", "t11.ename as supervisor_name"];
        $order_by = "t1.created_time DESC";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], $count);
    }

    public function getOneData($id)
    {
        $from = Table::REQUISITIONS . " t1
        INNER JOIN " . Table::USERS . " t2 ON t1.sd_mt_userdb_id = t2.ID
        LEFT JOIN " . Table::USERS . " t11 ON t11.ID = t1.supervisor";
        $select = ["t1.*,t2.ename as created_by", "t11.ename as supervisor_name"];
        $sql = "t1.ID=:ID";
        $data_in = ["ID" => $id];
        return $this->getAll($select, $from, $sql, "", "", $data_in, true, []);
    }

    public function deleteOneId($id)
    {
        $from = Table::REQUISITIONS;
        $this->deleteId($from, $id);
    }
}
