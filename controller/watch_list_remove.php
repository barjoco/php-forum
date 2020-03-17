<?
require '../util/globals.php';

// Remove an item from the current user's watch list

session_start();

if (isset($_SESSION['uid'])) {
  require 'db_connect.php';
  $db->watch_list_remove($_GET['n'], $_SESSION['uid']);
  send_success('Post(s) removed from watch list', '/watch-list.php');
} else {
  send_error('Can\'t delete post', '/watch-list.php');
}