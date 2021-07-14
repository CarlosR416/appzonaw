<?php 
namespace App\Controller;


class Pagos
{   
    protected $container;

	public function __construct($container)
	{
		$this->container = $container;
	}

    function pendientes(){
        $datos = new \App\Data;
        $args = [];
        //$settings = $this->container->get('settings')['renderer'];
        //$settings["template"] = "tabla.php";

        /*if(file_exists($settings['template_path'].$args["name"].".php") && $args["name"] != 'index'){
            $settings["template"] = $args["name"].".php";
        }*/
        $args["data"] = $datos->obtener_pagos();

        return $this->container->twig->render($this->container->response,  "tabla_pagos.twig", $args);
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

        return $this->container->twig->render($this->container->response,  "tabla_meses.twig", $args);
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

    function add(){
        $data = new \App\Data;
        $evaluar = $this->container->request->getParams();

        $resp[0] = $data->crear_pago($evaluar);
        
        return json_encode($resp);
    }

    function eliminar(){

        $data = new \App\Data;
        $evaluar = $this->container->request->getParams();
        $delete = ["true" => 0, "false" => 0];


        foreach ($evaluar["id"] as $key => $value) {
            $data->eliminar_pago($value) == true ? $delete["true"] = $delete["true"]+1 : $delete["false"] = $delete["false"]+1 ;
        }
        $evaluar["eliminados"] = $delete; 
        
        return json_encode($evaluar);

    }

    function editar(){
        $id = $this->container->request->getParam("id2");
        $evaluar = '{"id": "'.$id.'", "array": ["<input type=\"checkbox\" name=\"check-all\" onclick=\"this.checked = !this.checked\" value=\"'.$id.'\">", "0000125", "Raiza Gonzales", "90.000", "05"]}';
        
        return $evaluar;

    }

    function getdata(){
        $data = new \App\Data;
        $ids = $this->container->request->getParam("id");

        $evaluar = $data->obtener_pago($ids);


        return json_encode($evaluar);

    }
} 