<div>
    <div class="text-center">
        <h2 class="mb-4 text-white bg-danger rounded d-inline-block p-2 px-3">
            LIBERAR BOLETOS APARTADOS (NO PAGADOS)
        </h2>
    </div>


    <div class="form-check mt-2 mb-4 d-flex justify-content-center">
        <div>
            <input type="checkbox" class="form-check-input" id="cbLibTickets" />
            <label class="form-check-label h5 font-weight-bold text-danger" for="cbLibTickets">DESEO LIBERAR TODOS LOS BOLETOS APARTADOS (HAGA CLIC EN EL RECUADRO)</label>
        </div>
    </div>
    <div>
        <form class="d-flex justify-content-center" action="<?php echo $page_admin_dev ?>" method="post" name="oneTicketForm">
            <input hidden type="text" name="rifa" value="<?php echo $id_rifa ?>">
            <input type="text" name="id-opcion" hidden value="2">
            <input type="submit" class="btn btn-success" id="btnSubmit" value="LIBERAR APARTADOS NO PAGADOS" onclick="return confirm('Esto liberará todos los boletos apartados sin pagar. ¿Seguro que deseas continuar?');" disabled>
        </form>
    </div>

    <?php
    if ($id_opcion && $id_opcion == "2") {
        if (!$error) {
    ?>
            <div class="bg-success rounded p-3 mt-2">
                <p class="text-center h5 text-light m-0">
                    Operación Realizada - Boletos Apartados liberados
                </p>
            </div>
        <?php
        } else {
        ?>

            <div class="bg-danger rounded p-3 mt-2">
                <p class="text-center h5 text-light m-0">
                    ERROR
                </p>
            </div>

    <?php
        }
    }
    ?>
</div>