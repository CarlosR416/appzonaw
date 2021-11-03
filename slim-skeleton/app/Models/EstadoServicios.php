<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoServicios extends Model
{
	 
	protected $fillable = [
		"id",
		"id_cliente",
		"estado", 
		"fecha"
	];

}