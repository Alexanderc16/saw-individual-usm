<?php

const LOG_FORMAT = '[[date]] [user-id] [ip] [role-id] [message]';

function action_log($userId, $roleId, $log_message)
{
   $message = LOG_FORMAT;

   $message = preg_replace('/\[date\]/', $message, date("Y-m-d H:i:s")) . "\t";
   $message .= preg_replace('/\[user-id\]/', $message, $userId) . "\t\t";
   $message .= preg_replace('/\[role-id\]/', $message, $roleId) . "\t\t";
   $message .= preg_replace('/\[ip\]/', $message, $_SERVER['REMOTE_ADDR']) . "\t";
   $message .= preg_replace('/\[message\]/', $message, $log_message) . "\t";

   try {
      file_put_contents('./log/lo1gs.txt', $message . PHP_EOL, FILE_APPEND);
   } catch (Exception $e) {
      log_error($e->getMessage());
      header('Location: ./pages/error.php');
      die();
   }

   return true;
}

function log_error($error)
{
   try {
      file_put_contents('./log/error.txt', $error . PHP_EOL, FILE_APPEND);
   } catch (Exception $ex) {
      header('./pages/error.php');
   }
}
