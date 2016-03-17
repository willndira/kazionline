<?php

require_once '../neuro/Jobs.php';

session_start();

$bid_id = filter_input(INPUT_POST, "dx");

echo Jobs::accept_bid($bid_id);