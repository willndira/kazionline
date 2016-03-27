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
    
    public static function search_users($key)
    {
        global $mysqli;
        
        $key = Security::clean_data($key);
        
        $res = $mysqli->query("SELECT id, names, avatar FROM users WHERE names LIKE '%{$key}%'")
                or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
        $hints = [];
        
        while($data = $res->fetch_assoc())
        {
            $hints [] = $data;
        }
        
        return json_encode($hints);
    }
    
    public static function load_notifications($user_id)
    {
        global $mysqli;
        
        $notifications = [];
        
        $unread = 0;
        
        $res = $mysqli->query("SELECT msg, is_read, notif_type, UNIX_TIMESTAMP(stamp) ustamp FROM notifications WHERE user_id={$user_id} "
        . "ORDER BY stamp DESC") or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
        while($notif = $res->fetch_assoc())
        {
            if($notif["is_read"] == 0) $unread++;
            
            $notifications[] = ["msg"=>$notif["msg"], "is_read"=>$notif["is_read"], "type"=>$notif["notif_type"], "time"=>Jobs::time_gap($notif["ustamp"])];
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
    
    public static function load_activity_log($user_id)
    {
        global $mysqli;
        
        $log = [];
        
        $res = $mysqli->query("SELECT activity_text atx, activity_type aty, UNIX_TIMESTAMP(stamp) ustamp FROM user_activity_log WHERE user_id={$user_id} "
        . "ORDER BY stamp DESC") or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
        while($activity = $res->fetch_assoc())
        {
            $log[] = ["text"=>$activity["atx"], "type"=>$activity["aty"], "time"=>Jobs::time_gap($activity["ustamp"])];
        }
        
        $res->close();
        
        return $log;
    }
    
    public static function load_tasks($user_id)
    {
        global $mysqli;
        
        $tasks = [];
        
        //my pending tasks
        $sql = "SELECT ((UNIX_TIMESTAMP(b.award_stamp)+j.duration)-UNIX_TIMESTAMP(CURRENT_TIMESTAMP)) due_in, b.award_stamp awst, b.job_id, j.title FROM jobs j "
                . "INNER JOIN job_bids b ON j.id=b.job_id WHERE b.user_id={$user_id} AND b.awarded=1 AND j.status<3 ";
                
        $res_jb = $mysqli->query($sql) or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
        while($bid = $res_jb->fetch_assoc())
        {
            $short_title = substr($bid["title"], 0, 30);
            strlen($bid["title"]) > 30 ? $short_title.="&hellip;":"";
            $job_link = "<a href='job.php?job={$bid["job_id"]}'>{$short_title}</a>";
            
            $tasks [] = ["type"=>"1", "stamp"=>$bid["awst"], "time"=>$bid["due_in"], "text"=>"Job {$job_link} is due ".self::due_in_gap($bid["due_in"])];
        }
        
        $res_jb->close();
        
        //accepting bids
        
        $sql = "SELECT DISTINCT b.job_id, COUNT(b.id) bid_num, j.title, UNIX_TIMESTAMP(b.stamp) ustamp FROM job_bids b INNER JOIN jobs j "
                . "ON j.id=b.job_id WHERE j.owner={$user_id} ORDER BY b.stamp DESC";
        
        $res_ab = $mysqli->query($sql) or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
        while($data_ab = $res_ab->fetch_assoc())
        {
            $short_title = substr($data_ab["title"], 0, 30);
            strlen($data_ab["title"]) > 30 ? $short_title.="&hellip;":"";
            $job_link = "<a href='job.php?job={$data_ab["job_id"]}'>{$short_title}</a>";
            
            $tasks[] = ["type"=>"2", "stamp"=>$data_ab["ustamp"], "text"=>"Job {$job_link} has received {$data_ab["bid_num"]} bids, waiting for your approval"];
        }
        
        $res_ab->close();
        
        // work inspection
        
        $sql = "SELECT u.names, j.id, j.title, UNIX_TIMESTAMP(j.created_on) ustamp FROM jobs j INNER JOIN job_bids b ON b.job_id=j.id "
                . "INNER JOIN users u ON u.id=b.user_id "
                . "WHERE j.status=3 AND j.owner={$user_id} AND b.awarded=1";
                
        $res_i = $mysqli->query($sql) or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
        while($insp = $res_i->fetch_assoc())
        {
            $short_title = substr($insp["title"], 0, 30);
            strlen($insp["title"]) > 30 ? $short_title.="&hellip;":"";
            $job_link = "<a href='job.php?job={$insp["id"]}'>{$short_title}</a>";
            
            $tasks[] = ["type"=>"3", "stamp"=>$insp["ustamp"], "text"=>"{$insp["names"]} has completed his work for {$job_link}, pending your confirmation"];
        }
        
        $res_i->close();
        
        $stamps = [];
        
        foreach($tasks as $i=>$v)
        {
            $stamps[$i] = $v["stamp"];
        }
        
        array_multisort($stamps, SORT_DESC, SORT_NUMERIC, $tasks);

        return $tasks;        
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
    
    public static function email_exists($email)
    {
        global $mysqli;
        $exists = FALSE;
        
        $res = $mysqli->query("SELECT id FROM users WHERE email='{$email}'") 
                  or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
        if($res->num_rows)
            $exists = TRUE;
        
        
        $res->close();
        
        return $exists;
    }
    
    public static function get_activity_types()
    {
        /*
         * we do not expect this function to ever be called
         * this is just show activity types plus descriptions
         */
        
        $types = ["1"=>"user registration",
                  "2"=>"posting job",
                  "3"=>"bid for job",
                  "4"=>"awarded bid",
                  "5"=>"received n bids for job",
                  "6"=>"received ksh n for job",
                  "7"=>"earned points x for job",
                  "8"=>"closed job",
                  "9"=>"reopened job",
                  "10"=>"deleted job"];
        
        return $types;
    }
    
    public static function get_me_jobs_bids_count($user_id)
    {
        global $mysqli;
        
        $sql = "SELECT COUNT(*) FROM jobs WHERE owner={$user_id} OR id "
        . "IN(SELECT job_id FROM used_job_tags WHERE tag_id IN(SELECT tag_id FROM user_skills WHERE user_id={$user_id})) "
        . "UNION ALL "
        . "SELECT COUNT(*) FROM job_bids WHERE job_id IN(SELECT id FROM jobs WHERE owner={$user_id} OR "
        . "id IN(SELECT job_id FROM used_job_tags WHERE tag_id IN(SELECT tag_id FROM user_skills WHERE user_id={$user_id})) OR "
        . "id IN(SELECT job_id FROM job_bids WHERE user_id={$user_id}))";
        
        $res_c = $mysqli->query($sql) or die($mysqli->error." ".__FILE__." line ".__LINE__);
        $data = $res_c->fetch_array();
        $data2 = $res_c->fetch_array();
        
        $res_c->close();
        
        return [$data[0], $data2[0]];
    }
    
    public static function get_me_trade($user_id)
    {
        global $mysqli;
        
        $sql = "SELECT IFNULL(SUM(amount_max),0) FROM jobs WHERE owner={$user_id} OR id "
        . "IN(SELECT job_id FROM used_job_tags WHERE tag_id IN(SELECT tag_id FROM user_skills WHERE user_id={$user_id}))";
        
        $res_c = $mysqli->query($sql) or die($mysqli->error." ".__FILE__." line ".__LINE__);
        $data = $res_c->fetch_array();
        
        $res_c->close();
        
        return $data[0];              
    }
    
    public static function custom_number_format($number)
    {
        $num = intval($number);
        
        $f=$num;
        
        if($num >= pow(10, 4)) //im too lazy
        {
            $f = round($num/1000, 1);
            $f = $f."K";
        }
        
        if($num >= pow(10, 6))
        {
            $f = round($num/pow(10, 6), 2);
            $f = $f."M";
        }
        
        if($num >= pow(10, 9))
        {
            $f = round($num/pow(10, 9), 3);
            $f = $f."B";
        }
        
        return $f;
    }
        
    public static function get_trade_chart($user_id)
    {
        global $mysqli;
        
        //work x-axis first
        
        $x_axis = [];
        
        $res_1st = $mysqli->query("SELECT UNIX_TIMESTAMP(stamp) ustamp, MAX(balance) max_bal FROM user_trade_log WHERE user_id={$user_id} LIMIT 1") or die($mysqli->error." ".__FILE__." line ".__LINE__);
        $data_1st = $res_1st->fetch_assoc();
        $res_1st->close();
        
        $stmt_f = $mysqli->prepare("SELECT max(balance) max_bal FROM user_trade_log WHERE user_id={$user_id} "
        . "AND UNIX_TIMESTAMP(stamp) BETWEEN ? AND ?");
        
        $diff = time() - $data_1st["ustamp"];
        
        if($diff <= 3600 * 168) //less than a week
        {
            $x_axis["len"] = 1;
            $x_axis["type"] = 1; //one-off
            $x_axis["x"][] = ["week1"=>$data_1st["max_bal"]];
        }
        else if($diff <= 3600 * 24 * 62) //less than 2 months
        {
            $x_axis["len"] = floor($diff / (3600 * 168));
            $x_axis["type"] = 2; //weekly
            
            for($i=1; $i<=$x_axis["len"]; ++$i)
            {
                $lb = (($i-1) * 7 * 3600 * 24) + $data_1st["ustamp"];
                $hb = ($i * 7 * 3600 * 24) + $data_1st["ustamp"];
                
                $stmt_f->bind_param("ii", $lb, $hb);
                $stmt_f->execute() or die($mysqli->error." ".__FILE__." line ".__LINE__);
                $res_w = $stmt_f->get_result();
                $data_w = $res_w->fetch_assoc();
                
                $res_w->close();
                
                $x_axis["x"][] = ["week{$i}"=>$data_w["max_bal"]];
            }
        }
        else if($diff <= 3600 * 24 * 365)
        {
            $x_axis["len"] = floor($diff / (3600 * 24 * 31));
            $x_axis["type"] = 3; //monthly
            
            $last_lb_stamp=$data_1st["ustamp"];
            
            for($i=1; $i<=$x_axis["len"]; ++$i)
            {
                $lb = (($i-1) * 3600 * 24 * date("t", $last_lb_stamp)) + $data_1st["ustamp"];
                $hb = ($i * 3600 * 24 * date("t", $last_lb_stamp)) + $data_1st["ustamp"];
                
                $last_lb_stamp = $hb+1; //go to the next day
                
                $stmt_f->bind_param("ii", $lb, $hb);
                $stmt_f->execute() or die($mysqli->error." ".__FILE__." line ".__LINE__);
                $res_w = $stmt_f->get_result();
                $data_w = $res_w->fetch_assoc();
                
                $res_w->close();
                
                $x_axis["x"][] = [date("M", $lb)=>$data_w["max_bal"]];
            }
            
        }
        else
        {
            $jan1 = mktime(0, 0, 0, 1, 1, date("Y"));
            
            $x_axis["len"] = 12;
            $x_axis["type"] = 4; //year round
            
            $last_lb_stamp=$jan1;
            
            for($i=1; $i<=$x_axis["len"]; ++$i)
            {
                $lb = (($i-1) * 3600 * 24 * date("t", $last_lb_stamp)) + $jan1;
                $hb = ($i * 3600 * 24 * date("t", $last_lb_stamp)) + $jan1;
                
                $last_lb_stamp = $hb+1; //go to the next day
                
                $stmt_f->bind_param("ii", $lb, $hb);
                $stmt_f->execute() or die($mysqli->error." ".__FILE__." line ".__LINE__);
                $res_w = $stmt_f->get_result();
                $data_w = $res_w->fetch_assoc();
                
                $res_w->close();
                
                $x_axis["x"][] = [date("M", $lb)=>$data_w["max_bal"]];
            }
            
            
        }
        
        $stmt_f->close();
        
        //now work on y-axis
        
        $y_axis = [];
        $y_axis[] = 0; //y-axis always starts with 0
        
        $y_max = (100/85) * $data_1st["max_bal"];
        $r = round(($y_max/5));
        
        for($i=1; $i<=5; ++$i)
        {
            $y_axis[] = $i * $r;
        }
        
        return ["x"=>$x_axis, "y"=>$y_axis];
    }
    
    public static function load_chats($user_id)
    {
        global $mysqli;
        
        $chat_list = [];
        $threads = [];
        $pal_unread = [];
        
        $unread = 0;
                
        
        $res = $mysqli->query("SELECT c.*, UNIX_TIMESTAMP(c.stamp) ustamp  FROM chats c WHERE sender={$user_id} OR recep={$user_id} ORDER BY stamp DESC")
                    or die($mysqli->error." ".__FILE__." line ".__LINE__);        
        
        while($data = $res->fetch_assoc())
        {
            if(in_array($data["sender"], $chat_list) || in_array($data["recep"], $chat_list))
                continue;
                                    
            $pal = 0;
                
            if($data["sender"] == $user_id)
                $pal = $data["recep"];
            else
                $pal = $data["sender"];
            
            $chat_list[] = $pal;
            
            if(!isset($pal_unread[$pal]))
                $pal_unread[$pal] = 0;
            
            if($data["read_flag"] == 0 && $data["recep"] == $user_id)
            {
                $unread++;
                $pal_unread[$pal]++;
            }
            
            $threads[$pal][] = ["usr"=>self::user_data($data["sender"]), "msg"=>$data["msg"], "read"=>$data["read_flag"],
                                "time"=>self::time_gap($data["ustamp"]), "stamp"=>$data["ustamp"]];
            
        }
        
        $res->close();
        
        //now sort the conv in each thread by time asc
        
        foreach($threads as $pal=>$conv)
        {
            $stamps = [];
            foreach($conv as $t)
            {
                $stamps[] = $t["stamp"];
            }
            
            array_multisort($stamps, SORT_ASC, SORT_NUMERIC, $conv);
            
            $threads[$pal] = $conv;
        }
        
                        
        return ["threads"=>$threads, "pal_unread"=>$pal_unread, "total_unread"=>$unread];
    }
    
    public static function delete_chat($p1, $p2)
    {
        global $mysqli;
        
        $mysqli->query("DELETE FROM chats WHERE (sender={$p1} AND recep={$p2}) OR (sender={$p2} AND recep={$p1})")
                or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
        return "ok";        
    }
    
    public static function send_chat_msg($p1, $p2, $msg)
    {
        global $mysqli;
        
        $p1 = Security::clean_data($p1);
        $p2 = Security::clean_data($p2);
        $msg = Security::clean_data($msg);
        
        if(!Security::check_empty([$p1, $p2, $msg]))
            return "Can't send empty message";
        
        $mysqli->query("INSERT INTO chats(sender, recep, msg) VALUES ({$p1}, {$p2}, '{$msg}')")
                or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
        return "ok";        
    }
    
    public static function due_in_gap($stamp)
    {
        $now = time();
        
        if($stamp <= (3600 * 24))
            return "today";
        else if($stamp <= (3600 * 48))
            return "tomorrow";
        else if($stamp <= 3600 * 24 * 7)
            return "on ".date("L", $now + $stamp);
        else if($stamp <= 3600 * 24 * 365)
            return "on ".date("jS M ", $now + $stamp);
        else
            return "on ".date("jS M Y", $now + $stamp);        
    }
    
    public static function time_gap($stamp)
    {
        $gap = "";
        $now = date("U");
        
        if($now - $stamp < 3600)
            $gap = floor(($now - $stamp)/60)."m";
        else if($now - $stamp < 3600 * 24)
            $gap = floor(($now - $stamp)/3600)."h";
        else if($now - $stamp < 3600 * 48)
            $gap = "1d ago";
        else if($now - $stamp < 3600 * 24 * 7)
            $gap = floor(($now - $stamp) / (3600 * 24))."d";
        else
            $gap = date("jS M", $stamp);
        
        return $gap;        
    }
    
    
    
}

