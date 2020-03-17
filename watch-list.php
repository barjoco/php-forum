<?
require 'util/globals.php';
session_start();

if (!isset($_SESSION['uid'])) redirect('/login.php');

// Create Page object
require 'model/Page.php';
$page = new Page('Watch list');

// Connect to db
require 'controller/db_connect.php';

// Get index page view
if ($db->getConn()) require 'view/watch_list.phtml';
