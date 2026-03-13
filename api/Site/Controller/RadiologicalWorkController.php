<?php

namespace Site\Controller;

use Core\BaseController;
use Core\Helpers\SmartGeneral;
use Core\Helpers\SmartAuthHelper;
use Core\Helpers\SmartData;
use Core\Helpers\SmartFileHelper;
use Core\Helpers\SmartPdfHelper;
use Site\view\RadiologicalPdf;
use Site\Helpers\RadiologicalWorkHelper;
use Site\Helpers\RadiologicalWorkSubHelper;

class RadiologicalWorkController extends BaseController
{
    private RadiologicalWorkHelper $_radiologicalWork_helper;
    private RadiologicalWorkSubHelper $_radiologicalWorkSub_helper;
    function __construct($params)
    {
        parent::__construct($params);
        // 
        $this->_radiologicalWork_helper = new RadiologicalWorkHelper($this->db);
        $this->_radiologicalWorkSub_helper = new RadiologicalWorkSubHelper($this->db);
    }

    /**
     * 
     */
    public function insert()
    {
        $columns = [
            "name",
            "nature_of_work",
            "rwp_no",
            "job_description",

        ];
        $this->post["rwp_no"] = $this->_radiologicalWork_helper->generateRwpNo();
        // do validations
        $this->_radiologicalWork_helper->validate(RadiologicalWorkHelper::validations, $columns, $this->post);

        // add other columns
        $columns[] = "created_time";
        $columns[] = "sd_mt_userdb_id";
        $columns[] = "status";
        $this->post["status"] = 10;
        // insert and get id
        $id = $this->_radiologicalWork_helper->insert($columns, $this->post);
        // Handle sub-data using insert_update from the helper
        $sub_data = SmartData::post_array_data("sub_data");
        if (!empty($sub_data)) {
            $this->_radiologicalWorkSub_helper->insert_update($id, $sub_data);
        }
        // add log
        $this->addLog("RAISED A RADIOLOGICAL COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());
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
        $columns = ["status"];
        // do validations
        $this->_radiologicalWork_helper->validate(RadiologicalWorkHelper::validations, $columns, $this->post);
        // add columns
        $columns[] = "last_modified_by";
        $columns[] = "last_modified_time";
        $columns[] = "admin_remarks";
        // insert and get id
        $id = $this->_radiologicalWork_helper->update($columns, $this->post, $id);
        $sub_data = SmartData::post_array_data("sub_data");
        if (!empty($sub_data)) {
            $this->_radiologicalWorkSub_helper->insert_update($id, $sub_data);
        }
        // add log
        $this->addLog("UPDATED RADIOLOGICAL COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }

    public function getAll()
    {
        // check the mode received from router
        $sql = "";
        $data_in = [];
        $mode = isset($this->params["mode"]) ? $this->params["mode"] : "user";

        $status = isset($this->params["status"]) ? $this->params["status"] : [100];
        switch ($mode) {
            // indicates the logged user data
            case 'user':
                $sql = "t1.sd_mt_userdb_id=:user_id";
                $data_in = ["user_id" => SmartAuthHelper::getLoggedInId()];
                break;
            case 'app':
                $sql = "t1.app_id=:user_id AND status IN (" . implode(",", $status) . ")";
                $data_in = ["user_id" => SmartAuthHelper::getLoggedInId()];
                break;
            case 'admin':
                $sql = "status IN (" . implode(",", $status) . ")";
                break;
            case 'labincharge':
                $sql = "t1.status IN (" . implode(",", $status) . ")";
                break;
            case 'hp':
                $sql = "t1.status IN (" . implode(",", $status) . ")";
                break;
            default:
                break;
        }
        $data = $this->_radiologicalWork_helper->getAllData($sql, $data_in);
        foreach ($data as $obj) {
            $obj->sub_data = $this->_radiologicalWorkSub_helper->getAllWithParentId($obj->ID);
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
        $data = $this->_radiologicalWork_helper->getOneData($id);
        if (isset($data->ID)) {
            $data->sub_data = $this->_radiologicalWorkSub_helper->getAllWithParentId($data->ID);
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
        $this->_radiologicalWork_helper->deleteOneId($id);
        // add log
        $this->addLog("DELETED RADIOLOGICAL COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());
        //
        $out = new \stdClass();
        $out->msg = "Deleted Successfully";
        $this->response($out);
    }
   
     //APPROVAL FOR LAB INCHARGE , HP

    public function updateApprovalLabIncharge()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }

        $status = isset($this->post["status"]) ? $this->post["status"] : "";
        $remarks = isset($this->post["remarks"]) ? $this->post["remarks"] : "";

        // Determine status: HOS approval = 15, rejection = 14
        $status = ($status === "approve") ? 15 : (($status === "reject") ? 14 : 0);
        if ($status < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid Action");
        }
        $columns = ["status", "lab_incharge_id", "lab_incharge_remarks", "lab_incharge_time"];
        $dt = [
            "status" => $status,
            "lab_incharge_remarks" => $remarks
        ];

        $id = $this->_radiologicalWork_helper->update($columns, $dt, $id);

        $logMsg = $status == 15 ? "APPROVED RADIOLOGICAL WORK BY LABINCHARGE" : "REJECTED RADIOLOGICAL WORK BY LABINCHARGE";
        $this->addLog($logMsg, $remarks, SmartAuthHelper::getLoggedInUserName());

        $this->response($id);
    }
    public function updateApprovalHP()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }

        $status = isset($this->post["status"]) ? $this->post["status"] : "";
        $remarks = isset($this->post["remarks"]) ? $this->post["remarks"] : "";

        // HOD approval = 20, rejection = 19
        $status = ($status === "approve") ? 20 : (($status === "reject") ? 19 : 0);
        if ($status < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid Action");
        }

        $columns = [
            "status",
            "hp_id",
            "hp_remarks",
            "hp_time",
            "cover_all",
            "rubber_glove",
            "dosimeter",
            "complete_dress",
            "surgical_gloves",
            "respirator",
            "change_of_clothes",
            "over_shoes",
            "dust_gas_mask",
            "plastic_suit",
            "air_activity",
            "airline_breathing_apparatus",
            "rubber_station",
            "single_rubber_area",
            "double_rubber_area",
            "shower_after_work",
            "rubber_instruction"
        ];
        $dt = [
            "status" => $status,
            "hp_remarks" => $remarks,

            "cover_all" => $this->post["cover_all"] ?? null,
            "rubber_glove" => $this->post["rubber_glove"] ?? null,
            "dosimeter" => $this->post["dosimeter"] ?? null,
            "complete_dress" => $this->post["complete_dress"] ?? null,
            "surgical_gloves" => $this->post["surgical_gloves"] ?? null,
            "respirator" => $this->post["respirator"] ?? null,
            "change_of_clothes" => $this->post["change_of_clothes"] ?? null,
            "over_shoes" => $this->post["over_shoes"] ?? null,
            "dust_gas_mask" => $this->post["dust_gas_mask"] ?? null,
            "plastic_suit" => $this->post["plastic_suit"] ?? null,
            "air_activity" => $this->post["air_activity"] ?? null,
            "airline_breathing_apparatus" => $this->post["airline_breathing_apparatus"] ?? null,
            "rubber_station" => $this->post["rubber_station"] ?? null,
            "single_rubber_area" => $this->post["single_rubber_area"] ?? null,
            "double_rubber_area" => $this->post["double_rubber_area"] ?? null,
            "shower_after_work" => $this->post["shower_after_work"] ?? null,
            "rubber_instruction" => $this->post["rubber_instruction"] ?? null,
        ];

        $id = $this->_radiologicalWork_helper->update($columns, $dt, $id);

        $logMsg = $status == 20 ? "APPROVED RADIOLOGICAL WORK BY HP" : "REJECTED RADIOLOGICAL WORK BY HP";
        $this->addLog($logMsg, $remarks, SmartAuthHelper::getLoggedInUserName());

        $this->response($id);
    }
    /*
      public function getPdf()
    {
          $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }

       $data = $this->_radiologicalWork_helper->getOneData($id);
        if (isset($data->ID)) {
            $data->sub_data = $this->_radiologicalWorkSub_helper->getAllWithParentId($data->ID);
        }
        // var_dump($data);
        // exit();
        $this->_radiologicalWork_helper->generateRadiologicalPdf($id, $data);
        $html = RadiologicalPdf::getHtml((array)$data);
        $path = "radiologicalwork" . DS . $id . DS . "radiologicalwork.pdf";
        SmartPdfHelper::genPdf($html, $path, ["pagesize" => "A4"]);
        $full_path = SmartFileHelper::getDataPath() . $path;
        $this->responseFileBase64($full_path);
      
    
}
*/
    public function getPdf()
    {
    $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
    if ($id < 1) {
        \CustomErrorHandler::triggerInvalid("Invalid ID");
    }
 
    $data = $this->_radiologicalWork_helper->getOneData($id);
    if (isset($data->ID)) {
        $data->sub_data = $this->_radiologicalWorkSub_helper->getAllWithRadiologicalId($data->ID);
    }

    $this->_radiologicalWork_helper->generateRadiologicalPdf($id, $data);

    $html = RadiologicalPdf::getHtml([
        ... (array)$data,
        'personnel' => $data->sub_data ?? []
    ]);

    $path = "radiologicalwork" . DS . $id . DS . "radiologicalwork.pdf";
    SmartPdfHelper::genPdf($html, $path, ["pagesize" => "A4"]);
    $full_path = SmartFileHelper::getDataPath() . $path;
    $this->responseFileBase64($full_path);
    }



}