<?php 
namespace App\Controller;


class Pagos
{   
    protected $container;

	public function __construct($container)
	{
		$this->container = $container;
	}

    function cobranza(){
        $datos = new \App\Data;
        $args = [];

        $paran = $this->container->request->getParams();
        //$settings = $this->container->get('settings')['renderer'];
        //$settings["template"] = "tabla.php";

        /*if(file_exists($settings['template_path'].$args["name"].".php") && $args["name"] != 'index'){
            $settings["template"] = $args["name"].".php";
        }*/
        
        $args["data"] = $datos->obtener_pagos($paran);

        $args["meses"] =  $datos->obtener_meses();

        isset($paran["id_mes"]) ? $args["id_mes"] = $paran["id_mes"] : $args["id_mes"] = 0 ;

        return $this->container->twig->render($this->container->response,  "vistas_pagos/vista_cobranza.twig", $args);
    }

    function solventes(){
        $datos = new \App\Data;
        $args = [];
        //$settings = $this->container->get('settings')['renderer'];
        //$settings["template"] = "tabla.php";

        /*if(file_exists($settings['template_path'].$args["name"].".php") && $args["name"] != 'index'){
            $settings["template"] = $args["name"].".php";
        }*/

        $args["data"] =  $datos->datos_registros();

        return $this->container->twig->render($this->container->response,  "tabla_solventes.twig", $args);
    }

    function meses(){
        $datos = new \App\Data;
        $args = [];
        //$settings = $this->container->get('settings')['renderer'];
        //$settings["template"] = "tabla.php";

        /*if(file_exists($settings['template_path'].$args["name"].".php") && $args["name"] != 'index'){
            $settings["template"] = $args["name"].".php";
        }*/

        $args["data"] =  $datos->obtener_meses();

        return $this->container->twig->render($this->container->response,  "vistas_pagos/tabla_meses.twig", $args);
    }

    function cobrar(){

        $datos = new \App\Data;
        $args = [];

        $args["meses"] =  $datos->obtener_meses();
        return $this->container->twig->render($this->container->response,  "cobrar.twig", $args);
    }

    function Cuentas(){
        $datos = new \App\Data;
        $args = [];
        //$settings = $this->container->get('settings')['renderer'];
        //$settings["template"] = "tabla.php";

        /*if(file_exists($settings['template_path'].$args["name"].".php") && $args["name"] != 'index'){
            $settings["template"] = $args["name"].".php";
        }*/

        $args["data"] =  $datos->obtener_cuentas();

        return $this->container->twig->render($this->container->response,  "tabla_cuentas.twig", $args);
    }

    function agregar(){
        $data = new \App\Data;
        $evaluar = $this->container->request->getParams();

        $property = $evaluar['funcion'];

        if(method_exists($data, $property)){
            $resp[0] = $data->$property($evaluar['data']);
        }else{
            $resp = false;
        }
        
        return json_encode($resp);
    }

    function eliminar(){

        $data = new \App\Data;
        $evaluar = $this->container->request->getParams();
        $delete = ["true" => 0, "false" => 0];

        $property = $evaluar['funcion'];

        if(method_exists($data, $property)){

            foreach ($evaluar['data']["id"] as $key => $value) {
                $data->$property($value) == true ? $delete["true"] = $delete["true"]+1 : $delete["false"] = $delete["false"]+1 ;
            }
            
            $evaluar["eliminados"] = $delete; 
        }else{
            $evaluar = false;
        }
        
        
        return json_encode($evaluar);

    }

    function modificar(){

        $data = new \App\Data;
        $evaluar = $this->container->request->getParams();
        $property = $evaluar['funcion'];

        if(method_exists($data, $property)){
            
            $resp = $data->$property($evaluar['data']);

        }else{
            $resp = false;
        }
        
        return json_encode($resp);
    }

    function obtener(){
        $data = new \App\Data;
        $evaluar = $this->container->request->getParams();


        $property = $evaluar['funcion'];

        if(method_exists($data, $property)){
            $resp = $data->$property($evaluar['data']);
        }else{
            $resp = false;
        }
       
        return json_encode($resp);

    }

    function historico_cuentas(){
        $datos = new \App\Data;
        $args = [];
        //$settings = $this->container->get('settings')['renderer'];
        //$settings["template"] = "tabla.php";

        /*if(file_exists($settings['template_path'].$args["name"].".php") && $args["name"] != 'index'){
            $settings["template"] = $args["name"].".php";
        }*/
        $args["data"] = $datos->obtener_mov_cuentas();
        $args["meses"] =  $datos->obtener_meses();

        return $this->container->twig->render($this->container->response,  "tabla_historico_cuentas.twig", $args);
    }
} 