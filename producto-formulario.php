<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "config.php";
include_once "entidades/producto.php";
include_once "entidades/tipoproducto.php";

$producto = new Producto();
$producto->cargarFormulario($_REQUEST);

$pg = "Listado de productos";

if ($_POST) {
    //logica boton guardar 
    $producto->nombre = $_POST["txtNombre"];
    $producto->cantidad = $_POST["txtCantidad"];
    $producto->fk_idtipo_producto = $_POST["lstTipoProducto"];
    $producto->precio = $_POST["txtPrecio"];
    $producto->descripcion = $_POST["txtDescripcion"];
    $producto->imagen =  $_FILES["iFile"]["name"];
    if (isset($_POST["btnGuardar"])) {
        if ($_FILES["iFile"]["error"] === UPLOAD_ERR_OK) {
            $nombreAleatorio = rand(1, 3000);
            $archivo_tmp = $_FILES["iFile"]["tmp_name"];
            $extension = strtolower(pathinfo($_FILES["iFile"]["name"], PATHINFO_EXTENSION));
            if ($extension == "jpg" || $extension == "jpeg" || $extension == "png") {
                $nombreArchivo = pathinfo($_FILES["iFile"]["name"], PATHINFO_FILENAME);
                $nombreImagen = "$nombreArchivo$nombreAleatorio.$extension";
                move_uploaded_file($archivo_tmp, "img/$nombreImagen");
            }
        }
        if (isset($_GET["id"]) && $_GET["id"] > 0) {
            //Actualizo un cliente existente 

            $productoAnterior = new Producto();
            $productoAnterior->idProducto = $_GET["id"];
            $productoAnterior->obtenerPorId();
            $imagenAnterior = $productoAnterior->imagen;
            if ($_FILES["iFile"]["error"] === UPLOAD_ERR_OK) {
                if ($imagenAnterior != "") {
                    if (file_exists("img/$imagenAnterior")) {
                        unlink("img/$imagenAnterior");
                    }
                }
            } else {
                $nombreImagen = $imagenAnterior;
            }
            $producto->imagen = $nombreImagen;
            $producto->actualizar();
        } else {
            //Es Nuevo
            $producto->imagen = $nombreImagen;
            $producto->insertar();
        }
        $msg["texto"] = "Guardado correctamente";
        $msg["codigo"] = "alert-success";
    } elseif (isset($_POST["btnEliminar"])) {
        //logica boton eliminar
        $producto->eliminar();
        $msg["texto"] = "Eliminado correctamente";
        $msg["codigo"] = "alert-danger";
        header("Location: producto-listado.php");
    }
}

if (isset($_GET["id"]) && $_GET["id"] >= 0) {
    $id = trim($_GET["id"]);
    $producto->idProducto = $id;
    $producto->obtenerPorId();
}


$tipoProducto = new TipoProducto();

$aTipoProductos = $tipoProducto->obtenerTodos();

include_once "header.php";

?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Formulario Productos</h1>
    <?php if (isset($msg)) : ?>
        <div class="row">
            <div class="col-12">
                <div class="alert <?php echo $msg["codigo"]; ?>" role="alert">
                    <?php echo $msg["texto"] ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="row">
        <a href="producto-listado.php" class="btn btn-primary mr-2">Listado</a>
        <a href="producto-formulario.php" class="btn btn-primary mr-2">Nuevo</a>
        <button type="submit" class="btn btn-success mr-2" id="btnGuardar" name="btnGuardar">Guardar</button>
        <button type="submit" class="btn btn-danger mr-2" id="btnEliminar" name="btnEliminar">Eliminar</button>
    </div>
    <div class="row">
        <div class="col-6 form">
            <label for="txtNombre" class="h5 mt-3">Nombre:</label>
            <input type="text" required class="form-control border-solid" name="txtNombre" id="txtNombre" value="<?php echo $producto->nombre; ?>">
            <label for="txtCantidad" class="h5 mt-3">Cantidad:</label>
            <input type="text" required class="form-control border-solid" name="txtCantidad" id="txtCantidad" value="<?php echo $producto->cantidad; ?>">
        </div>
        <div class="col-6 form">
            <label for="txtTipoProducto" class="h5 mt-3 d-block">Tipo de producto:</label>
            <select class="form-control" name="lstTipoProducto" id="lstTipoProducto" required>
                <?php /*if (isset($_GET["id"]) && $_GET["id"] >= 0) :
                    $id = $_GET["id"]; ?>
                    <option value="" disabled>Seleccionar</option>
                    <option value="<?php $tipoProducto->id ?>" selected><?php echo $aTipoProductos[$id]->nombre ?></option>
                    <?php foreach ($aTipoProductos as $tipoProducto) : ?>
                        <option value="<?php $tipoProducto->id_tipoProducto; ?>"><?php echo $tipoProducto->nombre ?></option>
                    <?php endforeach;
                else : */ ?>
                <option value="" disabled selected>Seleccionar</option>
                <?php foreach ($aTipoProductos as $tipoProducto) : ?>
                    <option value="<?php echo $tipoProducto->id_tipoProducto; ?>"><?php echo $tipoProducto->nombre; ?></option>
                <?php endforeach;
                //endif; 
                ?>
            </select>
            <label for="txtPrecio" class="h5 mt-3">Precio:</label>
            <input type="text" required class="form-control border-solid" name="txtPrecio" id="txtPrecio" value="<?php echo number_format($producto->precio, 0, ",", "."); ?>">
        </div>
        <div class=" col-12">
            <label for="txtDescripcion" class="h5 mt-3">Descripción:</label>
            <textarea name="txtDescripcion" id="txtDescripcion" class="form-control" style="resize: none"><?php echo $producto->descripcion; ?></textarea>
            <script>
                ClassicEditor
                    .create(document.querySelector('#txtDescripcion'))
                    .catch(error => {
                        console.error(error);
                    });
            </script>
            <label for="iFile" class="h5 mt-3">Imagen:</label>
            <input type="file" name="iFile" id="iFile" class="d-block" accept=".jpg .jpge .png">
        </div>
    </div>
</div>