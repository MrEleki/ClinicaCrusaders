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

$cliente_id = $_SESSION['user_id'];
$cita_id = $_GET['id'] ?? null;
$cita_actual = null;
$error = null;

// Si no hay ID, no se puede reagendar. Redirigir.
if (!$cita_id) {
    header("Location: mis_citas.php");
    exit();
}

// Buscar los datos de la cita actual para pre-llenar el formulario
$stmt = $conexion->prepare("
    SELECT c.id, c.doctor, c.consultorio, c.fecha, d.especializacion, d.consultorio as doctor_consultorio 
    FROM cita c 
    JOIN doctores d ON c.doctor = d.id
    WHERE c.id = ? AND c.cliente = ?
");
$stmt->bind_param("ii", $cita_id, $cliente_id);
$stmt->execute();
$result = $stmt->get_result();
$cita_actual = $result->fetch_assoc();
$stmt->close();

// Si la cita no existe o no pertenece al usuario, redirigir.
if (!$cita_actual) {
    header("Location: mis_citas.php");
    exit();
}

// Obtener especialidades
$especialidades_query = $conexion->query("SELECT id, especializacion FROM especializacion");
$especialidades = $especialidades_query->fetch_all(MYSQLI_ASSOC);

// Procesar el formulario cuando se envía para ACTUALIZAR
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar que todos los campos requeridos estén presentes
    if (!isset($_POST['cita_id']) || !isset($_POST['especialidad_id']) || !isset($_POST['doctor_id']) || 
        !isset($_POST['fecha_cita'])) {
        $error = "Todos los campos son requeridos";
    } else {
        // Recuperar datos del formulario
        $cita_id = $_POST['cita_id'];
        $especialidad_id = $_POST['especialidad_id'];
        $doctor_id = $_POST['doctor_id'];
        $fecha_cita = $_POST['fecha_cita'];
        
        // Obtener el consultorio del doctor seleccionado
        $stmt_doctor = $conexion->prepare("SELECT consultorio FROM doctores WHERE id = ?");
        $stmt_doctor->bind_param("i", $doctor_id);
        $stmt_doctor->execute();
        $result_doctor = $stmt_doctor->get_result();
        $doctor = $result_doctor->fetch_assoc();
        $consultorio_id = $doctor['consultorio'];
        $stmt_doctor->close();
        
        try {
            // Actualizar la cita en la base de datos
            $stmt = $conexion->prepare("UPDATE cita SET doctor = ?, consultorio = ?, fecha = ? WHERE id = ?");
            $stmt->bind_param("iisi", $doctor_id, $consultorio_id, $fecha_cita, $cita_id);
            $stmt->execute();
            
            // Redirigir a mis_citas.php después de actualizar
            header("Location: mis_citas.php?success=1");
            exit();
        } catch (Exception $e) {
            $error = "Error al actualizar la cita: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reagendar Cita</title>
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
        <h1>Reagendar Cita</h1>

        <?php if ($error): ?>
            <div class="error">
                <strong>Error:</strong> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form id="citaForm" method="post">
            <input type="hidden" name="cita_id" value="<?php echo $cita_actual['id']; ?>">
            
            <div class="form-group">
                <label for="especialidad_id">Especialidad:</label>
                <select id="especialidad_id" name="especialidad_id" required>
                    <option value="">Seleccione una especialidad</option>
                    <?php foreach ($especialidades as $especialidad): ?>
                        <option value="<?php echo $especialidad['id']; ?>" <?php echo ($especialidad['id'] == $cita_actual['especializacion']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($especialidad['especializacion']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="doctor_id">Doctor:</label>
                <select id="doctor_id" name="doctor_id" required>
                    <option value="">Primero seleccione una especialidad</option>
                </select>
                <div id="consultorio-info" class="consultorio-info">
                    <strong>Consultorio:</strong> <span id="consultorio-nombre"></span>
                </div>
            </div>

            <div class="form-group">
                <label for="fecha_cita">Fecha:</label>
                <input type="date" id="fecha_cita" name="fecha_cita" value="<?php echo htmlspecialchars($cita_actual['fecha']); ?>" min="<?php echo date('Y-m-d'); ?>" required>
            </div>

            <button type="submit">Confirmar Cambios</button>
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
                success: function(response) {
                    try {
                        var options = '<option value="">Seleccione un doctor</option>';
                        
                        // Verificar si response es un array
                        if (Array.isArray(response)) {
                            $.each(response, function(index, doctor) {
                                // Verificar que las propiedades existan
                                var nombre = doctor.nombre || '';
                                var apellido = doctor.apellido || '';
                                var selected = (doctor.id == doctor_a_seleccionar) ? 'selected' : '';
                                
                                options += `<option value="${doctor.id}" ${selected} 
                                           data-consultorio="${doctor.consultorio}"
                                           data-consultorio-nombre="${doctor.consultorio_nombre}">
                                           ${nombre} ${apellido}</option>`;
                            });
                        } else {
                            console.error("La respuesta no es un array:", response);
                            options = '<option value="">Error al cargar doctores</option>';
                        }
                        
                        $('#doctor_id').html(options).prop('disabled', false);
                        
                        // Mostrar consultorio si hay doctor seleccionado
                        if (doctor_a_seleccionar) {
                            var doctorSeleccionado = response.find(function(d) {
                                return d.id == doctor_a_seleccionar;
                            });
                            if (doctorSeleccionado) {
                                $('#consultorio-nombre').text(doctorSeleccionado.consultorio_nombre);
                                $('#consultorio-info').show();
                            }
                        }
                    } catch (e) {
                        console.error("Error procesando doctores:", e);
                        $('#doctor_id').html('<option value="">Error al cargar doctores</option>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX:", status, error);
                    $('#doctor_id').html('<option value="">Error al conectar con el servidor</option>');
                }
            });
        } else {
            $('#doctor_id').html('<option value="">Seleccione una especialidad primero</option>')
                          .prop('disabled', true);
            $('#consultorio-info').hide();
        }
    }

    // Cargar doctores al cambiar especialidad
    $('#especialidad_id').change(function() {
        cargarDoctores($(this).val(), null);
    });

    // Cargar doctores al iniciar (si hay especialidad seleccionada)
    var especialidadInicial = $('#especialidad_id').val();
    var doctorInicial = <?php echo isset($cita_actual['doctor']) ? $cita_actual['doctor'] : 'null'; ?>;
    if (especialidadInicial) {
        cargarDoctores(especialidadInicial, doctorInicial);
    }
});
</script>
</body>
</html>