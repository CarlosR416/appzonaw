<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RutaUser extends Model
{
	 
	protected $fillable = [
		"id",
        "id_user", 
        "id_ruta"
	];

	public function ruta()
	{

		return  $this->belongsTo('App\Models\Rutas', 'id_ruta');
	
	}

	public function user()
	{

		return  $this->belongsTo('App\Models\User', 'id_user');
	
	}


}