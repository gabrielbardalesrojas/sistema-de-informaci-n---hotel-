<?php include '../partes/headeradmin.php'; ?>

<?php
// Incluir el archivo de conexión
include '../conexion.php';

// Insertar nuevo cliente si se envía el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["nombre_completo"])) {
    $nombre_completo = $_POST["nombre_completo"];
    $dni = $_POST["dni"];
    $telefono = $_POST["telefono"];
    $correo_electronico = $_POST["correo_electronico"];

    // Obtener la última estancia y contar el número de reservas del cliente
    $stmt = $conn->prepare("SELECT MAX(check_out) AS ultima_estancia, COUNT(*) AS num_reservas FROM reservas WHERE cliente_id = ?");
    $stmt->execute([$cliente_id]);
    $reservaData = $stmt->fetch(PDO::FETCH_ASSOC);

    $ultima_estancia = $reservaData['ultima_estancia'] ?: NULL;
    $num_reservas = $reservaData['num_reservas'];

    // Estado de reserva por defecto
    $estado_reserva = 'Activa';

    // Insertar el cliente con los datos calculados
    $sql = "INSERT INTO clientes (nombre_completo, dni, telefono, correo_electronico, ultima_estancia, num_reservas, estado_reserva) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$nombre_completo, $dni, $telefono, $correo_electronico, $ultima_estancia, $num_reservas, $estado_reserva]);

    header("Location: clientes.php");
    exit();
}

// Obtener lista de clientes
$sql = "SELECT * FROM clientes";
$clientes = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="table-container">
    <h1>CLIENTES</h1>
    <div class="action-bar">
        <button class="btn-add" onclick="openForm()">+ Agregar Cliente</button>
    </div>
    <div class="tabl">
        <table class="reservation-table">
            <thead>
                <tr>
                    <th>Nombre del cliente</th>
                    <th>DNI</th>
                    <th>Teléfono</th>
                    <th>Correo Electrónico</th>
                    <th>Última Estancia</th>
                    <th>Nº de Reservas</th>
                    <th>Estado de Reserva</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?= htmlspecialchars($cliente['nombre_completo']) ?></td>
                        <td><?= htmlspecialchars($cliente['dni']) ?></td>
                        <td><?= htmlspecialchars($cliente['telefono']) ?></td>
                        <td><?= htmlspecialchars($cliente['correo_electronico']) ?></td>
                        <td><?= htmlspecialchars($cliente['ultima_estancia']) ?></td>
                        <td><?= htmlspecialchars($cliente['num_reservas']) ?></td>
                        <td>
                            <span class="status 
                                <?= 
                                    $cliente['estado_reserva'] === 'Activa' ? 'active' : 
                                    ($cliente['estado_reserva'] === 'Cancelada' ? 'canceled' : 'completed') 
                                ?>">
                                <?= htmlspecialchars($cliente['estado_reserva']) ?>
                            </span>
                        </td>
                        <td><button class="btn-edit">Editar</button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="clientForm" class="form-popup">
    <form method="post" class="form-container">
        <span class="close-btn" onclick="closeForm()">×</span>
        <h2>Registrar Nuevo Cliente</h2>

        <label>Nombre Completo:</label>
        <input type="text" name="nombre_completo" required>

        <label>DNI:</label>
        <input type="text" name="dni" required>

        <label>Teléfono:</label>
        <input type="tel" name="telefono" required pattern="[0-9]{9}">

        <label>Correo Electrónico:</label>
        <input type="email" name="correo_electronico" required>

        <button type="submit" class="btn-submit">Registrar</button>
    </form>
</div>
<script src="../js/cliente.js"></script>
<?php include '../partes/footeradmin.php'; ?>
