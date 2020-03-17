<?
require 'util/globals.php';
session_start();

if (!isset($_SESSION['uid'])) redirect('/login.php');

// Create Page object
require 'model/Page.php';
$page = new Page('Settings');

// Connect to db
require 'controller/db_connect.php';

// Get index page view
require 'view/settings.phtml';
