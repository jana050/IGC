<?php

namespace Site\Controller;

use Core\BaseController;

use Core\Helpers\SmartAuthHelper;
use Core\Helpers\SmartData;
use Site\Helpers\AdminModulesHelper;
use Site\Helpers\AdminModulePermissionsHelper;
use Site\Helpers\AdminRolePermissionsHelper;

class AdminModulesController extends BaseController
{

    private AdminModulesHelper $_helper;
    private AdminModulePermissionsHelper $_admin_modules_permission_helper;
    private AdminRolePermissionsHelper $_admin_role_permission_helper;

    function __construct($params)
    {
        parent::__construct($params);
        // 
        $this->_helper = new AdminModulesHelper($this->db);
        $this->_admin_modules_permission_helper = new AdminModulePermissionsHelper($this->db);
        $this->_admin_role_permission_helper = new AdminRolePermissionsHelper($this->db);
    }

    /**
     * 
     */
    public function insert()
    {
        $columns = ["module_name"];
        // do validations
        $this->_helper->validate(AdminModulesHelper::validations, $columns, $this->post);
        //extra columns
        $columns[] = "status";
        $this->post["status"] = 5;
        // insert and get id
        $id = $this->_helper->insert($columns, $this->post);
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
        $columns = ["module_name", "status"];
        // do validations
        $this->_helper->validate(AdminModulesHelper::validations, $columns, $this->post);

        // begin transition
        $this->db->_db->Begin();
        // insert and get id
        $id = $this->_helper->update($columns, $this->post, $id);
        $this->db->_db->commit();
        $this->responseMsg("Updated Successfully");
    }
    /**
     * 
     */
    public function getAll()
    {
        $data = $this->_helper->getAllData();
        foreach ($data as $obj) {
            $obj->sub_data = $this->_admin_modules_permission_helper->getAllWithParentId($obj->ID);
        }
        $this->response($data);
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
        $actions = [
            "add" => "add_action",
            "edit" => "edit_action",
            "view" => "view_action",
            "approve" => "approve_action"
        ];
        // we will get the permissions set and make the payload as such the actions are available
        if (isset($data->ID)) {
            $sub_data = $this->_admin_modules_permission_helper->getAllWithParentId($data->ID);
            $data->add_action = "0";
            $data->edit_action = "0";
            $data->view_action = "0";
            $data->approve_action = "0";
            foreach ($sub_data as $obj) {
                if (isset($actions[$obj->action])) {
                    $data->{$actions[$obj->action]} = "1";
                }
            }
        }
        $this->response($data);
    }

    public function updateStatus()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $status = SmartData::post_data("status", "INTEGER");
        $columns = ["status"];
        $data_in = ["status" => $status];
        $this->_helper->update($columns, $this->post, $id);
        $this->responseMsg("Status Updated Successfully");
    }


    public function getAllSelect()
    {
        $select = ["ID as value,module_name as label"];
        $data = $this->_helper->getAllData("", [], $select);
        $this->response($data);
    }

    /**
     * AdminModulePermision
     */
    public function insertAdminModulePermission()
    {
        $moduleId = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($moduleId < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid Module ID");
        }

        $actions = [
            "add_action" => "add",
            "edit_action" => "edit",
            "view_action" => "view",
            "approve_action" => "approve"
        ];

        $insertData = [];
        foreach ($actions as $key => $actionName) {
            if (!empty($this->post[$key]) && $this->post[$key] == "1") {
                $insertData[] = [
                    "module_id" => $moduleId,
                    "action" => $actionName
                ];
            }
        }

        if (!empty($insertData)) {
            $this->_admin_modules_permission_helper->insert_data($moduleId, $insertData);
            $this->responseMsg("Added module permission Successfully");
        } else {
            $this->responseMsg("No Actions Selected");
        }
    }


    public function getAllModulePermissionWithRole()
    {
        $roleId = intval($this->post["role"]["value"] ?? 0);
        if ($roleId < 1)
            \CustomErrorHandler::triggerInvalid("Invalid Role ID");
        // get the module and related permissios
        $data = $this->_helper->getAllActiveModules();
        foreach ($data as $obj) {
            $obj->sub_data = $this->_admin_modules_permission_helper->getAllWithParentId($obj->ID);
            foreach ($obj->sub_data as $_obt) {
                $_obt->value =  $this->_admin_role_permission_helper->checkOneWithRoleIdPermissionId($roleId, $_obt->ID) ? "1" : "0";
            }
        }
        // now the data contains respective modules names and subdata contains available permissions
        $this->response($data);
    }



    /**
     * AdminRolePermision
     */
    public function insertRolePermission()
    {
        $roleId = intval($this->post["role"]["value"] ?? 0);
        if ($roleId < 1)
            \CustomErrorHandler::triggerInvalid("Invalid Role ID");

        $sub_data = SmartData::post_array_data("sub_data");
        if (empty($sub_data))
            \CustomErrorHandler::triggerInvalid("No Valid Permissions Provided");
        // remove existing role permissions 
        $this->_admin_role_permission_helper->deleteWithParentId($roleId);
        // loop the sub data assume the sub data comes in same way what i have sent
        foreach ($sub_data as $obj) {
            foreach ($obj["sub_data"] as $_obg) {
                $value = isset($_obg["value"]) ? $_obg["value"] : "0";
                if (intval($value) == 1) {
                    // insert the role and permssion here
                    $this->_admin_role_permission_helper->insert_single_row($roleId, $_obg["ID"]);
                }
            }
        }
        $this->responseMsg("Role Permissions Added Successfully");
    }
    public function getAllRolePermission()
    {
        $data = $this->_admin_role_permission_helper->getAllData();
        $this->response($data);
    }
}
