<?php  

require('../routeros_api.class.php');
$html = '';
$plan = '5Hora';
$titulo_hoja = 'Tickets 5 Horas';
$titulo_hoja = 'Tickets 5 Horas';
$titulo_archivo = 'Tickets 5 Horas - 14-08-2021';
$tiempo = '05:00:02';
$cantidad = 70*2;

$ticket_header = "Cód: 5 Horas";
$ticket_footer = "Valido por 72H";



$API = new RouterosAPI();

$API->debug = false;
$API->port = 8728;

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
	    max-width: 200px;
	  }
	  h3{
	  	margin-top: 30px;
	  }

	  .codigo{
	
		margin-top: 10px;
		border: solid #afadad 1px;
		padding: 1px 39px;
		font-size: x-large;
		background-color: #efefef;
	  }


 
</style>
</head>
<body style="margin: 0px;">

<?php

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



	$cantidad++;
   	for ($i=1; $i < $cantidad; $i++) {

   		$nun = 0;
   		$ancho = 5;
   		$alto = 70;

   		$nun = generate_string($permitted_chars2, 8);
   		//$leter = generate_string($permitted_chars, 6);

   		$codigo = $nun;

   		if ($tiempo == '') {
   			
   			$API->comm("/ip/hotspot/user/add", array(
		      "name"     => $codigo,
		      "server"  => "all",
		      "profile"      => $plan
		    ));

   		}else{
	   		
	   		$API->comm("/ip/hotspot/user/add", array(
		      "name"     => $codigo,
		      "server"  => "all",
		      "profile"      => $plan,
		      "limit-uptime" => $tiempo
		    ));
   		}
   	   
   	   if ($i%$alto == 1) {

   	   		//$html .= '<h3 style="text-align: center;">'.$titulo_hoja.'</h3><table style="border-collapse: collapse; font: caption;">';
   	   		$html .='<table style="border-collapse: collapse; font: caption; margin-bottom: 70px;">';
   	   		
   	   }

   		if ($i%$ancho == 1) {

   			$html .= '<tr>';

   		}
   		//<img src="icono.jpg" style="width: 15px; float: left; margin-top: 2px;">
   	    $html .= '
   	    <td>
			<div>
				<img src="logo2.png" style="width: 130px;">
				<!--<b style="font-size: 15px; display: block;">WILBEL HAMBUR</b>-->
				<b style="font-size: small; color: #03a9f4; font-family: system-ui; display: block; margin-bottom: 2px;">'.$ticket_header.'</b>
				<span class="codigo">'.$codigo.'</span>
				<br>
				<div style="width: 109px; margin: auto; margin-top: 1px;">
				<b style="font-size: small; font-family: system-ui;">'.$ticket_footer.'</b>
				</div>
			</div>
		</td>
   	    ';

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
