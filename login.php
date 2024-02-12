

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
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
        
        /*inicio sesion*/
          session_start();
          
          /*Si el usuario pulsa el boton de salir en el menu se elimina el usuario de la variable de sesion y se destruye la session*/
          if(isset($_REQUEST['salir']))
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

              /*Conecto a mi base de datos proyecto con mi usuario y contraseña*/
             $conexion = mysqli_connect("localhost", "julene","julene")
                or die ("No se puede conectar con el servidor");
            
            /*Selecionar la bd*/
             mysqli_select_db($conexion, "tienda")
                 or die ("No se puede seleccionar la base de datos");
             
             
            /* Consulta SQL para verificar usuario y contraseña introducido*/
            $consulta = "SELECT * FROM usuario WHERE usuario = '$usuario_ingresado' AND contrasena = '$contrasena_ingresada'";
            
            $consulta = mysqli_query($conexion, $consulta)
                    or die ("Fallo en la consulta");
       
            /*Sacamos la fila */
            $datosConsulta = mysqli_fetch_assoc($consulta);
             
           
             // Verificar si la consulta devuelve true porque hay resultados y son mas de 0 filas
             if ($consulta && mysqli_num_rows($consulta) > 0) 
             {
                /*Obtengo el rol (invitado,registrado....) de la consulta realizada*/
                $_SESSION['rol']= $datosConsulta['rol'];
                 
                print $_SESSION['rol'];
                 
                // Credenciales válidas, iniciar sesión
                $_SESSION['usuario'] = $usuario_ingresado;
                print $_SESSION['usuario'];
                 
                 
                /*Guardo el nombre del usuario para mostrarle un mensaje de bienvenido*/
                $_SESSION['nombreUsuario'] = $datosConsulta['nombre'];
                
                /*Al comprobar el rol y que todo ha ido bien lleva al menu*/
                header('Location: menu.php');
                
                 exit;
                 
            } 
            else 
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
