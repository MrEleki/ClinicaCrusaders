<?php
session_start();
require 'db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Obtener especialidades desde la base de datos
$especialidades_query = $conexion->query("SELECT id, especializacion FROM especializacion");
$especialidades = $especialidades_query->fetch_all(MYSQLI_ASSOC);

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente_id = $_SESSION['user_id'];
    $doctor_id = $_POST['doctor_id'] ?? null;
    $especialidad_id = $_POST['especialidad_id'] ?? null;
    $fecha_cita = $_POST['fecha_cita'] ?? null;
    
    // Validaciones
    $errors = [];
    
    // 1. Verificar que no sea una fecha pasada
    $fecha_actual = date('Y-m-d');
    if ($fecha_cita < $fecha_actual) {
        $errors[] = "No puedes agendar citas para días pasados.";
    }
    
    // 2. Verificar disponibilidad del doctor para la fecha
    $disponibilidad_query = $conexion->prepare("
        SELECT id FROM cita 
        WHERE doctor = ? AND fecha = ?
    ");
    $disponibilidad_query->bind_param("is", $doctor_id, $fecha_cita);
    $disponibilidad_query->execute();
    $disponibilidad_query->store_result();
    
    if ($disponibilidad_query->num_rows > 0) {
        $errors[] = "El doctor ya tiene una cita agendada para esta fecha.";
    }
    $disponibilidad_query->close();
    
    // Si no hay errores, insertar la cita
    if (empty($errors)) {
        // Obtener el consultorio del doctor seleccionado
        $stmt_doctor = $conexion->prepare("SELECT consultorio FROM doctores WHERE id = ?");
        $stmt_doctor->bind_param("i", $doctor_id);
        $stmt_doctor->execute();
        $result_doctor = $stmt_doctor->get_result();
        $doctor = $result_doctor->fetch_assoc();
        $consultorio_id = $doctor['consultorio'];
        $stmt_doctor->close();
        
        $estado_inicial = 1; // Asumiendo que 1 es "pendiente" en estado_cita
        
        $insert_query = $conexion->prepare("
            INSERT INTO cita (cliente, doctor, estado, consultorio, fecha)
            VALUES (?, ?, ?, ?, ?)
        ");
        $insert_query->bind_param(
            "iiiss", 
            $cliente_id, 
            $doctor_id, 
            $estado_inicial,
            $consultorio_id,
            $fecha_cita
        );
        
        if ($insert_query->execute()) {
            header("Location: mis_citas.php?success=1");
            exit();
        } else {
            $errors[] = "Error al agendar la cita: " . $conexion->error;
        }
        $insert_query->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Cita</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { font-family: 'Roboto', sans-serif; line-height: 1.6; margin: 0; padding: 0; background-color: #f5f5f5; }
        .container { max-width: 800px; margin: 20px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { color: #2c3e50; text-align: center; margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #34495e; }
        select, input[type="date"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 16px; }
        button { background-color: #395c78; color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; width: 100%; }
        button:hover { background-color: #2980b9; }
        .error { color: #e74c3c; margin-bottom: 20px; padding: 10px; background-color: #fadbd8; border-radius: 4px; }
        .success { color: #27ae60; margin-bottom: 20px; padding: 10px; background-color: #d5f5e3; border-radius: 4px; }
        .consultorio-info { 
            padding: 10px; 
            background-color: #e9ecef; 
            border-radius: 4px; 
            margin-top: 10px;
            display: none;
        }
    </style>
</head>
<body>
    <?php include 'encabezado.php'; ?>
    <div class="container">
        <h1>Agendar Nueva Cita</h1>
        
        <?php if (!empty($errors)): ?>
            <div class="error">
                <strong>Error:</strong>
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="success">
                Cita agendada exitosamente!
            </div>
        <?php endif; ?>
        
        <form id="citaForm" method="post">
            <div class="form-group">
                <label for="especialidad_id">Especialidad:</label>
                <select id="especialidad_id" name="especialidad_id" required>
                    <option value="">Seleccione una especialidad</option>
                    <?php foreach ($especialidades as $especialidad): ?>
                        <option value="<?php echo $especialidad['id']; ?>">
                            <?php echo htmlspecialchars($especialidad['especializacion']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="doctor_id">Doctor:</label>
                <select id="doctor_id" name="doctor_id" required disabled>
                    <option value="">Primero seleccione una especialidad</option>
                </select>
                <div id="consultorio-info" class="consultorio-info">
                    <strong>Consultorio:</strong> <span id="consultorio-nombre"></span>
                </div>
            </div>
            
            <div class="form-group">
                <label for="fecha_cita">Fecha:</label>
                <input type="date" id="fecha_cita" name="fecha_cita" 
                       min="<?php echo date('Y-m-d'); ?>" required>
            </div>
            
            <button type="submit">Agendar Cita</button>
        </form>
    </div>

    <script>
$(document).ready(function() {
    // Función para cargar doctores
    function cargarDoctores(especialidad_id, doctor_a_seleccionar) {
        if (especialidad_id) {
            $.ajax({
                url: 'obtener_doctores.php',
                type: 'POST',
                data: {especialidad_id: especialidad_id},
                dataType: 'json',
                success: function(doctores) {
                    var options = '<option value="">Seleccione un doctor</option>';
                    
                    // Llenar opciones de doctores
                    $.each(doctores, function(index, doctor) {
                        var selected = (doctor.id == doctor_a_seleccionar) ? 'selected' : '';
                        options += `<option value="${doctor.id}" ${selected}>${doctor.nombre} ${doctor.apellido}</option>`;
                    });
                    
                    $('#doctor_id').html(options).prop('disabled', false);
                    
                    // Mostrar consultorio si hay doctor seleccionado
                    if (doctor_a_seleccionar) {
                        var doctor = doctores.find(d => d.id == doctor_a_seleccionar);
                        if (doctor) {
                            $('#consultorio-nombre').text(doctor.consultorio_nombre);
                            $('#consultorio-info').show();
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error al cargar doctores:", error);
                    $('#doctor_id').html('<option value="">Error al cargar doctores</option>');
                }
            });
        } else {
            $('#doctor_id').html('<option value="">Seleccione una especialidad primero</option>').prop('disabled', true);
            $('#consultorio-info').hide();
        }
    }

    // Cargar doctores al cambiar especialidad
    $('#especialidad_id').change(function() {
        cargarDoctores($(this).val(), null);
    });

    // Cargar doctores al iniciar (si hay especialidad seleccionada)
    var especialidadInicial = $('#especialidad_id').val();
    var doctorInicial = <?php echo $cita_actual['doctor'] ?? 'null'; ?>;
    if (especialidadInicial) {
        cargarDoctores(especialidadInicial, doctorInicial);
    }
});
</script>
</body>
</html>