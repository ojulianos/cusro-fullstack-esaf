<?php
require_once "vendor/autoload.php";

use Sys\Bi\Controllers\Usuarios;

$page = $_REQUEST['page'] ?? 'index';
$usuarios = new Usuarios;

if (method_exists($usuarios, $page)) {
    $usuarios->$page();
} else {
    $usuarios->index();
}
