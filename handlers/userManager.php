<?php


require_once './handlers/db.php';
require_once './functions/user.php';
require_once './functions/log.php';


function data_verify($data)
{
   htmlspecialchars($data);
   htmlentities($data);
   stripslashes($data);
   return $data;
}

rederict_if_no_perm('can_view_user_panel', '');


$errors = [
   "login" => [
      "error" => false,
      "errorMsg" => "Логин должен содержать только буквы и цифры, длина от 3 до 15!"
   ],
   "password" => [
      "error" => false,
      "errorMsg" => "Пароль должен содержать буквы, цифры и знаки '%$#', длина от 4 до 30!"
   ],
   "exist" => [
      "error" => false,
      "errorMsg" => "Пользователь уже существует!"
   ],
   'loginIs' => [
      "error" => false,
      "errorMsg" => "Вы ввели неправильный пароль!"
   ],
   'role' => [
      "error" => false,
      "errorMsg" => "Вы не выбрали роль!",
   ]
];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

   if ($_POST['add_user'] === 'Add User') {

      rederict_if_no_perm('can_add_user', '');

      $request_data = [
         'login' => data_verify($_POST['login']),
         'password' => data_verify($_POST['password']),
         'role' => $_POST['role']
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
      if (!$errors['login']['error'] && !$errors['password']['error'] && !$errors['role']['error']) {

         $request_data['password'] = password_hash($request_data['password'], PASSWORD_DEFAULT);

         $db = new DB;
         $pdo = $db->get_pdo();

         // проверка на существование
         $std = $pdo->prepare('select count(*) as cnt from user where login = :login');
         $std->execute(['login' => $request_data['login']]);

         $arr = $std->fetch(PDO::FETCH_ASSOC);

         if ($arr['cnt'] == 0) {
            $std = $pdo->prepare('insert into user (login, password, role_id) values (:login, :password, :role)');
            action_log(get_user_id(), get_user_role(), "created new user");
            $std->execute($request_data);
            $db->del_pdo();
            header("Location: ./usermanager.php");
         } else if ($arr['cnt'] != 0) {
            action_log(get_user_id(), get_user_role(), "failed add user");
            $errors['exist']['error'] = true;
         }
      }
   }
}
