<?php 
namespace App\Controller;


class Cuentas
{  
    protected $container;

	public function __construct($container)
	{
		$this->container = $container;
	}

    function mes(){
        $datos = new \App\Data;
        $args = [];
        //$settings = $this->container->get('settings')['renderer'];
        //$settings["template"] = "tabla.php";

        /*if(file_exists($settings['template_path'].$args["name"].".php") && $args["name"] != 'index'){
            $settings["template"] = $args["name"].".php";
        }*/

        $args["data"] =  $datos->obtener_meses();

        return $this->container->twig->render($this->container->response,  "vistas_cuentas/meses.twig", $args);
    
    }

    function meses(){
        $datos = new \App\Data;
        $args = [];
        //$settings = $this->container->get('settings')['renderer'];
        //$settings["template"] = "tabla.php";

        /*if(file_exists($settings['template_path'].$args["name"].".php") && $args["name"] != 'index'){
            $settings["template"] = $args["name"].".php";
        }*/

        $args["data"] =  $datos->obtener_cuentas_por_meses();


        return $this->container->twig->render($this->container->response,  "vistas_cuentas/meses.twig", $args);
    
    }


}