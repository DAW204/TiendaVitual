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

        <h1>Catalogo de libros</h1>

        <?php
        /* inicio sesion */
        session_start();

        /* Conectamos con la base de datos e imprimos el mensaje correspondiente si no es posible */
        $conexion = mysqli_connect("localhost", "julene", "julene")
                or die("No se puede conectar con el servidor");

        /* Selecionar la bd */
        mysqli_select_db($conexion, "proyecto")
                or die("No se puede seleccionar la base de datos");
        
         $consultaLibros = "select * from libros;";
        
        ?>
        
        
        <form name="enviar" action="carrito" method="POST">
            
            LIBROS DISPONIBLES <BR><BR>
            <input type="checkbox" name="Alas de sangre" value="ON" />
            <input type="checkbox" name="Alas de hierro" value="ON" />
            
            
            <input type="submit" value="Buscar" name="enviarInsertar" />
            
            
        </form>
    </body>
</html>
