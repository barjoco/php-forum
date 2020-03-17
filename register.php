<?

// Create Page object
require 'model/Page.php';
$page = new Page('Register');

// Connect to db
require 'controller/db_connect.php';

// Get index page view
if ($db->getConn()) require 'view/register.phtml';
