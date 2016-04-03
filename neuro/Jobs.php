<?php

require_once 'connid.php';
require_once 'security.php';

class Jobs
{
    private static $valid_file_exts = "csv,xls,xlsx,txt,png,jpg,gif,bmp,avi,mpg,mpeg,mp4,mp3,wav,flv,pdf,doc,docx,ppt,pptx,psd,pub,7z,ppd,bz,bz2,ico,jar,phar,tex,latex,wma,wmx,wmv,mpga,mp4a,oda,oxt,ogx,oga,ogv,odb,rar,tar,xz,zip,gtar,tiff";

    public static function upload_job_attachment($file)
    {
        if (!self::check_file_upload($file))
        {
            return "E:Files of that type not allowed";
        }

        if (isset($_SESSION["tmp_jobfiles"][$file["name"]]))
        {
            return "E:You have already uploaded that file";
        }

        $fname = pathinfo($file["name"], PATHINFO_FILENAME);
        $ext = pathinfo($file["name"], PATHINFO_EXTENSION);
        $new_basename = $fname . "_" . uniqid(time()) . "." . $ext;
        $new_tmp_path = __DIR__ . "/../usr_files/tmp/" . $new_basename;

        move_uploaded_file($file["tmp_name"], $new_tmp_path);
        $_SESSION["tmp_jobfiles"][$file["name"]] = $new_basename;

        $ret = [];
        $ret[] = $file["name"];

        return json_encode($ret);
    }

    public static function upload_jobsb_files($file)
    {
        if (!self::check_file_upload($file))
        {
            return "E:Files of that type not allowed";
        }

        if (isset($_SESSION["tmp_jobsb_files"][$file["name"]]))
        {
            return "E:You have already uploaded that file";
        }

        $fname = pathinfo($file["name"], PATHINFO_FILENAME);
        $ext = pathinfo($file["name"], PATHINFO_EXTENSION);
        $new_basename = $fname . "_" . uniqid(time()) . "." . $ext;
        $new_tmp_path = __DIR__ . "/../usr_files/tmp/" . $new_basename;

        move_uploaded_file($file["tmp_name"], $new_tmp_path);
        $_SESSION["tmp_jobsb_files"][$file["name"]] = $new_basename;

        $ret = [];
        $ret[] = $file["name"];

        return json_encode($ret);
    }

    private static function check_file_upload($file)
    {
        $valid_exts = explode(",", self::$valid_file_exts);

        $ext = pathinfo($file["name"], PATHINFO_EXTENSION);

        if (!in_array($ext, $valid_exts))
        {
            return FALSE;
        }

        return TRUE;
    }

    public static function delete_uploaded_job_file($name)
    {
        $name = str_replace("..", ".", $name); //get rid of parent folders if any       
        $tmp_path = $_SESSION["tmp_jobfiles"][$name];
        unlink(__DIR__ . "/../usr_files/tmp/" . $tmp_path);
        unset($_SESSION["tmp_jobfiles"][$name]);
    }

    public static function delete_uploaded_jobsb($name)
    {
        $name = str_replace("..", ".", $name); //get rid of parent folders if any       
        $tmp_path = $_SESSION["tmp_jobsb_files"][$name];
        unlink(__DIR__ . "/../usr_files/tmp/" . $tmp_path);
        unset($_SESSION["tmp_jobsb_files"][$name]);
    }

    public static function cleanup_tmp()
    {
        if (isset($_SESSION["tmp_jobfiles"]))
        {
            foreach ($_SESSION["tmp_jobfiles"] as $tmp_file)
            {
                unlink(__DIR__ . "/../usr_files/tmp/" . $tmp_file);
            }

            unset($_SESSION["tmp_jobfiles"]);
        }

        if (isset($_SESSION["tmp_jobsb_files"]))
        {
            foreach ($_SESSION["tmp_jobsb_files"] as $tmp_file)
            {
                unlink(__DIR__ . "/../usr_files/tmp/" . $tmp_file);
            }

            unset($_SESSION["tmp_jobsb_files"]);
        }
    }

