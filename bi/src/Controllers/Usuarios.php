<?php

namespace Sys\Bi\Controllers;

use Sys\Bi\Common\BaseController;
use Exception;
use Throwable;

class Usuarios extends BaseController
{

    public function index()
    {
        echo $this->twig('usuarios/index.twig', [
            'title' => 'Lista de Usuarios',
        ]);
    }

    public function datatable()
    {
        $query_usuarios = "SELECT * FROM usuarios";
        $result_usuarios = DB->query($query_usuarios);
        $usuarios = [];
        while ($usuario = $result_usuarios->fetch_object()) {

            $usuario->buttons = '<button type="button"
            class="btn btn-sm btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#formUsuarios"
            data-bs-type="edit"
            data-bs-id="' . $usuario->id . '"
        >
            Editar
        </button>

        <button type="button"
            class="btn btn-sm btn-danger"
            data-bs-toggle="modal"
            data-bs-target="#deleteUsuarios"
            data-bs-id="' . $usuario->id . '"
        >
            Excluir
        </button>';
            $usuarios[] = $usuario;
        }
        
        echo json_encode([
            'data' => $usuarios
        ]);
    }



    public function create()
    {
        echo $this->twig('usuarios/form.twig', [
            'title' => 'Cadastro de Usuarios'
        ]);
    }

    public function edit()
    {
        $id = $_REQUEST['id'];

        $query_usuarios = "SELECT * FROM usuarios WHERE id = $id";
        $result_usuarios = DB->query($query_usuarios);
        $usuario = $result_usuarios->fetch_object();

        echo $this->twig('usuarios/form.twig', [
            'title' => 'Atualizar Usuario - ' . $usuario->name,
            'usuario' => $usuario
        ]);
    }

    public function store()
    {
        try {
            $name = trim($_REQUEST['name']);
            $email = trim($_REQUEST['email']);
            $password = trim($_REQUEST['password']);
            $passdowd_confirm = trim($_REQUEST['password_confirm']);
            $id = trim($_REQUEST['id']);

            if (strlen($name) < 2) {
                throw new Exception('Preencha um nome valido');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Preencha um email valido');
            }

            if (empty($id)) {
                $query_usuarios = "SELECT * FROM usuarios WHERE email='$email'";
                $result_usuarios = DB->query($query_usuarios);
                if ($result_usuarios->num_rows >= 1) {
                    throw new Exception("Ja existe usuario com esse email");
                }

                if (strlen($password) < 5) {
                    throw new Exception('A senha deve ter no minimo 5 caracteres');
                }

                if ($password != $passdowd_confirm) {
                    throw new Exception('As senhas devem ser iguais');
                }
            } else {
                $password_qry = '';
                if (!empty($password)) {
                    if (strlen($password) < 5) {
                        throw new Exception('A senha deve ter no minimo 5 caracteres');
                    }

                    if ($password != $passdowd_confirm) {
                        throw new Exception('As senhas devem ser iguais');
                    }

                    $password_qry = " , password=md5('$password') ";
                }
            }

            if (empty($id)) {
                $sql = "INSERT INTO usuarios (name, email, password) VALUES ('$name', '$email', md5('$password'))";
            } else {
                $sql = "UPDATE usuarios SET name='$name', email='$email' {$password_qry} WHERE id = {$id}";
            }

            DB->query($sql);

            echo json_encode([
                'success' => true,
                'message' => "Usuario atualizado ou cadastrado"
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

            $query_usuarios = "SELECT * FROM usuarios WHERE id=$id";
            $result_usuarios = DB->query($query_usuarios);
            if ($result_usuarios->num_rows < 1) {
                throw new Exception("Esse usuario nao existe");
            }

            $sql = "DELETE FROM usuarios WHERE id = {$id}";

            DB->query($sql);

            echo json_encode([
                'success' => true,
                'message' => "Usuario excluido"
            ]);
        } catch (Throwable $th) {
            echo json_encode([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}
