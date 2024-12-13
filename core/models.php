<?php

require_once 'dbConfig.php';

function insertNewUser($pdo, $username, $first_name, $last_name, $age, $gender, $email, $address, $nationality, $password, $role)
{

  $checkUserSql = "SELECT * FROM user_accounts WHERE username = ?";
  $checkUserSqlStmt = $pdo->prepare($checkUserSql);
  $checkUserSqlStmt->execute([$username]);

  if ($checkUserSqlStmt->rowCount() == 0) {

    $sql = "INSERT INTO user_accounts (username, first_name, last_name, age, gender, email, address, nationality, password, role) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute([$username, $first_name, $last_name, $age, $gender, $email, $address, $nationality, $password, $role]);

    if ($executeQuery) {
      $_SESSION['message'] = "User successfully inserted";
      return true;
    } else {
      $_SESSION['message'] = "An error occured from the query";
    }
  } else {
    $_SESSION['message'] = "User already exists";
  }
}

function loginUser($pdo, $username, $password)
{
  $sql = "SELECT * FROM user_accounts WHERE username=?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$username]);

  if ($stmt->rowCount() == 1) {
    $userInfoRow = $stmt->fetch();
    $userIDFromDB = $userInfoRow['user_id'];
    $usernameFromDB = $userInfoRow['username'];
    $passwordFromDB = $userInfoRow['password'];
    $roleFromDB = $userInfoRow['role']; // Role (applicant or HR)

    if ($password == $passwordFromDB) {
      $_SESSION['user_id'] = $userIDFromDB;
      $_SESSION['username'] = $usernameFromDB;
      $_SESSION['role'] = $roleFromDB;
      $_SESSION['message'] = "Login successful!";
      return true;
    } else {
      $_SESSION['message'] = "Password is invalid, but user exists";
    }
  }


  if ($stmt->rowCount() == 0) {
    $_SESSION['message'] = "Username doesn't exist from the database. You may consider registration first";
  }

}

function insertJobPosts($pdo, $title, $description, $hr_id)
{
  $sql = "INSERT INTO JobPosts (title, description, hr_id) VALUES (?,?,?)";

  $stmt = $pdo->prepare($sql);
  $executeQuery = $stmt->execute([$title, $description, $hr_id]);

  if ($executeQuery) {
    return true;
  }
}
function getJobPosts($pdo)
{
  $sql = "SELECT 
                jobPosts.job_id,
                JobPosts.title, 
                JobPosts.description, 
                user_accounts.username AS hr_username 
            FROM JobPosts
            JOIN user_accounts ON JobPosts.hr_id = user_accounts.user_id
            ORDER BY JobPosts.created_at DESC";

  $stmt = $pdo->query($sql);
  return $stmt->fetchAll();
}
function searchForAJobPost($pdo, $searchQuery)
{

  $sql = "SELECT 
                JobPosts.*, 
                user_accounts.username AS hr_username 
            FROM JobPosts
            JOIN user_accounts ON JobPosts.hr_id = user_accounts.user_id
            WHERE CONCAT(JobPosts.title, JobPosts.description, user_accounts.username) 
            LIKE ?
            ORDER BY JobPosts.created_at DESC";

  $stmt = $pdo->prepare($sql);
  $executeQuery = $stmt->execute(["%" . $searchQuery . "%"]);
  if ($executeQuery) {
    return $stmt->fetchAll();
  }
}

function getApplicationsByJob($pdo, $job_id)
{
  $sql = "SELECT 
                Applications.application_id, 
                Applications.applicant_id, 
                Applications.resume_path, 
                Applications.cover_letter, 
                Applications.application_status, 
                user_accounts.username AS applicant_username 
            FROM Applications 
            JOIN user_accounts ON Applications.applicant_id = user_accounts.user_id
            WHERE Applications.job_id = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$job_id]);
  return $stmt->fetchAll();
}
function getJobDetails($pdo, $job_id)
{
  $sql = "SELECT title FROM JobPosts WHERE job_id = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$job_id]);
  return $stmt->fetch();
}


