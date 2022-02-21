<?php
/*
  *Template Name: Form-template
 */
get_header();
global $page_end_purchase;
global $rifas_data;
$id_rifa = (int)$_GET['rifa'];
$boletos = $_POST['number'];
$rifa = $rifas_data->get_rifa($id_rifa);

if (!$id_rifa || !$rifa || !$rifa->get_is_disponible()) {
    $titulo = "Rifa Bloqueada";
    $mensaje = "La rifa a la que trata acceder no existe o ya ha acabado, si cree que es un error, contacte al administrador";
    echo "<script>parent.self.location='" . $page_notice . "?titulo=" . $titulo . "&mensaje=" . $mensaje . "';</script>";
    exit();
}

$query = $wpdb->prepare("select * from tbl_rifa where id_rifa= %d limit 1 ", [$id_rifa]);
$resultsRifa = $wpdb->get_row($query);

foreach ($boletos as $key => $boleto) {
    $boletos[$key] = (int)$boleto;
}

if (!$rifa->existen_suficientes_Boletos_de_regalo(count($boletos))) {
    $titulo = "NO EXISTEN SUFICIENTES BOLETOS DE REGALO";
    $mensaje = "No hay suficientes boletos de regalo disponibles";
    echo "<script>parent.self.location='" . $page_notice . "?titulo=" . $titulo . "&mensaje=" . $mensaje . "';</script>";
    exit();
}

$tickets_agregados = $rifa->obtener_boletos_de_regalo($boletos);
if (!isset($tickets_agregados)) {
    echo "<script>parent.self.location='" . $page_notice . "';</script>";
    exit();
}

$cant_boletos_random = count($tickets_agregados);

?>

<style>
    .boleto {
        border-radius: 12px;
        padding: 4px 8px;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-right: 5px;
        flex-wrap: wrap;
        background-color: #DBDBDB;
        color: #3B3B3B;
        margin-bottom: 10px;
        font-size: 1rem;
        font-weight: bold;
        min-width: 40px;
    }
</style>

<form onsubmit="return checkForm(this);" class="mb-4" action="<?php echo $page_end_purchase . '?rifa=' . $id_rifa ?>" method="post" enctype="multipart/form-data">
    <div class="container">
        <div class=" text-center mt-5 mb-2 ">
            <h5 class="mb-3">Por favor, completa tus datos</h5>

            <p class="font-weight-bold p-0 m-0 mb-2">Número(s):</p>
            <div class="d-flex justify-content-center flex-wrap">
                <?php foreach ($boletos as $boleto) { ?>
                    <div class="boleto">
                        <?php echo str_pad($boleto, strlen($resultsRifa->cant_boletos), "0", STR_PAD_LEFT) ?>
                    </div>
                <?php } ?>
            </div>

            <?php if ($cant_boletos_random > 0) { ?>
                <p class="font-weight-bold p-0 m-0 mb-2">¡ Número(s) de Regalo !</p>
                <div class="d-flex justify-content-center flex-wrap">
                    <?php foreach ($tickets_agregados as $boleto) { ?>
                        <div class="boleto">
                            <?php echo str_pad($boleto, strlen($resultsRifa->cant_boletos), "0", STR_PAD_LEFT) ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>

        </div>
        <div class="row ">
            <div class="col-lg-7 mx-auto">
                <div class="card mt-2 mx-auto p-4 bg-light">
                    <div class="card-body bg-light">
                        <div class="container">
                            <form role="form">
                                <div class="controls">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group"> <label for="form_name">Nombre(s)</label> <input type="text" name="nombre" class="form-control" placeholder="Nombre" required="required" data-error="Nombre es obligatorio."> </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group"> <label for="form_lastname">Apellidos</label> <input type="text" name="apellido" class="form-control" placeholder="Apellidos" required="required" data-error="Apellido es obligatorio."> </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group"> <label for="form_phone">Celular</label> <input type="tel" name="phone" class="form-control" placeholder="Celular" required="required" data-error="Celular es obligatorio." id="phone" onkeypress="return /[0-9]/i.test(event.key)" maxlength="10" minlength="10" required> </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group"> <label for="form_need">Estado</label> <select name="state" class="form-control" required="required" data-error="Verifique su estado">
                                                    <option value="Aguascalientes">Aguascalientes</option>
                                                    <option value="Baja California">Baja California</option>
                                                    <option value="Baja California Sur">Baja California Sur</option>
                                                    <option value="Campeche">Campeche</option>
                                                    <option value="Chiapas">Chiapas</option>
                                                    <option value="Chihuahua">Chihuahua</option>
                                                    <option value="Coahuila">Coahuila</option>
                                                    <option value="Colima">Colima</option>
                                                    <option value="Durango">Durango</option>
                                                    <option value="Estado de México">Estado de México</option>
                                                    <option value="Guanajuato">Guanajuato</option>
                                                    <option value="Guerrero">Guerrero</option>
                                                    <option value="Hidalgo">Hidalgo</option>
                                                    <option value="Jalisco">Jalisco</option>
                                                    <option value="Michoacán">Michoacán</option>
                                                    <option value="Morelos">Morelos</option>
                                                    <option value="Nayarit">Nayarit</option>
                                                    <option value="Nuevo León">Nuevo León</option>
                                                    <option value="Oaxaca">Oaxaca</option>
                                                    <option value="Puebla">Puebla</option>
                                                    <option value="Querétaro">Querétaro</option>
                                                    <option value="Quintana Roo">Quintana Roo</option>
                                                    <option value="San Luis Potosí">San Luis Potosí</option>
                                                    <option value="Sinaloa">Sinaloa</option>
                                                    <option value="Sonora" selected="selected">Sonora</option>
                                                    <option value="Tabasco">Tabasco</option>
                                                    <option value="Tamaulipas">Tamaulipas</option>
                                                    <option value="Tlaxcala">Tlaxcala</option>
                                                    <option value="Veracruz">Veracruz</option>
                                                    <option value="Yucatán">Yucatán</option>
                                                    <option value="Zacatecas">Zacatecas</option>
                                                    <option value="USA">Estados Unidos</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- valores ocultos, si el array.lenght ===1, mandó un solo numero, si no, mando la segunda opción. -->
                                    <?php
                                    foreach ($boletos as $boleto) {
                                        echo ' <input type="hidden" name="numbers[]" value="' . $boleto . '">';
                                    }

                                    foreach ($tickets_agregados as $boleto) {
                                        echo ' <input type="hidden" name="numbers[]" value="' . $boleto . '">';
                                    }

                                    ?>
                                    <!-- valores ocultos  -->
                                    <div class="d-flex justify-content-end">
                                        <input id="submit-btn" type="submit" class="btn btn-primary mt-3 py-3" value="Continuar">
                                    </div>

                            </form>

                        </div>

                    </div>
                </div>
            </div> <!-- /.8 -->
        </div> <!-- /.row-->
    </div>
</form>

<?php
$wpdb->close();
?>

<script>
    const submit_btn = document.getElementById("submit-btn");
    let bloqueado = false;

    function checkForm(form) {
        if (bloqueado) {
            return false;
        }
        submit_btn.disabled = true;
        bloqueado = true;
        return true;
    }
</script>