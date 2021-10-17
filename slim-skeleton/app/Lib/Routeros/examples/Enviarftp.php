<?php 
$server = "192.168.11.208"; 

$ftp_user_name = "admin"; 
$ftp_user_pass	= "8891957";
$dest = "tickets1.txt"; 
$source = "tickets/tickets1.txt";
$mode = FTP_ASCII;


$connection = ftp_connect($server);

$login = ftp_login($connection, $ftp_user_name, $ftp_user_pass);

if (!$connection || !$login) { die('Parece que no se puede conectar'); }

$upload = ftp_put($connection, $dest, $source, $mode);

if (!$upload) { echo 'Fallo la subida al FTP'; }

ftp_close($connection);

?>