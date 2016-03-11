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
    
    public static function loadJobs($page=1, $search="",$cat="", $tags="", $amount="")
    {
        global $mysqli;        
               
        $sql = "SELECT j.title, j.description, j.deadline, FORMAT(j.amount_min, 0) amount_1, FORMAT(j.amount_max, 0) amount_2, jc.name category "
                . "FROM jobs j INNER JOIN job_categories jc ON j.category=jc.id ";
        
        if(!empty($search) || !empty($cat) || !empty($tags) || !empty($amount))
        {
            $AND = FALSE;
            if(!empty($search))
            {
                $sql.= " (title LIKE '%{$search}%' OR title LIKE '%{$search}%') ";
                $AND = TRUE;
            }
            
            if(!empty($cat))
            {
                if($AND) $sql.=" AND ";
                $sql .= " category={$cat} ";
                $AND = TRUE;
            }
            
            if(!empty($amount))
            {
                if($AND) $sql.=" AND ";
                $sql.=" (amount_min<={$amount} AND amount_max>={$amount}) ";
                $AND = TRUE;
            }
            
            if(!empty($tags))
            {
                //tags are comma separated
                if($AND) $sql.=" AND ";
                $sql.=" id IN(SELECT job_id FROM used_job_tags WHERE tag_id IN({$tags})) ";
            }
            
        }        
                
        $jobs = [];
        
        $res_j = $mysqli->query($sql) or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
        $total_count = $res_j->num_rows;
        $total_pages = $total_count / 12;
        
        while($row = $res_j->fetch_assoc())
        {
            $min_desc = substr($row["description"], 0, 200);
            if(strlen($row["description"]) > 200)
                $min_desc.="&hellip;<a href='javascript:;' vec='".$row["id"]."'>See more</a>";
            
            $jobs[$row["id"]] = ["title"=>$row["title"], "category"=>$row["category"], "due_by"=>date("jS M Y", $row["deadline"]),
                "min_desc"=>$min_desc, "max_desc"=>$row["description"], "budget"=>"{$row["amount_1"]}-{$row["amount_2"]}"];
        }
        $res_j->close();
        
        $tag_stmt = $mysqli->prepare("SELECT name FROM job_tags WHERE id IN(SELECT tag_id FROM used_job_tags WHERE job_id=?)");
        
        foreach(array_keys($jobs) as $job_id)
        {
            $tag_stmt->bind_param("i", $job_id);
            $tag_stmt->execute() or die($mysqli->error." ".__FILE__." line ".__LINE__);
            $res_tn = $tag_stmt->get_result();
            $tag_ns=[];
            
            while($tag_inf = $res_tn->fetch_assoc())
            {
                $tag_ns[] = $tag_inf["name"];
            }
            
            $tag_ns = implode(", ", $tag_ns);
            $jobs[$job_id]["tags"] = $tag_ns;
            
            $res_tn->close();            
        }
        
        $tag_stmt->close();        
        
        $highest = 0;
        $lowest = 0;
        
        
        if($page <= 4)
        {            
            $highest = $total_pages > 7 ? 7:$total_pages;
            $lowest = 1;            
        }
        else
        {
            $highest = $page+3;
            $lowest = $page-3;            
        }
        
        $pagination_nav = '<nav><ul class="pagination">';
        
        $first = "";
        $laquo = "";
        $last = "";
        $raquo = "";
        if($lowest == $page)
        {
            $first = '<li class="disabled"><a href="javascript:;">First</a></li>';
            $laquo = '<li class="disabled"><a href="javascript:;" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>';
        }            
        else
        {
            $first = '<li><a href="javascript:;" dx="'.$lowest.'">First</a></li>';
            $laquo = '<li><a href="javascript:;" dx="'.($page-1).'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>';
        }
        
        $pagination_nav.= $first;
        $pagination_nav.=$laquo;     
        
        for($i=$lowest; $i<=$highest; ++$i)
        {
            if($i == $page)
                $pagination_nav.='<li class="active"><a href="javascript:;">'.$i.'</a></li>';
            else
                $pagination_nav.='<li><a href="javascript:;" dx="'.$i.'">'.$i.'</a></li>';
        }
        
        if($highest == $page)
        {
            $last = '<li class="disabled"><a href="javascript:;">Last</a></li>';
            $raquo = '<li class="disabled"><a href="javascript:;" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
        }            
        else
        {
            $last = '<li><a href="javascript:;" dx="'.$highest.'">Last</a></li>';
            $raquo = '<li class="disabled"><a href="javascript:;" aria-label="Next" dx="'.($page+1).'"><span aria-hidden="true">&raquo;</span></a></li>';
        }
        
        $pagination_nav.=$raquo;
        $pagination_nav.=$last; 
        
        $html = "";
        
        foreach(array_slice($jobs, ($page-1)*12, 12) as $id=>$job)
        {
            $html.="<tr><td style='width: 18%;'><a href='jobs.php?id=".$id."'>{$job["title"]}</a></td><td style='width: 30%;'>{$job["min_desc"]}</td><td style='width: 15%;'>{$job["category"]}</td><td style='width: 13%;'>{$job["tags"]}</td><td style='width: 11%;'>{$job["due_by"]}</td><td style='width: 20%;'>{$job["budget"]}</td></tr>";
        }
        
        return ["pagination"=>$pagination_nav, "jobs"=>$html];        
    }
    
    public static function loadDocket($user_id)
    {
        global $mysqli;
        
        $docket = [];
        
        $res = $mysqli->query("SELECT id, title, owner FROM jobs WHERE owner={$user_id} OR id IN"
        . "(SELECT job_id FROM job_bids WHERE user_id={$user_id} AND awarded=1) ORDER BY created_on DESC")
             or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
        while($row = $res->fetch_assoc())
        {
            $title = strlen($row["title"]) > 25 ? substr($row["title"], 0, 25)."&hellip;":$row["title"];
            
            if($row["owner"] == $user_id)
            {
                $docket["docket"][] = ["id"=>$row["id"], "title"=>$title];
            }
            else
            {
                $docket["todo"][] = ["id"=>$row["id"], "title"=>$title];
            }
        }
        
        $res->close();
        
        return $docket;
    }
    
}