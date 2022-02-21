<?php
$result = $wpdb->get_row("select fecha_limite_pago from tbl_rifa where id_rifa= " . $id_rifa . ";");
?>

<div class="card mt-3">
    <div class="card-body bg-info rounded">
        <form action="" method="post">
            <input hidden type="text" name="rifa" value="<?php echo $id_rifa ?>">
            <input hidden type="text" name="id-opcion" value="8">

            <div class="text-light text-center">
                <p class=" h5 font-weight-bold">CAMBIAR MENSAJE DE FECHA LIMITE DE PAGO</p>
                <p class="p-0 m-0">Si tiene la limpieza automatica desactivada puede poner una fecha en texto (12 de frebrero por ejemplo) en la cual usted manualmente limpiara los boletos no pagados</p>
                <p class="p-0">La fecha actual es '<b><?php echo $result->fecha_limite_pago ?>'</b></p>
            </div>
            <div class="d-flex justify-content-center mt-3">
                <input class="m-0 mr-2" type="text" placeholder="Nueva Cantidad" name="nueva-fecha" required>
                <input type="submit" value="CAMBIAR">
            </div>
        </form>
    </div>
</div>

<?php
if ($id_opcion && $id_opcion == "8") {
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