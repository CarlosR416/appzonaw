<?php 

namespace App\Controller;
use App\Helpers;


class AuthController
{
	
	protected $container;

    function __construct($container){
        $this->container = $container;
    }

	function ingresar(){

		$Auth =	Helpers\Auth::login(
					$this->container->request->getParam('pass'),
					$this->container->request->getParam('user')
				);
	
		if ($Auth){

			return $this->container->response->withRedirect($this->container->router->pathFor('ruta', ['arg1' => $_SESSION['path'][0], 'arg2' => $_SESSION['path'][1]]));
			
		}

		
		return $this->container->response->withRedirect($this->container->router->pathFor('login'));

	}

	public function logout(){

		Helpers\Auth::logout();

		return  $this->container->response->withRedirect($this->container->router->pathFor('login'));

	}

	
}

?>