<?php

namespace App\controller;
use App\Models;

class Configuraciones
{   
    protected $container;

    function __construct($container){
        $this->container = $container;
    }

    function menus(){

        $params = $this->container->request->getParams();

        if(isset($params['funcion'])){

            switch ($params['funcion']) {
                case 'menu_crear':

                    if(!isset($params['nombre']) | !isset($params['icono'])){
                        $data['error'] = 'Nombre de menu no ingresado';
                        break;
                    }

                    if(strlen($params['nombre']) < 4 | strlen($params['icono']) < 4 ){
                        $data['error'] = 'Nombre de menu muy corto';
                        break;
                    }

                    $menu = Models\Menu::where('nombre', $params['nombre']);

                    if($menu->exists()){
                        $data['error'] = 'Ya existe el nombre de menu';
                        break;
                    }

                    
                    Models\Menu::create(['nombre' => $params['nombre'], 'icono' =>  $params['icono']]);   
                    $menu = Models\Menu::all();
                    $this->container->twig->getEnvironment()->addGlobal('menu', $menu);

                    break;
                
                case 'ruta_crear':

                    if(!isset($params['nombre']) | !isset($params['ruta'])){
                        $data['error'] = 'Nombre de ruta no ingresado';
                        break;
                    }

                    if(strlen($params['nombre']) < 4 | strlen($params['ruta']) < 1 ){
                        $data['error'] = 'Nombre de ruta muy corto';
                        break;
                    }

                    $menu = Models\Rutas::where('nombre', $params['nombre'])->orWhere('ruta', $params['ruta']);

                    if($menu->exists()){
                        $data['error'] = 'Ya existe el nombre o la ruta';
                        break;
                    }

                    Models\Rutas::create(['nombre' => $params['nombre'], 'descripcion' => $params['descripcion'], 'id_menu' => $params['id_menu'], 'ruta' => $params['ruta']]);

                    break;
                case 'eliminar_menu':

                    break;
                
                case 'eliminar_ruta':

                    break;
                
                case 'crud_crear':
                    
                    if(!isset($params['id_menu'])){
                        $data['error'] = 'Es necesario un menu';
                        break;
                    }

                    $menu = Models\Menu::find($params['id_menu']);

                    if(!$menu){
                        $data['error'] = 'No se pude crear Crud, menu inexistente';
                        break;
                    }


                    $Temp_param = []; 

                    $ruta_url[] = ["Eliminar", "data/eliminar/"];
                    $ruta_url[] = ["Agregar", "data/agregar/"];
                    $ruta_url[] = ["Modificar", "data/modificar/"];
                    $ruta_url[] = ["Obtener", "data/obtener/"];
                    
                    foreach ($ruta_url as $key => $value) {
                        
                        $Temp_param[] = [    
                                    'nombre' => $value[0]." ".$menu->nombre, 
                                    'descripcion' => "Ruta basica de CRUD para ".$value[0]." ".$menu->nombre, 
                                    'id_menu' => 0,
                                    'crud_menu' => $menu->id, 
                                    'ruta' => $value[1].strtolower($menu->nombre)
                                ];       
                    }
                    
                    foreach ($Temp_param as $key => $value) {
                        Models\Rutas::create($value);
                    }
                    

                    break;

                    default:
                    # code...
                    break;
            }
            $data['rutas'] = [];

        }

        $data['rutas'] = Models\Rutas::all();

        return $this->container->twig->render($this->container->response,  "configuracion_menu.twig", $data);
    
    }

    function usuarios(){

        $params = $this->container->request->getParams();
        $data['rutas'] = Models\Rutas::all();

        if(isset($params['funcion'])){
            
            switch ($params['funcion']) {
                case 'user_crear':

                    if(!isset($params['nombre']) | !isset($params['name_user']) | !isset($params['pass_user'])){
                        $data['error'] = 'Los datos no son correctos';
                        break;
                    }

                    if(strlen($params['nombre']) < 4 | strlen($params['name_user']) < 4 ){
                        $data['error'] = 'El Nombre es muy corto';
                        break;
                    }

                    if(strlen($params['pass_user']) < 6 ){
                        $data['error'] = 'La Contraseña es muy corta';
                        break;
                    }

                    $user = Models\User::where('usuario', $params['name_user']);

                    if($user->exists()){
                        $data['error'] = 'Ya existe el nombre de menu';
                        break;
                    }

                    Models\User::create(['name' => $params['nombre'], 'usuario' => $params['name_user'], 'pass' => $params['pass_user']]);

                    break;

                case 'user_cargar':

                    if(isset($params['user'])){
                        $data['user'] = Models\User::find($params['user']);

                        
                        if(!$data['user']){
                            $data['error'] = 'Error al cargar permisos del Usuario';
                            break;
                        }


                        foreach ($data['rutas'] as $key => $value) {
                            $bufer = $value->users->where('id_user', $data['user']->id);
                            $data['rutas'][$key]['user_checked'] = count($bufer) > 0 ? true : false;
                            
                            if($data['rutas'][$key]['user_checked']){
                                $data['menu_checket'][] = $data['rutas'][$key]->id_menu;
                            }
                             
                        }


                    }else{
                        $data['error'] = 'Error al cargar permisos del Usuario';
                    }

                    //die(var_dump(json_encode($data['user']->name)));
                   
                    break;

                case 'cargar_permisos':

                    if(isset($params['user'])){
                        $data['user'] = Models\User::find($params['user']);

                        
                        if(!$data['user']){
                            $data['error'] = 'Error al cargar permisos del Usuario';
                            break;
                        }

                        $data['user']->rutas()->delete();
                        foreach ($params['permisos'] as $key => $value) {
                           
                            $data['user']->rutas()->create(['id_ruta' => $value]);
                        }

                        foreach ($data['rutas'] as $key => $value) {

                            $bufer = $value->users->where('id_user', $data['user']->id);
                            $data['rutas'][$key]['user_checked'] = count($bufer) > 0 ? true : false;
                            
                            if($data['rutas'][$key]['user_checked']){
                                $data['menu_checket'][] = $data['rutas'][$key]->id_menu;
                            }
                             
                        }
                    
                    
                    }
                    
                    break;
                default:
                    break;
            }

        }

        $data['users'] = Models\User::all();
        

        return $this->container->twig->render($this->container->response,  "configuracion_usuarios.twig", $data);
    
    }

}