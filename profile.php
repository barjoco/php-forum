<?
require 'util/globals.php';
session_start();

if (!isset($_GET['u'])) redirect('/');

// Create Page object
require 'model/Page.php';
$page = new Page('Profile');

// Connect to db
require 'controller/db_connect.php';

// Get index page view
if ($db->getConn()) require 'view/profile.phtml';
