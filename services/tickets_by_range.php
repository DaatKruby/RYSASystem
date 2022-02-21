<?php

global $wpdb;
require('../../../../wp-load.php');

$min = (int)$_GET['min'];
$max = (int)$_GET['max'];

$error = false;
$numeros = [];

$id_rifa = null;
if (isset($_GET["rifa"])) {
    $id_rifa = (int)$_GET['rifa'];
}
$rifa = $rifas_data->get_rifa($id_rifa);

$myJson = array("error" => false, "boletos" => null);

if ($rifa) {
    try {
        $query = $wpdb->prepare('select num_boleto, id_estado_boleto from tbl_boleto where id_rifa = %d and num_boleto >= %d and num_boleto <= %d and num_boleto < %d order by num_boleto asc', [$id_rifa, $min, $max, $rifa->get_comienzo_numeros_reservados()]);
        $results = $wpdb->get_results($query);

        foreach ($results as $numero) {
            $numeros[] = $boleto = array("numero" => $numero->num_boleto, "estado" => $numero->id_estado_boleto);
        }
    } catch (\Exception $e) {
        $error = true;
    }
} else {
    $error=true;
}

if ($error === true) {
    $myJson["error"] = true;
} else {
    $myJson["boletos"] = $numeros;
}
echo json_encode($myJson);
