<?php

namespace App\Middleware;
use App\Helpers;



class AuthMiddleware extends Middleware
{
	 
	
	public function __invoke($request, $response, $next)
	{
		

		if (!Helpers\Auth::check()){

			return $response->withRedirect($this->container->router->pathFor('login'));
		  	
		}


		return $response = $next($request, $response);;
        
	}

	
	
	

	
}

?>