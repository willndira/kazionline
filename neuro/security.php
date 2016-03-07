<?php

require_once "connid.php";
require_once "Access.php";

define("HASHSALT", "strongsiberianwhitefox");

class Security 
{
    
    public static function check_empty($haystack)
    {
        foreach($haystack as $data)
        {
            if($data == NULL)
                return FALSE;
            
            $data = trim($data);
            if(strlen($data) == 0)
                return FALSE;
        }
        
        return TRUE;
    }
    
    public static function clean_data($input) 
    {
        global $mysqli;

        $input = strip_tags(htmlentities($input));
        $input = $mysqli->real_escape_string($input);

        $input = preg_replace("/;/", "", $input);
        
        $input=trim($input);

        return $input;
    }
    
    public static function clean_array($haystack)
    {
        $_clean = [];
        foreach($haystack as $data)
        {
            $_clean [] = self::clean_data($data);
        }
        
        return $_clean;
    }
    
    public static function hash_data($data)
    {
        return hash("SHA256", $data.HASHSALT);        
    }
    
    //revise encryption and decryption
    public static function cipher_encrypt($data)
    {
        $chars = preg_split("//", $data);
        $cipher = "";
        
        foreach($chars as $char)
        {
            $ascii = ord($char);
            $ascii += 1000;
            $cipher_char = chr($ascii);
            $cipher.=$cipher_char;            
        }
        
        return $cipher;
    }
    
    public static function cipher_decrypt($cipher)
    {
        $cipher_chars = preg_split("//", $cipher);
        $raw_data = "";
        
        foreach($cipher_chars as $c_char)
        {
            $ascii = ord($c_char);
            $ascii -= 1000;
            
            $raw_char = chr($ascii);
            $raw_data.=$raw_char;
        }
        
        return $raw_data;        
    }
    
    public static function check_email($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }    
    
    public static function check_session($redirect=FALSE)
    {
        
        if((!isset($_SESSION["sess_id"]) || empty($_SESSION["sess_id"])) && !Access::check_cookie())
        {
            if($redirect)
            {
                header("Location:index.php");
                exit;
            }
            
            return "bad";            
        }
        
        if(!isset($_SESSION["sess_id"]) || empty($_SESSION["sess_id"]))
        {
            $_SESSION["sess_id"] = Access::check_cookie();
        }
    }

}
