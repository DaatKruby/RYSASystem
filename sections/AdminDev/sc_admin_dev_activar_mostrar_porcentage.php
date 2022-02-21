<?php
$result = $wpdb->get_row("select mostrar_porcentage_apartados from tbl_rifa where id_rifa= " . $id_rifa . ";");
$estado_str = null;
$nuevo_estado = null;
$btn_estado = null;
if ($result->mostrar_porcentage_apartados == 1) {
    $nuevo_estado = "0";
    $estado_str = "ACTIVO";
    $btn_estado = "DESACTIVAR";
} else {
    $nuevo_estado = "1";
    $estado_str = "DESACTIVADO";
    $btn_estado = "ACTIVAR";
}
?>

<div class="card mt-3">
    <div class="card-body bg-info rounded">
        <form action="" method="post">
            <input hidden type="text" name="rifa" value="<?php echo $id_rifa ?>">
            <input hidden type="text" name="id-opcion" value="9">
            <input hidden type="text" name="nuevo-estado" value="<?php echo $nuevo_estado ?>">

            <div class="text-light text-center">
                <p class=" h5 font-weight-bold">MOSTRAR | ESCONDER PORCENTAJE VENDIDOS </p>
                <p for="number" class="p-0">El porcentage de vendidos esta <b> <?php echo $estado_str ?> </b></p>
            </div>
            <div class="d-flex justify-content-center mt-3">
                <input type="submit" value="<?php echo $btn_estado ?>">
            </div>
        </form>
    </div>
</div>

<?php
if ($id_opcion && $id_opcion == "9") {
    if ($error) {
?>
        <div class="bg-danger rounded p-3 mt-2">
            <p class="text-center h5 text-light m-0">
                HUBO UN ERROR
            </p>
        </div>

    <?php
    } else {
    ?>
        <div class="bg-success rounded p-3 mt-2">
            <p class="text-center h5 text-light m-0">
                EXITO
            </p>
        </div>
<?php
    }
?>
    <hr class="my-3" />
<?php
}
?>