<?php  

require('../routeros_api.class.php');
$html = '';
$plan = 'Mes';
$titulo_hoja = 'Tickets 1 Mes';
$titulo_archivo = 'Tickets 1 Mes - Noe 28-05-2021';
$tiempo = '';
//$cantidad = 248*1;

$API = new RouterosAPI();

$API->debug = false;

?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $titulo_archivo; ?></title>

	<style type="text/css">
  
	  td{
	    border: solid 1px;
	    border-style: dashed;
	    padding: 5px 10px 5px 10px;
	    text-align: center;
	    font-size: 16px;
	  }
	  h3{
	  	margin-top: 30px;
	  }

 
</style>
</head>
<body style="margin: 0px;">

<?php

$codigos = [



];


$cantidad = count($codigos);

//die(var_dump($codigos));

if ($API->connect('192.168.11.208', 'admin', '8891957')) {

   /*$API->write('/ip/hotspot');

   $READ = $API->read(false);
   $ARRAY = $API->parseResponse($READ);

   print_r($ARRAY);
 
	*/
	 
	// Output: iNCHNGzByPjhApvn7XBD

	 
	// Output: 70Fmr9mOlGID7OhtTbyj
	//echo generate_string($permitted_chars, 20);
	 
	// Output: Jp8iVNhZXhUdSlPi1sMNF7hOfmEWYl2UIMO9YqA4faJmS52iXdtlA3YyCfSlAbLYzjr0mzCWWQ7M8AgqDn2aumHoamsUtjZNhBfU
	//echo generate_string($permitted_chars, 100);
 

  // $API->disconnect();

   //$permitted_chars = 'abcdefghkupwmzx';
   $permitted_chars2 = '0123456789';
	 
	function generate_string($input, $strength = 16) {
	    $input_length = strlen($input);
	    $random_string = '';
	    for($i = 0; $i < $strength; $i++) {
	        $random_character = $input[mt_rand(0, $input_length - 1)];
	        $random_string .= $random_character;
	    }
	 
	    return $random_string;
	}


	$API->write('/ip/hotspot/user/print');
   	$users = $API->read();

	$cantidad++;
   	for ($i=1; $i < $cantidad; $i++) {

   		$nun = 0;
   		$ancho = 8;
   		$alto = 248;

   		//$nun = generate_string($permitted_chars2, 8);
   		//$leter = generate_string($permitted_chars, 6);

   		//$codigo = "28312464";
   		$codigo = $codigos[$i-1];
   		
   	
		foreach ($users as $key => $value) {
   		

   			if ($value["name"] == $codigo) {

   				$API->write('/ip/hotspot/user/remove', false);
     			$API->write("=.id=" . $value[".id"], true);

     			echo "<<<>>>>Eliminado<<<<>>>";

     			$API->read();

   			}

   		}
   		

   		
   	   
   	   if ($i%$alto == 1) {

   	   		$html .= '<h3 style="text-align: center;">'.$titulo_hoja.'</h3><table style="border-collapse: collapse; font: caption;">';
   	   		
   	   }

   		if ($i%$ancho == 1) {

   			$html .= '<tr>';

   		}
   		
   	    $html .= '<td>'.$codigo.'</td>';

   	    if ($i%$ancho == 0) {

   	    	$html .= '</tr>';

   	    }
   	    
   	    if ($i%$alto == 0) {

   	   		$html .= '</table>';
   	    }

   	   
   	}
	
	$API->disconnect();
	

	


	/*$ARRAY = $API->comm("/ip/hotspot/user/print", array());

	echo '<br><br>';

	foreach ($ARRAY as $key => $value) {

		if (isset($value['comment'])) {

			if (isset($value['limit-uptime'])) {
				echo $value['name'].' : '.$value['comment'].' : '.$value['profile'].' : '.$value['limit-uptime'].' : 1';
			}else{
				echo $value['name'].' : '.$value['comment'].' : '.$value['profile'].' : S/LU : 1';
			}
			
		
		}else{

			if (isset($value['limit-uptime'])) {
				echo $value['name'].' : S/FV :'.$value['profile'].' : '.$value['limit-uptime'].' : 0';
			}else{
				echo $value['name'].' : S/FV :'.$value['profile'].' : S/LU : 0';
			}
			

		}
		

		echo '<br>';

	}
	
	*/
	$API->disconnect();

}
	

	echo '<div style="width: max-content;">';
	echo $html;
	echo "</div>";
?>
</body>
</html>
