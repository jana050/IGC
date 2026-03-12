<?php

namespace Site\Controller;

use Core\BaseController;


use Site\Helpers\HomeImagesHelper as ActivityHelper;
use Core\Helpers\SmartAuthHelper;
use Core\Helpers\SmartFileHelper;

class HomeImagesController extends BaseController
{

    private ActivityHelper $_helper;

    function __construct($params)
    {
        parent::__construct($params);
        // 
        $this->_helper = new ActivityHelper($this->db);
    }

    /**
     * 
     */
    /*
    public function insert()
    {
        $valid_columns = ["uploaded_file"];
        // do validations
        $this->_helper->validate(ActivityHelper::validations, $valid_columns, $this->post);
        // add other columns
        //  $columns[]="created_by"; 
        //  $columns[]="created_time"; 
        // insert and get id
        $columns = ["home_image"];
        $id =  $this->_helper->insert($columns, $this->post);

        $file_path = $this->_helper->getFullFile($id);
        // move the uploaded file to path 
        $stored_file_path = SmartFileHelper::moveSingleFile("uploaded_file", $file_path);
       // echo $stored_file_path;
        // update the file path in table
        $update_columns = ["home_image"];
        $update_data = ["home_image" => $stored_file_path];
        $this->_helper->update($update_columns, $update_data, $id);
        // $filename = "../assets/images/test.png";
        //file_put_contents($filename, $this->post["home_image"]);
        //  $this->addLog("INSERTED ACTIVITY","",SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }
     */
   public function insert()
   {
    $valid_columns = ["uploaded_file", "title"];
    // Validate
    $this->_helper->validate(ActivityHelper::validations, $valid_columns, $this->post);

    // Insert first to get ID
    $columns = ["home_image", "title"];
    $id = $this->_helper->insert($columns, $this->post);

    $title_type = isset($this->post["title"]) ? intval($this->post["title"]) : 0;
    $file_type = pathinfo($_FILES["uploaded_file"]["name"], PATHINFO_EXTENSION);
    $file_type = strtolower($file_type);

    $file_path = $this->_helper->getFullFile($id);

    if ($title_type === 1) {
        // IMAGE case
        if (!in_array($file_type, ['png', 'jpeg', 'jpg'])) {
            \CustomErrorHandler::triggerInvalid("Only image files allowed for title = 1");
        }
        $stored_file_path = SmartFileHelper::moveSingleFile("uploaded_file", $file_path . "." . $file_type);

    } elseif ($title_type === 2) {
        // VIDEO case
        if (!in_array($file_type, ['mp4', 'avi', 'mov'])) {
            \CustomErrorHandler::triggerInvalid("Only video files allowed for title = 2");
        }
        $stored_file_path = SmartFileHelper::moveSingleFile("uploaded_file", $file_path . "." . $file_type);

    } else {
        \CustomErrorHandler::triggerInvalid("Invalid title value (must be 1 or 2)");
    }

    // Update DB with stored file path
    $update_columns = ["home_image"];
    $update_data = ["home_image" => $stored_file_path];
    $this->_helper->update($update_columns, $update_data, $id);

    $this->response($id);
    }


   /*
    public function update()
    {
        $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
        if ($id < 1) {
            \CustomErrorHandler::triggerInvalid("Invalid ID");
        }
        $columns = ["uploaded_file"];
        // do validations
        $this->_helper->validate(ActivityHelper::validations, $columns, $this->post);
        // add other columns
        //   $columns[]="last_modified_time";
        // insert and get id
        $file_path = $this->_helper->getFullFile($id);
        // move the uploaded file to path 
        $stored_file_path = SmartFileHelper::moveSingleFile("uploaded_file", $file_path);
       // echo $stored_file_path;
        // update the file path in table
        $update_columns = ["home_image"];
        $update_data = ["home_image" => $stored_file_path];
        $this->_helper->update($update_columns, $update_data, $id);

        $this->addLog("UPDATED ACTIVITY", "", SmartAuthHelper::getLoggedInUserName());
        $this->response($id);
    }
    */
   public function update()
  {
    $id = isset($this->post["id"]) ? intval($this->post["id"]) : 0;
    if ($id < 1) {
        \CustomErrorHandler::triggerInvalid("Invalid ID");
    }

    $columns = ["uploaded_file", "title"];
    $this->_helper->validate(ActivityHelper::validations, $columns, $this->post);

    $title_type = isset($this->post["title"]) ? intval($this->post["title"]) : 0;
    $file_type = pathinfo($_FILES["uploaded_file"]["name"], PATHINFO_EXTENSION);
    $file_type = strtolower($file_type);

    $file_path = $this->_helper->getFullFile($id);

    if ($title_type === 1) {
        // IMAGE case
        if (!in_array($file_type, ['png', 'jpeg', 'jpg'])) {
            \CustomErrorHandler::triggerInvalid("Only image files allowed for title = 1");
        }
        $stored_file_path = SmartFileHelper::moveSingleFile("uploaded_file", $file_path . "." . $file_type);

    } elseif ($title_type === 2) {
        // VIDEO case
        if (!in_array($file_type, ['mp4', 'avi', 'mov'])) {
            \CustomErrorHandler::triggerInvalid("Only video files allowed for title = 2");
        }
        $stored_file_path = SmartFileHelper::moveSingleFile("uploaded_file", $file_path . "." . $file_type);

    } else {
        \CustomErrorHandler::triggerInvalid("Invalid title value (must be 1 or 2)");
    }

    $update_columns = ["home_image", "title"];
    $update_data = [
        "home_image" => $stored_file_path,
        "title" => $title_type
    ];
    $this->_helper->update($update_columns, $update_data, $id);

    $this->addLog("UPDATED ACTIVITY", "", SmartAuthHelper::getLoggedInUserName());
    $this->response($id);
    }



    public function getAll()
    {
        // insert and get id
      
        $data =  $this->_helper->getAllData();
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
        $data =  $this->_helper->getOneData($id);
         if (isset($data->ID)) {
            $data->home_image = json_decode($data->home_image);
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
        //
        $this->addLog("DELETED ACTIVITY", "", SmartAuthHelper::getLoggedInUserName());
        $out = new \stdClass();
        $out->msg = "Deleted Successfully";
        $this->response($out);
    }
    /**
     * 
     */
    public function getOneImage()
    {
        $id = isset($this->params["id"]) ? $this->params["id"] : 0;
       
        $data =  $this->_helper->getOneData($id);
        // 
        $pdf_path =  $this->_helper->getFolder($id). $data->home_image;
        // echo $pdf_path;
        $this->responseImage($pdf_path);
       // $final_path = SmartFileHelper::getDataPath().DS . $pdf_path;
      //  $this->responseFileBase64($final_path);
    }
    /** */
    
    public function getOneImageNew()
    {
        $id = isset($this->params["id"]) ? $this->params["id"] : 0;
       
        $data =  $this->_helper->getOneData($id);
        // 
        $pdf_path =  $this->_helper->getFolder($id). $data->home_image;
        // echo $pdf_path;
       // $this->responseImage($pdf_path);
        $final_path = SmartFileHelper::getDataPath(). $pdf_path;
        $this->responseFileBase64($final_path);
    }
    



}
