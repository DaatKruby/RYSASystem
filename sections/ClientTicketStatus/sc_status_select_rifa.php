<?php
global $page_client_ticket_status;
if ($rifas_data->get_cant_rifas_activas() == 1) {
    $rifa=$rifas_data->get_first_rifa_activa();
    echo "<script>parent.self.location='" . $page_client_ticket_status . "?rifa=" . $rifa->get_id_rifa() . "';</script>";
    exit();
}
?>

<div class="card w-100 p-3">
    <div class="text-center my-2">
        <h2>Consulta el estado de tu folio</h2>
        <h6>Escoge la rifa donde selecciono su boleto</h6>
    </div>
    <hr>
    <div class="d-flex flex-column">
        <?php
        $a_rifas = $rifas_data->rifas;
        foreach ($a_rifas as $key => $a_rifa) {
            $a_id_rifa = $a_rifa->get_id_rifa();
            $a_num_rifa = $a_rifa->get_numero_rifa();
        ?>
            <a class="d-block w-100 mb-3" href="<?php echo $page_client_ticket_status . '?rifa=' . $a_id_rifa ?>">
                <button class="w-100"><?php echo $a_num_rifa ?></button>
            </a>
        <?php
        }
        ?>
    </div>
</div>