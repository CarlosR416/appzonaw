<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
	
	protected $fillable = [
		'id',
		'usuario',
		'name',
		'pass',
		'admin'
	];
	
	public function rutas(){
		return  $this->hasMany('App\Models\RutaUser', 'id_user');
	}
}

?>