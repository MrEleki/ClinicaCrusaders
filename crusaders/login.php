<?php
session_start();
require 'db.php';

// Configuración idéntica a crear_usuario.php
$options = [
    'memory_cost' => 65536,
    'time_cost'   => 4,
    'threads'     => 2
];

$emailUsuario = $_POST['email_usuario'] ?? "";
$contraseña = $_POST['contraseña_usuario'] ?? "";

// Buscar usuario por email
$stmt = $conexion->prepare("SELECT id, nombre, email, password FROM usuario WHERE email = ?");
$stmt->bind_param("s", $emailUsuario);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($id, $nombre, $emailDB, $contraseñaHasheada);
    $stmt->fetch();
    
    // Verificación con Argon2id
    if (password_verify($contraseña, $contraseñaHasheada)) {
        // Verificar si necesita re-hashear (por si cambian los parámetros)
        if (password_needs_rehash($contraseñaHasheada, PASSWORD_ARGON2ID, $options)) {
            $nuevoHash = password_hash($contraseña, PASSWORD_ARGON2ID, $options);
            // Actualizar la contraseña en la base de datos
            $update_stmt = $conexion->prepare("UPDATE usuario SET contraseña = ? WHERE id = ?");
            $update_stmt->bind_param("si", $nuevoHash, $id);
            $update_stmt->execute();
            $update_stmt->close();
        }

        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $emailDB;
        $_SESSION['nombre'] = $nombre;
        header("Location: mis_citas.php");
    } else {
        header('Location: index.php?error=contInv');
    }
} else {
    header('Location: index.php?error=noUser');
}

$stmt->close();
$conexion->close();
exit();
?>
