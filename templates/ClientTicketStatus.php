<?php
/*
  *Template Name: Client Ticket Status
 */
get_header();
global $rifas_data;
global $folder_js;

$img_status_url = "";
$id_rifa = null;
if (isset($_GET["rifa"])) {
    $id_rifa = (int)$_GET['rifa'];
}
$rifa = $rifas_data->get_rifa($id_rifa);

global $page_client_ticket_status;
global $sections_folder;

$error = false;
$num_estado = null;
$folio = null;
$nombre_estado = null;
$nombre_completo = null;
$boletos = null;
$fecha_apartado = null;
$estado_republica = null;
$resultsRifa = null;

if ($id_rifa && $rifa) {
    $img_status_url = $rifa->get_status_img_url();
    if (isset($_POST["folio"]) && isset($_POST["numeroBoleto"])) {
        $folio = $_POST["folio"];
        $numeroBoleto = $_POST["numeroBoleto"];

        try {
            $query = $wpdb->prepare("select count(*) as cantidad from tbl_boleto where id_rifa = %d and num_folio = %d and num_boleto= %d;", [$id_rifa, $folio, $numeroBoleto]);
            $result = $wpdb->get_row($query);
            $cant_resultados = $result->cantidad;

            if ($cant_resultados > 0) {
                $query = $wpdb->prepare("select * from vw_reporte_publico where id_rifa = %d and num_folio = %d limit 1;", [$id_rifa, $folio]);
                $result = $wpdb->get_row($query);
                $nombre_estado = $result->nombre_estado;
                $num_estado = $result->id_estado;
                $boletos = $result->boletos;
                $fecha_apartado = $result->fecha_apartado;
                $nombre_completo = $result->nombres . " " . $result->apellidos;
                $estado_republica = $result->estado;

                $query = $wpdb->prepare("select * from tbl_rifa where id_rifa= %d limit 1 ", [$id_rifa]);
                $resultsRifa = $wpdb->get_row($query);
            }
        } catch (\Throwable $th) {
            $error = true;
        }
    }
    $query = $wpdb->prepare("select * from tbl_rifa where id_rifa= %d limit 1 ", [$id_rifa]);
    $resultsRifa = $wpdb->get_row($query);
    $wpdb->close();
}
?>

<style>
    .boleto {
        border-radius: 12px;
        padding: 4px 8px;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-right: 5px;
        flex-wrap: wrap;
        background-color: #DBDBDB;
        color: #3B3B3B;
        margin-bottom: 10px;
        font-size: 1rem;
        font-weight: bold;
        min-width: 40px;
    }
</style>

<input value="<?php echo $num_estado ?>" type="text" hidden id="tk-num-estado">
<input value="<?php echo $nombre_estado ?>" type="text" hidden id="tk-estado-nombre">
<input value="<?php echo $folio ?>" type="text" hidden id="tk-folio">
<input value="<?php echo $nombre_completo ?>" type="text" hidden id="tk-nombre-completo">
<input value="<?php echo $boletos ?>" type="text" hidden id="tk-boletos">
<input value="<?php echo $estado_republica ?>" type="text" hidden id="tk-estado-republica">
<input value="<?php echo $fecha_apartado ?>" type="text" hidden id="tk-fecha-apartado">
<input value="<?php echo $img_status_url ?>" type="text" hidden id="tk-img-base">


<div class="container">
    <div class="d-flex justify-content-center row mb-5">
        <div class="col-md-6 col-lg-6 mt-5">

            <?php

            if (!$id_rifa || !$rifa) {
                require $sections_folder . "/ClientTicketStatus/sc_status_select_rifa.php";
            } else if (!isset($_POST["folio"]) || !isset($_POST["numeroBoleto"])) {
                require $sections_folder . "/ClientTicketStatus/sc_status_input.php";
            } else {
                if ($error) {
                    $titulo = "OCURRIO UN ERROR";
                    $mensaje = "Vuelva intentarlo mas tarde o contacte al administrador";
                    echo "<script>parent.self.location='" . $page_notice . "?titulo=" . $titulo . "&mensaje=" . $mensaje . "';</script>";
                } else if ($nombre_estado) {
                    require $sections_folder . "/ClientTicketStatus/sc_status_obtenido.php";
                } else {
                    $titulo = "No existe un folio con ese boleto";
                    $mensaje = "Asegurece de que puso un folio correcto junto a uno de sus boletos o contactese con el administrados";
                    echo "<script>parent.self.location='" . $page_notice . "?titulo=" . $titulo . "&mensaje=" . $mensaje . "';</script>";
                }
            }

            ?>

        </div>
    </div>
</div>

<script src="<?php echo $folder_js . '/draw_ticket.js' ?>"></script>