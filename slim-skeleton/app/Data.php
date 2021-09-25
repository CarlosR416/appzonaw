<?php
namespace App; 
use App\Models;

class Data
{	
	protected $settings;

	public function __construct($container = null)
	{
		if(!is_null($container)){

			$this->settings = $container->get('settings');

		}

	}
	//Este codigo solo es temporal, solo lo debe ejecutar el controlador. ->>>
    function __run($container){
        return $this->datos_registros();
    }
	// ->>>

	function intrusos(){

		$registros = Models\Clientes::all();
		$arp_router = $this->datos_router();
		//$txt = $this->datos_registros();

		$i = 0;

		$data = [];
		foreach ($arp_router as $key => $value) {
			
			$match = false;

			foreach ($registros as $key2 => $value2) {
				
				if($value['mac'] == $value2->mac_address){
				
					$match = true;

				}
				
			}


			if(!$match){
				
				$data[$i]['ip'] = $value['ip'];
				$data[$i]['mac'] = $value['mac'];
				$data[$i]['comentario'] = 'Desconocido';
				$data[$i]['estatus'] = 'Desconocido';
				$i++;

			}

		}
		

		return $data;
	}

	function clientes_estado($estado = 'Activo'){

		$registros = Models\Clientes::where('estatus_servicio', $estado)->get();
		$arp_router = $this->datos_router();
		//$txt = $this->datos_registros();

		$i = 0;

		$data = [];
		/*foreach ($arp_router as $key => $value) {
			
			$match = false;

			/*foreach ($registros as $key2 => $value2) {
				
				if($value['mac'] == $value2->mac_address){

					$data[$i]['ip'] = $value['ip'];
					$data[$i]['mac'] = $value['mac'];
					$data[$i]['comentario'] = $value2->nombre;
					$data[$i]['estatus'] = $value2->estatus_servicio;
				
					$i++;
					$match = true;
				}
				
			}*/

			/*if(!$match){
				foreach ($txt as $key2 => $value2) {
				
					if($value['mac'] == $value2['mac']){

						$data[$i]['ip'] = $value['ip'];
						$data[$i]['mac'] = $value['mac'];
						$data[$i]['comentario'] = $value2['comentario'];
						$data[$i]['estatus'] = $value2['estatus'];
					
						$i++;
						$match = true;
					}
				
				}
			}*/

			/*if(!$match){
				
				$data[$i]['ip'] = $value['ip'];
				$data[$i]['mac'] = $value['mac'];
				$data[$i]['comentario'] = 'Desconocido';
				$data[$i]['estatus'] = 'Desconocido';
				$i++;

			}

		}*/
		
		foreach ($registros as $key => $value) {
			
			$match = false;

			foreach ($arp_router as $key2 => $value2) {
				if($value->mac_address == $value2['mac']){

					$value['ip'] = $value2['ip'];
					
					$i++;

					$match = true;
					break;
				}
			}

			if(!$match){
				
				$value['ip'] = 'No ha registrado IP';
				$i++;

			}
		}

		return $registros;
	}
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

