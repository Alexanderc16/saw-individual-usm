<?php
require_once './components/header.php';
require_once './handlers/auth.php';
require_once './functions/user.php';
action_log(get_user_id(), get_user_role(), "entered page <auth>");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

set_permission($_SESSION['id']);
?>

<link rel="stylesheet" href="style/style.css?v=<?php echo time(); ?>">

<body>
    <div class="meniu">
        <ul>
            <li><a href="./index.php">Main</a></li>
            <li><a href="./registration.php">Registration</a></li>
            <li><b>Authentication</b></li>
        </ul>
    </div>

    <form method="post" action="<?= $_SERVER['PHP_SELF']; ?>">
        <div>
            <label for="login">Login</label>
            <input type="text" class="form-control" id="login" name="login" required>
        </div>

        <div>
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div>
            <input type="submit" name="sumbit" value="Auth">
        </div>

        <?php if ($errors['loginIs']['error']) : ?>
            <span style="color: black">* <?= $errors['loginIs']['message']; ?></span>
        <?php endif; ?>
    </form>

</body>

</html>