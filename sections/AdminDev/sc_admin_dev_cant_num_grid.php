<?php
$result = $wpdb->get_row("select cant_boletos_selec_cuadricula from tbl_rifa where id_rifa= " . $id_rifa . ";");
?>

<div class="card mt-3">
    <div class="card-body bg-info rounded">
        <form action="" method="post">
            <input hidden type="text" name="rifa" value="<?php echo $id_rifa ?>">
            <input hidden type="text" name="id-opcion" value="7">

            <div class="text-light text-center">
                <p class=" h5 font-weight-bold">CAMBIAR LA CANTIDAD DE BOLETOS QUE SE MUESTREN A LA VEZ EN LA CUADRICULA</p>
                <p for="number" class="p-0 m-0">Una cantidad muy grande puede hacer lenta la pagina o los celulares de los usuarios</p>
                <p for="number" class="p-0 m-0">Recomendamos poner como maximo 10,000</p>
                <p for="number" class="p-0">La cantidad actual son <b> <?php echo $result->cant_boletos_selec_cuadricula ?> </b> boletos</p>
            </div>
            <div class="d-flex justify-content-center mt-3">
                <input class="m-0 mr-2" type="number" placeholder="Nueva Cantidad" name="nueva-cantidad" min="0">
                <input type="submit" value="CAMBIAR">
            </div>
        </form>
    </div>
</div>

<?php
if ($id_opcion && $id_opcion == "7") {
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