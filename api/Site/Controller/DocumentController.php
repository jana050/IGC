<?php 

namespace Site\Controller;

use Core\BaseController;
use Core\Helpers\SmartGeneral;
use Core\Helpers\SmartFileHelper;
use Core\Helpers\SmartAuthHelper;
use Core\Helpers\SmartData as Data;
use Site\Helpers\UserRoleHelper;
use Site\Helpers\DocumentHelper;
use Site\Helpers\DocumentPermissionHelper;
use Site\Helpers\DocTypeHelper;

class DocumentController extends BaseController{

    private DocumentHelper $_document_helper;
    private DocumentPermissionHelper $_doc_perm_helper;
   // private DocTypeController $_doc_type_helper;
    //private UserRoleHelper $_user_role_helper;

    function __construct($params)
    {
        parent::__construct($params);
        //  document helper
        $this->_document_helper = new DocumentHelper($this->db);
        // check doc permission
        $this->_doc_perm_helper = new DocumentPermissionHelper($this->db);
        // test 
      ///  $this->_doc_type_helper = new DocTypeHelper($this->db);
        //$this->_user_role_helper = new UserRoleHelper($this->db);
    }

    private function check_permission_value(){
        
        if( $this->post["view_per_role"] == "null"){
            $_POST["view_per_role"] = array(array('value' => 10000000));
        }  
        if( $this->post["view_per_user"] ==  "null"){
            $_POST["view_per_user"] = array(array('value' => 10000000));
        }  
        if( $this->post["download_per_role"] == "null"){
            $_POST["download_per_role"] = array(array('value' => 10000000));
        }  
        if( $this->post["download_per_user"] ==  "null"){
            $_POST["download_per_user"] = array(array('value' => 10000000));
        }
    }
    private function single_permission_helper($post_index,$p_type,$type,$doc_id){
        //var_dump($_POST);
        $posted_data = Data::post_data($post_index,"ARRAY");
        //var_dump($posted_data);
        if(is_array($posted_data) && sizeof($posted_data) > 0){
            // now insert this data 
            $this->_doc_perm_helper->insertPermission($p_type,$type,$doc_id,$posted_data);
        }
    }

    private function insert_permissions($doc_id){
        $this->single_permission_helper("view_per_user","VIEW","USER",$doc_id);
        $this->single_permission_helper("view_per_role","VIEW","ROLE",$doc_id);
        $this->single_permission_helper("download_per_user","DOWNLOAD","USER",$doc_id);
        $this->single_permission_helper("download_per_role","DOWNLOAD","ROLE",$doc_id);
    }

   /**
     * 
     */
    
    public function insert(){
        $validate_columns = [ "doc_title","doc_type",
         "doc_main_cat","doc_auth","entering_keyword"];    
          // Trim and sanitize doc_title
        if (isset($this->post["doc_title"])) {
        $this->post["doc_title"] = trim(preg_replace('/\s+/', ' ', $this->post["doc_title"]));

        // Validate: title must have at least one alphanumeric or Tamil character
        if (!preg_match('/[\p{L}\p{N}]/u', $this->post["doc_title"])) {
            \CustomErrorHandler::triggerInvalid("Document Title must contain at least one valid character.");
        }

        // Check for duplicate doc_title
        $existing = $this->_document_helper->getAllData("doc_title = :dt", ["dt" => $this->post["doc_title"]]);
        if (!empty($existing)) {
            \CustomErrorHandler::triggerInvalid("Duplicate Document already exists.");
        }
      }

        $doc_main_cat = isset($this->post["doc_main_cat"]) ? intval($this->post["doc_main_cat"]) : 1;
        if($doc_main_cat ==1){
            $validate_columns[] = "uploaded_file";
        }  
        // do validations
        $this->_document_helper->validate($this->_document_helper->getValidations(),$validate_columns,$this->post);
        // columns to be inserted
        $this->post["app_id"] = 0;
        $columns = ["doc_title","doc_type","entering_keyword",
         "doc_main_cat", "created_time",
         "created_by","doc_status","app_id"];   
        if($doc_main_cat ==2){
            $columns[] = "wo_no";
            $columns[] = "wo_value";
            $columns[] = "wo_name";
            $columns[] = "wo_type";
            $columns[] = "wo_start_from";
            $columns[] = "wo_start_to";
            $columns[] = "contractor_name";
            $columns[] = "work_type";
            $this->post["wo_start_from"] = Data::post_data("wo_start_from", "DATE");
            $this->post["wo_start_to"] = Data::post_data("wo_start_to", "DATE");
        }  
        $this->post["doc_status"] = 10;
        // enabling overall permission
        //$this->check_permission_value();
        // begin transation
        $this->db->_db->Begin();
        // insert and get id
        $id = $this->_document_helper->insert($columns,$this->post);
        // process the file
        $file_path = $this->_document_helper->getFullFile($id);
        if( isset($_FILES["uploaded_file"])){
        // move the uploaded file to path 
        $stored_file_path = SmartFileHelper::moveSingleFile("uploaded_file",$file_path);
        // update the file path in table
        $update_columns = ["doc_loc"];
        $update_data = ["doc_loc"=>$stored_file_path];
        $this->_document_helper->update($update_columns,$update_data,$id);   
        } 
        // insert the permissions 
        $this->insert_permissions($id);
         // insert authors
        if(isset($this->post["doc_auth"]) && is_array($this->post["doc_auth"])){
            $this->_document_helper->insertAuthors($id, $this->post["doc_auth"]);
        } 
        // commit the transaction and 
        $this->db->_db->commit();
        // add log
        $this->addLog("UPLOADED A DOCUMENT","",SmartAuthHelper::getLoggedInUserName());
        //
        $this->response($id);
    }
        

