<?php
$result = $wpdb->get_row("select intervalo_proceder_reinicio_auto from tbl_rifa where id_rifa= " . $id_rifa . ";");
?>

<div class="card mt-3">
    <div class="card-body bg-info rounded">
        <form action="" method="post">
            <input hidden type="text" name="rifa" value="<?php echo $id_rifa ?>">
            <input hidden type="text" name="id-opcion" value="5">

            <div class="text-light text-center">
                <p class=" h5 font-weight-bold">CAMBIAR TIEMPO ENTRE LOS INTERVALOS DE LIMPIEZA</p>
                <p for="number" class="p-0 m-0">Poco tiempo puede hacer la pagina lenta si existen muchos boletos</b></p>
                <p for="number" class="p-0">El tiempo actual es <b> <?php echo $result->intervalo_proceder_reinicio_auto ?> </b> Segundos</p>
            </div>
            <div class="d-flex justify-content-center mt-3">
                <input class="m-0 mr-2" type="number" placeholder="Nuevo Tiempo" name="nuevo-tiempo" min="1">
                <input type="submit" value="CAMBIAR">
            </div>
        </form>
    </div>
</div>

<?php
if ($id_opcion && $id_opcion == "5") {
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