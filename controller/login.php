<?php

require_once '../neuro/Access.php';

if(isset($_POST))
{
    echo Access::Login($_POST["login_msisdn"], $_POST["login_passwd"]);    
}

