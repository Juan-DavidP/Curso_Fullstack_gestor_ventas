<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "config.php";
include_once "entidades/producto.php";

$pg = "Listado de productos";

$producto = new Producto();
$aProducto = $producto->obtenerTodos();

include_once("header.php");
?>

<div class="container-fluid">
    <!-- cabecera de la pagina -->
    <h1 class="h3 mb-4 text-gray-800">Listado de productos</h1>
    <div class="row">
        <div class="col-12 mb-3">
            <a href="producto-formulario.php" class="btn btn-primary mr-2">Nuevo</a>
        </div>
    </div>
    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th>Imagen</th>
                <th>ID producto</th>
                <th>Nombre</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($aProducto as $producto) : ?>
                <tr>
                    <td><img src="img/<?php echo $producto->imagen; ?>" alt=" <?php echo $producto->nombre; ?>" height="130px"></td>
                    <td><?php echo $producto->idProducto; ?></td>
                    <td><?php echo $producto->nombre; ?></td>
                    <td><?php echo $producto->cantidad; ?></td>
                    <td><?php echo $producto->precio; ?></td>
                    <td><?php echo $producto->descripcion; ?></td>
                    <td>
                        <a href="producto-formulario.php?id=<?php echo $producto->idProducto; ?>"><i class="fas fa-search"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<? include_once "footer.php" ?>