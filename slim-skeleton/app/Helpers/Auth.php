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
}

?>