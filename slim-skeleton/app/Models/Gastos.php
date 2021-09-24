<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gastos extends Model
{
	 
	protected $fillable = [
		"id",
        "id_mes", 
        "descripcion",
        "valor",
        "fecha"
	];

    
}