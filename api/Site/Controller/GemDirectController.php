<?php

namespace Site\Controller;

use Core\BaseController;
use Core\Helpers\SmartGeneral;
use Core\Helpers\SmartAuthHelper;
use Core\Helpers\SmartSiteSettings;
use Core\Helpers\SmartFileHelper;
use Core\Helpers\SmartPdfHelper;
use Core\Helpers\SmartData;

use Site\Helpers\OrganisationHelper;
use Site\Helpers\GemDirectHelper;
use Site\View\GemDirectPdf;


class GemDirectController extends BaseController
{
    private GemDirectHelper $_gem_direct_helper;
    private OrganisationHelper $_org_helper;


    function __construct($params)
    {
        parent::__construct($params);
        // 
        $this->_gem_direct_helper = new GemDirectHelper($this->db);
        $this->_org_helper = new OrganisationHelper($this->db);

    }
    // 
    /** 10 = submitted-techwait  , 10=hos_wait,14=hos_reject,15=hos_process , 15=hod wait,hod process=20
     * 
     */
    public function insert()
    {
        $columns = [
            "indent_no",
            "item_brief_description",
            "head_of_account",
            "estimate_source",
            "cost",
            "gem_id_item",
            "justification_purchase",
            "quantity",
            "unit",
        ];



        // validations
        $this->_gem_direct_helper->validate(GemDirectHelper::validations, $columns, $this->post);

        // additional fields
        $columns[] = "created_time";
        $columns[] = "sd_mt_userdb_id";
        $columns[] = "status";
        $this->post["status"] = 10;
        $columns[] = "detailed_specification";


        // insert
        $id = $this->_gem_direct_helper->insert($columns, $this->post);

        // process the file
        $file_path = $this->_gem_direct_helper->getFullFile($id);
        if (isset($_FILES["uploaded_file"])) {
            // move the uploaded file to path 
            $stored_file_path = SmartFileHelper::moveSingleFile("uploaded_file", $file_path);
            // update the file path in table
            $update_columns = ["doc_loc"];
            $update_data = ["doc_loc" => $stored_file_path];
            $this->_gem_direct_helper->update($update_columns, $update_data, $id);
        }

        // log
        $this->addLog("RAISED AN GEM DIRECT COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());

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
        $this->_gem_direct_helper->validate(GemDirectHelper::validations, $columns, $this->post);
        // add columns
        // $columns[] = "admin_remarks";
        $columns[] = "last_modified_by";
        $columns[] = "last_modified_time";


        // insert and get id
        $id = $this->_gem_direct_helper->update($columns, $this->post, $id);
        // process the file
        $file_path = $this->_gem_direct_helper->getFullFile($id);
        if (isset($_FILES["uploaded_file"])) {
            // move the uploaded file to path 
            $stored_file_path = SmartFileHelper::moveSingleFile("uploaded_file", $file_path);
            // update the file path in table
            $update_columns = ["doc_loc"];
            $update_data = ["doc_loc" => $stored_file_path];
            $this->_gem_direct_helper->update($update_columns, $update_data, $id);
        }

        // add log
        $this->addLog("UPDATED AN GEM DIRECT COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }

    public function getAll()
    {
        // check the mode received from router
        $sql = "";
        $data_in = [];
        $mode = isset($this->params["mode"]) ? $this->params["mode"] : "user";
        // $status = isset($this->params["status"]) ? $this->params["status"] : [100];
        $status = isset($this->params["status"]) && is_array($this->params["status"]) && count($this->params["status"]) > 0
            ? $this->params["status"]
            : [100]; // fallback if not set or empty

        $logged_id = SmartAuthHelper::getLoggedInId();
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
            case 'hos':
                $sql = "t1.status IN (" . implode(",", $status) . ")";
                break;
            // case 'hos':
            //     $org_ids = $this->_org_helper->getSubOrdIds($logged_id, "SH");
            //     $sql = "t1.status IN (" . implode(",", $status) . ") AND t2.sd_org_id IN (" . implode(",", $org_ids) . ")";
            //     break;

            case 'financial_approval':
                $sql = "t1.status IN (" . implode(",", $status) . ")";
                break;
            // case 'financial_approval':
            //     $sql = "t1.financial_approval_id = :financial_approval_id AND status IN (" . implode(",", $status) . ")";
            //     $data_in = ["financial_approval_id" => SmartAuthHelper::getLoggedInId()];
            //     break;

            default:
                break;
        }
        $data = $this->_gem_direct_helper->getAllData($sql, $data_in);
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

        // Get the main record
        $data = $this->_gem_direct_helper->getOneData($id);

        if (!$data) {
            \CustomErrorHandler::triggerInvalid("Record not found");
        }

        // Get tracker (pass created_by also)
        $statusTracker = $this->_gem_direct_helper->getStatusTracker($data->status, $data->created_by);

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
        $this->_gem_direct_helper->deleteOneId($id);
        // add log
        $this->addLog("DELETED AN GEM DIRECT COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());
        //
        $out = new \stdClass();
        $out->msg = "Deleted Successfully";
        $this->response($out);
    }

    public function updateApprovalHos()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }

        $status = isset($this->post["status"]) ? $this->post["status"] : "";
        $remarks = isset($this->post["remarks"]) ? $this->post["remarks"] : "";

        // HOD approval = 35, rejection = 6
        $status = ($status === "approve") ? 15 : (($status === "reject") ? 14 : 0);



        if ($status < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid Action");
        }
        $columns = ["status", "hos_id", "hos_remarks", "hos_time"];
        $dt = [
            "status" => $status,
            "hos_remarks" => $remarks
        ];


        $id = $this->_gem_direct_helper->update($columns, $dt, $id);

        $logMsg = $status == 15 ? "APPROVED  GEM DIRECT CARD BY HOS" : "SENT GEM DIRECT CARD FOR REJECT BY HOS";

        $this->addLog($logMsg, $remarks, SmartAuthHelper::getLoggedInUserName());

        $this->response($id);
    }


    public function updateApprovalfinancialApprover()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }

