<?php

require_once '../neuro/Jobs.php';

if(!isset($_SESSION))
    session_start();

echo Jobs::upload_jobsb_files($_FILES["jobfile"]);
