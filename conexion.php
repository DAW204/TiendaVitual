<?php

function getConn()
{
    $conexion = mysqli_connect('localhost', 'root', '', "tienda");
    if (mysqli_connect_errno()) {
        echo "Fallo al conectar a MySQL: " . mysqli_connect_error();
    }
    $conexion->set_charset('utf8');
    return $conexion;
}

