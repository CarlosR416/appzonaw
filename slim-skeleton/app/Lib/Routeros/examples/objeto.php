<?php

$clientes = [
                ['7C-A1-77-18-E3-88','Yanet'],
                ['08-BF-A0-61-94-FD','A21s'],
                ['70-9F-A9-4F-20-88','Alex'],
                ['04-4F-4C-16-C7-24','blanca'],
                ['7C-1C-68-BE-5E-2A','Sra Neida Chacon'],
                ['00-27-15-80-39-AB','Carlos Belandia'],
                ['50-8A-0F-EF-89-35','Nayeli'],
                ['24-0D-C2-5B-9B-3B','Sr Marilu'],
                ['BC-98-DF-3B-02-BB','Yasmin'],
                ['B0-55-08-34-A2-AB','Sra Sandra'],
                ['00-57-F7-B2-06-1A','Norelis'],
                ['7C-FD-6B-E2-A9-D9','Hija de Ulices'],
                ['B4-F1-DA-E6-4D-99','esposa de ulices'],
                ['B4-1C-30-E4-4A-56','Hijo de Norelis'],
                ['B4-39-39-13-BB-02','Andreina'],
                ['7C-23-02-97-6E-D2','edilsa belandia'],
                ['C0-3F-D5-DA-87-B4','table hijo de edilsa belandia'],
                ['BC-2D-EF-DB-23-23','Gonzalo'],
                ['B4-1C-30-E6-7E-9D','Hijo de Gonzalo'],
                ['7C-23-02-9A-CC-1E','Señor luis'],
                ['7C-1C-68-F3-5A-18','Sra Alba']
            ];

$datos = [
        [
            'mac' => '7C-A1-77-18-E3-88',
            'comentario' => 'Yanet',
            'ip' => '192.168.11.2'
        ],
        [
            'mac' => '08-BF-A0-61-94-FD',
            'comentario' => 'A21s',
            'ip' => '192.168.11.2'
        ],
        [
            'mac' => '70-9F-A9-4F-20-88',
            'comentario' => 'Alex',
            'ip' => '192.168.11.2'
        ],
        [
            'mac' => '04-4F-4C-16-C7-24',
            'comentario' => 'blanca',
            'ip' => '192.168.11.2'
        ],
        [
            'mac' => '7C-1C-68-BE-5E-2A',
            'comentario' => 'Sra Neida Chacon',
            'ip' => '192.168.11.2'
        ],
        [
            'mac' => '00-27-15-80-39-AB',
            'comentario' => 'Carlos Belandia',
            'ip' => '192.168.11.2'
        ],
        [
            'mac' => '50-8A-0F-EF-89-35',
            'comentario' => 'Nayeli',
            'ip' => '192.168.11.2'
        ],
        [
            'mac' => '24-0D-C2-5B-9B-3B',
            'comentario' => 'Sr Marilu',
            'ip' => '192.168.11.2'
        ],
        [
            'mac' => 'BC-98-DF-3B-02-BB',
            'comentario' => 'Yasmin',
            'ip' => '192.168.11.2'
        ],
        [
            'mac' => 'B0-55-08-34-A2-AB',
            'comentario' => 'Sra Sandra',
            'ip' => '192.168.11.2'
        ],
        [
            'mac' => '00-57-F7-B2-06-1A',
            'comentario' => 'Norelis',
            'ip' => '192.168.11.2'
        ],
        [
            'mac' => '7C-FD-6B-E2-A9-D9',
            'comentario' => 'Hija de Ulices',
            'ip' => '192.168.11.2'
        ],
        [
            'mac' => 'B4-F1-DA-E6-4D-99',
            'comentario' => 'esposa de ulices',
            'ip' => '192.168.11.2'
        ],
        [
            'mac' => 'B4-1C-30-E4-4A-56',
            'comentario' => 'Hijo de Norelis',
            'ip' => '192.168.11.2'
        ],
        [
            'mac' => 'B4-39-39-13-BB-02',
            'comentario' => 'Andreina',
            'ip' => '192.168.11.2'
        ],
        [
            'mac' => '7C-23-02-97-6E-D2',
            'comentario' => 'edilsa belandia',
            'ip' => '192.168.11.2'
        ],
        [
            'mac' => 'C0-3F-D5-DA-87-B4',
            'comentario' => 'table hijo de edilsa belandia',
            'ip' => '192.168.11.2'
        ],
        [
            'mac' => 'BC-2D-EF-DB-23-23',
            'comentario' => 'Gonzalo',
            'ip' => '192.168.11.2'
        ],
        [
            'mac' => 'B4-1C-30-E6-7E-9D',
            'comentario' => 'Hijo de Gonzalo',
            'ip' => '192.168.11.2'
        ],
        [
            'mac' => '7C-23-02-9A-CC-1E',
            'comentario' => 'Señor luis',
            'ip' => '192.168.11.2'
        ],
        [
            'mac' => '7C-1C-68-F3-5A-18',
            'comentario' => 'Sra Alba',
            'ip' => '192.168.11.2'
        ]
    ];
$nun = 230;
echo '[<br>';
for ($i=0; $i < count($clientes); $i++) {

    echo '[<br>';

    echo "'mac' => '".$clientes[$i][0]."',<br>";
    echo "'comentario' => '".$clientes[$i][1]."',<br>";
    echo "'ip' => '192.168.80.".$nun++."'<br>";

    echo '],<br>';

}

echo ']<br>';
die();