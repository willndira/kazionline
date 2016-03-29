<?php

require_once '../neuro/Data.php';

session_start();

$chats = Data::load_chats($_SESSION["sess_id"]);

$new_messages = array_diff($chats["loaded"], $_SESSION["loaded_chat_messages"]);
$new_threads = array_diff_key($chats["threads"], $_SESSION["loaded_chat_threads"]);

$_SESSION["loaded_chat_messages"] = $chats["loaded"];
$_SESSION["loaded_chat_threads"] = $chats["threads"];

$data = ["new_messages"=>$new_messages, "new_threads"=>$new_threads, "total_unread"=>$chats["total_unread"], 
    "pal_unread"=>$chats["pal_unread"], "pal_data"=>$chats["pal_data"]];


echo json_encode($data);
