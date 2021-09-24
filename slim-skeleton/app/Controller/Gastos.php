<?php 
namespace App\Controller;


class Gastos
{  
    protected $container;

	public function __construct($container)
	{
		$this->container = $container;
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

        return $this->container->twig->render($this->container->response,  "vistas_gastos/meses.twig", $args);
    
    }

    function pendientes(){
        $datos = new \App\Data;
        $args = [];
        //$settings = $this->container->get('settings')['renderer'];
        //$settings["template"] = "tabla.php";

        /*if(file_exists($settings['template_path'].$args["name"].".php") && $args["name"] != 'index'){
            $settings["template"] = $args["name"].".php";
        }*/
        
        $args["data"] = \App\Models\Gastos::all();
        $args["meses"] =  $datos->obtener_meses();

        return $this->container->twig->render($this->container->response,  "vistas_gastos/pendientes.twig", $args);
   
    }
}