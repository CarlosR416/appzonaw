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


    public function gastos(){
		return  $this->hasMany('App\Models\Gastos', 'id_mes');
	}

    public function movimientos(){
		return  $this->hasMany('App\Models\MovCuentas', 'id_mes');
	}

   
	
}