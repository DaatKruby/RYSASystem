<?php

function agregar_folio_con_boletos($rifa_class, $id_rifa, $cant_boletos, $nombres, $apellidos, $celular, $estado, $correo)
{
    global $wpdb;
    global $boletos_obtenidos;
    $boletos_obtenidos = [];

    try {
        $wpdb->query("start transaction;");

        $query = $wpdb->prepare("call sp_obtener_folio_unico (%d);", [$id_rifa]);
        $result = $wpdb->get_row($query);
        $folio = $result->result;

        $result = $wpdb->get_row("select cant_boletos from tbl_rifa where id_rifa = " . $id_rifa);
        $boletos_totales_tabla = $result->cant_boletos;

        $result = $wpdb->get_row("select now() as 'date';");
        $fecha = $result->date;

        $query = $wpdb->prepare("call sp_insert_folio (%d, %d, %s, %s, %s, %s, %s);", [$folio, $id_rifa, $nombres, $apellidos, $celular, $estado, $correo]);
        $wpdb->query($query);
        $query = $wpdb->prepare("update tbl_metadata_folio set ultimo_valor_folio=%d where id_rifa=%d", [$folio, $id_rifa]);
        $wpdb->query($query);

        $boleto_max_para_rnds = $boletos_totales_tabla;
        if ($rifa_class->get_tipo_rifa() == 3) {
            $boleto_max_para_rnds = $rifa_class->get_comienzo_numeros_reservados() - 1;
        }
        $query = $wpdb->prepare("call sp_get_boletos_disponibles_rnd_range( %d, %d, %d, %d)", [$id_rifa, $cant_boletos, 0, $boleto_max_para_rnds]);
        $result = $wpdb->get_results($query);

        /*CONVERTIR BOLETOS ALEATORIOS A ARRAY*/
        $boletos = [];
        foreach ($result as $boleto) {
            $boletos[] = $boleto->boleto;
        }

        /*SI RIFA TIPO 3, AGREGAR LOS BOLETOS EXTRA*/
        if ($rifa_class->get_tipo_rifa() == 3) {
            $boletos_extra = $rifa_class->obtener_boletos_de_regalo($boletos);
            $boletos = array_merge($boletos, $boletos_extra);
        }

        foreach ($boletos as $boleto) {
            $query = $wpdb->prepare("call sp_apartar_boleto (%d, %d, %d, %s);", [$id_rifa, $folio, $boleto, $fecha]);
            $boletos_obtenidos[] = $boleto;
            $wpdb->query($query);
        }

        $wpdb->query("commit;");
        return $folio;
    } catch (\Throwable $e) {
        $wpdb->query("rollback;");
        echo $e;
        return null;
    }
}

function resetar_boletos_apartados_auto($id_rifa)
{
    global $wpdb;
    try {
        $query = $wpdb->prepare("call sp_reiniciar_estado_por_fecha(%d)", [$id_rifa]);
        $result = $wpdb->query($query);
    } catch (\Throwable $th) {
        //echo $th;
    }
}

function obtener_boletos_random($id_rifa, $cant_randoms, $min_rng, $max_rng)
{
    global $wpdb;
    $query = $wpdb->prepare('call sp_get_boletos_disponibles_rnd_range( %d , %d, %d, %d)', [$id_rifa, $cant_randoms, $min_rng, $max_rng]);
    $results = $wpdb->get_results($query);
    return $results;
}

function obtener_whatsapp_rnd($id_rifa)
{
    global $wpdb;
    $query = $wpdb->prepare('select * from tbl_num_whatsapp where id_rifa = %d', [$id_rifa]);
    $results = $wpdb->get_results($query);

    $whatsapp_numbers = [];

    foreach ($results as $result) {
        $whatsapp_numbers[] = $result->num_whatsapp;
    }

    $cant_numbers = count($whatsapp_numbers);
    if ($cant_numbers != 0) {
        $rnd_whatsapp = $whatsapp_numbers[rand(0, $cant_numbers - 1)];
    } else {
        $rnd_whatsapp = "NO_NUMBER_PROGRAMMED";
    }
    return $rnd_whatsapp;
}

function fix_folio_rifa_tipo_3($id_rifa, $folio, $start_rnd_numbers)
{
    global $wpdb;
    $query = $wpdb->prepare("call fix_boletos_faltantes_rango_tipo_3(%d, %d, %d, %d)", [$id_rifa, $start_rnd_numbers, $folio, $folio]);
    $wpdb->query($query);
}
