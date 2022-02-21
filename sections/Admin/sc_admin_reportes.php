<p class="h3 text-center mb-3">Sección de Reportes</p>
<div class="container-title">
    <div class="d-flex justify-content-center flex-wrap mt-4">
        <form class="mr-2 mb-2" action="<?php echo $page_reports?>" method="get">
            <input type="hidden" name="reportName" value="reportPublic">
            <input type="hidden" name="rifa" value="<?php echo $id_rifa ?>">
            <input type="submit" value="Reporte publico">
        </form>

        <form class="mr-2 mb-2" action="<?php echo $page_reports?>" method="get">
            <input type="hidden" name="reportName" value="reportNumDisp">
            <input type="hidden" name="rifa" value="<?php echo $id_rifa ?>">
            <input type="submit" value="Reporte Números disponibles">
        </form>

        <form class="mr-2 mb-2" action="<?php echo $page_reports?>" method="get">
            <input type="hidden" name="reportName" value="reportPrivate">
            <input type="hidden" name="rifa" value="<?php echo $id_rifa ?>">
            <input type="submit" value="Reporte privado">
        </form>
        <form class="mr-2 mb-2" action="<?php echo $page_reports?>" method="get">
            <input type="hidden" name="reportName" value="reportByFolios">
            <input type="hidden" name="rifa" value="<?php echo $id_rifa ?>">
            <input type="submit" value="Reporte Por Folios">
        </form>
    </div>
</div>
<hr class="my-3">