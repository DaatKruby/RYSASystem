<?php
global $page_number_select;
global $page_notice;
global $show_rifa_select_menu;

if ($rifas_data->get_cant_rifas_activas() == 1) {
    $rifa=$rifas_data->get_first_rifa_activa();
    echo "<script>parent.self.location='" . $page_number_select . "?rifa=" . $rifa->get_id_rifa() . "';</script>";
    exit();
} else if (!$show_rifa_select_menu) {
    $rifa=$rifas_data->get_first_rifa_activa();
    if ($rifa) {
        echo "<script>parent.self.location='" . $page_number_select . "?rifa=" . $rifa->get_id_rifa() . "';</script>";
    } else {
        $titulo = "¡ Whoops, No se encontro una rifa, intentelo mas tarde";
        $mensaje = "No se encontrol la rifa que solicito";
        echo "<script>parent.self.location='" . $page_notice . "?titulo=" . $titulo . "&mensaje=" . $mensaje . "';</script>";
    }
    exit();
}
?>

<div class="container">
    <div class="d-flex justify-content-center row mb-5">
        <div class="col-md-6 col-lg-6 mt-5">
            <div class="card w-100 p-3">
                <div class="text-center my-2">
                    <h2>Seleccione una Rifa</h2>
                </div>
                <hr>
                <div class="d-flex flex-column">
                    <?php
                    $a_rifas = $rifas_data->rifas;
                    foreach ($a_rifas as $key => $a_rifa) {
                        $a_id_rifa = $a_rifa->get_id_rifa();
                        $a_num_rifa = $a_rifa->get_numero_rifa();
                        $a_is_disponible = $a_rifa->get_is_disponible();

                        if ($a_is_disponible) {
                    ?>
                            <a class="d-block w-100 mb-3" href="<?php echo $page_number_select . '?rifa=' . $a_id_rifa ?>">
                                <button class="w-100"><?php echo $a_num_rifa ?></button>
                            </a>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>