<?php include '../partes/headeradmin.php'; ?>

<?php
// Incluir archivo de conexión a la base de datos
include '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_servicio = $_POST['nombre_servicio'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $disponibilidad = $_POST['disponibilidad'];
    $horario = $_POST['horario'];

    // Insertar el nuevo servicio en la base de datos
    $stmt = $conn->prepare("INSERT INTO servicios (nombre_servicio, descripcion, precio, disponibilidad, horario) VALUES (?, ?, ?, ?, ?)");
    
    if ($stmt->execute([$nombre_servicio, $descripcion, $precio, $disponibilidad, $horario])) {
        echo "<script>
                Swal.fire({
                    title: '¡Registro Exitoso!',
                    text: 'El servicio ha sido creada exitosamente.',
                    icon: 'success'
                }).then(() => {
                    window.location.href = 'servicios.php'; // Redirigir a la página de inicio de sesión
                });
            </script>";
    } else {
        echo 
        "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un problema al registrar el servicio.',
                    icon: 'error'
                });.then(() => {
                    window.location.href = 'servicios.php'; // Redirigir a la página de inicio de sesión
                });
            </script>";
    }
}

// Obtener lista de empleados
$sql = "SELECT * FROM servicios";
$servicios = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>



<div class="table-container">
    <h1>SERVICIOS ADICIONALES</h1>
    <div class="action-bar">
      
    <button class="btn-add" onclick="openForm()">+ Agregar Servicio</button>
    </div>
    <div class="tabl">
      <table class="reservation-table">
        <thead>
          <tr>
            <th>Nombre del Servicio</th>
            <th>Descripción</th>
            <th>Precio en SOLES</th>
            <th>Disponibilidad</th>
            <th>Horario</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($servicios as $servicio): ?>
                <tr>
                    <td><?= htmlspecialchars($servicio['nombre_servicio']) ?></td>
                    <td><?= htmlspecialchars($servicio['descripcion']) ?></td>
                    <td><?= htmlspecialchars($servicio['precio']) ?></td>
                    <td><?= htmlspecialchars($servicio['disponibilidad']) ?></td>
                    <td><?= htmlspecialchars($servicio['horario']) ?></td>
                    <td><button class="btn-edit">Editar</button></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div id="serviceForm" class="form-popup">
    <form  method="post" class="form-container">
        <span class="close-btn" onclick="closeForm()">×</span>
        <h2>Agregar Servicio Adicional</h2>
        
        <label>Nombre del Servicio:</label>
        <input type="text" name="nombre_servicio" required>

        <label>Descripción:</label>
        <textarea name="descripcion" required></textarea>

        <label>Precio en SOLES:</label>
        <input type="number" name="precio" step="0.01" required>

        <label>Disponibilidad:</label>
        <select name="disponibilidad" required>
            <option value="Disponible">Disponible</option>
            <option value="No Disponible">No Disponible</option>
        </select>

        <label>Horario:</label>
        <input type="text" name="horario" placeholder="Ej. 10:00 am - 8:00 pm" required>

        <button type="submit" class="btn-submit">Agregar Servicio</button>
    </form>
</div>

  <script src="../js/servicio.js"></script>
<?php include '../partes/footeradmin.php'; ?>