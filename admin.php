<?php
require_once './components/header.php';
require_once './handlers/auth.php';
require_once './functions/user.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
set_permission($_SESSION['id']);

rederict_if_no_perm('can_view_panel', '');

action_log(get_user_id(), get_user_role(), "entered page <admin-panel>");
?>

<link rel="stylesheet" href="style/style.css?v=<?php echo time(); ?>">

<body>
    <div class="meniu">
        <ul>
            <li><a href="./">Main</a></li>
            <?php if (check_permissions($_SESSION['id'], 'can_view_post_panel')) : ?> <li><a href="./postmanager.php">Post Manager</a></li> <?php endif; ?>
            <?php if (check_permissions($_SESSION['id'], 'can_view_user_panel')) : ?> <li><a href="./usermanager.php">User Manager</a></li> <?php endif; ?>
        </ul>
    </div>
    <div class="content">
        <h1 style="text-align: center;">Welcome to the Administration Panel</h1>
    </div>
    <p style="text-align: center;">
        <img src="img/secret.jpg">
    </p>

    <h1 style="text-align: center; font: bold 30px Arial, serif;">
        Congratulations!
    </h1>

    <div style="text-align: center">
        <audio controls autoplay>
            <source src="sound/secret.mp3" type="audio/mp3">
        Your browser does not support the audio element.
        </audio>
    </div>

</body>

</html>