<div class="container">
    <div class="d-flex justify-content-center row mb-5">
        <div class="col-md-6 col-lg-6 mt-5">
            <div class="card w-100 p-3">
                <div class="text-center my-2">
                    <h2>Seleccione una Rifa</h2>
                </div>
                <hr>
                <div>
                    <?php
                    $a_rifas = $rifas_data->rifas;
                    foreach ($a_rifas as $key => $a_rifa) {
                        $a_id_rifa = $a_rifa->get_id_rifa();
                        $a_num_rifa = $a_rifa->get_numero_rifa();
                        $a_is_disponible = $a_rifa->get_is_disponible();
                    ?>
                        <form class="w-100 d-flex flex-column mb-3" action="<?php echo $page_admin_dev ?>" method="post">
                            <input hidden type="text" name="rifa" value="<?php echo $a_id_rifa ?>">
                            <div><p class="small text-secondary mb-0 mt-0">DB ID: <?php echo $a_id_rifa ?></p></div>
                            <input class="btn w-100" type="submit" value="<?php echo $a_num_rifa ?>">
                        </form>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>