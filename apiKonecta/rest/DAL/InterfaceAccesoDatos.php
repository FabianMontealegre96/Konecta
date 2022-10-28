<?php // Interface que expone todo lo que el DAL (Capa Acceso Datos) implementa

interface IAccesoDatos
{
    public function InsertarProducto($nombre, $referencia, $precio, $peso, $categoria, $stock);
    public function InsertarVentaProducto($Idventa, $idProducto, $cantidad, $precio);
    public function ConsultarProductoStock($referencia);
    public function ConsultaIdProducto($referencia);
    public function UpdateStockProducto($idProducto, $nuevoStock);
    public function InsertarVenta($precioTotal);
    public function ModificarProducto($nombre, $referencia, $peso, $precio, $categoria, $stock);
    public function ConsultaUltimoIdVenta();
    public function EliminarProducto($referencia);
    public function consultarReferenciaProducto($referencia);
    public function ObtenerListadoProductoInventario();
    
}