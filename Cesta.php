
<?php

require_once './Producto.php';

class Cesta implements Serializable {
    /* Propied del objeto cesta, en este caso un arrya de objetos producto */

    private $productos = array();

    /* Constructor vacio */

    public function __construct() {
        $this->productos = [];
    }

    // Método getter para acceder al array
    public function getProductos() {
        return $this->productos;
    }

    // Método setter para modificar el array
    public function setProductos($nuevoArray) {
        $this->productos = $nuevoArray;
    }

    /* Creamos un metodo en la clase cesta para agregar nuevos libros */

    public function agregarProducto($producto) {

        $this->productos[] = $producto;
    }

    // Implementación del método serialize
    public function serialize() {
        return serialize($this->productos);
    }

    // Implementación del método unserialize
    public function unserialize($data) {
        $this->productos = unserialize($data);
    }

    /* Método para buscar un libro determinado por título
     * si encuentra coincidencias dentro del array retorna el producto
     * si no retorna false */

    public function buscarProductoPorTitulo($tituloDelLibro) {
        foreach ($this->productos as $producto) {
            if ($producto->getTitulo() === $tituloDelLibro) {
                return $producto;
            }
        }
        return false;
    }

    // Método para reindexar los productos que permite eliminar los 
    // huecos despues de un borrado en el array y que se reorganicen 
    // sus ínidices
    public function reindexar() {
        $this->productos = array_values($this->productos);
    }

}

?>
