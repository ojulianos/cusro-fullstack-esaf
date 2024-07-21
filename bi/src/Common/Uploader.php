<?php
namespace Sys\Bi\Common;

use Exception;

/**
 * Classe Uploader
 * @package Sys\Bi\Common
 */
class Uploader {

    /**
     * Diretório onde os arquivos serão armazenados
     * @var string
     */
    protected $uploadDir;

    /**
     * Construtor da classe Uploader
     *
     * @param string $uploadDir Subdiretório onde os arquivos serão armazenados
     */
    public function __construct(string $uploadDir = 'uploads') {
        $this->uploadDir = BASE_DIR . '/' . $uploadDir;
        
        // Cria o diretório de upload se não existir
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    /**
     * Faz o upload de um arquivo.
     *
     * @param array $file Dados do arquivo enviado ($_FILES['file'])
     * @return string|false Caminho do arquivo salvo em caso de sucesso, falso em caso de falha
     */
    public function upload(array $file) {
        // Verifica se houve um erro no upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        // Gera um nome único para o arquivo
        $fileName = uniqid() . '_' . basename($file['name']);
        $destination = $this->uploadDir . '/' . $fileName;

        // Move o arquivo para o diretório de upload
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $fileName;
        } else {
            return false;
        }
    }

    /**
     * Faz o download de um arquivo.
     *
     * @param string $fileName Nome do arquivo a ser baixado
     * @return void
     */
    public function download(string $fileName) {
        $filePath = $this->uploadDir . '/' . $fileName;

        if (file_exists($filePath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));
            flush(); // Limpa o buffer do sistema
            readfile($filePath);
            exit;
        } else {
            http_response_code(404);
            echo 'Arquivo não encontrado.';
            exit;
        }
    }
}
