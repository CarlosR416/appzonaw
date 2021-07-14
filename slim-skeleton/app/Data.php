<?php
namespace App; 
use App\Models;

class Data
{	

	//Este codigo solo es temporal, solo lo debe ejecutar el controlador. ->>>
    function __run($container){
        return $this->datos_registros();
    }
	// ->>>


	function c_activos(){

		$registros = $this->datos_registros();
	
		$arp_router = $this->datos_router();
		
		$eval_resp =  $this->evaluar($arp_router, $registros);

		$resp = $this->filtrar($eval_resp, '0', 'Tipo');
		
		$view = [];
		$i = 0;
		foreach ($resp['registro'] as $key => $value) {
			$i++;
			$view[$i]['ip'] = $value['ip'];
			$view[$i]['mac'] = $value['mac'];
			$view[$i]['comentario'] = $value['comentario'];
			$view[$i]['estatus'] = 'Activo';
			

		}

		foreach ($resp['desconocido'] as $key => $value) {
			$i++;
			$view[$i]['ip'] = $value['ip'];
			$view[$i]['mac'] = $value['mac'];
			$view[$i]['comentario'] = $value['comentario'];
			$view[$i]['estatus'] = 'Alerta';

		}

		foreach ($resp['inactivo'] as $key => $value) {
			$i++;
			$view[$i]['ip'] = 'Ausente';							
			$view[$i]['mac'] = $value['mac'];
			$view[$i]['comentario'] = $value['comentario'];
			$view[$i]['estatus'] = 'Inactivo';

		}

		return $view;
	}

    function datos_registros(){
        
		$clientes = fopen(__DIR__ . "/clientes.txt", "r");
		$todos = [];
		$lineas = [];
		$i = 0;
		
		while(!feof($clientes)) {

			$linea = fgets($clientes);
			$lineas[$i] = $linea;
			$todos[$i] = json_decode($lineas[$i], true, 512, JSON_UNESCAPED_UNICODE);
			$i++;

		}
		//echo var_dump($lineas[0]);

		return $todos;

	}

    function datos_router(){

		$ak = 'solucionex';
		$mi	= 'adminsolucione';

		$url1 = 'userRpm/FixMapCfgRpm.htm';
		$url2 = 'userRpm/FixMapCfgRpm.htm?Mac=68-72-51-28-15-8F&Ip=192.168.1.101&State=1&Changed=0&SelIndex=0&Page=1&btn_save=Save';
		$url3 = 'userRpm/LanArpBindingListRpm.htm';

		$ch = curl_init('http://192.168.11.1/'.$url3);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch,CURLOPT_USERPWD,$ak.":".$mi);
		
		$respuesta = curl_exec ($ch);

		$pos = strpos($respuesta,'new Array(',0);
		$cadena = [];

		while (is_int($pos)) {

			$pos1 = strpos($respuesta,');',$pos);
			$length = $pos1 - $pos - 11;

			$cadena[] = substr($respuesta,$pos+11,$length);

			$pos = strpos($respuesta,'new Array(',$pos1);

		}


		$Array = explode(", ", $cadena[0]);

		for ($i=0; $i < count($Array); $i = $i+3) {

			if (!($i >= count($Array)-2)) {
			 	$array2[] = array('mac'	=> preg_replace(['/ "/', '/"/'],'',$Array[$i+1]),
								  'ip'	=> preg_replace(['/ "/', '/"/'],'',$Array[$i+2]));
			} 
			
		}

