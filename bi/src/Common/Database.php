<?php
namespace Sys\Bi\Common;

use PDO;
use PDOException;
use PDOStatement;


/**
 * Class Database
 * Classe responsável por gerenciar a conexão com o banco de dados MySQL e operações básicas usando PDO.
 * @package Sys\Bi\Common
 */
class Database
{
    private string $host = DB_HOST;
    private string $port = DB_PORT;
    private string $db = DB_DATABASE;
    private string $user = DB_USER;
    private string $pass = DB_PASSWORD;
    private string $charset = 'utf8mb4';
    protected PDO $pdo;

    /**
     * Database constructor.
     * Inicializa a conexão com o banco de dados usando PDO.
     */
    public function __construct()
    {
        $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db};charset={$this->charset}";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * Associa parâmetros à declaração preparada.
     * 
     * @param PDOStatement $stmt A declaração preparada.
     * @param array $params Os parâmetros a serem associados.
     */
    protected function bindParams(PDOStatement $stmt, array $params): void
    {
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
    }

    /**
     * Prepara e executa uma consulta.
     * 
     * @param string $sql A consulta SQL.
     * @param array $params Os parâmetros a serem associados.
     * @return PDOStatement A declaração preparada.
     */
    protected function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $this->bindParams($stmt, $params);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Obtém todos os resultados de uma consulta.
     * 
     * @param string $sql A consulta SQL.
     * @param array $params Os parâmetros a serem associados.
     * @return array Os resultados da consulta.
     */
    protected function fetchAll(string $sql, array $params = []): array
    {
        return $this->query($sql, $params)->fetchAll();
    }

    /**
     * Obtém um único resultado de uma consulta.
     * 
     * @param string $sql A consulta SQL.
     * @param array $params Os parâmetros a serem associados.
     * @return mixed O resultado da consulta.
     */
    protected function fetch(string $sql, array $params = []): mixed
    {
        return $this->query($sql, $params)->fetch();
    }

    /**
     * Executa uma declaração SQL.
     * 
     * @param string $sql A declaração SQL.
     * @param array $params Os parâmetros a serem associados.
     * @return bool True em caso de sucesso, False caso contrário.
     */
    protected function execute(string $sql, array $params = []): bool
    {
        $stmt = $this->pdo->prepare($sql);
        $this->bindParams($stmt, $params);
        return $stmt->execute();
    }

    /**
     * Obtém o ID do último registro inserido.
     * 
     * @return string O ID do último registro inserido.
     */
    protected function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * Inicia uma transação.
     * 
     * @return bool True em caso de sucesso, False caso contrário.
     */
    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * Confirma uma transação.
     * 
     * @return bool True em caso de sucesso, False caso contrário.
     */
    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    /**
     * Reverte uma transação.
     * 
     * @return bool True em caso de sucesso, False caso contrário.
     */
    public function rollBack(): bool
    {
        return $this->pdo->rollBack();
    }

    /**
     * Executa uma consulta SQL que pode ser qualquer comando.
     * 
     * @param string $sql A consulta SQL ou comando a ser executado.
     * @param array $params Os parâmetros a serem associados (opcional).
     * @return mixed O resultado da consulta, ou o número de linhas afetadas para comandos de modificação.
     */
    public function executeQuery(string $sql, array $params = []): mixed
    {
        $stmt = $this->pdo->prepare($sql);
        $this->bindParams($stmt, $params);
        $stmt->execute();

        // Retorna o número de linhas afetadas para comandos de modificação ou o resultado para SELECT
        if (stripos($sql, 'select') === 0) {
            return $stmt->fetchAll();
        } else {
            return $stmt->rowCount();
        }
    }
    
}
