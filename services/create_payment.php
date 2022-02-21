<?php
global $wpdb;
require('../../../../wp-load.php');

ini_set('display_errors', 1);
error_reporting(E_ALL);
//ob_start();

//\Stripe\Stripe::setApiKey('sk_test_51JXx4zCK0nZTk4HlRZp2lTPTFBpbsOLdunWi3Mm7B8K37Qe5kykht4RixD1KtwjxA9zW1dRKozsloFtPrktxnzuM00f6xCDLnC');
$stripe = new \Stripe\StripeClient('sk_test_51JXx4zCK0nZTk4HlRZp2lTPTFBpbsOLdunWi3Mm7B8K37Qe5kykht4RixD1KtwjxA9zW1dRKozsloFtPrktxnzuM00f6xCDLnC');

function calculatePrice($id_rifa, $folio)
{
    global $wpdb;
    global $rifas_data;

    $rifa = $rifas_data->get_rifa($id_rifa);
    $id_rifa = $rifa->get_id_rifa();
    $qry_results = $wpdb->get_row("select count(*) as cantidad_boletos from tbl_boleto where id_rifa = {$id_rifa} and num_folio = {$folio}");
    $cant_boletos = $qry_results->cantidad_boletos;
    $price = $rifa->get_precio_de_grupo($cant_boletos);
    return $price;
}

header('Content-Type: application/json');

$output = array("error" => false, "results" => null);
try {
    $jsonStr = file_get_contents('php://input');
    $jsonObj = json_decode($jsonStr);

    $price = calculatePrice((int)$jsonObj->idRifa, (int)$jsonObj->folio);
    $results = [
        "clientSecret" => null
    ];

    if (!is_null($price)) {
        $priceCents = ((int)$price) * 100;
        // Create a PaymentIntent with amount and currency
        /*$paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $priceCents,
            'currency' => 'mxn',
            'automatic_payment_methods' => [
                'enabled' => true,
            ]
        ]);*/

        $paymentIntent = $stripe->paymentIntents->create(
            [
                'amount' => $priceCents,
                'currency' => 'mxn',
                'automatic_payment_methods' => ['enabled' => true],
            ]
        );

        $results["clientSecret"] = $paymentIntent->client_secret;
        $output["results"] = $results;
    } else {
        $output["error"] = true;
    }

    //ob_end_clean();

    echo json_encode($output);
} catch (Error $e) {
    //ob_end_clean();
    $output["error"] = var_dump($e);
    http_response_code(500);
    echo json_encode($output);
}
