<?php // DAL: Data Access Layer - Capa Acceso Datos
require_once 'Conexion.php';
require_once 'InterfaceAccesoDatos.php';

class AccesoDatos implements IAccesodatos
{
    private $cn = NULL; // Alias para la Conexion

    public function InsertarProducto($nombre, $referencia, $precio, $peso, $categoria, $stock) {
        $cn = Conexion::ObtenerConexion();
        try
        {
            $sql = "INSERT INTO producto (nombre, referencia, precio, peso, categoria, stock) VALUES (?,?,?,?,?,?)";
            $rs = $cn->prepare($sql);
            $rs->execute([$nombre, $referencia, $precio, $peso, $categoria, $stock]);
            return true;
        }
        catch(Exception $ex)
        {
            echo $ex;
        }

    }

    public function InsertarVentaProducto($Idventa, $idProducto, $cantidad, $precio) {
        $cn = Conexion::ObtenerConexion();
        try
        {
            $sql = "INSERT INTO ventaproducto (idVenta, idProducto, cantidadVendida, precio) VALUES (?,?,?,?)";
            $rs = $cn->prepare($sql);
            $rs->execute([$Idventa, $idProducto, $cantidad, $precio]);
            return true;
        }
        catch(Exception $ex)
        {
            echo $ex;
        }

    }

    public function ConsultarProductoStock($referencia) {
        $cn = Conexion::ObtenerConexion();
        try
        {
            $sql = "SELECT stock FROM producto WHERE referencia = ? ;";
            $rs = $cn->prepare($sql);
            $rs->execute([$referencia]);
            return $rs->fetch(PDO::FETCH_ASSOC);
        }
        catch(Exception $ex)
        {
            echo $ex;
        }
    }
    public function ConsultaIdProducto($referencia){
        $cn = Conexion::ObtenerConexion();
        try
        {
            $sql = "SELECT idProducto FROM producto WHERE referencia = ? ;";
            $rs = $cn->prepare($sql);
            $rs->execute([$referencia]);
            return $rs->fetch(PDO::FETCH_ASSOC);
        }
        catch(Exception $ex)
        {
            echo $ex;
        }
    }
    public function UpdateStockProducto($idProducto, $nuevoStock) {
        $cn = Conexion::ObtenerConexion();
        try
        {
            $sql = "UPDATE producto SET stock=? WHERE idProducto=? ;";
            $rs = $cn->prepare($sql);
            $rs->execute([$nuevoStock, $idProducto]);
            return true;
        }
        catch(Exception $ex)
        {
            echo $ex;
        }
    }
    public function InsertarVenta($precioTotal) {
        $cn = Conexion::ObtenerConexion();
        try
        {
            $sql = "INSERT INTO ventas (precioTotal) VALUES (?)";
            $rs = $cn->prepare($sql);
            $rs->execute([$precioTotal]);
            $ultimoIdVenta = $this->ConsultaUltimoIdVenta();
            return $ultimoIdVenta;
        }
        catch(Exception $ex)
        {
            echo $ex;
        }
    }

    public function ModificarProducto($nombre, $referencia, $peso, $precio, $categoria, $stock) {
        $cn = Conexion::ObtenerConexion();
        try
        {
            $sql = "UPDATE producto SET nombre=?, peso=?, precio=?, categoria=?, stock=? WHERE referencia=?;";
            $rs = $cn->prepare($sql);
            $rs->execute([$nombre, $peso, $precio, $categoria, $stock, $referencia]);
            return true;
        }
        catch(Exception $ex)
        {
            echo $ex;
        }
    }
    public function ConsultaUltimoIdVenta() {
        $cn = Conexion::ObtenerConexion();
        try
        {
            $sql = "SELECT MAX(idVenta) as UltimoIdVenta FROM ventas ;";
            $rs = $cn->prepare($sql);
            $rs->execute();
            return $rs->fetch(PDO::FETCH_ASSOC);
        }
        catch(Exception $ex)
        {
            echo $ex;
        }

    }
    public function EliminarProducto($referencia) {
        $cn = Conexion::ObtenerConexion();
        try
        {
            $sql = "DELETE FROM producto WHERE referencia=?;";
            $rs = $cn->prepare($sql);
            $ejecucion = $rs->execute([$referencia]);
            if($ejecucion){
                $retorno = true;
            } else {
                $retorno = false;
            }
            return $retorno;
        }
        catch(Exception $ex)
        {
            echo $ex;
        }
    }
    public function consultarReferenciaProducto($referencia){
        $cn = Conexion::ObtenerConexion();
        try
        {
            $sql = "SELECT referencia FROM producto WHERE referencia = ? ;";
            $rs = $cn->prepare($sql);
            $rs->execute([$referencia]);
            return $rs->fetch(PDO::FETCH_ASSOC);
        }
        catch(Exception $ex)
        {
            echo $ex;
        }
    }

    public function ObtenerListadoProductoInventario()
    {
        $cn = Conexion::ObtenerConexion();
        $ListaProductos = array();
        try
        {
            $rs = $cn->prepare("SELECT nombre, referencia, peso, precio, categoria, stock FROM producto");

            $rs->execute();
            while ($fila = $rs->fetch(PDO::FETCH_ASSOC))
            {
                $ListaProductos['ProductosInventario'][] = $fila;
            }

            return $ListaProductos;
        }
        catch(Exception $ex)
        {
            echo $ex;
        }
    }
}
?>
