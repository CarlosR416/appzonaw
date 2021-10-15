<?php 
namespace App\Controller;


class Data
{
    protected $container;

	public function __construct($container)
	{
		$this->container = $container;
	}
    
    function agregar($args){
        $data = new \App\Data;
        $params = $this->container->request->getParams();

        $property = "agregar_" . $args['arg2']; 

        if(method_exists($data, $property)){
            $resp = $data->$property($params);
        }else{
            $resp = ['error' => 'No existe funcion para agregar registro'];
        }
        
        return json_encode($resp);
    }

    function eliminar($args){

        $data = new \App\Data;
        $params = $this->container->request->getParams();

        $property = "eliminar_" . $args['arg2'];


        if(method_exists($data, $property)){

            $resp = $data->$property($params);

        }else{
            $resp = [['error' => 'No existe funcion para eliminar registro']];
        }
        
        return json_encode($resp);

    }

    function modificar($args){

        $data = new \App\Data;
        $params = $this->container->request->getParams();

        $property = "modificar_" . $args['arg2'];

        if(method_exists($data, $property)){
            
            $resp = $data->$property($params);

        }else{
            $resp[] = ['error' => 'No existe funcion para modificar registro'];
        }
        
        return json_encode($resp);
    }

    function obtener($args){
        $data = new \App\Data;
        $params = $this->container->request->getParams();

        $property = "obtener_" . $args['arg2']; 
       
        if(method_exists($data, $property)){
            $resp = $data->$property($params);
        }else{
            $resp[] = ['error' => 'No existe funcion para obtener registro'];
        }
       
        return json_encode($resp);

    }
}