<?php

require_once '../neuro/Jobs.php';

session_start();

$title = filter_input(INPUT_POST, "job_title");
$desc = filter_input(INPUT_POST, "job_desc");
$category = filter_input(INPUT_POST, "job_category");
$tags = filter_input(INPUT_POST, "job_tags");
$deadline = filter_input(INPUT_POST, "job_deadline");
$amount_min = filter_input(INPUT_POST, "job_amount_min");
$amount_max = filter_input(INPUT_POST, "job_amount_max");

echo Jobs::updateJob($title, $desc, $category, $tags, $deadline, $amount_min, $amount_max);
