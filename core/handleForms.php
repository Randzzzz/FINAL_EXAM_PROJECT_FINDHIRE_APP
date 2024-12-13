<?php

require_once 'dbConfig.php';
require_once 'models.php';
require_once 'validate.php';

if (isset($_POST['registerUserBtn'])) {

  $username = sanitizeInput($_POST['username']);
  $first_name = sanitizeInput($_POST['first_name']);
  $last_name = sanitizeInput($_POST['last_name']);
  $age = sanitizeInput($_POST['age']);
  $gender = sanitizeInput($_POST['gender']);
  $email = sanitizeInput($_POST['email']);
  $address = sanitizeInput($_POST['address']);
  $nationality = sanitizeInput($_POST['nationality']);
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];
  $role = sanitizeInput($_POST['role']); // Applicant or HR

  if (
    !empty($username) && !empty($first_name) && !empty($last_name) &&
    !empty($age) && !empty($gender) && !empty($email) &&
    !empty($address) && !empty($nationality) &&
    !empty($password) && !empty($confirm_password)
  ) {

    if ($password == $confirm_password) {

      if (validatePassword($password)) {

        $insertQuery = insertNewUser(
          $pdo,
          $username,
          $first_name,
          $last_name,
          $age,
          $gender,
          $email,
          $address,
          $nationality,
          sha1($password),
          $role
        );

        if ($insertQuery) {
          $_SESSION['message'] = "Registration successful!";
          header("Location: ../login.php");
        } else {
          $_SESSION['message'] = "An error occurred while registering. Please try again.";
          header("Location: ../register.php");
        }
      } else {
        $_SESSION['message'] = "Password should be more than 8 characters and should contain both uppercase, lowercase, and numbers";
        header("Location: ../register.php");
      }
    } else {
      $_SESSION['message'] = "Please check if both passwords are equal!";
      header("Location: ../register.php");
    }

  } else {
    $_SESSION['message'] = "Please make sure the input fields 
		are not empty for registration!";

    header("Location: ../register.php");
  }

}


if (isset($_POST['loginUserBtn'])) {

  $username = sanitizeInput($_POST['username']);
  $password = sha1($_POST['password']);

  if (!empty($username) && !empty($password)) {

    $loginQuery = loginUser($pdo, $username, $password);

    if ($loginQuery) {
      if ($_SESSION['role'] === 'applicant') {
        header("Location: ../index.php");
      } elseif ($_SESSION['role'] === 'HR') {
        header("Location: ../HR/hr_dashboard.php");
      } else {
        $_SESSION['message'] = "Unknown user role. Please contact support.";
        header("Location: ../login.php");
      }

    } else {
      header("Location: ../login.php");
    }

  } else {
    $_SESSION['message'] = "Please make sure the input fields 
		are not empty for the login!";
    header("Location: ../login.php");
  }

}

if (isset($_POST['insertJobPostBtn'])) {
  $hr_id = $_SESSION['user_id'];

  $title = sanitizeInput($_POST['title']);
  $description = sanitizeInput($_POST['description']);

  $query = insertJobPosts($pdo, $title, $description, $hr_id);

  if ($query) {
    $_SESSION['message'] = "Job post created successfully!";
    header("Location: ../HR/hr_dashboard.php");
  } else {
    $_SESSION['message'] = "Failed to create the job post.";
    header("Location: ../HR/insertJobPosts.php");
  }
}

if (isset($_POST['insertJobApplicationBtn'])) {

  $job_id = $_POST['job_id'];
  $resume = $_FILES['resume']['name'];
  $cover_letter = $_POST['cover_letter'];
  $applicant_id = $_SESSION['user_id'];

  $target_dir = "../resumes/";
  $target_file = $target_dir . basename($_FILES["resume"]["name"]);

  // Check if the file is uploaded successfully
  if (move_uploaded_file($_FILES["resume"]["tmp_name"], $target_file)) {
    $result = submitApplication($pdo, $applicant_id, $job_id, $target_file, $cover_letter);


    if (is_string($result)) {
      echo $result;
    } else {
      $_SESSION['message'] = "Application submitted successfully!";
      header("Location: ../index.php");
      exit;
    }
  } else {
    echo "Sorry, there was an error uploading your resume.";
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'];
  $application_id = $_POST['application_id'];
  $job_id = $_POST['job_id'];

  if (!in_array($action, ['accept', 'reject'])) {
    die("Invalid action.");
  }

  $status = $action === 'accept' ? 'Accepted' : 'Rejected';

  $result = updateApplicationStatus($pdo, $application_id, $status);

  if ($result === "Application updated successfully.") {
    $_SESSION['success'] = "Application status updated to '$status'.";
  } else {
    $_SESSION['error'] = "Failed to update application.";
  }

  header("Location: ../HR/viewApplications.php?job_id=$job_id");
}



?>