<?php

namespace App\Middleware;
use App\Helpers;



class AuthMiddleware extends Middleware
{
	 
	
	public function __invoke($request, $response, $next)
	{
		
		$uri = $request->getUri();
		$path = $uri->getPath();
		
		if (!Helpers\Auth::check()){

			return $response->withRedirect($this->container->router->pathFor('login'));
		  	
		}else if(!Helpers\Auth::checkpath($path)){

			$path = "noautorizado";
			$uri = $uri->withPath($path);

			return $response->withStatus(403)
            ->withHeader('Content-Type', 'text/html')
            ->write(json_encode([['error' => 'No estas autorizado']]));
		}

		return $response = $next($request, $response);
        
	}



	
	
	

	
}

?>