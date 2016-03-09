<?php

require_once 'connid.php';
require_once 'security.php';

class Data
{
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
    
            
}

