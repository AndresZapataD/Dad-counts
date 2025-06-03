<?php
require_once 'models/Cliente.php';
require_once 'models/Venta.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="assets/styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Ventas</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Control de Ventas</h1>

    <!-- Formulario de Registro de Ventas -->
    <section>
        <h2>Registrar Venta</h2>
        <form id="form-venta">
            <label for="cliente">Cliente:</label>
            <input type="text" id="cliente" name="cliente" required>

            <label for="zona">Zona del Cliente:</label>
            <input type="text" id="zona" name="zona" required>

            <div id="productos">
                <h3>Productos</h3>
                <button type="button" id="agregar-producto">Agregar Producto</button>
            </div>
            
            <button type="submit">Registrar Venta</button>
        </form>
    </section>
    
    <!-- Filtro y barra de búsqueda -->
    <section>
        <h2>Buscar Ventas</h2>
        <label for="search">Buscar por cliente:</label>
        <input type="text" id="search" placeholder="Buscar cliente...">

        <label for="status">Filtrar por estado:</label>
        <select id="status">
            <option value="">Todos</option>
            <option value="debe">Debe</option>
            <option value="pagó">Pagó</option>
            <option value="vencida">Vencida</option>
        </select>
    </section>

    <!-- Lista de Ventas -->
    <section>
        <h2>Ventas Registradas</h2>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Zona</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="ventas-registradas">
                <?php
                $ventas = Venta::obtenerVentas();
                foreach ($ventas as $venta) {
                    echo "<tr class='venta-row' data-id='{$venta['id']}'>
                            <td>{$venta['fecha']}</td>
                            <td class='nombre-cliente'>{$venta['nombre_cliente']}</td>
                            <td>{$venta['zona']}</td>
                            <td>{$venta['total']}</td>
                            <td>
                                <select class='estado-lista'>
                                    <option value='debe' " . ($venta['estado'] === 'debe' ? 'selected' : '') . ">Debe</option>
                                    <option value='pagó' " . ($venta['estado'] === 'pagó' ? 'selected' : '') . ">Pagó</option>
                                    <option value='vencida' " . ($venta['estado'] === 'vencida' ? 'selected' : '') . ">Vencida</option>
                                </select>
                            </td>
                            <td>
                                <button class='ver-detalles'>Ver Detalles</button>
                                <button class='eliminar-venta'>Eliminar</button>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </section>

    <!-- Botón para descargar reporte en PDF -->
    <section>
        <button id="descargar-reporte">Descargar Reporte PDF</button>
    </section>

    <script>
        $(document).ready(function() {
            // Barra de búsqueda
            $('#search').on('keyup', function() {
                const value = $(this).val().toLowerCase();
                $('#ventas-registradas .venta-row').filter(function() {
                    $(this).toggle($(this).find('.nombre-cliente').text().toLowerCase().indexOf(value) > -1);
                });
            });

            // Filtro por estado
            $('#status').change(function() {
                const selectedStatus = $(this).val();
                $('#ventas-registradas .venta-row').filter(function() {
                    $(this).toggle(selectedStatus === "" || $(this).find('.estado-lista').val() === selectedStatus);
                });
            });

            // Descargar reporte PDF
            $('#descargar-reporte').click(function() {
                const estado = $('#status').val();
                window.open(`controllers/ReporteController.php?tipo=${estado}`, '_blank');
            });

            // Cambiar el estado de la venta mediante la lista desplegable
            $('#ventas-registradas').on('change', '.estado-lista', function() {
                const ventaId = $(this).closest('tr').data('id');
                const nuevoEstado = $(this).val();

                $.post('controllers/CambiarEstadoController.php', { id: ventaId, estado: nuevoEstado }, function(response) {
                    alert(response.message);
                }, 'json');
            });

            // Ver detalles de la venta
            $('#ventas-registradas').on('click', '.ver-detalles', function() {
                const ventaId = $(this).closest('tr').data('id');
                window.open(`controllers/FacturaController.php?id=${ventaId}`, '_blank');
            });

            // Eliminar la venta
            $('#ventas-registradas').on('click', '.eliminar-venta', function() {
                if (confirm('¿Estás seguro de que deseas eliminar esta venta? Esta acción no se puede deshacer.')) {
                    const ventaId = $(this).closest('tr').data('id');
                    
                    $.post('controllers/VentaController.php', { action: 'eliminar', id: ventaId }, function(response) {
                        alert(response.message);
                        if (response.status === 'success') {
                            location.reload();
                        }
                    }, 'json');
                }
            });

            // Añadir productos dinámicamente
            $('#agregar-producto').click(function() {
                $('#productos').append(`
                    <div class="producto">
                        <label>Nombre del Producto: <input type="text" name="productos[nombre][]" required></label>
                        <label>Cantidad: <input type="number" name="productos[cantidad][]" required></label>
                        <label>Precio Unitario: <input type="number" step="0.01" name="productos[precio][]" required></label>
                        <button type="button" class="eliminar-producto">Eliminar</button>
                    </div>
                `);
            });

            $('#productos').on('click', '.eliminar-producto', function() {
                $(this).parent('.producto').remove();
            });

            // Enviar el formulario de venta
            $('#form-venta').submit(function(e) {
                e.preventDefault();
                const formData = $(this).serializeArray();
                const data = {};

                formData.forEach(item => {
                    if (item.name.endsWith('[]')) {
                        const key = item.name.slice(0, -2);
                        if (!data[key]) data[key] = [];
                        data[key].push(item.value);
                    } else {
                        data[item.name] = item.value;
                    }
                });

                $.post('controllers/VentaController.php', data, function(response) {
                    alert(response.message);
                    if(response.status === 'success') {
                        location.reload();
                    }
                }, 'json');
            });
        });
    </script>
</body>
</html>
