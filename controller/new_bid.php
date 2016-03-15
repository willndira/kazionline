<?php

require_once '../neuro/Jobs.php';

$bid_comment = filter_input(INPUT_POST, "cmt");
$bid_amount = filter_input(INPUT_POST, "amt");

echo Jobs::newBid($bid_comment, $bid_amount);
