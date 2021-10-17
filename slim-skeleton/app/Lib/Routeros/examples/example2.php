<?php

require('../routeros_api.class.php');

$API = new RouterosAPI();

$API->debug = true;

if ($API->connect('192.168.11.209', 'admin', '8891957')) {

   $API->write('/ip/hotspot/user/print',false);
   //$API->write('=stats=');
 
   $READ = $API->read(false);
   $ARRAY = $API->parseResponse($READ);

   print_r($ARRAY);

   $API->disconnect();

}

?>
