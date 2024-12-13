<?php
require_once 'core/models.php';
require_once 'core/handleForms.php';
require_once 'core/access.php';
restrictAccess('applicant');

if (!isset($_SESSION['username'])) {
  header("Location: login.php");
}
$job_id = $_GET['job_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="css/index.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="css/header.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="css/insertJobPosts.css?v=<?php echo time(); ?>">
</head>

<body>
  <div class="title">
    <h1>FindHire</h1>
    <div class="title-row">
      <div class="title-row-value">

        <div class="audit">
          <p><a href="messageHR.php">HR Messages</a></p>
        </div>
      </div>

      <div class="search">

      </div>
      <div class="title-row-value">
        <p><a href="index.php">Applicant Dashboard</a></p>
        <div class="logout">
          <?php if (isset($_SESSION['username'])) { ?>
            <p><a id="linkref" href="core/logout.php">Logout here</a>
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
      <h1>Send an Application</h1>
      <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
        <p>
          <label id="applicant-label" for="resume">Upload Resume:</label><br>
          <input type="file" name="resume" id="applicant-file" required>
        </p>
        <p>
          <label id="applicant-label" for="cover_letter">Cover Letter:</label><br>
          <textarea name="cover_letter" id="description" required></textarea>
        </p>
        <input id="submitBtn" type="submit" name="insertJobApplicationBtn" value="Submit">
      </form>
    </div>
  </div>



</body>

</html>