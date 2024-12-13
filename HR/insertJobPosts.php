<?php
require_once '../core/models.php';
require_once '../core/handleForms.php';
require_once '../core/access.php';
restrictAccess('HR');

if (!isset($_SESSION['username'])) {
  header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="../css/index.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../css/header.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="../css/insertJobPosts.css?v=<?php echo time(); ?>">
</head>

<body>
  <div class="title">
    <h1>FindHire</h1>
    <div class="title-row">
      <div class="title-row-value">
        <p><a href="insertJobPosts.php">Page Refresh</a></p>
        <div class="audit">
          <p><a href="messageApplicant.php">Applicant Messages</a></p>
        </div>
      </div>

      <div class="search">

      </div>
      <div class="title-row-value">
        <p><a href="hr_dashboard.php">Job Dashboard</a></p>
        <div class="logout">
          <?php if (isset($_SESSION['username'])) { ?>
            <p><a id="linkref" href="../core/logout.php">Logout here</a>
            </p>
          <?php } else { ?>
            <p><a href="login.php">Please Login</a></p>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>

  <div class="form">
    <div class="items">
      <h1>Create Job Post</h1>
      <form action="../core/handleForms.php" method="POST">
        <p>
          <label for="title">Title:</label>
          <input type="text" name="title" required>
        </p>
        <p>
          <label for="description">Description:</label>
          <textarea name="description" id="description" required></textarea>

        </p>
        <input id="submitBtn" type="submit" name="insertJobPostBtn">
      </form>
    </div>
  </div>




</body>

</html>