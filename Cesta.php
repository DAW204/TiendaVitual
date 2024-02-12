
<?php

require_once './Producto.php';

class Cesta
{
    /* Propied del objeto cesta, en este caso un arrya de objetos producto */

    private $productos = array();

    /* Constructor vacio */

    public function __construct()
    {
        $this->productos = [];
    }

    // Método getter para acceder al array
    public function getProductos()
    {
        return $this->productos;
    }

    // Método setter para modificar el array
    public function setProductos($nuevoArray)
    {
        $this->productos = $nuevoArray;
    }

    /* Creamos un metodo en la clase cesta para agregar nuevos libros */

    public function agregarProducto($producto)
    {
        $this->productos[] = $producto;
    }

}

?>
