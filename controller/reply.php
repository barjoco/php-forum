<?

// Create a reply on a top level post

if (isset($_POST['reply_submit'])) {

  session_start();
  require 'database_connection.php';

  $body = $_POST['body'];
  $replying_to = $_POST['replying_to'];

  // Insert new post into posts table
  $sql = 'INSERT INTO posts VALUES (NULL, "", ?, "", 0, 0, 0, ?, ?, NULL)';
  $db->exec($sql, 'sis', $body, $replying_to, $_SESSION['username']);

  // Send a message to the original poster to alert them
  // that someome has replied to their post
  $sql = 'SELECT title, created_by FROM posts WHERE id = ? LIMIT 1';
  $row = $db->query_row($sql, 'i', $replying_to);
  $title = $row['title'];
  $created_by = $row['created_by'];

  if ($created_by != $_SESSION['username']) {
    $replier = $_SESSION['username'];
    $sql = "INSERT INTO messages VALUES (NULL, ?, ?, ?, ?, NULL)";
    $db->exec(
      $sql, 'ssss',
      'A user replied to your post',
      "$replier has just replied to your post: $title",
      $created_by,
      'JS Forum'
    );
  }

  // Send a message to all users who are watching this post
  // to alert them that someone has replied to a post that
  // they're watching
  $sql = 'SELECT username FROM watch_list WHERE post_id = ?';
  $users_watching = $db->query($sql, 's', $replying_to);

  while ($user_watching = mysqli_fetch_array($users_watching)) {
    if ($user_watching[0] != $_SESSION['username']) {
      $sql = "INSERT INTO messages VALUES (NULL, ?, ?, ?, ?, NULL)";
      $db->exec(
        $sql, 'ssss',
        'A user replied to a post you are watching',
        "Someone has just replied in a post you are watching: $title",
        $user_watching[0],
        'JS Forum'
      );
    }
  }

  $_SESSION['success'] = 'Reply posted';
  header("Location: /post.php?p=$replying_to");
}