    private static function move_job_files($job_id, $type=1) //type = 1 job attached files, 2 = job sb files
    {
        global $mysqli;
        $table_name = "";
        $obj_array = NULL;
        
        if($type == 1)
        {
            $table_name = "job_attachments";
            $obj_array = isset($_SESSION["tmp_jobfiles"]) ? $_SESSION["tmp_jobfiles"]:NULL;
        }
        else
        {
            $table_name = "jobsb_files";
            $obj_array = isset($_SESSION["tmp_jobsb_files"])?$_SESSION["tmp_jobsb_files"]:NULL;
        }
        
        if (isset($obj_array))
        {
            $sql = "INSERT INTO {$table_name}(job_id, file_path) VALUES ";

            $count = 0;
            foreach ($obj_array as $tmp_name)
            {
                $count++;
                $new_path = __DIR__ . "/../usr_files/" . $tmp_name;
                $saved_path = "usr_files/" . $tmp_name;
                $tmp_dir = __DIR__ . "/../usr_files/tmp/";

                rename($tmp_dir . $tmp_name, $new_path);
                
                $sql.="({$job_id}, '{$saved_path}')";

                if ($count < count($obj_array))
                    $sql.=",";
                else
                    $sql.=";";
                
            }            
                        
            $mysqli->query($sql) or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);

            if($type == 1)            
                unset($_SESSION["tmp_jobfiles"]);
            
            else
                unset($_SESSION["tmp_jobsb_files"]);
        }
    }

    private static function auth_job($job_id, $user_id)
    {
        global $mysqli;

        $auth = FALSE;

        $res_c = $mysqli->query("SELECT id FROM jobs WHERE id={$job_id} AND owner={$user_id}")
                or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);

        if ($res_c->num_rows)
        {
            $auth = TRUE;
        }
        $res_c->close();

        return $auth;
    }
    
    private static function auth_bid_award($job_id, $user_id)
    {
        global $mysqli;

        $auth = FALSE;

        $res_c = $mysqli->query("SELECT id FROM job_bids WHERE job_id={$job_id} AND user_id={$user_id} AND awarded=1")
                or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);

        if ($res_c->num_rows)
        {
            $auth = TRUE;
        }
        $res_c->close();

        return $auth;
    }
    
    public static function job_exists($job_id)
    {
        global $mysqli;
        
        $res = $mysqli->query("SELECT id FROM jobs WHERE id={$job_id}") 
                    or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
        $found = 0;
        if($res->num_rows)
            $found = 1;
        
        $res->close();
        
        if(!$found)
            header("Location:home.php");        
    }
    
    public static function newJob($title, $desc, $category, $tags, $duration, $workers, $payment_model, $min_budget, $max_budget, $hpw)
    {
        global $mysqli;

        $title = Security::clean_data($title);
        $desc = Security::clean_data($desc);
        $category = Security::clean_data($category);
        $workers = Security::clean_data($workers);
        $payment_model = Security::clean_data($payment_model);
        $tags = Security::clean_data($tags);
        $duration = Security::clean_data($duration);
        $min_budget = Security::clean_data($min_budget);
        $max_budget = Security::clean_data($max_budget);
        $hpw = Security::clean_data($hpw);

        if (!Security::check_empty([$title, $desc, $category, $tags, $duration, $workers, $payment_model, $min_budget, $max_budget, $hpw]))
        {
            return "Please fill all fields. Only attachments are optional";
        }
        
        if(!in_array($payment_model, ["fixed", "hourly"]))
        {
            return "Please set the payment model correctly";
        }
        
        if($payment_model == "hourly" && !intval($hpw))
        {
            return "Reset the hours per week";
        }
        
        if($max_budget <= $min_budget)
        {
            return "Budget set incorrectly. Check again";
        }
        
        if(!in_array($duration, [3,7,14,21,28,35,42,61,92,122,153,183,214,244,275,365,548]))
        {
            return "Reset the work duration correctly";
        }

        $owner = $_SESSION["sess_id"];
        
        if($duration == 3)
            $hpw *= 3;

        $duration *= (3600 * 24);
        
        $job_type = $payment_model == "fixed"?1:2;
        
        $sql = "INSERT INTO jobs (title, description, category, duration, job_type, hours_a_week, workers_wanted,"
                . "amount_min, amount_max, owner) "
                        . "VALUES ('{$title}', '{$desc}', {$category}, {$duration},{$job_type}, {$hpw}, {$workers}, "
                        . "{$min_budget}, {$max_budget}, {$owner})";
                        
        $mysqli->query($sql)
                or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);

        $job_id = $mysqli->insert_id;

        $_tags = explode(",", $tags);

        $sql = "INSERT INTO used_job_tags (tag_id, job_id) VALUES ";

        foreach ($_tags as $i => $tag)
        {
            $sql.=" ({$tag}, {$job_id})";
            if ($i < count($_tags) - 1)
                $sql.=",";
            else
                $sql.=";";
        }

        $mysqli->query($sql) or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);

        self::move_job_files($job_id);

        return "ok";
    }

    public static function loadJobs($page = 1, $search = "", $cat = "", $tags = "", $amount = "")
    {
        global $mysqli;

        $sql = "SELECT j.id, j.title, j.description, j.duration, FORMAT(j.amount_min, 0) amount_1, FORMAT(j.amount_max, 0) amount_2, jc.name category, j.job_type, j.hours_a_week, "
                . "UNIX_TIMESTAMP(j.created_on) created_on,(j.workers_wanted - (SELECT IFNULL(SUM(id),0) FROM job_bids WHERE job_id=j.id AND awarded=1)) spaces_left, "
                . "(SELECT IFNULL(SUM(id),0) FROM job_bids WHERE job_id=j.id)bids FROM jobs j INNER JOIN job_subcategories jc ON j.category=jc.id WHERE j.status=1 ";

        if (!empty($search) || !empty($cat) || !empty($tags) || !empty($amount))
        {            
            if (!empty($search))
            {
                $sql.= " AND (j.title LIKE '%{$search}%' OR j.description LIKE '%{$search}%') ";                
            }

            if (!empty($cat))
            {
                $sql .= " AND category={$cat} ";                
            }

            if (!empty($amount))
            {
                $sql.=" AND (j.amount_min<={$amount} AND j.amount_max>={$amount}) ";                
            }

            if (!empty($tags))
            {
                //tags are comma separated
                $sql.=" AND j.id IN(SELECT job_id FROM used_job_tags WHERE tag_id IN({$tags})) ";
            }
        }
        
        $sql.=" ORDER BY j.created_on DESC ";
        
                
        $jobs = [];
        $job_cats = [];

        $res_j = $mysqli->query($sql) or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);

        $total_count = $res_j->num_rows;
        $total_pages = $total_count / 12;
        
        while ($row = $res_j->fetch_assoc())
        {
            $min_desc = substr($row["description"], 0, 200);
            if (strlen($row["description"]) > 200)
                $min_desc.="&hellip;<a href='javascript:;' vec='" . $row["id"] . "'>See more</a>";
            
            $duration = $row["duration"]/(3600*24);
            $hours_week = "";
            
                        
            if($duration == 3)
            {
                if($row["job_type"] == 2)
                    $hours_week = ($row["hours_a_week"]/3)."hrs/d";                  
                
                $duration .=" days";
            }                
            if($duration >= 7 && $duration < 62)
            {
                $duration = ($duration/7)." Weeks";
                if($row["job_type"] == 2) $hours_week = ($row["hours_a_week"])."hrs/w";
            }                
            else if($duration >= 62)
            {
                $duration = floor($duration/30)." Months";
                if($row["job_type"] == 2) $hours_week = ($row["hours_a_week"])."hrs/w";
            }
            
            $budget = $row["job_type"] == 1 ? "{$row["amount_1"]} - {$row["amount_2"]}":"{$row["amount_1"]} - {$row["amount_2"]}/hr";

            $jobs[$row["id"]] = ["id"=>$row["id"], "title" => $row["title"], "category" => $row["category"], "hours_week"=>$hours_week, "duration" => $duration,
                "bids"=>$row["bids"], "spaces_left"=>$row["spaces_left"], "min_desc" => $min_desc, "max_desc" => $row["description"], "budget" => $budget,
                "time"=>self::time_gap($row["created_on"])];
            
            $job_cats[$row["id"]] = $row["category"];
            
        }
        $res_j->close();

        $tag_stmt = $mysqli->prepare("SELECT name FROM job_tags WHERE id IN(SELECT tag_id FROM used_job_tags WHERE job_id=?)");

        foreach (array_keys($jobs) as $job_id)
        {
            $tag_stmt->bind_param("i", $job_id);
            $tag_stmt->execute() or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);
            $res_tn = $tag_stmt->get_result();
            $tag_ns = [];

            while ($tag_inf = $res_tn->fetch_assoc())
            {
                $tag_ns[] = $tag_inf["name"];
            }

            //$tag_ns = implode(", ", $tag_ns);
            $jobs[$job_id]["tags"] = $tag_ns;

            $res_tn->close();
        } 
        
        $tag_stmt->close();

        $highest = 0;
        $lowest = 0;
        
        
        if ($page <= 4)
        {
            $highest = $total_pages > 7 ? 7 : $total_pages;
            $lowest = 1;
        } else
        {
            $highest = $page + 3;
            $lowest = $page - 3;
        }        
        
        $pagination_nav = '<nav><ul class="pagination">';

        $first = "";
        $laquo = "";
        $last = "";
        $raquo = "";
        if ($lowest == $page)
        {
            $first = '<li class="disabled"><a href="javascript:;">First</a></li>';
            $laquo = '<li class="disabled"><a href="javascript:;" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>';
        } else
        {
            $first = '<li><a href="javascript:;" dx="' . $lowest . '">First</a></li>';
            $laquo = '<li><a href="javascript:;" dx="' . ($page - 1) . '" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>';
        }

        $pagination_nav.= $first;
        $pagination_nav.=$laquo;

        for ($i = $lowest; $i <= $highest; ++$i)
        {
            if ($i == $page)
                $pagination_nav.='<li class="active"><a href="javascript:;">'.$i.'</a></li>';
            else
                $pagination_nav.='<li><a href="javascript:;" dx="'.$i.'">' . $i . '</a></li>';
        }

        if ($highest - $page <= 4)
        {
            $last = '<li class="disabled"><a href="javascript:;">Last</a></li>';
            $raquo = '<li class="disabled"><a href="javascript:;" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
        } else
        {
            $last = '<li><a href="javascript:;" dx="' . $highest . '">Last</a></li>';
            $raquo = '<li><a href="javascript:;" aria-label="Next" dx="' . ($page + 1) . '"><span aria-hidden="true">&raquo;</span></a></li>';
        }

        $pagination_nav.=$raquo;
        $pagination_nav.=$last;

        $html = "";        
        
        foreach (array_slice($jobs, ($page - 1) * 12, 12) as $job)
        {
            $html .= '<div class="row">
                      <div class="col-md-10">
                        <a href="viewjob.php?job='.$job["id"].'" class="job-main-link text-info">'.$job["title"].'</a>
                    </div>
                    <div class="col-md-2 right"><small><i class="fa fa-clock-o"></i> '.$job["time"].'</small></div>
                    <div class="col-md-4"><br>'.$job["hours_week"].' KSh '.$job["budget"].'</div>
                    <div class="col-md-3"><br>Duration: '.$job["duration"].'</div>
                    <div class="col-md-3"><br><span class="fa fa-fw fa-users"></span> '.$job["spaces_left"].' spaces left</div>
                    <div class="col-md-2 right"><br><span class="badge badge-primary">'.$job["bids"].' bids</span></div>
                    <div class="col-md-12"><br>
                        <span class="min_job_desc" vec="'.$job["id"].'">
                        '.$job["min_desc"].'
                        </span>
                        <span class="max_job_desc" vec="'.$job["id"].'">
                        '.$job["max_desc"].'
                        </span>
                      </div>
                    <div class="col-md-12"><br>';                        
                        foreach($job["tags"] as $t)
                        {
                            $html.= '<span class="label label-primary">'.$t.'</span> ';
                        }
                    $html.= '</div>
                    <div class="col-md-12"><hr></div>
                  </div>';
            
         }
        
        if(strlen($html) == 0)
            $html = "<tr><td>No jobs found</td></tr>";      
        

        return ["pagination" => $pagination_nav, "jobs" => $html, "job_cats"=> array_count_values($job_cats)];
    }

    public static function loadDocket($user_id)
    {
        global $mysqli;

        $docket = [];
        $docket["docket"] = [];
        $docket["todo"] = [];

        $res = $mysqli->query("SELECT id, title, owner FROM jobs WHERE owner={$user_id} OR id IN"
                . "(SELECT job_id FROM job_bids WHERE user_id={$user_id} AND awarded=1) ORDER BY created_on DESC")
                or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);

        while ($row = $res->fetch_assoc())
        {
            $title = strlen($row["title"]) > 25 ? substr($row["title"], 0, 25) . "&hellip;" : $row["title"];

            if ($row["owner"] == $user_id)
            {
                $docket["docket"][] = ["id" => $row["id"], "title" => $title];
            } else
            {
                $docket["todo"][] = ["id" => $row["id"], "title" => $title];
            }
        }

        $res->close();

        return $docket;
    }

    public static function updateJob($title, $desc, $category, $tags, $duration, $workers, $payment_model, $min_budget, $max_budget, $hpw)
    {
        global $mysqli;

        $title = Security::clean_data($title);
        $desc = Security::clean_data($desc);
        $category = Security::clean_data($category);
        $workers = Security::clean_data($workers);
        $payment_model = Security::clean_data($payment_model);
        $tags = Security::clean_data($tags);
        $duration = Security::clean_data($duration);
        $min_budget = Security::clean_data($min_budget);
        $max_budget = Security::clean_data($max_budget);
        $hpw = Security::clean_data($hpw);
        
        $job_id = $_SESSION["job_id"];
        
        //check for accepted bid

        $res_b = $mysqli->query("SELECT id FROM job_bids WHERE job_id={$job_id} AND awarded=1 "
                . "AND job_id IN(SELECT id FROM jobs WHERE status<4 AND deadline>=UNIX_TIMESTAMP(CURRENT_TIMESTAMP))") 
                or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);

        if ($res_b->num_rows)
        {
            $res_b->close();
            return "You have accepted bids. People are already working on this!";
        }
        $res_b->close();

        if (!Security::check_empty([$title, $desc, $category, $tags, $duration, $workers, $payment_model, $min_budget, $max_budget]))
        {
            return "Please fill all fields. Only attachments are optional";
        }
        
        if (!self::auth_job($job_id, $_SESSION["sess_id"]))
        {
            return "Only the owner can do this operation";
        }

        if(!in_array($payment_model, ["fixed", "hourly"]))
        {
            return "Please set the payment model correctly";
        }
        
        if($payment_model == "hourly" && !intval($hpw))
        {
            return "Reset the hours per week";
        }
        
        if($max_budget <= $min_budget)
        {
            return "Budget set incorrectly. Check again";
        }
        
        if(!in_array($duration, [3,7,14,21,28,35,42,61,92,122,153,183,214,244,275,365,548]))
        {
            return "Reset the work duration correctly";
        }
        
        $duration *= (3600 * 24);
        
        $job_type = $payment_model == "fixed"?1:2;

        $mysqli->query("UPDATE jobs SET title='{$title}', description='{$desc}', category={$category}, duration={$duration}, "
                        . "job_type={$job_type}, hours_a_week={$hpw}, workers_wanted={$workers}, "
                        . "amount_min={$min_budget}, amount_max={$max_budget} WHERE id={$job_id}")
                or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);

        $mysqli->query("DELETE FROM used_job_tags WHERE job_id={$job_id}")
                or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);

        $sql = "INSERT INTO used_job_tags (job_id, tag_id) VALUES ";
        $_tags = explode(",", $tags);

        foreach ($_tags as $i => $tag)
        {
            $sql.="({$job_id}, {$tag})";
            if ($i < count($_tags) - 1)
                $sql.=",";
            else
                $sql.=";";
        }

        $mysqli->query($sql) or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);

        self::move_job_files($job_id);

        return "ok";
    }

    public static function deleteJob()
    {
        global $mysqli;

        $user_id = $_SESSION["sess_id"];
        $job_id = $_SESSION["job_id"];

        if (!self::auth_job($job_id, $user_id))
        {
            return "Only the owner can delete a job";
        }

        //check for accepted bid

        $res_b = $mysqli->query("SELECT id FROM job_bids WHERE job_id={$job_id} AND awarded=1 "
                . "AND job_id IN(SELECT id FROM jobs WHERE status<4 AND deadline>=UNIX_TIMESTAMP(CURRENT_TIMESTAMP))") 
                or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);

        if ($res_b->num_rows)
        {
            $res_b->close();
            return "You have accepted bids. People are already working on this!";
        }
        $res_b->close();

        //delete job

        $mysqli->query("DELETE FROM job_bids WHERE job_id={$job_id}") or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);
        $mysqli->query("DELETE FROM used_job_tags WHERE job_id={$job_id}") or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);

        $res_f = $mysqli->query("SELECT file_path FROM job_attachments WHERE job_id={$job_id} "
        . "UNION ALL SELECT file_path FROM jobsb_files WHERE job_id={$job_id}") or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);

        while ($file = $res_f->fetch_assoc())
        {
            unlink(__DIR__."/../".$file["file_path"]);
        }

        $res_f->close();

        $mysqli->query("DELETE FROM job_attachments WHERE job_id={$job_id}") or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);
        
        $mysqli->query("DELETE FROM jobsb_files WHERE job_id={$job_id}") or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);
        
        $mysqli->query("DELETE FROM jobs WHERE id={$job_id}") or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);

        return "ok";
    }

    public static function closeJob($rating)
    {
        global $mysqli;
        
        if(!self::auth_job($_SESSION["job_id"], $_SESSION["sess_id"]))
        {
            return "You don't own this job";
        }
        
        $job_id = $_SESSION["job_id"];
        
        //set user's reputation
        
        self::update_user_reputation($rating);
        
        //set the job status as confirmed        
                
        $mysqli->query("UPDATE jobs SET status=4 WHERE id={$job_id}") or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
        //move the cash
        $sql = "UPDATE users u INNER JOIN job_bids jb ON jb.user_id=u.id INNER JOIN jobs j ON j.id=jb.job_id "
                . "SET u.balance = "
                . "CASE WHEN j.job_type = 1 THEN u.balance + (jb.amount * 0.9) "
                . "WHEN j.duration >= (3600*168) THEN u.balance + (jb.amount * (j.hours_a_week * (j.duration/(3600*24*7)))*0.9) "
                . "ELSE u.balance + (jb.amount * j.hours_a_week * 0.9) "
                . "END u.total_transacted = "
                . "CASE WHEN j.job_type = 2 THEN u.total_transacted + (jb.amount * (j.hours_a_week * (j.duration/(3600*24*7)))*0.9) "
                . "ELSE u.total_transacted + (jd.amount * 0.9) END "
                . "WHERE jb.job_id={$job_id} AND jb.awarded=1";
        
        $mysqli->query($sql) or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
        //add the revenue to our account
        $sql = "UPDATE kzo_revenue SET amount = amount + "
                . "(SELECT (CASE WHEN j.job_type = 1 THEN SUM(jb.amount * 0.1) "
                . "WHEN j.duration < (3600*168) THEN SUM(jb.amount * j.hours_a_week * 0.1) "
                . "ELSE SUM(jb.amount * (j.hours_a_week * (j.duration/(3600*24*7)))*0.1) "
                . "END) FROM jobs j INNER JOIN job_bids jb ON j.id=jb.job_id "
                . "WHERE j.id={$job_id}) WHERE id=1";
                
        $mysqli->query($sql) or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
        //update owner's total transaction
        
        $sql = "UPDATE users u INNER JOIN jobs j ON j.owner=u.id INNER JOIN job_bids jb ON jb.job_id=j.id "
                . "SET u.total_transacted = "
                . "CASE WHEN j.job_type = 1 THEN u.total_transcated + SUM(jb.amount * 0.9) "
                . "WHEN j.duration >= (3600*168) THEN u.total_transacted + SUM(jb.amount * (j.hours_a_week * (j.duration/(3600*24*7)))*0.9) "
                . "ELSE u.total_transacted + SUM(jb.amount * j.hours_a_week * 0.9) END "
                . "WHERE jb.job_id={$job_id} AND jb.awarded=1";
                
        $mysqli->query($sql) or die($mysqli->error." ".__FILE__." line ".__LINE__);        
                
        return self::deleteJob(); //should return "ok"        
    }
    
    public static function uploadFiles()
    {
        global $mysqli;
        
        $user_id = $_SESSION["sess_id"];
        $job_id = $_SESSION["job_id"];
        
        if(!self::auth_bid_award($job_id, $user_id))
            return "You were not awarded bid to this job";
        
        if(count($_SESSION["tmp_jobsb_files"]) == 0)
            return "You have not attached any files";
        
        $mysqli->query("UPDATE jobs SET status=3 WHERE id={$job_id}") or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
        self::move_job_files($job_id, 2);
        
        //notify job owner
        
        $owner = self::get_job_owner();
        $ttl = self::get_20chartitle_deadline();
        $notif = "Submitted work! The successful bidder for job {$ttl["title"]} has uploaded his work. Please inspect his work and rate "
        . "the rate the output received";
        
        $mysqli->query("INSERT INTO notifications (user_id, msg) VALUES ({$owner}, '{$notif}')")
                or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);        
        
        return "ok";        
    }
    
    public static function delete_sb_file($file_id)
    {
        global $mysqli;
        
        $job_id = $_SESSION["job_id"];
        $user_id = $_SESSION["sess_id"];
        
        if(!self::auth_bid_award($job_id, $user_id))
            return "You don't have right to edit this work";
        
        $mysqli->query("DELETE FROM jobsb_files WHERE id={$file_id} AND job_id={$job_id}")
                or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
        return "ok";
    }
    
    public static function auth_bid($job_id, $user_id)
    {
        global $mysqli;
        
        $auth = FALSE;
        
        $res = $mysqli->query("SELECT id FROM job_bids WHERE job_id={$job_id} AND user_id={$user_id}")
                or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);

        if ($res->num_rows)
        {
            $auth = TRUE;
        }
        
        $res->close();
        
        return $auth;
    }
    
    public static function newBid($bid_comment, $bid_amount, $bid_duration)
    {
        global $mysqli;

        $bid_amount = Security::clean_data($bid_amount);
        $bid_comment = Security::clean_data($bid_comment);
        $bid_duration = Security::clean_data($bid_duration);

        if (!Security::check_empty([$bid_amount, $bid_comment, $bid_duration]))
            return "Please fill all fields";

        $user_id = $_SESSION["sess_id"];
        $job_id = $_SESSION["job_id"];

        $res = $mysqli->query("SELECT id FROM job_bids WHERE job_id={$job_id} AND user_id={$user_id}")
                or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);

        if ($res->num_rows)
        {
            $res->close();
            return "You have already bidded for this job";
        }
        $res->close();
        
        $res_c = $mysqli->query("SELECT id FROM job_bids WHERE job_id={$job_id} AND awarded=1")
                or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);

        if ($res_c->num_rows)
        {
            $res_c->close();
            return "This job has already been taken";
        }
        $res_c->close();
        
        $bid_duration *= 3600 * 24;
        

        $sql = "INSERT INTO job_bids(job_id, user_id, amount, duration, comment) "
                . "VALUES ({$job_id}, {$user_id}, {$bid_amount}, {$bid_duration}, '{$bid_comment}')";

        $mysqli->query($sql) or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);

        return "ok";
    }

    public static function editBid($bid_comment, $bid_amount, $bid_duration)
    {
        global $mysqli;

        $bid_amount = Security::clean_data($bid_amount);
        $bid_comment = Security::clean_data($bid_comment);
        $bid_duration = Security::clean_data($bid_duration);

        if (!Security::check_empty([$bid_amount, $bid_comment, $bid_duration]))
            return "Please fill all fields";

        $user_id = $_SESSION["sess_id"];
        $job_id = $_SESSION["job_id"];

        
        if (!self::auth_bid($job_id, $user_id))
        {            
            return "Your bid doesn't exist";
        }
        
        $bid_duration *= 3600 * 24;
        
        $sql = "UPDATE job_bids SET comment='{$bid_comment}', amount={$bid_amount}, duration={$bid_duration} "
        . "WHERE user_id={$user_id} AND job_id={$job_id}";

        $mysqli->query($sql) or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);

        return "ok";
    }

    public static function deleteBid()
    {
        global $mysqli;

        $user_id = $_SESSION["sess_id"];
        $job_id = $_SESSION["job_id"];

        if(!self::auth_bid($job_id, $user_id))
        {
            return "This bid isn't yours";
        }

        $sql = "DELETE FROM job_bids WHERE user_id={$user_id} AND job_id={$job_id}";

        $mysqli->query($sql) or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);

        return "ok";
    }

    public static function accept_bid($bid_id)
    {
        global $mysqli;

        //check if another bid has already been awarded

        $job_id = $_SESSION["job_id"];
        $user_id = $_SESSION["sess_id"];
        
        if(!Security::check_empty([$bid_id]))
            return "Empty data found";

        if (!self::auth_job($job_id, $user_id))
            return "Only job owners can accept bids";
        
        $res_e = $mysqli->query("SELECT id FROM job_bids WHERE job_id={$job_id} AND id={$bid_id}") 
                        or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);
        
        if(!$res_e->num_rows)
        {
            $res_e->close();
            return "Bid doesn't exist";
        }
        
        $res_e->close();
        
        $sql = "SELECT (CASE WHEN COUNT(id) < (SELECT workers_wanted FROM jobs WHERE id={$job_id}) THEN 1 ELSE 0 END) chk "
                . "FROM job_bids WHERE job_id={$job_id} AND awarded=1";

        $res_c = $mysqli->query($sql)
                or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);
        
        $data_c = $res_c->fetch_assoc();
        $res_c->close();

        if ($data_c["chk"] == 0)
        {
            return "You have already awarded this job to all bidders";
        }
                
        $bid_inf = self::get_bid_owner($bid_id);
        
        //check balance        
              
        $res_b = $mysqli->query("SELECT id FROM users WHERE id={$user_id} AND balance >= {$bid_inf["amount"]}")
                or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);
        
        $res_b = $mysqli->query("SELECT u.balance, j.job_type, j.hours_a_week, j.duration FROM jobs j "
                . "INNER JOIN users u ON j.owner=u.id WHERE j.id={$job_id}") or die($mysqli->error." ".__FILE__." line ".__LINE__);
                
        $data_b = $res_b->fetch_assoc();
        $res_b->close();
        
        if($data_b["job_type"] == 1 && $data_b["balance"] < $bid_inf["amount"])//fixed price
        {
            return "You do not have enough money in your account. KSH {$bid_inf["amount"]} needs to be deducted ";
        }
        
        if($data_b["job_type"] == 2 && $data_b["duration"] < (3600 * 168) &&$data_b["balance"] < ($data_b["hours_a_week"] * $bid_inf["amount"]))
        {
            return "You do not have enough money in your account. KSH ".($bid_inf["amount"] * $data_b["hours_a_week"])." needs to be deducted ";
        }
        
        $no_weeks = $data_b["duration"]/(3600*24)/7;
        
        if($data_b["job_type"] == 2 && $data_b["balance"] < ($bid_inf["amount"] * $data_b["hours_a_week"] * $no_weeks))
        {
            return "You do not have enough money in your account. KSH ".($bid_inf["amount"] * $data_b["hours_a_week"] *  $no_weeks)." needs to be deducted ";
        }
        
        if($data_b["job_type"] == 2)
            $amount_needed = $no_weeks * $bid_inf["amount"];
        else
            $amount_needed = $bid_inf["amount"];
        
        //move the money
        
        $mysqli->query("UPDATE users SET balance = balance-{$amount_needed} "
        . "WHERE id={$user_id}") or die($mysqli->error." ".__FILE__." line ".__LINE__);

        $mysqli->query("UPDATE job_bids SET awarded=1 WHERE id={$bid_id}")
                or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);

        //set job status to 2 'in progress' if all freelancers have been found
        
        $res_num_c = $mysqli->query("SELECT COUNT(b.id) bids, j.workers_wanted FROM jobs j INNER JOIN job_bids b ON b.job_id=j.id "
                . "WHERE j.id = {$job_id} AND b.awarded=1") or die($mysqli->error." ".__FILE__." line ".__LINE__);
        $data_num = $res_num_c->fetch_assoc();
        
        if($data_num["bids"] == $data_num["workers_wanted"])
            $mysqli->query("UPDATE jobs SET status=2 WHERE id={$job_id}") or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);
            
        $res_num_c->close();        

        //send notification to successful bidder
        
        $bidder = self::get_bid_owner($bid_id);
        $ttl_ddl = self::get_20chartitle_deadline();
        
        $notif = "Bid accepted! Your bid for {$ttl_ddl["title"]} has been accepted! You are required to submit your work not later than "
                . date("jS M Y", $ttl_ddl["deadline"]);
        
        $mysqli->query("INSERT INTO notifications (user_id, msg) VALUES ({$bidder["id"]}, '{$notif}')")
                or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);
        
        
        return "ok";
    }
    
    public static function reopenJob($rating)
    {
        global $mysqli;
        
        $job_id = $_SESSION["job_id"];
        $user_id = $_SESSION["sess_id"];
        
        if(!self::auth_job($job_id, $user_id))
            return "This job isn't yours";
        
        //set bidder's reputation
        self::update_user_reputation($rating, FALSE);
        
        //uncheck the awarded bid and reset the job status back to 1
        
        $mysqli->query("UPDATE job_bids SET awarded=0 WHERE awarded=1 AND job_id={$job_id}; "
                     . "UPDATE jobs SET status=1 WHERE id={$job_id}") or die($mysqli->error." ".__FILE__." line ".__LINE__);
                     
        //return the money
        $sql = "UPDATE users u INNER JOIN jobs j ON j.owner=u.id INNER JOIN job_bids jb ON jb.job_id=j.id SET u.balance = "
                . "(CASE WHEN j.job_type = 1 THEN u.balance + SUM(b.amount) "
                . "WHEN j.duration < (3600*168) THEN u.balance + SUM(b.amount * j.hours_a_week) "
                . "ELSE u.balance + SUM(b.amount * j.hours_a_week * (j.duration/(3600*24*7)) "
                . "END) WHERE j.id={$job_id}";             
        
        $mysqli->query($sql) or die($mysqli->error." ".__FILE__." line ".__LINE__);          
        
        //delete any previously submitted work
        $res = $mysqli->query("SELECT file_path FROM jobsb_files WHERE job_id={$job_id}") 
                    or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
        while($sb_file = $res->fetch_assoc())
        {
            unlink($sb_file["file_path"]);
        }
        
        $res->close();
        
        $mysqli->query("DELETE FROM jobsb_files WHERE job_id={$job_id}")
                    or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
        return "ok";                     
    }
    
    private static function caculate_reputation($amount, $rating)
    {
        $points = ceil(sin(deg2rad(1)) * $amount);
        
        if($rating == 1) //very dissatifying
        {
            $points = - (ceil(($points * 0.2)));
        }
        if($rating == 2) //dissatifying
        {
            $points = ceil(($points * 0.2));
        }
        if($rating == 4) //good
        {
            $points = ceil($points *= 1.5);
        }
        if($rating == 4) //very good
        {
            $points = ceil($points *= 2.3);
        }
        //else: fair, points remain as pre-calculated
        
        return $points;
    }
    
    private static function update_user_reputation($rating, $Reward=TRUE)
    {
        global $mysqli;
        
        $job_id = $_SESSION["job_id"];
        
        $sql = "SELECT (CASE WHEN j.job_type = 2 THEN b.amount * (j.hours_a_week * (j.duration/(3600*24*7)))*0.9 "
                . "ELSE b.amount*0.9 END) AS awarded_cash, b.user_id, j.job_type, j.hours_a_week FROM job_bids b INNER JOIN jobs j ON b.job_id=j.id "
                . "WHERE b.job_id={$job_id} AND b.awarded=1";
        
        $res_amt = $mysqli->query($sql) 
                            or die($mysqli->error." ".__FILE__." line ".__LINE__);                
             
        while($amount_d = $res_amt->fetch_assoc())
        {
            $user_points = self::caculate_reputation($amount_d["awarded_cash"], $rating);            
            
            $ttl = self::get_20chartitle_deadline();
            
            //send notification to bidder
            
            $mysqli->query("UPDATE users SET reputation = reputation + {$user_points} WHERE id={$amount_d["user_id"]}")
                    or die($mysqli->error." ".__FILE__." line ".__LINE__);
            
            if($Reward)
            {
                $notif = "Payment received! The owner of job {$ttl["title"]} rated your services {$rating}/5. You receive KSH {$amount_d["awarded_cash"]} "
                . "after 10% service fee deduction. You earned {$user_points} reputation points";
        
                $mysqli->query("INSERT INTO notifications (user_id, msg) VALUES ({$amount_d["user_id"]}, '{$notif}')")
                or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);
                
            }
            else
            {
                $notif = "We're a sorry! The owner of job {$ttl["title"]} rated your services {$rating}/5 and deemed your work not good enough. "
                    . "The owner reopened the job to other bidders. You earned {$user_points} reputation points";
        
                $mysqli->query("INSERT INTO notifications (user_id, msg) VALUES ({$amount_d["user_id"]}, '{$notif}')")
                    or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);
            }
            
        }
        
        $res_amt->close();
        
        return ["bid"=>$amount_d, "points"=>$user_points];        
    }
    
    public static function loadInfo()
    {
        global $mysqli;
        
        $job_id = $_SESSION["job_id"];
        $me = $_SESSION["sess_id"];
        
        $gen_info = [];                
        $uj_info = [];
        $attachments = [];
        $tags = [];
        $bids = [];
        $sb_files = [];
        
        $gen_info["me"]["is_owner"] = FALSE;
        $gen_info["me"]["has_bidded"] = FALSE;
        $gen_info["me"]["my_bid_awarded"] = FALSE;
        $gen_info["job"]["has_all_bids_awarded"] = FALSE;
        
        $sql = "SELECT u.names, u.location, u.avatar, j.category cat_id, c.name category, j.owner, j.title, j.description, j.amount_min, j.amount_max, j.status, "
                . "j.job_type, j.workers_wanted, j.hours_a_week, UNIX_TIMESTAMP(j.created_on) created_on, j.duration raw_duration, "
                . "(CASE WHEN j.duration < (3600 * 168) THEN CONCAT(FLOOR(j.duration/(3600 * 24)), ' days') "
                . "WHEN j.duration >= (3600 * 168) && j.duration <= (3600 * 24 * 61) THEN CONCAT(FLOOR(j.duration/(3600 *24 * 7)), ' weeks') "
                . "ELSE CONCAT(FLOOR(j.duration/(3600 * 24 * 30)), ' months') END) AS duration "
                . "FROM jobs j INNER JOIN users u ON j.owner=u.id "
                . "INNER JOIN job_subcategories c ON j.category=c.id "
                . "WHERE j.id={$job_id}"; 
                
                                
        $res_uj = $mysqli->query($sql) or die($mysqli->error." ".__FILE__." line ".__LINE__);
        $uj_info = $res_uj->fetch_assoc();
        $res_uj->close();
        
        if($uj_info["owner"] == $me)
            $gen_info["me"]["is_owner"] = TRUE;
        
        $res_tags = $mysqli->query("SELECT jt.* FROM used_job_tags ut INNER JOIN job_tags jt ON jt.id=ut.tag_id "
                . "WHERE ut.job_id={$job_id} ") or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
        while($tag = $res_tags->fetch_assoc())
        {
            $tags[] = $tag;
        }
        
        $res_tags->close();
        
        $res_attach = $mysqli->query("SELECT file_path, id FROM job_attachments WHERE job_id={$job_id}") 
                or die($mysqli->error." ".__FILE__." line ".__LINE__);
                
        while($attach = $res_attach->fetch_assoc())
        {
            $attachments[] = ["id"=>$attach["id"], "basename"=>pathinfo($attach["file_path"], PATHINFO_BASENAME)];
        }
        $res_attach->close();
        
        $res_sb = $mysqli->query("SELECT file_path, id FROM jobsb_files WHERE job_id={$job_id}") 
                or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
        while($sb = $res_sb->fetch_assoc())
        {
            $sb_files[] = ["id"=>$sb["id"], "basename"=>pathinfo($sb["file_path"], PATHINFO_BASENAME)];
        }
        
        $sql = "SELECT u.id bidder, u.location, u.names, u.avatar, u.reputation, b.id, b.amount, b.duration raw_duration, b.awarded, b.comment, "
                . "(CASE WHEN b.duration < (3600 * 168) THEN CONCAT(FLOOR(b.duration/(3600 * 24)), ' days') "
                . "WHEN b.duration >= (3600 * 168) && b.duration <= (3600 * 24 * 61) THEN CONCAT(FLOOR(b.duration/(3600 *24 * 7)), ' weeks') "
                . "ELSE CONCAT(FLOOR(b.duration/(3600 * 24 * 30)), ' months') END) AS duration, UNIX_TIMESTAMP(b.stamp) stamp FROM job_bids b "
                . "INNER JOIN users u ON b.user_id=u.id WHERE b.job_id={$job_id}";
                
        $res_bids = $mysqli->query($sql) or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
        $bids_awarded=0;
        $lucky_bidders = [];
        
        while($bid = $res_bids->fetch_assoc())
        {
            if($bid["bidder"] == $me)
            {
                $gen_info["me"]["has_bidded"] = TRUE;
                if($bid["awarded"] == 1)
                    $gen_info["me"]["my_bid_awarded"] = TRUE;
            }
            if($bid["awarded"] == 1)
            {
                $bids_awarded++;
                $lucky_bidders[] = $bid;
                
                if($bids_awarded == $bid["workers_wanted"])
                    $gen_info["job"]["has_all_bids_awarded"] = TRUE;
            }   
            
            $bids[] = $bid;
        }
        
        $res_bids->close();
        
        return ["gen_info"=>$gen_info, "uj_info"=>$uj_info, "attachments"=>$attachments, "tags"=>$tags, "sb_files"=>$sb_files, 
                "bids"=>$bids, "lucky_bidders"=>$lucky_bidders, "posted"=>self::time_gap($uj_info["created_on"])];
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
    
    private static function get_bid_owner($bid_id)
    {
        global $mysqli;
        
        $res = $mysqli->query("SELECT u.names, u.id, b.amount FROM job_bids b INNER JOIN users u ON u.id=b.user_id "
                . "WHERE b.id={$bid_id}") or die($mysqli->error." ".__FILE__." line ".__LINE__);
                
        $data = $res->fetch_assoc();
        $res->close();
        
        return $data;
    }
    
    private static function get_job_owner()
    {
        global $mysqli;
        
        $job_id = $_SESSION["job_id"];
        
        $res = $mysqli->query("SELECT u.names, u.id FROM jobs j INNER JOIN users u ON u.id=j.owner "
                . "WHERE j.id={$job_id}") or die($mysqli->error." ".__FILE__." line ".__LINE__);
                
        $data = $res->fetch_assoc();
        $res->close();
        
        return $data;
    }
    
    private static function get_20chartitle_deadline()
    {
        global $mysqli;
        
        $job_id = $_SESSION["job_id"];
        
        $res = $mysqli->query("SELECT title, deadline FROM jobs WHERE id={$job_id}") 
                or die($mysqli->error." ".__FILE__." line ".__LINE__);
                
        $data = $res->fetch_assoc();
        $res->close();
        
        $title = strlen($data["title"]) > 20 ? substr($data["title"], 0, 20)."&hellip;":$data["title"];
        
        return ["title"=>$title, "deadline"=>$data["deadline"]];
    }    
    
    public static function prepare_file_download($mode, $id)
    {
        global $mysqli;
        
        $table_name = "";
        $e404 = FALSE;
        $file_path = "";
        
        if(!Security::check_empty([$mode, $id]))
        {
            return ["404"=>TRUE, "file_path"=>""];
        }
        
        if($mode == 1)
            $table_name = "job_attachments";
        else if($mode == 2)
            $table_name = "jobsb_files";
        else
            $e404 = TRUE;
        
        //check auth for sb files
        
        if(!self::auth_job($_SESSION["job_id"], $_SESSION["sess_id"]) && !self::auth_bid_award($_SESSION["job_id"], $_SESSION["sess_id"]) && $mode == 2)
                $e404 = TRUE;
        
        $res_check = $mysqli->query("SELECT file_path FROM {$table_name} WHERE id={$id} AND "
                . "job_id={$_SESSION["job_id"]}") or die($mysqli->error." ".__FILE__." line ".__LINE__);
    
        if(!$res_check->num_rows)
            $e404 = TRUE;
        else
        {
            $file = $res_check->fetch_assoc();
            $file_path = $file["file_path"];
        }
    
        $res_check->close();
        
        return ["404"=>$e404, "file_path"=>$file_path];        
    }
}
