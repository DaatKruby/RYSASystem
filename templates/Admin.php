<?php
/*
  *Template Name: Rifas Admin
 */
get_header();
global $rifas_data;
global $page_admin;
global $sections_folder;
global $post;
global $page_reports;
global $show_phone_on_admin;
global $hide_numbers;

$id_rifa = null;
if (isset($_POST["rifa"])) {
    $id_rifa = (int)$_POST['rifa'];
}
$rifa = $rifas_data->get_rifa($id_rifa);

if (post_password_required($post)) {
    echo get_the_password_form();
    exit();
} else if (!$rifa) {
    require $sections_folder . "/Admin/sc_admin_select_rifa.php";
    exit();
}

//LOGICA DE PAGINA
$errorMessage = null;
$id_opcion = null;

//VARIABLES PARA IMPRIMIR EL BOLETO AL OCUPAR
$nombre_estado = null;
$num_estado = null;
$boletos = null;
$fecha_apartado = null;
$nombre_completo = null;
$estado_republica = null;
$img_status_url = $rifa->get_status_img_url();

if (isset($_POST["id-opcion"])) {
    $id_opcion = $_POST["id-opcion"];

    try {
        if ($id_opcion == "1" && isset($_POST['folio'])) {
            $folio = $_POST['folio'];

            $estado = $wpdb->get_row('call sp_obtener_estado_boleto_por_folio(' . $id_rifa . ',' . $folio . ')');
            if ($estado->estado != 2) {

                /*ARREGLAR FOLIOS, EN CASO DE RIFA TIPO 3*/
                if ($rifa->get_tipo_rifa() == 3) {
                    fix_folio_rifa_tipo_3($id_rifa, $folio, $rifa->get_comienzo_numeros_reservados());
                }

                $results = $wpdb->get_results('call sp_obtener_boletos_por_folio(' . $id_rifa . ',' . $folio . ')');
                $info = $wpdb->get_row('select * from tbl_folio where num_folio = ' . $folio . ' and id_rifa = ' . $id_rifa);

                $query_tickes_paid = $wpdb->get_results('call sp_cambiar_ocupado_por_folio( ' . $id_rifa . ', ' . $folio . ')');
                $existe_folio = $wpdb->get_row('call sp_is_folio_usado(' . $id_rifa . ', ' . $folio . ')');

                if ($existe_folio->valor != 0) {
                    $query = $wpdb->prepare("select * from vw_reporte_publico where id_rifa = %d and num_folio = %d limit 1;", [$id_rifa, $folio]);
                    $result = $wpdb->get_row($query);

                    $nombre_estado = $result->nombre_estado;
                    $num_estado = $result->id_estado;
                    $boletos = $result->boletos;
                    $fecha_apartado = $result->fecha_apartado;
                    $nombre_completo = $result->nombres . " " . $result->apellidos;
                    $estado_republica = $result->estado;
                }
            }
        } else if ($id_opcion == "2" && isset($_POST['folio'])) {
            $folio = $_POST['folio'];

            $existe_folio = $wpdb->get_row('call sp_is_folio_usado(' . $id_rifa . ',' . $folio . ')');
            if ($existe_folio->valor != 0) {
                $estado = $wpdb->get_row('call sp_obtener_estado_boleto_por_folio(' . $id_rifa . ',' . $folio . ')');
                if ($estado->estado != 2) {
                    $results = $wpdb->get_results('call sp_cambiar_a_disponible_por_folio(' . $id_rifa . ',' . $folio . ')');
                }
            }
        } else if ($id_opcion == "3" && isset($_POST['folio'])) {
            $folio = $_POST['folio'];

            $existe_folio = $wpdb->get_row('call sp_is_folio_usado(' . $id_rifa . ',' . $folio . ')');

            if ($existe_folio->valor != 0) {
                $estado = $wpdb->get_row('call sp_obtener_estado_boleto_por_folio(' . $id_rifa . ',' . $folio . ')');
                if ($estado->estado != 1) {
                    $results = $wpdb->get_results('call sp_cambiar_ocupado_a_apartado(' . $id_rifa . ',' . $folio . ')');
                    $existe_folio = $wpdb->get_row('call sp_is_folio_usado(' . $id_rifa . ',' . $folio . ')');
                }
            }
        } else if ($id_opcion == "4" && isset($_POST['folio'])) {
            $folio = $_POST['folio'];

            $existe_folio = $wpdb->get_row('call sp_is_folio_usado(' . $id_rifa . ',' . $folio . ')');
            if ($existe_folio->valor != 0) {
                $estado = $wpdb->get_row('call sp_obtener_estado_boleto_por_folio(' . $id_rifa . ',' . $folio . ')');
                if ($estado->estado != 1) {
                    $results = $wpdb->get_results('call sp_cambiar_a_disponible_por_folio(' . $id_rifa . ',' . $folio . ')');
                }
            }
        } else if ($id_opcion == "5" && isset($_POST['boleto'])) {
            $boleto = $_POST['boleto'];

            $results = $wpdb->get_row('call sp_info_por_boleto(' . $id_rifa . ',' . $boleto . ')');
            if ($results) {
                $fecha_apartado = $wpdb->get_row("select fecha_apartado from tbl_boleto where id_rifa = " . $id_rifa . " and num_boleto = " . $boleto)->fecha_apartado;
                $estado = $wpdb->get_row('call sp_obtener_estado_boleto_por_folio(' . $id_rifa . ',' . $results->num_folio . ')');
            }
        } else if ($id_opcion == "6") {
            $nueva_fecha = $_POST['nueva-fecha'];
            try {
                $query = $wpdb->prepare("update tbl_rifa set fecha_limite_pago = %s where id_rifa = %d", [$nueva_fecha, $id_rifa]);
                $result = $wpdb->query($query);
            } catch (\Throwable $e) {
                $error = true;
                echo $e;
            }
        } else if ($id_opcion == "7") {
            $no_paid_min_folio = $_POST['no-pagado-min-folio'];
            $no_paid_max_folio = $_POST['no-pagado-max-folio'];
            $rows_found = false;
            try {
                $query = $wpdb->prepare('select count(*) as cantidad_resultados from vw_info_folio where id_rifa = %d and num_folio between %d and %d and id_estado_boleto = 1 limit 1', [$id_rifa, $no_paid_min_folio, $no_paid_max_folio]);
                $results = $wpdb->get_row($query);
                if ($results->cantidad_resultados > 0) {
                    $rows_found = true;
                }

                if ($rows_found) {
                    $query = $wpdb->prepare('select nombres, apellidos, num_folio, celular from vw_info_folio where id_rifa = %d and num_folio between %d and %d and id_estado_boleto = 1', [$id_rifa, $no_paid_min_folio, $no_paid_max_folio]);
                    $results = $wpdb->get_results($query);
                }
            } catch (\Throwable $e) {
                $error = true;
                echo $e;
            }
        } else if ($id_opcion == "8") {
            $new_whatsapp_num = $_POST['whatsapp-new-num'];
            $query = $wpdb->prepare("insert into tbl_num_whatsapp (num_whatsapp, id_rifa) values (%s, %d)", [$new_whatsapp_num, $id_rifa]);
            $wpdb->query($query);
        } else if ($id_opcion == "9") {
            $number_to_delete = $_POST['num-whatsapp'];
            $query = $wpdb->prepare("delete from tbl_num_whatsapp where id_rifa = %d and id_num_whatsapp = %d", [$id_rifa, $number_to_delete]);
            $wpdb->query($query);
        }
    } catch (\Exception $e) {
        $errorMessage = $e->getMessage();;
    }
}
$query = $wpdb->prepare("select * from tbl_rifa where id_rifa= %d limit 1 ", [$id_rifa]);
$resultsRifa = $wpdb->get_row($query);
?>

