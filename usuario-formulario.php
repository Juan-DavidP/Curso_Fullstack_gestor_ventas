<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "config.php";
include_once "entidades/usuario.php";

$usuario = new Usuario();
$usuario->cargarFormulario($_REQUEST);

$pg = "Formulario de usuarios";

include_once "header.php";

if ($_POST) {

    $usuarioAux = new Usuario();
    if ($usuario->obtenerPorUsuario($usuario->usuario, $usuario->id_usuario)) {
        //Ya existe un usuario con ese nombre de usuario
        $msg["existente"] = "El usuario ya existe";
    } else if ($usuario->obtenerPorCorreo($usuario->correo, $usuario->id_usuario)) {
        //Ya existe un usuario con ese correo
        $msg["existente"] = "El correo ya se encuentra en uso";
    } else {
        if (isset($_POST["btnGuardar"])) {
            if (isset($_GET["id"]) && $_GET["id"] >= 0) {
                $usuario->actualizar();
            } else {
                $usuario->insertar();
            }
            $msg["texto"] = "Guardado correctamente";
            $msg["codigo"] = "alert-success";
        }
        if (isset($_POST["btnEliminar"])) {
            if (isset($_GET["id"]) && $_GET["id"] > 0) {
                $usuario->id_usuario = $_GET["id"];
                $tipoProducto->eliminar();
            }
            $msg["texto"] = "Eliminado correctamente";
            $msg["codigo"] = "alert-success";
        }
    }
}

if (isset($_GET["id"]) && $_GET["id"] >= 0) {
    $id = trim($_GET["id"]);
    $usuario->id_usuario = $id;
    $usuario->obtenerPorId();
}
?>

<div class="container-fluid">
    <!-- cabecera de la pagina -->
    <h1 class="h3 mb-4 text-gray-800">Formulario usuarios</h1>
    <?php if (isset($msg) && !$msg["existente"]) : ?>
        <div class="row">
            <div class="col-12">
                <div class="alert <?php echo $msg["codigo"]; ?>" role="alert">
                    <?php echo $msg["texto"]; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="row">
        <a href="usuario-listado.php" class="btn btn-primary mr-2">Listado</a>
        <a href="usuario-formulario.php" class="btn btn-primary mr-2">Nuevo</a>
        <button type="submit" class="btn btn-success mr-2" id="btnGuardar" name="btnGuardar">Guardar</button>
        <button type="submit" class="btn btn-danger" id="btnEliminar" name="btnEliminar">Eliminar</button>
    </div>
    <?php if (isset($msg["existente"])) :  ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-danger" role="alert">
                    <?php echo $msg["existente"]; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-6 form">
            <label for="txtNombre" class="h5 mt-3">Nombre:</label>
            <input type="text" required class="form-control border-solid" name="txtNombre" id="txtNombre" value="<?php echo $usuario->nombre; ?>">
            <label for="txtApellido" class="h5 mt-3">Usuario:</label>
            <input type="text" required class="form-control border-solid" name="txtUsuario" id="txtUsuario" value="<?php echo $usuario->usuario; ?>">
            <label for="txtClave" class="h5 mt-3">Clave:</label>
            <input type="password" required class="form-control border-solid" name="txtClave" id="txtClave" value="">
        </div>
        <div class="col-6 form">
            <label for="txtApellido" class="h5 mt-3">Apellido:</label>
            <input type="text" required class="form-control border-solid" name="txtApellido" id="txtApellido" value="<?php echo $usuario->apellido; ?>">
            <label for="txtCorreo" class="h5 mt-3">Correo:</label>
            <input type="text" required class="form-control border-solid" name="txtCorreo" id="txtCorreo" value="<?php echo $usuario->correo; ?>">
        </div>
    </div>
</div>

<?php include_once "footer.php"; ?>