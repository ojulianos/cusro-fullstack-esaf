<?php
namespace Sys\Bi\Common;

use Exception;

/**
 * Classe Uploader
 * @package Sys\Bi\Common
 */
class Uploader {

    /**
     * @var string Diretório de upload
     */
    protected $uploadDir;

    /**
     * Construtor para a classe Uploader
     *
     * @param string $uploadDir Diretório onde os arquivos serão salvos
     */
    public function __construct(string $uploadDir) {
        if (!is_dir($uploadDir)) {
            throw new Exception("O diretório de upload não existe: " . $uploadDir);
        }

        $this->uploadDir = rtrim($uploadDir, '/') . '/';
    }

    /**
     * Faz o upload de um arquivo.
     *
     * @param array $file Arquivo do array $_FILES
     * @return string Caminho completo do arquivo salvo
     * @throws Exception Se ocorrer um erro durante o upload
     */
    public function upload(array $file): string {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Erro ao fazer upload do arquivo: " . $this->fileUploadErrorMessage($file['error']));
        }

        $filename = basename($file['name']);
        $targetPath = $this->uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new Exception("Erro ao mover o arquivo para o diretório de destino");
        }

        return $targetPath;
    }

    /**
     * Faz o download de um arquivo.
     *
     * @param string $filename Nome do arquivo a ser baixado
     * @throws Exception Se o arquivo não existir
     */
    public function download(string $filename) {
        $filePath = $this->uploadDir . basename($filename);

        if (!file_exists($filePath)) {
            throw new Exception("Arquivo não encontrado: " . $filename);
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }

    /**
     * Retorna a mensagem de erro correspondente ao código de erro de upload.
     *
     * @param int $code Código de erro de upload
     * @return string Mensagem de erro
     */
    protected function fileUploadErrorMessage(int $code): string {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                return 'O arquivo excede o tamanho máximo permitido pelo PHP.';
            case UPLOAD_ERR_FORM_SIZE:
                return 'O arquivo excede o tamanho máximo permitido pelo formulário HTML.';
            case UPLOAD_ERR_PARTIAL:
                return 'O arquivo foi parcialmente carregado.';
            case UPLOAD_ERR_NO_FILE:
                return 'Nenhum arquivo foi enviado.';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Diretório temporário não encontrado.';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Erro ao gravar o arquivo no disco.';
            case UPLOAD_ERR_EXTENSION:
                return 'Uma extensão do PHP interrompeu o upload do arquivo.';
            default:
                return 'Erro desconhecido ao fazer upload do arquivo.';
        }
    }
}
