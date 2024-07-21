<?php
namespace Sys\Bi\Common;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

/**
 * Classe SendMail
 * @package Sys\Bi\Common
 */
class SendMail {

    /**
     * @var PHPMailer Instância do PHPMailer
     */
    protected $mailer;

    /**
     * Construtor para a classe SendMail
     */
    public function __construct() {
        $this->mailer = new PHPMailer(true);

        try {
            // Configurações do servidor de e-mail
            $this->mailer->isSMTP();
            $this->mailer->Host = MAIL_HOST; // Substitua pelo servidor SMTP
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = MAIL_USER; // Substitua pelo e-mail
            $this->mailer->Password = MAIL_PASSWORD; // Substitua pela senha
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Habilita TLS
            $this->mailer->Port = MAIL_PORT; // Porta TCP para TLS
        } catch (PHPMailerException $e) {
            throw new \Exception("Não foi possível configurar o PHPMailer: " . $e->getMessage());
        }
    }

    /**
     * Define o remetente do e-mail.
     *
     * @param string $from Endereço de e-mail do remetente
     * @param string $fromName Nome do remetente
     */
    public function setFrom(string $from, string $fromName = '') {
        $this->mailer->setFrom($from, $fromName);
    }

    /**
     * Define o destinatário do e-mail.
     *
     * @param string $to Endereço de e-mail do destinatário
     */
    public function addAddress(string $to) {
        $this->mailer->addAddress($to);
    }

    /**
     * Define o assunto do e-mail.
     *
     * @param string $subject Assunto do e-mail
     */
    public function setSubject(string $subject) {
        $this->mailer->Subject = $subject;
    }

    /**
     * Define o corpo do e-mail.
     *
     * @param string $body Corpo do e-mail
     */
    public function setBody(string $body) {
        $this->mailer->Body = $body;
        $this->mailer->AltBody = strip_tags($body);
    }

    /**
     * Gera um corpo de e-mail em formato de tabela a partir de um array.
     *
     * @param array $data Dados a serem exibidos na tabela
     * @param array $headers Cabeçalhos das colunas da tabela
     * @return string Corpo do e-mail em formato HTML
     */
    public function generateTableBody(array $data, array $headers): string {
        $html = '<table border="1" cellpadding="5" cellspacing="0">';
        
        // Gerar cabeçalhos
        $html .= '<thead><tr>';
        foreach ($headers as $header) {
            $html .= '<th>' . htmlspecialchars($header) . '</th>';
        }
        $html .= '</tr></thead>';
        
        // Gerar linhas da tabela
        $html .= '<tbody>';
        foreach ($data as $row) {
            $html .= '<tr>';
            foreach ($row as $cell) {
                $html .= '<td>' . htmlspecialchars($cell) . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';

        return $html;
    }

    /**
     * Adiciona um anexo ao e-mail.
     *
     * @param string $filePath Caminho para o arquivo a ser anexado
     * @param string|null $name Nome do arquivo como aparecerá no e-mail (opcional)
     */
    public function addAttachment(string $filePath, ?string $name = null) {
        if (file_exists($filePath)) {
            $this->mailer->addAttachment($filePath, $name);
        } else {
            throw new \Exception("Arquivo para anexar não encontrado: " . $filePath);
        }
    }

    /**
     * Envia um e-mail.
     *
     * @return bool Retorna verdadeiro se o e-mail for enviado com sucesso, falso caso contrário
     * @throws PHPMailerException Se ocorrer um erro ao enviar o e-mail
     */
    public function send(): bool {
        try {
            // Envia o e-mail
            return $this->mailer->send();
        } catch (PHPMailerException $e) {
            throw new \Exception("Não foi possível enviar o e-mail: " . $e->getMessage());
        }
    }
}
