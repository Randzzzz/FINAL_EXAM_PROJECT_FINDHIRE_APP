<?php
require_once 'core/models.php';
require_once 'core/handleForms.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="css/login_reg.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="css/login_reg_header.css?v=<?php echo time(); ?>">
</head>

<body>
  <div class="title">
    <h1>FindHire</h1>
  </div>
  <div class="form">
    <div class="items">
      <h1>Login Now!</h1>
      <?php if (isset($_SESSION['message'])) { ?>
        <h5 style="color: red;"><?php echo $_SESSION['message']; ?></h5>
      <?php }
      unset($_SESSION['message']); ?>
      <form action="core/handleForms.php" method="POST">
        <p>
          <label for="username">Username:</label>
          <input type="text" name="username" required>
        </p>
        <p>
          <label for="password">Password:</label>
          <input type="password" name="password" required>
          <input id="submitBtn" type="submit" name="loginUserBtn">
        </p>
      </form>
      <p>Don't have an account? You may register <a id="linkref" href="register.php">here.</a></p>
    </div>
  </div>


</body>

</html>