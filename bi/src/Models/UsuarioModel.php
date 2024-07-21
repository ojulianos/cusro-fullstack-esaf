<?php
namespace Sys\Bi\Models;

use Sys\Bi\Common\BaseModel;

/**
 * Class Usuario
 * Exemplo de um model que estende a classe Model e representa a tabela "usuarios".
 */
class Usuario extends BaseModel
{
    protected string $table = 'usuarios';
    protected array $fillable = ['nome', 'email', 'senha'];
}