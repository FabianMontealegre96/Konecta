<?php // Este es el servicio Rest como tal, quien recibe las peticiones desde el exterior
require_once 'Controlador.php';

class APIKonecta
{

    public function __construct()
    {
    }

    public function API()
    {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method)
        {
            case 'GET':
                if (isset($_GET['action']))
                {
                    if ($_GET['action'] == 'ListadoProductoInventario')
                    {
                        $this->ObtenerListadoProductoInventario();
                    }
                    else{
                      $this->response(400, "Error", "Accion no Encontrada");
                    }
                }else{
                  $this->response(400, "Error", "Accion no Encontrada");
                }
                break;
            case 'POST':
                if (isset($_GET['action']))
                {
                    if ($_GET['action'] == 'InsertarProducto')
                    {
                        $this->InsertarProducto();
                    }

                    if ($_GET['action'] == 'InsertarVenta')
                    {
                        $this->InsertarVentaProducto();
                    }
                } else {
                    $this->response(400, "Error", "Accion no Encontrada");
                }
                break;
            case 'PUT':
                if (isset($_GET['action']))
                {
                    if ($_GET['action'] == 'ModificarProducto')
                    {
                        $this->ModificarProducto();
                    }
                } else {
                    $this->response(400, "Error", "Accion no Encontrada");
                }
                break;
            case 'DELETE':
                if (isset($_GET['action']))
                {
                    if ($_GET['action'] == 'EliminarProducto')
                    {
                        $this->EliminarProducto();
                    }
                } else {
                    $this->response(400, "Error", "Accion no Encontrada");
                }
                break;
            default:
                echo 'Metodo No Valido';
                break;
            }
        }

        function InsertarProducto()
        {
            $data = json_decode(file_get_contents('php://input'), true);
            if ($data) {
                $nombre = $data['nombre'];
                $referencia = $data['referencia'];
                $precio = $data['precio'];
                $peso = $data['peso'];
                $categoria = $data['categoria'];
                $stock = $data['stock'];

                $controlador = new Controlador();
                if($controlador->InsertarProducto($nombre, $referencia, $precio, $peso, $categoria, $stock)){
                    $this->response(200, "Success", "Insertado correctamente");
                }else{
                    $this->response(500, "error", "NO Insertado correctamente");
                }
            }
        }
        function InsertarVentaProducto()
        {
            $data = json_decode(file_get_contents('php://input'), true);
            if ($data) {
                $bandera = 0;
                $cantidadDisponible = 0;
                $cantidadSolicitada = 0;
                foreach($data as $clave) {
                    $referencia = $clave['referencia'];
                    $cantidad = $clave['cantidad'];
                    $stock = $this->ConsultarProductoStock($referencia);
                    $evaluarStock = $stock - $cantidad;
                    
                    if($evaluarStock < 0) {
                        $bandera = 1;
                        $cantidadDisponible = $stock;
                        $cantidadSolicitada = $cantidad;
                    }               
                }

                if($bandera == 0) {
                    $Idventa = $this->insertarVenta($data);
                    foreach($data as $clave) {
                        $referencia = $clave['referencia'];
                        $cantidad = $clave['cantidad'];
                        $precio = $clave['precio'];
                        $stock = $this->ConsultarProductoStock($referencia);
                        $nuevoStock = $stock - $cantidad;
                        $idProducto = $this->ConsultaIdProducto($referencia);
                        $this->UpdateStockProducto($idProducto, $nuevoStock);

                        $controlador = new Controlador();
                        $response = $controlador->InsertarVentaProducto($Idventa, $idProducto, $cantidad, $precio);                        
                    }
                    if($response){
                        $this->response(200, "OK", "Compra realizada con exito");
                    }
                } else {
                    $this->response(202, "Accepted", "No es posible realizar la venta, se solicitan ".$cantidadSolicitada." y se dispone de ".$cantidadDisponible);
                }
                
            }            
        }

        function ConsultarProductoStock($referencia) {
            $controlador = new Controlador();
            $response = $controlador->ConsultarProductoStock($referencia);
            return $response['stock'];
        }
        function ConsultaIdProducto($referencia) {
            $controlador = new Controlador();
            $response = $controlador->ConsultaIdProducto($referencia);
            return $response['idProducto'];
        }
        function insertarVenta($data) {
            $precioTotal = 0;
            foreach($data as $clave) {
                $precioTotal = $clave['precio']+$precioTotal;
            }
            $controlador = new Controlador();
            $idVenta = $controlador->InsertarVenta($precioTotal);
            return $idVenta['UltimoIdVenta'];
        }

        function UpdateStockProducto($idProducto, $nuevoStock){
            $controlador = new Controlador();
            $controlador->UpdateStockProducto($idProducto, $nuevoStock);
        }

        function ActualizarStockProducto(){
            $controlador = new Controlador();
        }

        function ModificarProducto() {
            $controlador = new Controlador();
            $data = json_decode(file_get_contents('php://input'), true);
            if ($data) {
                foreach($data as $clave) {
                    $nombre = $clave['nombre'];
                    $referencia = $clave['referencia'];
                    $peso = $clave['peso'];
                    $precio = $clave['precio'];
                    $categoria = $clave['categoria'];
                    $stock = $clave['stock'];

                    if($controlador->ModificarProducto($nombre, $referencia, $peso, $precio, $categoria, $stock)){
                        $this->response(200, "OK", "Actualizacion realizada con exito");
                    } else {
                        $this->response(500, "Error", "No se pudo modificar el registro");
                    }
                }
                
            } else {
                $this->response(400, "Error", "Datos de entrada Vacios");
            }
        }

        function EliminarProducto(){
            $data = json_decode(file_get_contents('php://input'), true);
            if($data){
                $controlador = new Controlador();
                $bandera = 0;
                $referenciaMal = "";
                foreach($data as $clave) {
                    $validaReferencia = $this->consultarReferenciaProducto($clave['referencia']);
                    if($validaReferencia){
                        $response = $controlador->EliminarProducto($clave['referencia']);
                    } else {
                        $bandera = 1;
                        $referenciaMal = $clave['referencia'].", ".$referenciaMal;
                    }
                }
                if($bandera==0) {
                    if($response){
                        $this->response(200, "OK", "Producto eliminado");
                    } else {
                        $this->response(500, "Error", "No fue posible eliminar el o los productos");
                    }
                } else {
                    $this->response(404, "Not Found", "La o las referencias no existen: ".$referenciaMal);
                }
                
            } else {
                $this->response(400, "Error", "Datos de entrada Vacios");
            }
            
        }
        function consultarReferenciaProducto($referencia){
            $controlador = new Controlador();
            $response = $controlador->consultarReferenciaProducto($referencia);
            if(is_array($response)) {
                $retorno = true;
            } else {
                $retorno = false;
            }
            return $retorno;
        }
        function ObtenerListadoProductoInventario()
        {
            $controlador = new Controlador();
            $response = $controlador->ObtenerListadoProductoInventario();
            echo json_encode($response, JSON_PRETTY_PRINT);
        }

        function response($code = 200, $status = "", $message = "")
        {
            http_response_code($code);
            if (!empty($status) && !empty($message))
            {
                $response = array(
                    "status" => $status,
                    "message" => $message
                );
                echo json_encode($response, JSON_PRETTY_PRINT);
            }
        }
    }
    
