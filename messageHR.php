<?php
require_once 'core/models.php';
require_once 'core/access.php';
require_once 'core/validate.php';
restrictAccess('applicant');

if (!isset($_SESSION['username'])) {
  header("Location: login.php");
}


$applicant_id = $_SESSION['user_id'];

// Retrieve the HR ID
$hr_id = getHRId($pdo);

$messages = getMessages($pdo, $hr_id, $applicant_id);

// Handle sending message
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
  $message = trim($_POST['message']);
  if (!empty($message)) {
    sendMessage($pdo, $applicant_id, $hr_id, $message);
    $_SESSION['message'] = 'Message sent successfully!';
    header("Location: messageHR.php");
    exit();
  } else {
    $_SESSION['message'] = 'Message cannot be empty.';
  }
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
  <link rel="stylesheet" href="css/index.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="css/header.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="css/message.css?v=<?php echo time(); ?>">
</head>

<body>
  <div class="title">
    <h1>FindHire</h1>
    <div class="title-row">
      <div class="title-row-value">

        <div class="audit">

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
    <h1>Message Employer</h1>
    <?php if (isset($_SESSION['message'])) { ?>
      <h5 style="color: green; margin-top: 15px;"><?php echo $_SESSION['message']; ?></h5>
    <?php }
    unset($_SESSION['message']); ?>
  </div>

  <!-- Display conversation -->
  <div class="message-box">
    <?php if (!empty($messages)): ?>
      <?php foreach ($messages as $msg): ?>
        <div class="message">
          <span class="sender"><?php echo sanitizeInput($msg['sender_name']); ?>:</span>
          <span><?php echo sanitizeInput($msg['message']); ?></span>
          <div class="time">
            <?php echo date('Y-m-d H:i:s', strtotime($msg['sent_at'])); ?>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>No conversation yet.</p>
    <?php endif; ?>
  </div>

  <div class="message-input-box">
    <!-- Send message form -->
    <form method="POST" action="">
      <textarea class="text-area" name="message" rows="4" cols="50" placeholder="Type your message here..."
        required></textarea>
      <br>
      <button id="sendBtn" type="submit">Send Message</button>
    </form>
  </div>






</body>

</html>