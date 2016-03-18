<?php

require_once 'neuro/Jobs.php';

session_start();

$mode = filter_input(INPUT_GET, "mode");
$file_id = filter_input(INPUT_GET, "id");


$download = Jobs::prepare_file_download($mode, $file_id);

if($download["404"] == TRUE)
{
    header("HTTP/1.0 404 Not Found");
    exit;
}

$filename = pathinfo($download["file_path"], PATHINFO_FILENAME);
$ext = pathinfo($download["file_path"], PATHINFO_EXTENSION);

$nice_name = substr($filename, 0, strrpos($filename, "_")).$ext;

header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: Binary"); 
header("Content-disposition: attachment; filename=\"".$nice_name."\"");

readfile($download["file_path"]);
