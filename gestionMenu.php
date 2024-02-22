<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Gestión</title>

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

            table{
                margin-left: auto;
                margin-right: auto;
                width: 80%;

            }
            tr
            {
                height: 40px
            }

            th {
                background-color: lightskyblue;
            }

            td {
                padding-left: 20px;
                text-align: left;
            }
            table, tr, td,th
            {
                border: 2px solid;
                border-collapse: collapse;

            }

            .checkbox {
                text-align: center;
            }



        </style>
    </head>
    <body>

        <?php
        /* Iniciamos la sesion */
        session_start();

        /* Inlcuimos la conexion a la BD */
        include 'conexion.php';

        // Obtenemos la conexión utilizando la función getConn() (definida en el php de conexion a la BD)
        $conexion = getConn();

        /* SI HAY UN USUARIO LOGEADO ENTRA */
        if (isset($_SESSION['usuario']) && isset($_SESSION['rol']) == 'vendedor') {

            /* Si se ha enviado algo desde el menu entra en este if */
            if (isset($_REQUEST['enviar'])) {
                /* Guardo la opcion (el value de ese boton) seleccionada para a continuacion realizar la accion necesaria* */
                $_SESSION['opcionMenu'] = $_REQUEST['enviar'];
            }


            /* Este if evalua que se haya seleccionado algo en el menu, si es que si entra */
            if (isset($_SESSION['opcionMenu'])) {

//ROL VENDEDOR (ADMIN)
//OPCION VER SOLICITUDES
                /* Si el boton pulsado es ver solicitudes entra */
                if ($_SESSION['opcionMenu'] == 'VerSolicitudes') {
                    echo "<h1>GESTION DE SOLICITUDES</h1><hr>";

                    /* Se compreba que se haya pulsado el boton de cambiar */
                    if (isset($_REQUEST['cambiar'])) {
                        /* Comprobamos que se haya selecionado algo en caso de que si entra en el if */
                        if (isset($_POST['opciones'])) {
                            /* Recojemos las opciones */
                            $opciones_seleccionadas = $_POST['opciones'];

                            /* Recorremos todas las opciones selecionadas */
                            foreach ($opciones_seleccionadas as $opcion) {
                                // $opcion contiene el valor (ID) del checkbox seleccionado
                                /* Actualizamos con update el rol a comprador selecionado el id del usuario */

                                $consulta = " UPDATE usuario SET rol='comprador' WHERE id_usuario='$opcion';";
                                $consulta = mysqli_query($conexion, $consulta)
                                        or die("Fallo en la consulta");

                                /* Actualizamos tambien la propia tabla de solicitudes */
                                $consulta = " UPDATE solicitudes SET estado='confirmado' WHERE id_usuario='$opcion';";
                                $consulta = mysqli_query($conexion, $consulta)
                                        or die("Fallo en la consulta");

                                /* Obtenemos la fecha actual para introducicirla en la tabla solicitudes y que se registre la fecha del momento */
                                $fecha_actual = date("Y-m-d");

                                /* Introducimos la fecha del momento de confirmacion, utilizando como antes el id del usuario correspondiente */
                                $consulta = " UPDATE solicitudes SET fecha_aprobacion = '$fecha_actual' WHERE id_usuario = '$opcion'";
                                $consulta = mysqli_query($conexion, $consulta)
                                        or die("Fallo en la consulta");
                            }
                        } else {
                            // Si no se seleccionaron opciones
                            echo "No se han seleccionado opciones.";
                        }
                    }

                    /* Realizamos un select para que el vendedor pueda ver todos los usuarios que han solicitado el cambio de rol */
                    $consulta = " select * from solicitudes WHERE estado='pendiente';";
                    $consulta = mysqli_query($conexion, $consulta)
                            or die("Fallo en la consulta");
                    ?>

                    <form action="gestionMenu.php" method="POST">

                        <?php
                        /* Consulta si hay usuarios pendientes */
                        if (mysqli_num_rows($consulta) > 0) {
                            while ($row = mysqli_fetch_assoc($consulta)) {
                                /* Guardo en las variables cada dato de la tabla */
                                $id_solicitud = $row['id_solicitud'];
                                $nombre_opcion = $row['id_usuario'];
                                $estado = $row['estado'];
                                $fecha = $row['fecha_solicitud'];

                                /* Muestra el ID en el valor del checkbox y los demás campos como texto en una etiqueta <label> */
                                echo "<input type='checkbox' name='opciones[]' value='$nombre_opcion'> 
                                <label>ID solicitud: $id_solicitud, ID usuario: $nombre_opcion, Estado: $estado, Fecha: $fecha</label> <br>";
                            }
                        } else {
                            /* Mensaje de que no hay ningun registro que sea acorde a la consulta */
                            echo "No hay opciones disponibles.";
                        }
                        ?>

                        <br><br>
                        <input type="submit" value="cambiar" name="cambiar" />

                    </form>
                    <?php
                }
//ROL VENDEDOR (ADMIN)
//OPCION CAMBIAR ESTADOS
                /* Si el boton que se pulsa en le menu es cambiar estados (vendedor), entra en el  if */
                if ($_SESSION['opcionMenu'] == 'CambiarEstados') {

                    echo "<h1>Cambiar estados de los pedidos</h1><hr>";
                    /* Aqui le mostramos los pedidos que tiene y en que estados estan para que pueda cambiarlos */
                    ?>

                    <form action="gestionMenu.php" method="POST">

                        <label for="opcion">Filtrar por estado:</label>
                        <select name="opcion" id="opcion">
                            <option value="procesado">Procesado</option>
                            <option value="enviado">Enviado</option>
                            <option value="reparto">En Reparto</option>
                            <option value="entregado">Entregado</option>
                            <option value="todos">Ver todos</option>
                        </select>
                        <input type="submit" value="Consultar" name="seleccion">

                    </form>

                    <br><br>
                    <?php
                    /* Solo entra en este if si el vendedor ha pulsado para confirmar el cambio de estado, de esta manera al estar primero, la tabla que
                      aparecera despues estara actualizada, de otra forma, el cambio no iria en concordandia con el momento, el and nos sirve para asegurarnos de
                     * que siempre hay una opcion seleccionada en el radio button */
                    if (isset($_REQUEST['cambiarEstado']) && isset($_REQUEST['selectCambio'])) {
                        /* Consulta de actualizacion, modificamos el estado de pedido con la opcion marcada por el vendedro, indicandole con la clausula wher correspondiete al id
                          de pedido que recogemos gracias al value del radio button indicado */
                        $consulta = "UPDATE pedido SET estadoPedido='" . $_REQUEST['opcion'] . "' WHERE id_pedido = " . $_REQUEST['selectCambio'];
                        $consulta = mysqli_query($conexion, $consulta)
                                or die("Fallo en la consulta");
                    }

                    /* Entra en el if si se ha pulsado cualquiera de los dos botones para sacar la tabla, ya que en inicio hasta que no se pulsa no aparece la tabla */
                    if (isset($_REQUEST['seleccion']) || isset($_REQUEST['cambiarEstado'])) {
                        ?>
                        <form action="gestionMenu.php" method="POST">
                            <?php
                            /* Recojo la opcion que quiere cambiar el admin */
                            $estadoSelecionado = $_POST['opcion'];

                            /* Si el usuario quiere ver todos los pedidos independientemente del estado entra en el if y se realiza una cosnulta sin WHERE */
                            if ($estadoSelecionado == 'todos') {
                                $consulta = "SELECT pedido.id_usuario, pedido.id_pedido, pedido.estadoPedido AS Estado, pedido.facturado AS TOTAL, 
                                     SUM(detallePedido.cantidad) AS Articulos FROM pedido
                                     INNER JOIN detallePedido ON pedido.id_pedido = detallePedido.id_pedido
                                     GROUP BY pedido.id_usuario, pedido.id_pedido, pedido.estadoPedido, pedido.facturado;";
                            } else {
                                /* Realizamos la consulta conforme al estado selecionado para que nos devuelva id de pedido, el estado del mismo, asi como el total del precio y la cantidad
                                  de articulos totales que contiene el pedido */
                                $consulta = "SELECT pedido.id_usuario, pedido.id_pedido, pedido.estadoPedido AS Estado, pedido.facturado AS TOTAL, 
                                     SUM(detallePedido.cantidad) AS Articulos FROM pedido
                                     INNER JOIN detallePedido ON pedido.id_pedido = detallePedido.id_pedido
                                     WHERE pedido.estadoPedido = '$estadoSelecionado'GROUP BY pedido.id_usuario, pedido.id_pedido, pedido.estadoPedido, pedido.facturado;";
                            }


                            $consulta = mysqli_query($conexion, $consulta)
                                    or die("Fallo en la consulta");


                            /* Comprobamos si hay resultados */
                            if (mysqli_num_rows($consulta) > 0) {
                                /* Sacamos la tabla por pantalla */
                                echo "<table>";
                                echo "<tr>";

                                echo "<th>ID USUARIO</th>";
                                echo "<th>ID PEDIDO</th>";
                                echo "<th>ESTADO PEDIDO</th>";
                                echo "<th>COSTE</th>";
                                echo "<th>TOTAL ARTICULOS</th>";
                                echo "<th></th>";

                                echo "</tr>";

                                /* Recorre el resultado de la consulta realizada */
                                while ($row = mysqli_fetch_assoc($consulta)) {

                                    echo "<tr>";
                                    echo "<td>" . $row['id_usuario'] . "</td>";
                                    echo "<td>" . $row['id_pedido'] . "</td>";
                                    echo "<td>" . $row['Estado'] . "</td>";
                                    echo "<td>" . $row['TOTAL'] . "</td>";
                                    echo "<td>" . $row['Articulos'] . "</td>";

                                    /* Pasamos mediante el value del radio button el id pedido que corresponde con ese registro, para modificar solo el correcto */
                                    echo "<td><input type='radio' name='selectCambio' value='" . $row['id_pedido'] . "'/></td>";
                                    echo "</tr>";
                                }

                                echo "</table>";
                                echo "<br><br><br>";
                            } else {
                                echo "No hay opciones disponibles.<br><br><br>";
                            }
                            ?>

                            <!-- Usamos otro selecto para elegit a que estado queremos cambiar el pedido, el primer select solo es para filtrar -->
                            <label for="opcion">Cambiar a:</label>
                            <select name="opcion" id="opcion">
                                <option value="procesado">Procesado</option>
                                <option value="enviado">Enviado</option>
                                <option value="reparto">En Reparto</option>
                                <option value="entregado">Entregado</option>
                            </select>
                            <input type="submit" name="cambiarEstado" value="Cambiar estado"> 
                        </form>
                        <?php
                        echo "<br>";

                        /* Si el usuario no ha marcado nada y pulsa el boton entra en el if y sale el mensaje correspondiente */
                        if (isset($_REQUEST['cambiarEstado']) && !isset($_REQUEST['selectCambio'])) {
                            echo 'No has selecionado nada';
                        }
                    }
                }

//ROL VENDEDOR (ADMIN)
//OPCION INSERTAR NUEVO LIBRO

                if ($_SESSION['opcionMenu'] == 'InsertarLibro') {
                    echo "<h1>AÑADIR NUEVO TÍTULO</h1>";
                    /* Compruebo que se ha enviado el formulario */
                    if (isset($_REQUEST['insertar'])) {
                        /* Verifica que los datos se han recibido correctamente, si no es así se le indica al usuario */
                        if (isset($_REQUEST['isbn']) && isset($_REQUEST['titulo']) && isset($_REQUEST['autor']) && isset($_REQUEST['editorial']) && isset($_REQUEST['precio'])) {
                            
                            
                            /*Se obtiene la información del título enviado y se guarda en una variable*/
                            $tituloAComprobar = $_REQUEST['titulo'];
                            /* Realizo una consulta buscando coincidencias con el titulo */
                            $consulta = "SELECT * FROM libro WHERE titulo = '" . $tituloAComprobar . "';";
                            $consulta = mysqli_query($conexion, $consulta)
                                    or die("Fallo en la consulta");
                            /* Si no hay coincidencias en la consulta procede a insertar el nuevo libro en la tabla
                             * Si por el contrario sí hay, informa al usuario con un mensaje */
                            if (mysqli_num_rows($consulta) == 0) {

                                $consulta = "INSERT INTO libro(isbn, titulo, autor, editorial, precio) "
                                        . "VALUES ('" . $_REQUEST['isbn'] . "','" . $_REQUEST['titulo'] . "', '" . $_REQUEST['autor']
                                        . "','" . $_REQUEST['editorial'] . "','" . $_REQUEST['precio'] . "')";
                                $consulta = mysqli_query($conexion, $consulta)
                                        or die("Fallo en la consulta");

                                print "Se ha registrado el nuevo libro con éxito";
                            } else {
                                print "Ya existe un libro con ese mismo título";
                            }
                        } else {
                            print 'No se ha podido registrar el nuevo libro. Revisa los datos.';
                        }
                    } else {
                        ?>
                        <form action="gestionMenu.php" method="POST">
                            <hr>
                            <h3>Introduce los datos del libro</h3>
                            <br>
                            ISBN:
                            <br>
                            <input type="number" name="isbn" min="0" value="" required/>
                            <br><br>

                            Título:
                            <br>
                            <input type="text" name="titulo" value="" required/>
                            <br><br>

                            Autor:
                            <br>
                            <input type="text" name="autor" value="" required/>
                            <br><br>

                            Editorial:
                            <br>
                            <input type="text" name="editorial" value="" required/>
                            <br><br>

                            Precio:
                            <br>
                            <input type="number" name="precio" step="0.01" min="0" placeholder="0.00" value="" required/>
                            <br><br>

                            <input type="submit" name="insertar" value="Insertar"> 
                        </form>

                        <?php
                    }
                }

//ROL COMPRADOR
//OPCION COMPRAR
                /* Si el boton que se pulsa en el menu es comprar (por parte del comprador), entra en el if */
                if ($_SESSION['opcionMenu'] == 'Comprar') {
                    /* Redirigimos a la página especificada, en este caso al catalogo */
                    header("Location: catalogo.php");
                    exit;
                }
//ROL COMPRADOR
//OPCION ESTADO PEDIDO
                /* Si el boton que se pulsa en el menu es Estado pedido (comprador) entra en el if */
                if ($_SESSION['opcionMenu'] == 'EstadoPedido') {
                    echo "<h1>PEDIDOS REALIZADOS</h1><hr>";
                    /* Aqui le mostramos los pedidos que tiene y en que estados estan */
                    ?>

                    <br><form action="gestionMenu.php" method="POST">

                        <label for="opcion">Selecciona el estado del pedido:</label>
                        <select name="opcion" id="opcion">
                            <option value="procesado">Procesado</option>
                            <option value="enviado">Enviado</option>
                            <option value="reparto">En Reparto</option>
                            <option value="entregado">Entregado</option>
                            <option value="todos">Ver todos</option>
                        </select>
                        <input type="submit" value="Enviar" name="seleccion">

                    </form><br><br>
                    <?php
                    if (isset($_REQUEST['seleccion'])) {
                        /* Recojo el id del usuario para utilizarlo en la select y sacar sus pedidos y el estado de pedido elegido */
                        $id_usuario = $_SESSION['id_usuario'];
                        $estadoSelecionado = $_POST['opcion'];


                        /* Si el usuario seleciona la opcion de todos enrta en el if y vera todos los pedidos que tenga */
                        if ($estadoSelecionado == 'todos') {
                            $consulta = "SELECT pedido.id_pedido, pedido.estadoPedido AS Estado, pedido.facturado AS TOTAL, 
                                     SUM(detallePedido.cantidad) AS Articulos FROM pedido
                                     INNER JOIN detallePedido ON pedido.id_pedido = detallePedido.id_pedido
                                     WHERE pedido.id_usuario =$id_usuario GROUP BY pedido.id_pedido, pedido.estadoPedido, pedido.facturado;";
                        } else {
                            /* Realizamos la consulta conforme al estado selecionado para que nos devuelva id de pedido, el estado del mismo, asi como el total del precio y la cantidad
                              de articulos totales que contiene el pedido */
                            $consulta = "SELECT pedido.id_pedido, pedido.estadoPedido AS Estado, pedido.facturado AS TOTAL, 
                                     SUM(detallePedido.cantidad) AS Articulos FROM pedido
                                     INNER JOIN detallePedido ON pedido.id_pedido = detallePedido.id_pedido
                                     WHERE pedido.id_usuario =$id_usuario AND pedido.estadoPedido = '$estadoSelecionado'GROUP BY pedido.id_pedido, pedido.estadoPedido, pedido.facturado;";
                        }


                        $consulta = mysqli_query($conexion, $consulta)
                                or die("Fallo en la consulta");


                        /* Comprobamos si hay resultados */
                        if (mysqli_num_rows($consulta) > 0) {
                            /* Sacamos la tabla con todos los datos necesarios del pedido, estado etc... */
                            echo "<table>";
                            echo "<tr>";

                            echo "<th>ID PEDIDO</th>";
                            echo "<th>ESTADO PEDIDO</th>";
                            echo "<th>COSTE</th>";
                            echo "<th>TOTAL ARTICULOS</th>";

                            echo "</tr>";

                            /* Recorremos todos los registros que nos devuelve la consulta */
                            while ($row = mysqli_fetch_assoc($consulta)) {
                                echo "<tr>";
                                echo "<td>" . $row['id_pedido'] . "</td>";
                                echo "<td>" . $row['Estado'] . "</td>";
                                echo "<td>" . $row['TOTAL'] . "</td>";
                                echo "<td>" . $row['Articulos'] . "</td>";
                                echo "</tr>";
                            }

                            echo "</table>";
                        } else {
                            echo "No hay opciones disponibles.";
                        }
                    }
                }
            }
            ?>

            <br><hr><hr>
            <a href="menu.php">Volver al Menu</a>
            <hr><hr>

            <?php
        } else {
            /* Si no hay nadie logeado se le indica que no tiene acceso permitido y se le proporciona un enlace de vuelta al login */
            session_destroy();
            print "ACCESO NO PERMITIDO";
            ?>

            <br><a href="login.php">Volver al Login</a><br><br>
            <?php
        }
        ?>
    </body>
</html>
