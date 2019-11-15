<?php
setlocale(LC_ALL, 'pl_PL.UTF-8');
date_default_timezone_set('Europe/Warsaw');
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'errorlog.txt');

$dbfile = 'baza/baza.db';
$db = null;
$kom = array();

require_once('inc/db.php');
require_once('inc/functions.php');

init_baza();
init_tables();

echo "<h1>Witaj!</h1>";

get_kom();

?>