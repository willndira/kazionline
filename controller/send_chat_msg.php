<?php

require_once '../neuro/Data.php';

session_start();

$p2 = filter_input(INPUT_POST, "rdx");
$msg = filter_input(INPUT_POST, "msx");

echo Data::send_chat_msg($_SESSION["sess_id"], $p2, $msg);