<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "config.php";
include_once "entidades/tipoproducto.php";

$tipoProducto = new TipoProducto();

$pg = "Formulario Tipos de productos";

include_once "header.php";

if ($_POST) {
    /* mi código
    $tipoProducto->nombre = $_POST["txtNombre"];
    if (isset($_POST["btnGuardar"])) {
        if (isset($_GET["idTipoProducto"]) && $_GET["idTipoProducto"] > 0) {
            //Actualizo un tipo de producto existente
            $tipoProducto->actualizar();
        } else {
            //Se crea un nuevo tipo de producto
            $tipoProducto->insertar();
        }
        $msg["texto"] = "Guardado correctamente";
        $msg["codigo"] = "alert-success";
    }*/
    if (isset($_POST["btnGuardar"])) {
        $tipoProducto->cargarFormulario($_REQUEST);

        if (isset($_GET["id"]) && $_GET["id"] >= 0) {
            $tipoProducto->actualizar();
        } else {
            $tipoProducto->insertar();
        }
    }

    $msg["texto"] = "Guardado correctamente";
    $msg["codigo"] = "alert-success";

    if (isset($_POST["btnEliminar"])) {
        if (isset($_GET["id"]) && $_GET["id"] > 0) {
            $tipoProducto->id_tipoProducto = $_GET["id"];
            $tipoProducto->eliminar();
        }
        $msg["texto"] = "Eliminado correctamente";
        $msg["codigo"] = "alert-success";
    }
}

if (isset($_GET["id"]) && $_GET["id"] >= 0) {
    $id = trim($_GET["id"]);
    $tipoProducto->id_tipoProducto = $id;
    $tipoProducto->obtenerPorId();
}

?>

<div class="container-fluid">
    <!-- cabecera de la pagina -->
    <h1 class="h3 mb-4 text-gray-800">Tipo de Productos</h1>
    <?php if (isset($msg)) : ?>
        <div class="row">
            <div class="col-12">
                <div class="alert <?php echo $msg["codigo"]; ?>" role="alert">
                    <?php echo $msg["texto"]; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="row">
        <a href="tipo_producto-listado.php" class="btn btn-primary mr-2">Listado</a>
        <a href="tipo_producto-formulario.php" class="btn btn-primary mr-2">Nuevo</a>
        <button type="submit" class="btn btn-success mr-2" id="btnGuardar" name="btnGuardar">Guardar</button>
        <button type="submit" class="btn btn-danger" id="btnEliminar" name="btnEliminar">Eliminar</button>
    </div>
    <div class="row">
        <div class="col-6 form">
            <label for="txtNombre" class="h5 mt-5">Nombre:</label>

            <input type="text" required class="form-control border-solid" name="txtNombre" id="txtNombre" value="<?php echo $tipoProducto->nombre; ?>">
        </div>
    </div>
</div>