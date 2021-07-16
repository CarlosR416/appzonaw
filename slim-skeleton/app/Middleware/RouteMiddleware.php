<?php

namespace App\Middleware;



class RouteMiddleware extends Middleware
{
	
	
	public function __invoke($request, $response, $next)
	{
		
		$route = $request->getUri()->getPath(); 
		
		die(var_dump($route));

		if (!is_null($route)) {

		  	$response = $next($request, $response);

		}else{

			die('error 404');
		  	return $response->withRedirect('login');

		}


		return $response;
        


	}
	
	

	
}

?>