<?php

require_once 'connid.php';

class Jobs
{
    public static function upload_job_attachment($file)
    {
        $_exts = "csv,xls,xlsx,txt,png,jpg,gif,bmp,avi,mpg,mpeg,mp4,mp3,wav,flv,pdf,doc,docx,ppt,pptx,psd,pub,7z,ppd,bz,bz2,ico,jar,phar,tex,latex,wma,wmx,wmv,mpga,mp4a,oda,oxt,ogx,oga,ogv,odb,rar,tar,xz,zip,gtar,tiff";
        $valid_exts = explode(",", $_exts);
        
        $ext = pathinfo($file["name"], PATHINFO_EXTENSION);
        
        if(!in_array($ext, $valid_exts))
        {
            return "E:Files of that type not allowed";
        }
        
        if(isset($_SESSION["tmp_jobfiles"][$file["name"]]))
        {
            return "E:You have already uploaded that file";
        }
        
        $fname = pathinfo($file["name"], PATHINFO_FILENAME);
        $new_basename = $fname."_".uniqid(time()).".".$ext;
        $new_tmp_path = __DIR__."/../usr_files/tmp/" .$new_basename;
        
        move_uploaded_file($file["tmp_name"], $new_tmp_path);
        $_SESSION["tmp_jobfiles"][$file["name"]] = $new_basename;
        
        $ret = [];
        $ret[] = $file["name"];
        
        return json_encode($ret);
    }
    
    public static function delete_uploaded_job_file($name)
    { 
        $name = str_replace("..", ".", $name);//get rid of parent folders if any       
        $tmp_path = $_SESSION["tmp_jobfiles"][$name];
        unlink(__DIR__."/../usr_files/tmp/".$tmp_path);
        unset($_SESSION["tmp_jobfiles"][$name]);
    }
    
    public static function cleanup_tmp()
    {
        foreach($_SESSION["tmp_jobfiles"] as $tmp_file)
        {
            unlink(__DIR__."/../usr_files/tmp/".$tmp_file);
        }
        
        unset($_SESSION["tmp_jobfiles"]);
    }
    
    public static function newJob($title, $desc, $category, $tags, $deadline, $min_budget, $max_budget)
    {
        global $mysqli;
        
        $title = Security::clean_data($title);
        $desc = Security::clean_data($desc);
        $category = Security::clean_data($category);
        $tags = Security::clean_data($tags);
        $deadline = Security::clean_data($deadline);
        $min_budget = Security::clean_data($min_budget);
        $max_budget = Security::clean_data($max_budget);
        
        if(Security::check_empty([$title, $desc, $category, $tags, $deadline, $min_budget, $max_budget]))
        {
            return "Please fill all fields. Only attachments are optional";
        }
        
        $owner = $_SESSION["sess_id"];
        
        $_dd = explode("/", $deadline);
        $deadline_stamp = mktime(0, 0, 0, $_dd[1], $_dd[0], $_dd[2]);
        
        if($deadline_stamp <= time())
        {
            return "Deadline date must be a future date";
        }
        
        $mysqli->query("INSERT INTO jobs (title, description, category, deadline, amount_min, amount_max, owner) "
                . "VALUES ('{$title}', '{$desc}', {$category}, {$deadline_stamp}, {$min_budget}, {$max_budget}, {$owner})") 
                           or die($mysqli->error." ".__FILE__." line ".__LINE__);
                
        $job_id = $mysqli->insert_id;
                
        $_tags = explode(",", $tags);
        
        $sql = "INSERT INTO used_job_tags (tag_id, job_id) VALUES ";
        
        foreach($_tags as $i=>$tag)
        {
            $sql.=" ({$tag}, {$job_id})";
            if($i < count($_tags)-1)
                $sql.=",";
            else
                $sql.=";";
        }
        
        $mysqli->query($sql) or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
        //check for uploads
        $sql = "INSERT INTO job_attachments(job_id, file_path) VALUES ";
        $count = 0;
        
        foreach($_SESSION["tmp_jobfiles"] as $key=>$tmp_name)
        {
            $count++;
            $new_path = __DIR__."/../usr_files/".$tmp_name;
            $tmp_dir = __DIR__."/../usr_files/tmp/";
            
            rename($tmp_dir.$tmp_name, $new_path);
                        
            unset($_SESSION["tmp_jobfiles"][$key]);
            
            $sql.="({$job_id}, '{$new_path}')";
            
            if($count < count($_SESSION["tmp_jobfiles"])-1)
                $sql.=",";
            else
                $sql.=";";            
        }
        
        $mysqli->query($sql) or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
        unset($_SESSION["tmp_jobfiles"]);
        
        return "ok";        
    }
    
    
}