function updateApplicationStatus($pdo, $application_id, $status)
{
  $sql = "UPDATE Applications SET application_status = ? WHERE application_id = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$status, $application_id]);
  return $stmt->rowCount() > 0 ? "Application updated successfully." : "Failed to update application.";
}

function getHiredApplicantsByJob($pdo, $job_id)
{
  $stmt = $pdo->prepare("SELECT user_accounts.username,
                        Applications.resume_path, 
                        Applications.applied_at
        FROM Applications
        JOIN user_accounts ON Applications.applicant_id = user_accounts.user_id
        WHERE Applications.job_id = ? AND Applications.application_status = 'Accepted'
    ");
  $stmt->execute([$job_id]);
  return $stmt->fetchAll();
}

function submitApplication($pdo, $applicant_id, $job_id, $resume_path, $cover_letter)
{
  // Check if the job exists
  $sql = "SELECT COUNT(*) FROM JobPosts WHERE job_id = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$job_id]);
  $job_exists = $stmt->fetchColumn();

  if ($job_exists == 0) {

    return "The job you're trying to apply for does not exist.";
  }

  // Insert the application into the database
  $sql = "INSERT INTO Applications (applicant_id, job_id, resume_path, cover_letter)
            VALUES (?, ?, ?, ?)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    $applicant_id,
    $job_id,
    $resume_path,
    $cover_letter
  ]);

  return $stmt->rowCount();
}
function getUserApplications($pdo, $user_id)
{
  $sql = "SELECT 
              Applications.application_id, 
              Applications.resume_path, 
              Applications.application_status, 
              Applications.applied_at, 
              JobPosts.title AS job_title, 
              JobPosts.description AS job_description
          FROM Applications
          INNER JOIN JobPosts ON Applications.job_id = JobPosts.job_id
          WHERE Applications.applicant_id = ?";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([$user_id]);

  return $stmt->fetchAll();
}


function getMessages($pdo, $user1_id, $user2_id)
{
  $sql = "SELECT Messages.message_id,
                  Messages.sender_id,
                  Messages.receiver_id,
                  Messages.message,
                  Messages.sent_at,
                  CONCAT(user_accounts.first_name, ' ', user_accounts.last_name) AS sender_name
            FROM Messages
            JOIN user_accounts ON Messages.sender_id = user_accounts.user_id
            WHERE (Messages.sender_id = :user1_id AND Messages.receiver_id = :user2_id)
                OR (Messages.sender_id = :user2_id AND Messages.receiver_id = :user1_id)
            ORDER BY Messages.sent_at ASC";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':user1_id' => $user1_id,
    ':user2_id' => $user2_id,
  ]);

  return $stmt->fetchAll();
}

function sendMessage($pdo, $sender_id, $receiver_id, $message)
{
  // Use a prepared statement to prevent SQL injection
  $sql = "INSERT INTO Messages (sender_id, receiver_id, message, sent_at) 
            VALUES (:sender_id, :receiver_id, :message, NOW())";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':sender_id' => $sender_id,
    ':receiver_id' => $receiver_id,
    ':message' => $message,
  ]);
}

function getHRId($pdo)
{
  $sql = "SELECT user_id FROM user_accounts WHERE role = 'HR' ORDER BY RAND() LIMIT 1"; // Fetch a random HR
  $stmt = $pdo->prepare($sql);
  $stmt->execute();

  return $stmt->fetchColumn(); // Returns a random HR's user_id
}
function getApplicantsWhoMessageHR($pdo, $hr_id)
{
  $sql = "SELECT DISTINCT user_accounts.user_id, user_accounts.first_name, user_accounts.last_name
          FROM Messages
          JOIN user_accounts ON Messages.sender_id = user_accounts.user_id
          WHERE Messages.receiver_id = :hr_id
          ORDER BY user_accounts.first_name";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([':hr_id' => $hr_id]);
  return $stmt->fetchAll();
}


?>