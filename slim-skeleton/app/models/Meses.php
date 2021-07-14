<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meses extends Model
{
	 
	protected $fillable = [
		"nombre", 
        "clientes_activos",  
        "clientes_retirados",  
        "gastos",  
        "ingresos"  
	];

	
}