<!--ESTA ES LA PAGINA DE INICIO DE SESION Y LA PRINCIPAL (INDEX)-->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="index-body">

    <?php
        include 'encabezado.php';
    ?>

    <div class="index-div-sesion">
        <h1 class="index-h1">Iniciar Sesión</h1>
        <form action="login.php" method="post" class="index-form">
                <input type="email" minlength="5" maxlength="100" class="index-input" name="email_usuario" placeholder="Ingresa tu correo electrónico" required>
                <input type="password" minlength="3" maxlength="20" class="index-input" name="contraseña_usuario" placeholder="Ingresa tu contraseña" required>
                <input type="submit" value="Entrar" class="index-input-submit">

        </form>
    </div>

    <p class="index-p-error index-errorMsg"> <!-- muestra mensaje de error por url (ver login.php) -->
        <?php
            if(isset($_GET['error'])){
                switch($_GET['error']){
                    case "noUser":
                        echo "<u>Usuario no encontrado</u>";
                    break;
                    case "contInv":
                        echo "<u>Contraseña incorrecta</u>";
                    break;
                    case "usrCreado":
                        echo "<u>Usuario creado correctamente, inicie sesión</u>";
                    break;
                }
            }
        ?>
    </p>

    <p class="index-p">No te has registrado? <a href="registro.php" target="_self" class="index-a">registrate aqui</a></p>

    <!-- Bootstrap JS Bundle (opcional para componentes interactivos) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
 