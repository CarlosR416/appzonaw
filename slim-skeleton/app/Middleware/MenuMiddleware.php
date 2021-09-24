<?php

namespace App\Middleware;
use App\Helpers;
use App\Models;



class MenuMiddleware extends Middleware
{
	 
	
	public function __invoke($request, $response, $next)
	{
		

		

		$uri = $request->getUri();
		$path = $uri->getPath();

		$auth = new Helpers\Auth;
		
		if ($auth->check()){

			$conf = $auth->conf();

			$this->container->twig->getEnvironment()->addGlobal('menu', $conf['menu']);
			$this->container->twig->getEnvironment()->addGlobal('session_user', $conf['session_user']);
			
		}

		return $response = $next($request, $response);
        
	}



	
	
	

	
}

?>