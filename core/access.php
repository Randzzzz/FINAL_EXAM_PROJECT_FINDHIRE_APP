<?php
function restrictAccess($requiredRole)
{

  if (!isset($_SESSION['role'])) {
    $_SESSION['message'] = "Please log in first.";
    header("Location: ../login.php");
    exit();
  }

  if ($_SESSION['role'] == $requiredRole) {
    return; // Stop further execution, no redirection needed
  }

  // Redirect if the role does not match
  $_SESSION['message'] = "Access denied! You are not authorized to view this page.";

  if ($_SESSION['role'] == 'HR') {
    header("Location: HR/hr_dashboard.php");
  } elseif ($_SESSION['role'] == 'applicant') {
    header("Location: ../index.php");
  } else {
    header("Location: ../login.php");
  }
  exit();
}


?>