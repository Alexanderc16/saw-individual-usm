<?php

require_once './components/header.php';
require_once './handlers/userManager.php';
require_once './functions/user.php';
require_once './functions/log.php';


if (session_status() == PHP_SESSION_NONE) {
   session_start();
}

$roles = get_roles();
action_log(get_user_id(), get_user_role(), "entered page <admin-panel ~ user-manager>");
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

   <div class="user-page">
      <h1>User Manager</h1>

      <div class="user-page__actions">
         <?php if (check_permissions($_SESSION['id'], 'can_add_user')) : ?>
            <div class="user-page__actions--add">
               <h2>Add User</h2>
               <form method="post" action="<?= $_SERVER['PHP_SELF']; ?>" style='display: flex; flex-direction: column; align-items: start; gap: 20px;'>
                  <input type="text" name="login" id="user__login" placeholder="User Name">
                  <input type="text" name="password" id="user__password" placeholder="User Password">
                  <select class="submit_button"  name="role" id="user__roles">
                     <option selected disabled>Select Role</option>
                     <?php foreach ($roles as $role) : ?>
                        <option value="<?= $role['id'] ?>"><?= $role['role'] ?></option>
                     <?php endforeach; ?>
                  </select>
                  <input class="submit_button" type="submit" name="add_user" value="Add User">
               </form>

               <?php foreach ($errors as $error) : ?>
                  <?php if ($error['error']) : ?>
                     <p style="color: red"> * <?php echo $error['errorMsg']; ?> </p>
                  <?php endif; ?>
               <?php endforeach; ?>
            </div>
         <?php endif; ?>
      </div>
   </div>
</body>

</html>