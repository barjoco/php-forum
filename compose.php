<?
require 'util/globals.php';
session_start();

if (!isset($_SESSION['uid'])) redirect('/login.php');

// Create Page object
require 'model/Page.php';
$page = new Page('Compose');

// Connect to db
require 'controller/db_connect.php';

// Get index page view
if ($db->getConn()) require 'view/compose.phtml';
