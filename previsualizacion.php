<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Previsualización</title>
        <style>

            table{

                width: 80%;

            }
            tr
            {
                height: 40px
            }
            table, tr, td,th
            {
                border: 2px solid;
                border-collapse: collapse;

            }

            input {
                margin-top: 15px;
            }
        </style>
    </head>
    <body>
        <?php
        if (isset($_SESSION['usuario']) && isset($_SESSION['rol']) == 'invitado') {
            ?>

            <h1>ERES INVITADO</h1>
            <h2>Catalogo de libros</h2>
            <?php
            /* inicio sesion */
            session_start();

            /* Inlcuimos la conexion a la BD */
            include 'conexion.php';

            // Obtenemos la conexión utilizando la función getConn() (definida en el php de conexion a la BD)
            $conexion = getConn();
            $consultaGeneral = "select * from libro;";
            $resultado = mysqli_query($conexion, $consultaGeneral)
                    or die("Fallo en la consulta");
            ?>
            <table>
                <tr>
                    <th>Titulo</th>
                    <th>Autor</th>
                    <th>precio</th>
                    <th>Editorial</th>
                </tr>

                <?php
                while ($row = mysqli_fetch_assoc($resultado)) {
                    ?>

                    <tr>
                        <td><?php echo $row['titulo'] ?></td>
                        <td><?php echo $row['autor'] ?></td>
                        <td><?php echo $row['precio'] ?></td>
                        <td><?php echo $row['editorial'] ?> </td>
                    </tr>


                <?php } ?>

            </table>

            <form action="previsualizacion.php" method="POST">
                <input type="submit" name="solicitar" value="CAMBIAR ROL" />
                <input type="submit" name="volver" value="LOGIN" />
            </form>


            <?php
            /* Recojemos el id del usuario de la variable de sesion */
            $id_usuario = $_SESSION['id_usuario'];

            /* Si el usuario invitado pulsa solicitar, entra en el if y se realiza un insert en la tabla de solicitudes, donde quedara 
              su peticion de cambio de rol registrada con el estado en pendiente para que despues el vendedor efectue el cambio a comprador */
            if (isset($_POST['solicitar'])) {

                $consulta = "INSERT INTO solicitudes (id_usuario) VALUES ($id_usuario);";

                $consulta = mysqli_query($conexion, $consulta)
                        or die("Fallo en la consulta");
            }


            /* Si el usuario invitado pulsa el boton para cambiar de rol le redirigira al login */
            if (isset($_POST['volver'])) {

                /* Redirigimos a la página especificada, en este caso el login */
                header("Location: login.php");
                exit;
            }
        } else {
            session_destroy();
            header("Location: login.php");
            exit;
        }
        ?>
    </body>
</html>
