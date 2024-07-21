<?php

namespace Sys\Bi\Controllers;

use Exception;
use Sys\Bi\Common\BaseController;
use Throwable;

class Afazeres extends BaseController {

    public function index()
    {
        echo $this->twig('afazeres/index.twig', [
            'title' => 'Lista de Afazeres',
        ]);
    }

    public function datatable()
    {
        $tipos = [
            'U' => '<span class="badge bg-danger">Urgente</span>',
            'P' => '<span class="badge bg-warning">Prioridade</span>',
            'N' => '<span class="badge bg-primary">Não Prioritário</span>',
        ];
        $status = [
            'A' => '<span class="badge bg-warning"><i class="bi bi-dash-square-dotted"></i></span>',
            'C' => '<span class="badge bg-success"><i class="bi bi-check-lg"></i></span>'
        ];

        $query_afazeres = "SELECT * FROM lista_afazeres";
        $result_afazeres = DB->query($query_afazeres);
        $afazeres = [];

        while ($afazer = $result_afazeres->fetch_object()) {
            $afazer->datas = "{$afazer->data_cadastro} - {$afazer->data_finalizacao}";
            $afazer->tipo_txt = $tipos[$afazer->tipo];
            $afazer->status_txt = $status[$afazer->status];
            
            $afazer->buttons = '<button type="button"
            class="btn btn-sm btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#formAfazeres"
            data-bs-type="edit"
            data-bs-id="' . $afazer->id . '"
        >
            Editar
        </button>

        <button type="button"
            class="btn btn-sm btn-danger"
            data-bs-toggle="modal"
            data-bs-target="#deleteAfazeres"
            data-bs-id="' . $afazer->id . '"
        >
            Excluir
        </button>';
            $afazeres[] = $afazer;
        }
        
        echo json_encode([
            'data' => $afazeres
        ]);
    }



    public function create()
    {
        echo $this->twig('afazeres/form.twig', [
            'title' => 'Cadastro de Afazeres'
        ]);
    }

    public function edit()
    {
        $id = $_REQUEST['id'];

        $query_afazeres = "SELECT * FROM lista_afazeres WHERE id = $id";
        $result_afazeres = DB->query($query_afazeres);
        $afazer = $result_afazeres->fetch_object();

        echo $this->twig('afazeres/form.twig', [
            'title' => 'Atualizar Afazer - ' . $afazer->titulo,
            'afazer' => $afazer
        ]);
    }

    public function store()
    {
        try {
            $titulo = trim($_REQUEST['titulo']);
            $detalhes = trim($_REQUEST['detalhes']);
            $tipo = trim($_REQUEST['tipo']);
            $id_usuario = $_SESSION['usuarioId'];
            $id = trim($_REQUEST['id']);

            if (is_null($id_usuario)) {
                session_destroy();
                echo('<script>window.location.href="index.php"</script>');
                die;
            }

            if (empty($titulo)) {
                throw new Exception('Preencha o título');
            }

            if (!in_array($tipo, ['U', 'P', 'N'])) {
                throw new Exception('Selecione um tipo válido');
            }

            if (empty($id)) {
                $sql = "INSERT INTO lista_afazeres (titulo, detalhes, tipo, id_usuario) VALUES ('$titulo', '$detalhes', '$tipo', $id_usuario)";
            } else {
                $sql = "UPDATE lista_afazeres SET titulo='$titulo', detalhes='$detalhes' tipo='$tipo' WHERE id = {$id}";
            }

            DB->query($sql);

            echo json_encode([
                'success' => true,
                'message' => "Afazer atualizado ou cadastrado"
            ]);
        } catch (Throwable $th) {
            echo json_encode([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function delete()
    {
        try {
            $id = trim($_REQUEST['id']);

            if (empty($id)) {
                throw new Exception("Parametro invalido");
            }

            $query_afazeres = "SELECT * FROM lista_afazeres WHERE id=$id";
            $result_afazeres = DB->query($query_afazeres);
            if ($result_afazeres->num_rows < 1) {
                throw new Exception("Esse afazer nao existe");
            }

            $sql = "DELETE FROM lista_afazeres WHERE id = {$id}";

            DB->query($sql);

            echo json_encode([
                'success' => true,
                'message' => "Afazer excluido"
            ]);
        } catch (Throwable $th) {
            echo json_encode([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}