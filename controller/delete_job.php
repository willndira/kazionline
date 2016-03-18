<?php

require_once '../neuro/Jobs.php';

session_start();

echo Jobs::deleteJob();