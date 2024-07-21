<?php
namespace Sys\Bi\Common;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Classe BaseController
 * @package Sys\Bi\Controllers
 */
class BaseController
{
    /**
     * Construtor da classe BaseController
     *
     * @param bool $logado Indica se o usuário deve estar logado
     */
    public function __construct(bool $logado = true)
    {
        if ($logado) {
            $this->estaLogado();
        }
    }

    /**
     * Renderiza um template Twig
     *
     * @param string $template Nome do template Twig
     * @param array $params Parâmetros a serem passados para o template
     * @return string O conteúdo renderizado do template
     */
    protected function twig(string $template, array $params = []): string
    {
        $loader = new FilesystemLoader(BASE_DIR . '/src/Views/');
        $twig = new Environment($loader, [
            'cache' => BASE_DIR . '/cache/twig', // Configura o cache, ajuste conforme necessário
        ]);
        $twig->addGlobal('TITLE', TITLE);
        $twig->addGlobal('SESSION', $_SESSION);

        return $twig->render($template, $params);
    }

    /**
     * Verifica se o usuário está logado e redireciona caso não esteja.
     *
     * @param string $redirectUrl URL para redirecionar se não estiver logado
     */
    private function estaLogado(string $redirectUrl = 'index.php')
    {
        if (empty($_SESSION["estaLogado"]) || $_SESSION["estaLogado"] !== true) {
            $_SESSION["mensagemLogado"] = "Usuário não logado";
            header("Location: $redirectUrl");
            exit;
        }
    }

    /**
     * Finaliza a sessão e redireciona para a página de login.
     */
    public function sair()
    {
        session_destroy();
        header("Location: index.php");
        exit;
    }

    /**
     * Retorna uma resposta JSON de sucesso.
     *
     * @param mixed $data Dados a serem retornados no JSON
     * @param int $status Código de status HTTP (default: 200)
     */
    protected function jsonSuccess($data, int $status = 200)
    {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
        exit;
    }

    /**
     * Retorna uma resposta JSON de erro.
     *
     * @param string $message Mensagem de erro
     * @param int $status Código de status HTTP (default: 400)
     */
    protected function jsonError(string $message, int $status = 400)
    {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode([
            'success' => false,
            'error' => $message
        ]);
        exit;
    }
}
