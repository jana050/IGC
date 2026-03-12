<?php 

namespace Site\Controller;

use Core\BaseController;
use Core\Helpers\SmartGeneral;
use Core\Helpers\SmartData as Data;
use Core\Helpers\SmartAuthHelper;
use Site\Helpers\TypeHelper;
use Site\Helpers\DocumentHelper;
use Site\Helpers\MeetRoomHelper;


class TypeController extends BaseController{
    private TypeHelper $_type_helper;
    private DocumentHelper $_doc_helper;
    private MeetRoomHelper $_meet_room_helper;

    function __construct($params)
    {
        parent::__construct($params);
        // 
        $this->_type_helper = new TypeHelper($this->db);
        $this->_doc_helper = new DocumentHelper($this->db);
        $this->_meet_room_helper = new MeetRoomHelper($this->db);
    }

   /**
     * 
     */
    public function insert(){
        $columns = [ "type_name","type_value"];        
        //
        if(isset($this->post["type_name_sel"]) && $this->post["type_name_sel"]!=="OTHERS"){
                 $_POST["type_name"]= $this->post["type_name_sel"];
                 $this->post["type_name"] = $this->post["type_name_sel"];
        }
        // do validations
        $this->_type_helper->validate(TypeHelper::validations,$columns,$this->post);
        //
        $type_name = Data::post_data("type_name","STRING");
        $type_value = Data::post_data("type_value","STRING");
        $out = $this->_type_helper->getWithTypeNameTypeValue($type_name,$type_value);
         //      
         if($out){
             \CustomErrorHandler::triggerInternalError("Details Already Exist");
         }
         // insert and get id
         $id = $this->_type_helper->insert($columns,$this->post);
         // add log
        $this->addLog("ADDED A DOCUMENT TYPE","",SmartAuthHelper::getLoggedInUserName());
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
        $columns = ["type_value","type_name"];
         //
        if(isset($this->post["type_name_sel"]) && $this->post["type_name_sel"]!=="OTHERS"){
                 $_POST["type_name"]= $this->post["type_name_sel"];
                 $this->post["type_name"] = $this->post["type_name_sel"];
        }
        // do validations
        $this->_type_helper->validate(TypeHelper::validations,$columns,$this->post);
        //
        $type_name = Data::post_data("type_name","STRING");
        $type_value = Data::post_data("type_value","STRING");
        $out = $this->_type_helper->getWithTypeNameTypeValue($type_name,$type_value);
         //      
         if($out){
             \CustomErrorHandler::triggerInternalError("Details Already Exist");
         }
        // insert and get id
        $id = $this->_type_helper->update($columns,$this->post,$id);
        // add log
        $this->addLog("UPDATED A DOCUMENT TYPE","",SmartAuthHelper::getLoggedInUserName());
        //
        $this->response($id);
    }

    public function getAll(){      
        // insert and get id
        $sql = "";
        $data_in = [];
        if(isset($this->params["type"]) && strlen($this->params["type"])>2){
            $sql = "type_name=:type_name";
            $data_in = ["type_name"=>$this->params["type"]];
        }
        $data = $this->_type_helper->getAllData($sql,$data_in);
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
        $data = $this->_type_helper->getOneData($id);
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
        // existing check in meetroom
        $meet = $this->_meet_room_helper->getRoomName($id);
        if(!empty($meet)){
            \CustomErrorHandler::triggerInvalid("This Meet Room have Meetings");
        }
        // insert and get id
        $this->_type_helper->deleteOneId($id);
        // add log
        $this->addLog("DELETED A MEET ROOM","",SmartAuthHelper::getLoggedInUserName());
        //
        $out = new \stdClass();
        $out->msg = "Deleted Successfully";
    }    

     /**
     * 
     */
    public function getAllSelect(){      
        $select = ["ID as value,type_value as label"];
        $sql = "type_name=:type_name";
        $data_in =["type_name"=>$this->params["type"]];
        $data = $this->_type_helper->getAllData($sql,$data_in,$select);
        $this->response($data);
    }
    /**
     * 
     */
    public function getAllSelectTypes(){      
        $select = ["type_name as value,type_name as label"];       
        $data = $this->_type_helper->getAllData("",[],$select,"type_name","type_name ASC");
        $other = new \stdClass();
        $other->value="OTHER";
        $other->label="OTHER";
        $data[] = $other;
        $this->response($data);
    }

   


}