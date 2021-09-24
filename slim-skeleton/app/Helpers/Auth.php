<?php
namespace App\Helpers; 
use App\Models;

class Auth
{
    public function logout()
	{
		session_destroy();
	}
	
	function check()
	{
		return isset($_SESSION['usuario']);
	} 

	function login($pass, $user)
	{

		$Resul = Models\User::where('usuario',$user)->first();

		if (!isset($Resul)) {

			return false;

		}else if($Resul->pass == $pass){


			$_SESSION['usuario'] = $Resul->id;
			$_SESSION['admin'] = $Resul->admin;
			return true;

		}
		
		return false;
	}

	function user()
	{

		if (isset($_SESSION['usuario'])) {

			return User::where('id',$_SESSION['usuario'])->first();

		}

		return false;
	}

	public function checkpath($path = '/'){

		$permitido[] = "data/no_autorizado";
		$permitido[] = "clientes/todos";
		$permitido[] = "pagos/obtener";
		$resp = false;

		$ruta = Models\Rutas::where('ruta', $path);
		
		if($ruta->exists()){
			$ruta = $ruta->first();
			$acceso = Models\RutaUser::where(['id_user' => $_SESSION['usuario'], 'id_ruta' => $ruta->id]);
			
			if($acceso->exists()){
				$resp = true;
			}

		}

		return $resp;

	}

	public function conf(){

		$session_user =  Models\User::find($_SESSION['usuario']);
		$menu = Models\Menu::orderBy('nivel')->get();
		
		$data = ['menu' => $menu, 'session_user' => $session_user];


		return $data;
	}
}

?>