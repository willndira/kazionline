<?php

require_once '../neuro/Access.php';

$msi_email = filter_input(INPUT_POST, "msie");
$passwd = filter_input(INPUT_POST, "passwd");
$rem = filter_input(INPUT_POST, "rem");

echo Access::Login($msi_email, $passwd, $rem);
