<?php
require_once './components/header.php';
require_once './handlers/auth.php';
require_once './functions/log.php';
action_log(get_user_id(), get_user_role(), "entered page <auth>");
?>

<link rel="stylesheet" href="style/style.css?v=<?php echo time(); ?>">

<body>
    <div class="meniu">
        <ul>
            <li><a href="./index.php">Main</a></li>
            <?php if (!is_logged($_SESSION['login'])) : ?>
                <li><b>Registration</b></li>
                <li><a href="./auth.php">Authentication</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <form method="post" action="<?= $_SERVER['PHP_SELF']; ?>">
        <div>
            <label for="login">Login</label>
            <input type="text" class="form-control" id="login" name="login" required>
            <?php if ($errors['login']['error']) : ?>
                <span>* <?= $errors['login']['message']; ?></span>
            <?php endif; ?>
        </div>

        <div>
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
            <?php if ($errors['password']['error']) : ?>
                <span>* <?= $errors['password']['message']; ?></span>
            <?php endif; ?>
        </div>

        <div>
            <input type="submit" name="sumbit" value="Register">
        </div>

        <?php if ($errors['exist']['error']) : ?>
            <span style="color: black">* <?= $errors['exist']['message']; ?></span>
        <?php endif; ?>
    </form>
</body>

</html>