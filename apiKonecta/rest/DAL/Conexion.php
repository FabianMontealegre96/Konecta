<?php // Clase que nos devuelve la conexion con el proveedor que se desee
class Conexion
{
    public static function ObtenerConexion()
    {
        $tipo_de_base = 'mysql';
        $host = 'localhost';
        $nombre_de_base = 'konecta';
        $usuario = 'root';
        $contrasena = '';
        try
        {
            $conn = new PDO("mysql:host=" . $host . ";dbname=" . $nombre_de_base, $usuario, $contrasena, array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
            ));

            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return ($conn);
        }
        catch(PDOException $exception)
        {
            echo $exception->getMessage();
            exit($exception->getMessage());
        }
    }
}

?>
