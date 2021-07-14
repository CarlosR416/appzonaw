<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovCuentas extends Model
{
	 
	protected $fillable = [
		"id_cuenta", 
        "valor",
        "tipo_mov"
	];

	
}