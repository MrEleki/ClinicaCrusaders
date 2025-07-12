<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href='https://fonts.googleapis.com/css?family=GFS Didot' rel='stylesheet'>
</head>
<body>
    
    <header class="encabezado-header">
            <?php
                if(isset($_SESSION['username'])){
                    echo '
                        
                        <div class="encabezado-div-logo">
                            <a href="mis_citas.php"><img src="imgs/crusadersLogoBueno.png" alt="Logo de empresa" class="encabezado-img-logo"></a>
                        </div> 
                        <div class="texto-header">
                            Clínica Crusaders
                        </div>
                        
                        <nav class="encabezado-elementos-derecha">
                            <ul class="encabezado-ul">
                                <li class="encabezado-li" style= "padding:0"><a class="encabezado-a"><button class="encabezado-button">'.$_SESSION['nombre'].'</button></a></li>
                            </ul>
                        </nav>
                        <a style="margin-left: 5px;" href="logout.php"><button class="encabezado-button">Cerrar Sesion</button></a>
                        <a style="margin-left: 5px;" href="agendar_cita.php"><button class="encabezado-button">Agendar Cita</button></a>
                    
 
                    
                        '
                    ;
                }elseif(!isset($_SESSION['ussername'])){
                    echo '
                        <div class="encabezado-div-logo">
                            <a href="#"><img src="Imagenes/logo2.png" alt="Logo de empresa" class="encabezado-img-logo"></a>
                        </div>
                        <nav class="encabezado-elementos-derecha">
                            <ul class="encabezado-ul">
                                <li class="encabezado-li"><a href="registro.php" class="encabezado-a">Registrarse</a></li>
                                <li class="encabezado-li"><a href="index.php" class="encabezado-a">Iniciar Sesion</a></li>
                            </ul>
                        </nav>
                    ';
                }
            ?>
    </header>


</body>
</html>


