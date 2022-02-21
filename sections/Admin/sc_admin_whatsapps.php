<?php
$cant_numbers = $wpdb->get_row("select count(*) as 'cant' from tbl_num_whatsapp where id_rifa = " . $id_rifa)->cant;
$query = $wpdb->prepare("select * from tbl_num_whatsapp where id_rifa= %d", [$id_rifa]);
$results_whatsapp = $wpdb->get_results($query);
?>

<style>
    .whatsapp-tbl-cont {
        max-height: 300px;
        overflow: auto;
        display: block;
        width: 100%;
    }

    .whatsapp-input {
        width: 200px;
        margin-right: 20px;
    }
</style>

<div>
    <p class="text-center">Se escogera de forma aleatorio uno de los whatsapps que agregue cada vez que alguien quiera apartar boletos</p>
    <p class="text-center mb-0">El celular tiene que comenzar con el simbolo "+" y lada internacional</p>
    <p class="text-center mt-0"><b>Ejemplo: +521XXXXXXXXXX</b></p>

    <form class="my-4" action="" method="post">
        <input hidden type="text" name="rifa" value="<?php echo $id_rifa ?>">
        <input hidden type="text" name="id-opcion" value="8">

        <div class="d-flex justify-content-center align-items-center flex-wrap">
            <p class="mr-3 my-0">Registrar Whatsapp: </p>
            <input class="m-0 mr-3 whatsapp-input" type="text" name="whatsapp-new-num" required>
            <input type="submit" value="Registarr">
        </div>
    </form>

    <?php if ($cant_numbers == 0) { ?>

        <div class="bg-secondary rounded p-3 my-2">
            <p class="text-center h5 text-light m-0">
                No hay whatsapps registrados en el sistema
            </p>
        </div>

    <?php } else { ?>

        <div class="whatsapp-tbl-cont mt-3">
            <table class="table my-0">
                <thead class="thead-dark">
                    <tr class="text-center">
                        <th scope="col">Whatsapp</th>
                        <th scope="col">Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results_whatsapp as $tbl_data) { ?>
                        <tr class="" onclick="onNoPaidRowClick(this)">
                            <td class="text-center"> <?php echo $tbl_data->num_whatsapp ?></td>
                            <td>
                                <form class="text-center" action="" method="post">
                                    <input hidden type="text" name="rifa" value="<?php echo $id_rifa ?>">
                                    <input hidden type="text" name="id-opcion" value="9">
                                    <input hidden type="text" name="num-whatsapp" value="<?php echo $tbl_data->id_num_whatsapp ?>">
                                    <button onclick="" class="btn-danger">ELIMINAR</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    <?php } ?>

</div>