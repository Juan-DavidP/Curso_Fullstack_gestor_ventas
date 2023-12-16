<?php

class Usuario
{
    private $id_usuario;
    private $usuario;
    private $clave;
    private $nombre;
    private $apellido;
    private $correo;

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
        $this->id_usuario = isset($request["id"]) ? $request["id"] : "";
        $this->nombre = isset($request["txtNombre"]) ? $request["txtNombre"] : "";
        $this->apellido = isset($request["txtApellido"]) ? $request["txtApellido"] : "";
        $this->usuario = isset($request["txtUsuario"]) ? $request["txtUsuario"] : "";
        $this->clave = isset($request["txtClave"]) && $request["txtClave"] != "" ? $this->encriptarClave($request["txtClave"]) : "";
        $this->correo = isset($request["txtCorreo"]) ? $request["txtCorreo"] : "";
    }

    public function insertar()
    {
        //Crear la variable de conexión a la base de datos
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);

        //Creación de la query SQL
        $sql = "INSERT INTO usuarios(usuario, clave, nombre, apellido, correo)  values 
        (
        '$this->usuario',
        '$this->clave',
        '$this->nombre', 
        '$this->apellido',
        '$this->correo'
        );";

        //Ejecución de la query
        if (!$mysqli->query($sql)) {
            print_f("Error en la query%s\n", $mysqli->error . "" . $sql);
        }

        //obtener el id del valor insertado
        $this->id_usuario = $mysqli->insert_id;

        //cerrar la conexión a la base de datos
        $mysqli->close();
    }

    public function eliminar()
    {
        //Crear la variable de conexión a la base de datos
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);

        //Creación de la query SQL
        $sql = "DELETE FROM usuarios WHERE id_usuario=" . $this->id_usuario;

        //Ejecución de la query
        if (!$mysqli->query($sql)) {
            print_f("Error en la query%s\n", $mysqli->error . "" . $sql);
        }
        //cerrar la conexión a la base de datos
        $mysqli->close();
    }

    public function actualizar()
    {
        //Crear la variable de conexión a la base de datos
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        $sql = "";
        if ($this->clave != "") {
            //Creación de la query SQL
            $sql = "UPDATE usuarios SET 
        usuario ='" . $this->usuario . "',
        clave ='" . $this->clave . "',
        nombre ='" . $this->nombre . "',
        apellido = '" . $this->apellido . "',
        correo ='" . $this->correo . "' WHERE id_usuario = " . $this->id_usuario;
        } else {
            $sql = "UPDATE usuarios SET 
            usuario ='" . $this->usuario . "',
            nombre ='" . $this->nombre . "',
            apellido = '" . $this->apellido . "',
            correo ='" . $this->correo . "' WHERE id_usuario = " . $this->id_usuario;
        }

        //Ejecución de la query
        if (!$mysqli->query($sql)) {
            print_f("Error en la query%s\n", $mysqli->error . "" . $sql);
        }
        //cerrar la conexión a la base de datos
        $mysqli->close();
    }

    public function obtenerTodos()
    {
        //Crear la variable de conexión a la base de datos
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);

        //creación de la query SQL
        $sql = "SELECT * FROM usuarios";

        //Ejecución de la query
        if (!$resultado = $mysqli->query($sql)) {
            print_f("Error en la query%s\n", $mysqli->error . "" . $sql);
        }

        $aResultado = array();
        if ($resultado) {

            while ($fila = $resultado->fetch_assoc()) {
                $entidadAux = new Usuario();
                $entidadAux->id_usuario = $fila["id_usuario"];
                $entidadAux->usuario = $fila["usuario"];
                $entidadAux->clave = $fila["clave"];
                $entidadAux->nombre = $fila["nombre"];
                $entidadAux->apellido = $fila["apellido"];
                $entidadAux->correo = $fila["correo"];

                $aResultado[] = $entidadAux;
            }
        }
        return $aResultado;

        //cerrar la conexión a la base de datos
        $mysqli->close();
    }

    public function obtenerPorId()
    {
        //Crear la variable de conexión a la base de datos
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);

        //creación de la query SQL
        $sql = "SELECT * FROM usuarios WHERE id_usuario=" . $this->id_usuario;

        //Ejecución de la query
        if (!$resultado = $mysqli->query($sql)) {
            print_f("Error en la query%s\n", $mysqli->error . "" . $sql);
        }

        while ($fila = $resultado->fetch_assoc()) {
            $this->usuario = $fila["usuario"];
            $this->clave = $fila["clave"];
            $this->nombre = $fila["nombre"];
            $this->apellido = $fila["apellido"];
            $this->correo = $fila["correo"];
        }

        //Cerrar la conexión a la base de datos
        $mysqli->close();
    }

    public function obtenerPorUsuario($usuario, $id_usuario = "")
    {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' <> '$id_usuario'";
        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        //Convierte el resultado en un array asociativo
        if ($fila = $resultado->fetch_assoc()) {
            $this->id_usuario = $fila["id_usuario"];
            $this->usuario = $fila["usuario"];
            $this->clave = $fila["clave"];
            $this->nombre = $fila["nombre"];
            $this->apellido = $fila["apellido"];
            $this->correo = $fila["correo"];
            return true;
        } else {
            return false;
        }
        $mysqli->close();
    }
    public function obtenerPorCorreo($correo, $id_usuario = "")
    {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        $sql = "SELECT * FROM usuarios WHERE correo = '$correo' AND id_usuario <> '$id_usuario'";
        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        //Convierte el resultado en un array asociativo
        if ($fila = $resultado->fetch_assoc()) {
            $this->id_usuario = $fila["id_usuario"];
            $this->usuario = $fila["usuario"];
            $this->clave = $fila["clave"];
            $this->nombre = $fila["nombre"];
            $this->apellido = $fila["apellido"];
            $this->correo = $fila["correo"];
            return true;
        } else {
            return false;
        }
        $mysqli->close();
    }

    public function encriptarClave($clave)
    {
        $claveEncriptada = password_hash($clave, PASSWORD_DEFAULT);
        return $claveEncriptada;
    }

    public function verificarClave($claveIngresada, $claveEnBBDD)
    {
        return password_verify($claveIngresada, $claveEnBBDD);
    }
}
