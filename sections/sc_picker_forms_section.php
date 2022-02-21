<div class="container mt-4">
    <div class="row d-flex justify-content-center flex-wrap">

        <?php
        foreach ($form_configs as $key => $conjunto) {
            $cantidad = $conjunto->cantidad;
            $precio = $conjunto->price;
            $tipo_form = $conjunto->tipo;

            if ($tipo_form == 1) {
                require $sections_folder . "/sc_picker_regular_form.php";
            } else if ($tipo_form == 2) {
                require $sections_folder . "/sc_picker_no_input_form.php";
            }
        }
        ?>
    </div>
</div>