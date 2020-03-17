<?
require 'util/globals.php';

// Log the user out by clearing the session

session_start();
session_unset();
session_destroy();
unset($_SESSION['uid']);
unset($_SESSION['username']);

session_start();
send_success('You have been logged out', '/');