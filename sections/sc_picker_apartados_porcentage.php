<?php

$results_porcentage_apartados = $wpdb->get_row("call sp_cant_boletos_por_estado(" . $id_rifa . ");");
$cant_boletos_dispo = (int)$results_porcentage_apartados->cant_disponible;
$cant_boletos_apartados = (int)$results_porcentage_apartados->cant_ocupado + (int)$results_porcentage_apartados->cant_apartado;
$total_boletos = $cant_boletos_apartados + $cant_boletos_dispo;

$porcentge_apartados = $cant_boletos_apartados * 100 / $total_boletos;

?>

<style>
    .pb-base {
        width: 100%;
        border-style: solid;
        border-width: 1px;
        border-color: #b5b5b5;
        height: 30px;
        margin: 15px 0;
    }

    .pb-progress {
        height: 100%;
        background-color: #61C43D;
    }
</style>

<?php if ($resultsRifa->mostrar_porcentage_apartados) { ?>

    <div class="container mt-4 text-center bg-light rounded p-4 border text-dark">
        <h2>% BOLETOS VENDIDOS</h2>
        <div class="row justify-content-center">
            <div class="col-12 col-md-8">
                <div class="pb-base">
                    <div class="pb-progress" style="width: <?php echo $porcentge_apartados ?>%"></div>
                </div>
            </div>
        </div>
        <p class="h4"><?php echo number_format($porcentge_apartados, 2) ?>%</p>
    </div>

<?php } ?>