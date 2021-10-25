<?php
namespace App\Controller;

class Giros extends Controller
{   
    function maxjo(){

        $datos = new \App\Data;

        $a['data'] = $datos->obtener_girosconfirmados();
 
        return $this->container->twig->render($this->container->response,  "giros/maxjo.twig", $a);
    
    }

    function cargar(){

        $datos = new \App\Data;

        $a['data'] = $datos->obtener_girosp();

        return $this->container->twig->render($this->container->response,  "giros/cargar.twig", $a);
        
    }

    function maxjoentregados(){

        $datos = new \App\Data;

        $a['data'] = $datos->obtener_girosentregados();
 
        return $this->container->twig->render($this->container->response,  "giros/entregados.twig", $a);

    }
    
}
