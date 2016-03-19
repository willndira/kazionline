<?php

require_once __DIR__.'/connid.php';
require_once __DIR__.'/security.php';
require_once __DIR__.'/Jobs.php';

class Data
{
    public static function load_job_categories()
    {
        global $mysqli;
        
        $res = $mysqli->query("SELECT * FROM job_categories ORDER BY name ASC")
                  or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
        $cats = [];
        
        while($cat = $res->fetch_assoc())
        {
            $cats[] = $cat;
        }
        $res->close();
        
        return $cats;        
    }
    
    public static function search_tags($key)
    {
        global $mysqli;
        
        $key = Security::clean_data($key);
        
        $res_t = $mysqli->query("SELECT * FROM job_tags WHERE name LIKE '%{$key}%'")
                    or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
        $tags = [];
        
        while($row = $res_t->fetch_assoc())
        {
            $tags[] = $row;
        }
        
        $res_t->close();
        
        return json_encode($tags);
    }
    
    public static function load_notifications($user_id)
    {
        global $mysqli;
        
        $notifications = [];
        
        $unread = 0;
        
        $res = $mysqli->query("SELECT msg, is_read, UNIX_TIMESTAMP(stamp) ustamp FROM notifications WHERE user_id={$user_id} "
        . "ORDER BY stamp DESC") or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
        while($notif = $res->fetch_assoc())
        {
            if($notif["is_read"] == 0) $unread++;
            
            $notifications[] = ["msg"=>$notif["msg"], "is_read"=>$notif["is_read"], "time"=>Jobs::time_gap($notif["ustamp"])];
        }
        
        $res->close();
        
        return ["notifications"=>$notifications, "unread"=>$unread];
    }
    
    public static function clear_notifications($user_id)
    {
        global $mysqli;
        
        $mysqli->query("UPDATE notifications SET is_read=1 WHERE user_id={$user_id}")
                or die($mysqli->error." ".__FILE__." line ".__LINE__);
    }
    
    public static function user_data($user_id)
    {
        global $mysqli;
        
        $res = $mysqli->query("SELECT * FROM users WHERE id={$user_id}")
                    or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
        $user = $res->fetch_assoc();
        
        $res->close();
        
        return $user;
    }
    
    public static function abt_usr_mg($names, $email, $message)
    {        
        if(!Security::check_empty([$names, $email, $message]))
        {
            return "Please fill in all fields";
        }
        
        if(!Security::check_email($email))
        {
            return "The email isn't valid";
        }
        
        
        $to = "info@kazionline.co.ke";
        $subject = "User feedback";
        $headers = "From: {$email}";
        
        mail($to, $subject, $message, $headers);
        
        $message="Dear {$names},\nThank you for taking your time to get back to us.\nWe will read your message and someone "
        . "from our team will get back to you personally if necessary.\n\nKind Regards,\nKaziOnline Team";
        $subject = "We just got your message";
        $to = $email;
        $headers = "From: info@kazionline.co.ke";
        
        mail($to, $subject, $message, $headers);
        
        return "ok";                
    }
    
    
}

