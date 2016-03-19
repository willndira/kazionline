<?php

require_once '../neuro/Data.php';

$names = filter_input(INPUT_POST, "names");
$email = filter_input(INPUT_POST, "email");
$message = filter_input(INPUT_POST, "message");

echo Data::abt_usr_mg($names, $email, $message);
