<?php
namespace Sys\Bi\Common;

/**
 * Classe ErrorController
 * @package Sys\Bi\Common
 */
class ErrorController extends BaseController {

    /**
     * Lida com erros 404 Not Found.
     */
    public function notFound() {
        http_response_code(404);
        echo $this->twig('errors/404.twig');
    }

}