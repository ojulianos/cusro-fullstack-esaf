<?php

use Sys\Bi\Common\Router;

require_once "../vendor/autoload.php";

$router = new Router();

// Define o namespace base para as rotas
$router->addNamespace('App\Controllers');

// Adicione as rotas que apontam para os métodos correspondentes do UserController
$router->addRoute('GET', '/cusro-fullstack-esaf/bi/public/user/:id', 'Usuarios@getUser');
$router->addRoute('POST', '/cusro-fullstack-esaf/bi/public/user', 'Usuarios@createUser');
$router->addRoute('PUT', '/cusro-fullstack-esaf/bi/public/user/:id', 'Usuarios@updateUser');
$router->addRoute('DELETE', '/cusro-fullstack-esaf/bi/public/user/:id', 'Usuarios@deleteUser');

$router->addRoute('GET', '/cusro-fullstack-esaf/bi/public/user/:id', function($id){
    echo 'teste - ' . $id;
    exit;
});

// Tente corresponder a rota atual
try {
    $router->matchRoute();
} catch (Exception $e) {
    echo $e->getMessage();
}