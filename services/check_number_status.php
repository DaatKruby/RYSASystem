<?php

global $wpdb;
require('../../../../wp-load.php');

$id_rifa = (int)$_GET['rifa'];

$boletos = $_GET['numbers'];
$boletos = explode(",", $boletos);
$cantBoletos = count($boletos);
$estadosBoleto = "";
$error = false;

foreach ($boletos as $key => $boleto) {
    try {
        $query = $wpdb->prepare ('call sp_is_boleto_disponible( %d , %d)', [$id_rifa, $boleto]);
        $results = $wpdb->get_row($query);
        $result = $results->result;
        $estadosBoleto = $estadosBoleto . $result;
        if ($key !== $cantBoletos - 1) {
            $estadosBoleto = $estadosBoleto . ",";
        }
    } catch (\Exception $e) {
        $error = true;
        echo $e->getMessage();
    }
}

if ($error !== true) {
    echo $estadosBoleto;
}

?>