<input value="<?php echo $num_estado ?>" type="text" hidden id="tk-num-estado">
<input value="<?php echo $nombre_estado ?>" type="text" hidden id="tk-estado-nombre">
<input value="<?php echo $folio ?>" type="text" hidden id="tk-folio">
<input value="<?php echo $nombre_completo ?>" type="text" hidden id="tk-nombre-completo">
<input value="<?php echo $boletos ?>" type="text" hidden id="tk-boletos">
<input value="<?php echo $estado_republica ?>" type="text" hidden id="tk-estado-republica">
<input value="<?php echo $fecha_apartado ?>" type="text" hidden id="tk-fecha-apartado">
<input value="<?php echo $img_status_url ?>" type="text" hidden id="tk-img-base">
<input value="<?php echo $resultsRifa->cant_boletos ?>" type="text" hidden id="max-ticket-number">

<div class="container my-5">

    <div class="h1 page-title text-center">
        <p class="mb-2"> <b>ADMINISTRADOR</b> </p>
        <p class="text-white bg-danger rounded d-inline-block p-1 px-3"> <b> SORTEO - <?php echo $rifa->get_numero_rifa() ?> </b></p>
    </div>
    <hr class="my-3">

    <?php require $sections_folder . "/Admin/sc_admin_ocupar.php"; ?>

    <?php require $sections_folder . "/Admin/sc_admin_tabla_boletos.php"; ?>

    <?php require $sections_folder . "/Admin/sc_admin_reportes.php"; ?>


    <p class="text-center h3">Sección de servicios</p>

    <?php require $sections_folder . "/Admin/sc_admin_apart_dispo.php"; ?>

    <?php require $sections_folder . "/Admin/sc_admin_pagados_apart.php"; ?>

    <?php require $sections_folder . "/Admin/sc_admin_pagados_dispo.php"; ?>

    <hr class="mt-5 mb-2">

    <?php require $sections_folder . "/Admin/sc_admin_buscador.php"; ?>

    <hr class="my-3">
    <p class="text-center h3 mb-4">OTROS</p>

    <?php require $sections_folder . "/Admin/sc_admin_dev_fecha_limite.php"; ?>

    <hr class="my-3">
    <p class="text-center h3 mb-4">NO PAGADOS</p>

    <?php require $sections_folder . "/Admin/sc_admin_no_pagados.php"; ?>

    <hr class="my-3">
    <p class="text-center h3 mb-4">CAMBIAR WHATSAPPS DE PAGO</p>

    <?php require $sections_folder . "/Admin/sc_admin_whatsapps.php"; ?>

</div>

<?php $wpdb->close(); ?>

<script>
    var folio_1 = document.getElementById('folio-confirm-1');
    var folio_2 = document.getElementById('folio-confirm-2');
    var btnSubmit = document.getElementById('btnsubmit');

    function validatePassword() {
        if (folio_1.value != folio_2.value) {
            folio_2.setCustomValidity("No coinciden");
        } else {
            folio_2.setCustomValidity('');
        }
    }

    folio_1.onchange = validatePassword;
    folio_2.onkeyup = validatePassword;
</script>

<script src="<?php echo $folder_js . '/draw_ticket.js' ?>"></script>