<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "config.php";
include_once "entidades/venta.php";
include_once "entidades/cliente.php";
include_once "entidades/producto.php";

$venta = new Venta();
$venta->cargarFormulario($_REQUEST);

if ($_POST) {
    if (isset($_POST["btnGuardar"])) {
        if (isset($_GET["id"]) && $_GET["id"] > 0) {
            //Si existe la venta se actualiza
            $venta->actualizar();
        } else {
            //En caso de ser una nueva venta
            $producto = new Producto();
            $producto->idProducto = $venta->fk_idproducto;
            $producto->obtenerPorId();
            if ($venta->cantidad <= $producto->cantidad) {
                $total = $venta->cantidad * $producto->precio;
                $venta->total = $total;
                $venta->insertar();

                $producto->cantidad -= $venta->cantidad;
                // $producto->actualizar();
            } else {
                $msg = "Cantidad a comprar superior a la disponible en inventario";
            }
        }
        $msg["texto"] = "Guardado correctamente";
        $msg["codigo"] = "alert-success";
    } elseif (isset($_POST["btnEliminar"])) {
        $venta->eliminar();
        header("Location: venta-listado.php");
    }
}

if (isset($_GET["do"]) && $_GET["do"] == "buscarProducto") {
    $aResultado = array();
    $idProducto = $_GET["id"];
    $producto = new Producto();
    $producto->idProducto = $idProducto;
    $producto->obtenerPorId();
    $aResultado["precio"] = $producto->precio;
    $aResultado["cantidad"] = $producto->cantidad;
    echo json_encode($aResultado);
    exit;
}

if (isset($_GET["id"]) && $_GET["id"] > 0) {
    $venta->obtenerPorId();
}

$cliente = new Cliente();
$aClientes = $cliente->obtenerTodos();

$producto = new Producto();
$aProductos = $producto->obtenerTodos();

$pg = "Ventas";

include_once "header.php";
?>

<div class="container-fluid">
    <!-- cabecera de la pagina -->
    <h1 class="h3 mb-4 text-gray-800">Formulario de ventas</h1>
    <div class="row">
        <div class="col-12 mb-3">
            <a href="venta-listado.php" class="btn btn-primary mr-2">Listado</a>
            <a href="venta-formulario.php" class="btn btn-primary mr-2">Nuevo</a>
            <button type="submit" class="btn btn-success" name="btnGuardar" id="btnGuardar">Guardar</button>
            <button type="submit" class="btn btn-danger" id="btnEliminar" name="btnEliminar">Eliminar</button>

        </div>
    </div>
    <div class="row">
        <div class="col-12 form-group">
            <label for="lstFechaNac" class="d-block">Fecha y Hora:</label>
            <select name="lstDia" id="lstDia" class="form-control d-inline" style="width: 80px" required>
                <option value="" selected disabled>DD</option>
                <?php for ($i = 1; $i <= 31; $i++) : ?>
                    <?php if ($venta->fecha != "" && $i == date_format(date_create($venta->fecha), "d")) : ?>
                        <option selected><?php echo $i; ?></option>
                    <?php else : ?>
                        <option><?php echo $i; ?></option>
                    <?php endif; ?>
                <?php endfor; ?>
            </select>
            <select class="form-control d-inline" name="lstMes" id="lstMes" style="width: 80px" required>
                <option value="" selected disabled>MM</option>
                <?php for ($i = 1; $i <= 12; $i++) : ?>
                    <?php if ($venta->fecha != "" && $i == date_format(date_create($venta->fecha), "m")) : ?>
                        <option selected><?php echo $i; ?></option>
                    <?php else : ?>
                        <option><?php echo $i; ?></option>
                    <?php endif; ?>
                <?php endfor; ?>
            </select>
            <select class="form-control d-inline" name="lstAnio" id="lstAnio" style="width: 100px" min="2010" required>
                <option selected="" disabled="">YYYY</option>
                <?php for ($i = 1900; $i <= date("Y"); $i++) : ?>
                    <?php if ($venta->fecha != "" && $i == date_format(date_create($venta->fecha), "Y")) : ?>
                        <option selected><?php echo $i; ?></option>
                    <?php else : ?>
                        <option><?php echo $i; ?></option>
                    <?php endif; ?>
                <?php endfor; ?> ?>
            </select>
            <?php if ($venta->fecha == "") : ?>
                <input type="time" class="form-control d-inline" style="width: 120px" name="lstHora" id="lstHora" value="00:00" required>
            <?php else : ?>
                <input type="time" class="form-control d-inline" style="width: 120px" name="lstHora" id="lstHora" required value="<?php echo date_format(date_create($venta->fecha), "H:i"); ?>">
            <?php endif; ?>
        </div>
        <div class="col-6 form-group">
            <label for="txtCliente">Cliente:</label>
            <select class="form-control selectpicker" name="lstCliente" id="lstCliente" required>
                <option value="" disabled selected>Seleccionar</option>
                <?php foreach ($aClientes as $cliente) : ?>
                    <?php if ($cliente->idcliente == $venta->fk_idcliente) : ?>
                        <option selected value="<?php echo $cliente->idcliente; ?>"><?php echo $cliente->nombre; ?></option>
                    <?php else : ?>
                        <option value="<?php echo $cliente->idcliente; ?>"><?php echo $cliente->nombre; ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
            <label for="txtPrecioUni" class="mt-4">Precio unitario:</label>
            <input type="text" class="form-control" name="txtPrecioUni" id="txtPrecioUni" placeholder="0" required>
            <label for="txtTotal" class="mt-4">Total:</label>
            <input type="text" class="form-control" name="txtTotal" id="txtTotal" placeholder="0" required>
        </div>
        <div class="col-6 form-group">
            <label for="lstProducto">Producto:</label>
            <select class="form-control selectpicker" name="lstProducto" id="lstProducto" required>
                <option value="" disabled selected>Seleccionar</option>
                <?php foreach ($aProductos as $producto) : ?>
                    <?php if ($producto->idProducto == $venta->fk_idproducto) : ?>
                        <option selected value="<?php echo $producto->idProducto; ?>"><?php echo $producto->nombre; ?></option>
                    <?php else : ?>
                        <option value="<?php echo $producto->idProducto; ?>"><?php echo $producto->nombre; ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
            <label for="txtCantidad" class="mt-4">Cantidad:</label>
            <input type="text" name="txtCantidad" id="txtCantidad" class="form-control" placeholder="0" required>
        </div>
    </div>

    <?php include_once "footer.php"; ?>