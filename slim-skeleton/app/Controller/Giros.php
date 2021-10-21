<?php
namespace App\Controller;

class Giros extends Controller
{   
    function maxjo(){

        $datos = new \App\Data;


        $a['data'] = $datos->obtener_girosp();
 
        return $this->container->twig->render($this->container->response,  "giros/maxjo.twig", $a);
    
    }

    function cargar(){

        return $this->container->twig->render($this->container->response,  "giros/cargar.twig");
        
    }

    function maxjoentregados(){

        $datos = new \App\Data;


        $a['data'] = $datos->obtener_girosentregados();
 
        return $this->container->twig->render($this->container->response,  "giros/entregados.twig", $a);

    }
    
}
