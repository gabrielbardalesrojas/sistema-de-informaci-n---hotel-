<?php include '../partes/headeradmin.php'; ?>

<?php
// Incluir archivo de conexión a la base de datos
include '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $numero_habitacion = $_POST['numero_habitacion'];
    $tipo_habitacion = $_POST['tipo_habitacion'];
    $capacidad = $_POST['capacidad'];
    $precio_por_noche = $_POST['precio_por_noche'];
    
    $servicios_incluidos = $_POST['servicios_incluidos'];
    $vista = $_POST['vista'];
    $tamano_m2 = $_POST['tamano_m2'];
    $piso = $_POST['piso'];

    // Insertar la nueva habitación en la base de datos
    // Insertar la nueva habitación en la base de datos
// Modificar la consulta para establecer el estado por defecto en "Disponible"
// Consulta de inserción sin el campo "estado"
$stmt = $conn->prepare("INSERT INTO habitaciones (numero_habitacion, tipo_habitacion, capacidad, precio_por_noche, servicios_incluidos, vista, tamano_m2, piso) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

// Ejecutar la consulta con el número correcto de variables
if ($stmt->execute([$numero_habitacion, $tipo_habitacion, $capacidad, $precio_por_noche, $servicios_incluidos, $vista, $tamano_m2, $piso])) {
    echo "<script>
    Swal.fire({
        title: '¡Registro Exitoso!',
        text: 'La habitación ha sido creada exitosamente.',
        icon: 'success'
    }).then(() => {
        window.location.href = 'habitaciones.php'; // Redirigir a la página de inicio
    });
    </script>";
} else {
    echo "<script>
    Swal.fire({
        title: 'Error',
        text: 'Hubo un problema al registrar la habitación.',
        icon: 'error'
    }).then(() => {
        window.location.href = 'habitaciones.php'; // Redirigir a la página de inicio
    });
    </script>";
}

}

// Obtener lista de habitaciones
$sql = "SELECT * FROM habitaciones";
$habitaciones = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// Obtener números de habitación ya en uso
$sql = "SELECT numero_habitacion FROM habitaciones";
$habitaciones_ocupadas = $conn->query($sql)->fetchAll(PDO::FETCH_COLUMN);
?>

<div class="table-container">
    <h1>HABITACIONES</h1>
    <div class="action-bar">
        <button class="btn-add" onclick="openForm()">+ Agregar Habitación</button>
    </div>
    <div class="tabl">
        <table class="reservation-table">
            <thead>
                <tr>
                    <th>Número de Habitación</th>
                    <th>Tipo de Habitación</th>
                    <th>Capacidad</th>
                    <th>Precio por Noche</th>
                    <th>Estado</th>
                    <th>Servicios Incluidos</th>
                    <th>Vista</th>
                    <th>Tamaño (m²)</th>
                    <th>Piso</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($habitaciones as $habitacion): ?>
                    <tr>
                        <td><?= htmlspecialchars($habitacion['numero_habitacion']) ?></td>
                        <td><?= htmlspecialchars($habitacion['tipo_habitacion']) ?></td>
                        <td><?= htmlspecialchars($habitacion['capacidad']) ?></td>
                        <td><?= htmlspecialchars($habitacion['precio_por_noche']) ?></td>
                        <td>
                            <span class="status 
                                <?= 
                                    $habitacion['estado'] === 'Disponible' ? 'active' : 
                                    ($habitacion['estado'] === 'Ocupada' ? 'completed' : 'canceled') 
                                ?>">
                                <?= htmlspecialchars($habitacion['estado']) ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($habitacion['servicios_incluidos']) ?></td>
                        <td><?= htmlspecialchars($habitacion['vista']) ?></td>
                        <td><?= htmlspecialchars($habitacion['tamano_m2']) ?></td>
                        <td><?= htmlspecialchars($habitacion['piso']) ?></td>
                        <td><button class="btn-edit">Editar</button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="roomForm" class="form-popup">
    <form method="post" class="form-container">
        <span class="close-btn" onclick="closeForm()">×</span>
        <h2>Agregar Habitación</h2>
        
        <label>Número de Habitación:</label>
        <select name="numero_habitacion" required>
            <?php
            for ($i = 1; $i <= 50; $i++) {
                if (!in_array($i, $habitaciones_ocupadas)) {
                    echo "<option value=\"$i\">$i</option>";
                }
            }
            ?>
        </select>

        <label>Tipo de Habitación:</label>
        <input type="text" name="tipo_habitacion" required>

        <label>Capacidad:</label>
        <input type="number" name="capacidad" min="1" required>

        <label>Precio por Noche (Soles):</label>
        <input type="number" name="precio_por_noche" step="0.01" required>

        <!-- Elimina o comenta esta parte del formulario -->
<!--
<label>Estado:</label>
<select name="estado" required>
    <option value="Disponible">Disponible</option>
    <option value="Ocupada">Ocupada</option>
    <option value="Mantenimiento">Mantenimiento</option>
</select>
-->


        <label>Servicios Incluidos:</label>
        <select name="servicios_incluidos" required>
            <option value="wifi">Wifi</option>
            <option value="tv">Tv</option>
            <option value="minibar">Mini bar</option>
        </select>

        <label>Vista:</label>
        <input type="text" name="vista" placeholder="Ej: Vista al Mar">

        <label>Tamaño (m²):</label>
        <input type="number" name="tamano_m2" required>

        <label>Piso:</label>
        <input type="number" name="piso" required>

        <button type="submit" class="btn-submit">Agregar Habitación</button>
    </form>
</div>

<script src="../js/habitaciones.js"></script>
<?php include '../partes/footeradmin.php'; ?>
