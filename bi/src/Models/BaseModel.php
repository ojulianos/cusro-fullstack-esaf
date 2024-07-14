<?php

namespace Sys\Bi\Controllers;

use Exception;
use Sys\Bi\DB;
use Throwable;

class ModelController extends DB
{
    protected $table;

    public function getOne($id)
    {}

    public function getAll($where)
    {}

    public function save(array $data)
    {}

    public function update(array $data, $where)
    {}

    public function delete($where)
    {}
}
