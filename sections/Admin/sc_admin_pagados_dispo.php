<div class="card mt-3">
    <div class="card-body bg-danger rounded">
        <form action="" method="post">
            <input hidden type="text" name="rifa" value="<?php echo $id_rifa ?>">
            <input hidden type="text" name="id-opcion" value="4">

            <div class="container-liberar-folio">
                <p class="text-center h5 text-light">Poner números <span class="font-weight-bold"> DE PAGADOS A DISPONIBLES </span> a la venta por folio <br> <small>De PAGADO a DISPONIBLE</small></p>
                <div class="d-flex justify-content-center mt-3">
                    <input class="m-0 mr-2" type="tel" placeholder="Liberar Folio PAGADO" name="folio" onkeypress=" return /[0-9]/i.test(event.key);" minlength="4">
                    <input type="submit" id="btnsubmlibPaid" name="btnsubmlibPaid" value="Liberar Folio">
                </div>
            </div>
        </form>
    </div>
</div>

<?php
if ($id_opcion && $id_opcion == "4") {

    if ($existe_folio->valor == 0) {
?>
        <div class="bg-danger rounded p-3 mt-2">
            <p class="text-center h5 text-light m-0">
                El folio introducido no existe
            </p>
        </div>
        <?php
    } else {
        if ($estado->estado == 1) {
        ?>
            <div class="bg-danger rounded p-3 mt-2">
                <p class="text-center h5 text-light m-0">
                    Dicho folio está apartado, por favor, coloquelo en la casilla correspondiente.
                </p>
            </div>
        <?php
        } else {
        ?>
            <div class="bg-success rounded p-3 mt-2">
                <p class="text-center h5 text-light m-0">
                    Folio liberado correactamente.
                </p>
            </div>
<?php
        }
    }
}
?>