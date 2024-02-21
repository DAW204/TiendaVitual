<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Registro</title>
        <style>
            * {
                font-family: Helvetica, Verdana, sans-serif;
                text-align: center;
            }
            
            input {
                border-radius: 4px;
            }
            
            input:hover {
                background-color: lightskyblue;
            }
            
            h1 {
                color: dodgerblue;
            }
            
           
        </style>
    </head>
    <body>
        <?php
        /* Inlcuimos la conexion a la BD */
        include 'conexion.php';

        // Obtenemos la conexión utilizando la función getConn() (definida en el php de conexion a la BD)
        $conexion = getConn();


        /* Si se ha enviado los datos del formulario, usuario y contraseña del nuevo usuario entra en el if */
        if (isset($_POST['enviar']))
        {
            /* Verifico que usuario y contraseña no esten vacios y despues en la cosulta realizo el insert con los datos requeriddos */
            if (isset($_REQUEST['nombre']) && isset($_REQUEST['apellido']) && isset($_REQUEST['dni']) && isset($_REQUEST['usuario']) 
                    && isset($_REQUEST['contrasena']) && isset($_REQUEST['email']) && isset($_REQUEST['telefono']))
            {


                $consulta = "INSERT INTO usuario(nombre, apellido, usuario, contrasena, email, telefono, dni) "
                        . "VALUES ('" . $_REQUEST['nombre'] . "','" . $_REQUEST['apellido'] . "', '" . $_REQUEST['usuario'] 
                        . "','" . $_REQUEST['contrasena'] . "','" . $_REQUEST['email'] . "','" . $_REQUEST['telefono'] . "','" . $_REQUEST['dni'] . "')";


                $consulta = mysqli_query($conexion, $consulta)
                        or die("Fallo en la consulta");

                print "Se ha creado el usuario";
            } else
            {
                print 'No se ha rellenado alguno de los campos';
            }
        }
        ?>


        <form name="form" action="" method="POST" enctype="multipart/form-data">

            <h1>REGISTRO</h1>
            
            <hr>
            <h3>Introduce tus datos</h3>
            <br>
            Nombre:
            <br>
            <input type="text" name="nombre" value="" />
            <br><br>

            Apellido:
            <br>
            <input type="text" name="apellido" value="" />
            <br><br>
            
            DNI/NIF:
            <br>
            <input type="text" name="dni" pattern="^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$" value="" />
            <br><br>

            Nombre usuario (login):
            <br>
            <input type="text" name="usuario" value="" />
            <br><br>
            
            Contraseña:
            <br>
            <input type="password" name="contrasena" value="" />
            <br><br>

            Email:<br>
            <input type="text" name="email" value="" placeholder="example@example.com" /><br><br>

            Telefono:<br>
            <input type="tel" id="telefono" name="telefono" pattern="[6789]\d{8}" placeholder="Ej. 612345678" required>
            <br><br>
           
            <input type="submit" value="Registrar" name="enviar" /><br><br>
            <hr>
            <p>¿No te quieres registrar, o ya lo hiciste con éxito?</p>
            <a href="login.php"><input type="button" value="Volver a Login" name="cancelar" /></a>
        </form>
    </body>
</html>
