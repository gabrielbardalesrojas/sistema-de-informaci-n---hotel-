<?php include '../partes/headeradmin.php'; ?>

<?php
// Incluir el archivo de conexión
include '../conexion.php';

// Insertar nuevo empleado si se envía el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["nombre"])) {
    $nombre = $_POST["nombre"];
    $cargo = $_POST["cargo"];
    $departamento = $_POST["departamento"];
    $fecha_contratacion = $_POST["fecha_contratacion"];
    $tipo_contrato = $_POST["tipo_contrato"];
    $salario = $_POST["salario"];
    $estado = $_POST["estado"];

    $sql = "INSERT INTO empleados (nombre_completo, cargo, departamento, fecha_contratacion, tipo_contrato, salario, estado) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$nombre, $cargo, $departamento, $fecha_contratacion, $tipo_contrato, $salario, $estado]);

    header("Location: personales.php");
    exit();
}

// Obtener lista de empleados
$sql = "SELECT * FROM empleados";
$empleados = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="table-container">
    <h1>PERSONALES</h1>
    
    <div class="action-bar">
      
    <button class="btn-add" onclick="openForm()">+ Agregar Empleado</button>
    </div>
    <div class="tabl">
    <table class="reservation-table">
        <thead>
            <tr>
                <th>ID de Empleado</th>
                <th>Nombre Completo</th>
                <th>Cargo</th>
                <th>Departamento</th>
                <th>Fecha de Contratación</th>
                <th>Tipo de Contrato</th>
                <th>Salario Mensual</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($empleados as $empleado): ?>
                <tr>
                    <td><?= htmlspecialchars($empleado['id']) ?></td>
                    <td><?= htmlspecialchars($empleado['nombre_completo']) ?></td>
                    <td><?= htmlspecialchars($empleado['cargo']) ?></td>
                    <td><?= htmlspecialchars($empleado['departamento']) ?></td>
                    <td><?= htmlspecialchars($empleado['fecha_contratacion']) ?></td>
                    <td><?= htmlspecialchars($empleado['tipo_contrato']) ?></td>
                    <td>S/ <?= htmlspecialchars($empleado['salario']) ?></td>
                    <td><span class="status <?= $empleado['estado'] === 'Activo' ? 'active' : 'inactive' ?>"><?= htmlspecialchars($empleado['estado']) ?></span></td>
                    <td><button class="btn-edit">Editar</button></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>

<!-- Formulario de registro flotante -->
<div id="employeeForm" class="form-popup">
    <form  method="post" class="form-container">
        <span class="close-btn" onclick="closeForm()">×</span>
        <h2>Registrar Empleado</h2>
        <label>Nombre Completo:</label>
        <input type="text" name="nombre" required>
        
        <label>Cargo:</label>
        <input type="text" name="cargo" required>

        <label>Departamento:</label>
        <input type="text" name="departamento" required>

        <label>Fecha de Contratación:</label>
        <input type="date" name="fecha_contratacion" required>

        <label>Tipo de Contrato:</label>
        <select name="tipo_contrato" required>
            <option value="Tiempo Completo">Tiempo Completo</option>
            <option value="Medio Tiempo">Medio Tiempo</option>
        </select>

        <label>Salario Mensual:</label>
        <input type="number" name="salario" required>

        <label>Estado:</label>
        <select name="estado" required>
            <option value="Activo">Activo</option>
            <option value="Inactivo">Inactivo</option>
        </select>

        <button type="submit" class="btn-submit">Registrar</button>
    </form>
</div>


  <script src="../js/personales.js"></script>

<?php include '../partes/footeradmin.php'; ?>