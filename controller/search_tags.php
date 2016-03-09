<?php

require_once '../neuro/Data.php';

$key = filter_input(INPUT_GET, "term");

if(!$key)
{
    echo json_encode([]);
    exit;
}

echo Data::search_tags($key);
