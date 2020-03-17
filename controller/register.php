<?
require '../util/globals.php';

// Registers a new user in the forum and sets
// session variables

if (isset($_POST['register_submit'])) {
  require 'db_connect.php';
  session_start();

  // Validate passwords match
  $password = $_POST['password'];
  $verify_password = $_POST['verify_password'];

  if ($password !== $verify_password) {
    send_error('Passwords do not match', '/register.php');
  }

  // Check if username is used already
  $username = $_POST['username'];
  
  if ($db->check_username($username)) {
    send_error('This username is already in use', '/register.php');
  }

  // Validate email address
  $email = $_POST['email'];

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    send_error('Invalid email address', '/register.php');
  }

  // Check if email has been used already  
  if ($db->check_email($email)) {
    send_error('This email address is already in use', '/register.php');
  }
  
  $firstname = $_POST['firstname'];
  $lastname = $_POST['lastname'];

  // Encrypt submitted password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Insert user into users table
  $user_id = $db->insert_user($firstname, $lastname, $username, $email, $hashed_password);

  // Set session variables
  $_SESSION['uid'] = $user_id;
  $_SESSION['username'] = $username;

  // Set success message and go to home page
  send_success('Success! Account has been created.', '/');
}