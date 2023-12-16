<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "config.php";
include_once "entidades/usuario.php";

$pg = "Listado de usuarios";

$usuario = new Usuario();
$aUsuarios = $usuario->obtenerTodos();

include_once "header.php";
?>


<div class="container-fluid">
    <!-- cabecera de la pagina -->
    <h1 class="h3 mb-4 text-gray-800">Listado de usuarios</h1>
    <div class="row">
        <div class="col-12 mb-3">
            <a href="usuario-formulario.php" class="btn btn-primary mr-2">Nuevo</a>
        </div>
    </div>
    <table class="table table-hover border">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>correo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($aUsuarios as $usuario) : ?>
                <tr>
                    <td><?php echo $usuario->usuario ?></td>
                    <td><?php echo $usuario->nombre ?></td>
                    <td><?php echo $usuario->apellido ?></td>
                    <td><?php echo $usuario->correo ?></td>
                    <td style="width: 110px;">
                        <a href="usuario-formulario.php?id=<?php echo $usuario->id_usuario; ?>"><i class="fas fa-search"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>