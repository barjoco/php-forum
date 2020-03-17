<?
require '../util/globals.php';

// Takes input from filter form, parse, and return user to the page with an
// appropriate query string

if (isset($_POST['filter_submit'])) {
  session_start();

  $c = implode(',', array_slice(array_keys($_POST), 0, -4));
  $p = $_POST['page_num'];
  $l = $_POST['page_len'];
  $s = $_POST['query'];

  send_success('Filter applied', "/search.php?c=$c&p=$p&l=$l&s=$s");
}