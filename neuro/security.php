<?php

require_once "connid.php";
require_once "Access.php";

define("HASHSALT", "strongsiberianwhitefox");
define("IV_SIZE", 16);

class Security
{

    public static function check_empty($haystack)
    {
        foreach ($haystack as $data)
        {
            if ($data == NULL)
                return FALSE;

            $data = trim($data);
            if (strlen($data) == 0)
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

        $input = trim($input);

        return $input;
    }

    public static function clean_array($haystack)
    {
        $_clean = [];
        foreach ($haystack as $data)
        {
            $_clean [] = self::clean_data($data);
        }

        return $_clean;
    }

    public static function hash_data($data)
    {
        return hash("SHA256", $data . HASHSALT);
    }

    //revise encryption and decryption
    public static function cipher_encrypt($data)
    {
        $key = pack("A*", HASHSALT);
        
        $iv = mcrypt_create_iv(IV_SIZE, MCRYPT_RAND);

        $cipher = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);

        $cipher = $iv . $cipher;

        return base64_encode($cipher);
    }

    public static function cipher_decrypt($cipher)
    {
        $key = pack("A*", HASHSALT);
        $ciphertext_dec = base64_decode($cipher);

        $iv_dec = substr($ciphertext_dec, 0, IV_SIZE);

        $ciphertext_dec = substr($ciphertext_dec, IV_SIZE);
        $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);

        echo $plaintext_dec;
    }

    public static function check_email($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function check_session($redirect = FALSE)
    {

        if ((!isset($_SESSION["sess_id"]) || empty($_SESSION["sess_id"])) && !Access::check_cookie())
        {
            if ($redirect)
            {
                header("Location:index.php");
                exit;
            }

            return "bad";
        }

        if (!isset($_SESSION["sess_id"]) || empty($_SESSION["sess_id"]))
        {
            $_SESSION["sess_id"] = Access::check_cookie();
        }
    }

}
