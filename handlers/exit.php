<?php

require_once './functions/log.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['exit'] == 'Exit') {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        action_log(get_user_id(), get_user_role(), 'log out');
        unset($_SESSION['login']);
        session_destroy();
    }
}