        $status = isset($this->post["status"]) ? $this->post["status"] : "";
        $remarks = isset($this->post["remarks"]) ? $this->post["remarks"] : "";

        // Determine status: Iibcc approval = 20, rejection = 19
        $status = ($status === "approve") ? 20 : (($status === "reject") ? 19 : 0);


        if ($status < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid Action");
        }
        $columns = ["status", "financial_approval_id", "financial_approval_remarks", "financial_approval_time"];
        $dt = [
            "status" => $status,
            "financial_approval_remarks" => $remarks,
        ];

        $id = $this->_gem_direct_helper->update($columns, $dt, $id);

        $logMsg = $status == 20 ? "APPROVED GEM DIRECT BY IIBCC APPROVER" : "SENT GEM DIRECT CARD BY IIBCC APPROVER TO REWORK";
        $this->addLog($logMsg, $remarks, SmartAuthHelper::getLoggedInUserName());

        $this->response($id);
    }

    public function getPdf()
    {
        // $id = 10;
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $data = $this->_gem_direct_helper->getOneData($id);

        // var_dump($data);
        // exit();
        $this->_gem_direct_helper->generateRfidCardPdf($id, $data);
        $html = GemDirectPdf::getHtml((array) $data);
        // var_dump($html);
        // exit();
        $path = "gemdirect" . DS . $id . DS . "gemdirect.pdf";
        SmartPdfHelper::genPdf($html, $path, ["pagesize" => "A4"]);
        $full_path = SmartFileHelper::getDataPath() . $path;
        $this->responseFileBase64($full_path);
    }

    //
    public function updateUser()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $columns = [
            "indent_no",
            "item_brief_description",
            "head_of_account",
            "estimate_source",
            "cost",
            "gem_id_item",
            "justification_purchase",
            "quantity",
            "unit",
        ];

        // do validations
        $this->_gem_direct_helper->validate(GemDirectHelper::validations, $columns, $this->post);
        // add columns
        $columns[] = "last_modified_by";
        $columns[] = "last_modified_time";
        $columns[] = "status";
        $this->post["status"] = 10;
        // insert and get id
        $id = $this->_gem_direct_helper->update($columns, $this->post, $id);
        // process the file
        $file_path = $this->_gem_direct_helper->getFullFile($id);
        if (isset($_FILES["uploaded_file"])) {
            // move the uploaded file to path 
            $stored_file_path = SmartFileHelper::moveSingleFile("uploaded_file", $file_path);
            // update the file path in table
            $update_columns = ["doc_loc"];
            $update_data = ["doc_loc" => $stored_file_path];
            $this->_gem_direct_helper->update($update_columns, $update_data, $id);
        }

        // add log
        $this->addLog("UPDATED AN GEM DIRECT COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }
    //
    public function getDoc()
    {
        $id = isset($this->post["id"]) ? $this->post["id"] : 0;
        if ($id < 0) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $data = $this->_gem_direct_helper->getOneData($id);
        // 
        $pdf_path = $this->_gem_direct_helper->getFullFile($id) . ".pdf";
        //    echo $pdf_path;
        $this->responseFileBase64(SmartFileHelper::getDataPath() . $pdf_path);
    }






}
