<?php
/*
  *Template Name: Proceso-Pago
 */
get_header();
global $folder_js;
global $rifas_data;
global $service_create_payment;

if (!isset($_GET["rifa"]) || !isset($_GET["folio"])) {
    $titulo = "No tiene acceso a este link";
    $mensaje = "Si tiene algun problema no dude en contactarse con nosotros";
    echo "<script>parent.self.location='" . $page_notice . "?titulo=" . $titulo . "&mensaje=" . $mensaje . "';</script>";
    exit();
}

$id_rifa = (int)$_GET["rifa"];
$folio = (int)$_GET["folio"];
$rifa = $rifas_data->get_rifa($id_rifa);
if (!$rifa) {
    $titulo = "No tiene acceso a este link";
    $mensaje = "Si tiene algun problema no dude en contactarse con nosotros";
    echo "<script>parent.self.location='" . $page_notice . "?titulo=" . $titulo . "&mensaje=" . $mensaje . "';</script>";
    exit();
}

?>

<script src="https://js.stripe.com/v3/"></script>

<input type="text" id="id-rifa" value="<?php echo $id_rifa ?>" hidden>
<input type="text" id="folio" value="<?php echo $folio ?>" hidden>
<input type="text" id="service-create-intent" value="<?php echo $service_create_payment ?>" hidden>

<div class="container">
    <!-- Display a payment form -->
    <form id="payment-form">
        <div id="payment-element">
            <!--Stripe.js injects the Payment Element-->
        </div>
        <button id="submit mt-3">
            <div class="spinner hidden" id="spinner"></div>
            <span id="button-text">Pagar ahora</span>
        </button>
        <div id="payment-message" class="hidden"></div>
    </form>
</div>

<script src="<?php echo $folder_js . "/stripe_checkout.js"  ?>"></script>