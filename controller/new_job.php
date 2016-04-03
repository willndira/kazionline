<?php

require_once '../neuro/Jobs.php';

$title = filter_input(INPUT_POST, "job_title");
$desc = filter_input(INPUT_POST, "job_desc");
$category = filter_input(INPUT_POST, "job_category");
$tags = filter_input(INPUT_POST, "job_tags");
$workers = filter_input(INPUT_POST, "no_workers");
$pmodel = filter_input(INPUT_POST, "payment_model");
$hpw = filter_input(INPUT_POST, "hours_per_week");
$duration = filter_input(INPUT_POST, "job_duration");
$amount_min = filter_input(INPUT_POST, "job_amount_min");
$amount_max = filter_input(INPUT_POST, "job_amount_max");

if(!isset($_SESSION))
    session_start();

if($pmodel == "fixed")
    $hpw = 0;

echo Jobs::newJob($title, $desc, $category, $tags, $duration, $workers, $pmodel, $amount_min, $amount_max, $hpw);