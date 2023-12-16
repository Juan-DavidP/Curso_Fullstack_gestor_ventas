<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Producto
{
    private $idProducto;
    private $nombre;
    private $cantidad;
    private $precio;
    private $descripcion;
    private $imagen;
    private $fk_idtipo_producto;

    public function __construct()
    {
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function cargarFormulario($request){
        $this->idProducto = isset($request["id"])? $request["id"] : "";
        $this->nombre = isset($request["txtNombre"])? $request["txtNombre"] : "";
        $this->fk_idtipo_producto = isset($request["lstTipoProducto"])? $request["lstTipoProducto"] : "";
        $this->cantidad = isset($request["txtCantidad"])? $request["txtCantidad"]: 0;
        $this->precio = isset($request["txtPrecio"])? $request["txtPrecio"]: 0;
        $this->descripcion = isset($request["txtDescripcion"])? $request["txtDescripcion"] : "";
    }

    public function insertar()
    {
        //Instaciando la clase mysqli con su constructor parametrizado
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, config::BBDD_NOMBRE, Config::BBDD_PORT);

        //Armando la Query de SQL

        $sql = "INSERT INTO productos (
            nombre,
            cantidad,
            precio,
            descripcion,
            imagen,
            fk_idtipo_producto)  VALUES (
                '$this->nombre',
                $this->cantidad,
                $this->precio,
                '$this->descripcion',
                '$this->imagen',
                '$this->fk_idtipo_producto'
            );";

        //ejecutar la Query
        if (!$mysqli->query($sql)) {
            printf("Error en la query: %s\n", $mysqli->error . "" . $sql);
        }
        //Obtiene el id generado por la inserción
        $this->idProducto = $mysqli->insert_id;
        //Cierra la conexión
        $mysqli->close();
    }

    public function actualizar()
    {
        //Instaciando la clase mysqli con su constructor parametrizado
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, config::BBDD_NOMBRE, Config::BBDD_PORT);

        //Armando la Query de SQL
        $sql = "UPDATE productos SET
                nombre = '$this->nombre',
                fk_idtipo_producto = $this->fk_idtipo_producto,
                cantidad = $this->cantidad,
                precio = $this->precio,
                descripcion = '$this->descripcion',
                imagen = '$this->imagen'
                WHERE idproducto = $this->idProducto";
        /*"UPDATE productos SET
        nombre = '" . $this->nombre . "',
        cantidad =" . $this->cantidad . ",
        precio = " . $this->precio . ",
        descripcion = '" . $this->descripcion . "',
        imagen ='" . $this->imagen .  "',
        fk_idtipo_producto =" . $this->fk_idtipo_producto . "
        WHERE idproducto=" . $this->idProducto . ";";*/
        //ejecutar la Query
        if (!$mysqli->query($sql)) {
            printf("Error en la query: %s\n", $mysqli->error . "" . $sql);
        }
        //Cierra la conexión
        $mysqli->close();
    }

    public function eliminar()
    {
        //Instaciando la clase mysqli con su constructor parametrizado
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, config::BBDD_NOMBRE, Config::BBDD_PORT);

        //Armando la Query de SQL
        $sql = "DELETE FROM  productos WHERE idproducto =" . $this->idProducto;

        //ejecutar la Query
        if (!$mysqli->query($sql)) {
            printf("Error en la query: %s\n", $mysqli->error . "" . $sql);
        }
        //Cierra la conexión
        $mysqli->close();
    }

    public function obtenerTodos()
    {
        //Instaciando la clase mysqli con su constructor parametrizado
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);

        //armando la Query de SQL
        $sql = "SELECT * FROM productos;";

        //ejecutar la Query
        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en la query: %s\n", $mysqli->error . "" . $sql);
        }

        $aResultado = array();
        if ($resultado) {
            //Convierte el resultado en un array asociativo
            while ($fila = $resultado->fetch_assoc()) {
                $entidadAux = new Producto();
                $entidadAux->idProducto = $fila["idproducto"];
                $entidadAux->nombre = $fila["nombre"];
                $entidadAux->cantidad = $fila["cantidad"];
                $entidadAux->precio = $fila["precio"];
                $entidadAux->descripcion = $fila["descripcion"];
                $entidadAux->imagen = $fila["imagen"];
                $aResultado[] = $entidadAux;
            }
        }
        return $aResultado;
    }

    public function obtenerPorId()
    {
        //Instaciando la clase mysqli con su constructor parametrizado
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, COnfig::BBDD_NOMBRE, Config::BBDD_PORT);

        //Armando la Query
        $sql = "SELECT * FROM productos WHERE idproducto =" . $this->idProducto;

        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en la query: %s\n", $mysqli->error . "" . $sql);
        }
        //covierte el resultado en array asociativo
        if ($fila = $resultado->fetch_assoc()) {
            $this->idProducto = $fila["idproducto"];
            $this->nombre = $fila["nombre"];
            $this->cantidad = $fila["cantidad"];
            $this->precio = $fila["precio"];
            $this->descripcion = $fila["descripcion"];
            $this->imagen = $fila["imagen"];
            $this->fk_idtipo_producto = $fila["fk_idtipo_producto"];
        }
        $mysqli->close();
    }
}
