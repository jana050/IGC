<?php

namespace Site\Controller;

use Core\BaseController;
use Core\Helpers\SmartFileHelper;
use Core\Helpers\SmartAuthHelper;
use Site\Helpers\ACVShutDownHelper;
use Site\Helpers\OrganisationHelper;
use Core\Helpers\SmartData as Data;


class ACVShutDownController extends BaseController
{
    private ACVShutDownHelper $_acv_shutdown_helper;
    private OrganisationHelper $_org_helper;

    function __construct($params)
    {
        parent::__construct($params);
        // 
        $this->_acv_shutdown_helper = new ACVShutDownHelper($this->db);
        $this->_org_helper = new OrganisationHelper($this->db);
    }

    /**
     * 
     */
    public function insert()
    {
        $columns = ["from_date", "to_date", "from_time", "to_time", "description"];
        // do validations
        $this->_acv_shutdown_helper->validate(ACVShutDownHelper::validations, $columns, $this->post);
        // add other columns
        $this->post["from_date"] = Data::post_data("from_date", "DATE");
        $this->post["to_date"] = Data::post_data("to_date", "DATE");
        $columns[] = "created_time";
        $columns[] = "sd_mt_userdb_id";
        $columns[] = "shutdown_type";
        $columns[] = "status";
        $this->post["status"] = 15;
        // begin transation
        $this->db->_db->Begin();
        // insert and get id
        $id = $this->_acv_shutdown_helper->insert($columns, $this->post);
        // upload the document
        $file_path = $this->_acv_shutdown_helper->getFullFile($id);
        if (isset($_FILES["uploaded_file"])) {
            // move the uploaded file to path 
            $stored_file_path = SmartFileHelper::moveSingleFile("uploaded_file", $file_path);
            // update the file path in table
            $update_columns = ["location"];
            $update_data = ["location" => $stored_file_path];
            $this->_acv_shutdown_helper->update($update_columns, $update_data, $id);
        }
        // commit the transaction and 
        $this->db->_db->commit();
        // add log
        $this->addLog("ADDED A WORKSHOP DOC", "", SmartAuthHelper::getLoggedInUserName());
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
        $valid_columns = ["status"];

        // do validations
        $this->_acv_shutdown_helper->validate(ACVShutDownHelper::validations, $valid_columns, $this->post);
        $columns = ["status", "admin_remarks"];
        // add columns
        $columns[] = "last_modified_by";
        $columns[] = "last_modified_time";
        // insert and get id
        $id = $this->_acv_shutdown_helper->update($columns, $this->post, $id);
        // add log
        $this->addLog("UPDATED A WORKSHOP DOC", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }

    private function file_check($id)
    {
        $pdf_path =  $this->_acv_shutdown_helper->getFullFile($id) . ".pdf";
        return file_exists(SmartFileHelper::getDataPath()  . $pdf_path) ? true : false;
    }
     public function getOnePdf()
    {
        $id = isset($this->post["id"]) ? $this->post["id"] : 0;
        if ($id < 0) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
      //  $data =  $this->_acv_shutdown_helper->getOneData($id);
        // 
        $pdf_path =  $this->_acv_shutdown_helper->getFullFile($id) . ".pdf";
        // echo $pdf_path;
        $this->responseFileBase64(SmartFileHelper::getDataPath()  . $pdf_path);
    }

    public function getAll()
    {
        // check the mode received from router
        $sql = "";
        $data_in = [];
        $mode = isset($this->params["mode"]) ? $this->params["mode"] : "user";
        $status = isset($this->params["status"]) ? $this->params["status"] : [100];
        $logged_id = SmartAuthHelper::getLoggedInId();
        switch ($mode) {
            // indicates the logged user data
            case 'user':
                $sql = "t1.sd_mt_userdb_id=:user_id";
                $data_in = ["user_id" => SmartAuthHelper::getLoggedInId()];
                break;
            case 'admin':
                $sql = "status IN (" . implode(",", $status) . ")";
                break;
            // case 'hos':
            //     $org_ids = $this->_org_helper->getSubOrdIds($logged_id, "SH");
            //     $sql = "t1.status IN (" . implode(",", $status) . ") AND t2.sd_org_id IN (" . implode(",", $org_ids) . ")";
            //     break;
            // case 'hod':
            //     $org_ids = $this->_org_helper->getSubOrdIds($logged_id, "DH");
            //     $sql = "t1.status IN (" . implode(",", $status) . ") AND t2.sd_org_id IN (" . implode(",", $org_ids) . ")";
            //     // echo "sql " . $sql;
            //     //exit();
            //     break;
              case 'hos':
                $sql = "t1.status IN (" . implode(",", $status) . ")";
                break;
                case 'hod':
                $sql = "t1.status IN (" . implode(",", $status) . ")";
                break;
            default:
                break;
        }
        $data = $this->_acv_shutdown_helper->getAllData($sql, $data_in);
        $out = [];
        foreach ($data as $obj) {
            $obj->file_check = $this->file_check($obj->ID);
            $out[] = $obj;
        }
        $this->response($out);
    }
    /**
     * 
     */
    /*
    public function getOne()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        // insert and get id
        $data = $this->_acv_shutdown_helper->getOneData($id);
        $this->response($data);
    }
    */
    public function getOne() 
    {
    $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
    if ($id < 1) {
        \CustomErrorHandler::triggerInvalid("Invalid ID");
    }

    // Get the main record (must include created_by name from DB)
    $data = $this->_acv_shutdown_helper->getOneData($id);

    if (!$data) {
        \CustomErrorHandler::triggerInvalid("Record not found");
    }

    // Get tracker (pass created_by also)
    $statusTracker = $this->_acv_shutdown_helper->getStatusTracker($data->status, $data->created_by);

    // Add only status_list to the main data
    $data->status_list = $statusTracker['status_list'];

    // Send response
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
        $this->_acv_shutdown_helper->deleteOneId($id);
        // add log
        $this->addLog("DELETED A WORKSHOP DOC", "", SmartAuthHelper::getLoggedInUserName());
        //
        $out = new \stdClass();
        $out->msg = "Deleted Successfully";
        $this->response($out);
    }

    //  //APPROVAL FOR HOS, HOD, AD/DIRECTOR

    public function updateApprovalHos()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }

