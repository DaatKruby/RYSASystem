<?php
/*
  *Template Name: Admin-Dev
 */
get_header();
global $page_admin_dev;
global $sections_folder;

$id_rifa = null;
if (isset($_POST["rifa"])) {
    $id_rifa = (int)$_POST['rifa'];
}
$rifa = $rifas_data->get_rifa($id_rifa);

if (post_password_required($post)) {
    echo get_the_password_form();
    exit();
} else if (!$rifa) {
    require $sections_folder . "/AdminDev/sc_admin_dev_select_rifa.php";
    exit();
}
?>

<?php
$id_opcion = null;
$error = false;
if (isset($_POST['id-opcion'])) {
    $id_opcion = $_POST["id-opcion"];

    if ($id_opcion == "1") {
        $cant_boletos = $_POST['cant-boletos'];

        $nombres = $_POST['nombres'];
        $apellidos = $_POST['apellidos'];
        $celular = $_POST['celular'];
        $estado = $_POST['estado'];
        $correo = $_POST['correo'];

        $boletos_obtenidos = [];
        $folio = agregar_folio_con_boletos($rifa, $id_rifa, $cant_boletos, $nombres, $apellidos, $celular, $estado, $correo);
    } else if ($id_opcion == "2") {
        try {
            $query = "call sp_eliminar_todos_apartados (" . $id_rifa . ")";
            $verify_tickets = $wpdb->query($query);
        } catch (\Throwable $e) {
            $error = true;
            echo $e;
        }
    } else if ($id_opcion == "3") {
        $nuevo_estado = $_POST['nuevo-estado'];
        try {
            $query = $wpdb->prepare("update tbl_rifa set reinicio_auto_estado_activo = %d where id_rifa = %d", [$nuevo_estado, $id_rifa]);
            $result = $wpdb->query($query);
        } catch (\Throwable $e) {
            $error = true;
            echo $e;
        }
    } else if ($id_opcion == "4") {
        $nuevo_tiempo = $_POST['nuevo-tiempo'];
        try {
            $query = $wpdb->prepare("update tbl_rifa set segundos_reinicio_estado = %d where id_rifa = %d", [$nuevo_tiempo, $id_rifa]);
            $result = $wpdb->query($query);
        } catch (\Throwable $e) {
            $error = true;
            echo $e;
        }
    } else if ($id_opcion == "5") {
        $nuevo_tiempo = $_POST['nuevo-tiempo'];
        try {
            $query = $wpdb->prepare("update tbl_rifa set intervalo_proceder_reinicio_auto = %d where id_rifa = %d", [$nuevo_tiempo, $id_rifa]);
            $result = $wpdb->query($query);
        } catch (\Throwable $e) {
            $error = true;
            echo $e;
        }
    } else if ($id_opcion == "6") {
        $nuevo_estado = $_POST['nuevo-estado'];
        try {
            $query = $wpdb->prepare("update tbl_rifa set cuadricula_seleccion_activa = %d where id_rifa = %d", [$nuevo_estado, $id_rifa]);
            $result = $wpdb->query($query);
        } catch (\Throwable $e) {
            $error = true;
            echo $e;
        }
    } else if ($id_opcion == "7") {
        $nueva_cantidad = $_POST['nueva-cantidad'];
        try {
            $query = $wpdb->prepare("update tbl_rifa set cant_boletos_selec_cuadricula = %d where id_rifa = %d", [$nueva_cantidad, $id_rifa]);
            $result = $wpdb->query($query);
        } catch (\Throwable $e) {
            $error = true;
            echo $e;
        }
    } else if ($id_opcion == "8") {
        $nuevo_estado = $_POST['nuevo-estado'];
        try {
            $query = $wpdb->prepare("update tbl_rifa set cuadricula_mostrar_ocupados = %d where id_rifa = %d", [$nuevo_estado, $id_rifa]);
            $result = $wpdb->query($query);
        } catch (\Throwable $e) {
            $error = true;
            echo $e;
        }
    } else if ($id_opcion == "9") {
        $nuevo_estado = $_POST['nuevo-estado'];
        try {
            $query = $wpdb->prepare("update tbl_rifa set mostrar_porcentage_apartados = %d where id_rifa = %d", [$nuevo_estado, $id_rifa]);
            $result = $wpdb->query($query);
        } catch (\Throwable $e) {
            $error = true;
            echo $e;
        }
    }
}
?>

<style>
    .noselect {
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
</style>

<div class="container my-5 noselect">

    <div class="h1 page-title text-center">
        <p class="mb-2"> <b>ADMINISTRADOR DEV</b> </p>
        <p class="text-white bg-danger rounded d-inline-block p-1 px-3"> <b> SORTEO - <?php echo $rifa->get_numero_rifa() ?> </b></p>
    </div>
    <hr class="my-3">

    <?php require $sections_folder . "/AdminDev/sc_admin_dev_crear_folio.php"; ?>

    <hr class="mt-5 mb-5">

    <?php require $sections_folder . "/AdminDev/sc_admin_dev_resetear_apart.php"; ?>

    <hr class="mt-5 mb-5">
    <p class="text-center h3 mb-3">LIMPIADO AUTOMATICO</p>

    <?php
    $segundos_reinicio_estado = $wpdb->get_row("select segundos_reinicio_estado from tbl_rifa where id_rifa= " . $id_rifa . ";")->segundos_reinicio_estado;
    ?>

    <p class="p-0 mb-4 h5 text-center">El limpiado automatico si esta activo limpiara todos los boletos apartados pero no pagados que hayan estado en ese estado mas de <b><?php echo $segundos_reinicio_estado ?> segundos.</b></p>

    <?php require $sections_folder . "/AdminDev/sc_admin_dev_activar_auto_reseteo.php"; ?>

    <?php require $sections_folder . "/AdminDev/sc_admin_dev_tiempo_apartados.php"; ?>

    <?php require $sections_folder . "/AdminDev/sc_admin_dev_tiempo_intervalo_reseteado.php"; ?>

    <hr class="mt-5 mb-5">
    <p class="text-center h3 mb-4">CUADRICULA DE NUMEROS</p>

    <p class="p-0 mb-4 h5 text-center">Opciones para la cuadricula con los boletos disponibles que los usuarios ven en la pagina de seleccion de numeros.</p>

    <?php require $sections_folder . "/AdminDev/sc_admin_dev_activar_select_cuadricula.php"; ?>

    <?php require $sections_folder . "/AdminDev/sc_admin_dev_cant_num_grid.php"; ?>

    <?php require $sections_folder . "/AdminDev/sc_admin_dev_mostrar_ocupados_grid.php"; ?>

    <hr class="mt-5 mb-5">
    <p class="text-center h3 mb-4">OTROS</p>

    <?php require $sections_folder . "/AdminDev/sc_admin_dev_activar_mostrar_porcentage.php"; ?>

</div>

<?php $wpdb->close(); ?>


<script>
    document.getElementById('cbLibTickets').onchange = function() {
        document.getElementById('btnSubmit').disabled = !this.checked;
    }
</script>