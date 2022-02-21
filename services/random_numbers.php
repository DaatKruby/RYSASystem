<?php

global $wpdb;
require('../../../../wp-load.php');

$cantidad = $_GET['cantidad'];
$id_rifa = null;
if (isset($_GET["rifa"])) {
    $id_rifa = (int)$_GET['rifa'];
}
$rifa = $rifas_data->get_rifa($id_rifa);

$error = false;
$numeros = [];
$numeros_str = "";

try {
    
    $query = $wpdb->prepare ('call sp_get_boletos_disponibles_rnd_range( %d , %d, %d, %d)', [$id_rifa, $cantidad, 0, $rifa->get_comienzo_numeros_reservados()-1]);
    $results = $wpdb->get_results($query);
        
    foreach ($results as $numero) {
        $numeros[] = $numero->boleto;
    }
    
    foreach ($numeros as $key => $numero) {
        $numeros_str = $numeros_str . $numero;
        if ($key !== $cantidad - 1) {
            $numeros_str = $numeros_str . ",";
        }
    }

} catch (\Exception $e) {
    $error = true;
    echo $e->getMessage();
}

if ($error !== true) {
    echo $numeros_str;
}
