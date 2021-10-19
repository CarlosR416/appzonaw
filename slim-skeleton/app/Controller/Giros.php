<?php
namespace App\Controller;

class Giros extends Controller
{   
    function maxjo(){

        $datos = new \App\Data;

        $a['data'][0]['id'] = '112';
        $a['data'][0]['ref'] = '10012475';
        $a['data'][0]['nombre'] = 'Carlos Rodriguez';
        $a['data'][0]['cedula'] = '15255658';
        $a['data'][0]['monto_env'] = '107000';
        $a['data'][0]['monto_entregar'] = '100000';
        $a['data'][1]['id'] = '113';
        $a['data'][1]['ref'] = '10078565';
        $a['data'][1]['nombre'] = 'Carlos Rodriguez';
        $a['data'][1]['cedula'] = '15255658';
        $a['data'][1]['monto_env'] = '107000';
        $a['data'][1]['monto_entregar'] = '100000';
        $a['data'][2]['id'] = '114';
        $a['data'][2]['ref'] = '10012512';
        $a['data'][2]['nombre'] = 'Carlos Rodriguez';
        $a['data'][2]['cedula'] = '15255658';
        $a['data'][2]['monto_env'] = '107000';
        $a['data'][2]['monto_entregar'] = '100000';

        $a['data'] = $datos->obtener_girosp();
 
        return $this->container->twig->render($this->container->response,  "giros/maxjo.twig", $a);
    
    }

    function cargar(){

        return $this->container->twig->render($this->container->response,  "giros/cargar.twig");
        
    }
    
}
