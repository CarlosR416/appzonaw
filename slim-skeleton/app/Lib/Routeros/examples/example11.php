<?php  
	$html = '';
	$titulo_hoja = 'Tickets 2 Hora';
	$titulo_archivo = 'Tickets 2 Hora - 06-10-2021';
	$ntickets = 248*2;

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

	
	$i = 0;
	$nfile = 0;
	$file = false;
	for ($j=1; $j <= $ntickets; $j++) { 

		$nun = 0;
   		$ancho = 8;
   		$alto = 248;

		if ($j>$i) {

			$nfile = $nfile+1;
			$file = fopen("tickets/tickets".$nfile.".txt", "w+");
			$i = 400+$i;
		}
	
		$nun = generate_string($permitted_chars2, 8);

		if ($j < $i && $j < $ntickets) {
			fwrite($file, $nun . PHP_EOL);
		}else{
			fwrite($file, $nun );
			fclose($file);
		}
			
	    if ($j%$alto == 1) {

   	   		$html .= '<h3 style="text-align: center;">'.$titulo_hoja.'</h3><table style="border-collapse: collapse; font: caption;">';
   	   		
   	   }

   		if ($j%$ancho == 1) {

   			$html .= '<tr>';

   		}
   		
   	    $html .= '<td>'.$nun.'</td>';

   	    if ($j%$ancho == 0) {

   	    	$html .= '</tr>';

   	    }
   	    
   	    if ($j%$alto == 0) {

   	   		$html .= '</table>';
   	    }
		
	}

	echo '<div style="width: max-content;">';
	echo $html;
	echo "</div>";
	
?>
</body>
</html>
