<?php

namespace Site\Controller;

use Core\BaseController;

use Core\Helpers\SmartAuthHelper;
use Core\Helpers\SmartData;
use Core\Helpers\SmartFileHelper;
use Core\Helpers\SmartExcellHelper;
use Site\Helpers\ImportHelper;
use Site\Helpers\OrganisationHelper as OrganisationHelper;
use Site\Helpers\UserHelper;


class OrganisationController extends BaseController
{

    private OrganisationHelper $_org_helper;
    private UserHelper $_user_helper;
    private ImportHelper $_import_helper;
    private $_apps = [];

    function __construct($params)
    {
        parent::__construct($params);
        // 
        $this->_org_helper = new OrganisationHelper($this->db);
        // 
        $this->_user_helper = new UserHelper($this->db);

        $this->_import_helper = new ImportHelper($this->db);
    }

    /**
     * 
     */
    public function insert()
    {
        $columns = ["sd_org_name", "sd_org_type_id", "sd_mt_userdb_id", "sd_org_short_label"];
        // do validations
        $this->_org_helper->validate(OrganisationHelper::validations, $columns, $this->post);
        // generate full_label

        // add other columns
        $columns[] = "sd_org_id";
        $columns[] = "sd_org_short_label";
        $columns[] = "sd_org_full_label";
        $columns[] = "created_by";
        $columns[] = "created_time";

        // insert and get id
        $id = $this->_org_helper->insert($columns, $this->post);
        // add log
        $this->addLog("ADDED A MEMBER TO ORGANIZATION", "", SmartAuthHelper::getLoggedInUserName());
        //
        $this->response($id);
    }
    /**
     * 
     */
    public function update()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $columns = ["sd_org_name", "sd_org_type_id", "sd_org_id", "sd_mt_userdb_id"];
        // do validations
        $this->_org_helper->validate(OrganisationHelper::validations, $columns, $this->post);
        // add other columns
        $columns[] = "last_modified_time";
        // insert and get id
        $this->_org_helper->update($columns, $this->post, $id);
        // add log
        $this->addLog("UPDATED ORGANIZATION MEMBER", "", SmartAuthHelper::getLoggedInUserName());
        //
        $this->response($id);
    }

    public function getAll()
    {
        $data = $this->_org_helper->getAllData();
        $this->response($data);
    }
    /**
     * 
     */
    public function getAllTree()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        $type = $id < 1 ? "INIT" : "SECTION";
        $data =  $this->getSingleTree($id, $type);
        if ($type == "SECTION") {
            $parent_data = $this->_org_helper->getOneData($id);
            $parent_data->children = $data;
            $this->response([$parent_data]);
        }
        $this->response($data);
    }

    private function getSingleTree($id, $type)
    {
        $data_in = ["ID" => $id];
        $sql = "t1.sd_org_id=:ID";
        if ($type == "INIT") {
            $sql .= " AND t1.sd_org_type_id > 12";
        }
        $data =  $this->_org_helper->getAllData($sql, $data_in);
        foreach ($data as $key => $obj) {
            $children = $this->getSingleTree($obj->ID, $type);
            if ($children) {
                $obj->children =  $children;
            }
            $data[$key] = $obj;
        }
        return $data;
    }
    /**
     * 
     */
    public function getAllAssociateParents()
    {
        $userid = SmartAuthHelper::getLoggedInUserId();
        $org_id =  $this->_user_helper->getOneDataWithUserId($userid);
        // var_dump($org_id);
        $this->_apps = [];
        $data = $this->getBackAuthPattern($org_id->sd_org_id);
        $this->response($data);
    }
    /**
     * 
     */
    public function getBackAuthPattern($id)
    {

        $data_in = ["ID" => $id];
        $sql = "t1.ID=:ID";
        $data = $this->_org_helper->getAllAuthParent($sql, $data_in);
        $this->_apps[] = $data;
        if (intval($data->sd_org_id) > 0) {
            $this->getBackAuthPattern($data->sd_org_id);
        }
        return $this->_apps;
    }
    /**
     * 
     */
    public function getOne()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        // insert and get id
        $data = $this->_org_helper->getOneData($id);
        $this->response($data);
    }
    /**
     * 
     */
    public function deleteOne()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $orgdb_check = $this->_org_helper->getUserWithChild($id, "org");
        $userdb_check = $this->_org_helper->getUserWithChild($id, "user");
        if (!empty($orgdb_check) || !empty($userdb_check)) {
            \CustomErrorHandler::triggerInvalid("can't delete Parent");
        }
        // delete
        $this->_org_helper->deleteOneId($id);
        // add log
        $this->addLog("DELETED A MEMBER FROM ORGANIZATION", "", SmartAuthHelper::getLoggedInUserName());
        //
        $out = new \stdClass();
        $out->msg = "Deleted Successfully";
        $this->response($out);
    }


    public function getAllParent()
    {
        $data = $this->_org_helper->getAllParents();
        $this->response($data);
    }

    public function initChangeLabel()
    {
        // change label
        $this->change_label_new(13, "MCMFCG");
        $this->responseMsg("Labels updated");
    }
    private function change_label_new($id = 0, $label = "")
    {
        $data_in = ["ID" => $id];
        $sql = "sd_org_id=:ID";
        $data =  $this->_org_helper->getAllOrg($sql, $data_in);
        foreach ($data as $key => $obj) {
            $new_label = ($label !== "") ? $label . "/" . $obj->sd_org_short_label : $obj->sd_org_short_label;
            $this->_org_helper->update_label($obj->ID, $new_label);
            $this->change_label_new($obj->ID, $new_label);
        }
    }

    public function getAllSelect()
    {
        $mode = isset($this->params["mode"]) ? $this->params["mode"] : "SH";
        $data = $this->_org_helper->getOrgHead($mode);
        $this->response($data);
    }

    public function getOrgExcel()
    {
        $out = $this->_org_helper->getOrgTotal();
        $full_label = "";
        foreach ($out as $key => $obj) {
            // var_dump($obj);
            if ($full_label == $obj->sd_org_full_label) {
                $obj->sd_org_full_label = "";
                $obj->sd_org_type_id = "";
                $obj->head_id = "";
            }
            $full_label = $obj->sd_org_full_label;
        }
        $this->response($out);
    }

    public function importOrganisationExcel()
    {
        $excel_import = SmartData::post_array_data("file");
        if (!is_array($excel_import) || count($excel_import) < 1) {
            \CustomErrorHandler::triggerInvalid("Please upload an Excel file to Import");
        }

        $content = isset($excel_import["content"]) ? $excel_import["content"] : "";
        if (strlen($content) < 10) {
            \CustomErrorHandler::triggerInvalid("Please upload a valid Excel file");
        }

        $insert_id = $this->_import_helper->insertData("ORGANISATION");
        $store_path = "excel_import" . DS . $insert_id . DS . "organisation.xlsx";
        $dest_path = SmartFileHelper::storeFile($content, $store_path);
        $this->_import_helper->updatePath($insert_id, $store_path);
        //  $dest_path = "D:\sample_sd_org_structure.xlsx";
        $excel = new SmartExcellHelper($dest_path, 0);
        $excel->init_excel();

        $out = [];
        $k = 0;
        for ($i = 2; $i <= $excel->get_last_row(); $i++) {
            $org_type = $excel->get_cell_value("B", $i);
            $org_full_label = $excel->get_cell_value("C", $i);
            $head_id = $excel->get_cell_value("D", $i);
            $emp_id = $excel->get_cell_value("E", $i);
            $org_data = [
                "org_type" => $org_type,
                "org_full_label" => $org_full_label,
                "head_id" => intval($head_id),
                "emp_id" => intval($emp_id),
            ];

            // // Validation
            // if (empty($org_data["sd_org_name"])) {
            //     $org_data["status"] = 5;
            //     $org_data["msg"] = "Organization Name is required";
            // } elseif ($org_data["sd_org_type_id"] < 1) {
            //     $org_data["status"] = 5;
            //     $org_data["msg"] = "Invalid Organization Type ID";
            // } elseif (empty($org_data["sd_org_short_label"])) {
            //     $org_data["status"] = 5;
            //     $org_data["msg"] = "Short Label is required";
            // } else {
            //     // Generate full label
            //     $org_data["sd_org_full_label"] = $org_data["sd_org_short_label"];

            //     $this->db->_db->Begin();
            //     $insert_status = $this->_org_helper->insert(["sd_org_name", "sd_org_type_id", "sd_org_short_label", "sd_org_id", "sd_org_full_label", "sd_mt_userdb_id", "created_by", "created_time"], $org_data);
            //     $this->db->_db->commit();

            //     if (!$insert_status) {
            //         $org_data["status"] = 5;
            //         $org_data["msg"] = "Failed to insert organization";
            //     } else {
            //         $org_data["status"] = 1;
            //         $org_data["msg"] = "Inserted successfully";
            //     }
            // }

            $out[$k] = $org_data;
            $k++;
        }





        $this->response($out);
    }
}
