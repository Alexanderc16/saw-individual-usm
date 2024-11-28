<?php
require_once './components/header.php';
require_once './handlers/exit.php';
require_once './functions/user.php';
require_once './functions/posts.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

action_log(get_user_id(), get_user_role(), "entered page <main-page>");

?>
<link rel="stylesheet" href="style/style.css?v=<?php echo time(); ?>">

<body>
    <div class="meniu">
        <ul>
            <li><b>Main</b></li>
            <?php if (!is_logged($_SESSION['login'])) : ?>
                <li><a href="./registration.php">Registration</a></li>
                <li><a href="./auth.php">Authentication</a></li>
            <?php endif; ?>
            <?php if (check_permissions($_SESSION['id'], 'can_view_panel')) : ?>
                <li><a href="./admin.php">Admin Panel</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <?php if (is_logged($_SESSION['login'])) : ?>
        <form class="aboba" action="<?= $_SERVER['PHP_SELF']; ?>" method="post">
            <div>
                You are logged as: <?php echo $_SESSION['login']; ?>
            </div>

            <div>
                <input type="submit" name="exit" value="Exit">
            </div>
        </form>
    <?php endif; ?>

    <p style="text-align: center;">
        <img src="img/main.png">
    </p>

    <div class="posts">
        <h1>Recent Posts</h1>
        <div>
            <?php
            $posts = get_posts();
            foreach ($posts as $post) : ?>
                <div class="post">
                    <div class="post__meta" style="display:flex; justify-content:space-between;">
                        <p><b><?php echo $post['post_title']; ?></b></p>
                        <p><?php echo $post['created']; ?></p>
                    </div>
                    <div>
                        <p><?php echo $post['post_content']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</body>

</html>