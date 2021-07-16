<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuentas extends Model
{
	 
	protected $fillable = [
		"nombre", 
        "saldo"
	];

	
}