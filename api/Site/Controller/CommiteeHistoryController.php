<?php

namespace Site\Controller;

use Core\BaseController;


use Site\Helpers\CommiteeHistoryHelper;
use Core\Helpers\SmartAuthHelper;
use Core\Helpers\SmartFileHelper;

class CommiteeHistoryController extends BaseController
{

    private CommiteeHistoryHelper $_comittee_history_helper;

    function __construct($params)
    {
        parent::__construct($params);
        // 
        $this->_comittee_history_helper = new CommiteeHistoryHelper($this->db);
    }

    /**
     * 
     */
    public function insert()
    {
        $columns = [
            "commitee_name",
            "commitee_id",
            "role_name",
            "remarks",
            "action"
        ];
        // do validations
        $this->_comittee_history_helper->validate(CommiteeHistoryHelper::validations, $columns, $this->post);
        // columns to be inserted

        // begin transation
        $this->db->_db->Begin();
        // insert and get id
        $id = $this->_comittee_history_helper->insert($columns, $this->post);
        // commit the transaction and 
        $this->db->_db->commit();
        // add log
        $this->addLog("Added Commitee History successfully", "", SmartAuthHelper::getLoggedInUserName());
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
        $columns = [
            "commitee_name",
            "commitee_id",
            "role_name",
            "remarks",
            "action"
        ];
        // do validations
        $this->_comittee_history_helper->validate(CommiteeHistoryHelper::validations, $columns, $this->post);

        // insert and get id
        $id = $this->_comittee_history_helper->update($columns, $this->post, $id);
        // add log
        $this->addLog("Updated Commitee History successfully", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }

    public function getAll()
    {
        // insert and get id
        $data = $this->_comittee_history_helper->getAllData();
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
        $data = $this->_comittee_history_helper->getOneData($id);
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
        $this->_comittee_history_helper->deleteOneId($id);
        // add log
        $this->addLog("Deleted Commitee History successfully", "", SmartAuthHelper::getLoggedInUserName());
        //
        $out = new \stdClass();
        $out->msg = "Deleted Successfully";
        $this->response($out);
    }
    /* 🔥 IMPORTANT API */
    public function getByCommittee()
    {
        $data = $this->_comittee_history_helper->getByCommittee(
            $this->post["commitee_name"],
            $this->post["commitee_id"]
        );

        $this->response($data);
    }




}

