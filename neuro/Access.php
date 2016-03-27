<?php

require_once 'connid.php';
require_once "security.php";
require_once "Data.php";

class Access
{
    public static function Login($msisdn, $password, $rem = FALSE)
    {
        global $mysqli;
        
        if(empty($msisdn) || empty($password))
            return "Fill out all fields";
        
        $msisdn = Security::clean_data($msisdn);
        $password = Security::clean_data($password);
        
        $e_pass = Security::hash_data($password);
        
        $res = $mysqli->query("SELECT * FROM users WHERE (msisdn = '{$msisdn}' OR email='{$msisdn}') AND password = '{$e_pass}'")
                or die($mysqli->error);
        
        if(!$res->num_rows)
            return "<strong>Account not found</strong><br>Phone number/Email or password incorrect<br><strong>Tip:</strong> Use country code in phone";
        
        $data = $res->fetch_assoc();
        
        if($rem)
        {
           setcookie("googol_kz_sessid", Security::cipher_encrypt($data["id"]), time() + (3600 * 24 * 21));           
        }
        
        session_start();
        $_SESSION["sess_id"] = $data["id"];
        
        $res->close();
                
        return "ok";
    }
    
    public static function SignUp($names, $msisdn, $p1, $p2, $location)
    {
        global $mysqli;
        
        if(!Security::check_empty([$names, $msisdn, $p1, $p2, $location]))
            return "Fill out all fields";
        
        $names = Security::clean_data($names);
        $msisdn = Security::clean_data($msisdn);
        $p1 = Security::clean_data($p1);
        $p2 = Security::clean_data($p2);
        $location = Security::clean_data($location);
        
        //validate names       
        if(strlen($names) < 6)
            return "Name is too short. Atleast 7 characters";            
        
        //validate msisdn        
        $msi_p = preg_split("//", $msisdn);
        if($msi_p[1] != "+")
            return "Country code e.g. +254 is needed in Cell Number";
        
        if(strlen($p1) <= 6)
            return "Password too short. Atleast 7 characters";
                
        if(strcmp($p1, $p2))
             return "Passwords do not match";
        
        $p1 = Security::hash_data($p1);
        
        $avatar = "img/avatars/default_avatar.png";
        
        $mysqli->query("INSERT INTO users (names, msisdn, password, avatar, location) VALUES ('{$names}', '{$msisdn}', '{$p1}', '{$avatar}', '{$location}')")
                or die($mysqli->error);
        
        $sess_id = $mysqli->insert_id;  
        
        $mysqli->query("INSERT INTO user_activity_log(user_id, activity_type, activity_text) "
                . "VALUES ({$sess_id}, 1, 'You joined KaziOnline. We warmly welcome you')") or die($mysqli->error." ".__FILE__." line ".__LINE__);
                
        $mysqli->query("INSERT INTO user_trade_log(user_id, balance, direction) VALUES ({$sess_id}, 0, 1)") or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
        session_start();
        $_SESSION["sess_id"] = $sess_id;
        
        return "ok";        
    }
    
    public static function regFirm($names, $email, $p1, $p2, $location)
    {
        global $mysqli;
        
        if(!Security::check_empty([$names, $email, $p1, $p2, $location]))
            return "Fill out all fields";
        
        $names = Security::clean_data($names);
        $email = Security::clean_data($email);
        $p1 = Security::clean_data($p1);
        $p2 = Security::clean_data($p2);
        $location = Security::clean_data($location);
        
        //validate names       
        if(strlen($names) < 6)
            return "Name is too short. Atleast 7 characters";            
        
        //validate email
        if(!Security::check_email($email))
            return "Please enter a valid email address";
        
        if(Data::email_exists($email))
            return "That email is already in use here";
        
        if(strlen($p1) <= 6)
            return "Password too short. Atleast 7 characters";
                
        if(strcmp($p1, $p2))
             return "Passwords do not match";
        
        $p1 = Security::hash_data($p1);
        
        $avatar = "img/avatars/default_company_logo.png";
        
        $mysqli->query("INSERT INTO users (names, email, password, avatar, location, user_type) VALUES ('{$names}', '{$email}', '{$p1}', '{$avatar}', '{$location}', 2)")
                or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
        $sess_id = $mysqli->insert_id;

        $mysqli->query("INSERT INTO user_activity_log(user_id, activity_type, activity_text) "
                . "VALUES ({$sess_id}, 1, 'You joined KaziOnline. We warmly welcome you')") or die($mysqli->error." ".__FILE__." line ".__LINE__);
                
        $mysqli->query("INSERT INTO user_trade_log(user_id, balance, direction) VALUES ({$sess_id}, 0, 1)") or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
        session_start();
        $_SESSION["sess_id"] = $sess_id;
        
        return "ok";        
    }
    
    public static function check_cookie()
    {
        $sess = filter_input(INPUT_COOKIE, 'googol_kz_sessid');
        
        if($sess)
        {
            return Security::cipher_decrypt($sess);            
        }
        
        return FALSE;
    }
    
    public static function upload_avatar($file, $user)
    {
        global $mysqli;
        
        $allowed_mimes = ["image/gif", "image/png", "image/jpeg", "image/bmp", "image/svg+xml"];
        if($file["error"] > 0)
            return "Strange error occured. Please try again";
        
        if($file["size"] > 2097152)
            return "Photo sizes should not exceed 2MB";
        
        if(!in_array($file["type"], $allowed_mimes))
            return "Only images of type jpg, png, gif, bmp and svg are allowed";
        
        $pathinfo = pathinfo($file["name"]);
        $new_name = uniqid().".".$pathinfo["extension"];
        $new_name = "img/avatars/".$new_name;
        
        move_uploaded_file($file["tmp_name"], "../".$new_name);
        
        $mysqli->query("UPDATE users SET avatar='{$new_name}', updated_avatar=1 WHERE id={$user}")
              or die($mysqli->error);
        
        return "ok";
    }
    
}

