<?

// Create Page object
require 'model/Page.php';
$page = new Page('Login');

// Connect to db
require 'controller/db_connect.php';

// Get index page view
if ($db->getConn()) require 'view/login.phtml';
