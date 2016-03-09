<?php

require_once '../neuro/Jobs.php';

if(!isset($_SESSION))
    session_start();

$file = filter_input(INPUT_POST, "name");
if(!$file)
{
    echo "fail";
    exit;
}

Jobs::delete_uploaded_job_file($file);
echo "ok";