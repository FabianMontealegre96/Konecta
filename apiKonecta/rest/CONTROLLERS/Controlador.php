<?php // Este es el Controlador Manager general de todo el sistema. Todo tiene que pasar por esta clase
require_once 'DAL/AccesoDatos.php';
class Controlador {
    protected $iaccesoDatos;
    public function __construct() {
        $this->iaccesoDatos = new AccesoDatos();
    }

    public function ConsultarProductoStock($referencia) {
        return $this->iaccesoDatos->ConsultarProductoStock($referencia);
    }
    public function ConsultaIdProducto($referencia){
        return $this->iaccesoDatos->ConsultaIdProducto($referencia);
    }
    public function InsertarProducto($nombre, $referencia, $precio, $peso, $categoria, $stock){
        return $this->iaccesoDatos->InsertarProducto($nombre, $referencia, $precio, $peso, $categoria, $stock);
    }
    public function InsertarVentaProducto($Idventa, $idProducto, $cantidad, $precio){
        return $this->iaccesoDatos->InsertarVentaProducto($Idventa, $idProducto, $cantidad, $precio);
    }
    public function InsertarVenta($precioTotal){
        return $this->iaccesoDatos->InsertarVenta($precioTotal);
    }
    public function UpdateStockProducto($idProducto, $nuevoStock){
        return $this->iaccesoDatos->UpdateStockProducto($idProducto, $nuevoStock);
    }
    public function ModificarProducto($nombre, $referencia, $peso, $precio, $categoria, $stock){
        return $this->iaccesoDatos->ModificarProducto($nombre, $referencia, $peso, $precio, $categoria, $stock);
    }
    public function EliminarProducto($referencia){
        return $this->iaccesoDatos->EliminarProducto($referencia);
    }
    public function consultarReferenciaProducto($referencia) {
        return $this->iaccesoDatos->consultarReferenciaProducto($referencia);
    }
    public function ObtenerListadoProductoInventario() {
        return $this->iaccesoDatos->ObtenerListadoProductoInventario();
    }

    function response($code = 200, $status = "", $message = "") {
        http_response_code($code);
        if (!empty($status) && !empty($message)) {
            $response = array("status" => $status, "message" => $message);
            return $response;
        }
    }
}

?>