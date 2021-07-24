<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pagos extends Model
{
	 
	protected $fillable = [
		"id_cliente",
        "id_mes", 
        "fecha_cobro",  
        "fecha_pago",  
        "estado",  
        "monto",   
        "created_at",  
        "updated_at"  
	];

    public function cliente()
	{

		return  $this->belongsTo('App\Models\Clientes', 'id_cliente');
	
	}

    public function mes()
	{

		return  $this->belongsTo('App\Models\Meses', 'id_mes');
	
	}
	
}