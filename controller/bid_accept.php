<?php

require_once '../neuro/Jobs.php';

$bid_id = filter_input(INPUT_POST, "dx");

echo Jobs::accept_bid($bid_id);