<?php
namespace Sys\Bi\Common;

use Closure;
use Exception;
use Throwable;

/**
 * Classe Router
 * @package Sys\Bi\Common
 */
class Router {

    protected $routes = [];
    protected $namespace = '';

    /**
     * Adiciona uma rota à tabela de rotas.
     *
     * @param string $method Método HTTP (GET, POST, etc.)
     * @param string $url URL da rota
     * @param mixed $target Alvo da rota (Closure ou string no formato "Classe@Método")
     */
    public function addRoute(string $method, string $url, $target) {
        $this->routes[$method][$url] = $target;
    }

    /**
     * Define o namespace base para as rotas.
     *
     * @param string $namespace Namespace base para as rotas
     */
    public function setNamespace(string $namespace) {
        $this->namespace = $namespace;
    }

    /**
     * Combina e executa a rota correspondente à requisição.
     *
     * @throws Exception Se a rota não for encontrada
     */
    public function matchRoute() {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = $_SERVER['REQUEST_URI'];
        
        // Remove o BASE_URL da URL para comparar com as rotas
        $url = str_replace(BASE_URL, '', $url);
        
        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $routeUrl => $target) {
                $pattern = preg_replace('/\/:([^\/]+)/', '/(?P<$1>[^/]+)', $routeUrl);
                if (preg_match('#^' . $pattern . '$#', $url, $matches)) {
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                    
                    // Verifica se o target é uma string (Classe@Método) ou Closure
                    if (is_string($target)) {
                        [$class, $method] = explode('@', $target);
                        $class = $this->namespace . '\\' . $class;
                        if (!class_exists($class)) {
                            throw new Exception("Classe não encontrada: $class");
                        }
                        $controller = new $class;
                        if (!method_exists($controller, $method)) {
                            throw new Exception("Método não encontrado: $method");
                        }
                        call_user_func_array([$controller, $method], $params);
                    } elseif ($target instanceof Closure) {
                        call_user_func_array($target, $params);
                    } else {
                        throw new Exception('Target inválido');
                    }
                    return;
                }
            }
        }
        throw new Exception('Rota não encontrada');
    }
}
