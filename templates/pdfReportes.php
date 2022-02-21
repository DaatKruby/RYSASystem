<?php
require('../../../../wp-load.php');
global $reportes_folder;
global $sections_folder;
require($reportes_folder . "/fpdf.php");
define('WP_USE_THEMES', false);
$repName = $_GET['reportName'];
global $contexto;

$id_rifa = null;
if (isset($_GET["rifa"])) {
    $id_rifa = (int)$_GET['rifa'];
}
$rifa = $rifas_data->get_rifa($id_rifa);
if (!$rifa) {
    $titulo = "Rifa no encontrada";
    $mensaje = "La rifa a la que trata acceder no existe o ya ha acabado, si cree que es un error, contacte al administrador";
    echo "<script>parent.self.location='" . $page_notice . "?titulo=" . $titulo . "&mensaje=" . $mensaje . "';</script>";
    exit();
}
$query = $wpdb->prepare("select * from tbl_rifa where id_rifa= %d limit 1 ", [$id_rifa]);
$resultsRifa = $wpdb->get_row($query);
global $cantCifrasBoleto;
$cantCifrasBoleto = strlen($resultsRifa->cant_boletos);

function titulo($name, $context)
{
    switch ($name) {
        case "reportNumDisp":
            $context->Cell(30, 10, 'Numeros disponibles', 0, 0, 'C');
            break;
        case "reportPublic":
            $context->Cell(30, 10, 'Reporte Publico', 0, 0, 'C');
            break;
        case "reportPaid":
            $context->Cell(30, 10, 'Boletos pagados', 0, 0, 'C');
            break;
        case "reportPrivate":
            $context->Cell(30, 10, 'Reporte Privado', 0, 0, 'C');
            break;
        case "reportByFolios":
            $context->Cell(30, 10, 'Reporte Folios', 0, 0, 'C');
            break;
        case "reportPhone":
            $context->Cell(30, 10, 'Reporte Celulares', 0, 0, 'C');
            break;
    }
}

function cabecera($name, $context)
{
    switch ($name) {
        case "reportNumDisp":
            return $context->Cell(190, 10, 'Numeros', 1, 1, 'C', 0);
            break;
        case "reportPublic":
            $context->Cell(30, 10, utf8_decode('Números'), 1, 0, 'C', 0);
            $context->Cell(60, 10, utf8_decode('Nombre(s)'), 1, 0, 'C', 0);
            $context->Cell(60, 10, utf8_decode('Apellidos'), 1, 0, 'C', 0);
            $context->Cell(40, 10, utf8_decode('Disponibilidad'), 1, 1, 'C', 0);
            break;
        case "reportPaid":
            $context->Cell(30, 10, utf8_decode('Disponibles'), 1, 0, 'C', 0);
            $context->Cell(60, 10, utf8_decode('Apartados'), 1, 0, 'C', 0);
            $context->Cell(60, 10, utf8_decode('Pagados'), 1, 1, 'C', 0);
            break;
        case "reportPrivate":
            $context->Cell(30, 10, utf8_decode('Números'), 1, 0, 'C', 0);
            $context->Cell(10, 10, utf8_decode('Folio'), 1, 0, 'C', 0);
            $context->Cell(50, 10, utf8_decode('Nombre'), 1, 0, 'C', 0);
            $context->Cell(50, 10, utf8_decode('Apellidos'), 1, 0, 'C', 0);
            $context->Cell(20, 10, utf8_decode('Celular'), 1, 0, 'C', 0);
            $context->Cell(20, 10, utf8_decode('Situación'), 1, 1, 'C', 0);
            break;
        case "reportByFolios":
            $context->Cell(40, 10, utf8_decode('ID Rifa'), 1, 0, 'C', 0);
            $context->Cell(30, 10, utf8_decode('Grupo de boletos'), 1, 0, 'C', 0);
            $context->Cell(30, 10, utf8_decode('Cantidad de grupos'), 1, 0, 'C', 0);
            $context->Cell(30, 10, utf8_decode('Estado'), 1, 1, 'C', 0);
            break;
        case "reportPhone":
            $context->SetLeftMargin(50);
            $context->Cell(110, 10, utf8_decode('Celular'), 1, 1, 'C', 0);
            break;
    }
}

