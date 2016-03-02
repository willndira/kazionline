<?php

define("DBNAME", "neuro");
define("USR", "root");
define("PASSWD", "");
define("HOST", "127.0.0.1");

$mysqli = new mysqli(HOST, USR, PASSWD);
if(!$mysqli->select_db(DBNAME))
{
    $mysqli->query("CREATE DATABASE ".DBNAME) or die($mysqli->error." ".__FILE__." line ".__LINE__);
}

$mysqli->query("SET NAMES utf8");
