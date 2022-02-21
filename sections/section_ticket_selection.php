<div class="container">
    <div class="row d-flex justify-content-center flex-wrap">

        <?php

        foreach ($conjuntos_boleto as $key => $conjunto) {
            $cant_boletos = $conjunto->cantidad_boletos;
            $precio = $conjunto->costo_conjunto;
        ?>

            <div class="col-md-6 mb-3">
                <div class="card rounded shadow-sm p-3 bg-black h-100">
                    <div class="card-body h-100">
                        <form class="h-100 d-flex justify-content-between flex-column" action="<?php echo $page_user_info_form . "/?id_rifa=" . $id_rifa; ?>" method="post" name="oneTicketForm">
                            <div>
                                <input hidden class="verificado" value="true" />
                                <div class="form-group">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <p class="h4">Escoge tu número</p>
                                        </div>
                                        <div>
                                            <p class="h5"><?php echo $precio ?>$ MXN</p>
                                        </div>
                                    </div>
                                    <div class="my-3 d-flex justify-content-between align-items-center">
                                        <a class="text-secondary small" href="#availableNumberList">Ver numeros disponibles</a>
                                        <button class="btn btn-verificar-general btn-color" onclick="fillRND(this)">
                                            Rellenar con Randoms
                                        </button>
                                    </div>
                                </div>

                                <?php
                                for ($i = 0; $i < $cant_boletos; $i++) {
                                ?>

                                    <div class="cont-msj-ocupado"><small class="text-danger"><em class="p-0 m-0"></em></small></div>
                                    <input type="text" onkeydown="inputOnChange(this)" name="number[]" class="form-control mb-2" placeholder="Escribe tu número" onkeypress="return /[0-9]/i.test(event.key)" maxlength="4" minlength="4" required>

                                <?php
                                }
                                ?>

                                <div class="d-flex justify-content-end">
                                    <small class="text-secondary">Escoja entre el 0000 y 9999</small>
                                </div>
                            </div>
                            <div>
                                <input type="submit" onclick="verificarBtnClick(this)" class="btn btn-color mt-3 form-control btn-verificar-general" value="Checar Disponiblidad">
                                <div class="container-resultado" id="div-result-one"></div>
                                <input type="hidden" name="formOption" value="optionOne">
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        <?php
        }
        ?>

    </div>
</div>