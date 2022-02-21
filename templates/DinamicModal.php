<?php
/*
    *Template Name: dinamic-modal-test
*/
get_header();

$cantidad = 3;
?>

<div class="col-md-6 mb-3">
    <div class="card rounded shadow-sm p-3 bg-black h-100">
        <div class="card-body h-100">
            <form class="h-100 d-flex justify-content-between flex-column" action="<?php echo $page_user_info_form . '?rifa=' . $id_rifa; ?>" method="post">
                <div>
                    <input hidden class="verificado" value="true" />
                    <div class="form-group">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="h4">Escoge tu número</p>
                            </div>
                            <div>
                                <p class="h5">900$ MXN</p>
                            </div>
                        </div>
                        <div class="my-3 d-flex justify-content-between align-items-center">
                            <a class="text-secondary small" href="#availableNumberList">Ver numeros disponibles</a>
                            <button class="btn btn-primary btn-verificar-general" onclick="rellenarFormConRandoms(this)">
                                Rellenar al azar
                            </button>
                        </div>
                    </div>

                    <?php
                    for ($i = 0; $i < $cantidad; $i++) {
                    ?>

                        <div class="cont-msj-ocupado"><small class="text-danger"><em class="p-0 m-0"></em></small></div>
                        <input type="text" onkeydown="inputOnChange(this)" name="number[]" class="form-control mb-2 ticket-input-number" placeholder="Escribe tu número" onkeypress="return /[0-9]/i.test(event.key)" required>

                    <?php } ?>

                    <div class="d-flex justify-content-end">
                        <small class="text-secondary">Escoja entre el <?php echo str_pad(0, strlen($resultsRifa->cant_boletos), "0", STR_PAD_LEFT) ?> y <?php echo str_pad($rifa->get_comienzo_numeros_reservados() - 1, strlen($resultsRifa->cant_boletos), "0", STR_PAD_LEFT) ?></small>
                    </div>
                </div>
                <div>
                    <input type="submit" onclick="verificarForm(this)" class="btn btn-success mt-3 form-control btn-verificar-general" value="Checar Disponiblidad">
                    <div class="container-resultado" id="div-result-one"></div>
                    <input type="hidden" name="formOption" value="optionOne">
                </div>
            </form>
        </div>
    </div>
</div>