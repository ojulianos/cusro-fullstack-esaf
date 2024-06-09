<?php

if (!function_exists('connectDB')) {
    function connectDB() {
        try {
            $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE, DB_PORT);
            if ($mysqli->connect_error) {
                throw new \Exception("Erro de conexao com a base de dados - " . $mysqli->connect_error);
            }

            /**
             * type mysqli
             */
            define("DB", $mysqli);
        } catch (Throwable $th) {
            echo $th->getMessage();
            die();
        }
    }
}



