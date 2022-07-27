<?php

namespace App\controller;
use App\Models;
use Illuminate\Database\Capsule\Manager as DB;

class AppZona
{   
    protected $container;

    function __construct($container){
        $this->container = $container;
    }

    function index(){

        $data = $this->container->DataMikrotick->getAlls();

        return $this->container->twig->render($this->container->response,  "app_zona/index.twig", ["data" => $data]);
    
    }

    function getMikros(){
        $data = $this->container->DataMikrotick->getAlls();

        return json_encode($data);
    }

    function TicketsActivos(){
        return $this->container->twig->render($this->container->response, "app_zona/administracion/ticketsactivos.twig");
    }

    function MacActivas(){
        return $this->container->twig->render($this->container->response, "app_zona/administracion/macsactivas.twig");
    }
}