<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Obtener IDs de la URL y de la sesión
$cita_id = $_GET['id'] ?? null;
$cliente_id = $_SESSION['user_id'];

// Asegurarse de que el ID de la cita fue proporcionado
if ($cita_id) {
    // Preparar y ejecutar la consulta de eliminación
    // no pueda borrar las citas de otro cambiando el ID en la URL.
    $stmt = $conexion->prepare("DELETE FROM cita WHERE id = ? AND cliente = ?");
    $stmt->bind_param("ii", $cita_id, $cliente_id);
    $stmt->execute();
    $stmt->close();
}

$conexion->close();

// Redirigir al usuario de vuelta a la lista de citas
header("Location: mis_citas.php");
exit();
?>