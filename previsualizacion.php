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

        <h1>ERES INVITADO</h1>


        <form action="previsualizacion.php" method="POST">
            <input type="submit" name="solicitar" value="CAMBIAR ROL" />
            <input type="submit" name="volver" value="LOGIN" />
        </form>


        <?php
        /* inicio sesion */
        session_start();

        /*Inlcuimos la conexion a la BD*/
        include 'conexion.php';

        // Obtenemos la conexión utilizando la función getConn() (definida en el php de conexion a la BD)
        $conexion = getConn();
        
        /*Recojemos el id del usuario de la variable de sesion*/
        $id_usuario = $_SESSION['id_usuario'];

        /*Si el usuario invitado pulsa solicitar, entra en el if y se realiza un insert en la tabla de solicitudes, donde quedara 
          su peticion de cambio de rol registrada con el estado en pendiente para que despues el vendedor efectue el cambio a comprador*/
        if (isset($_POST['solicitar']))
        {

            $consulta = "INSERT INTO solicitudes (id_usuario) VALUES ($id_usuario);";

            $consulta = mysqli_query($conexion, $consulta)
                    or die("Fallo en la consulta");
//            
//           
//            /*MIRAR EN OTRO MOMENTO*/
//            if ($consulta)
//            {
//                
//                echo "Se ha realizado la solicitud correctamente.";
//                
//            }else
//            {
//                die("Fallo en la consulta: " . mysqli_error($conexion));
//            }
        }


        /* Si el usuario invitado pulsa el boton para cambiar de rol le redirigira al login */
        if (isset($_POST['volver']))
        {

            /* Redirigimos a la página especificada, en este caso el login*/
            header("Location: login.php");
            exit; 
        }
        ?>
    </body>
</html>