		return $array2;
	}

	function evaluar($ar, $ar2){

		$array1 = array();
		$array2 = array();
		$array3 = array();

		foreach ($ar as $key => $value) {
			
			$bol = true;

			foreach ($ar2 as $k => $c) {

				if($value['mac']  == $c['mac']){

					$array1[] = array( 'mac' => $value['mac'], 'ip' => $value['ip'], 'comentario' => $c['comentario'], 'AP' => $c['AP'], 'Tipo' => $c['Tipo']);

					$bol = false;
				}

			}

			if ($bol) {

				$array2[] = array( 'mac' => $value['mac'], 'ip' => $value['ip'], 'comentario' => 'Desconocido');

			}
		}


		foreach ($ar2 as $key => $value) {

			$bol = true;

			foreach ($ar as $k => $c) {

				if($value['mac']  == $c['mac']){
						
					$bol = false;
				}

			}

			if ($bol) {

				$array3[] = array( 'mac' => $value['mac'], 'ip' => 'Inactivo', 'comentario' => $value['comentario'], 'AP' => $value['AP'], 'Tipo' => $value['Tipo']);
				
			}
		}

		return ['registro' => $array1, 'desconocido' => $array2, 'inactivo' => $array3];
	}

	function filtrar($ar, $param, $campo){

		foreach ($ar['registro'] as $key => $value) {

		  $value[$campo] == $param ? $array1[] = $ar['registro'][$key] : '';

		}

		foreach ($ar['inactivo'] as $key => $value) {
			
		  $value[$campo] == $param ? $array3[] = $ar['inactivo'][$key] : '';

		}

		$array2 = $ar['desconocido'];

		return ['registro' => $array1, 'desconocido' => $array2, 'inactivo' => $array3];
	}

	function login($pass, $user){

		$Resul = User::where('usuario',$user)->first();

		if (!isset($Resul)) {

			return false;

		}else if($Resul->pass == $pass){


			$_SESSION['usuario'] = $Resul->id;
			$_SESSION['admin'] = $Resul->admin;
			return true;

		}
		
		return false;
	}

	function users(){
		return Models\User::get();
	}

	function Clientes(){
		return Models\Clientes::get();
	}

	function getCliente($id){
		
		return Models\Clientes::find($id);
	}

	function modifCliente($data){

		$registro = Models\Clientes::find($data["id"]);

		if(!$registro)
		{
			$resultado = false;
		}else{
			$resultado = $registro->update($data);
		}

		return $resultado;
	}

	function crear_usuario($data){

		unset($data["id"]);
		return Models\Clientes::create($data);

	}

	function eliminar_usuario($user_id){
		$user = Models\Clientes::where('id', $user_id)->first();
		return $user->delete();
	}

	function crear_pago($data){


		$cliente = Models\Clientes::find($data["id_cliente"]);
		unset($data["id_cliente"]);
		$respuesta=false; 

		if($cliente)
		{
			$data["id_cliente"] = $cliente->id;
			$data["estado"] = "Pendiente";

			$pago = Models\Pagos::create($data);

			if($pago){
				$respuesta["nombre"] = $cliente->nombre;
				$respuesta["apellido"] = $cliente->apellido;
				$respuesta["fecha_cobro"] = $pago->fecha_cobro; 
				$respuesta["monto"] = $pago->monto;
				$respuesta["estado"] = $pago->estado;
				$respuesta["id"] = $pago->id;
			}
		}

		return $respuesta;

	}

	function eliminar_pago($pago_id){
		$user = Models\Pagos::where('id', $pago_id)->first();
		return $user->delete();
	}
	function obtener_pagos(){
		$pagos = Models\Pagos::join("clientes", "pagos.id_cliente" ,"=", "clientes.id" )->select("pagos.id", "clientes.nombre", "clientes.apellido", "pagos.fecha_cobro", "pagos.estado", "pagos.monto")->where("pagos.estado", "Pendiente")->get();

		return $pagos;
	}

	function obtener_pago($id){
		$pagos = Models\Pagos::find($id);
		
		if(count($pagos) < 2){
			
			$pagos[0]["nombre"] = $pagos[0]->cliente->nombre;
			$pagos[0]["apellido"] = $pagos[0]->cliente->apellido;
			$pagos[0]["cedula"] = $pagos[0]->cliente->cedula;
		}
		
		return $pagos;
	}

	function modificar_pago($data){

		$registro = Models\Pagos::find($data["id"]);
		$cliente = Models\Clientes::find($data["id_cliente"]);
		$respuesta=false; 

		if($registro && $cliente)
		{
			$respuesta = $registro->update($data);
		}

		return $respuesta;
	}



	function obtener_meses(){
		return Models\Meses::all();
	}

	function obtener_cuentas(){
		return Models\Cuentas::all();
	}
}