		$ch = \curl_init($this->settings['router']['ip'].'/'.$url3);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch,CURLOPT_USERPWD,$ak.":".$mi);
		
		$respuesta = curl_exec ($ch);
		
		if(curl_errno($ch)){
			return [];	
		}

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
		return Models\Clientes::where('estatus_servicio', '!=', 'Opcional')->get();
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

			if($registro->update($data)){
				$resultado[] = $data;
			}
			
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
		$mes = Models\Meses::find($data["mes"]);

		if(!isset($cliente) && !isset($mes)){
			return $resp = ['error' => 'Cliente o Mes No Seleccionado'];
		}

		unset($data["id"]);
		$respuesta=false; 

		
		$data["id_cliente"] = $cliente->id;
		$data["estado"] = "Pendiente";

		$num_mes = Data::mes_nun($mes->nombre);
		$fecha_tem = $num_mes['year'].'-'.$num_mes['mes'].'-'.$cliente->dia_cobro;
		$test = new \DateTime($fecha_tem);

		$data["fecha_cobro"] = date_format($test, 'Y-m-d');

		$data["id_mes"] = $mes->id;

		$pago = Models\Pagos::create($data);

		if($pago){
			$respuesta["nombre"] = $cliente->nombre;
			$respuesta["apellido"] = $cliente->apellido;
			$respuesta["nombre_mes"] = $mes->nombre;
			$respuesta["fecha_cobro"] = $pago->fecha_cobro; 
			$respuesta["monto"] = $pago->monto;
			$respuesta["estado"] = $pago->estado;
			$respuesta["id"] = $pago->id;
		}
		

		return $respuesta;

	}

	function eliminar_pago($pago_id){
		$pago = Models\Pagos::where('id', $pago_id)->first();

		if($pago){

			Models\MovCuentas::where('id_pago', $pago->id)->delete();
			Data::recalcular_saldo_cuentas();
			
		}

		return $pago->delete();
	}

	function obtener_pagos(){
		//$pagos = Models\Pagos::join("clientes", "pagos.id_cliente" ,"=", "clientes.id" )->select("pagos.id", "clientes.nombre", "clientes.apellido", "pagos.fecha_cobro", "pagos.estado", "pagos.monto")->where("pagos.estado", "Pendiente")->get();
		$pagos = Models\Pagos::where("pagos.estado", "Pendiente")->get();

		return $pagos;
	}

	function obtener_pagos_cliente($cliente){
		//$pagos = Models\Pagos::join("clientes", "pagos.id_cliente" ,"=", "clientes.id" )->select("pagos.id", "clientes.nombre", "clientes.apellido", "pagos.fecha_cobro", "pagos.estado", "pagos.monto")->where("pagos.estado", "Pendiente")->get();
		$pagos = Models\Pagos::select('meses.nombre as nombre_mes', 'pagos.*')->join('meses', 'pagos.id_mes', '=', 'meses.id')->where("id_cliente", $cliente['id_cliente'])->get();

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

			if($respuesta){

				$registro['nombre'] = $cliente->nombre;
				$registro['apellido'] = $cliente->apellido;
                $respuesta = [];
                $respuesta[0] = $registro;
            }
		}

		return $respuesta;
	}

	function obtener_meses(){
		return Models\Meses::all();
	}

	function obtener_cuentas(){
		return Models\Cuentas::all();
	}

	function obtener_mes($id){

		$mes = Models\Meses::find($id);
		
		return $mes;
	}

	function eliminar_mes($id){
		$mes = Models\Meses::where('id', $id)->first();

		if($mes){
			Models\Pagos::where('id_mes', $mes->id)->delete();
			Models\MovCuentas::where('id_mes', $mes->id)->delete();
			$mes->delete();
			Data::recalcular_saldo_cuentas();
		}

		return $mes->delete();
	}

	function generar_mes($data){

		$nombre_mes = $data['mes'].'/'.$data['year'];
	
		if(Models\Meses::where('nombre', $nombre_mes)->exists()){

			$mes['error'] = 'Ya existe el Mes/Año';

		}else{

			$mes = Models\Meses::create(['nombre' => $nombre_mes]);
			$clientes = Models\Clientes::select("id", "dia_cobro", "tipo_servicio", "estatus_servicio")->get();
			$update_mes = $mes;
			$update_mes['clientes_activos'] = 0;
			$update_mes['clientes_retirados'] = 0;
			$update_mes['ingresos'] = 0;
			$update_mes['gastos'] = 0;

			foreach ($clientes as $key => $value) {
				
				if($value->estatus_servicio == "Activo"){

					$campos = [];
					$campos['id_cliente'] = $value->id;
					$campos['id_mes'] = $mes->id;

					$num_mes = Data::mes_nun($nombre_mes);
					$campos['fecha_cobro'] = $data['year'].'-'.$num_mes['mes'].'-'.$value->dia_cobro;

					$campos['estado'] = 'Pendiente';
					
					if($value->tipo_servicio == "Nacional"){
						$campos['monto'] = '50000';
					}else{
						$campos['monto'] = '90000';
					}
					
					$pago = true;
					if(!Models\Pagos::where('id_cliente', $campos['id_cliente'])->where('id_mes', $campos['id_mes'])->exists()){
						$pago = Models\Pagos::create($campos);
					}
					

					if($pago){
						$update_mes->clientes_activos++;
					}
					
				}else{
					$update_mes->clientes_retirados++;
				}
				
				
			}

			if($update_mes->clientes_activos > 0){
				$mes->update();
			}else{
				$mes->delete();
			}
		}

		return $mes;
	}

	function cobrar_pago($data){

		$error = [];
		if(!isset($data['pagos']['id']) | !validateDate($data['fecha_cobro']) | !isset($data['cuenta']) ){
			return ['error' => 'Faltan datos para procesar la factura'];
		}
		
		$pagos = Models\Pagos::find($data['pagos']['id']);
		$cuenta = Models\Cuentas::find($data['cuenta']);

		foreach ($pagos as $key => $value) {

			if($value->estado == 'Pagado'){
				$error = ['error' => 'La Factura Ya Se Pago'];
			}else{
				
				$value->estado = 'Pagado';
				$value->update();
				$temp_descrip = 'Pago Mensualidad';
				$movcuenta = [	
								'id_cuenta' => $data['cuenta'],
								'id_pago' => $value->id,
								'id_mes' => $value->id_mes, 
								'tipo' => 'CREDITO',
								'descripcion' => $temp_descrip,
								'valor' => $value->monto,
								'fecha' => $data['fecha_cobro']
							];
				Models\MovCuentas::create($movcuenta);

				$cuenta->saldo = $cuenta->saldo + $value->monto;

				$error[] = $value;
			}
			
		}

		$cuenta->update();
		
		return $error;
	}

	function mes_nun($mesyear)
	{	

		$pos = strpos($mesyear, '/');

		if($pos === false){
			return false;
		}

		$mes = substr($mesyear, 0, $pos);
		$year = substr($mesyear, $pos+1);


		$meses = [
			'Enero',
			'Febrero',
			'Marzo',
			'Abril',
			'Mayo',
			'Junio',
			'Agosto',
			'Septiembre',
			'Optubre',
			'Noviembre',
			'Diciembre'
		];

		$resp = ['year' => $year];
		$resp['mes'] = array_search($mes, $meses)+1; 
		return $resp;
	}

	function obtener_mov_cuentas()
	{
		return Models\MovCuentas::all();
	}

	function crear_mov_cuenta($data){

		if(isset($data['id_cuenta'])){
			$cuenta = Models\Cuentas::find($data['id_cuenta']);

			if($cuenta){
				if($data['tipo'] == 1){
					$cuenta->saldo = $cuenta->saldo + $data['valor']; 
				}else if($data['tipo'] == 2){
					$cuenta->saldo = $cuenta->saldo - $data['valor'];
				}else{
					return ['error' => 'No es posible crear el registro'];
				}

				$cuenta->update();
			}

		}

		$data['tipo'] = $data['tipo'] == 1 ? "CREDITO" : "DEBITO";
		$data['fecha'] = date('Y-m-d');

		$movCuenta = Models\MovCuentas::create($data);

		if($movCuenta){
			$movCuenta['cuenta'] = $cuenta->nombre;
			$movCuenta['pago'] = '-';
		}else{
			$movCuenta = ['error' => 'No es posible crear el registro'];
		}

		return $movCuenta;

	}

	function eliminar_mov_cuenta($id){


		$movcuenta = Models\MovCuentas::where('id', $id)->first();

		if(isset($movcuenta->id_pago)){
			$pago = Models\Pagos::where('id', $movcuenta->id_pago)->first();

			if($pago){

				$pago->estado = 'Pendiente';
				$pago->update();

			}
			
		}

		$resp = $movcuenta->delete();
		Data::recalcular_saldo_cuentas();
		
		return $resp;

	}

	function recalcular_saldo_cuentas(){

		$cuentas = Models\Cuentas::all();

		foreach ($cuentas as $key => $value) {
			
			$a = Models\MovCuentas::where(['id_cuenta' => $value->id, 'tipo' => 'CREDITO'])->get()->sum('valor');

			$b = Models\MovCuentas::where(['id_cuenta' => $value->id, 'tipo' => 'DEBITO'])->get()->sum('valor');

			$value->saldo = $a-$b;

			$value->update();
			
		}


	}

	function crear_gasto($data){
		$gasto = Models\Gastos::create($data);
		for ($i=0; $i < 100000000; $i++) { 
			# code...
		}
		return $gasto;
	}

	function procesar_gasto(){
		return "pasto";
	}

	function obtener_gasto($data){
		$gasto = Models\Gastos::find($data["gastos"]);

		return [$gasto];
	}

}

function validateDate($date, $format = 'Y-m-d')
{
    $d = \DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}




