<!--ESTA ES LA PAGINA DE REGISTRO DE USUARIO-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Registarse</title>
</head>

<body class="registro-body">

    <?php
        include 'encabezado.php';
    ?>

    <div class="registro-div-formulario">
        <h1 class="registro-h1">Registrarse</h1>
        <form action="crear_usuario.php" method="POST">
            <div class="registro-div-nombre-apellido-flexbox">
                <input type="text" minlength="3" maxlength="100" placeholder="Ingrese su nombre" name="nombre" class="registro-input-nombre" required>
                <input type="text" minlength="3" maxlength="100" placeholder="Ingrese su apellido" name="apellido" class="registro-input-apellido" required>
            </div>
            <input type="email" minlength="5" maxlength="100" placeholder="Ingrese su email" name="email" class="registro-input" required>
            <input type="tel" minlength="7" maxlength="15" placeholder="Ingrese su teléfono (opcional)" name="telefono" class="registro-input">
  
            <input type="password" minlength="3" maxlength="20" placeholder="Ingrese la contraseña" name="contraseña" class="registro-input" required>
            <input type="password" minlength="3" maxlength="20" placeholder="Confirme su contraseña" name="confirmacion_contraseña" class="registro-input contraseña" required>
            <input type="submit" value="Registrarse" class="registro-input-submit">
        </form>
    </div>

    <p class="index-errorMsg">
        <?php
            if(isset($_GET['error'])){
                switch($_GET['error']){
                    case "contDif":
                        echo "<u>Las contraseñas no coinciden</u>";
                    break;
                    // case "invAlias": // Este caso ya no es necesario
                    //     echo "<u>Alias invalido</u>";
                    // break;
                    // case "usrExiste": // Este caso ya no es necesario
                    //     echo "<u>El alias ya existe, inicie sesión o elija otro alias</u>";
                    // break;
                    case "emailExiste": // Nuevo caso para email duplicado
                        echo "<u>El email ya está registrado, inicie sesión o use otro email</u>";
                    break;
                }
            }
        ?>
    </p>

    <p class="registro-p">Ya te registraste? <a href="index.php" target="_self" class="registro-a">Inicia sesion aqui</a></p>
</body>
</html>
