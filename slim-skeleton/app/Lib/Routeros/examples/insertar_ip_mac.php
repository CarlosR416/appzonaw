<?php

require('../routeros_api.class.php');
$API = new RouterosAPI();

$API->debug = false;


$datos = [
            [
                'mac-address' => '7C-A1-77-18-E3-88',
                'comment' => 'Yanet',
                'ip' => '192.168.80.230'
            ],
            [
                'mac-address' => '08-BF-A0-61-94-FD',
                'comment' => 'A21s',
                'ip' => '192.168.80.231'
            ],
            [
                'mac-address' => '70-9F-A9-4F-20-88',
                'comment' => 'Alex',
                'ip' => '192.168.80.232'
            ],
            [
                'mac-address' => '04-4F-4C-16-C7-24',
                'comment' => 'blanca',
                'ip' => '192.168.80.233'
            ],
            [
                'mac-address' => '7C-1C-68-BE-5E-2A',
                'comment' => 'Sra Neida Chacon',
                'ip' => '192.168.80.234'
            ],
            [
                'mac-address' => '00-27-15-80-39-AB',
                'comment' => 'Carlos Belandia',
                'ip' => '192.168.80.235'
            ],
            [
                'mac-address' => '50-8A-0F-EF-89-35',
                'comment' => 'Nayeli',
                'ip' => '192.168.80.236'
            ],
            [
                'mac-address' => '24-0D-C2-5B-9B-3B',
                'comment' => 'Sr Marilu',
                'ip' => '192.168.80.237'
            ],
            [
                'mac-address' => 'BC-98-DF-3B-02-BB',
                'comment' => 'Yasmin',
                'ip' => '192.168.80.238'
            ],
            [
                'mac-address' => 'B0-55-08-34-A2-AB',
                'comment' => 'Sra Sandra',
                'ip' => '192.168.80.239'
            ],
            [
                'mac-address' => '00-57-F7-B2-06-1A',
                'comment' => 'Norelis',
                'ip' => '192.168.80.240'
            ],
            [
                'mac-address' => '7C-FD-6B-E2-A9-D9',
                'comment' => 'Hija de Ulices',
                'ip' => '192.168.80.241'
            ],
            [
                'mac-address' => 'B4-F1-DA-E6-4D-99',
                'comment' => 'esposa de ulices',
                'ip' => '192.168.80.242'
            ],
            [
                'mac-address' => 'B4-1C-30-E4-4A-56',
                'comment' => 'Hijo de Norelis',
                'ip' => '192.168.80.243'
            ],
            [
                'mac-address' => 'B4-39-39-13-BB-02',
                'comment' => 'Andreina',
                'ip' => '192.168.80.244'
            ],
            [
                'mac-address' => '7C-23-02-97-6E-D2',
                'comment' => 'edilsa belandia',
                'ip' => '192.168.80.245'
            ],
            [
                'mac-address' => 'C0-3F-D5-DA-87-B4',
                'comment' => 'table hijo de edilsa belandia',
                'ip' => '192.168.80.246'
            ],
            [
                'mac-address' => 'BC-2D-EF-DB-23-23',
                'comment' => 'Gonzalo',
                'ip' => '192.168.80.247'
            ],
            [
                'mac-address' => 'B4-1C-30-E6-7E-9D',
                'comment' => 'Hijo de Gonzalo',
                'ip' => '192.168.80.248'
            ],
            [
                'mac-address' => '7C-23-02-9A-CC-1E',
                'comment' => 'Señor luis',
                'ip' => '192.168.80.249'
            ],
            [
                'mac-address' => '7C-1C-68-F3-5A-18',
                'comment' => 'Sra Alba',
                'ip' => '192.168.80.250'
            ]
        ];
if ($API->connect('192.168.11.208', 'admin', '8891957')) {

for ($i=0; $i < count($datos); $i++) { 
    
$API->comm("/ip/dhcp-server/lease/add", array(
              "mac-address"     => $datos[$i]["mac-address"],
              "server"  => "defconf",
              "address"  => $datos[$i]["ip"],
              "comment"  => $datos[$i]["comment"]
            ));

}
$API->disconnect();

}