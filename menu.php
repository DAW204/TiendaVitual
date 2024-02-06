<!DOCTYPE html>

<?php
session_start();
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        
        /*Verifico que hay un usuario guardado en la variable de sesion, lo cual me indica si se han logeado, si este es el caso entra en el if
        y se hace la comprobacion del rol para redirigir al usuario a su menu personalizado en funcion de este */
        if (isset($_SESSION['usuario']))
        {
            
            $usuario_ingresado = $_SESSION['usuario'];
            $rol = $_SESSION['rol'];
            $nombreUsuario = $_SESSION['nombreUsuario'];
            
            
            $conexion = mysqli_connect("localhost", "julene", "julene")
                    or die("No se puede conectar con el servidor");

            /* Selecionar la bd */
            mysqli_select_db($conexion, "tienda")
                    or die("No se puede seleccionar la base de datos");
            ?> 

            <!-- Mensaje de bienvenida personalizado con el nombre del usuario -->
            <h1>Menu </h1>
            <hr>
            <?php
            /*Mensaje de bienvenida en funcion del usuario que sea usando la variable de sesion*/
            print "Bienvenido usuario: " .$nombreUsuario;
            ?>
            <br><br>
            
            <!-- Formulario que alberga los diferentes botones que se mostraran en funcion del rol que posea el ususario-->
            <form name="form" action="general.php" method="POST" enctype="multipart/form-data">


                <!-- En funcion del rol que tenga el usuario podra hacer diferentes cosas el 1(Profesores), 2 alumnos y el 3 administradores
                quienes podran realizar todas la funcionalidades, mediante el link les lleva al correspondiente php-->
                
                <!--Los administradores podran consultar,insertar y eliminar, los profesores(1) podran Consultar e insertar, y los alumnos solo podran Consultar-->

                <input type="submit" value="Consultar" name="enviar" />

                <?php
                if ($rol == 3)
                {
                    ?>

                    <input type="submit" value="Eliminar" name="enviar" />


                    <?php
                }
                if ($rol == 3 || $rol == 1)
                {
                    ?>

                    <input type="submit" value="Insertar" name="enviar" />

                    <?php
                }
                ?>




            </form>
            
            <!-- Formulario con un boton para salir comun a todos los usuarios, al pulsarlo se redirige al login y se cierra la sesion(Gestionado en el login el cierre de sesion) -->
            <form name="form" action="login.php" method="POST" enctype="multipart/form-data">
                
                <input type="submit" value="salir" name="salir" />
                
            </form>
            
            <hr>  

            <?php
        }else
        {
            /*En caso de que no exista ningun usuario en la variable de sesion indica que nadie se ha logeado por lo tanto le prohibimos el acceso y le ofrecemos 
            volver al login para que se autentique correctamente para acceder*/
            print "ACCESO NO PERMITIDO";
            ?>
            <a href="login.php">Volver al Login</a>

            <?php
        }
        ?>

    </body>
</html>
