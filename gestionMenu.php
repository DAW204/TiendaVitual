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

        <style>

            table{

                width: 60%;

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

        </style>
    </head>
    <body>


        <?php
        session_start();

        /* Inlcuimos la conexion a la BD */
        include 'conexion.php';

        // Obtenemos la conexión utilizando la función getConn() (definida en el php de conexion a la BD)
        $conexion = getConn();


        /* SI HAY UN USUARIO LOGEADO ENTRA */
        if (isset($_SESSION['usuario']))
        {

            /* Si se ha enviado algo desde el menu entra en este if */
            if (isset($_REQUEST['enviar']))
            {
                /* Guardo la opcion (el value de ese boton) seleccionada para a continuacion realizar la accion necesaria* */
                $_SESSION['opcionMenu'] = $_REQUEST['enviar'];
            }


            if (isset($_SESSION['opcionMenu']))
            {

                if ($_SESSION['opcionMenu'] == 'verSolicitudes')
                {
                    echo "<h1>GESTION DE SOLICITUDES</h1>";

                    /* AHORA GESTIONAR LOS SELECCIONADOS PARA CAMBIARLES EL ESTADO */
                    if (isset($_REQUEST['cambiar']))
                    {
                        if (isset($_POST['opciones']))
                        {

                            // Recolecta todas las opciones seleccionadas
                            $opciones_seleccionadas = $_POST['opciones'];

                            // Itera sobre las opciones seleccionadas
                            foreach ($opciones_seleccionadas as $opcion)
                            {
                                // $opcion contiene el valor (ID) del checkbox seleccionado
                                // echo "Opción seleccionada: $opcion <br>";
                                //AQUI REALIZAR LA CONSULTA DE MODIFICAR TABLA USUARIOS/ROL EN FUNCION DEL ID QUE SEA IDENTICO AL DE LA TABLA 

                                $consulta = " UPDATE usuario SET rol='comprador' WHERE id_usuario='$opcion';";

                                $consulta = mysqli_query($conexion, $consulta)
                                        or die("Fallo en la consulta");


                                /* Actualizamos tambien la propia tabla de solicitudes */

                                $consulta = " UPDATE solicitudes SET estado='confirmado' WHERE id_usuario='$opcion';";

                                $consulta = mysqli_query($conexion, $consulta)
                                        or die("Fallo en la consulta");


                                /* Obtenemos la fecha actual */
                                $fecha_actual = date("Y-m-d");

                                /* Introducimos la fecha del momento de confirmacion */ //IGUAL ES NECESARIO CREAR UN OBJETO DATE PARA GESTIONARLO
                                $consulta = " UPDATE solicitudes SET fecha_aprobacion = '$fecha_actual' WHERE id_usuario = '$opcion'";

                                $consulta = mysqli_query($conexion, $consulta)
                                        or die("Fallo en la consulta");
                            }
                        } else
                        {
                            // Si no se seleccionaron opciones
                            echo "No se han seleccionado opciones.";
                        }
                    }

                    /* Realizamos un select para que el vendedor pueda ver todos los usuarios que han solicitado el cambio de rol */
                    $consulta = " select * from solicitudes WHERE estado='pendiente';"; //AQUI DESPUES AÑADIR UN WHERE PARA SACAR SOLO LOS QUE ESTAN PENDIENTES

                    $consulta = mysqli_query($conexion, $consulta)
                            or die("Fallo en la consulta");
                    ?>
                    <form action="gestionMenu.php" method="POST">

                        <?php
                        /* Consulta si hay usuarios pendientes */
                        if (mysqli_num_rows($consulta) > 0)
                        {
                            while ($row = mysqli_fetch_assoc($consulta))
                            {
                                /* Guardo en las variables cada dato de la tabal */
                                $id_solicitud = $row['id_solicitud'];
                                $nombre_opcion = $row['id_usuario'];
                                $estado = $row['estado'];
                                $fecha = $row['fecha_solicitud'];

                                // Muestra el ID en el valor del checkbox y los demás campos como texto en una etiqueta <label>
                                echo "<input type='checkbox' name='opciones[]' value='$nombre_opcion'> 
                                <label>ID solicitud: $id_solicitud, ID usuario: $nombre_opcion, Estado: $estado, Fecha: $fecha</label> <br>";
                            }
                        } else
                        {
                            echo "No hay opciones disponibles.";
                        }
                        ?>

                        <br><br>
                        <input type="submit" value="cambiar" name="cambiar" />

                    </form>
                    <?php
                }

                if ($_SESSION['opcionMenu'] == 'Comprar')
                {

                    /* Redirigimos a la página especificada, en este caso al catalogo */
                    header("Location: catalogo.php");
                    exit;
                }

                if ($_SESSION['opcionMenu'] == 'EstadoPedido')
                {
                    echo "<h1>PEDIDOS REALIZADOS</h1>";
                    /* Aqui le mostramos los pedidos que tiene y en que estados estan */
                    ?>

                    <form action="gestionMenu.php" method="POST">

                        <label for="opcion">Selecciona el estado del pedido:</label>
                        <select name="opcion" id="opcion">
                            <option value="procesado">Procesado</option>
                            <option value="enviado">Enviado</option>
                            <option value="reparto">En Reparto</option>
                            <option value="entregado">Entregado</option>
                        </select>
                        <input type="submit" value="Enviar" name="seleccion">

                    </form>
                    <?php
                    if (isset($_REQUEST['seleccion']))
                    {
                        $estadoSelecionado = $_POST['opcion'];
                        echo "<br><br>";


                        /* Realizamos la consulta para que nos devuelva id de pedido, el estado del mismo, asi como el total del precio y la cantidad
                          de articulos totales que contiene el pedido */
                        $consulta = "SELECT pedido.id_pedido, pedido.estadoPedido AS Estado, pedido.facturado AS TOTAL, 
                                     SUM(detallePedido.cantidad) AS Articulos FROM pedido
                                     INNER JOIN detallePedido ON pedido.id_pedido = detallePedido.id_pedido
                                     WHERE pedido.id_pedido = 5;";

                        $consulta = mysqli_query($conexion, $consulta)
                                or die("Fallo en la consulta");


                        /* Comprobamos si hay resultados */
                        if (mysqli_num_rows($consulta) > 0)
                        {
                            /* Encabezado de la tabla */
                            echo "<table>";
                            echo "<tr>";

                            echo "<th>ID PEDIDO</th>";
                            echo "<th>ESTADO PEDIDO</th>";
                            echo "<th>COSTE</th>";
                            echo "<th>TOTAL ARTICULOS</th>";

                            echo "</tr>";


                            while ($row = mysqli_fetch_assoc($consulta))
                            {

                                echo "<tr>";
                                echo "<td>" . $row['id_pedido'] . "</td>";
                                echo "<td>" . $row['Estado'] . "</td>";
                                echo "<td>" . $row['TOTAL'] . "</td>";
                                echo "<td>" . $row['Articulos'] . "</td>";

                                echo "</tr>";
                            }

                            echo "</table>";
                        } else
                        {
                            echo "No hay opciones disponibles.";
                        }
                    }
                }
            }
            ?>


            <a href="login.php">Volver al Login</a>
            <a href="menu.php">Volver al Menu</a>

            <?php
        } else
        {

            print "ACCESO NO PERMITIDO";
            ?>

            <a href="login.php">Volver al Login</a>
            <?php
        }

        /* Recojo el id del usuario procedente de una varibale de sesion, en una variable local */
        $id_usuario = $_SESSION['id_usuario'];
        ?>
    </body>
</html>
