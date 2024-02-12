

<?php

class Producto
{
    /* Propiedades del objeto producto */

    private $titulo;
    private $precio;
    private $cantidad;

    /* Constructor */

    public function __construct($titulo, $precio, $cantidad)
    {
        $this->titulo = $titulo;
        $this->titulo = $precio;
        $this->titulo = $cantidad;
    }

    /* GET/ SET TITULO */

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    /* GET/ SET TITULO */

    public function getPrecio()
    {
        return $this->precio;
    }

    public function setPrecio($precio)
    {
        $this->precio = $precio;
    }

    /* GET/ SET CANTIDAD */

    public function getCantidad()
    {
        return $this->cantidad;
    }

    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    }

}
?>
