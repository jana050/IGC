<?php

namespace Site\Controller;

use Core\BaseController;
use Core\Helpers\SmartData;
use Site\Helpers\AdminMenuHelper;
use Site\Helpers\AdminModulePermissionsHelper;

class AdminMenuController extends BaseController
{

    private AdminMenuHelper $_helper;
    private AdminModulePermissionsHelper $_admin_modules_permission_helper;

    function __construct($params)
    {
        parent::__construct($params);
        // 
        $this->_helper = new AdminMenuHelper($this->db);
        $this->_admin_modules_permission_helper = new AdminModulePermissionsHelper($this->db);
    }

    /*
 *
     NEWS CATEGORY ..................................//
     * 
     */

    private function getRoles($roles)
    {
        $roles_arr = [];
        foreach ($roles as $dt) {
            $roles_arr[] = $dt["value"];
        }
        return $roles_arr;
    }

    public function insert()
    {
        $columns = ["label", "link"];
        // do validations
        $this->_helper->validate(AdminMenuHelper::validations, $columns, $this->post);
        $parent_id = SmartData::post_select_value("parent_id");
        $this->post["sd_admin_modules_id"] = SmartData::post_select_value("sd_admin_modules_id");
        $other_columns = [
            "icon",
            "roles",
            "created_by",
            "last_modified_time",
            "order_number",
            "sd_admin_modules_id"
        ];
        $roles = SmartData::post_array_data("roles");
        $this->post["roles"] = implode(",",  $this->getRoles($roles));
        if ($parent_id > 0) {
            $other_columns[] = "parent_id";
            $this->post["parent_id"] = $parent_id; // Fixed typo: "patent_id" to "parent_id"
        }
        $columns = array_merge($columns, $other_columns);
        // Insert and get id
        $this->_helper->insert($columns, $this->post);
        $this->responseMsg("Added Successfully");
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
        $columns = ["label", "link"];
        // do validations
        $this->_helper->validate(AdminMenuHelper::validations, $columns, $this->post);
        $parent_id = SmartData::post_select_value("parent_id");
        $this->post["sd_admin_modules_id"] = SmartData::post_select_value("sd_admin_modules_id");
        $other_columns = ["icon", "roles", "created_by", "last_modified_time",  "order_number", "sd_admin_modules_id"];
        $roles = SmartData::post_array_data("roles");
        $this->post["roles"] = implode(",",  $this->getRoles($roles));
        if ($parent_id > 0) {
            $other_columns[] = "parent_id";
            $this->post["parent_id"] = $parent_id; // Fixed typo: "patent_id" to "parent_id"
        } else {
            $other_columns[] = "parent_id";
            $this->post["parent_id"] = 0; // Fixed typo: "patent_id" to "parent_id"
        }
        $columns = array_merge($columns, $other_columns);
        // insert and get id
        $this->_helper->update($columns, $this->post, $id);
        $this->responseMsg("Updated Successfully");
        $this->response($id);
    }
    /**
     * 
     */
    public function getAll()
    {
        $sql = "";
        $data_in = [];
        $data = $this->_helper->getAllDataRecursive($sql, $data_in);
        $this->response($data);
    }

    private  function buildTree(array $elements, $parentId = 0)
    {
        $branch = [];
        foreach ($elements as $element) {
            $module_status = isset($element->module_status) ? $element->module_status : 5;
            if ($element->parent_id == $parentId && $module_status == 5) {
                // Recursively find children
                $children = $this->buildTree($elements, $element->ID);
                if ($children) {
                    $element->children = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }

    public function getAllMenus()
    {
        $data = $this->_helper->getAllData();
        //var_dump($data);
        // exit();
        $nested_data = $this->buildTree($data);
        //

        //var_dump($nested_data);
        $this->response($nested_data);
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
        $data = $this->_helper->getOneData($id);
        if (isset($data->ID)) {
            $data->parent_id = ["value" => $data->parent_id, "label" => $data->parent_name];
            $data->sd_admin_modules_id = [
                "value" =>  $data->sd_admin_modules_id,
                "label" => $data->module_name
            ];
            $roles = array_filter(explode(",", $data->roles));
            $data->roles = [];
            foreach ($roles as $role) {
                $data->roles[] =  ["value" => $role, "label" => $role];
            }
        }
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
        // insert and get id
        $this->_helper->deleteOneId($id);
        $out = new \stdClass();
        $out->msg = "Removed Successfully";
        $this->response($out);
    }

    public function getAllSelect()
    {
        $select = ["t1.ID as value", "t1.full_label as label"];
        $data = $this->_helper->getAllDataRecursive("", [], $select);
        $this->response($data);
    }
    
}
