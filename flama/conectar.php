<?php
// Incluir archivo de conexión a la base de datos
include 'conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Buscar el usuario en la base de datos
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Iniciar sesión exitoso
        $_SESSION['username'] = $user['username'];
        header("Location: admin/reservas.php"); // Redirige a la página principal
    } else {
        header("Location: login.php?error=1"); // Redirige a login con un parámetro de error
        exit();
    }
}

?>