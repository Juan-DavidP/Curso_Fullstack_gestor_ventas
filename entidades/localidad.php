<?php

class Localidad
{
    private $idlocalidad;
    private $nombre;
    private $fk_idprovincia;
    private $cod_postal;

    public function __construct()
    {
    }

    public function __get($atributo)
    {
        return $this->$atributo;
    }

    public function __set($atributo, $valor)
    {
        $this->$atributo = $valor;
        return $this;
    }

     public function obtenerPorProvincia($idProvincia)
    {
        $aLocalidades = array();
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        $sql = "SELECT * FROM localidades  WHERE fk_id_provincia = $idProvincia  ORDER BY id_localidad ASC;";
        $resultado = $mysqli->query($sql);

        while ($fila = $resultado->fetch_assoc()) {
            $entidad = new Localidad();
            $entidad->idlocalidad = $fila["id_localidad"];
            $entidad->nombre = $fila["nombre"];
            $entidad->cod_postal = $fila["cod_postal"];
            $entidad->fk_idprovincia = $fila["fk_id_provincia"];

            $aLocalidades[] = $entidad;
        }
        return $aLocalidades;
    }
}

/*
   public function obtenerPorProvincia($idProvincia)
    {
        $aLocalidades = null;
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        $sql = "SELECT *
            FROM localidades 
            WHERE fk_idprovincia = $idProvincia
            ORDER BY idlocalidad ASC";
        $resultado = $mysqli->query($sql);

        while ($fila = $resultado->fetch_assoc()) {
            $aLocalidades[] = array(
                "idlocalidad" => $fila["idlocalidad"],
                "nombre" => $fila["nombre"],
                "cod_postal" => $fila["cod_postal"]
            );
        }
        return $aLocalidades;
    }*/
