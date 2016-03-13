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
            $obj_array = $_SESSION["tmp_jobfiles"];
        }
        else
        {
            $table_name = "jobsb_files";
            $obj_array = $_SESSION["tmp_jobsb_files"];
        }
        
        if (isset($obj_array))
        {
            $sql = "INSERT INTO {$table_name}(job_id, file_path) VALUES ";

            $count = 0;
            foreach ($_SESSION["tmp_jobfiles"] as $key => $tmp_name)
            {
                $count++;
                $new_path = __DIR__ . "/../usr_files/" . $tmp_name;
                $tmp_dir = __DIR__ . "/../usr_files/tmp/";

                rename($tmp_dir . $tmp_name, $new_path);

                unset($obj_array[$key]);

                $sql.="({$job_id}, '{$new_path}')";

                if ($count < count($obj_array) - 1)
                    $sql.=",";
                else
                    $sql.=";";
            }

            $mysqli->query($sql) or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);

            unset($obj_array);
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

        if (Security::check_empty([$title, $desc, $category, $tags, $deadline, $min_budget, $max_budget]))
        {
            return "Please fill all fields. Only attachments are optional";
        }

        $owner = $_SESSION["sess_id"];

        $_dd = explode("/", $deadline);
        $deadline_stamp = mktime(0, 0, 0, $_dd[1], $_dd[0], $_dd[2]);

        if ($deadline_stamp <= time())
        {
            return "Deadline date must be a future date";
        }

        $mysqli->query("INSERT INTO jobs (title, description, category, deadline, amount_min, amount_max, owner) "
                        . "VALUES ('{$title}', '{$desc}', {$category}, {$deadline_stamp}, {$min_budget}, {$max_budget}, {$owner})")
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

        $sql = "SELECT j.title, j.description, j.deadline, FORMAT(j.amount_min, 0) amount_1, FORMAT(j.amount_max, 0) amount_2, jc.name category "
                . "FROM jobs j INNER JOIN job_categories jc ON j.category=jc.id ";

        if (!empty($search) || !empty($cat) || !empty($tags) || !empty($amount))
        {
            $AND = FALSE;
            if (!empty($search))
            {
                $sql.= " (title LIKE '%{$search}%' OR title LIKE '%{$search}%') ";
                $AND = TRUE;
            }

            if (!empty($cat))
            {
                if ($AND)
                    $sql.=" AND ";
                $sql .= " category={$cat} ";
                $AND = TRUE;
            }

            if (!empty($amount))
            {
                if ($AND)
                    $sql.=" AND ";
                $sql.=" (amount_min<={$amount} AND amount_max>={$amount}) ";
                $AND = TRUE;
            }

            if (!empty($tags))
            {
                //tags are comma separated
                if ($AND)
                    $sql.=" AND ";
                $sql.=" id IN(SELECT job_id FROM used_job_tags WHERE tag_id IN({$tags})) ";
            }
        }

        $jobs = [];

        $res_j = $mysqli->query($sql) or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);

        $total_count = $res_j->num_rows;
        $total_pages = $total_count / 12;

        while ($row = $res_j->fetch_assoc())
        {
            $min_desc = substr($row["description"], 0, 200);
            if (strlen($row["description"]) > 200)
                $min_desc.="&hellip;<a href='javascript:;' vec='" . $row["id"] . "'>See more</a>";

            $jobs[$row["id"]] = ["title" => $row["title"], "category" => $row["category"], "due_by" => date("jS M Y", $row["deadline"]),
                "min_desc" => $min_desc, "max_desc" => $row["description"], "budget" => "{$row["amount_1"]}-{$row["amount_2"]}"];
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

            $tag_ns = implode(", ", $tag_ns);
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
                $pagination_nav.='<li class="active"><a href="javascript:;">' . $i . '</a></li>';
            else
                $pagination_nav.='<li><a href="javascript:;" dx="' . $i . '">' . $i . '</a></li>';
        }

        if ($highest == $page)
        {
            $last = '<li class="disabled"><a href="javascript:;">Last</a></li>';
            $raquo = '<li class="disabled"><a href="javascript:;" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
        } else
        {
            $last = '<li><a href="javascript:;" dx="' . $highest . '">Last</a></li>';
            $raquo = '<li class="disabled"><a href="javascript:;" aria-label="Next" dx="' . ($page + 1) . '"><span aria-hidden="true">&raquo;</span></a></li>';
        }

        $pagination_nav.=$raquo;
        $pagination_nav.=$last;

        $html = "";

        foreach (array_slice($jobs, ($page - 1) * 12, 12) as $id => $job)
        {
            $html.="<tr><td style='width: 18%;'><a href='jobs.php?id=" . $id . "'>{$job["title"]}</a></td><td style='width: 30%;'>{$job["min_desc"]}</td><td style='width: 15%;'>{$job["category"]}</td><td style='width: 13%;'>{$job["tags"]}</td><td style='width: 11%;'>{$job["due_by"]}</td><td style='width: 20%;'>{$job["budget"]}</td></tr>";
        }

        return ["pagination" => $pagination_nav, "jobs" => $html];
    }

    public static function loadDocket($user_id)
    {
        global $mysqli;

        $docket = [];

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

    public static function updateJob($title, $desc, $category, $tags, $min_budget, $max_budget)
    {
        global $mysqli;

        $title = Security::clean_data($title);
        $desc = Security::clean_data($desc);
        $category = Security::clean_data($category);
        $tags = Security::clean_data($tags);
        $deadline = Security::clean_data($deadline);
        $min_budget = Security::clean_data($min_budget);
        $max_budget = Security::clean_data($max_budget);

        $job_id = $_SESSION["job_id"];

        if (self::auth_job($job_id, $_SESSION["sess_id"]))
        {
            return "Only the owner can do this operation";
        }

        if (Security::check_empty([$title, $desc, $category, $tags, $deadline, $min_budget, $max_budget]))
        {
            return "Please fill all fields. Only attachments are optional";
        }

        $_dd = explode("/", $deadline);
        $deadline_stamp = mktime(0, 0, 0, $_dd[1], $_dd[0], $_dd[2]);

        if ($deadline_stamp <= time())
        {
            return "Deadline date must be a future date";
        }

        $mysqli->query("UPDATE jobs SET title='{$title}', description='{$desc}', category={$category}, deadline={$deadline_stamp}, "
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
            return "Only the owner can delete a jon";
        }

        //check for accepted bid

        $res_b = $mysqli->query("SELECT id FROM job_bids WHERE job_id={$job_id} AND awarded=1 "
                . "AND job_id IN(SELECT id FROM jobs WHERE status<4") or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);

        if ($res_b->num_rows)
        {
            $res_b->close();
            return "The bidder hasn't finished doing his work yet";
        }
        $res_b->close();

        //delete job

        $mysqli->query("DELETE FROM job_bids WHERE job_id={$job_id}") or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);
        $mysqli->query("DELETE FROM used_job_tags WHERE job_id={$job_id}") or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);

        $res_f = $mysqli->query("SELECT file_path FROM job_attachments WHERE job_id={$job_id}") or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);

        while ($file = $res_f->fetch_assoc())
        {
            unlink($file["file_path"]);
        }

        $res_f->close();

        $mysqli->query("DELETE FROM job_attachments WHERE job_id={$job_id}") or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);

        return "ok";
    }

    public static function closeJob($rating)
    {
        global $mysqli;
        
        if(!self::auth_bid($_SESSION["job_id"], $_SESSION["sess_id"]))
        {
            return "You don't own this job";
        }
        
        $job_id = $_SESSION["job_id"];
        
        //set user's reputation
        
        self::update_user_reputation($rating);
        
        //set the job status as confirmed        
                
        $mysqli->query("UPDATE jobs SET status=4 WHERE id={$job_id}") or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
        //move the cash
        $sql = "UPDATE users u INNER JOIN job_bids jb ON jb.user_id=u.id "
                . "SET u.balance = u.balance + (jb.amount * 0.8) WHERE jb.job_id={$job_id} AND jb.awarded=1";
        
        $mysqli->query($sql) or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
        return self::deleteJob(); //should return "ok"
        
    }
    
    public static function uploadFiles()
    {
        global $mysqli;
        
        $user_id = $_SESSION["user_id"];
        $job_id = $_SESSION["job_id"];
        
        if(self::auth_job($job_id, $user_id))
            return "You were not awarded bid to this job";
        
        if(count($_SESSION["tmp_jobsb_files"]))
            return "You have not attached any files";
        
        $mysqli->query("UPDATE jobs SET status=3 WHERE id={$job_id}") or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
        self::move_job_files($job_id, 2);
        
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
    
    public static function newBid($bid_comment, $bid_amount)
    {
        global $mysqli;

        $bid_amount = Security::clean_data($bid_amount);
        $bid_comment = Security::clean_data($bid_comment);

        if (Security::check_empty([$bid_amount, $bid_comment]))
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

        $sql = "INSERT INTO job_bids(job_id, user_id, amount, comment) "
                . "VALUES ({$job_id}, {$user_id}, {$bid_amount}, {$bid_comment})";

        $mysqli->query($sql) or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);

        return "ok";
    }

    public static function editBid($bid_comment, $bid_amount)
    {
        global $mysqli;

        $bid_amount = Security::clean_data($bid_amount);
        $bid_comment = Security::clean_data($bid_comment);

        if (Security::check_empty([$bid_amount, $bid_comment]))
            return "Please fill all fields";

        $user_id = $_SESSION["sess_id"];
        $job_id = $_SESSION["job_id"];

        
        if (!self::auth_bid($job_id, $user_id))
        {            
            return "Your bid doesn't exist";
        }
        
        $sql = "UPDATE job_bids SET comment='{$bid_comment}', amount={$bid_amount} WHERE user_id={$user_id} AND job_id={$job_id}";

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

        if (self::auth_job($job_id, $user_id))
            return "Only job owners can accept bids";

        $res_c = $mysqli->query("SELECT id FROM job_bids WHERE job_id={$job_id} AND awarded=1")
                or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);

        if ($res_c->num_rows)
        {
            $res_c->close();
            return "You have already awarded this job to another bidder";
        }
        $res_c->close();

        $mysqli->query("UPDATE job_bids SET awarded=1 WHERE id={$bid_id}")
                or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);

        //set job status to 2 'in progress'

        $mysqli->query("UPDATE jobs SET status=2 WHERE id={$job_id}") or die($mysqli->error . " " . __FILE__ . " line " . __LINE__);

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
        self::update_user_reputation($rating);
        
        //uncheck the awarded bid and reset the job status back to 1
        
        $mysqli->query("UPDATE job_bids SET awarded=0 WHERE awarded=1 AND job_id={$job_id}; "
                     . "UPDATE jobs SET status=1 WHERE id={$job_id}") or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
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
    
    private static function update_user_reputation($rating)
    {
        global $mysqli;
        
        $job_id = $_SESSION["job_id"];
        
        $res_amt = $mysqli->query("SELECT amount, user_id FROM job_bids WHERE job_id={$job_id} AND awarded=1") 
                            or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
        $amount_d = $res_amt->fetch_assoc();
        $res_amt->close();
        
        $user_points = self::caculate_reputation($amount_d["amount"], $rating);
        
        $mysqli->query("UPDATE users SET reputation = reputation + {$user_points} WHERE id={$amount_d["user_id"]}")
                  or die($mysqli->error." ".__FILE__." line ".__LINE__);
        
    }
    
    
}
