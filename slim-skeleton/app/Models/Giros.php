<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Giros extends Model
{
	 
	protected $fillable = [
		"id",
        "ref", 
        "Nombre",  
        "Cedula",  
        "monto_env",  
        "monto_entregar",   
        "estado",
        "created_at",  
        "updated_at"  
	];

	
}