<?php

include_once "config.php";
include_once "entidades/tipoproducto.php";

$pg = "Listado de tipos de productos";

$tipoProducto = new TipoProducto();
$aTipoProducto = $tipoProducto->obtenerTodos();

include_once("header.php");
?>

<div class="container-fluid">
    <!-- cabecera de la pagina -->
    <h1 class="h3 mb-4 text-gray-800">Listado de tipos de productos</h1>
    <div class="row">
        <div class="col-12 mb-3">
            <a href="tipo_producto-formulario.php" class="btn btn-primary mr-2">Nuevo</a>
        </div>
    </div>
    <table class="table table-hover border">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($aTipoProducto as $tipoProducto) : ?>
                <tr>
                    <td><?php echo $tipoProducto->nombre ?></td>
                    <td style="width: 110px;">
                        <a href="tipo_producto-formulario.php?id=<?php echo $tipoProducto->id_tipoProducto; ?>"><i class="fas fa-search"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include_once "footer.php" ?>