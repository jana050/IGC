<?php 

namespace Site\Controller;

use Core\BaseController;
use Core\Helpers\SmartFileHelper;
use Core\Helpers\SmartAuthHelper;
use Site\Helpers\MeetProposalHelper;


class MeetProposalController extends BaseController{
  
  private MeetProposalHelper $_meet_proposal_helper;
    function __construct($params)
    {
        parent::__construct($params);
        // 
        $this->_meet_proposal_helper = new MeetProposalHelper($this->db);
    }

   /**
     * 
     */
    public function insert(){
        $valid_columns = [ "title","description"];        
        // do validations
        $this->_meet_proposal_helper->validate(MeetProposalHelper::validations,$valid_columns,$this->post);
        // add columns
        $columns = [ "title","description"];      
      //  $columns[] = "app_id";
       // $columns[] = "app_date";
       // $columns[] = "app_remarks";
        //var_dump($columns);
       // $this->post["mom_type"] = "test";
        $this->post["status"] = 5;
        $columns[] = "status";
        $columns[] = "mom_type";
        $columns[] = "created_by";
        $columns[] = "created_time";
         // insert and get id         
         $id = $this->_meet_proposal_helper->insert($columns,$this->post);
          // process the file
        $file_path = $this->_meet_proposal_helper->getFullFile($id);
        if( isset($_FILES["uploaded_file"])){
        // move the uploaded file to path 
        $stored_file_path = SmartFileHelper::moveSingleFile("uploaded_file",$file_path);
        // update the file path in table
        $update_columns = ["doc_loc"];
        $update_data = ["doc_loc"=>$stored_file_path];
           $this->_meet_proposal_helper->update($update_columns,$update_data,$id);   
        } 
         // add log
        $this->addLog("ADDED A MEET PROPOSAL","",SmartAuthHelper::getLoggedInUserName());
        //
        
         $this->response($id);
    }
    // public function update()
    // {
    // $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
    // if ($id < 1) {
    //     \CustomErrorHandler::triggerInvalid("Invalid ID");
    // }

    // $columns = ["title", "description"];

    // // Validate required fields
    // $this->_meet_proposal_helper->validate(MeetProposalHelper::validations, $columns, $this->post);

    // // Add optional fields
    // $columns[] = "admin_remarks";
    // $columns[] = "last_modified_by";
    // $columns[] = "last_modified_time";

    // // File handling
    // $file_path = $this->_meet_proposal_helper->getFullFile($id);
    // if (isset($_FILES["uploaded_file"]) && $_FILES["uploaded_file"]["error"] === UPLOAD_ERR_OK) {
    //     $stored_file_path = SmartFileHelper::moveSingleFile("uploaded_file", $file_path);
    //     if ($stored_file_path) {
    //         $update_columns = ["doc_loc"];
    //         $update_data = ["doc_loc" => $stored_file_path];
    //         $this->_meet_proposal_helper->update($update_columns, $update_data, $id);
    //     } else {
    //         \CustomErrorHandler::triggerInvalid("File upload failed.");
    //     }
    // }

    // // Main update
    // $id = $this->_meet_proposal_helper->update($columns, $this->post, $id);

    // // Log the update
    // $this->addLog("UPDATED AN TEMPORARY ADVANCE COMPLAINT", "", SmartAuthHelper::getLoggedInUserName());

    // // Send response
    // $this->response($id);
    // }
     public function update()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $columns = ["title", "description"];
        // do validations
        $this->_meet_proposal_helper->validate(MeetProposalHelper::validations, $columns, $this->post);
    
        // insert and get id
        $id = $this->_meet_proposal_helper->update($columns, $this->post, $id);
        // process the file
        $file_path = $this->_meet_proposal_helper->getFullFile($id);
        if( isset($_FILES["uploaded_file"])){
        // move the uploaded file to path 
        $stored_file_path = SmartFileHelper::moveSingleFile("uploaded_file",$file_path);
        // update the file path in table
        $update_columns = ["doc_loc"];
        $update_data = ["doc_loc"=>$stored_file_path];
           $this->_meet_proposal_helper->update($update_columns,$update_data,$id);   
        } 
        // add log
        $this->addLog("UPDATED AN MBOOK ISSUE", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }

    /**
     * 
     */
    public function getAll()
    {
        // check the mode received from router
       
      
        $sql = "t1.mom_type=:mom_type";
        $mom_type = isset($this->post["mom_type"]) ? $this->post["mom_type"] : "##";   
        $data_in = ["mom_type"=>$mom_type];   
        $mode = isset($this->params["mode"]) ? $this->params["mode"] : "user";
        $status = isset($this->params["status"]) ? $this->params["status"] : [100];
        switch ($mode) {
                // indicates the logged user data
            case 'user':
                $sql .= " AND t1.created_by=:user_id";
                $data_in["user_id"] = SmartAuthHelper::getLoggedInId();
                break;
            case 'admin':
                $sql .= " AND t1.status IN (".implode(",", $status).")";
                break;
            default:
                break;
        } 
        $data = $this->_meet_proposal_helper->getAllData($sql, $data_in);
        $this->response($data);
    }
    /**
     * 
     */
    public function getOne(){  
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if($id < 1){
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }    
        // insert and get id
        $data = $this->_meet_proposal_helper->getOneData($id);
        $this->response($data);
    }
    /**
     * 
     */
    public function deleteOne(){  
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if($id < 1){
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }    
        // insert and get id
        $this->_meet_proposal_helper->deleteOneId($id);
        // add log
        $this->addLog("REMOVED A MEET PROPOSAL","",SmartAuthHelper::getLoggedInUserName());
        //
        $out = new \stdClass();
        $out->msg = "Removed Successfully";
    }    
     /**
     * 
     */
    public function getDoc(){
        $id = isset($this->post["id"]) ? $this->post["id"] : 0;
        if($id < 0){
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $data = $this->_meet_proposal_helper->getOneData($id);
        // 
        $pdf_path =  $this->_meet_proposal_helper->getFullFile($id) .".pdf";
       // echo $pdf_path;
        $this->responseFileBase64(SmartFileHelper::getDataPath()  . $pdf_path);
    }

    public function updateApp()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
      //  $action = isset($this->post["action"]) ? ($this->post["action"]) : "";

       // echo "action = " . $action;

        $columns = ["status","app_id"];
        //
        $dt = ["status"=>$this->post["status"]];
        // do validations
       // $this->_electrical_helper->validate(ElectricalHelper::validations, $columns, $dt);
        // add columns
       // $columns[] = "last_modified_by";
        $columns[] = "app_date";
        // insert and get id
        $id = $this->_meet_proposal_helper->update($columns, $dt, $id);
        // add log
        $this->addLog("UPDATED AN Proposal", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }
   


}