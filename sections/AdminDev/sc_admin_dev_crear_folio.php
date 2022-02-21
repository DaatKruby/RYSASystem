<div>
    <h2 class="text-center mb-4">CREAR FOLIO Y AGREGAR BOLETOS RANDOM</h2>
    <p class="p-0 mb-4 h5 text-center">Se agregara un folio junto a boletos aleatorios. El folio tendra el estado 'En proceso', cambielo a pagado en el admin.</p>

    <form class="" action="<?php echo $page_admin_dev ?>" method="post" name="oneTicketForm">
        <input type="text" name="id-opcion" hidden value="1">
        <input hidden type="text" name="rifa" value="<?php echo $id_rifa ?>">
        <input type="text" name="cant-boletos" class="form-control mb-5" placeholder="Cantidad de boletos" onkeypress="return /[0-9]/i.test(event.key)" maxlength="4" required>

        <input type="text" name="nombres" class="form-control mb-2" placeholder="Nombres" required>
        <input type="text" name="apellidos" class="form-control mb-2" placeholder="Apellidos" required>
        <input type="text" name="celular" class="form-control mb-2" placeholder="Celular" required>
        <input type="text" name="estado" class="form-control mb-2" placeholder="Estado" required>
        <input type="text" name="correo" class="form-control mb-2" placeholder="Correo" required>

        <input type="submit" class="btn btn-success btn-block" value="Agregar">

        <?php
        if ($id_opcion && $id_opcion == "1") {
            if ($folio) {
        ?>
                <div class="bg-success rounded p-3 mt-2">
                    <p class="text-center h5 text-light m-0">
                        SE AGREGO CORRECTAMENTE
                    </p>
                    <p class="text-center h5 text-light m-0">
                        FOLIO: <?php echo $folio; ?>
                    </p>
                    <p class="text-center h5 text-light m-0">
                        NOMBRE: <?php echo $nombres; ?> <?php echo $apellidos; ?>
                    </p>
                </div>

                <div>

                    <div class="bg-secondary rounded p-3 my-2">
                        <p class="text-center h4 text-light m-0 mb-2">BOLETOS AGREGADOS</p>
                        <p class="text-center h5 text-light m-0">

                            <?php
                            foreach ($boletos_obtenidos as $key => $boleto_obtenido) {
                                echo $boleto_obtenido . ", ";
                            }
                            ?>


                        </p>

                    </div>

                </div>
            <?php
            } else {
            ?>

                <div class="bg-danger text-white my-3 container-grid">
                    <h3>ERROR</h3>
                    <p>PUEDE QUE EL ERROR SE DEBA A QUE NO HAY SUFICIENTES BOLETOS DISPONIBLES</p>
                </div>

        <?php
            }
        }
        ?>
    </form>
</div>