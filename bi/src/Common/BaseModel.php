<?php
namespace Sys\Bi\Common;

use PDO;
use Sys\Bi\Common\Database;

/**
 * Class BaseModel
 * Classe base para os models que interagem com o banco de dados.
 */
class BaseModel extends Database
{
    protected string $table;
    protected array $fillable = [];

    /**
     * Obtém todos os registros da tabela.
     * 
     * @return array Os registros da tabela.
     */
    public function getAll(): array
    {
        $sql = "SELECT * FROM $this->table";
        return $this->fetchAll($sql);
    }

    /**
     * Obtém um registro pelo ID.
     * 
     * @param int $id O ID do registro.
     * @param string $idField O campo que representa o ID.
     * @return mixed O registro encontrado.
     */
    public function getById(int $id, string $idField = 'id'): mixed
    {
        $sql = "SELECT * FROM $this->table WHERE $idField = :id";
        return $this->fetch($sql, ['id' => $id]);
    }

    /**
     * Insere um novo registro na tabela.
     * 
     * @param array $data Os dados a serem inseridos.
     * @return string O ID do registro inserido.
     */
    public function insert(array $data): string
    {
        $data = $this->filterData($data);
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO $this->table ($columns) VALUES ($placeholders)";
        $this->execute($sql, $data);
        return $this->lastInsertId();
    }

    /**
     * Atualiza um registro na tabela.
     * 
     * @param int $id O ID do registro a ser atualizado.
     * @param array $data Os dados a serem atualizados.
     * @param string $idField O campo que representa o ID.
     * @return bool True em caso de sucesso, False caso contrário.
     */
    public function update(int $id, array $data, string $idField = 'id'): bool
    {
        $data = $this->filterData($data);
        $fields = "";
        foreach ($data as $key => $value) {
            $fields .= "$key = :$key, ";
        }
        $fields = rtrim($fields, ", ");
        $data['id'] = $id;
        $sql = "UPDATE $this->table SET $fields WHERE $idField = :id";
        return $this->execute($sql, $data);
    }

    /**
     * Remove um registro da tabela.
     * 
     * @param int $id O ID do registro a ser removido.
     * @param string $idField O campo que representa o ID.
     * @return bool True em caso de sucesso, False caso contrário.
     */
    public function delete(int $id, string $idField = 'id'): bool
    {
        $sql = "DELETE FROM $this->table WHERE $idField = :id";
        return $this->execute($sql, ['id' => $id]);
    }

    /**
     * Obtém a contagem de registros na tabela.
     * 
     * @return int A contagem de registros.
     */
    public function count(): int
    {
        $sql = "SELECT COUNT(*) as count FROM $this->table";
        return (int)$this->fetch($sql)['count'];
    }

    /**
     * Pagina os registros da tabela.
     * 
     * @param int $page O número da página.
     * @param int $limit O número de registros por página.
     * @return array Os registros da página especificada.
     */
    public function paginate(int $page = 1, int $limit = 10): array
    {
        $offset = ($page - 1) * $limit;
        $sql = "SELECT * FROM $this->table LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Realiza uma busca na tabela com base em condições específicas.
     * 
     * @param array $conditions As condições de busca.
     * @param string $logic A lógica entre as condições (AND/OR).
     * @return array Os registros que atendem às condições.
     */
    public function search(array $conditions = [], string $logic = 'AND'): array
    {
        $sql = "SELECT * FROM $this->table";
        if (!empty($conditions)) {
            $clauses = [];
            $params = [];
            foreach ($conditions as $field => $value) {
                $clauses[] = "$field = :$field";
                $params[$field] = $value;
            }
            $sql .= " WHERE " . implode(" $logic ", $clauses);
        }
        return $this->fetchAll($sql, $params);
    }

    /**
     * Filtra os dados com base nos campos permitidos.
     * 
     * @param array $data Os dados a serem filtrados.
     * @return array Os dados filtrados.
     */
    protected function filterData(array $data): array
    {
        return array_filter(
            $data,
            fn($key) => in_array($key, $this->fillable),
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * Executa uma consulta SQL customizada.
     * 
     * @param string $sql A consulta SQL.
     * @param array $params Os parâmetros a serem associados.
     * @return array Os resultados da consulta.
     */
    public function customQuery(string $sql, array $params = []): array
    {
        return $this->fetchAll($sql, $params);
    }
}
