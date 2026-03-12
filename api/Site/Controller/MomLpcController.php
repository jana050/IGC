<?php

namespace Site\Controller;

use Core\BaseController;
use Core\Helpers\SmartData as Data;
use Core\Helpers\SmartAuthHelper;
use Core\Helpers\SmartFileHelper;


use Site\Helpers\MomLpcHelper;


class MomLpcController extends BaseController
{
    private MomLpcHelper $_mom_lpc_helper;

    function __construct($params)
    {
        parent::__construct($params);
        // 
        $this->_mom_lpc_helper = new MomLpcHelper($this->db);
    }

    /**
   --- 
   ---
  LPC MOM TABLE
   ---
   ---
     */

    public function insertMom()
    {
        $columns = ["meet_no", "meet_desc", "meet_date", "mom_file"];
        $this->post["meet_date"] = Data::post_data("meet_date", "DATE");

        // do validations
        $this->_mom_lpc_helper->validate(MomLpcHelper::validations, $columns, $this->post);
        // add other columns
        $columns[] = "mom_type";
        $this->post["mom_type"] = "LPC";
        $columns[] = "created_by";
        $columns[] = "created_time";
        // insert and get id
        $id = $this->_mom_lpc_helper->insertMom($columns, $this->post);
        //
        $file_path = $this->_mom_lpc_helper->getFullFile($id);
        // move the uploaded file to path 
        $stored_file_path = SmartFileHelper::moveSingleFile("mom_file", $file_path);
        // update the file path in table
        $update_columns = ["mom_file"];
        $update_data = ["mom_file" => $stored_file_path];
        $this->_mom_lpc_helper->updateMom($update_columns, $update_data, $id);
        // add log
        // $this->addLog("UPLOADED A MOM FILE","",SmartAuthHelper::getLoggedInUserName());
        //
        $this->response($id);
    }
    /**
     * 
     */
    public function updateMom()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $columns = ["mom_type", "meet_no", "meet_date", "date", "mom_file"];
        // do validations
        $this->_mom_lpc_helper->validate(MomLpcHelper::validations, $columns, $this->post);
        // insert and get id
        $id = $this->_mom_lpc_helper->updateMom($columns, $this->post, $id);
        // add log
        $this->addLog("UPDATED MOM FILE", "", SmartAuthHelper::getLoggedInUserName());
        //
        $this->response($id);
    }

    /**
     * 
     */

    public function getAllMom()
    {
        $data = $this->_mom_lpc_helper->getAllDataMom();
        $this->response($data);
    }
    /**
     * 
     */
    public function getOneMom()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }

        $data = $this->_mom_lpc_helper->getOneDataMom($id);
        $this->response($data);
    }

    /**
     * 
     */

    public function deleteOneMom()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        // insert and get id
        $this->_mom_lpc_helper->deleteOneIdMom($id);
        // add log
        $this->addLog("CANCELLED A MEET", "", SmartAuthHelper::getLoggedInUserName());
        //
        $out = new \stdClass();
        $out->msg = "Deleted Successfully";
        $this->response($out);
    }
    /**
     * 
     */
    public function getAllByMomType()
    {
        $type = isset($this->post["mom_type"]) ? ($this->post["mom_type"]) : "##";
        $data = $this->_mom_lpc_helper->getByMomType($type);
        $this->response($data);
    }


    public function getMomPdf()
    {
        // mom_type varala na default IIBCC
        $mom_type = isset($this->post["mom_type"]) && !empty($this->post["mom_type"])
            ? $this->post["mom_type"]
            : "LPC";

        // mom_type base panni data eduka
        $data = $this->_mom_lpc_helper->getOneByMomType($mom_type);

        if (empty($data) || empty($data->ID)) {
            \CustomErrorHandler::triggerInvalid("Document not found");
        }

        $pdf_path = $this->_mom_lpc_helper->getFullFile($data->ID) . ".pdf";

        $this->responseFileBase64(
            SmartFileHelper::getDataPath() . $pdf_path
        );
    }



}
