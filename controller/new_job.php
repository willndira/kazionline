<?php

require_once '../neuro/Jobs.php';

$title = filter_input(INPUT_POST, "job_title");
$desc = filter_input(INPUT_POST, "job_desc");
$category = filter_input(INPUT_POST, "job_category");
$tags = filter_input(INPUT_POST, "job_tags");
$deadline = filter_input(INPUT_POST, "job_deadline");
$amount_min = filter_input(INPUT_POST, "job_amount_min");
$amount_max = filter_input(INPUT_POST, "job_amount_max");

if(!isset($_SESSION))
    session_start();

echo Jobs::newJob($title, $desc, $category, $tags, $deadline, $amount_min, $amount_max);
