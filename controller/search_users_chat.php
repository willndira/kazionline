<?php

require_once '../neuro/Data.php';

$key = filter_input(INPUT_GET, "q");

if(!$key)
{
    echo "{}";
    exit;
}

header("Content-Type: text/html; charset=UTF-8");

echo Data::search_users($key);
