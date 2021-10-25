<?php
namespace App\Helpers; 

class Api_mikrotick
{
    
    function crear_tickets($param){
        
        $html = '';
        $plan = $param["tickets_plan"]->plan;
        $tiempo = $param["tickets_plan"]->tiempo;
        $titulo_hoja = 'Tickets '.$param["tickets_plan"]->titulo;
        $titulo_archivo = $titulo_hoja.' - '.$param["tickets_nombre"]." ".$param["tickets_fecha_generacion"];
        
        $cantidad = $param["tickets_nun_tickets"];

        $API = new \App\Lib\Routeros\RouterosAPI();

        $API->debug = false;
        $API->port = 8728;

        echo '
        <!DOCTYPE html>
        <html>
        <head>
            <title>'.$titulo_archivo.'</title>

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
        <body style="margin: 0px;">';

        
        function crear_plan($API, $plan){

        //$script = \App\Models\Mikroscript::where("nombre", $plan)->first();
      
        switch ($plan) {
                case '1 Hora':
                    
                   $API->comm("/ip/hotspot/user/profile/add", array(
                        "name"     => "mi hora",
                        "on-login" => $script
                    ));

                    break;

                case 'Mes':
                    # code...
                    break;
                
                default:
                    # code...
                    break;
            }


        }
        //die(var_dump($codigos));

        if ($API->connect($param["tickets_ip_mik"], $param["tickets_user_mik"], $param["tickets_password_mik"])) {
        

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
            echo "</div>  </body> </html>";  

    }
    
}