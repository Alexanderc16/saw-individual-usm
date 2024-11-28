<?php

require_once './handlers/db.php';
require_once './functions/user.php';
require_once './functions/log.php';

if (session_status() == PHP_SESSION_NONE) {
   session_start();
}

rederict_if_no_perm('can_view_post_panel', '');

function new_sanitized_data($data)
{
   $data = htmlspecialchars($data);
   $data = stripslashes($data);
   return $data;
}

$errors = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

   rederict_if_no_perm('can_add_post', '');

   if ($_POST['add_post'] === 'Add Post') {

      $post = [
         'post_name' => new_sanitized_data($_POST['post_name']),
         'post_content' => new_sanitized_data($_POST['post_content']),
      ];

      $errors = [
         'empty' => [
            'errorMsg' => '* Заполните все поля формы',
            'error' => false,
         ],
         'ifErrors' => false,
      ];

      if (empty($post['post_name']) || empty($post['post_content'])) {
         $errors['ifErrors'] = true;
         $errors['empty']['error'] = true;
      }

      if (!$errors['ifErrors']) {
         $db = new DB;
         $pdo = $db->get_pdo();
         $sth = $pdo->prepare('insert into posts (post_title, post_content) values (:post_name, :post_content)');
         action_log(get_user_id(), get_user_role(), "added new post");
         $sth->execute($post);
         $db->del_pdo();
         header("Location: ./postmanager.php");
      }
   }

   if ($_POST['del_post'] === 'Delete Post') {

      rederict_if_no_perm('can_del_post', '');

      $post = [
         'id' => $_POST['post']
      ];


      $db = new DB;
      $pdo = $db->get_pdo();
      $sth = $pdo->prepare('delete from posts where id = :id');
      $sth->execute($post);
      action_log(get_user_id(), get_user_role(), "deleted post <{$post['id']}>");
      $db->del_pdo();
      header("Location: ./postmanager.php");
   }
}
