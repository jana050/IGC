<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of router
 *
 * @author kms
 */
namespace Core;

class Router {
    //put your code here
    private $urlarr;
    
    private static $_instance = null;
    
    static public function add_route(){
        $obj = self::get_instance();
        return $obj->get_router_classes();
    }
    
     public static function get_instance(){
           if(!isset(self::$_instance)){
              self::$_instance = new self();
           }
           return self::$_instance;
       }
    
    private function get_router_classes(){
       // parse url
        $this->parse_url_data();
        // get module name
        $modname = $this->get_module_name();
        // get function name 
        $function_name = $this->get_function_name();
        //
        $parameres = $this->extract_paramters();
        //
        return ["Mod_Name"=>$modname, "Func_Name"=>$function_name,"Param_var"=>$parameres];
    }
    
    /**
    * 
    */
    private function parse_url_data(){
        // get the request url 
        $url = isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:NULL;
        // remove upto api part from the site
        $url = str_replace(SITEDIR . DS . "api" . DS , "", $url);
        //
        $this->urlarr = array_filter(explode("/", $url),array($this,'filter_url_param')); 
        //
    }  
    /**
     * 
     * @return type
     */
    private function get_module_name(){
        $mod_name = isset($this->urlarr[1]) && strlen(trim($this->urlarr[1])) > 1 ? 
                $this->urlarr[1] : "Index"; 
        $mod_name = str_replace("__", "\\", $mod_name);
        return ucfirst($mod_name);
    }
    
    private function get_function_name(){
        $mod_name = isset($this->urlarr[2]) && strlen(trim($this->urlarr[2])) > 1 ?  $this->urlarr[2] : "Index"; 
        return strtolower($mod_name);
    }
    
    /**
     * 
     * @param type $var
     * @return type
     */
    private function filter_url_param($var){
      return ($var !== NULL && $var !== FALSE && $var !== '');
    }
    /**
     * 
     * @param type $input
     * @return type
     */
    private function santize_parameter($input){
         $output = preg_replace( '/[^a-zA-Z0-9\-\_]/i', '', $input);
         return $output;         
    }
    /**
     * 
     * @return type
     */
     public function extract_paramters(){
        $param = array();        
        if(isset($this->urlarr[3])){ 
            $param["firstParam"] = $this->santize_parameter($this->urlarr[3]);
        }
         if(isset($this->urlarr[4])){ 
            $param["secondParam"] = $this->santize_parameter($this->urlarr[4]);
         }
         if(isset($this->urlarr[5])){ 
            $param["thirdParam"]= $this->santize_parameter($this->urlarr[5]);
         }
         if(isset($this->urlarr[6])){ 
            $param["fourthParam"] = $this->santize_parameter($this->urlarr[6]);
         }
        return $param;
     }
    
    
}
