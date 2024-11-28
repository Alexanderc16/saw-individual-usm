<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once './functions/log.php';

class DB
{
    private static $config = [
        'db_name' => 'lab_app',
        'db_host' => 'localhost',
        'db_user' => 'root',
        'db_pass' => ''
    ];
    public static $pdo;

    public function get_pdo(): ?PDO
    {
        $dsn = 'mysql:dbname=' . self::$config['db_name'] . ';host=' . self::$config['db_host'];
        try {
            self::$pdo = new PDO($dsn, self::$config['db_user'], self::$config['db_pass']);
        } catch (Exception $ex) {
            log_error($ex->getMessage());
            header('Location: ./pages/error.php');
            die();
        }
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return self::$pdo;
    }

    public function del_pdo()
    {
        self::$pdo = null;
    }
}
