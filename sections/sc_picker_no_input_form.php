<div class="col-md-6 mb-3">
    <div class="card rounded shadow-sm p-3 bg-black h-100">
        <div class="card-body h-100">
            <form onsubmit="verificarRandomForm(this, event)" class="h-100 d-flex justify-content-between flex-column" action="<?php echo $page_user_info_form . "/?rifa=" . $id_rifa; ?>" method="post">
                <div>
                    <input hidden class="cant-randoms" value="<?php echo $cantidad; ?>" />
                    <div class="form-group">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="h4">Dejalo a la suerte</p>
                            </div>
                            <div>
                                <p class="h5"><?php echo $precio; ?>$ MXN</p>
                            </div>
                        </div>
                    </div>

                    <div class="rnd-input-area"></div>

                </div>

                <div class="my-3 text-center">
                    <hr>
                    <h5 class="text-secondary">¡Seleccione <?php echo $cantidad; ?> boletos aleatorios!</h5>
                    <small class="text-secondary">O puedes seleccionar <?php echo $cantidad; ?> desde la cuadricula.</small>
                    <hr>
                </div>

                <div>
                    <input type="submit" class="btn btn-success mt-3 form-control input-btn-rnd" value="Escoger">
                </div>
            </form>
        </div>
    </div>
</div>