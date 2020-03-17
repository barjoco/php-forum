<?
require '../util/globals.php';

// Creates a new post based on submitted data

if (isset($_POST['new_post_submit'])) {
  require 'db_connect.php';
  session_start();

  $title = $_POST['title'];
  $category = $_POST['category'];  
  $body = $_POST['body'];

  if ($category == '') send_error('Please select a category', '/new-post.php');
  if ($title == '') send_error('Please enter a title for the post', '/new-post.php');
  if ($body == '') send_error('Please enter content for the post', '/new-post.php');
  if (strlen($body) > 1024) send_error('Post content can\'t be longer than 1024 characters', '/new-post.php');

  // Get upload image file
  $has_img = $_FILES['img_attachment']['name'] != '' ? 1 : 0;

  // Insert post
  $db->insert_post($title, $body, $has_img, $category, $_SESSION['uid'], NULL);

  // Get the last inserted ID
  $p = $db->get_last_id();

  // Compress uploaded image and move to the image directory
  if ($has_img) {
    $img_destination = $_SERVER['DOCUMENT_ROOT'] . '/static/img/img_attachment';
    $source = $_FILES['img_attachment']['tmp_name'];
    $target = "$img_destination/$p.jpg";

    move_uploaded_file($source, $target);

    $img = imagecreatefromjpeg($target);
    imagejpeg($img, $target, 50);
  }
  
  send_success('Post created', "/post.php?p=$p");
}