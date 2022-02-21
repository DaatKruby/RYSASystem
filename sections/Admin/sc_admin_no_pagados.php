<style>
    .no-paid-tbl-cont {
        max-height: 300px;
        overflow: auto;
        display: block;
        width: 100%;
    }

    .no-paid-input {
        width: 100px;
    }

    .no-paid-tbl-row {
        cursor: pointer;
    }

    .no-paid-tbl-row:hover {
        background-color: #e3e3e3;
    }

    .no-paid-tbl-row:active {
        background-color: #a8a8a8;
    }
</style>

<div class="card mt-3">
    <div class="card-body bg-light rounded">
        <form action="" method="post">
            <input hidden type="text" name="rifa" value="<?php echo $id_rifa ?>">
            <input hidden type="text" name="id-opcion" value="7">

            <div class="text-center">
                <p class=" h5 font-weight-bold">MANDAR RECORDATORIO A FOLIOS NO PAGADOS</p>
                <!--<p class="p-0 m-0">Doble click en la tabla para mandar un mensaje de whatsapp para recordar</p>-->
            </div>

            <div class="mt-3">
                <?php if ($id_opcion && $id_opcion == "7" && $rows_found) {?>
                    <textarea class="w-100" name="" id="txt-message-no-paid" cols="30" rows="2"></textarea>
                <?php } ?>
                <div class="d-flex justify-content-center align-items-center flex-wrap">
                    <p class="mr-2 my-0">Folios entre </p>
                    <input class="m-0 no-paid-input" type="number" value="10000" name="no-pagado-min-folio" required>
                    <p class="mx-2 my-0"> y </p>
                    <input class="m-0 mr-4 no-paid-input" type="number" value="10200" name="no-pagado-max-folio" required>
                    <input type="submit" value="Buscar">
                </div>
            </div>
        </form>
    </div>
</div>

<?php
if ($id_opcion && $id_opcion == "7") {
    if ($errorMessage) {
?>
        <div class="bg-danger rounded p-3 mt-2">
            <p class="text-center h5 text-light m-0">
                HUBO UN ERROR
            </p>
        </div>

    <?php
    } else if ($rows_found) {
    ?>
        <p class="text-right mt-3 mb-0 text-secondary"><em>Click en la fila para mandar msj</em></p>
        <div class="no-paid-tbl-cont">
            <table class="table my-0">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Folio</th>
                        <th scope="col">Celular</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Apellido</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $tbl_data) { ?>
                        <tr class="no-paid-tbl-row" onclick="onNoPaidRowClick(this)">
                            <td> <?php echo $tbl_data->num_folio ?></td>
                            <td> <?php echo $tbl_data->celular ?></td>
                            <td> <?php echo $tbl_data->nombres ?></td>
                            <td> <?php echo $tbl_data->apellidos ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php
    } else {
    ?>
        <div class="bg-secondary rounded p-3 mt-2">
            <p class="text-center h5 m-0 text-light">
                NO SE ENCONTRARON RESULTADOS
            </p>
        </div>
    <?php } ?>
    <hr class="my-3" />
<?php
}
?>

<script>
    const text_box_msj = document.getElementById("txt-message-no-paid");

    function onNoPaidRowClick(rowElement) {
        const childrenElements = rowElement.children;
        const folio = childrenElements[0].textContent;
        let celular = childrenElements[1].textContent;
        celular = "+521" + celular.replace(" ", "");
        const nombre = childrenElements[2].textContent;
        const apellido = childrenElements[3].textContent;

        const mensaje = text_box_msj.value;

        window.open("https://web.whatsapp.com/send?phone=" + celular + "&text=" + mensaje);
    }
</script>