<?php
session_start();
require 'db.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$cliente_id = $_SESSION['user_id'];

// Se añade c.id a la consulta para usarlo en los botones
$query_string = "
    SELECT 
        c.id, 
        c.fecha,
        d.nombre AS doctor_nombre,
        d.apellido AS doctor_apellido,
        e.especializacion AS doctor_especialidad,
        co.nombre AS consultorio_nombre,
        co.direccion AS consultorio_direccion,
        ec.estado AS estado_cita
    FROM 
        cita AS c
    JOIN 
        doctores AS d ON c.doctor = d.id
    JOIN 
        consultorio AS co ON c.consultorio = co.id
    JOIN 
        estado_cita AS ec ON c.estado = ec.id
    JOIN
        especializacion AS e ON d.especializacion = e.id
    WHERE 
        c.cliente = ?
    ORDER BY 
        c.fecha DESC
";

$stmt = $conexion->prepare($query_string);
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$citas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Citas</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="citas.css">
</head>
<body>
    <?php include 'encabezado.php'; ?>

    <div class="container">
        <h1>Mis Citas Agendadas</h1>
        
        <?php if (empty($citas)): ?>
            <p class="no-citas">Aún no tienes ninguna cita agendada.</p>
        <?php else: ?>
            <div style="overflow-x:auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Doctor</th>
                            <th>Especialidad</th>
                            <th>Consultorio</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($citas as $cita): ?>
                            <tr>
                                <td><?php echo htmlspecialchars(date("d/m/Y", strtotime($cita['fecha']))); ?></td>
                                <td><?php echo htmlspecialchars($cita['doctor_nombre'] . ' ' . $cita['doctor_apellido']); ?></td>
                                <td><?php echo htmlspecialchars($cita['doctor_especialidad']); ?></td>
                                <td>
                                    <?php echo htmlspecialchars($cita['consultorio_nombre']); ?>
                                    <div class="direccion-consultorio">
                                        <?php echo htmlspecialchars($cita['consultorio_direccion']); ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="estado <?php echo strtolower(htmlspecialchars($cita['estado_cita'])); ?>">
                                        <?php echo htmlspecialchars($cita['estado_cita']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="reagendar_cita.php?id=<?php echo $cita['id']; ?>" class="btn-accion reagendar">Reagendar</a>
                                    <a href="cancelar_cita.php?id=<?php echo $cita['id']; ?>" class="btn-accion cancelar" onclick="return confirm('¿Estás seguro de que quieres cancelar esta cita?');">Cancelar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <div class="info-estados">
            <h4>Sobre los Estados de la Cita</h4>
            <p>El doctor debe confirmar que puede recibirte ese día. A continuación el significado de cada estado:</p>
            <ul>
                <li><strong>Pendiente:</strong> Si está pendiente, el doctor aún no ha aceptado la cita.</li>
                <li><strong>Aceptada:</strong> Si está aceptada, el doctor la aceptó.</li>
                <li><strong>Cancelada:</strong> Si está cancelada, el doctor no puede aceptar esa cita.</li>
            </ul>
        </div>

        <a href="agendar_cita.php" class="btn-agendar">Agendar Nueva Cita</a>
    </div>
</body>
</html>