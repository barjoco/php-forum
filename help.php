<?
require 'util/globals.php';
session_start();

// Create Page object
require 'model/Page.php';
$page = new Page('Help');

// Connect to db
require 'controller/db_connect.php';

// Get index page view
require 'view/help.phtml';
