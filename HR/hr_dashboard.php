<?php
require_once '../core/models.php';
require_once '../core/handleForms.php';
require_once '../core/access.php';
restrictAccess('HR');

if (!isset($_SESSION['username'])) {
  header("Location: login.php");
}

$searchQuery = $_GET['searchInput'] ?? null;
if ($searchQuery) {
  $getAllJobPosts = searchForAJobPost($pdo, $searchQuery);
} else {
  $getAllJobPosts = getJobPosts($pdo);
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
  <link rel="stylesheet" href="../css/hr_dashboard.css?v=<?php echo time(); ?>">
</head>

<body>
  <div class="title">
    <h1>FindHire</h1>
    <div class="title-row">
      <div class="title-row-value">
        <p><a href="hr_dashboard.php">Clear Search Query</a></p>
        <div class="audit">
          <p><a href="messageApplicant.php">Applicant Messages</a></p>
        </div>
      </div>

      <div class="search">
        <form action="<?php echo sanitizeInput($_SERVER['PHP_SELF']); ?>" method="GET">
          <input type="text" name="searchInput" placeholder="Search here..." class="search-box">
          <button type="submit" name="searchBtn" class="searchBtn">Submit</button>
        </form>
      </div>
      <div class="title-row-value">
        <p><a href="insertJobPosts.php">New Job Posts</a></p>
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

  <div class="dashboard-title">
    <h1>HR Dashboard</h1>
    <?php if (isset($_SESSION['message'])) { ?>
      <h5 style="color: green; margin-top: 15px;"><?php echo $_SESSION['message']; ?></h5>
    <?php }
    unset($_SESSION['message']); ?>
  </div>
  <div class="job-posts-container">
    <?php if ($getAllJobPosts): ?>
      <?php foreach ($getAllJobPosts as $post): ?>
        <div class="job-post-box">
          <div class="job-header">
            <h2 class="job-title"><?php echo sanitizeInput($post['title']); ?></h2>
            <p class="job-username">Posted by: <?php echo sanitizeInput($post['hr_username']); ?></p>
          </div>
          <div class="job-description">
            <p><?php echo sanitizeInput($post['description']); ?></p>
          </div>
          <div class="job-actions">
            <a href="viewApplications.php?job_id=<?php echo $post['job_id']; ?>" class="btn">View Applications</a>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>No job posts found.</p>
    <?php endif; ?>
  </div>


</body>

</html>