    /**
     * 
     */
    public function update(){
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if($id < 1){
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $columns = ["doc_status","entering_keyword"];
        // do validations
        $this->_document_helper->validate(DocumentHelper::validations,$columns,$this->post);
        // add columns
        $columns[] = "updated_by";
        $columns[] = "last_modified_time";
        // insert and get id
        $id = $this->_document_helper->update($columns,$this->post,$id);
        //
        $this->insert_permissions($id);
         // add log
         $this->addLog("UPDATED A DOCUMENT","",SmartAuthHelper::getLoggedInUserName());
        //
        $this->response($id);
    }
    
    public function getAll(){ 
        // check the mode received from router
        $sql = "";
        $data_in = [];
        $mode = isset($this->params["mode"]) ? $this->params["mode"] : "user";
        $status = isset($this->params["status"]) ? $this->params["status"] : [100];
        switch ($mode) {
                // indicates the logged user data
            case 'user':
                $sql = "t1.created_by=:user_id";
                $data_in = ["user_id" => SmartAuthHelper::getLoggedInId()];
                break;
            case 'app':
                $sql = "t1.app_id=:user_id AND doc_status IN (".implode(",", $status).")";
                $data_in = ["user_id" => SmartAuthHelper::getLoggedInId()];
                break;
            case 'admin':
                $sql = "doc_status IN (".implode(",", $status).")";
                break;
            default:
                break;
        }
        $data = $this->_document_helper->getAllData($sql,$data_in);
        foreach($data as $obj){
            $obj->authors = $this->_document_helper->getAuthors($obj->ID);
        }
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
        $data = $this->_document_helper->getOneData($id);
        // gettng all permissions data 
         if(isset($data->ID)){
            $data->permissions = $this->_doc_perm_helper->getAllPermissions($data->ID);
         }

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
        $this->_document_helper->deleteOneId($id);
         // add log
         $this->addLog("DELETED A DOCUMENT","",SmartAuthHelper::getLoggedInUserName());
        //
        $out = new \stdClass();
        $out->msg = "Deleted Successfully";
        $this->response($out);
    }

    private function getPermission($id,$type){
        // check for admin role 
        if(SmartAuthHelper::checkRole(["ADMIN"])){
            return true;
        }
        $user_id = SmartAuthHelper::getLoggedInId();
        // check for author first 
        if($this->_document_helper->checkAuthor($id,$user_id)){
            return true;
        }
        $roles = SmartAuthHelper::getRolesArr();
        $user = $this->_doc_perm_helper->check_permission($type,"USER",$id,$user_id,$roles);
        $role = $this->_doc_perm_helper->check_permission($type,"ROLES",$id,$user_id,$roles);
        return $user || $role;               
    }

    /*
    public function getDocByType()
    {
            
        $type = isset($this->post["type"]) ? intval($this->post["type"]) : 0;
        $doc_main_cat = isset($this->post["doc_main_cat"]) ? intval($this->post["doc_main_cat"]) : 1;
       // if($type < 1){
           // \CustomErrorHandler::triggerInvalid("Invalid type");
       // }    
         // insert and get id
        $data = $this->_document_helper->getDocType($type, $doc_main_cat);
       // var_dump($data);
         // loop over and get the perimission details 
        $out = [];
         foreach($data as $obj){
               $obj->view_perm = $this->getPermission($obj->ID,"VIEW");
               $obj->download_perm = $this->getPermission($obj->ID,"DOWNLOAD");
               $obj->file_check = $this->file_check($obj->ID); 
                 $obj->authors = $this->_document_helper->getAuthors($obj->ID);
               $out[] = $obj;
         }
         //var_dump($out);
        $this->response($out);
    }
    */
    public function getDocByType()
    {
    $type = isset($this->post["type"]) ? intval($this->post["type"]) : 0;
    $doc_main_cat = isset($this->post["doc_main_cat"]) ? intval($this->post["doc_main_cat"]) : 1;

    // fetch docs by type & category
    $data = $this->_document_helper->getDocType($type, $doc_main_cat);

    $out = [];
    foreach ($data as $obj) {
        // permissions
        $obj->view_perm = $this->getPermission($obj->ID, "VIEW");
        $obj->download_perm = $this->getPermission($obj->ID, "DOWNLOAD");

        // file exists or not
        $obj->file_check = $this->file_check($obj->ID);

        // authors
        $obj->authors = $this->_document_helper->getAuthors($obj->ID);

        // 🔹 get view & download count from DocViewCountHelper
        $docViewHelper = new \Site\Helpers\DocViewCountHelper($this->db);

        $obj->view_count = $docViewHelper->getCountByAction($obj->ID, "VIEW");
        $obj->download_count = $docViewHelper->getCountByAction($obj->ID, "DOWNLOAD");

        $out[] = $obj;
    }

    $this->response($out);
  }


    private function file_check($id){
        $pdf_path =  $this->_document_helper->getFullFile($id) .".pdf";
        return file_exists(SmartFileHelper::getDataPath()  . $pdf_path) ? true : false;
    }
    
     /**
     * 
     */
    public function getDocByCategoryAndType()
    { 
        $category = isset($this->post["category"]) ? $this->post["category"] : "Engineering";  
        $type = isset($this->post["type"]) ? intval($this->post["type"]) : 0;  
         // insert and get id
         $data = $this->_document_helper->getDocByCategoryAndType($category,$type);
         // loop over and get the perimission details 
        $out = [];
         foreach($data as $obj){
               $obj->authors = $this->_document_helper->getAuthors($obj->ID);
               $obj->view_perm = $this->getPermission($obj->ID,"VIEW");
               $obj->download_perm = $this->getPermission($obj->ID,"DOWNLOAD");  
               $obj->file_check = $this->file_check($obj->ID);            
               $out[] = $obj;
         }
        $this->response($out);
    }
     /**
     * 
     */
    /*
    public function getDoc(){
        $id = isset($this->post["id"]) ? $this->post["id"] : 0;
        if($id < 0){
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $data = $this->_document_helper->getOneData($id);
        // 
        $pdf_path =  $this->_document_helper->getFullFile($id) .".pdf";
       // echo $pdf_path;
        $this->responseFileBase64(SmartFileHelper::getDataPath()  . $pdf_path);
       //responsePdf
    }
       */

   public function getDoc(){
    $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
    if($id < 1){
        \CustomErrorHandler::triggerInvalid("Invalid ID");
    }

    // fetch document details
    $data = $this->_document_helper->getOneData($id);
    if (!isset($data->ID)) {
        \CustomErrorHandler::triggerInvalid("Document not found");
    }

    // get action type (VIEW or DOWNLOAD) -> still log but doesn't affect response
    $action = isset($this->post["action"]) ? strtoupper($this->post["action"]) : "VIEW";

    // insert into view_count table
    $docViewHelper = new \Site\Helpers\DocViewCountHelper($this->db);
    $columns = ["doc_id", "sd_mt_userdb_id", "action_type", "created_by", "created_time"];
    $insertData = [
        "doc_id" => $id,
        "sd_mt_userdb_id" => SmartAuthHelper::getLoggedInId(),
        "action_type" => $action,
        "created_by" => SmartAuthHelper::getLoggedInId(),
        "created_time" => date("Y-m-d H:i:s")
    ];
    $docViewHelper->insert($columns, $insertData);

    // build file path
    $pdf_path = SmartFileHelper::getDataPath() . $this->_document_helper->getFullFile($id) . ".pdf";
    if(!file_exists($pdf_path)){
        \CustomErrorHandler::triggerInvalid("File not found");
    }

    // Stream the file as base64 to avoid memory issues with large files.
    $this->responseFileBase64Streamed($pdf_path);
    }



    public function getDocNew(){
       // http://localhost/igcardoc_backend/api/document/get_doc
       $id =  79;
       if($id < 0){
           \CustomErrorHandler::triggerInvalid("Invalid ID");
       }
      // $data = $this->_document_helper->getOneData($id);
       // 
       $pdf_path =  $this->_document_helper->getFullFile($id) .".pdf";
       //
       $this->responsePdfNew($pdf_path);
    }

   
    public function updateApp()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $action = isset($this->post["action"]) ? ($this->post["action"]) : "";
        $columns = ["doc_status","app_time"];
        //
        $dt = ["doc_status"=>$action=="approve" ? 10 : 6];
        // add columns
        $columns[] = "updated_by";
        $columns[] = "last_modified_time";
        // insert and get id
        $id = $this->_document_helper->update($columns, $dt, $id);
        // add log
        $this->addLog("UPDATED A DOCUMENT STATUS ", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }

}