<?php

/*
    *Template Name: MercadoPago
*/

// SDK de Mercado Pago
require __DIR__ .  '/vendor/autoload.php';
// Agrega credenciales
MercadoPago\SDK::setAccessToken('TEST-7330002973113125-021500-387750c6ba12e30f3dd571ca219a599d-180674235');

// Crea un objeto de preferencia
$preference = new MercadoPago\Preference();

// Crea un ítem en la preferencia
$ticket = new MercadoPago\Item();
$ticket->id = '¡';
$ticket->title = 'Numero';
$ticket->currency = "MXN";
$ticket->quantity = 3;
$ticket->unit_price = 400;

$preference->items = array($ticket);
$preference->save();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Security-Policy" content="default-src * self blob: data: gap:; style-src * self 'unsafe-inline' blob: data: gap:; script-src * 'self' 'unsafe-eval' 'unsafe-inline' blob: data: gap:; object-src * 'self' blob: data: gap:; img-src * self 'unsafe-inline' blob: data: gap:; connect-src self * 'unsafe-inline' blob: data: gap:; frame-src * self blob: data: gap:;">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://sdk.mercadopago.com/js/v2"></script>
  <title>Document</title>
</head>
<body>
<a href="<?php echo $preference->init_point; ?>">Pagar con Mercado Pago</a>
</body>
</html>

<script>
  // Agrega credenciales de SDK
  const mp = new MercadoPago("TEST-9dedf469-1be5-4a87-afc9-cc77487d0f30", {
    locale: "es-MX",
  });

  // Inicializa el checkout
  mp.checkout({
    preference: {
      id: '0001',
    },
  });
</script>