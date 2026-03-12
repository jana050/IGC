<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Site\Routes;


class SiteRouter{

    private $_routes=[];

    /**
     * 
     */
    private function doc_routes(){
        $controller = "DocController";
        $this->_routes["/doc/get_one/{id}"] = [$controller,"login"];        
    }
    

    private function get_all_routes(){
        $this->doc_routes();
        return $this->_routes;
    }


    /**
     * 
     */
    static public function getRoutes(){
            $obj = new self();
            return $obj->get_all_routes();
    } 

}

