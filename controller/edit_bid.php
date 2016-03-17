<?php

require_once '../neuro/Jobs.php';

session_start();

$bid_comment = filter_input(INPUT_POST, "cmt");
$bid_amount = filter_input(INPUT_POST, "amt");

echo Jobs::editBid($bid_comment, $bid_amount);
