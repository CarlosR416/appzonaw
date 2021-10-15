<?php

namespace App\Middleware;
use App\Helpers;



class AuthMiddleware extends Middleware
{
	 
	
	public function __invoke($request, $response, $next)
	{
		
		$uri = $request->getUri();
		$path = $uri->getPath();
		
		$path = substr($path, 0, 1) == "/" ? substr($path, 1) : $path;
		
		if (!Helpers\Auth::check()){

			return $response->withRedirect($this->container->router->pathFor('login'));
		  	
		}else if(!Helpers\Auth::checkpath($path)){

			$path = "noautorizado";
			$uri = $uri->withPath($path);

			$msj = ["MSJ" => ['error' => ["title" => "Sin Permiso", "text" => "No estas autorizado"]]];

			return $response->withStatus(403)
            ->withHeader('Content-Type', 'text/html')
            ->write(json_encode($msj));
		}

		return $response = $next($request, $response);
        
	}



	
	
	

	
}

?>