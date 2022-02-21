<?php
/*
    *Template Name: PickANumber-template 
*/
get_header();

global $folder_js;
global $folder_css;
global $service_random_numbers;
global $service_are_tickets_available;
global $service_available_tickets_range;
global $sections_folder;
global $page_notice;
global $page_number_select;
global $img_folder;

global $rifas_data;
$id_rifa = null;
if (isset($_GET["rifa"])) {
    $id_rifa = (int)$_GET['rifa'];
}
$rifa = $rifas_data->get_rifa($id_rifa);

if (!$id_rifa || !$rifa) {
    require $sections_folder . "/sc_picker_select_rifa.php";
    exit();
} else if (!$rifa->get_is_disponible()) {
    $titulo = "Rifa Bloqueada";
    $mensaje = "La rifa a la que trata acceder esta en mantenimiento, ya ha acabado o no existe, si cree que es un error, contacte al administrador";
    echo "<script>parent.self.location='" . $page_notice . "?titulo=" . $titulo . "&mensaje=" . $mensaje . "';</script>";
    exit();
}
resetar_boletos_apartados_auto($id_rifa);
$query = $wpdb->prepare("select * from tbl_rifa where id_rifa= %d limit 1 ", [$id_rifa]);
$resultsRifa = $wpdb->get_row($query);

$form_configs = $rifa->get_form_configs();
$grid_cant_seleccion = $rifa->get_grid_cant_seleccion();
$num_rifa = $rifa->get_numero_rifa();

global $page_user_info_form;
global $limit_due;
$cant_tickets_allowed_str = $rifa->get_grid_cant_seleccion_str();
?>

<link rel="stylesheet" href="<?php echo $folder_css . '/number_select.css' ?>">

<input hidden id="page-user-info-form" value="<?php echo $page_user_info_form . "/?rifa=" . $id_rifa; ?>" />
<input hidden id="max-ticket-number" value="<?php echo $resultsRifa->cant_boletos ?>" />
<input hidden id="service-random-numbers" value="<?php echo $service_random_numbers . "/?rifa=" . $id_rifa; ?>" />
<input hidden id="service-check-number-status" value="<?php echo $service_are_tickets_available . "/?rifa=" . $id_rifa; ?>" />
<input hidden id="service-available-tickets" value="<?php echo $service_available_tickets_range . "/?rifa=" . $id_rifa; ?>" />
<input hidden id="cant-tickets-allowed" value="<?php echo $cant_tickets_allowed_str; ?>" />
<input hidden id="boletos-por-pagina" value="<?php echo $resultsRifa->cant_boletos_selec_cuadricula ?>" />
<input hidden id="mostrar-ocupados-cuadricula" value="<?php echo $resultsRifa->cuadricula_mostrar_ocupados ?>" />
<input hidden id="comienzo-numeros-randoms" value="<?php echo $rifa->get_comienzo_numeros_reservados() ?>" />

<!-- Para obtener la info del sorteo -->
<?php
$query_rifas_page = new WP_Query('pagename=slista');
while ($query_rifas_page->have_posts()) : $query_rifas_page->the_post();
    the_content();
endwhile;
wp_reset_postdata();
?>



