<?php

namespace Sys\Bi\Controllers;

use Exception;
use Throwable;

class BaseController
{

    public function __construct($logado = true)
    {
        if ($logado) {
            $this->estaLogado();
        }
    }

    protected function twig(string $template, array $params)
    {
        $loader = new \Twig\Loader\FilesystemLoader(BASE_DIR . '/src/Views/');
        $twig = new \Twig\Environment($loader, [
            // 'cache' => '/cache',
        ]);
        $twig->addGlobal('TITLE', TITLE);
        $twig->addGlobal('SESSION', $_SESSION);

        return $twig->render($template, $params);
    }

    private function estaLogado()
    {
        if ($_SESSION["estaLogado"] != true) {
            $_SESSION["mensagemLogado"] = "Usuario nao logado";
            header("Location: index.php");
        }
    }

    public function sair()
    {
        session_destroy();
        header("Location: index.php");
    }
}
