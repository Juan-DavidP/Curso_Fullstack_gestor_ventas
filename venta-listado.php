<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "config.php";
include_once "entidades/venta.php";
include_once "entidades/cliente.php";
include_once "entidades/producto.php";

$venta = new Venta();
$venta->obtenerTodos();

$aVenta = $venta;

foreach ($aVenta as $venta) {
    echo $venta;
}

$pg = "Listado de ventas";

include_once "header.php";

?>



<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Listado de ventas</h1>
    <div class="row">
        <div class="col-12">
            <a href="venta-formulario.php" class="btn btn-primary">Nuevo</a>
        </div>
        <div class="col-12 mt-4">
            <table class="table table-hover border">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Cantidad</th>
                        <th>Producto</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php ?>
                    <tr></tr>
                    <?php ?>
                </tbody>
            </table>
        </div>
    </div>
</div>