<?php

global $wpdb;
require('../../../wp-load.php');
$boletos = $_GET['numbers'];
$boletos = explode(",", $boletos);
$cantBoletos = count($boletos);
$estadosBoleto = "";

try{
    $cant_boletos_rifa = $wpdb ->get_row("select cant_boletos from tbl_rifa where id_rifa=2");
    $cant_boletos_rifa = $cant_boletos_rifa -> cant_boletos;
    foreach ($boletos as $boleto){
        if($boleto < 0 || $boleto > $cant_boletos_rifa){
            throw new Exception('out of bounds');
        }
    }
    
    foreach ($boletos as $key => $boleto) {
        $results = $wpdb->get_row('call sp_is_boleto_disponible(2,' . $boleto . ')');
        $result = $results->result;
        $estadosBoleto = $estadosBoleto . $result;
        if ($key !== $cantBoletos - 1) {
            $estadosBoleto = $estadosBoleto . ",";
        }
    }

    echo $estadosBoleto;
}catch(\Exception $e){
    echo $e;
}

?>