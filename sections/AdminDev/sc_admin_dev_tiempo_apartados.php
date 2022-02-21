<div class="card mt-3">
    <div class="card-body bg-info rounded">
        <form action="" method="post">
            <input hidden type="text" name="rifa" value="<?php echo $id_rifa ?>">
            <input hidden type="text" name="id-opcion" value="4">

            <div class="text-light text-center">
                <p class=" h5 font-weight-bold">CAMBIAR TIEMPO MAXIMO EN QUE UN FOLIO PUEDE ESTAR APARTADO</p>
                <p for="number" class="p-0 m-0">Ponga una cantidad <b class="text-danger">DE SEGUNDOS</b> razonable para dar tiempo a los compradores a pagar</p>
                <p for="number" class="p-0 m-0">El tiempo actual es <b> <?php echo $segundos_reinicio_estado ?> </b> Segundos</p>
                <p for="number" class="p-0 m-0">2hr = 7200 |  12hr = 43200 | 24hr = 86400 | 48hr = 172800 | 72hr = 259200</p>
            </div>
            <div class="d-flex justify-content-center mt-3">
                <input class="m-0 mr-2" type="number" placeholder="Nuevo Tiempo" name="nuevo-tiempo" min="1">
                <input type="submit" value="CAMBIAR">
            </div>
        </form>
    </div>
</div>

<?php
if ($id_opcion && $id_opcion == "4") {
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