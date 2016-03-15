<?php

require_once '../neuro/Jobs.php';

$rating = filter_input(INPUT_POST, "rating", FILTER_VALIDATE_INT);
if(!$rating)
{
    echo "Rating must be a valid integer value";
    exit;
}

echo Jobs::closeJob($rating);