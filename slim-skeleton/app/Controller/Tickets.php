<?php

namespace App\controller;

class Tickets
{   
    protected $container;

    function __construct($container){
        $this->container = $container;
    }


    function generar(){

        return $this->container->twig->render($this->container->response,  "tickets/generar.twig");
        
    }

    function generando(){
        
        $param = $this->container->request->getParams();
        
        if(!isset($param["tickets_nombre"]) | $param["tickets_nombre"] == ""){
            return "error tickets_nombre";
        }else if(!isset($param["tickets_user_mik"]) | $param["tickets_user_mik"] == ""){
            return "error tickets_user_mik";
        }else if(!isset($param["tickets_password_mik"]) | $param["tickets_password_mik"] == ""){
            return "error tickets_password_mik";
        }else if(!isset($param["tickets_ip_mik"]) | $param["tickets_ip_mik"] == ""){
            return "error tickets_ip_mik";
        }else if(!isset($param["tickets_plan"]) | $param["tickets_plan"] == ""){
            return "error tickets_plan";
        }else if(!isset($param["tickets_nun_tickets"]) | $param["tickets_nun_tickets"] == ""){
            return "error tickets_nun_tickets";
        }else if(!isset($param["tickets_fecha_generacion"]) | $param["tickets_fecha_generacion"] == ""){
            
            $param["tickets_fecha_generacion"] = date('Y-m-d');
            
        }
     
        $param["tickets_plan"] = json_decode($param["tickets_plan"]);

        $api_mikrotick = new \App\Helpers\Api_mikrotick;
        
        $api_mikrotick->crear_tickets($param);

    }

    function mikrotiks(){

        
        return $this->container->twig->render($this->container->response,  "tickets/add_mikrotik.twig");
    
    }

}

?>