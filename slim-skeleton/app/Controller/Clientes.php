<?php

namespace App\controller;

class Clientes
{   
    protected $container;

    function __construct($container){
        $this->container = $container;
    }

    function todos(){

        $datos = new \App\Data;
        $args = [];
        //$settings = $this->container->get('settings')['renderer'];
        //$settings["template"] = "tabla.php";

        /*if(file_exists($settings['template_path'].$args["name"].".php") && $args["name"] != 'index'){
            $settings["template"] = $args["name"].".php";
        }*/
        
        $args["data"] =  $datos->Clientes();

        return $this->container->twig->render($this->container->response,  "todos_clientes.twig", $args);
        
    }

    function activos(){
        $datos = new \App\Data;

        
        $args["data"] =  $datos->clientes_estado();

        return $this->container->twig->render($this->container->response,  "todos_clientes_activos.twig", $args);

    }

    function suspendidos(){
        $datos = new \App\Data;

        
        $args["data"] =  $datos->clientes_estado('Suspendido');

        return $this->container->twig->render($this->container->response,  "todos_clientes_activos.twig", $args);

    }

    function retirados(){
        $datos = new \App\Data;

        
        $args["data"] =  $datos->clientes_estado('Retirado');

        return $this->container->twig->render($this->container->response,  "todos_clientes_activos.twig", $args);

    }

    function intrusos(){
        $datos = new \App\Data;

        
        $args["data"] =  $datos->intrusos();

        return $this->container->twig->render($this->container->response,  "clientes_activos.twig", $args);
    }

    function opcionales(){
        $datos = new \App\Data;

        
        $args["data"] =  $datos->clientes_estado('Opcional');

        return $this->container->twig->render($this->container->response,  "todos_clientes_activos.twig", $args);

    }

    function add(){
        $data = new \App\Data;
        $evaluar = $this->container->request->getParams();

        $resp[0] = $data->crear_usuario($evaluar);
        
        return json_encode($resp);
    }

    function eliminar(){
        $data = new \App\Data;
        $evaluar = $this->container->request->getParams();
        $delete = ["true" => 0, "false" => 0];


        foreach ($evaluar["id"] as $key => $value) {
            $data->eliminar_usuario($value) == true ? $delete["true"] = $delete["true"]+1 : $delete["false"] = $delete["false"]+1 ;
        }
        $evaluar["eliminados"] = $delete; 
        
        return json_encode($evaluar);
    }

    function editar(){

        $data = new \App\Data;
        $evaluar = $this->container->request->getParams();
        
        $registro = $data->modifCliente($evaluar);

        if($registro){
            $registro = $evaluar;
        }

        return json_encode([$registro]);
    }

    function getdata(){

        $data = new \App\Data;
        $evaluar = $this->container->request->getParams();
        $registros = [];

        foreach ($evaluar["id"] as $key => $value) {
            $registros[$key] = $data->getCliente($value);
        }

   
        return json_encode($registros);
    }

    function obtenerclientes(){
        $datos = new \App\Data;

        $registros = $datos->Clientes();

        return json_encode($registros);
    }
}
