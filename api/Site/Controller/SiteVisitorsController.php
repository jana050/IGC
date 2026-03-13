<?php 

namespace Site\Controller;

use Core\BaseController;
use Core\Helpers\SmartFileHelper;

use Core\Helpers\SmartAuthHelper;
use Site\Helpers\SiteVisitorsHelper;


class SiteVisitorsController extends BaseController{
    
    private SiteVisitorsHelper $_siteVisitors_helper;

    function __construct($params)
    {
        parent::__construct($params);
        // 
        $this->_siteVisitors_helper = new SiteVisitorsHelper($this->db);
    }

   /**
     * 
     */
    
   

    public function getAll(){      
        // this function as to be modified 
        $data = $this->_siteVisitors_helper->getAllData();
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
        $data = $this->_siteVisitors_helper->getOneData($id);
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
        $this->_siteVisitors_helper->deleteOneId($id);
        // add log
        $this->addLog("DELETED A SETTING","",SmartAuthHelper::getLoggedInUserName());
        //
        $out = new \stdClass();
        $out->msg = "Deleted Successfully";
        $this->response($out);
    }
   
public function getVisitorCount()
{
    $ip = $_SERVER['REMOTE_ADDR'];

    $this->_siteVisitors_helper->logUniqueVisitor($ip);

    $count = $this->_siteVisitors_helper->getTotalVisitorCount();

    $out = new \stdClass();
    $out->visitor_count = $count->count;

    $this->response($out);
}

   


}