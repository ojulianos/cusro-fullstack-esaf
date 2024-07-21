<?php
namespace Sys\Bi\Common;

use Closure;
use Exception;


/**
 * Class Router
 * Classe responsável por gerenciar as rotas do sistema.
 * @package Sys\Bi\Common
 */
class Router {

    /**
     * @var array Array associativo de rotas
     */
    protected $routes = [];

    /**
     * @var string Namespace para os controladores
     */
    protected $namespace = '';

    /**
     * @var string Classe de controlador de erro
     */
    protected $errorController = 'Sys\Bi\Common\ErrorController';

    /**
     * Adiciona um namespace para os controladores.
     *
     * @param string $namespace
     */
    public function addNamespace(string $namespace) {
        $this->namespace = rtrim($namespace, '\\') . '\\';
    }

    /**
     * Adiciona uma rota à tabela de roteamento.
     *
     * @param string $method Método HTTP (GET, POST, PUT, DELETE)
     * @param string $url URL da rota
     * @param Closure|string $target Método do controlador alvo ou closure
     */
    public function addRoute(string $method, string $url, $target) {
        $this->routes[$method][$url] = $target;
    }

    /**
     * Tenta corresponder a requisição atual a uma rota.
     *
     * @throws Exception Se a rota ou método não for encontrado
     */
    public function matchRoute() {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = strtok($_SERVER['REQUEST_URI'], '?'); // Ignora a string de consulta
        $queryParams = [];
        parse_str($_SERVER['QUERY_STRING'] ?? '', $queryParams);

        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $routeUrl => $target) {
                $pattern = preg_replace('/\/:([^\/]+)/', '/(?P<$1>[^/]+)', $routeUrl);
                if (preg_match('#^' . $pattern . '$#', $url, $matches)) {
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                    $params = array_merge($params, $queryParams);

                    if ($target instanceof Closure) {
                        call_user_func_array($target, $params);
                    } else if (is_string($target)) {
                        list($classPath, $method) = explode('@', $target);
                        $fullClassPath = $this->namespace . str_replace('/', '\\', $classPath);
                        if (class_exists($fullClassPath)) {
                            $obj = new $fullClassPath();
                            if (method_exists($obj, $method)) {
                                call_user_func_array([$obj, $method], $params);
                            } else {
                                $this->handleError();
                            }
                        } else {
                            $this->handleError();
                        }
                    }
                    return;
                }
            }
        }
        $this->handleError();
    }

    /**
     * Lida com erros chamando o método notFound do controlador de erro.
     *
     * @throws Exception Se o controlador de erro ou o método notFound não forem encontrados
     */
    protected function handleError() {
        $errorController = $this->errorController;
        if (class_exists($errorController) && method_exists($errorController, 'notFound')) {
            $controller = new $errorController();
            $controller->notFound();
        } else {
            throw new Exception('ErrorController ou método notFound não encontrados');
        }
    }
}
