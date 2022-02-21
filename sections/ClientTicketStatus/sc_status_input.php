<div class="card w-100 p-3">
    <h2 class="text-center my-2">Consulta el estado de tu folio</h2>
    <hr>
    <form class="d-flex flex-column" action="<?php echo $page_client_ticket_status.'?rifa='.$id_rifa ?>" method="post">
        <label class="fw-bold" for="">Introduzca su folio</label>
        <input required type="number" name="folio" data-error="Folio es obligatorio." />
        <label class="fw-bold" for="">Introduzca uno de sus boletos</label>
        <input required type="number" name="numeroBoleto" data-error="Número es obligatorio." />

        <input class="mt-3" type="submit" value="Checar estado" />
    </form>
</div>