<?php

require_once '../neuro/Access.php';

$names = filter_input(INPUT_POST, "name");
$email = filter_input(INPUT_POST, "email");
$passwd = filter_input(INPUT_POST, "passwd");
$passwd2 = filter_input(INPUT_POST, "passwd2");
$location = filter_input(INPUT_POST, "myloc");

echo Access::regFirm($names, $email, $passwd, $passwd2, $location);