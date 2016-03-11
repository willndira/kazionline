<?php

require_once 'connid.php';

$sql = "CREATE TABLE IF NOT EXISTS users"
        . "(id INT(255) NOT NULL AUTO_INCREMENT PRIMARY KEY,"
        . "names TINYTEXT CHARACTER SET utf32 NOT NULL,"
        . "msisdn VARCHAR(50) NOT NULL,"
        . "password VARCHAR(255) NOT NULL,"
        . "location TINYTEXT CHARACTER SET utf32 NOT NULL,"
        . "avatar VARCHAR(255) NOT NULL,"
        . "balance DECIMAL(12,2) DEFAULT 0,"
        . "total_transacted DECIMAL(12,2) DEFAULT 0,"
        . "join_stamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP)";

$mysqli->query($sql) or die($mysqli->error." ".__FILE__." line ".__LINE__);

$sql = "CREATE TABLE IF NOT EXISTS jobs"
        . "(id INT(255) NOT NULL AUTO_INCREMENT PRIMARY KEY,"
        . "title TINYTEXT CHARACTER SET utf32 NOT NULL,"
        . "description LONGTEXT CHARACTER SET utf32 NOT NULL,"
        . "category INT(2) NOT NULL,"
        . "deadline INT(255) NOT NULL,"
        . "amount_min DECIMAL(12,2) NOT NULL,"
        . "amount_max DECIMAL(12,2) NOT NULL,"
        . "owner INT(255) NOT NULL,"
        . "status INT(1) NOT NULL DEFAULT 1,"
        . "submitted_file VARCHAR(255) NOT NULL DEFAULT 0,"
        . "created_on TIMESTAMP DEFAULT CURRENT_TIMESTAMP)";

$mysqli->query($sql) or die($mysqli->error." ".__FILE__." line ".__LINE__);

$sql = "CREATE TABLE IF NOT EXISTS job_bucketlist"
        . "(id INT(255) NOT NULL AUTO_INCREMENT PRIMARY KEY,"
        . "job_id INT(255) NOT NULL,"
        . "user_id INT(255) NOT NULL)";

$mysqli->query($sql) or die($mysqli->error." ".__FILE__." line ".__LINE__);

$sql = "CREATE TABLE IF NOT EXISTS job_bids"
        . "(id INT(255) NOT NULL AUTO_INCREMENT PRIMARY KEY,"
        . "job_id INT(255) NOT NULL,"
        . "user_id INT(255) NOT NULL,"
        . "comment LONGTEXT CHARACTER SET utf32 NOT NULL,"
        . "awarded INT(1) NOT NULL DEFAULT 0,"
        . "stamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP)";

$mysqli->query($sql) or die($mysqli->error." ".__FILE__." line ".__LINE__);

$sql = "CREATE TABLE IF NOT EXISTS job_categories"
        . "(id INT(255) NOT NULL AUTO_INCREMENT PRIMARY KEY,"
        . "name VARCHAR(100) NOT NULL)";

$mysqli->query($sql) or die($mysqli->error." ".__FILE__." line ".__LINE__);

$sql = "CREATE TABLE IF NOT EXISTS job_tags"
        . "(id INT(255) NOT NULL AUTO_INCREMENT PRIMARY KEY,"
        . "name TINYTEXT CHARACTER SET utf32)";

$mysqli->query($sql) or die($mysqli->error." ".__FILE__." line ".__LINE__);

$sql = "CREATE TABLE IF NOT EXISTS used_job_tags"
        . "(id INT(255) NOT NULL AUTO_INCREMENT PRIMARY KEY,"
        . "job_id INT(255) NOT NULL,"
        . "tag_id INT(255) NOT NULL)";

$mysqli->query($sql) or die($mysqli->error." ".__FILE__." line ".__LINE__);

$sql = "CREATE TABLE IF NOT EXISTS job_attachments"
        . "(id INT(255) NOT NULL AUTO_INCREMENT PRIMARY KEY,"
        . "job_id INT(255) NOT NULL,"
        . "file_path VARCHAR(255) NOT NULL)";

$mysqli->query($sql) or die($mysqli->error." ".__FILE__." line ".__LINE__);

