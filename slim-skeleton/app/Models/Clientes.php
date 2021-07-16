<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
	
	protected $fillable = [
		"id",  
		"nombre",  
		"apellido",  
		"cedula",  
		"direccion",  
		"email",   
		"tipo_servicio",  
		"estatus_servicio",  
		"dia_cobro",  
		"modelo_equipo",  
		"mac_address",  
		"fecha_ingreso"  
	];

	public function pagos(){
		return  $this->hasMany('App\Models\Pagos', 'id_cliente');
	}
	
}

?>