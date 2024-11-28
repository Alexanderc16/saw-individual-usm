<?php

require_once './handlers/db.php';
require_once './functions/log.php';
require_once './functions/user.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function data_verify($data)
{
    strip_tags($data);
    htmlspecialchars($data);
    htmlentities($data);
    stripslashes($data);
    return $data;
}

$errors = [
    "login" => [
        "error" => false,
        "message" => "Логин должен содержать только буквы и цифры, длина от 3 до 15!"
    ],
    "password" => [
        "error" => false,
        "message" => "Пароль должен содержать буквы, цифры и знаки '%$#', длина от 4 до 30!"
    ],
    "exist" => [
        "error" => false,
        "message" => "Пользователь уже существует!"
    ],
    'loginIs' => [
        "error" => false,
        "message" => "Вы ввели неправильный пароль!"
    ]
];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['sumbit'] === 'Register') {

        $request_data = [
            'login' => data_verify($_POST['login']),
            'password' => data_verify($_POST['password'])
        ];

        // verification

        $patterns = [
            'login' => "/^[A-Za-z0-9]{3,15}$/",
            'password' => "/^[A-Za-z0-9$#%]{4,30}$/"
        ];

        if (!preg_match($patterns['login'], $request_data['login'])) {
            $errors['login']['error'] = true;
        }

        if (!preg_match($patterns['password'], $request_data['password'])) {
            $errors['password']['error'] = true;
        }

        // добавление в базу данных
        if (!$errors['login']['error'] && !$errors['password']['error']) {

            $request_data['password'] = password_hash($request_data['password'], PASSWORD_DEFAULT);

            $db = new DB;
            $pdo = $db->get_pdo();

            // проверка на существование
            $std = $pdo->prepare('select count(*) as cnt from user where login = :login');
            $std->execute(['login' => $request_data['login']]);

            $arr = $std->fetch(PDO::FETCH_ASSOC);

            if ($arr['cnt'] == 0) {
                $std = $pdo->prepare('insert into user (login, password, role_id) values (:login, :password, :role)');
                action_log(get_user_id(), get_user_role(), 'successfully registred');
                $query_data = $request_data;
                $query_data['role'] = 0;
                $std->execute($query_data);
                $db->del_pdo();
                header("Location: .");
            } else if ($arr['cnt'] != 0) {
                $errors['exist']['error'] = true;
                action_log(get_user_id(), get_user_role(), 'failed registred');
            }
        }
    }

    if ($_POST['sumbit'] === 'Auth') {
        $request_data = [
            'login' => data_verify($_POST['login']),
            'password' => data_verify($_POST['password'])
        ];

        $db = new DB;
        $pdo = $db->get_pdo();

        // проверка на существование

        $std = $pdo->prepare('select login, password from user where login = :login');
        $std->execute(['login' => $request_data['login']]);

        $arr = $std->fetch(PDO::FETCH_ASSOC);
        if (password_verify($request_data['password'], $arr['password'])) {
            $std = $pdo->prepare('select id, login, role_id from user where login = :login');
            $std->execute(['login' => $request_data['login']]);
            $arr = $std->fetch(PDO::FETCH_ASSOC);
            $db->del_pdo();
            $_SESSION['login'] = $arr['login'];
            $_SESSION['id'] = $arr['id'];
            $_SESSION['role_id'] = $arr['role_id'];
            action_log(get_user_id(), get_user_role(), 'successfully auth');
            header('Location: ./');
        } else {
            action_log(get_user_id(), get_user_role(), 'failed auth');
            $errors['loginIs']['error'] = true;
        }

        $db->del_pdo();
    }
}
