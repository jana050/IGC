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
class ImportHelper extends BaseHelper
{
    const schema = [
        "sd_import_type" => SmartConst::SCHEMA_VARCHAR,
        "sd_file" => SmartConst::SCHEMA_VARCHAR,
        "sd_mt_userdb_id" => SmartConst::SCHEMA_CUSER_ID,
    ];
    /**
     * 
     */
    const validations = [];
    /**
     * 
     */
    public function insert(array $columns, array $data)
    {
        return $this->insertDb(self::schema, Table::SD_IMPORT, $columns, $data);
    }
    /**
     * 
     */
    public function update(array $columns, array $data, int $id)
    {
        return $this->updateDb(self::schema, Table::SD_IMPORT, $columns, $data, $id);
    }

    public function insertData($type)
    {
        // insert data in import helper 
        $columns = ["sd_import_type", "sd_mt_userdb_id"];
        $data = ["sd_import_type" => $type];
        return $this->insert($columns, $data);
    }

    public function updatePath($id, $path)
    {
        // insert data in import helper 
        $columns = ["sd_file"];
        $data = ["sd_file" => $path];
        return $this->update($columns, $data, $id);
    }

    /**
     * 
     */
    public function getOneData($id)
    {
        $from = Table::SD_IMPORT . " t1";
        ;
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
        $from = Table::SD_IMPORT;
        $this->deleteId($from, $id);
    }

    public function importColumnsAttendance()
    {
        $columns = [
            [
                "letter" => "A",
                "index" => "sd_employees_id",
                "empty" => true
            ],
            [
                "letter" => "B",
                "index" => "date",

            ],
            [
                "letter" => "C",
                "index" => "check_in_time",

            ],
            [
                "letter" => "D",
                "index" => "check_out_time",

            ]
        ];
        return $columns;
    }

    public function importColumnsAssets()
    {
        $columns = [
            [
                "letter" => "A",
                "index" => "asset_type",
            ],
            [
                "letter" => "B",
                "index" => "asset_name",

            ],
            [
                "letter" => "C",
                "index" => "asset_number",

            ],
            [
                "letter" => "D",
                "index" => "asset_details",


            ],
            [
                "letter" => "E",
                "index" => "assert_return",


            ],
            [
                "letter" => "F",
                "index" => "asset_condition",


            ]
        ];
        return $columns;
    }
    public function importColumnsEmployees()
    {
        $columns = [
            [
                "letter" => "A",
                "index" => "emp_type",
            ],
            [
                "letter" => "B",
                "index" => "employee_id",

            ],
            [
                "letter" => "C",
                "index" => "epassword",

            ],
            [
                "letter" => "D",
                "index" => "ename",

            ],
            [
                "letter" => "E",
                "index" => "first_name",

            ],
            [
                "letter" => "F",
                "index" => "last_name",

            ],
            [
                "letter" => "G",
                "index" => "sd_designations_id",

            ],
            [
                "letter" => "H",
                "index" => "email",

            ],
            [
                "letter" => "I",
                "index" => "phone",

            ],
            [
                "letter" => "R",
                "index" => "emp_dob",

            ],
            [
                "letter" => "S",
                "index" => "joining_date",

            ],
            [
                "letter" => "T",
                "index" => "having_login",

            ],
            [
                "letter" => "U",
                "index" => "attendance_app",

            ],
            [
                "letter" => "V",
                "index" => "last_login_time",

            ],
            [
                "letter" => "W",
                "index" => "last_login_ip",

            ],
            [
                "letter" => "X",
                "index" => "last_reset_time",

            ],
            [
                "letter" => "Y",
                "index" => "change_pass",

            ],
            [
                "letter" => "Z",
                "index" => "aadhar_number",

            ],
            [
                "letter" => "AA",
                "index" => "pan_number",

            ],
            [
                "letter" => "AB",
                "index" => "last_reset_time",

            ],
            [
                "letter" => "AC",
                "index" => "address",

            ],
            [
                "letter" => "AE",
                "index" => "day_deduction",

            ],
            [
                "letter" => "AF",
                "index" => "exit_date",

            ],
            [
                "letter" => "AG",
                "index" => "emp_qualification",

            ],
            [
                "letter" => "AH",
                "index" => "emp_degree",

            ],
            [
                "letter" => "AI",
                "index" => "emp_experience",

            ],


        ];
        return $columns;
    }




}
