<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Giros extends Model
{
	 
	protected $fillable = [
		"id",
        "ref", 
        "nombre_remitente",  
        "cedula_remitente",
        "nombre_destinatario",  
        "cedula_destinatario",  
        "monto_env",
        "tipo_porcentaje",  
        "monto_entregar",   
        "estado",
        "nota",
        "created_at",  
        "updated_at"  
	];

	
}