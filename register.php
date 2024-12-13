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
      <h1>Register here!</h1>
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
          <label for="first_name">First Name:</label>
          <input type="text" name="first_name" required>
        </p>
        <p>
          <label for="last_name">Last Name:</label>
          <input type="text" name="last_name" required>
        </p>
        <p>
          <label for="age">Age:</label>
          <input type="number" name="age" required>
        </p>
        <p>
          <label for="gender">Gender:</label>
          <select name="gender" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
          </select>
        </p>
        <p>
          <label for="email">Email:</label>
          <input type="email" name="email" required>
        </p>
        <p>
          <label for="address">Address:</label>
          <input type="text" name="address" required>
        </p>
        <p>
          <label for="nationality">Nationality:</label>
          <input type="text" name="nationality" required>
        </p>
        <p>
          <label for="password">Password:</label>
          <input type="password" name="password" required>
        </p>
        <p>
          <label for="confirm_password">Confirm Password:</label>
          <input type="password" name="confirm_password" required>
        </p>
        <p>
          <label for="role">Register as:</label>
          <select name="role" required>
            <option value="applicant">Applicant</option>
            <option value="HR">Human Resources</option>
          </select>
          <input id="submitBtn" type="submit" name="registerUserBtn">
        </p>

        <p>Already have an account? You may login <a id="linkref" href="login.php">here.</a></p>
      </form>
    </div>
  </div>

</body>

</html>