<?php
global $wpdb;
$result = $wpdb->get_row("call sp_cant_boletos_por_estado_singular(" . $id_rifa . ", 0)");
if ($result->NumeroBoletos === '0') {
?>
    <div class="row d-flex bg-success justify-content-center p-3 my-5" id="list">
        <p class="text-center text-white">
            <b class="h4">BOLETOS AGOTADOS</b> <br>
            <small>Espera los números despreciados muy pronto.</small>
        </p>
    </div>
<?php
} else {
?>

    <div id="confirm-click-msj" class="confirm-click-grid hide">
        <div class="confirm-click-cont text-center">
            <img src="<?php echo $img_folder . '/paloma_verde.png' ?>" />
            <p class="m-0 p-0">NUMERO AGREGADO</p>
        </div>
    </div>

    <div id="confirm-click-msj-remove" class="confirm-click-grid hide">
        <div class="confirm-click-cont text-center">
            <img class="img-remove" src="<?php echo $img_folder . '/remove-icon.png' ?>" />
            <p class="m-0 p-0">NUMERO REMOVIDO</p>
        </div>
    </div>

    <div>
        <a href="#availableNumberList">
            <div id="back-to-top">
                <img class="back-top-icon" src="<?php echo $img_folder . '/back-to-top-icon-png.jpg' ?>" alt="">
                <span class="screen-reader-text">Back to top</span>
            </div>
        </a>
    </div>

    <div id="loading-animation" class="hide">
        <div>
            <img src="<?php echo $img_folder . '/loading-animation.gif' ?>" alt="" srcset="">
        </div>
    </div>


    <?php require $sections_folder . "/sc_picker_forms_section.php"; ?>

    <?php require $sections_folder . "/sc_picker_apartados_porcentage.php"; ?>

    <script src="<?php echo $folder_js . '/number_select.js' ?>"></script>

    <div class="container mb-1 mt-5" id="availableNumberList">
        <hr class="mb-4">

        <p class="text-center h4 font-weight-bold"> 🎫 BOLETOS DISPONIBLES 🎫</p>

        <?php if ($resultsRifa->cuadricula_seleccion_activa == 1) { ?>

            <div class="text-center">

                <p class="h5 mb-3">
                    Puede seleccionar boletos con la cuadricula. <br>
                    Seleccione los numeros de su agrado y de click en continuar
                </p>

                <?php
                $hours_to_pay = $resultsRifa->segundos_reinicio_estado / 60 / 60;
                $days_to_pay = $hours_to_pay / 24;
                ?>
                <p class="mb-3 text-danger">
                    <?php if ($resultsRifa->reinicio_auto_estado_activo) { ?>
                        Tiene <span class='font-weight-bold'><?php echo round($days_to_pay, 1) ?> dias </span>(<?php echo round($hours_to_pay, 1) ?> horas) para pagar sus boletos
                    <?php } else { ?>
                        Tiene hasta <span class='font-weight-bold'><?php echo $resultsRifa->fecha_limite_pago ?> </span> para pagar sus boletos
                    <?php } ?>
                </p>

                <p class="h6">Escoja entre las siguientes cantidades de numeros</p>

                <div class="d-flex justify-content-center flex-wrap">
                    <?php foreach ($grid_cant_seleccion as $key => $cantidad) { ?>
                        <div class="cant-disponibles">
                            <span class="h4 m-0 p-0"><?php echo $cantidad ?></span>
                        </div>
                    <?php } ?>
                </div>
                <p class="h6 mt-2">El precio dependera de la cantidad a escoger. (Ver tabla arriba)</p>
            </div>

            <div class="card bg-light p-3 text-center rounded mb-2 mt-3">
                <p class="h3 p-0 m-0">Boletos seleccionados</p>
                <p>Puede seleccionar un numero para quitarlo</p>
                <div id="boletos-user-cont" class="d-flex justify-content-center flex-wrap">

                </div>
            </div>
            <input type="button" id="btn-grid-continue" onclick="gridContinue()" class="btn btn-success mt-3 btn-verificar-general py-3 w-100" value="Continuar">

        <?php } ?>

        <hr class="my-4">

    </div>

    <div class="container mt-0 mb-4" id="list">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="text-dark font-italic">
                <p class="p-0 m-0 font-weight-bold">
                    Mostrando: <span id="grid-rng-min">*</span> - <span id="grid-rng-max">*</span>
                </p>
                <p>Total: <?php echo $rifa->get_comienzo_numeros_reservados() ?></p>
            </div>
            <div>
                <button class="grid-control-btn" onclick="gridLeft()"> &lt&lt </button>
                <button class="grid-control-btn" onclick="gridRight()"> &gt&gt </button>
            </div>
        </div>

        <input type="hidden" name="formOption" value="gridOption">

        <div id="grid-container" onclick="<?php echo $resultsRifa->cuadricula_seleccion_activa == 1 ? 'numberButtonPress(event)' : '' ?>" class="container-grid">

        </div>
    </div>

<?php
}
get_footer();
$wpdb->close();
?>

<script src="<?php echo $folder_js . '/number_select_grid.js' ?>"></script>