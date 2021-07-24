<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meses extends Model
{
	 
	protected $fillable = [
        "id",
		"nombre", 
        "clientes_activos",  
        "clientes_retirados",  
        "gastos",  
        "ingresos"  
	];

    public function pagos(){
		return  $this->hasMany('App\Models\Pagos', 'id_mes');
	}

	
}