<?
require '../util/globals.php';

// Takes input from filter form, parse, and return user to the page with an
// appropriate query string

if (isset($_POST['filter_submit'])) {
  session_start();

  $p = $_POST['page_num'];
  $l = $_POST['page_len'];

  send_success('Filter applied', "/inbox.php?p=$p&l=$l");
}