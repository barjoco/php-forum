<?

// Update the cover image of the current user
// Compresses the uploaded image and moves it to
// the cover images directory

if (isset($_POST['update_cover_img_submit'])) {

  $img_attachment = $_FILES['img_attachment']['name'] != '' ? 1 : 0;

  if ($img_attachment) {
    session_start();
    require 'database_connection.php';

    $u = $_POST['u'];

    $img_destination = $_SERVER['DOCUMENT_ROOT'] . 'static/img/cover_img';
    $source = $_FILES['img_attachment']['tmp_name'];
    $target = "$img_destination/$u.jpg";
    
    move_uploaded_file($source, $target);

    $img = imagecreatefromjpeg($target);
    imagejpeg($img, $target, 50);

    $sql = 'UPDATE users SET default_cover_img = 0 WHERE id = ?';
    $db->exec($sql, 's', $u);

    $_SESSION['success'] = 'Cover picture updated';
    header('Location: /settings.php');
  }
}
