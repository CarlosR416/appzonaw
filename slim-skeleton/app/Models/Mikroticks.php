<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mikroticks extends Model
{
    
	protected $fillable = [
		"id",
        "descripcion",
        "cliente_id",
        "ip",
        "mac",
        "port",
        "fecha" 
	];

	
}