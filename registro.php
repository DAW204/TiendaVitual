<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        /* Conecto con la base de datos */
        $conexion = mysqli_connect("localhost", "julene", "julene")
                or die("No se puede conectar con el servidor");

        /* Selecionar la bd */
        mysqli_select_db($conexion, "tienda")
                or die("No se puede seleccionar la base de datos");


        /* Si se ha enviado los datos del formulario, usuario y contraseña del nuevo usuario entra en el if */
        if (isset($_POST['enviar']))
        {
            /* Verifico que usuario y contraseña no esten vacios y despues en la cosulta realizo el insert con los datos requeriddos */
            if (isset($_REQUEST['nombre']) && isset($_REQUEST['apellido']) && isset($_REQUEST['usuario']) 
                    && isset($_REQUEST['contrasena']) && isset($_REQUEST['email']) && isset($_REQUEST['telefono']))
            {


                $consulta = "INSERT INTO usuario(nombre, apellido, usuario, contrasena, email, telefono) "
                        . "VALUES ('" . $_REQUEST['nombre'] . "','" . $_REQUEST['apellido'] . "', '" . $_REQUEST['usuario'] 
                        . "','" . $_REQUEST['contrasena'] . "','" . $_REQUEST['email'] . "','" . $_REQUEST['telefono'] . "')";


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
            <br><br>

            Nombre:

            <br><br>
            <input type="text" name="nombre" value="" />
            <br><br>

            Apellido:
            <br><br>
            <input type="text" name="apellido" value="" />
            <br><br>

            Nombre usuario (login):

            <br><br>
            <input type="text" name="usuario" value="" />
            <br><br>
            
            Contraseña:
            <br><br>
            <input type="password" name="contrasena" value="" />
            <br><br>

            Email:<br><br>
            <input type="text" name="email" value="" placeholder="example@example.com" /><br><br>

            
            Telefono:<br><br>
            <input type="tel" id="telefono" name="telefono" pattern="[6789]\d{8}" placeholder="Ej. 612345678" required>
            <br><br>
            
            
            <a href="login.php"><input type="button" value="Cancelar" name="cancelar" /></a>
            <input type="submit" value="Registrar" name="enviar" /><br><br>

        </form>
    </body>
</html>
