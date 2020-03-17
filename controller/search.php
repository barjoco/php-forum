<?
require '../util/globals.php';

// Set search query string

require 'db_connect.php';
$users = $db->get_users_by_query($_GET['s']);

echo var_dump($users);