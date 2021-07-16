<?php 

namespace App\Controller;
use App\helpers;


class AuthController
{
	
	protected $container;

    function __construct($container){
        $this->container = $container;
    }

	public function GetLogin($request, $response){
		
		return $this->view->render($response, '/login/login.html');
		
	}

	function ingresar(){

		$Auth =	Helpers\Auth::login(
					$this->container->request->getParam('pass'),
					$this->container->request->getParam('user')
				);
	
		if ($Auth){

			return $this->container->response->withRedirect($this->container->router->pathFor('ruta', ['name' => 'clientes', 'name2' => 'todos']));
			
		}

		return $this->container->response->withRedirect($this->container->router->pathFor('login'));

	}

	public function logout($request, $response){

		Helpers\Auth::logout();

		return  $this->container->response->withRedirect($this->container->router->pathFor('login'));

	}
	
}

?>