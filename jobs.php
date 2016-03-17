<?php
require_once 'neuro/Jobs.php';
require_once 'neuro/Data.php';
require_once 'neuro/security.php';

session_start();

Security::check_session(TRUE);

$me = $_SESSION["sess_id"];

$job_id = filter_input(INPUT_GET, "job");

if($job_id)
    $_SESSION["job_id"] = $job_id;

Jobs::cleanup_tmp();

$job_data = Jobs::loadInfo();
$notifications = Data::load_notifications($_SESSION["sess_id"]);
$job_categories = Data::load_job_categories();

?>

<!DOCTYPE html>
<html lang="en">
  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= $job_data["uj_info"]["title"] ?></title>

    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">     
    <link href="bootstrap/css/timeline.css" rel="stylesheet">    
    <link href="bootstrap/css/dashboard_custom.css" rel="stylesheet">
    <link href="bootstrap/css/bootstrap-tagsinput.css" rel="stylesheet"> 
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="jquery/css/datepicker.css" rel="stylesheet">
    <link href="jquery/css/uploadfile.css" rel="stylesheet">
    <link href="jquery/css/rating.css" rel="stylesheet">
    <style>

      .white-bg
      {
          background-color: #ffffff;
      }
      .badge-blue
      {
          background-color: #0066ff;
      }  

      .bids-section
      {
          border-left: #d8d5d5 1px solid;
          border-right: 1px #d8d5d5 solid;
      }
      .bootstrap-tagsinput 
      {
          width: 100% !important;
      }
      .feedback,#jobs-load
      {
          position: absolute;
          top: 250px;
          left: 300px;
          z-index: 100000;
      }
      #jobs-load
      {
          opacity: 0.6;
      }
      #search_bar_txt
      {
          font-size: 16px;
          font-weight: 600;
      }

    </style>

  </head>

  <!-- Navigation -->
  <nav class="navbar navbar-inverse navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="javascript:">Kim &nbsp;<i class="fa fa-fw fa-home" style="color: #337AB7;"></i></a>
    </div>        

    <ul class="nav navbar-top-links navbar-right">
      <li class="dropdown">
        <a class="dropdown-toggle navlink" data-toggle="dropdown" href="#">
          <i class="fa fa-bell fa-fw"></i>  <i class="fa fa-caret-down"></i>
        </a>
        <ul class="dropdown-menu dropdown-alerts">
            <?php
            if(count($notifications["notifications"]) == 0)
             echo '<li><a href="javascript:;">No new notifications</a></li>';
            
            foreach(array_slice($notifications["notifications"], 0, 3) as $notif)
            {
                $intro = substr($notif["msg"], 0, strpos($notif["msg"], "!"));
                $class="";
                
                if(!$notif["is_read"])
                    $class="unread_notif";
                
                echo '<li class="'.$class.'">a href="javascript:;">'.$intro.'</a></li>';
            }
            
            ?>
          <li>
            <a class="text-center" href="javascript:;" id="see_all_notifs">
              <strong>See Notifications</strong>
              <i class="fa fa-angle-right"></i>
            </a>
          </li>
        </ul>            
      </li>

      <li class="dropdown">
        <a class="dropdown-toggle navlink" data-toggle="dropdown" href="#">
          <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
        </a>
        <ul class="dropdown-menu dropdown-user">
          <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
          </li>
          <li class="divider"></li>
          <li><a href="#"><i class="fa fa-gear fa-fw"></i>About Us</a>
          </li>
          <li><a href="#"><i class="fa fa-gear fa-fw"></i>Contact Us</a>
          </li>
          <li class="divider"></li>
          <li><a href="#"><i class="fa fa-gear fa-fw"></i>Help</a>
          </li>
          <li class="divider"></li>
          <li><a href="#"><i class="fa fa-gear fa-fw"></i>Terms &amp; Conditions</a>
          </li>
          <li class="divider"></li>
          <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
          </li>
        </ul>
        <!-- /.dropdown-user -->
      </li>
      <!-- /.dropdown -->
    </ul>

  </nav>

  <div id="page-container">

    <div class="col-lg-12 white-bg data-container">
      <br><br>
      <div class="col-lg-3">            
        <h3><small>CREATOR</small></h3>

        <div class="row"><br>
          <div class="col-lg-4"><img src="<?= $job_data["uj_info"]["avatar"] ?>" style="height: 100px; width: auto;"></div>
          <div class="col-lg-8"><?= $job_data["uj_info"]["names"] ?><br><i class="fa fa-fw fa-map-marker"></i><small><?= $job_data["uj_info"]["location"] ?></small></div></div>
        <br>
        <h3><small>JOB INFO</small></h3>
        <strong>Brief</strong><br>
        <span id="job-info-brief"><?= $job_data["uj_info"]["title"] ?></span><br><br>
        <strong>Category</strong><br>
        <span><?= $job_data["uj_info"]["category"] ?></span><br><br>
        <span id="job-info-category" style="display: none"><?= $job_data["uj_info"]["cat_id"] ?></span>
        <strong>Tags</strong><br>
        <span>
        <?php
            $tags_names = [];
            
            foreach($job_data["tags"] as $tag)
            {
                $tag_names[] = $tag["name"];                
            }
            
            echo implode(",", $tag_names);
        ?>
        </span>
        <span id="tag-ids" style="display:none;"><?= json_encode($job_data["tags"]) ?></span>
        <br><br>
        <strong>Attachments</strong><br>
        <span>
            <?php
              
              if(count($job_data["attachments"]) == 0)
                  echo "No files attached";
              
              foreach($job_data["attachments"] as $file)
              {
                  echo "<a href='download_file.php?id={$file["id"]}'>{$file["basename"]}</a><br>";
              }
            ?>
        </span><br><br>
        <strong>Description</strong><br>
        <span id="job-info-description"><?= $job_data["uj_info"]["description"] ?></span>
        
        <div class="col-lg-12"><br><br><br></div>
      </div>
      <div class="col-lg-7 bids-section">            
        <div class="col-lg-8">
          <p><strong>Created On:</strong> <?= date("jS M Y", $job_data["uj_info"]["created_on"]) ?></p>
          <p><strong>Due by:</strong> <?= date("jS M Y", $job_data["uj_info"]["deadline"]) ?></p>
          <p><strong>Budget:</strong> KSH <?= $job_data["uj_info"]["amount_min"]." - ".$job_data["uj_info"]["amount_max"]  ?> </p>
          <br><br>
        </div>

        <div class="col-lg-12 jobs-list-container">
          <fieldset>
            <legend><h3><small>BIDS</small></h3></legend>
          </fieldset>
          <div class="row col-lg-12">
            <div><span class="badge badge-blue"><?= count($job_data["bids"]);  ?> bids</span><br><br><br></div>

            <table class="table">              
              <tbody id="bids-listings">
                
                <?php
                
                foreach($job_data["bids"] as $bid)
                {                
                    echo "<tr dx='{$bid["id"]}'><td dx='{$bid["id"]}'>"
                    . "<div class='col-lg-2'><img src='{$bid["avatar"]}' style='width: 80px; height: auto'></div>"
                    . "<div class='col-lg-10'><div class='col-lg-5'><span class='bid-user'>{$bid["names"]}</span> <i class='fa fa-star' style='color:#FFD700;'></i> {$bid["reputation"]}</div><div class='col-lg-4'><i class='fa fa-fw fa-map-marker'></i><small>{$bid["location"]}</small></div>"
                    . "<div class='col-lg-3'><span class='pull-right'>";
                    if($bid["bidder"] == $me && $bid["awarded"] == 0)
                    {
                        echo "<a href='javascript:;' vec='bid-edit' dx='{$bid["id"]}'><i class='fa fa-fw fa-pencil'></i></a><a href='javascript:;' vec='bid-delete' dx='{$bid["id"]}'><i class='fa fa-fw fa-trash-o'></i></a>&nbsp;";
                    }
                    
                    echo "<small><i class='fa fa-clock-o fa-fw'></i>".Jobs::time_gap($bid["stamp"])."</small></span></div><div class='col-lg-6'><i class='fa fa-fw fa-credit-card'></i> <small><span class='bid-amount'>{$bid["amount"]}</span> ";
                    if($bid["awarded"] == 1)
                    {
                        echo "&nbsp;&nbsp;<span><i class='fa fa-fw fa-gift' style='color: #40E0D0;'></i>&nbsp;<span class='label label-info'>accepted</span></span>";
                    }
                    
                    else if($job_data["gen_info"]["me"]["is_owner"])
                    {
                        echo "<br><a href='javascript:;' class='btn btn-xs btn-danger' vec='bid-accept' dx='{$bid["id"]}'>Accept</a></small>";
                    }                    
                    
                    echo "</div><div class='col-lg-12'><br><span class='bid-comment'>{$bid["comment"]}</span></div></div>";                
                
                }
                
                
                if(!$job_data["gen_info"]["me"]["has_bidded"] && !$job_data["gen_info"]["job"]["has_bid_awarded"]  && !$job_data["gen_info"]["me"]["is_owner"])
                {
                ?>
                <tr id="new_bid_tr">
                  <td>
                    <br><br>
                    <fieldset><legend>Submit Bid <small>150 chars</small></legend></fieldset>
                    <div class="col-lg-7"><label>Why are you the best candidate?</label><textarea class="form-control new-bid-comment" style="min-height: 150px;" placeholder="Be brief"></textarea></div>
                    <div class="col-lg-5">
                      <label>How much do you want?</label>
                      <div class="input-group"><span class="input-group-addon">KSH</span><input type="text" class="form-control new-bid-amount" placeholder="0">
                        <span class="input-group-addon">.00</span>
                      </div>
                      <br><br>
                      <button class="btn btn-primary" id="new-bid-sb">Submit <i class="fa fa-fw fa-angle-double-right"></i></button>
                    </div>
                  </td>
                </tr>
                <?php
                    }
                ?>
              </tbody>
            </table>


          </div>
          <div class="col-lg-12"><br></div>
        </div>  

      </div>
      <div class="col-lg-2">
        <fieldset><legend><small>Status</small></legend></fieldset>
        
        <?php
        $status_class = "primary";
        $status_text = "Open";
        
        if($job_data["uj_info"]["status"] == 2)
        {
            $status_class = "warning";
            $status_text = "Work Ongoing";
        }
        if($job_data["uj_info"]["status"] == 3)
        {
            $status_class = "success";
            $status_text = "Pending Confirmation";
        }
        
        ?>
        
        <span class="label label-<?= $status_class ?>">&nbsp;&nbsp;<?= $status_text ?>&nbsp;&nbsp;</span><br><br>
        <?php
            
            if($job_data["gen_info"]["me"]["is_owner"] && !$job_data["gen_info"]["job"]["has_bid_awarded"])
            {
        
        ?>
        <fieldset><legend><small>Edit Job</small></legend></fieldset>
        <button type="button" id="open_edit_job" class="btn btn-danger">Edit Job &nbsp;&nbsp;&nbsp;<i class="fa fa-fw fa-edit"></i></button><br><br>
        <?php
            }
            if($job_data["gen_info"]["me"]["is_owner"] && (!$job_data["gen_info"]["job"]["has_bid_awarded"] || $job_data["uj_info"]["deadline"] < time()))
            {
        ?>        
        <fieldset><legend><small>Delete Job</small></legend></fieldset>        
        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete_job_modal" id="job_delete">Delete Job &nbsp;&nbsp;<i class="fa fa-fw fa-trash-o"></i></button><br><br>
        <?php
            }
            if($job_data["gen_info"]["me"]["my_bid_awarded"])
            {
        ?>        
        <fieldset><legend><small>Upload your work</small></legend></fieldset>
        <div id="job-upload-div"></div>
        <button type="button" class="btn btn-primary" id="upload_sb_work">Upload &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-fw fa-upload"></i></button><br><br>
        <?php
            }
            if($job_data["gen_info"]["me"]["is_owner"] && $job_data["uj_info"]["status"] == 3)
            {
        ?>
        <fieldset><legend><small>submitted work</small></legend></fieldset>
        <table class="table">
          <thead>File</thead>
          <tbody>
        <?php
            foreach($job_data["sb_files"] as $file)
            {
                echo "<tr><td><a href='download_file.php?id=".$file["id"]."'>{$file["basename"]}</a></td></tr>";
            }
        ?>
          </tbody>
        </table>
        <fieldset><legend><small>rate submitted work</small></legend></fieldset>        
        <div id="sbwork-rating">
            <input type="radio" name="example" class="rating" value="1">
            <input type="radio" name="example" class="rating" value="2">
            <input type="radio" name="example" class="rating" value="3">
            <input type="radio" name="example" class="rating" value="4">
            <input type="radio" name="example" class="rating" value="5">
        </div>
        <?php
            }
        ?>

      </div>

      <!-- Modal -->
      <div class="modal fade" id="edit_job_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel"><i class="fa fa-fw fa-edit"></i>&nbsp;Edit Job</h4>
            </div>
            <div class="modal-body" style="height: 500px; overflow-y: scroll;">
              <form id="job_edit_form" action="controller/update_job.php" method="post" enctype="multipart/form-data">              
                <div class="form-group">
                  <label for="job_title">Summary <small>(30 chars)</small></label>
                  <input type="text" class="form-control" name="job_title" id="job_title" placeholder="Very brief summary" value="<?= $job_data["uj_info"]["title"] ?>" required="">
                </div>
                <div class="form-group">
                  <label for="job_desc">Complete description</label>
                  <textarea class="form-control" id="job_desc" name="job_desc" placeholder="Be expansive about the job, project, task etc." required=""><?= $job_data["uj_info"]["description"] ?></textarea>                    
                </div>
                <div class="form-group">
                  <label for="job_criteria">Category</label>
                  <select class="form-control" id="job_category" name="job_category" required="">
                    <option value="">Select Category</option>                    
                <?php
                    foreach($job_categories as $cat)
                    {
                        if($cat["name"] == "Other")
                            continue;
                ?>
                <option value="<?= $cat["id"] ?>"><?= $cat["name"] ?></option>
                <?php
                    }
                 ?>                
                <option value="1">Other</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="job_tags">Additional Tags</label><br>
                  <input type="text" class="form-control" name="job_tags" id="job_tags" placeholder="e.g. Physics, CPA, C++, Pharmacy">
                </div>
                <div class="form-group">
                  <strong>Attach More Files</strong>&nbsp;&nbsp;<small>(if any) max <strong>25MB</strong> total</small>
                  <div id="job_attach_files"></div>
                </div>
                <fieldset>
                    <legend>Due by</legend>
                    <label>Pick date</label>
                    <div class="form-group">
                      <div class="input-group" style="width: 50%;">
                        <input type="text" class="form-control" id="job_deadline" name="job_deadline" value="<?= date("d/m/Y", $job_data["uj_info"]["deadline"]) ?>">
                        <span class="input-group-addon"><i class="fa fa-fw fa-calendar"></i></span>
                      </div>
                    </div>                
                </fieldset>

                  <fieldset>
                    <legend>Your Budget</legend>
                    <div class="form-group">
                      <label>Minimum</label>
                      <div class="input-group" style="width: 50%;">
                        <span class="input-group-addon">KSH</span>
                        <input type="text" id="job_amount_min" name="job_amount_min" class="form-control" placeholder="Minimum amount" value="<?= $job_data["uj_info"]["amount_min"] ?>" required="">
                        <span class="input-group-addon">.00</span>
                      </div>                  
                    </div>
                    <div class="form-group">
                      <label>Maximum</label>
                      <div class="input-group" style="width: 50%;">
                        <span class="input-group-addon">KSH</span>
                        <input type="text" id="job_amount_max" name="job_amount_max" class="form-control" placeholder="Maximum amount" value="<?= $job_data["uj_info"]["amount_max"] ?>" required="">
                        <span class="input-group-addon">.00</span>
                      </div>                  
                    </div>
                  </fieldset>

              </form>

              <form class="feedback" style="display: none;">
              </form>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-primary" id="sb_job_create_form">Save</button>
            </div>
          </div>
        </div>
      </div>

      <div id="jobs-load" style="display: none;"></div>

      <div class="modal fade" id="edit_bid_modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><i class="fa fa-fw fa-edit"></i>&nbsp;Edit bid</h4>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-lg-7">
                  <textarea class="form-control edit-bid-comment" style="min-height: 100px;" placeholder="Bid Comment"></textarea>
                </div>
                <div class="col-lg-5">
                  <div class="input-group">
                    <span class="input-group-addon">KSH</span>
                    <input type="text" class="form-control edit-bid-amount" placeholder="Bid Amount">
                    <span class="input-group-addon">.00</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <span id="edit-bid-loading-gif"></span>
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-primary" id="save-bid-edits">Save changes</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="delete_bid_modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><i class="fa fa-fw fa-trash-o"></i>&nbsp;Delete bid</h4>
            </div>
            <div class="modal-body">
              <p>Are you sure you want to delete your bid to this Job?</p>
            </div>
            <div class="modal-footer">
              <span id="delete-bid-loading-gif"></span>
              <button type="button" class="btn btn-danger" id="delete-bid-sb">Delete</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
          </div>
        </div>
      </div>
      
      <div class="modal fade" id="delete_job_modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><i class="fa fa-fw fa-trash-o"></i>&nbsp;Delete Job</h4>
            </div>
            <div class="modal-body">
              <p>Are you sure you want to delete this job?</p>
            </div>
            <div class="modal-footer">
              <span id="delete-bid-loading-gif"></span>
              <button type="button" class="btn btn-danger" id="delete-job-conf">Delete</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="accept_bid_modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><i class="fa fa-fw fa-check"></i>&nbsp;Accept bid</h4>
            </div>
            <div class="modal-body">
              <p>Are you sure you want to accept bid from <strong><span id="bid-accept-usr">Kim</span></strong> for KSH <strong><span id="bid-accept-amt">12,500</span></strong></p>
            </div>
            <div class="modal-footer">
              <span id="accept-bid-loading-gif"></span>        
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-danger" id="accept-bid-sb">Confirm</button>
            </div>
          </div>
        </div>
      </div>
      
      <div class="modal fade" id="notifs_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Modal title</h4>
      </div>
      <div class="modal-body">
        
          <?php
          
            foreach($notifications["notifications"] as $notif)
            {
                $intro = substr($notif["msg"], 0, strpos($notif["msg"], "!"));
                $info = substr($notif["msg"], strpos($notif["msg"], "!")+1);
                
                $class="";                
                if(!$notif["is_read"])
                    $class="unread_notif";
                
                
          ?>
        <div class="row <?= $class ?>">
           <div class="col-lg-10">
              <h4><?= $intro ?></h4>
            </div>
            <div class="col-lg-2"><small><?= $notif["time"] ?></small></div>
            <div class="col-lg-10">
                <?= $info ?>
            </div>
        <div class="col-lg-12"><hr></div>
        </div>
        <?php
            }
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>        
      </div>
    </div>
  </div>
