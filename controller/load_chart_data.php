<?php

require_once "../neuro/Data.php";

if(!$_SESSION)
    session_start ();

$chart = Data::get_trade_chart($_SESSION["sess_id"]);
$xdata = [];

foreach($chart["x"]["x"] as $label=>$value)
{
    $xdata[] = [$label, $value];
}

echo json_encode($xdata);