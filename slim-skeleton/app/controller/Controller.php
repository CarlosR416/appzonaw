<?php  

namespace App\Controller;


/**
 * 
 */
class Controller 
{
	
	protected $container;

	public function __construct($container)
	{
		$this->container = $container;
	}

	public function __run($class_c, $property)
	{	
		$class = "\App\\Controller\\".ucfirst($class_c);
		
		if(class_exists($class)){
			
			$controller = new $class($this->container);
			
			if(method_exists($controller, $property)){
				return $controller->$property();
			}else{
				return $this->container->twig->render($this->container->response,  "page_404.twig");
			}

		}else{

			return $this->container->twig->render($this->container->response,  "page_404.twig");

		}
		
		
	}


}



?>