</div>


      <script src="jquery/jquery-1.10.2.min.js"></script>
      <script src="jquery/jquery.form.js"></script> 
      <script src="bootstrap/js/bootstrap.min.js"></script>
      <script src="bootstrap/js/metisMenu.min.js"></script>
      <script src="bootstrap/js/dashboard_custom.js"></script>
      <script src="bootstrap/js/dashboard_js.js"></script>
      <script src="jquery/bootstrap3-typeahead.min.js"></script>
      <script src="bootstrap/js/bootstrap-tagsinput.js"></script>
      <script src="jquery/datepicker.js"></script>
      <script src="jquery/jquery.uploadfile.js"></script>
      <script src="jquery/rating.js"></script>
      <script type="text/javascript">
          
            var work_rating = 0

            var tomorrow = new Date()
            tomorrow.setDate(tomorrow.getDate() + 1)         
             
            var allowed_exts = "csv,xls,xlsx,txt,png,jpg,gif,bmp,avi,mpg,mpeg,mp4,mp3,wav,flv,pdf,doc,docx,ppt,pptx,psd,pub,7z,ppd,bz,bz2,ico,jar,phar,tex,latex,wma,wmx,wmv,mpga,mp4a,oda,oxt,ogx,oga,ogv,odb,rar,tar,xz,zip,gtar,tiff";

            $(function ()
            {
                $("#job_category").val($("#job-info-category").html())                
                                
                $("#job_deadline").datepicker({zIndex: 1000000, autohide: true, startDate: tomorrow, format: 'dd/mm/yyyy'});
                
                $("#job_tags").tagsinput({
                    tagClass: 'label label-info',
                    itemValue: function (item)
                    {
                        return item.id
                    },
                    itemText: function (item)
                    {
                        return item.name
                    },
                    typeahead: {
                        afterSelect: function (val)
                        {
                            this.$element.val("");
                        },
                        source: function (query)
                        {
                            return $.getJSON("controller/search_tags.php", {term: query})
                        }
                    }
                })
                
                var tags_str = $("#tag-ids").html()                
                var tag_ids = JSON.parse(tags_str)
                
                for(var j in tag_ids)
                {
                    $("#job_tags").tagsinput('add', tag_ids[j])
                }
                               
                $("#job-upload-div").uploadFile(
                        {
                            url: "controller/job_sb_files_upload.php",
                            fileName: 'jobfile',
                            uploadStr: "<i class='glyphicon glyphicon-paperclip'></i>",
                            multiple: true,
                            dragDrop: true,
                            showPreview: true,
                            showProgress: true,
                            showDelete: true,
                            sequential: true,
                            dragdropWidth: 200,
                            sequentialCount: 1,
                            previewHeight: '100px',
                            previewWidth: 'auto',
                            deletelStr: '<i class="fa fa-fw fa-trash"></i>',
                            fileCounterStyle: '. ',
                            maxFileSize: 26214400,
                            allowedTypes: allowed_exts,
                            extErrorStr: ' such files are not allowed. The following are allowed - ',
                            sizeErrorStr: 'maximum attachment size is 25MB',
                            deleteCallback: function (data, pd)
                            {
                                data = JSON.parse(data)

                                $.post("controller/job_sb_upl_delete.php", {name: data[0]},
                                        function (resp, textStatus, jqXHR)
                                        {

                                        }
                                );

                                pd.statusbar.hide()
                            },
                            onError: function (files, status, errMsg, pd)
                            {
                                $(".feedback").html('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Oops:</strong> Lost Connection</div>')
                                $(".feedback").show()
                            },
                            onSuccess: function (files, data, xhr, pd)
                            {
                                var res = new String(data)
                                if (res.substr(0, 2) == "E:")
                                    {
                                        $(".feedback").html('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Error uploading ' + files + ':</strong> ' + res.substring(2) + '</div>')
                                        $(".feedback").show()
                                        pd.statusbar.hide()
                                    }
                            }
                        })

                $("#job_attach_files").uploadFile(
                        {
                            url: "controller/job_file_upload.php",
                            fileName: 'jobfile',
                            uploadStr: "<i class='glyphicon glyphicon-paperclip'></i>",
                            multiple: true,
                            dragDrop: true,
                            showPreview: true,
                            showProgress: true,
                            showDelete: true,
                            sequential: true,
                            sequentialCount: 1,
                            previewHeight: '100px',
                            previewWidth: 'auto',
                            deletelStr: '<i class="fa fa-fw fa-trash"></i>',
                            fileCounterStyle: '. ',
                            maxFileSize: 26214400,
                            allowedTypes: allowed_exts,
                            extErrorStr: ' such files are not allowed. The following are allowed - ',
                            sizeErrorStr: 'maximum attachment size is 25MB',
                            deleteCallback: function (data, pd)
                            {
                                data = JSON.parse(data)

                                $.post("controller/job_file_upl_delete.php", {name: data[0]},
                                        function (resp, textStatus, jqXHR)
                                        {

                                        }
                                );

                                pd.statusbar.hide()
                            },
                            onError: function (files, status, errMsg, pd)
                            {
                                $(".feedback").html('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Oops:</strong> Lost Connection</div>')
                                $(".feedback").show()
                            },
                            onSuccess: function (files, data, xhr, pd)
                            {
                                var res = new String(data)
                                if (res.substr(0, 2) == "E:")
                                    {
                                        $(".feedback").html('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Error uploading ' + files + ':</strong> ' + res.substring(2) + '</div>')
                                        $(".feedback").show()
                                        pd.statusbar.hide()
                                    }
                            }
                        })

                
                $("[vec='bid-edit']").on("click", function ()
                {
                    var dx = $(this).attr("dx");
                    var bid_amount = $("td[dx='" + dx + "']").find("span.bid-amount").html()
                    var bid_text = $("td[dx='" + dx + "']").find("span.bid-comment").html()
                    $(".edit-bid-comment").val(bid_text)
                    $(".edit-bid-amount").val(bid_amount)
                    $("#save-bid-edits").attr("dx", dx);
                    $("#edit_bid_modal").modal('show')

                })

                $("[vec='bid-delete']").on("click", function ()
                {
                    var dx = $(this).attr("dx");
                    $("#delete-bid-sb").attr("dx", dx);
                    $("#delete_bid_modal").modal('show')
                })

                $("[vec='bid-accept']").on("click", function ()
                {
                    var dx = $(this).attr("dx")
                    var bid_amount = $("td[dx='" + dx + "']").find("span.bid-amount").html()
                    var bid_user = $("td[dx='" + dx + "']").find("span.bid-user").html()
                    
                    $("#bid-accept-usr").html(bid_user)
                    $("#bid-accept-amt").html(bid_amount)

                    $("#accept_bid_modal").modal('show')
                    $("#accept-bid-sb").attr("dx", dx);

                })
                
                $('#sbwork-rating').rating(function(vote, e)
                {
                    $("#rate-reaction").remove()
                    
                    var verdict
                    var rate_reaction = $("<div id='rate-reaction'></div>")
                    
                    switch(vote)
                    {
                        case "1":
                            verdict = "Very Dissatisfied"
                            rate_reaction.append("<div class='text-danger'><small>"+verdict+"</small></div><div><br><button type='button' class='btn btn-danger' id='reopen_job'>Reopen Job</button><br><small>You may reopen it for someone else to do it</small><br><br><button type='button' class='btn btn-danger' id='close_job'>Close Job</button><br><small>Payment will be finalized and job closed</small>")
                            break
                        case "2":
                            verdict = "Dissatisfied"
                            rate_reaction.append("<div class='text-warning'><small>"+verdict+"</small></div><div><br><button type='button' class='btn btn-danger' id='reopen_job'>Reopen Job</button><br><small>You may reopen it for someone else to do it</small><br><br><button type='button' class='btn btn-danger' id='close_job'>Close Job</button><br><small>Payment will be finalized and job closed</small>")
                            break
                        case "3":
                            verdict = "Fair Enough"
                            rate_reaction.append("<div class='text-info'><small>"+verdict+"</small></div><div><br><br><button type='button' class='btn btn-danger' id='close_job'>Close Job</button><br><small>Payment will be finalized and job closed</small>")
                            break
                        case "4":
                            verdict = "Good"
                            rate_reaction.append("<div class='text-primary'><small>"+verdict+"</small></div><div><br><br><button type='button' class='btn btn-danger' id='close_job'>Close Job</button><br><small>Payment will be finalized and job closed</small>")
                            break
                        default:
                            verdict = "Very Good"
                            rate_reaction.append("<div class='text-success'><small>"+verdict+"</small></div><div><br><br><button type='button' class='btn btn-danger' id='close_job'>Close Job</button><br><small>Payment will be finalized and job closed</small>")
                    }
                    
                    $('#sbwork-rating').after(rate_reaction)                    
                    
                });
                
                $("#upload_sb_work").on("click", function()
                {
                    $.ajax({
                        url:"controller/job_sb_conf_upload.php",
                        type:"GET",
                        async:true,
                        success:function(result)
                        {
                            if(result == "ok")
                            {
                                $(".feedback").html('<div class="alert alert-success" role="alert"><strong>Successful!: </strong>The job owner will inspect your work and get back to you soon<br><em>Just a minute&hellip;</em></div>')
                                $(".feedback").show()                              
                                
                                setTimeout(function(){ window.location.reload()}, 1500)
                            }
                            else
                            {
                                $(".feedback").html('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Error!:</strong> '+result+'</div>')
                                $(".feedback").show()
                            }
                            
                        },
                        error:function()
                        {
                            $(".feedback").html('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Oops:</strong> Lost Connection</div>')
                            $(".feedback").show()
                        }
                    })
                })
                
              var options1 =
              {
                  complete: function (response)
                  {
                      if (response.responseText != "ok")
                          {
                              $(".feedback").html('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Error!:</strong> '+response.responseText+'</div>')
                              $(".feedback").show()
                          }
                      else
                          {
                              $(".feedback").html('<div class="alert alert-success" role="alert"><strong>Successful!: </strong><br><em>Just a minute&hellip;</em></div>')
                              $(".feedback").show()
                                
                               setTimeout(function(){window.location.reload()}, 1500)
                          }
                  }
              }

                $("#job_edit_form").ajaxForm(options1)                
                
                $("#save-bid-edits").on("click", function()
                {
                    var cmt = $(".edit-bid-comment").val()
                    var amt = $(".edit-bid-amount").val()
                    var dx = $(this).attr("dx")
                    
                    $.ajax({
                        url:"controller/edit_bid.php",
                        type:"POST",
                        async:true,
                        data:{cmt:cmt, amt:amt},
                        success:function(result)
                        {
                            if(result == "ok")
                            {
                                $(".feedback").html('<div class="alert alert-success" role="alert"><strong>Successful!: </strong><br>Your edits have been applied</div>')
                                $(".feedback").show()
                                
                                $("td[dx='"+dx+"']").find("span.bid-comment").html(cmt)
                                $("td[dx='"+dx+"']").find("span.bid-amount").html(amt)
                                
                                $("#edit_bid_modal").modal('hide')
                                
                            }
                            else
                            {
                                $(".feedback").html('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Error!:</strong> '+result+'</div>')
                                $(".feedback").show()
                            }
                        },
                        error:function()
                        {
                            $(".feedback").html('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Oops:</strong> Lost Connection</div>')
                            $(".feedback").show()
                        }
                    })
                })
                
                $("#delete-bid-sb").on("click", function()
                {
                    
                    var dx = $(this).attr("dx")
                    
                    $.ajax({
                        url:"controller/delete_bid.php",
                        type:"POST",
                        async:true,
                        success:function(result)
                        {
                            if(result == "ok")
                            {
                                $(".feedback").html('<div class="alert alert-success" role="alert"><strong>Successful!: </strong><br>Your edits have been applied</div>')
                                $(".feedback").show()
                                
                                $("#delete_bid_modal").modal('hide')
                                
                                $("td[dx='"+dx+"']").slideUp()                                                                
                            }
                            else
                            {
                                $(".feedback").html('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Error!:</strong> '+result+'</div>')
                                $(".feedback").show()
                            }
                        },
                        error:function()
                        {
                            $(".feedback").html('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Oops:</strong> Lost Connection</div>')
                            $(".feedback").show()
                        }
                    })
                })
                
                $("#accept-bid-sb").on("click", function()
                {
                    var dx = $(this).attr("dx")
                    
                    $.ajax({
                        url:"controller/bid_accept.php",
                        type:"POST",
                        async:true,
                        data:{dx:dx},
                        success:function(result)
                        {
                            if(result == "ok")
                            {
                                $(".feedback").html('<div class="alert alert-success" role="alert"><strong>Successful!: </strong><br>Your edits have been applied</div>')
                                $(".feedback").show()
                                
                                $("#accept_bid_modal").modal('hide')
                            }
                            else
                            {
                                $(".feedback").html('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Error!:</strong> '+result+'</div>')
                                $(".feedback").show()
                            }
                        },
                        error:function()
                        {
                            $(".feedback").html('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Oops:</strong> Lost Connection</div>')
                            $(".feedback").show()
                        }
                    })
                })
                
                $("#delete-job-conf").on("click", function()
                {                    
                    $.ajax({
                        url:"controller/delete_job.php",
                        type:"POST",
                        async:true,
                        success:function(result)
                        {
                            if(result == "ok")
                            {
                                $(".feedback").html('<div class="alert alert-success" role="alert"><strong>Successful!: </strong><br><em>Just a moment&hellip;</em></div>')
                                $(".feedback").show()
                                
                                setTimeout(function(){window.location="home.php"}, 1500)                                                               
                            }
                            else
                            {
                                $(".feedback").html('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Error!:</strong> '+result+'</div>')
                                $(".feedback").show()
                            }
                        },
                        error:function()
                        {
                            $(".feedback").html('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Oops:</strong> Lost Connection</div>')
                            $(".feedback").show()
                        }
                    })
                })
                
                $("#reopen_job").on("click", function()
                {                    
                    $.ajax({
                        url:"controller/delete_job.php",
                        type:"POST",
                        async:true,
                        data:{rating:work_rating},
                        success:function(result)
                        {
                            if(result == "ok")
                            {
                                $(".feedback").html('<div class="alert alert-success" role="alert"><strong>Successfully reopened!: </strong><br><em>Just a moment&hellip;</em></div>')
                                $(".feedback").show()
                                
                                setTimeout(function(){window.location="jobs.php"}, 1500)                                                               
                            }
                            else
                            {
                                $(".feedback").html('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Error!:</strong> '+result+'</div>')
                                $(".feedback").show()
                            }
                        },
                        error:function()
                        {
                            $(".feedback").html('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Oops:</strong> Lost Connection</div>')
                            $(".feedback").show()
                        }
                    })
                })
                
                $("#close_job").on("click", function()
                {                    
                    $.ajax({
                        url:"controller/delete_job.php",
                        type:"POST",
                        async:true,
                        data:{rating:work_rating},
                        success:function(result)
                        {
                            if(result == "ok")
                            {
                                $(".feedback").html('<div class="alert alert-success" role="alert"><strong>Successfully closed!: </strong><br><em>Just a moment&hellip;</em></div>')
                                $(".feedback").show()
                                
                                setTimeout(function(){window.location="home.php"}, 1500)                                                               
                            }
                            else
                            {
                                $(".feedback").html('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Error!:</strong> '+result+'</div>')
                                $(".feedback").show()
                            }
                        },
                        error:function()
                        {
                            $(".feedback").html('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Oops:</strong> Lost Connection</div>')
                            $(".feedback").show()
                        }
                    })
                })
                
                $("#sb_job_create_form").on("click", function()
                {
                    $("#job_edit_form").trigger("submit")
                })
                
                $("#new-bid-sb").on("click", function()
                {
                    var cmt = $(".new-bid-comment").val()
                    var amt = $(".new-bid-amount").val()
                                        
                    $.ajax({
                        url:"controller/new_bid.php",
                        type:"POST",
                        async:true,
                        data:{cmt:cmt, amt:amt},
                        success:function(result)
                        {
                            if(result == "ok")
                            {
                                $(".feedback").html('<div class="alert alert-success" role="alert"><strong>Successful!: </strong><br>Your bid has been submitted<br><em>Just a minute&hellip;</em></div>')
                                $(".feedback").show()                               
                                
                                setTimeout(function(){window.location="jobs.php"}, 1500)
                            }
                            else
                            {
                                $(".feedback").html('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Error!:</strong> '+result+'</div>')
                                $(".feedback").show()
                            }
                        },
                        error:function()
                        {
                            $(".feedback").html('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Oops:</strong> Lost Connection</div>')
                            $(".feedback").show()
                        }
                    })
                })
                
                $("#open_edit_job").on("click", function()
                {
                    $("#edit_job_modal").modal('show')
                })
                
            })

      </script>

</html>
