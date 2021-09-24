<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
	 
	protected $fillable = [
		"id",
        "nombre", 
        "nivel",
        "icono",  
        "id_ruta"
	];

    public function ruta()
	{

		return  $this->belongsTo('App\Models\Rutas', 'id_ruta');
	
	}
}