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
        
        /*Recojo el id del usuario procedente de una varibale de sesion, en una variable local*/
        $id_usuario = $_SESSION['id_usuario'];
        
        /* Conectamos con la base de datos e imprimos el mensaje correspondiente si no es posible */
        $conexion = mysqli_connect("localhost", "julene", "julene")
                or die("No se puede conectar con el servidor");

        /* Selecionar la bd */
        mysqli_select_db($conexion, "tienda")
                or die("No se puede seleccionar la base de datos");
        
       
        /*Realizamos un insert con el id del usuario que ha solicitado el cambio de rol  y el estado en pendiente*/
        $consulta = "INSERT INTO solicitudes (id_usuario,estado) VALUES ($id_usuario, 'pendiente') ;";
        
        $consulta = mysqli_query($conexion, $consulta)
                or die("Fallo en la consulta");
        
        
        ?>
    </body>
</html>
