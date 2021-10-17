<?php  

require('../routeros_api.class.php');
$html = '';
$plan = 'Hora';
$titulo_hoja = 'Tickets 3 Hora';
$titulo_archivo = 'Tickets 3 Hora - Wifi MaxJo 11-10-2021';
$tiempo = '03:00:00';
$cantidad = 248*5;

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
	  }
	  h3{
	  	margin-top: 30px;
	  }

 
</style>
</head>
<body style="margin: 0px;">

<?php

$codigos = [
			'74487854',
			'96435781',
			'34337036',
			'28334638',
			'63812162',
			'52594122',
			'35477348',
			'00444314',
			'28561023',
			'95439177',
			'46215711',
			'94178003',
			'45166284',
			'29661428',
			'53716525',
			'19518548',
			'66678791',
			'02231442',
			'68214123',
			'88531082',
			'55671616',
			'65043772',
			'54368022',
			'41740948',
			'97901806',
			'74478290',
			'97404200',
			'76271033',
			'74479309',
			'74797470',
			'16917635',
			'47876745',
			'10674197',
			'81421938',
			'01304816',
			'93346952',
			'06447586',
			'92511478',
			'97121885',
			'64498830',
			'43417627',
			'03471227',
			'12904350',
			'54103702',
			'21688409',
			'41808919',
			'54776384',
			'66982587',
			'13368674',
			'18406689',
			'79507460',
			'41788306',
			'06459711',
			'49620099',
			'98261201',
			'11142941',
			'53844118',
			'61826680',
			'30334823',
			'62775956',
			'20758433',
			'38188079',
			'07214008',
			'15089848',
			'83150773',
			'98687642',
			'35292043',
			'98736837',
			'91073093',
			'76453677',
			'10906469',
			'15962082',
			'81852678',
			'09439894',
			'89805570',
			'97688578',
			'79588048',
			'31634266',
			'16662672',
			'93864154',
			'45852242',
			'38334742',
			'94073243',
			'28340800',
			'83275133',
			'06068375',
			'16513736',
			'31871952',
			'52962816',
			'72602840',
			'72724118',
			'33386953',
			'74964309',
			'69618057',
			'77401393',
			'65279684',
			'75854008',
			'91430737',
			'68915232',
			'18306299',
			'02188019',
			'52873783',
			'54345601',
			'49060243',
			'37510479',
			'52526586',
			'50346393',
			'01452427',
			'67236699',
			'47975368',
			'35494509',
			'17188105',
			'12003195',
			'20486732',
			'61672123',
			'91485483',
			'39873452',
			'69341669',
			'06480844',
			'41974466',
			'03429037',
			'56316073',
			'06943332',
			'59256094',
			'48599641',
			'40080506',
			'69645458',
			'89493641',
			'66643381',
			'72160855',
			'17207873',
			'30589155',
			'86308795',
			'49070887',
			'88607449',
			'04757708',
			'65138714',
			'30263269',
			'93513832',
			'05979036',
			'11516741',
			'34356764',
			'17281568',
			'73076358',
			'14614502',
			'19427028',
			'09642046',
			'52284650',
			'47369759',
			'51365714',
			'98566666',
			'29871590',
			'69928187',
			'24485808',
			'27120170',
			'56806759',
			'55319144',
			'45962061',
			'97250452',
			'65782917',
			'69511732',
			'28264698',
			'04155855',
			'84477384',
			'87604582',
			'78400496',
			'20852122',
			'37229666',
			'37128449',
			'31483565',
			'32483374',
			'81532401',
			'16400867',
			'47607440',
			'38264506',
			'53397676',
			'00316433',
			'59485691',
			'98392039',
			'53235814',
			'19495612',
			'30275381',
			'50714502',
			'12324027',
			'96188364',
			'36034648',
			'44852158',
			'50359450',
			'95593591',
			'32126532',
			'96026364',
			'81121293',
			'90477009',
			'18076893',
			'34730958',
			'91991579',
			'18162081',
			'83667124',
			'87391070',
			'94126936',
			'94760901',
			'07251769',
			'52641221',
			'16898057',
			'72132393',
			'76604247',
			'20570955',
			'38925499',
			'61082675',
			'69751984',
			'10092190',
			'95427590',
			'15823791',
			'31465671',
			'87829112',
			'74656430',
			'20097318',
			'38297627',
			'33895183',
			'79140839',
			'36434426',
			'86319848',
			'67563859',
			'53902947',
			'75236897',
			'09468608',
			'68635608',
			'19365602',
			'24625035',
			'50654780',
			'07160917',
			'59401751',
			'46078694',
			'73213378',
			'45365471',
			'67639803',
			'43385827',
			'20750782',
			'54489454',
			'70488312',
			'99234029',
			'36484773',
			'08697795',
			'68903695',
			'77051271',
			'48182719',
			'61324017',
			'55010844'
];

//die(var_dump($codigos));

if ($API->connect('192.168.88.1', 'admin', 'admin8891957')) {

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
   		$ancho = 8;
   		$alto = 248;

   		$nun = generate_string($permitted_chars2, 8);
   		//$leter = generate_string($permitted_chars, 6);

   		$codigo = $nun;
   		//$codigo = $codigos[$i-1];

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
