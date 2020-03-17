<?
require 'util/globals.php';
session_start();

if (!isset($_GET['p'])) redirect('/');

// Create Page object
require 'model/Page.php';
$page = new Page('Post');

// Connect to db
require 'controller/db_connect.php';

// Get index page view
if ($db->getConn()) require 'view/post.phtml';
