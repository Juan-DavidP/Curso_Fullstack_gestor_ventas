<?php

class TipoProducto
{
    private $id_tipoProducto;
    private $nombre;

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

    public function cargarFormulario($request)
    {
        $this->id_tipoProducto = isset($request["id"]) ? $request["id"] : "";
        $this->nombre = isset($request["txtNombre"]) ? $request["txtNombre"] : "";
    }

    public function insertar()
    {
        //Crear la variable de conexión a la base de datos
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);

        //Creación de la query SQL
        $sql = "INSERT INTO tipo_productos (nombre) VALUES ('$this->nombre');";

        //Ejecutar la query
        if (!$mysqli->query($sql)) {
            printf("Error en la query: %s\n", $mysqli->error . "" . $sql);
        }
        //obtener el id generado
        $this->id_tipoProducto = $mysqli->insert_id;
        //Cerrar la conexión con la base de datos
        $mysqli->close();
    }

    public function eliminar()
    {
        //Crear la variable de conexión a la base de datos
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);

        //Creación de la query SQL
        $sql = "DELETE FROM tipo_productos WHERE id_tipo_producto = " . $this->id_tipoProducto . ";";

        //Ejecutar la query
        if (!$mysqli->query($sql)) {
            printf("Error en la query: %s\n", $mysqli->error . "" . $sql);
        }

        //Cerrar la conexión con la base de datos
        $mysqli->close();
    }

    public function actualizar()
    {
        //Crear la variable de conexión a la base de datos
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);

        //creación de la query SQL
        $sql = "UPDATE tipo_productos SET
        nombre = ' " . $this->nombre . "' WHERE id_tipo_producto =" . $this->id_tipoProducto;

        //Ejecución de la query
        if (!$mysqli->query($sql)) {
            printf("Error en la query: %s\n", $mysqli->error . "" . $sql);
        }
        //Cerrar la conexión a la DB
        $mysqli->close();
    }

    public function obtenerPorId()
    {
        //Crear la variable de conexión a la base de datos
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);

        //Creacion de la query SQL
        $sql = "SELECT * FROM tipo_productos WHERE id_tipo_producto=" . $this->id_tipoProducto . ";";

        //Ejecución de la query
        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en la query: %s\n", $mysqli->error . "" . $sql);
        }

        //Convirtiendo la respuesta en un array asociativo
        if ($fila = $resultado->fetch_assoc()) {
            $this->id_tipoProducto = $fila["id_tipo_producto"];
            $this->nombre = $fila["nombre"];
        }

        //Cerrar la conexión a la base de datos
        $mysqli->close();
    }

    public function obtenerTodos()
    {
        //Crear la variable de conexión a la base de datos
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);

        //creación de la query SQL
        $sql = "SELECT * FROM tipo_productos";

        //Ejecución de la query
        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en la query: %s\n", $mysqli->error . "" . $sql);
        }

        $aResultado = array();
        //Convirtiendo la respuesta en un array asociativo
        while ($fila = $resultado->fetch_assoc()) {
            //Definiendo una instacia de clase para organizar los datos
            $variableAux = new TipoProducto;
            $variableAux->id_tipoProducto = $fila["id_tipo_producto"];
            $variableAux->nombre = $fila["nombre"];
            $aResultado[] = $variableAux;
        }
        return $aResultado;
    }
}
