<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['especialidad_id'])) {
    $especialidad_id = $_POST['especialidad_id'];
    
    $query = $conexion->prepare("
        SELECT d.id, d.nombre, d.apellido, d.consultorio, c.nombre as consultorio_nombre 
        FROM doctores d
        JOIN consultorio c ON d.consultorio = c.id
        WHERE d.especializacion = ? AND d.disponibilidad = 1
    ");
    
    $query->bind_param("i", $especialidad_id);
    $query->execute();
    $result = $query->get_result();
    
    $doctores = array();
    while ($row = $result->fetch_assoc()) {
        $doctores[] = array(
            'id' => $row['id'],
            'nombre' => $row['nombre'],
            'apellido' => $row['apellido'],
            'consultorio' => $row['consultorio'],
            'consultorio_nombre' => $row['consultorio_nombre']
        );
    }
    
    header('Content-Type: application/json');
    echo json_encode($doctores); // Cambiado para devolver array directo
    $query->close();
}

$conexion->close();
?>