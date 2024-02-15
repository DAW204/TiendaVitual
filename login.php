

<html>
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
    </head>
    <body>


        <form name="form" action="" method="POST" enctype="multipart/form-data">

            <h1>LOGIN</h1>
            <br><br>

            Nombre usuario:

            <br><br>
            <input type="text" name="usuario" value="" />
            <br><br>

            Contraseña:
            <br><br>
            <input type="password" name="contrasena" value="" />
            <br><br>

            <input type="submit" value="enviar" name="enviar" />
            <a href="registro.php"><input type="button" value="registro" name="registro" /></a>
        </form>

        <?php
        /* inicio sesion */
        session_start();

        /* Si el usuario pulsa el boton de salir en el menu se elimina el usuario de la variable de sesion y se destruye la session */
        if (isset($_REQUEST['salir']))
        {
            unset($_SESSION['usuario']);
            session_destroy();
        }

        // Verificar si el formulario  de logeo ha sido enviado
        if (isset($_POST['enviar']))
        {
            // Recuperar los datos del formulario
            $usuario_ingresado = $_POST['usuario'];
            $contrasena_ingresada = $_POST['contrasena'];

            /* Inlcuimos la conexion a la BD */
            include 'conexion.php';

            // Obtenemos la conexión utilizando la función getConn() (definida en el php de conexion a la BD)
            $conexion = getConn();


            /* Consulta SQL para verificar usuario y contraseña introducido */
            $consulta = "SELECT nombre,id_usuario,rol FROM usuario WHERE usuario = '$usuario_ingresado' AND contrasena = '$contrasena_ingresada'";

            $consulta = mysqli_query($conexion, $consulta)
                    or die("Fallo en la consulta");

            /* Sacamos la fila */
            $datosConsulta = mysqli_fetch_assoc($consulta);


            // Verificar si la consulta devuelve true porque hay resultados y son mas de 0 filas
            if ($consulta && mysqli_num_rows($consulta) > 0)
            {
                /* Obtengo el rol (invitado,registrado....) de la consulta realizada */
                $_SESSION['rol'] = $datosConsulta['rol'];

                // Credenciales válidas, iniciar sesión
                $_SESSION['usuario'] = $usuario_ingresado;

                /* Guardo el nombre del usuario para mostrarle un mensaje de bienvenido */
                $_SESSION['nombreUsuario'] = $datosConsulta['nombre'];

                /* Guardo en variable de sesion el id del usuario para usarlo mas tarde */
                $_SESSION['id_usuario'] = $datosConsulta['id_usuario'];

                /* Si el usuario que se logea tiene como rol invitado, solo le redirigira a previsualizacion donde podra solo observar los productos */
                if ($_SESSION['rol'] == 'invitado')
                {

                    header('Location: previsualizacion.php');
                    exit;
                }
                /* En caso de que el rol del usuario sea comprador/vendedor accedera al menu normal */
                header('Location: menu.php');

                exit;
            } else
            {
                // Credenciales inválidas, mostrar mensaje de error
                echo "Credenciales incorrectas. Por favor, inténtalo de nuevo.";
            }

            // Cerrar la conexión a la base de datos
            $conexion->close();
        }
        ?>
    </body>
</html>
