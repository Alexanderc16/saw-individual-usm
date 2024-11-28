<?php

require_once './components/header.php';
require_once './handlers/postManager.php';
require_once './functions/posts.php';

if (session_status() == PHP_SESSION_NONE) {
   session_start();
}

$roles = get_roles();
$posts = get_posts(['id', 'post_title']);
action_log(get_user_id(), get_user_role(), "entered page <admin-panel ~ post-manager>");
?>

<link rel="stylesheet" href="style/style.css?v=<?php echo time(); ?>">

<body>
   <div class="meniu">
      <ul>
         <li>
            <a href="./">To Main Page</a>
         </li>
      </ul>
   </div>

   <div class="post-page">
      <h1>Post Manager</h1>

      <div class="post-page__actions">
         <?php if (check_permissions($_SESSION['id'], 'can_add_post')) : ?>

            <div class="post-page__actions--add">
               <h2>Add Post</h2>
               <form method="post" action="<?= $_SERVER['PHP_SELF']; ?>" style='display: flex; flex-direction: column; align-items: start; gap: 20px;'>
                  <input type="text" name="post_name" id="post_name" placeholder="Post Name">
                  <textarea name="post_content" id="post_content" cols="40" rows="10" placeholder="Post Content" style="resize:none; font-family:serif; font-size:20px"></textarea>
                  <input class="submit_button" type="submit" name="add_post" value="Add Post">
               </form>

               <?php if ($errors['ifErrors']) : ?>
                  <?php foreach ($errors as $error) : ?>
                     <?php if ($error['error']) : ?>
                        <p style="color: red"> <?php echo $error['errorMsg']; ?> </p>
                     <?php endif; ?>
                  <?php endforeach; ?>
               <?php endif; ?>
            </div>


         <?php endif; ?>

         <?php if (check_permissions($_SESSION['id'], 'can_del_post')) : ?>

            <div class="post-page__actions--delete">
               <h2>Delete Post</h2>
               <form method="post" action="<?= $_SERVER['PHP_SELF']; ?>" style='display: flex; flex-direction: column; align-items: start; gap: 20px;'>
                  <p>Select the Post</p>
                  <select class="slct" name="post" id="post-list">
                     <option selected disabled>Select Post</option>
                     <?php foreach ($posts as $post) : ?>
                        <option value="<?= $post['id'] ?>"><?= $post['id'] ?> | <?= $post['post_title'] ?></option>
                     <?php endforeach; ?>
                  </select>
                  <input class="submit_button"  type="submit" name="del_post" value="Delete Post">
               </form>


            </div>

         <?php endif; ?>
      </div>
   </div>
</body>

</html>