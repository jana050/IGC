<?php

namespace Site\Helpers;

use Core\BaseHelper;
use Core\Helpers\SmartConst;

use Site\Helpers\TableHelper as Table;

/**
 * Requisition Types - free-text custom types with an Admin/Supervisor
 * role assignment each, mirroring CommanComplaintHelper's Complaint Types
 * section but backed by its own dedicated table.
 */
class RequisitionTypeHelper extends BaseHelper
{

    const schema = [
        "requisition_type" => SmartConst::SCHEMA_VARCHAR,
        "requisition_admin" => SmartConst::SCHEMA_INTEGER,
        "requisition_supervisor" => SmartConst::SCHEMA_INTEGER,
        "created_time" => SmartConst::SCHEMA_CDATETIME,
    ];

    const validations = [
        "requisition_type" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Enter Requisition Type"
            ]
        ],
        "requisition_admin" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Specify the Requisition's Administrator"
            ]
        ],
        "requisition_supervisor" => [
            [
                "type" => SmartConst::VALID_REQUIRED,
                "msg" => "Please Specify the Requisition's Supervisor"
            ]
        ],
    ];

    public function insertRequisitionTypes(array $columns, array $data)
    {
        return $this->insertDb(self::schema, Table::REQUISITIONTYPES, $columns, $data);
    }

    public function updateRequisitionTypes(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::REQUISITIONTYPES, $columns, $data, $id);
    }

    public function getAllDataRequisitionTypes($sql = "", $data_in = [], $group_by = "", $count = false)
    {
        $from = Table::REQUISITIONTYPES . " t1
        LEFT JOIN " . Table::ROLES . " t2 ON t1.requisition_admin=t2.ID
        LEFT JOIN " . Table::ROLES . " t3 ON t1.requisition_supervisor=t3.ID";
        $select = ["t1.*,t2.role_name AS requisition_admin,t3.role_name AS requisition_supervisor"];
        $order_by = "created_time DESC";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], $count);
    }

    public function getAllDataRequisitionTypesDropDown($sql = "", $data_in = [], $group_by = "", $count = false)
    {
        $from = Table::REQUISITIONTYPES;
        $select = ["ID as value,requisition_type as label"];
        $order_by = "created_time DESC";
        return $this->getAll($select, $from, $sql, $group_by, $order_by, $data_in, false, [], $count);
    }

    public function getOneDataRequisitionTypes($id)
    {
        $from = Table::REQUISITIONTYPES;
        $select = ["*"];
        $sql = "ID=:ID";
        $data_in = ["ID" => $id];
        return $this->getAll($select, $from, $sql, "", "", $data_in, true, []);
    }

    public function deleteOneIdRequisitionTypes($id)
    {
        $from = Table::REQUISITIONTYPES;
        $this->deleteId($from, $id);
    }

    public function checkRoleExist($type)
    {
        $from = Table::REQUISITIONTYPES;
        $select = ["ID"];
        $sql = "requisition_admin=:requisition_admin";
        $data_in = ["requisition_admin" => $type];
        return $this->getAll($select, $from, $sql, "", "", $data_in, true, []);
    }

    public function checkSupervisorRoleExist($type)
    {
        $from = Table::REQUISITIONTYPES;
        $select = ["ID"];
        $sql = "requisition_supervisor=:requisition_supervisor";
        $data_in = ["requisition_supervisor" => $type];
        return $this->getAll($select, $from, $sql, "", "", $data_in, true, []);
    }
}
