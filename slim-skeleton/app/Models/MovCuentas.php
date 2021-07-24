<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovCuentas extends Model
{
	 
	protected $fillable = [
		"id",
		"id_cuenta", 
		"id_mes",
		"id_pago",
        "tipo",
		"descripcion",
        "valor",
		"fecha"
	];


	public function cuenta()
	{

		return  $this->belongsTo('App\Models\Cuentas', 'id_cuenta');
	
	}

	public function pago()
	{

		return  $this->belongsTo('App\Models\Pagos', 'id_pago');
	
	}
	
}