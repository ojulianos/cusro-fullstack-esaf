<?php
require_once "vendor/autoload.php";

use Sys\Bi\Controllers\Afazeres;
use Sys\Bi\Controllers\Usuarios;

$page = $_REQUEST['page'] ?? 'index';
$afazeres = new Afazeres;

// Tela de afazeres
if (method_exists($afazeres, $page)) {
    $afazeres->$page();
} else {
    $afazeres->index();
}

// Tela de usuários
// $usuarios = new Usuarios;

// if (method_exists($usuarios, $page)) {
//     $usuarios->$page();
// } else {
//     $usuarios->index();
// }
