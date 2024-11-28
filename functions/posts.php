<?php

require_once('./handlers/db.php');

function get_posts($tables = ['*'])
{
   $db = new DB;
   $pdo = $db->get_pdo();
   $sth = $pdo->prepare('select ' . implode(',', $tables) . ' from posts');
   $sth->execute();
   $arr = $sth->fetchAll(PDO::FETCH_ASSOC);
   return $arr;
}
