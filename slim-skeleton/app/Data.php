<?php
namespace App; 
use App\Models;
use Illuminate\Database\Capsule\Manager as DB;

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

	function agregar_mac_filter($id){

		$ak = 'solucionex';
		$mi	= 'adminsolucione';
		 
		$mac = "01-00-00-00-00-00";
		$desc = "Cliente1";
		$state = 1;

		$C = [
			[ 'nombre' => '25', 'mac' => 'B4-FB-E4-DE-BE-86', 'estado' => 0],
			[ 'nombre' => '26', 'mac' => '74-F0-6D-9F-E6-62', 'estado' => 0],
			[ 'nombre' => '27', 'mac' => '2E-29-E9-29-E1-A1', 'estado' => 0],
			[ 'nombre' => '28', 'mac' => '74-4A-A4-D0-30-F1', 'estado' => 0],
			[ 'nombre' => '29', 'mac' => '08-BF-A0-61-94-FD', 'estado' => 0],
			[ 'nombre' => '30', 'mac' => '10-FE-ED-53-CF-39', 'estado' => 0],
			[ 'nombre' => '31', 'mac' => 'F8-D1-11-81-69-4B', 'estado' => 0],
			[ 'nombre' => '32', 'mac' => '28-C2-DD-2C-40-3B', 'estado' => 0],
			[ 'nombre' => '33', 'mac' => 'D4-CA-6D-D4-DF-E9', 'estado' => 0],
			[ 'nombre' => '34', 'mac' => '08-55-31-70-E8-2C', 'estado' => 0],
			[ 'nombre' => '35', 'mac' => '60-8F-5C-18-77-0A', 'estado' => 0],
			[ 'nombre' => '36', 'mac' => '68-A3-C4-0B-D7-06', 'estado' => 0],
			[ 'nombre' => '37', 'mac' => '00-22-B0-45-6D-A0', 'estado' => 0]
		];

		$i = 0;
		foreach ($C as $key => $value) {

			$url3 = 'userRpm/LanMacFilterRpm.htm?Mac='.$value["mac"].'&Desc='.$value["nombre"].'&State='.$value["estado"].'&Changed=0&SelIndex=0&Page=1&btn_save=Save';
			$i=$i+1;
			$ch = \curl_init($this->settings['router']['ip'].'/'.$url3);
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
			curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
			curl_setopt($ch,CURLOPT_USERPWD,$ak.":".$mi);

			$respuesta = curl_exec ($ch);
			
			curl_close($ch);
		}

		die(var_dump($i));
		
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

		$Resul = Models\User::where('usuario',$user)->first();

		if (!isset($Resul)) {

			return false;

		}else if($Resul->pass == $pass){


			$_SESSION['usuario'] = $Resul->id;
			$_SESSION['admin'] = $Resul->admin;
			return true;

		}
		
		return false;
	}


	/// Usuarios

	function users(){
		return Models\User::get();
	}

	


	/// Clientes

	function agregar_cliente($data = null){

		if(!is_null($data)){

			unset($data["id"]);
			$resp["DATA"] = Models\Clientes::create($data);
			$resp["MSJ"] = MSJ_success("Cliente creado exitosamente", "Cliente Agregado");

		}else{
			$resp["MSJ"] = MSJ_error("No se suministro parametros de insercion");
		}

		return $resp;

	}

	function eliminar_cliente($data = null){

		if(!is_null($data) && isset($data['id'])){

			$user_id = $data["id"];

			$d = 0;
			$nd = 0;
			$id = [];

			foreach ($user_id as $key => $value) {
				
				$user = Models\Clientes::where('id', $value)->first();

				if(!is_null($user)){
					
					if($user->delete()){
						$d++;
						$id[] = $value;
					}else{
						$nd++;
					}

	
				}else{
					$nd++;
				}

			}

			$resp['DATA']["id"] = $id;
			if($d > 1){

				$resp['MSJ'] = MSJ_success($d . " Registros Eliminados", "Cliente Eliminado");
				
			}else if($d > 0){

				$resp['MSJ'] = MSJ_success($d . " Registro Eliminado", "Cliente Eliminado");
				
			}

			if($nd > 1){
				$resp['MSJ'] = MSJ_warning($nd . " Registros no Eliminados");
			}else if($nd > 0){
				$resp['MSJ'] = MSJ_warning($nd . " Registro no Eliminado");
			}


		}else{
			$resp['MSJ'] = MSJ_error("No se suministro parametro para eliminar");
		}

		return $resp;
	}

	function obtener_cliente($data = null){
		
		if(!is_null($data) && isset($data['id'])){

			$id = $data["id"];
			$resp["DATA"] = Models\Clientes::find($id)->first();;
			
		}else{
			$resp["MSJ"] = MSJ_error("No se suministro parametro de busqueda");
		}

		return $resp;
	}

	function obtener_clientes(){

		$resp["DATA"] = Models\Clientes::where('estatus_servicio', '!=', 'Opcional')->get();
		
		return $resp;

	}

	function modificar_cliente($data = null){

		if(!is_null($data) && isset($data['id'])){
			$id = $data["id"];
			$registro = Models\Clientes::find($id);

			if(!$registro)
			{

				$resp["MSJ"] = MSJ_error("No se existe registro para modificar");

			}else{

				if($registro->update($data)){
					$resp["DATA"] = $registro;
					$resp["MSJ"] = MSJ_success("Modificacion exitosa", "Cliente Modificado");
				}else{
					$resp["MSJ"] = MSJ_error("No se pudo modificar el registro");
				}
				
			}

		}else{
			$resp["MSJ"] = MSJ_error("No se suministro parametro para modificar");
		}

		return $resp;
	}

	function obtener_estadoservicio($data = null){
		if(!is_null($data) && isset($data['id'])){

			$id = $data["id"];
			$resp["DATA"] = Models\EstadoServicios::where('id_cliente', $id)->orderBy('fecha', "desc")->first();;
			
			if(is_null($resp["DATA"])){
				$resp["MSJ"] = MSJ_warning("No Existe estado previo");
				$resp["DATA"] = [];
			}else{
				$resp["DATA"]['fecha'] = date('Y-m-d');
			}

		}else{
			$resp["MSJ"] = MSJ_error("No se suministro parametro de busqueda");
		}

		return $resp;
	}

	function agregar_estadoservicio($data = null){
		if(!is_null($data) && isset($data['id'])){

			$id = $data["id_cliente"];
			unset($data["id"]);

			$resp["DATA"] = Models\Clientes::where('id', $id);
			
			if(!$resp["DATA"]->exists()){
				$resp["MSJ"] = MSJ_error("No Existe Cliente para agregar estado");
				$resp["DATA"] = [];
			}else{

				$estado = Models\EstadoServicios::create($data);
				if(!is_null($estado)){
					$estado = Models\EstadoServicios::where('id_cliente', $id)->orderBy('fecha', "desc")->first();;
			

					$resp["DATA"] = $resp["DATA"]->first();
					$resp["DATA"]->update(["estatus_servicio" => $estado["estado"]]);
					$resp["MSJ"] = MSJ_success("Estado actualizado correctamente", "Estado Actualizado");
				}else{
					$resp["MSJ"] = MSJ_error("No se pudo crear el estado");
				}
				
			}

		}else{
			$resp["MSJ"] = MSJ_error("No se suministro parametro de busqueda");
		}

		return $resp;
	}
	
	
	/// Cuentas 
	
	function obtener_cuentas(){
		Data::recalcular_saldo_cuentas();
		return Models\Cuentas::all();
	}

	function obtener_cuentas_por_meses(){
		
		return Models\Meses::get();

	}

	function obtener_mov_cuentas()
	{
		return Models\MovCuentas::all();
	}

	function crear_mov_cuenta($data)
	{

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

	function eliminar_mov_cuenta($id)
	{


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

	function recalcular_saldo_cuentas()
	{

		$cuentas = Models\Cuentas::all();

		foreach ($cuentas as $key => $value) {
			
			$a = Models\MovCuentas::where(['id_cuenta' => $value->id, 'tipo' => 'CREDITO'])->get()->sum('valor');

			$b = Models\MovCuentas::where(['id_cuenta' => $value->id, 'tipo' => 'DEBITO'])->get()->sum('valor');

			$value->saldo = $a-$b;

			$value->update();
			
		}


	}


	/// Gastos

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

	//giros
	function agregar_entregagiro($data){
		
		if(is_null($data) | !isset($data["id"])){
			$resp["MSJ"] = MSJ_error("No se puede procesar giro, contacte al administrador");
			
			return $resp;
		}

		if(isset($data["nota"]) && $data["nota"] != ""){
			$updated["nota"] = $data["nota"];
		}

		$updated["estado"] = "entregado";

		$giro = Models\Giros::where('id', $data['id'][0])->first();

		$giro->update($updated);

		$resp["DATA"] = ['id' => [$giro->id]];
		$resp["MSJ"] = MSJ_success("Procesado Correctamente", "Giro entregado");
		
		return $resp; 
	}

	function agregar_confirmagiro($data){
		
		if(is_null($data) | !isset($data["id"])){
			$resp["MSJ"] = MSJ_error("No se puede procesar giro, contacte al administrador");
			
			return $resp;
		}

		if(isset($data["nota"]) && $data["nota"] != ""){
			$updated["nota"] = $data["nota"];
		}


		$updated["estado"] = "confirmado";

		$giro = Models\Giros::where('id', $data['id'][0])->first();

		$giro->update($updated);

		$resp["DATA"] = ['id' => [$giro->id]];
		$resp["MSJ"] = MSJ_success("Procesado Correctamente", "Giro Confirmado");
		
		return $resp; 
	}

	function obtener_giro($data = null)
	{	
		
		if(is_null($data) | !isset($data["id"])){
			$resp["MSJ"] = MSJ_error("No se puede obtener giro");
			
			return $resp;
		}

		$giro = Models\Giros::where('id', $data['id'][0])->first();

		//$giro->update(["estado" => "entregado"]);

		return ['DATA' => $giro];

	}

	function agregar_giro($data = null){

		if(!is_null($data)){

			unset($data["id"]);

			$permitted_chars2 = '0123456789';
			function generate_string($input, $strength = 16) {
                $input_length = strlen($input);
                $random_string = '';
                for($i = 0; $i < $strength; $i++) {
                    $random_character = $input[mt_rand(0, $input_length - 1)];
                    $random_string .= $random_character;
                }
            
                return $random_string;
            }
			do{
				$data["ref"] = generate_string($permitted_chars2, 10);
			}while(Models\Giros::where("ref", $data["ref"])->exists());
			

			$resp["DATA"] = Models\Giros::create($data);
			$resp["MSJ"] = MSJ_success("Giro creado exitosamente", "Giro Agregado");

		}else{
			$resp["MSJ"] = MSJ_error("No se suministro parametros de insercion");
		}

		return $resp;

	}

	function eliminar_giro($data = null){

		if(!is_null($data) && isset($data['id'])){

			$giro_id = $data["id"];

			$d = 0;
			$nd = 0;
			$id = [];

			foreach ($giro_id as $key => $value) {
				
				$giro = Models\Giros::where('id', $value)->first();

				if(!is_null($giro)){
					
					if($giro->delete()){
						$d++;
						$id[] = $value;
					}else{
						$nd++;
					}

	
				}else{
					$nd++;
				}

			}

			$resp['DATA']["id"] = $id;
			if($d > 1){

				$resp['MSJ'] = MSJ_success($d . " Registros Eliminados", "Giro Eliminado");
				
			}else if($d > 0){

				$resp['MSJ'] = MSJ_success($d . " Registro Eliminado", "Giro Eliminado");
				
			}

			if($nd > 1){
				$resp['MSJ'] = MSJ_warning($nd . " Registros no Eliminados");
			}else if($nd > 0){
				$resp['MSJ'] = MSJ_warning($nd . " Registro no Eliminado");
			}


		}else{
			$resp['MSJ'] = MSJ_error("No se suministro parametro para eliminar");
		}

		return $resp;
	}

	function obtener_girosentregados($data = null)
	{
		$date = time() - (15 * 24 * 60 * 60);

		$date = date('Y-m-d', $date);

		return Models\Giros::where('estado', 'entregado')->get();

	}

	function obtener_girosp($data = null)
	{
		
		return Models\Giros::where('estado', 'pendiente')->get();

	}

	function obtener_girosconfirmados($data = null)
	{
		
		return Models\Giros::where('estado', 'confirmado')->get();

	}



	/// Pagos divididos por meses

	function agregar_cobranzapormes($data){

		$nombre_mes = $data['mes'].'/'.$data['year'];
	
		if(Models\Meses::where('nombre', $nombre_mes)->exists()){

			$resp['MSJ'] = MSJ_error("Ya existe el Mes/A??o");

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
				$resp["DATA"] = $mes;
				$resp["MSJ"] = MSJ_success("Cobranza por mes generada con ??xito", "Cobranza Generada"); 
			}else{
				$mes->delete();
			}
		}

		return $resp;
	}

	function obtener_mes($id){

		$mes = Models\Meses::find($id);
		
		return $mes;
	}

	function obtener_meses(){
		return Models\Meses::all();
	}

	function eliminar_cobranzapormes($id){
		$mes = Models\Meses::where('id', $id)->first();

		if($mes){
			Models\Pagos::where('id_mes', $mes->id)->delete();
			Models\MovCuentas::where('id_mes', $mes->id)->delete();
			$mes->delete();
			Data::recalcular_saldo_cuentas();
			$resp['MSJ'] = MSJ_success("Cobranza por mes eliminada", "Eliminado");
			$resp['DATA'] = $id;
		}else{
			$resp['MSJ'] = MSJ_error("No se puede eliminar la cobranza por mes");
		}

		return $resp;
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



	/// todos los pagos individualmente
	
	function obtener_pagos_cliente($cliente){
		//$pagos = Models\Pagos::join("clientes", "pagos.id_cliente" ,"=", "clientes.id" )->select("pagos.id", "clientes.nombre", "clientes.apellido", "pagos.fecha_cobro", "pagos.estado", "pagos.monto")->where("pagos.estado", "Pendiente")->get();
		$pagos = Models\Pagos::select('meses.nombre as nombre_mes', 'pagos.*')->join('meses', 'pagos.id_mes', '=', 'meses.id')->where("id_cliente", $cliente['id_cliente'])->get();

		return $pagos;
	}

	function agregar_pago($data){
		
		if(isset($data["id_cliente"]) && isset($data["mes"])){

			$cliente = Models\Clientes::find($data["id_cliente"]);
			$mes = Models\Meses::find($data["mes"]);

			if(!isset($cliente) && !isset($mes)){

				$resp["MSJ"] = MSJ_error("No se puede crear el pago");

			}else{
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

					$resp["DATA"] = $respuesta;
					$resp["MSJ"] = MSJ_success("Pago creado correctamente");

				}else{
					$resp["MSJ"] = MSJ_error("No se puedo crear el pago");
				}
			}

			

		}else{
			$resp["MSJ"] = MSJ_error("No se proporcionaron datos necesarios");
		}

		return $resp;

	}

	function eliminar_pago($pago_id){
		$pago = Models\Pagos::where('id', $pago_id)->first();

		if($pago){

			Models\MovCuentas::where('id_pago', $pago->id)->delete();
			Data::recalcular_saldo_cuentas();
			
			$pago->delete();

			$resp["DATA"] = $pago_id;
			$resp["MSJ"] = MSJ_success("Pago eliminado correctamente", "Eliminado");
		}else{
			$resp["MSJ"] = MSJ_error("No se pudo eliminar el pago");
		}

		return $resp;
	}

	function obtener_pagos($data = null){

		if(!is_null($data) && isset($data['id_mes'])){
			$pagos = Models\Pagos::where("id_mes", $data['id_mes'])->get();
		}else{
			$pagos = Models\Pagos::where("pagos.estado", "Pendiente")->get();
		}
		//$pagos = Models\Pagos::join("clientes", "pagos.id_cliente" ,"=", "clientes.id" )->select("pagos.id", "clientes.nombre", "clientes.apellido", "pagos.fecha_cobro", "pagos.estado", "pagos.monto")->where("pagos.estado", "Pendiente")->get();
		

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
		$resp=''; 

		if($registro && $cliente)
		{

			if($registro['estado'] == 'Pagado'){
				$resp["MSJ"]= MSJ_error("No se puede modificar, ya esta pagado. Para modificar elimine el pago y vuelve a crear"); 
			}else{

				$resp["DATA"] = $registro->update($data);

				if(!is_null($resp["DATA"])){

					$registro['nombre'] = $cliente->nombre;
					$registro['apellido'] = $cliente->apellido;
					$resp["DATA"] = [];
					$resp["DATA"] = $registro;

				}else{
					$resp["MSJ"]= MSJ_error("No se puede guardar en la base de datos"); 
				}
			}	
				
		}else{
			$resp["MSJ"]= MSJ_error("No se puede cargar el pago..."); 
		}

		return $resp;
	}

	function agregar_cobro($data){
	
		$resp = [];
		if(!isset($data['pagos']['id']) | !validateDate($data['fecha_cobro']) | !isset($data['cuenta']) ){
			return $resp["MSJ"] = MSJ_error("No se proporcionaron datos necesarios");
		}
		
		

		DB::transaction(function() use ($data){
			
			$pagos = Models\Pagos::find($data['pagos']['id']);
			$cuenta = Models\Cuentas::find($data['cuenta']);

			foreach ($pagos as $key => $value) {

				if($value->estado == 'Pagado'){
					$resp["MSJ"] = MSJ_error("La factura ya se pago");
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
	
					$resp["DATA"] = $value;
					$resp["MSJ"] = MSJ_success("Pago Cobrado Correctamente", "Cobrado");
				}
				
			}
	
			$cuenta->update();

		});

		return $resp;
	}
	

}

function validateDate($date, $format = 'Y-m-d')
{
    $d = \DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}



/// Mensajes para la interfas

function MSJ_success($text = "Resultado ??xitoso", $title = "??xito"){

	return ["success" => ["title" => $title, "text" => $text]];
	
}

function MSJ_error($text = "Por favor contacte con el adminitrador", $title = "Error"){

	return ["error" => ["title" => $title, "text" => $text]];
	
}

function MSJ_warning($text = " ", $title = "Alerta"){

	return ["warning" => ["title" => $title, "text" => $text]];
	
}




