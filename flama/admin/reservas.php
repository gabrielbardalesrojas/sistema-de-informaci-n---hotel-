<?php include '../partes/headeradmin.php'; ?>

<?php
include '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $cliente_id = $_POST['cliente_id'];
  $habitacion_id = $_POST['habitacion_id'];
  $check_in = $_POST['check_in'];
  $check_out = $_POST['check_out'];
  $num_personas = $_POST['num_personas'];
  $servicios_adicionales = isset($_POST['servicio']) ? implode(',', $_POST['servicio']) : null;
  $total_pagado = filter_var($_POST['total_pagado'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
  $metodo_pago = $_POST['metodo_pago'];
  $comprobante = $_POST['comprobante'];

  // Insertar la reserva en la base de datos
  $stmt = $conn->prepare("INSERT INTO reservas (cliente_id, habitacion_id, check_in, check_out, num_personas, servicio, total_pagado, metodo_pago, comprobante) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
  
  if ($stmt->execute([$cliente_id, $habitacion_id, $check_in, $check_out, $num_personas, $servicio, $total_pagado, $metodo_pago, $comprobante])) {
      
      // Actualizar el estado de la habitación a 'Ocupada'
      $stmt = $conn->prepare("UPDATE habitaciones SET estado = 'Ocupada' WHERE id = ?");
      $stmt->execute([$habitacion_id]);

     

        echo "<script>
        Swal.fire({
            title: '¡Registro Exitoso!',
            text: 'La reserva ha sido creada exitosamente.',
            icon: 'success'
        }).then(() => {
            window.location.href = 'reservas.php'; // Redirigir a la página de inicio de sesión
        });
    </script>";
} else {
echo 
"<script>
        Swal.fire({
            title: 'Error',
            text: 'Hubo un problema al registrar la reserva.',
            icon: 'error'
        });.then(() => {
            window.location.href = 'reservas.php'; // Redirigir a la página de inicio de sesión
        });
    </script>";
}
}
//editar


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_reserva'])) {
    $reserva_id = $_POST['reserva_id'];
    $cliente_id = $_POST['cliente_id'];
    $habitacion_id = $_POST['habitacion_id'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $num_personas = $_POST['num_personas'];
    $servicios_adicionales = isset($_POST['servicios']) ? implode(',', $_POST['servicios']) : null;
    $total_pagado = filter_var($_POST['total_pagado'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $metodo_pago = $_POST['metodo_pago'];
    $comprobante = $_POST['comprobante'];

    // Actualizar la reserva en la base de datos
    $stmt = $conn->prepare("UPDATE reservas SET cliente_id = ?, habitacion_id = ?, check_in = ?, check_out = ?, num_personas = ?, servicio = ?, total_pagado = ?, metodo_pago = ?, comprobante = ? WHERE id = ?");
    
    if ($stmt->execute([$cliente_id, $habitacion_id, $check_in, $check_out, $num_personas, $servicios_adicionales, $total_pagado, $metodo_pago, $comprobante, $reserva_id])) {
        echo "<script>
            Swal.fire({
                title: '¡Actualización Exitosa!',
                text: 'La reserva ha sido actualizada correctamente.',
                icon: 'success'
            }).then(() => {
                window.location.href = 'reservas.php'; // Redirigir a la página de reservas
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al actualizar la reserva.',
                icon: 'error'
            });
        </script>";
    }
}
// cancelar reserva





// Paso 1: Seleccionar habitaciones que no están vinculadas a ninguna reserva activa
$stmt = $conn->query("
    SELECT id FROM habitaciones 
    WHERE id NOT IN (
        SELECT habitacion_id FROM reservas WHERE check_out >= CURDATE()
    )
");

// Paso 2: Actualizar el estado de esas habitaciones a "Disponible"
$habitacionesSinReservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($habitacionesSinReservas as $habitacion) {
    $stmtUpdate = $conn->prepare("UPDATE habitaciones SET estado = 'Disponible' WHERE id = ?");
    $stmtUpdate->execute([$habitacion['id']]);
}



// Obtener lista de reserva
$sql = "SELECT reservas.*, clientes.nombre_completo AS nombre_cliente, clientes.dni AS documento_id, habitaciones.tipo_habitacion 
        FROM reservas 
        JOIN clientes ON reservas.cliente_id = clientes.id 
        JOIN habitaciones ON reservas.habitacion_id = habitaciones.id";
$reservas = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);


//cambiarel estado de la hbaitacion



try {
    // Consultar todas las reservas con estado "Completada" o "Cancelada"
    $stmt = $conn->prepare("SELECT habitacion_id FROM reservas WHERE estado_reserva = 'Completada' OR estado_reserva = 'Cancelada'");
    $stmt->execute();
    $habitaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Actualizar el estado de las habitaciones a "Disponible"
    if ($habitaciones) {
        $stmt_update = $conn->prepare("UPDATE habitaciones SET estado = 'Disponible' WHERE id = ?");
        foreach ($habitaciones as $habitacion) {
            $stmt_update->execute([$habitacion['habitacion_id']]);
        }

    } else {
        echo "No hay habitaciones que necesiten ser actualizadas.";
    }
} catch (Exception $e) {
    echo "Error al actualizar el estado de las habitaciones: " . $e->getMessage();
}


//otro


try {
    // Iniciar una transacción
    $conn->beginTransaction();

    // Seleccionar todas las habitaciones que están en reservas y cuyo estado es "Disponible"
    $sql_select = "
        SELECT h.id 
        FROM habitaciones h
        INNER JOIN reservas r ON h.id = r.habitacion_id
        WHERE h.estado = 'Disponible'
    ";
    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->execute();
    $habitaciones = $stmt_select->fetchAll(PDO::FETCH_ASSOC);

    // Actualizar el estado de las habitaciones a "Ocupada"
    if ($habitaciones) {
        $sql_update = "UPDATE habitaciones SET estado = 'Ocupada' WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);

        foreach ($habitaciones as $habitacion) {
            $stmt_update->execute([$habitacion['id']]);
        }
    }

    // Confirmar la transacción
    $conn->commit();

    
} catch (Exception $e) {
    // Deshacer la transacción en caso de error
    $conn->rollBack();
    echo "Error al actualizar el estado de las habitaciones: " . $e->getMessage();
}

?>


<div class="table-container">
<h1>RESERVA</h1>
<div class="action-bar">
      
      <button class="btn-add" onclick="openForm()">+ Agregar Reserva</button>
      </div>
    <div class="tabl">
    <table class="reservation-table">
      <thead>
        <tr>
          <th>Nº Reserva</th>
          <th>Nombre del Cliente</th>
          <th>Documento ID</th>
          <th>Tipo de Habitación</th>
          <th>Check-In</th>
          <th>Check-Out</th>
          <th>Número de Personas</th>
          <th>Servicios Adicionales</th>
          <th>Total Pagado</th>
          <th>Método de Pago</th>
          <th>Comprobante de pago</th>
          <th>Estado de la Reserva</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($reservas as $reserva): ?>
    <tr>
        <td><?= htmlspecialchars($reserva['id']) ?></td>
        <td><?= htmlspecialchars($reserva['nombre_cliente'] ?? '') ?></td>
<td><?= htmlspecialchars($reserva['documento_id'] ?? '') ?></td>
<td><?= htmlspecialchars($reserva['tipo_habitacion'] ?? '') ?></td>
<td><?= htmlspecialchars($reserva['check_in'] ?? '') ?></td>
<td><?= htmlspecialchars($reserva['check_out'] ?? '') ?></td>
<td><?= htmlspecialchars($reserva['num_personas'] ?? '') ?></td>
<td><?= htmlspecialchars($reserva['servicio'] ?? '') ?></td>
<td>S/ <?= htmlspecialchars($reserva['total_pagado'] ?? '0.00') ?></td>
<td><?= htmlspecialchars($reserva['metodo_pago'] ?? '') ?></td>
<td><?= htmlspecialchars($reserva['comprobante'] ?? '') ?></td>
        <td><span class="status <?= $reserva['estado_reserva'] === 'Activa' ? 'active' : ($reserva['estado_reserva'] === 'En Uso' ? 'in-use' : ($reserva['estado_reserva'] === 'Completada' ? 'completed' : 'canceled')) ?>"><?= htmlspecialchars($reserva['estado_reserva']) ?></span></td>
        <td>
    <button class="btn-edit" onclick="openEditForm(<?= htmlspecialchars(json_encode($reserva)) ?>)">Editar</button>
   
</td>

    </tr>
<?php endforeach; ?>

      </tbody>
    </table>

    </div>
    
  </div>


  <div id="reservationForm" class="form-popup">
    <form  method="post" class="form-container">
        <span class="close-btn" onclick="closeForm()">×</span>
        <h2>Agregar Reserva</h2>
        
        <label>Cliente:</label>
        <select name="cliente_id" required>
            <?php
            // Obtener clientes de la base de datos
            include '../conexion.php';
            $clientes = $conn->query("SELECT id, nombre_completo, dni FROM clientes");
            while ($cliente = $clientes->fetch()) {
                echo "<option value='{$cliente['id']}'>{$cliente['nombre_completo']} - DNI: {$cliente['dni']}</option>";
            }
            ?>
        </select>

        <label>Habitación:</label>
        <select name="habitacion_id" id="habitacion_id" onchange="calcularTotal()" required>
            <?php
            // Obtener habitaciones disponibles
            $habitaciones = $conn->query("SELECT id, numero_habitacion, tipo_habitacion, precio_por_noche FROM habitaciones WHERE estado = 'Disponible'");
            while ($habitacion = $habitaciones->fetch()) {
                echo "<option value='{$habitacion['id']}' data-precio='{$habitacion['precio_por_noche']}'>{$habitacion['numero_habitacion']} - {$habitacion['tipo_habitacion']} - S/ {$habitacion['precio_por_noche']} por noche</option>";
            }
            ?>
        </select>

        <label>Check-In:</label>
        <input type="date" name="check_in" id="check_in" required>

        <label>Check-Out:</label>
        <input type="date" name="check_out" id="check_out" onchange="calcularTotal()" required>

        <label>Número de Personas:</label>
        <input type="number" name="num_personas" min="1" required>

        <label>Servicios Adicionales:</label>
        <select name="servicios[]" id="servicios" multiple onchange="calcularTotal()">
            <?php
            // Obtener servicios adicionales disponibles
            $servicios = $conn->query("SELECT id, nombre_servicio, precio FROM servicios WHERE disponibilidad = 'Disponible'");
            while ($servicio = $servicios->fetch()) {
                echo "<option value='{$servicio['id']}' dato-precio='{$servicio['precio']}'>{$servicio['nombre_servicio']} - S/ {$servicio['precio']}</option>";
            }
            ?>
        </select>

        <label>Total Pagado (Soles):</label>
        <input type="text" name="total_pagado" id="total_pagado" readonly>

        <label>Método de Pago:</label>
        <select name="metodo_pago" required>
            <option value="Efectivo">Efectivo</option>
            <option value="Tarjeta de Credito">Tarjeta de Credito</option>
            <option value="Transferencia Bancaria">Transferencia Bancaria</option>
        </select>

        <label>Comprobante de Pago:</label>
        <select name="comprobante" required>
            <option value="Boleta">Boleta</option>
            <option value="Factura">Factura</option>
            <option value="Guía de Remisión">Guía de Remisión</option>
            <option value="Nota de Crédito">Nota de Crédito</option>
            <option value="Nota de Débito">Nota de Débito</option>
        </select>

        <button type="submit" class="btn-submit">Agregar Reserva</button>
    </form>
</div>



<div id="editReservationForm" class="form-popup">
    <form method="post" class="form-container">
        <span class="close-btn" onclick="closeEditForm()">×</span>
        <h2>Editar Reserva</h2>

        <input type="hidden" name="reserva_id" id="edit_reserva_id">

        <label>Cliente:</label>
        <select name="cliente_id" id="edit_cliente_id" required>
            <!-- Aquí puedes reutilizar la lógica de PHP para obtener los clientes -->
        </select>

        <label>Habitación:</label>
        <select name="habitacion_id" id="edit_habitacion_id" required>
            <!-- Aquí puedes reutilizar la lógica de PHP para obtener las habitaciones -->
        </select>

        <label>Check-In:</label>
        <input type="date" name="check_in" id="edit_check_in" required>

        <label>Check-Out:</label>
        <input type="date" name="check_out" id="edit_check_out" required>

        <label>Número de Personas:</label>
        <input type="number" name="num_personas" id="edit_num_personas" min="1" required>

        <label>Servicios Adicionales:</label>
        <select name="servicios[]" id="edit_servicios" multiple>
            <!-- Aquí puedes reutilizar la lógica de PHP para obtener los servicios -->
        </select>

        <label>Total Pagado (Soles):</label>
        <input type="text" name="total_pagado" id="edit_total_pagado" readonly>

        <label>Método de Pago:</label>
        <select name="metodo_pago" id="edit_metodo_pago" required>
            <option value="Efectivo">Efectivo</option>
            <option value="Tarjeta de Credito">Tarjeta de Credito</option>
            <option value="Transferencia Bancaria">Transferencia Bancaria</option>
        </select>

        <label>Comprobante de Pago:</label>
        <select name="comprobante" id="edit_comprobante" required>
            <option value="Boleta">Boleta</option>
            <option value="Factura">Factura</option>
            <option value="Guía de Remisión">Guía de Remisión</option>
            <option value="Nota de Crédito">Nota de Crédito</option>
            <option value="Nota de Débito">Nota de Débito</option>
        </select>

        <button type="submit" name="update_reserva" class="btn-submit">Actualizar Reserva</button>
    </form>
</div>

<!-- Formulario oculto para cancelar la reserva -->


<script>
    function openEditForm(reserva) {
  document.getElementById('edit_reserva_id').value = reserva.id;
  document.getElementById('edit_cliente_id').value = reserva.cliente_id;
  document.getElementById('edit_habitacion_id').value = reserva.habitacion_id;
  document.getElementById('edit_check_in').value = reserva.check_in;
  document.getElementById('edit_check_out').value = reserva.check_out;
  document.getElementById('edit_num_personas').value = reserva.num_personas;
  document.getElementById('edit_total_pagado').value = reserva.total_pagado;
  document.getElementById('edit_metodo_pago').value = reserva.metodo_pago;
  document.getElementById('edit_comprobante').value = reserva.comprobante;
  // Abre el formulario de edición
  document.getElementById('editReservationForm').style.display = 'block';
}

function closeEditForm() {
  document.getElementById('editReservationForm').style.display = 'none';
}



</script>
  <script src="../js/reserva.js"></script>

<?php include '../partes/footeradmin.php'; ?>