<?php 

namespace Site\Controller;

use Core\BaseController;
use Core\Helpers\SmartFileHelper;

use Core\Helpers\SmartAuthHelper;
use Site\Helpers\SiteHelper as SiteHelper;


class SiteController extends BaseController{
    
    private SiteHelper $_site_helper;

    function __construct($params)
    {
        parent::__construct($params);
        // 
        $this->_site_helper = new SiteHelper($this->db);
    }

   /**
     * 
     */
    
    public function insert(){

        foreach($this->post as $key=>$value){
            // check whether key exists in settings : if exists get id
            $exists_data = $this->_site_helper->getOneSettingData($key);
            // var_dump($exists_data);
            $data = ["setting_value"=>$value];            
            if(!$exists_data){
                $columns = [ "setting_name","setting_value","created_by"];
                $data["setting_name"] = $key;
                $id = $this->_site_helper->insert($columns,$data);
            }else{
                $id = $exists_data->ID;
                $columns =  [ "setting_value","last_modified_time"];
                $this->_site_helper->update($columns,$data,$id);
            }

        }
        // add log
        $this->addLog("ADDED A NEW SETTING","",SmartAuthHelper::getLoggedInUserName());
        //
        $this->response("added");
    }
    

    public function insertOrg(){
        // load the filed
        $org_file_one = $this->_site_helper->getOrgFileOne(1);
        $org_file_two = $this->_site_helper->getOrgFileTwo(1);
        $dpr_file = $this->_site_helper->getDprFile(1);
        //
        $iibcc_file = $this->_site_helper->getIibccFile(1);
        $lpc_file = $this->_site_helper->getLpcFile(1);
    
        $stored_file_path_one = SmartFileHelper::moveSingleFile("uploaded_file_org_one", $org_file_one );
        $stored_file_path_one = SmartFileHelper::moveSingleFile("uploaded_file_org_two", $org_file_two);
        $stored_file_path_one = SmartFileHelper::moveSingleFile("uploaded_file_dpr", $dpr_file);
        //
        $stored_file_path_one = SmartFileHelper::moveSingleFile("uploaded_file_iibcc", $iibcc_file);
        $stored_file_path_one = SmartFileHelper::moveSingleFile("uploaded_file_lpc", $lpc_file);



        // only insert function there is not update : setting will come in the form of array 
        $data_in = [
            "uploaded_file_org_one"=>"org_one_file.pdf",
            "uploaded_file_org_two"=>"org_two_file.pdf",
            "uploaded_file_dpr"=>"dpr_file.pdf",
            "uploaded_file_iibcc"=>"iibcc_file.pdf",
            "uploaded_file_lpc"=>"lpc_file.pdf"
        ];
        foreach( $data_in as $key=>$value){
            // check whether key exists in settings : if exists get id
            $exists_data = $this->_site_helper->getOneSettingData($key);
            // var_dump($exists_data);
            $data = ["setting_value"=>$value];            
            if(!$exists_data){
                $columns = [ "setting_name","setting_value","created_by"];
                $data["setting_name"] = $key;
                $id = $this->_site_helper->insert($columns,$data);
            }else{
                $id = $exists_data->ID;
                $columns =  [ "setting_value","last_modified_time"];
                $this->_site_helper->update($columns,$data,$id);
            }

        }
        // add log
        $this->addLog("ADDED A NEW SETTING","",SmartAuthHelper::getLoggedInUserName());
        //
        $this->response("added");
    }

    public function getAllOrg()
   {
    $out = new \stdClass();

    $out->uploaded_file_org_one =
        $this->_site_helper->getFileBase64BySetting("uploaded_file_org_one");

    $out->uploaded_file_org_two =
        $this->_site_helper->getFileBase64BySetting("uploaded_file_org_two");

    $out->uploaded_file_dpr =
        $this->_site_helper->getFileBase64BySetting("uploaded_file_dpr");

    $out->uploaded_file_iibcc =
        $this->_site_helper->getFileBase64BySetting("uploaded_file_iibcc");

    $out->uploaded_file_lpc =
        $this->_site_helper->getFileBase64BySetting("uploaded_file_lpc");

    $this->response($out);
    }




    public function getAll(){      
        // this function as to be modified 
        $data = $this->_site_helper->getAllData();
        //var_dump($data);
        $out = new \stdClass();
        foreach($data as $obj){
            $out->{$obj->setting_name} = $obj->setting_value;
        }
        $this->response($out);
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
        $data = $this->_site_helper->getOneData($id);
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
        $this->_site_helper->deleteOneId($id);
        // add log
        $this->addLog("DELETED A SETTING","",SmartAuthHelper::getLoggedInUserName());
        //
        $out = new \stdClass();
        $out->msg = "Deleted Successfully";
        $this->response($out);
    }
    /**
     * 
     */
    public function getAllSelect(){      
        $select = ["ID as value,setting_name as label"];
        $data = $this->_site_helper->getAllData("",[],$select);
        $this->response($data);
    }

    public function getOrgOnePdf(){
        $id = 1;
        $pdf_path =  $this->_site_helper->getOrgFileOne($id) .".pdf";
       // echo $pdf_path;
        $this->responseFileBase64(SmartFileHelper::getDataPath()  . $pdf_path);
    }

    public function getOrgTwoPdf(){
        $id = 1;
        $pdf_path =  $this->_site_helper->getOrgFileTwo($id) .".pdf";
       // echo $pdf_path;
        $this->responseFileBase64(SmartFileHelper::getDataPath()  . $pdf_path);
    }
   
     public function getDprFile(){
        $id = 1;
        $pdf_path =  $this->_site_helper->getDprFile($id) .".pdf";
       // echo $pdf_path;
        $this->responseFileBase64(SmartFileHelper::getDataPath()  . $pdf_path);
    }
    public function getIibccFile(){
        $id = 1;
        $pdf_path =  $this->_site_helper->getIibccFile($id) .".pdf";
       // echo $pdf_path;
        $this->responseFileBase64(SmartFileHelper::getDataPath()  . $pdf_path);
    }
    public function getLpcFile(){
        $id = 1;
        $pdf_path =  $this->_site_helper->getLpcFile($id) .".pdf";
       // echo $pdf_path;
        $this->responseFileBase64(SmartFileHelper::getDataPath()  . $pdf_path);
    }



}