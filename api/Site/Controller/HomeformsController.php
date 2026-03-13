<?php 

namespace Site\Controller;

use Core\BaseController;
use Core\Helpers\SmartFileHelper;
use Core\Helpers\SmartAuthHelper;
use Site\Helpers\HomeformsHelper;


class HomeformsController extends BaseController{
  
  private HomeformsHelper $_helper;
    function __construct($params)
    {
        parent::__construct($params);
        // 
        $this->_helper = new HomeformsHelper($this->db);
    }

   /**
     * 
     */
    /*
    public function insert(){
        $validate_columns = [ "title","uploaded_file"];        
        // do validations
        $this->_helper->validate(HomeformsHelper::validations,$validate_columns,$this->post);
        $columns = [ "title","created_by","created_time"]; 
         // insert and get id
         $id = $this->_helper->insert($columns,$this->post);
          // process the file
        $file_path = $this->_helper->getFullFile($id);
        if( isset($_FILES["uploaded_file"])){
        // move the uploaded file to path 
        $stored_file_path = SmartFileHelper::moveSingleFile("uploaded_file",$file_path);
        // update the file path in table
        $update_columns = ["doc_loc"];
        $update_data = ["doc_loc"=>$stored_file_path];
        $this->_helper->update($update_columns,$update_data,$id);   
        } 
         // add log
        $this->addLog("UPLOADED A LICENSE DOCUMENT ","",SmartAuthHelper::getLoggedInUserName());
        //
         $this->response($id);
    }
         */
        public function insert(){
    $validate_columns = [ "title","uploaded_file"];        
    $this->_helper->validate(HomeformsHelper::validations,$validate_columns,$this->post);

    $columns = [ "title","created_by","created_time"]; 
    $id = $this->_helper->insert($columns,$this->post);

    // file path
    $file_path = $this->_helper->getFullFile($id);

    // CREATE DIRECTORY IF NOT EXISTS
    $dir = dirname($file_path);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    if(isset($_FILES["uploaded_file"])){

        // move file
        $stored_file_path = SmartFileHelper::moveSingleFile("uploaded_file", $file_path);

        // update DB
        $update_columns = ["doc_loc"];
        $update_data = ["doc_loc" => $stored_file_path];
        $this->_helper->update($update_columns, $update_data, $id);   
    }

    $this->addLog("UPLOADED A LICENSE DOCUMENT ","",SmartAuthHelper::getLoggedInUserName());

    $this->response($id);
}

    /**
     * 
     */

    public function getAll(){      
        $data = $this->_helper->getAllData();
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
        $data = $this->_helper->getOneData($id);
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
        $this->_helper->deleteOneId($id);
        // add log
        $this->addLog("REMOVED A LICENSE DOCUMENT","",SmartAuthHelper::getLoggedInUserName());
        //
        $out = new \stdClass();
        $out->msg = "Removed Successfully";
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
        $data = $this->_helper->getOneData($id);
        // 
        $pdf_path =  $this->_helper->getFullFile($id) .".pdf";
       // echo $pdf_path;
        $this->responseFileBase64(SmartFileHelper::getDataPath()  . $pdf_path);
    }
    */
    public function getDoc() {
    $id = isset($this->post["id"]) ? $this->post["id"] : 0;

    if ($id <= 0) {
        \CustomErrorHandler::triggerInvalid("Invalid ID");
    }

    // get database record
    $data = $this->_helper->getOneData($id);

    if (!$data || empty($data->doc_loc)) {
        \CustomErrorHandler::triggerInvalid("Document not found");
    }

    // Reconstruct the file path using the helper.
    // getFullFile() returns the base path, and moveSingleFile stored it with an extension.
    $file_path = $this->_helper->getFullFile($id) . '.' . pathinfo($data->doc_loc, PATHINFO_EXTENSION);

    // full system path
    $full_path = SmartFileHelper::getDataPath() . $file_path;

    if (!file_exists($full_path)) {
        \CustomErrorHandler::triggerInvalid("File does not exist on the server.");
    }

    // Get the original filename from the database record.
    $filename = $data->doc_loc;

    // Create a response object that includes the base64 content, filename, and MIME type.
    $response_data = new \stdClass();
    $response_data->content = base64_encode(file_get_contents($full_path)); // This can consume a lot of memory for large files.
    $response_data->filename = $filename;
    $response_data->mime_type = $this->getMimeTypeWithFallback($full_path);

    $this->response($response_data);
}

/**
 * Determines the MIME type of a file, with a fallback for common types.
 *
 * @param string $filepath The absolute path to the file.
 * @return string The MIME type of the file.
 */
private function getMimeTypeWithFallback(string $filepath): string
{
    // First, try the standard PHP function.
    if (function_exists('mime_content_type')) {
        $mime_type = mime_content_type($filepath);
        if ($mime_type !== 'application/octet-stream') {
            return $mime_type;
        }
    }

    // If the standard function fails or is not available, use a fallback map.
    $extension = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
    $mime_map = [
        'pdf'  => 'application/pdf',
        'doc'  => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xls'  => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'png'  => 'image/png',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
    ];

    return $mime_map[$extension] ?? 'application/octet-stream';
}
}