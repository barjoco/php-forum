<?
require '../util/globals.php';

// Log user into the forum and set session variables

if (isset($_POST['login_submit'])) {
  require 'db_connect.php';
  session_start();

  $username = $_POST['username'];
  $password = $_POST['password'];

  // Check if username exists
  if (!$db->check_username($username)) {
    send_error('No users found with this username', '/login.php');
  }

  $user = $db->get_user_by_username($username);

  // Verify password
  if (password_verify($password, $user['password'])) {
    // Set session variables
    $_SESSION['uid'] = $user['id'];
    $_SESSION['username'] = $username;

    // Return to home page
    send_success('You have been logged in', '/');
  } else {
    // Password verify failed
    send_error('Username and password do not match', '/login.php');
  }
}