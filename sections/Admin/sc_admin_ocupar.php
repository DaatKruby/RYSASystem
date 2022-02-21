<div class="text-center">
    <p class="h3">Confirmación de boletos vendidos</p>
    <p>En este campo ingresa el número de folio del comprador para confirmar el pago de sus boletos.</p>
</div>

<form action="" method="post">
    <input hidden type="text" name="rifa" value="<?php echo $id_rifa ?>">
    <input hidden type="text" name="id-opcion" value="1">

    <div class="d-flex justify-content-center align-content-center mt-4">
        <input required class="m-0 mr-2" type="tel" id="folio-confirm-1" placeholder="Folio" name="folio" onkeypress=" return /[0-9]/i.test(event.key);">
        <input required class="m-0 mr-2" type="tel" id="folio-confirm-2" placeholder="Confirmar Folio" name="folio" onkeypress="return /[0-9]/i.test(event.key);">
        <input type="submit" id="btnsubmit" name="submit" value="Pagar Folio">
    </div>

    <input type="hidden" name="folio_hidden" value="<?php $folio ?>">
</form>


<?php
if ($id_opcion && $id_opcion == "1") {
?>
    <hr class="my-3">
    <?php
    if ($estado->estado == 2) {
    ?>
        <div class="bg-danger rounded p-3">
            <p class="text-center h3 text-light">
                El folio <?php echo $folio ?> ya ha sido confirmado como pagado.
            </p>
        </div>

        <?php
    } else {
        if ($existe_folio->valor == 0) {
        ?>
            <div class="bg-danger p-3 rounded">
                <p class="text-center h3 text-light">El folio introducido no existe</p>
            </div>
        <?php
        } else {
        ?>
            <div class="bg-success text-center rounded text-white p-3">
                <p class="h3">Se ha confirmado la compra de los numeros del folio: <?php echo $folio; ?> <br>
                    Número pagados
                    <?php
                    foreach ($results as $result) {
                        echo " | " . str_pad($result->num_boleto, strlen($resultsRifa->cant_boletos), '0', STR_PAD_LEFT) . " | ";
                    }
                    ?>
                    <br>
                </p>
                <p>
                    <span>A nombre de:
                        <?php
                        $a_nombre_de = $info->nombres . " " . $info->apellidos;
                        if ($show_phone_on_admin) {
                            $a_nombre_de = $a_nombre_de . " - " . $info->celular;
                        }
                        echo $a_nombre_de;
                        ?>
                    </span>
                </p>
            </div>

            <hr class="my-3">

            <div class="row d-flex justify-content-center">
                <div class="col-sm-11 col-md-8 col-lg-5" id="ticket-cont"></div>
            </div>
<?php
        }
    }
}
?>

<hr class="my-3">