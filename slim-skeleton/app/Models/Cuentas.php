<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuentas extends Model
{
	 
	protected $fillable = [
		"id",
		"nombre", 
        "saldo"
	];

	public function mov_cuentas(){
		return  $this->hasMany('App\Models\MovCuentas', 'id_cuenta');
	}
	
}