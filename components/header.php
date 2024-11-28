<?php
require_once './functions/user.php';
if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
} else {
    $id = null; // Or provide a default value or error handling
}

set_permission($id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style/style.css">
</head>