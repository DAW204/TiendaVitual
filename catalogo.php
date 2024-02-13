<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<html>
    <head>
        <meta charset="UTF-8">
        <title>Catálogo</title>

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
            
            .checkbox {
                text-align: center;
            }

            #input {

                width: 40px;
                height: 15px;
                margin-left: 10px;
            }
            
            
        </style>
    </head>
    <body>

        <h1>Catalogo de libros</h1>

        <?php
        /* inicio sesion */
        session_start();

        /* Indicamos que haga referencia a usar la clase cesta y product */
        require_once './Cesta.php';
        require_once './Producto.php';

        /* Si la cesta esta vacia entra en el if */
        if (!isset($_SESSION['cesta'])) {
            /* Se crea una cesta vacia y se guarda serializada en la variable de sesion */
            $nuevaCesta = new Cesta();
            $_SESSION['cesta'] = $nuevaCesta->serialize();
        }


        /* Inlcuimos la conexion a la BD */
        include 'conexion.php';

        // Obtenemos la conexión utilizando la función getConn() (definida en el php de conexion a la BD)
        $conexion = getConn();

        $consulta = "select * from libro;";

        $consulta = mysqli_query($conexion, $consulta)
                or die("Fallo en la consulta");


        /* Sacamos la fila */
        //$datosConsulta = mysqli_fetch_assoc($consulta);
        ?>


        <form name="enviar" action="catalogo.php" method="POST">
            <table>
                <tr>
                    <th>Cantidad</th>
                    <th>Titulo</th>
                    <th>Autor</th>
                    <th>precio</th>
                    <th>Editorial</th>
                </tr>

                <?php
                while ($row = mysqli_fetch_assoc($consulta)) {
                    ?>

                    <tr>
                        <td> 
                            <input type='number' name='cantidad[<?php echo $row['titulo'] . " | " . $row ['precio'] ?>]' value='0' id= 'input'>
                        </td>
                        <td><?php echo $row['titulo'] ?></td>
                        <td><?php echo $row['autor'] ?></td>
                        <td><?php echo $row['precio'] ?></td>
                        <td><?php echo $row['editorial'] ?> </td>
                    </tr>


                <?php } ?>

            </table>

            <br><br>
            <input type="submit" value="comprar" name="enviar" />

        </form><br>


        <?php
        /* Si nos ha llegado las cantidades del libro que se quiere comprar entra en el if */
        if (isset($_POST['cantidad'])) {
            /* Recojo el array asociativo pasado por el formulario, donde la clave del mismo se compone del titulo y el precio del libto 
              y el valor se corresponde a la cantidad de ese libro que ha selecionado el usuario */
            $cantidades = $_POST['cantidad'];

            /* Se crea una cesta vacia para obtener la cesta de la variable de sesion */
            $cesta = new Cesta();

            /* Guardamos la cesta en la variable de sesion serializada */
            $cesta->unserialize($_SESSION['cesta']);

            /* Recorremos con el foreach el array devuelto y extraemos las claves y sus valores */
            foreach ($cantidades as $clave => $cantidad) {
                /* Si la cantidad del libro es mayor que cero entra en el if y... */
                if ($cantidad > 0) {
                    /* Divido las claves del array pasado por el form con la funcion explode  para obetener las claves por separado, ya que esta,
                      se encarga de dividir una cadena en subcadenas, le pasamos como primer argumento el delimitador en este caso | y el segundo
                     * argumento es lo que queremos dividir, por lo tanto partesClaves se convierte ahora en un array de dos posiciones */
                    $partesClaves = explode(" | ", $clave);


                    /* Aqui indicamos que la primera posicion [0] del array creado al dividir la clave, corresponde al titulo del libro y la segunda posicion
                      [1] correspondera al precio del libro, convierto el precio en float para despues realizar el sumatorio del factura  del comprador */
                    $tituloDelLibro = $partesClaves[0];
                    $precioLibro = (float) $partesClaves[1];


                    //IMPORTANTE A CONTINUACIÓN -> Problema al añadir más cantidades y entre sesiones
                    //Se repiten los libros al hacer pruebas
                    //SOLUCIONADO!!! EN PRINCIPIO
                    //Si puedes probar Julene


                    /* Con esta solución lo que hariamos sería comprobar primero si en la cesta ya está
                     * guardado ese título, y si está lo que hará será modificar solo la cantidad */
                    $productoExistente = $cesta->buscarProductoPorTitulo($tituloDelLibro);
                    
                    /* Verificar si el producto ya existe en la cesta */
                    if ($productoExistente) {
                        
                        // Si el producto ya existe, actualizar la cantidad
                        $productoExistente->setCantidad($productoExistente->getCantidad() + $cantidad);
                    } else {
                        // Si el producto no existe, crear un nuevo producto y agregarlo a la cesta
                        $producto = new Producto($tituloDelLibro, $precioLibro, $cantidad);
                        $cesta->agregarProducto($producto);
                    }
                }
            }
            ?>
            <h2>Mi Cesta</h2>
            <table>
                <tr>
                    <th>Título</th>
                    <th>Precio Unitario</th>
                    <th>Cantidad</th>
                    <th>Eliminar</th>
                </tr>


                <?php
                foreach ($cesta->getProductos() as $producto) {

                    echo "<tr><td>" . $producto->getTitulo() . "</td>";
                    echo "<td>" . $producto->getPrecio() . "</td>";
                    echo "<td>" . $producto->getCantidad() . "</td>";
                    echo '<td class="checkbox"><input type="checkbox" id="miCheckbox" name="miCheckbox" value="valor"/></td></tr>';
                }

                /* Guardamos la cesta en la variable de sesion serializada */
                $_SESSION['cesta'] = $cesta->serialize();
            }
            ?>
        </table>
    </body>
</html>
