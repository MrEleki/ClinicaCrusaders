<?php
$host = "localhost"; 
$usuario = "debian-sys-maint"; // tu usuario de MySQL, usualmente es root (al menos si va por defecto la configuracion)
$contraseña = "vE0qVrJ4incC237L";     // tu contraseña de MySQL. seguramente sea un espacio vacío, en caso de que no pos ponga su contraseña y ya mijo
$dataBase = "clinica_crusaders"; //nombre de la base de datos. en la carpeta con archivos estaré adjuntandola para importarla al xampp. usa este mismo nombre, o si se lo cambias ps cambialo acá tambien

$conexion = new mysqli($host, $usuario, $contraseña, $dataBase);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>
