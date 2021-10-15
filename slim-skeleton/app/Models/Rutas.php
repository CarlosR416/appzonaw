<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rutas extends Model
{
	 
	protected $fillable = [
		"id",
        "nombre", 
        "descripcion",
        "id_menu",
		"crud_menu",  
        "ruta"
	];

    public function menu(){
		return  $this->belongsTo('App\Models\Menu', 'id_menu');
	}

	public function users(){
		return  $this->hasMany('App\Models\RutaUser', 'id_ruta');
	}
}