<div class="card my-3 mb-0 p-4 bg-light">
    <p class="text-center h3">Buscador</p>
    <form class="mt-3" action="" method="post">
        <input hidden type="text" name="rifa" value="<?php echo $id_rifa ?>">
        <input hidden type="text" name="id-opcion" value="5">

        <div class="form-group d-flex justify-content-center">
            <label for="number" class="p-2">Introduce el número</label>
            <input type="text" placeholder="Número a buscar" name="boleto" class="mb-3 ml-3">
        </div>
        <div class="d-flex justify-content-center mt-0">
            <button type="submit" name="sbt-searcher" class="p-3 px-5 mb-3">Buscar</button>
        </div>
    </form>
</div>

<?php
if ($id_opcion && $id_opcion == "5") {
    if (!$results ||  $results->nombres == $hide_numbers && $results->apellidos == $hide_numbers) {
?>

        <div class="bg-danger rounded p-3 mt-2">
            <p class="text-center h5 text-light m-0">
                El numero no esta apartado o pagado
            </p>
        </div>

    <?php
    } else {
    ?>

        <?php
        if ($estado->estado == 2) {
        ?>
            <div class="bg-success rounded p-3">
                <p class="h3 text-center font-weight-bold text-white text-uppercase"> <span class="h1"> ¡FELICIDADES!, <?php echo $results->nombres ?> </span> <br> ¡ERES NUESTRO(A) NUEVO(A) GANADOR(A)!</p>
            </div>
        <?php
        } else {
        ?>
            <div class="bg-secondary rounded p-3 my-2">
                <p class="text-center h5 text-light m-0">
                    El folio no esta pagado
                </p>
            </div>
        <?php
        }
        ?>

        <div class="mt-3">
            <div class="d-flex justify-content-center">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Folio</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Apellidos</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Estado Boleto</th>
                            <th scope="col">Números</th>
                            <th scope="col">Fecha</th>
                            <?php if ($show_phone_on_admin) { ?>
                                <th scope="col">Celular</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $results->num_folio ?></td>
                            <td><?php echo $results->nombres ?></td>
                            <td><?php echo $results->apellidos ?></td>
                            <td><?php echo $results->estado ?></td>
                            <td><?php echo $results->nombre_estado ?></td>
                            <td><?php echo str_replace(",", ", ", $results->boletos) ?></td>
                            <td><?php echo $fecha_apartado ?></td>
                            <?php if ($show_phone_on_admin) { ?>
                                <td><?php echo $results->celular ?></td>
                            <?php } ?>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

<?php
    }
}
?>