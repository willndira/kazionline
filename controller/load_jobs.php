<?php

require_once '../neuro/Jobs.php';

$key = filter_input(INPUT_GET, "key");
$cat = filter_input(INPUT_GET, "ctg");
$amt = filter_input(INPUT_GET, "amt");
$tags = filter_input(INPUT_GET, "tgs");
$page = filter_input(INPUT_GET, "page");

echo json_encode(Jobs::loadJobs($page, $key, $cat, $tags, $amt));
