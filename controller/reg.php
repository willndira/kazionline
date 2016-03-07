<?php

require_once '../neuro/Access.php';

$names = filter_input(INPUT_POST, "names");
$msisdn = filter_input(INPUT_POST, "msisdn");
$passwd = filter_input(INPUT_POST, "passwd");
$passwd2 = filter_input(INPUT_POST, "passwd2");
$location = filter_input(INPUT_POST, "myloc");

if(!$names || !$msisdn || !$passwd || !$passwd2 || !$location)
{
    echo "Fill in all fields";
    exit;
}

echo Access::SignUp($names, $msisdn, $passwd, $passwd2, $location);
