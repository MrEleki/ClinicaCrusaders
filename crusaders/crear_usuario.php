<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require 'db.php';

// Configuración para Argon2id
$options = [
    'memory_cost' => 65536,    // 64MB de memoria
    'time_cost'   => 4,        // 4 iteraciones
    'threads'     => 2         // 2 hilos
];

// Datos del formulario
$nombre = $_POST['nombre'] ?? "";
$apellido = $_POST['apellido'] ?? "";
$email = $_POST['email'] ?? "";
$telefono = $_POST['telefono'] ?? "";
$contraseña = $_POST['contraseña'] ?? "";
$confirmar_contraseña = $_POST['confirmacion_contraseña'] ?? "";

// Validaciones básicas
if ($contraseña !== $confirmar_contraseña) {
    header("Location: registro.php?error=contDif");
    exit();
}

// Verificar si el email ya existe
$verif_email = $conexion->prepare("SELECT id FROM usuario WHERE email = ?");
if (!$verif_email) {
    die("Error en la preparación de la consulta: " . $conexion->error);
}
$verif_email->bind_param("s", $email);
$verif_email->execute();
$verif_email->store_result();

if ($verif_email->num_rows > 0) {
    header("Location: registro.php?error=emailExiste");
    $verif_email->close();
    exit();
}
$verif_email->close();

// Encriptación con Argon2id
$contraseña_hash = password_hash($contraseña, PASSWORD_ARGON2ID, $options);
if ($contraseña_hash === false) {
    die("Error al encriptar la contraseña");
}

// Insertar usuario en la base de datos
$stmt = $conexion->prepare("INSERT INTO usuario (nombre, apellido, email, telefono, password, fecha_registro) VALUES (?, ?, ?, ?, ?, CURDATE())");
if (!$stmt) {
    die("Error en la preparación de la consulta de inserción: " . $conexion->error);
}
$stmt->bind_param("sssss", $nombre, $apellido, $email, $telefono, $contraseña_hash);

if ($stmt->execute()) {
    $_SESSION['register_success'] = true;
    header("Location: index.php?success=usrCreado");
} else {
    die("Error al insertar el usuario: " . $stmt->error);
}

$stmt->close();
$conexion->close();
exit();
?>