function selectBodyPdf($name, $context)
{
    global $cantCifrasBoleto;
    global $id_rifa;
    global $reportes_folder;
    switch ($name) {
        case "reportNumDisp":
            require $reportes_folder . '/cn.php';

            $consulta = "select * from vw_boletos_disponibles where id_rifa = " . $id_rifa . ";";
            $resultados = $mysqli->query($consulta);
            $mysqli->close();
            if (!$resultados) {
                throw new Exception("Database Error [{$context->database->errno}] {$context->database->error}");
            }

            $pdf = new PDF();
            $pdf->AliasNbPages();
            $pdf->AddPage();
            $pdf->SetFont('Arial', '', 8);


            $cont = 0;
            while ($row = $resultados->fetch_assoc()) {
                if ($cont > 17) {
                    $pdf->Cell(10, 6, str_pad($row['num_boleto'], $cantCifrasBoleto, '0', STR_PAD_LEFT), 1, 1, 'C', 0);
                    $cont = 0;
                } else {
                    $pdf->Cell(10, 6, str_pad($row['num_boleto'], $cantCifrasBoleto, '0', STR_PAD_LEFT), 1, 0, 'C', 0);
                    $cont++;
                }
            }
            $pdf->Output();
            break;
        case "reportPublic":
            require $reportes_folder . '/cn.php';

            $consulta = "select * from vw_reporte_publico where id_rifa = " . $id_rifa . ";";
            $resultados = $mysqli->query($consulta);
            $mysqli->close();
            if (!$resultados) {
                throw new Exception("Database Error [{$context->database->errno}] {$context->database->error}");
            }
            $pdf = new PDF();
            $pdf->AliasNbPages();
            $pdf->AddPage();
            $pdf->SetFont('Arial', '', 8);
            while ($row = $resultados->fetch_assoc()) {
                $arr_numeros = explode(',', $row['boletos']);
                $numeros_boletos = '';

                $array_size = sizeof($arr_numeros);
                $i = 0;
                foreach ($arr_numeros as $numero_boleto) {
                    $numeros_boletos = $numeros_boletos . str_pad($numero_boleto, $cantCifrasBoleto, '0', STR_PAD_LEFT);
                    if ($i != $array_size - 1) {
                        $numeros_boletos = $numeros_boletos . ", ";
                    }
                    $i++;
                }

                if ($row['nombre_estado'] === 'Pagado') {
                    $pdf->SetFillColor(255, 233, 0);
                } else {
                    $pdf->SetFillColor(255, 255, 255);
                }

                $pdf->Cell(30, 6, $numeros_boletos, 1, 0, 'C', 1);
                $pdf->Cell(60, 6, utf8_decode($row['nombres']), 1, 0, 'C', 1);
                $pdf->Cell(60, 6, utf8_decode($row['apellidos']), 1, 0, 'C', 1);
                if ($row['nombre_estado'] === 'Pagado') {
                    $pdf->Cell(40, 6, 'Pagado', 1, 1, 'C', 1);
                } else {
                    $pdf->Cell(40, 6, $row['nombre_estado'], 1, 1, 'C', 1);
                }
            }
            $pdf->Output();
            break;
        case "reportPaid":
            require $reportes_folder . '/cn.php';

            // if ($mysqli = $context->query("call sp_cant_boletos_por_estado")) {
            //     $resultados = json_encode($mysqli->fetch_all(MYSQLI_ASSOC));
            //     $resultados = json_decode($resultados);
            // }

            $consulta = "call sp_cant_boletos_por_estado(2)";
            $resultados = $mysqli->query($consulta);
            $resultados = json_encode($resultados->fetch_all(MYSQLI_ASSOC));
            $resultados = json_decode($resultados);

            $mysqli->close();
            if (!$resultados) {
                throw new Exception("Database Error [{$context->database->errno}] {$context->database->error}");
            }

            $pdf = new PDF();
            $pdf->AliasNbPages();
            $pdf->AddPage();
            $pdf->SetFont('Arial', '', 8);

            $pdf->Cell(30, 6, $resultados[0]->cant_disponible, 1, 0, 'C', 0);
            $pdf->Cell(60, 6, $resultados[0]->cant_apartado, 1, 0, 'C', 0);
            $pdf->Cell(60, 6, $resultados[0]->cant_ocupado, 1, 0, 'C', 0);
            $pdf->Output();
            break;
        case "reportPrivate":
            require $reportes_folder . '/cn.php';

            $consulta = "select * from vw_boletos_con_estado where id_rifa = " . $id_rifa . ";"; //where nombre_estado!='Disponible' group by folio  //id_rifa_aqui
            $resultados = $mysqli->query($consulta);
            $mysqli->close();
            if (!$resultados) {
                throw new Exception("Database Error [{$context->database->errno}] {$context->database->error}");
            }

            $pdf = new PDF();
            $pdf->AliasNbPages();
            $pdf->AddPage();
            $pdf->SetFont('Arial', '', 8);

            while ($row = $resultados->fetch_assoc()) {

                $arr_numeros = explode(',', $row['num_boleto']);
                $numeros_boletos = '';

                $array_size = sizeof($arr_numeros);
                $i = 0;
                foreach ($arr_numeros as $numero_boleto) {
                    $numeros_boletos = $numeros_boletos . str_pad($numero_boleto, $cantCifrasBoleto, '0', STR_PAD_LEFT);
                    if ($i != $array_size - 1) {
                        $numeros_boletos = $numeros_boletos . ", ";
                    }
                    $i++;
                }
                if ($row['nombre_estado'] === 'Pagado') {
                    $pdf->SetFillColor(255, 233, 0);
                } else {
                    $pdf->SetFillColor(255, 255, 255);
                }
                if ($row['nombres'] !== 'oculto' && $row['apellidos'] !== 'oculto') {
                    $pdf->Cell(30, 6, $numeros_boletos, 1, 0, 'C', 1);
                    $pdf->Cell(10, 6, $row['num_folio'], 1, 0, 'C', 1);
                    $pdf->Cell(50, 6, utf8_decode($row['nombres']), 1, 0, 'C', 1);
                    $pdf->Cell(50, 6, utf8_decode($row['apellidos']), 1, 0, 'C', 1);
                    $pdf->Cell(20, 6, $row['celular'], 1, 0, 'C', 1);
                    if ($row['nombre_estado'] === 'Pagado') {
                        $pdf->Cell(20, 6, 'Pagado', 1, 1, 'C', 1);
                    } else {
                        $pdf->Cell(20, 6, $row['nombre_estado'], 1, 1, 'C', 1);
                    }
                }
            }

            $pdf->Output();
            break;
        case "reportByFolios":
            require $reportes_folder . '/cn.php';

            $consulta = "select * from vw_cant_de_grupos where id_rifa = " . $id_rifa . " ;";

            $resultados = $mysqli->query($consulta);
            $mysqli->close();
            if (!$resultados) {
                throw new Exception("Database Error [{$context->database->errno}] {$context->database->error}");
            }

            $pdf = new PDF();
            $pdf->AliasNbPages();
            $pdf->AddPage();
            $pdf->SetFont('Arial', '', 8);

            while ($row = $resultados->fetch_assoc()) {

                if ($row['id_estado_boleto'] == '2') {
                    $pdf->SetFillColor(255, 233, 0);
                } else {
                    $pdf->SetFillColor(255, 255, 255);
                }

                $pdf->Cell(40, 6, $row['id_rifa'], 1, 0, 'C', 1);
                $pdf->Cell(30, 6, $row['Grupo de boletos'], 1, 0, 'C', 1);
                $pdf->Cell(30, 6, $row['Cantidad de grupos'], 1, 0, 'C', 1);

                if ($row['id_estado_boleto'] == '2') {
                    $pdf->Cell(30, 6, 'Pagados', 1, 1, 'C', 1);
                } else {
                    $pdf->Cell(30, 6, 'Apartados', 1, 1, 'C', 1);
                }
            }

            $pdf->Output();
            break;
    }
}

class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
        global $url_img_logo_reports;
        global $repName;
        $contexto = $this;
        // Logo
        $this->Image($url_img_logo_reports, 10, 8, 15);
        // Arial bold 15
        $this->SetFont('Arial', 'B', 8);
        // Movernos a la derecha
        $this->Cell(80);
        // Título
        titulo($repName, $contexto);
        // Salto de línea
        $this->Ln(20);
        cabecera($repName, $contexto);
    }

    // Pie de página
    function Footer()
    {
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', '', 8);
        // Número de página
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

selectBodyPdf($repName, $contexto);
//$pdf->Output();
