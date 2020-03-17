<?
require '../util/globals.php';

// Send a message to a specific user
// They're able to view messages from their inbox

if (isset($_POST['compose_submit'])) {
  require 'db_connect.php';
  session_start();

  $title = $_POST['title'];
  $body = $_POST['body'];
  $recipient = $db->get_user_by_username($_POST['recipient']);

  $db->insert_message($title, $body, $_SESSION['uid'], $recipient['id']);
  send_success('Message sent!', '/inbox.php');
}