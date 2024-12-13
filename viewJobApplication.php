<?php
require_once 'core/models.php';
require_once 'core/handleForms.php';
require_once 'core/access.php';
restrictAccess('applicant');

if (!isset($_SESSION['username'])) {
  header("Location: login.php");
}
$user_id = $_SESSION['user_id'];
$applications = getUserApplications($pdo, $user_id);
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
  <link rel="stylesheet" href="css/viewJobApplication.css?v=<?php echo time(); ?>">
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

  <div class="dashboard-title">
    <h1>Job Applications</h1>
    <?php if (isset($_SESSION['message'])) { ?>
      <h5 style="color: green; margin-top: 15px;"><?php echo $_SESSION['message']; ?></h5>
    <?php }
    unset($_SESSION['message']); ?>
  </div>

  <?php
  if (!empty($applications)): ?>
    <div class="application-box">
      <h2>Submitted Applications</h2>
      <table class="application-table">
        <thead>
          <tr>
            <th>Job Title</th>
            <th>Status</th>
            <th>Applied Date</th>
            <th>Resume</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($applications as $row): ?>


            <tr>
              <td><?php echo sanitizeInput($row['job_title']); ?></td>
              <td><?php echo sanitizeInput($row['application_status']); ?></td>
              <td><?php echo sanitizeInput($row['applied_at']); ?></td>
              <td>
                <div class="resume">
                  <a href="../resumes/<?php echo sanitizeInput($row['resume_path']); ?>" download>
                    <button type="button" id="">Download Resume</button>
                  </a>
                </div>

              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <p>No applications submitted yet.</p>
  <?php endif; ?>




</body>

</html>