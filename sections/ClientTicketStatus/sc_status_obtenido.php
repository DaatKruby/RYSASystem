<input value="<?php echo $resultsRifa->cant_boletos?>" type="text" hidden id="max-ticket-number">

<div class="card w-100 p-3">
    <div class="text-center my-2">
        <h2>Estado del folio</h2>
        <h4 class="text-white bg-danger py-2 px-3 rounded d-inline-block"><?php echo $folio ?></h4>
    </div>
    <hr>

    <?php if ($num_estado != 2) { ?>
        <div class="text-center mb-2">
            <h5 class="m-1 p-0 font-weight-bold"><u>ESTADO DEL FOLIO:</u></h5>
            <h5 class="m-0 p-0 mb-4">'<?php echo $nombre_estado ?>'</h5>

            <?php if ($num_estado == 1) { ?>
                <h5 class="m-1 p-0 mt-3 font-weight-bold"><u>A NOMBRE DE:</u></h5>
                <h5 class="m-0 p-0 mb-2">'<?php echo $nombre_completo ?>'</h5>
            <?php } ?>

        </div>
    <?php } else { ?>
        <div class="text-center" id="ticket-cont"></div>
    <?php } ?>

    <div class="text-center">
        <p class="h5 mt-3 mb-3 p-0 font-weight-bold"><u>SUS BOLETOS:</u></p>
        
        <div class="d-flex justify-content-center flex-wrap">
        <?php
        $boletos_arr = explode(",", $boletos);
        foreach ($boletos_arr as $key => $boleto) {
        ?>

        <div class="boleto">
            <?php echo str_pad($boleto, strlen($resultsRifa->cant_boletos), "0", STR_PAD_LEFT)?>
        </div>

        <?php
        }
        ?>

        </div>
    </div>

    <form class="d-flex flex-column" action="<?php echo $page_client_ticket_status . '?rifa=' . $id_rifa ?>" method="post">
        <input class="mt-3" type="submit" value="Poner otro folio" />
    </form>

</div>