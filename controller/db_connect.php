<?

// Creates a database PDO

require $_SERVER['DOCUMENT_ROOT'] . '/model/Database.php';
$db = new Database('localhost', 'root', '', 'forum');