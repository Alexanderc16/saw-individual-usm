<?php

require_once('./handlers/db.php');

if (session_status() == PHP_SESSION_NONE) {
   session_start();
}

function get_permissions($user_id)
{
   $dbObj = (new DB);
   $pdo = $dbObj->get_pdo();
   $sth = $pdo->prepare('select p.perm from `permissions` p, `roles` r, `role_perm` rp, `user` u where u.role_id = r.id AND r.id = rp.role_id AND rp.perm_id = p.id AND u.id = :user_id');
   $sth->execute(['user_id' => $user_id]);
   $arr = $sth->fetchAll(PDO::FETCH_COLUMN);
   $dbObj->del_pdo();
   return array_values($arr);
}

function get_current_perm()
{
   return $_SESSION['perm'];
}


function rederict_if_no_perm($perm, $location)
{
   if (!in_array($perm, get_current_perm())) {
      header("Location: ./" . $location);
   }
}

function check_permissions($user_id, $perm)
{
   if (!$user_id) {
      return false;
   }
   $user_perm = get_current_perm();
   return in_array($perm, $user_perm);
}

function set_permission($user_id)
{
   $_SESSION['perm'] = get_permissions($user_id);
}

function is_logged($user_id)
{
   return $user_id;
}

function get_roles()
{
   $dbObj = (new DB);
   $pdo = $dbObj->get_pdo();
   $sth = $pdo->prepare('select id, role from roles');
   $sth->execute();
   $arr = $sth->fetchAll(PDO::FETCH_ASSOC);
   $dbObj->del_pdo();
   return $arr;
}

function get_user_id() {
   return !empty($_SESSION['id']) ? $_SESSION['id'] : '-';
}

function get_user_role() {
   return empty($_SESSION['id']) ? '-' : $_SESSION['role_id'];
}