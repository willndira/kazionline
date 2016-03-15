<?php

require_once '../neuro/Data.php';

if(!isset($_SESSION))
    session_start();

Data::clear_notifications($_SESSION["sess_id"]);