        $action = isset($this->post["action"]) ? $this->post["action"] : "";
        $remarks = isset($this->post["hos_remarks"]) ? $this->post["hos_remarks"] : "";

        // Determine status: HOS approval = 25, rejection = 6
        $status = ($action === "approve") ? 25 : (($action === "reject") ? 24 : 0);
        if ($status < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid Action");
        }

        $columns = ["status", "hos_id", "hos_remarks", "hos_time"];
        $dt = [
            "status" => $status,
            "hos_remarks" => $remarks
        ];

        $id = $this->_acv_shutdown_helper->update($columns, $dt, $id);

        $logMsg = $status == 25 ? "APPROVED ACV SHUTDOWN  BY HOS" : "REJECTED ACV SHUTDOWN  BY HOS";
        $this->addLog($logMsg, $remarks, SmartAuthHelper::getLoggedInUserName());

        $this->response($id);
    }
    public function updateApprovalHod()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }

        $action = isset($this->post["action"]) ? $this->post["action"] : "";
        $remarks = isset($this->post["hod_remarks"]) ? $this->post["hod_remarks"] : "";

        // HOD approval = 35, rejection = 6
        $status = ($action === "approve") ? 35 : (($action === "reject") ? 34 : 0);

        if ($status < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid Action");
        }

        $columns = ["status", "hod_id", "hod_remarks", "hod_time"];
        $dt = [
            "status" => $status,
            "hod_remarks" => $remarks
        ];

        $id = $this->_acv_shutdown_helper->update($columns, $dt, $id);

        $logMsg = $status == 35 ? "APPROVED ACV SHUTDOWN  BY HOD" : "REJECTED ACV SHUTDOWN  BY HOD";
        $this->addLog($logMsg, $remarks, SmartAuthHelper::getLoggedInUserName());

        $this->response($id);
    }


    public function hosGetAll()
    {
        $sql = "t1.status = 25";
        $data = $this->_acv_shutdown_helper->getAllData($sql);
        $this->response($data);
    }
    public function hodGetAll()
    {
        $sql = "t1.status = 35";
        $data = $this->_acv_shutdown_helper->getAllData($sql);
        $this->response($data);
    }
}
