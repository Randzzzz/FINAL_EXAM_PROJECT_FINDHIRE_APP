<?php
require_once '../core/models.php';
require_once '../core/handleForms.php';
require_once '../core/access.php';
restrictAccess('HR');

if (!isset($_SESSION['username'])) {
  header("Location: login.php");
}

$job_id = $_GET['job_id'];
$jobDetails = getJobDetails($pdo, $job_id);
$job_title = $jobDetails['title'];
$applications = getApplicationsByJob($pdo, $job_id);
$hiredApplicants = getHiredApplicantsByJob($pdo, $job_id);
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
  <link rel="stylesheet" href="../css/view_application.css?v=<?php echo time(); ?>">
</head>

<body>
  <div class="title">
    <h1>FindHire</h1>
    <div class="title-row">
      <div class="title-row-value">

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



  <div class="application-head-title">
    <h1>Applications for Job: <?php echo sanitizeInput($job_title); ?></h1>
    <?php
    if (isset($_SESSION['success'])) {
      echo "<p class='success-message'>" . $_SESSION['success'] . "</p>";
      unset($_SESSION['success']);
    }
    if (isset($_SESSION['error'])) {
      echo "<p class='error-message'>" . $_SESSION['error'] . "</p>";
      unset($_SESSION['error']);
    }

    if (!empty($hiredApplicants)): ?>
      <div class="hired-applicants-box">
        <h2>Hired Applicants</h2>
        <table class="hired-applicants-table">
          <thead>
            <tr>
              <th>Applicant Name</th>
              <th>Hiring Date</th>
              <th>Resume</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($hiredApplicants as $hiredApplicant): ?>
              <div class="hired-resume">
                <tr>
                  <td><?php echo sanitizeInput($hiredApplicant['username']); ?></td>
                  <td><?php echo sanitizeInput($hiredApplicant['applied_at']); ?></td>
                  <td>
                    <a href="../resumes/<?php echo sanitizeInput($hiredApplicant['resume_path']); ?>" download>
                      <button type="button" id="">Download Resume</button>
                    </a>
                  </td>
                </tr>
              </div>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p>No applicants have been hired for this job yet.</p>
    <?php endif; ?>
  </div>



  <div class="application-container">
    <?php foreach ($applications as $app): ?>
      <div class="application-box">
        <div class="application-header">
          <p class="applicant-name">Applicant: <?php echo sanitizeInput($app['applicant_username']); ?></p>
          <p class="applicant-status">Status: <?php echo sanitizeInput($app['application_status']); ?></p>
        </div>

        <p class="cover-letter">Cover Letter: <?php echo sanitizeInput($app['cover_letter']); ?></p>

        <p>
          <a href="../uploads/<?php echo sanitizeInput($app['resume_path']); ?>" download>
            <button id="downloadBtn" type="button">Download Resume</button>
          </a>
        </p>
        <form action="../core/handleForms.php" method="POST">
          <input type="hidden" name="application_id" value="<?php echo $app['application_id']; ?>">
          <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
          <?php if ($app['application_status'] === 'Pending'): ?>
            <button id="acceptBtn" type="submit" name="action" value="accept">Accept</button>
            <button id="rejectBtn" type="submit" name="action" value="reject">Reject</button>
          <?php endif; ?>
        </form>

      </div>
    <?php endforeach; ?>
  </div>


</body>

</html>