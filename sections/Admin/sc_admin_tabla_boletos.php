<div class="mb-5">
    <div>
        <p class="h3 text-center mb-3">Sección de Información</p>
    </div>
    <?php $available_numbers = $wpdb->get_row('call sp_cant_boletos_por_estado(' . $id_rifa . ')'); ?>
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col"># Disponibles</th>
                <th scope="col"># Apartados</th>
                <th scope="col"># Pagados</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td> <?php echo $available_numbers->cant_disponible ?></td>
                <td><?php echo $available_numbers->cant_apartado ?></td>
                <td><?php echo $available_numbers->cant_ocupado ?></td>
            </tr>
        </tbody>
    </table>

</div>