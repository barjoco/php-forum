<?
require '../util/globals.php';

// Create a reply to a post

if (isset($_POST['reply_submit'])) {
  require 'db_connect.php';
  session_start();

  $body = $_POST['body'];
  $replying_to = $_POST['replying_to'];

  // Insert post reply
  $db->insert_post('', $body, false, 1, $_SESSION['uid'], $replying_to);
  
  send_success('Reply has been posted', "/post.php?p=$replying_to");
}