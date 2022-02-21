<?php

/**
 * Template Name: EndPurchase-template
 * 
 */
date_default_timezone_set('America/Los_Angeles');
setlocale(LC_TIME, "spanish");
get_header();
global $show_phone_on_admin;
global $url_img_logo_reports;

$id_rifa = (int)$_GET['rifa'];
$rifa = $rifas_data->get_rifa($id_rifa);

if (!$id_rifa || !$rifa || !isset($_POST['numbers'])) {
    $titulo = "OCURRIO UN ERROR";
    $mensaje = "Vuelva intentarlo mas tarde o contacte al administrador";
    echo "<script>parent.self.location='" . $page_notice . "?titulo=" . $titulo . "&mensaje=" . $mensaje . "';</script>";
    exit();
}
$num_rifa = $rifa->get_numero_rifa();

$boletos = $_POST['numbers'];
$name = $_POST['nombre'];
$lastName = $_POST['apellido'];
$phone = $_POST['phone'];
$state = $_POST['state'];
$info_folio = array(
    $name,
    $lastName,
    $phone,
    $state,
    ""
);

$folio = $rifa->apartar_boletos($boletos, $info_folio);
if (!$folio) {
    $titulo = "¡ Whoops, uno de sus boletos fue apartado !";
    $mensaje = "Parece que uno de sus boletos fue apartado por alguien mas. Si sigue viendo este mensaje es posible que ya no queden boletos de regalo disponibles";
    echo "<script>parent.self.location='" . $page_notice . "?titulo=" . $titulo . "&mensaje=" . $mensaje . "';</script>";
    exit();
}

$boletos_str = "";
$fecha = strftime("%d de %B de %Y, Hora: %H:%M:%S");
foreach ($boletos as $boleto) {
    $num_format = str_pad($boleto, 4, '0', STR_PAD_LEFT);
    $boletos_str = $boletos_str . "%20" . $num_format . "%20";
    $telefono = obtener_whatsapp_rnd($id_rifa);
}
$whatsapp_url = "https://api.whatsapp.com/send?phone=$telefono&text=Hola%20soy:%20$name%20$lastName%0AY%20aparte%20los%20numeros:$boletos_str%0ACon%20mi%20folio:%20$folio%20%0AEl%20dia%20de%3A%20$fecha,%20Para%20la%20rifa:%20$num_rifa";


$ticket_quantity = count($boletos);
$precio = $rifa->get_precio_de_grupo($ticket_quantity);

$query = $wpdb->prepare("select * from tbl_rifa where id_rifa= %d limit 1 ", [$id_rifa]);
$resultsRifa = $wpdb->get_row($query);

?>

<link rel="stylesheet" href="<?php echo $folder_css . '/end_purchase.css' ?>">

<?php
function sup($text)
{
    $true = preg_replace('#(\d+)(st|th|nd|rd)#', '$1<sup class="super">$2</sup>', $text);
    return $true;
}
echo sup(the_content());
?>

<style>
    .imgLogo {
        width: 400px;
        height: 400px;
    }
</style>

<div class="container my-3">
    <div class="h4 text-center">

        <div class="card">
            <div class="card-header text-center">
                <h1 class="text-center">
                    Felicidades <?php echo $name ?> !
                </h1>
            </div>
            <div class="card-body text-center">

                <div class="row">
                    <div class="col-sm d-flex justify-content-center align-items-center">
                        <img src=<?php echo $url_img_logo_reports ?> alt="logo_rifa" class="imgLogo">
                    </div>
                    <div class="col-sm">
                        <h4 class="card-title">Haz apartado tus boletos:</h4>
                        <p>
                            <?php
                            foreach ($boletos as $boleto) {
                                $num_format = str_pad($boleto, strlen($resultsRifa->cant_boletos), "0", STR_PAD_LEFT);
                                echo "#" . $num_format . " ";
                            }
                            ?>
                        </p>
                        <p class="card-text">
                            Tu información: <br>
                            <?php echo $name ?>
                            <br>
                            <?php
                            $details_client = $name . " - " . $lastName;
                            if ($show_phone_on_admin) {
                                $details_client = $details_client . " - " . $phone;
                            }
                            echo $details_client;
                            ?>
                        </p>
                        <p class="m-0 p-0 mt-4"><b>Tu folio:</b> <?php echo $folio ?> <br>
                            <b>Tu Precio:</b> $<?php echo $precio ?>
                        </p>
                        <div class="text-muted h5">
                            <p class="m-0 p-0 mt-4"><b>Fecha de apartado:</b></p>
                            <small>
                                <?php echo strftime("%A, %d de %B de %Y"); ?> <br>
                                <?php echo strftime("Hora: %H : %M"); ?>
                            </small>
                        </div>
                        <br>
                    </div>
                </div>

            </div>
            <!--<a href=< class="btn btn-success rounded">Envianos Whatsapp!</a> -->
        </div>
    </div>

    <?php
    $hours_to_pay = $resultsRifa->segundos_reinicio_estado / 60 / 60;
    $days_to_pay = $hours_to_pay / 24;
    ?>
    <hr class="mb-3">
    <small>
        <p class="font-weight-bold my-3">¡IMPORTANTE!</p>
        <div>
            <ul>
                <li>Toma una captura de esta pantalla.</li>
                <li>Toma una foto de tu comprobante de pago.</li>
                <li>Manda los archivos a nuestro Whatsapp.</li>
            </ul>
        </div>
    </small>
    <p class="text-danger h6 mb-3 mt-3">
        <?php if ($resultsRifa->reinicio_auto_estado_activo) { ?>
            Tiene <span class='font-weight-bold'><?php echo round($days_to_pay, 1) ?> dias </span>(<?php echo round($hours_to_pay, 1) ?> horas) para pagar sus boletos
        <?php } else { ?>
            Tiene hasta <span class='font-weight-bold'><?php echo $resultsRifa->fecha_limite_pago ?> </span> para pagar sus boletos
        <?php } ?>
    </p>
</div>


<p class="text-center my-4">
    <a class="whatsapp-btn" href='<?php echo $whatsapp_url ?>'> Contacto con WhatsApp</a>
</p>

<div class="row d-flex justify-content-center">
    <div class="col-12 col-md-8 col-lg-7 text-center">
        <img class="rounded-lg" src="<?php echo $rifa->get_url_img_payment() ?>" alt="img_pago">
    </div>
</div>

</div>