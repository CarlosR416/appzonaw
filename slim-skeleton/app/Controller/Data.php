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
        $evaluar = $this->container->request->getParams();

        $property = $evaluar['funcion'];

        if(method_exists($data, $property)){
            $resp[0] = $data->$property($evaluar['data']);
        }else{
            $resp[] = ['error' => 'No existe funcion para agregar registro'];
        }
        
        return json_encode($resp);
    }

    function eliminar($args){

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
            $evaluar = [['error' => 'No existe funcion para eliminar registro']];
        }
        
        
        return json_encode($evaluar);

    }

    function modificar($args){

        $data = new \App\Data;
        $evaluar = $this->container->request->getParams();
        $property = $evaluar['funcion'];

        if(method_exists($data, $property)){
            
            $resp = $data->$property($evaluar['data']);

        }else{
            $resp[] = ['error' => 'No existe funcion para modificar registro'];
        }
        
        return json_encode($resp);
    }

    function obtener($args){
        $data = new \App\Data;
        $evaluar = $this->container->request->getParams();

        $property = $evaluar['funcion'];

        if(method_exists($data, $property)){
            $resp = $data->$property($evaluar['data']);
        }else{
            $resp[] = ['error' => 'No existe funcion para obtener registro'];
        }
       
        return json_encode($resp);

    }
}