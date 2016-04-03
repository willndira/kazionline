<?php
require_once 'neuro/Jobs.php';
require_once 'neuro/Data.php';
require_once 'neuro/security.php';

session_start();

Security::check_session(TRUE);

Jobs::cleanup_tmp();
$job_categories = Data::load_job_categories();

$me = Data::user_data($_SESSION["sess_id"]);
$me_jobs_count = Data::get_me_jobs_bids_count($_SESSION["sess_id"]);
$me_trade = Data::get_me_trade($_SESSION["sess_id"]);
$my_tasks = Data::load_tasks($_SESSION["sess_id"]);
$activity_log = Data::load_activity_log($_SESSION["sess_id"]);
$notifications = Data::load_notifications($_SESSION["sess_id"]);
$trade_chart = Data::get_trade_chart($_SESSION["sess_id"]);
$chats = Data::load_chats($_SESSION["sess_id"]);
$activity_icons = Data::get_activity_types();
$trade_stats = Data::get_trade_stats($_SESSION["sess_id"]);

$_SESSION["loaded_chat_messages"] = $chats["loaded"];
$_SESSION["loaded_chat_threads"] = $chats["threads"];


//prevent possible division by 0
$me_div = 1;
if ($me_trade > 0 || $me["total_transacted"] > 0)
    $me_div = $me_trade + $me["total_transacted"];

$me_growth = ceil($me_trade / ($me_div)) * 100;

?>
<!DOCTYPE html>
<html lang="en" class="no-js">
  <!-- BEGIN HEAD -->
  <head>
    <meta charset="utf-8">
    <title><?= $me["names"] ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta content="" name="description">
    <meta content="" name="author">
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=all" rel="stylesheet" type="text/css">
    <link href="metronic/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="metronic/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css">
    <link href="metronic/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="metronic/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css">
    <link href="metronic/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css">
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGIN STYLES -->
    <link href="metronic/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css">
    <link href="metronic/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css">
    <link href="metronic/global/plugins/jqvmap/jqvmap/jqvmap.css" rel="stylesheet" type="text/css">

    <!-- END PAGE LEVEL PLUGIN STYLES -->
    <!-- BEGIN PAGE STYLES -->
    <link href="metronic/admin/pages/css/tasks.css" rel="stylesheet" type="text/css">
    <!-- END PAGE STYLES -->
    <!-- BEGIN THEME STYLES -->
    <!-- DOC: To use 'rounded corners' style just load 'components-rounded.css' stylesheet instead of 'components.css' in the below style tag -->
    <link href="metronic/global/css/components.css" id="style_components" rel="stylesheet" type="text/css">
    <link href="metronic/global/css/plugins.css" rel="stylesheet" type="text/css">
    <link href="metronic/admin/layout/css/layout.css" rel="stylesheet" type="text/css">
    <link href="metronic/admin/layout/css/themes/light.css" rel="stylesheet" type="text/css" id="style_color">
    <link href="metronic/admin/layout/css/custom.css" rel="stylesheet" type="text/css">
    <link href="bootstrap/css/select2/select2.css" rel="stylesheet" type="text/css">
    <link href="bootstrap/css/bootstrap-tagsinput.css" rel="stylesheet">
    <link href="jquery/css/uploadfile.css" rel="stylesheet">
    <!-- END THEME STYLES -->
    <link rel="shortcut icon" href="favicon.ico">
    <style type="text/css">.
      jqstooltip { position: absolute;
                   left: 0px;top: 0px;visibility: hidden;
                   background: rgb(0, 0, 0);
                   background-color: rgba(0,0,0,0.6);
                   filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);
                   -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";
                   color: white;
                   font: 10px arial, san serif;
                   text-align: left;white-space: nowrap;padding: 5px;border: 1px solid white;z-index: 10000;}
      .jqsfield { color: white;font: 10px arial, san serif;text-align: left;}
      
      .feedback
      {
          position: absolute;
          top: 250px;
          left: 300px;
          z-index: 100000;
      }
      .bootstrap-tagsinput 
      {
          width: 100% !important;
      }
      
    </style>
  </head>
  <!-- END HEAD -->
  <!-- BEGIN BODY -->  
  <body class="page-quick-sidebar-over-content page-style-square page-header-fixed"> 
    <!-- BEGIN HEADER -->
    <div class="page-header navbar navbar-fixed-top">
      <!-- BEGIN HEADER INNER -->
      <div class="page-header-inner">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
          <a href="index.html">
            <img src="metronic/admin/layout/img/logo.png" alt="logo" class="logo-default">
          </a>
          <div class="menu-toggler sidebar-toggler hide">
            <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
          </div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
        </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu">
          <ul class="nav navbar-nav pull-right">
            <!-- BEGIN NOTIFICATION DROPDOWN -->
            <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
            <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                <i class="icon-bell"></i>
                <?php
                if ($notifications["unread"] > 0)
                {
                    ?>
                    <span class="badge badge-default">
                      <?= $notifications["unread"] ?> </span>
                    <?php
                }
                ?>
              </a>
              <ul class="dropdown-menu">
                <li class="external">
                  <h3><span class="bold"><?= $notifications["unread"] ?></span> notifications</h3>                  
                </li>
                <li>
                  <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: auto;"><ul class="dropdown-menu-list scroller" style="height: auto; overflow: hidden; width: auto;" data-handle-color="#637283" data-initialized="1">

                      <?php
                      if (count($notifications["notifications"]) == 0)
                          echo '<li><a href="javascript:;">No new notifications</a></li>';

                      foreach (array_slice($notifications["notifications"], 0, 4) as $notif)
                      {
                          ?>                      
                          <li>
                            <a href="javascript:;">
                              <span class="time"><?= $notif["time"] ?></span>
                              <span class="details">
                                <span class="label label-sm label-icon label-info">
                                  <i class="<?= $activity_icons[$notif["notif_type"]] ?>"></i>
                                </span>
                                <?= substr($notif["msg"], 0, strpos($notif["msg"], "!")) ?></span>
                            </a>
                          </li>
                          <?php
                      }
                      ?>

                    </ul>
                    <div class="slimScrollBar" style="width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 121.359px; background: rgb(99, 114, 131);"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(234, 234, 234);"></div></div>
                </li>
              </ul>
            </li>
            <!-- END NOTIFICATION DROPDOWN -->
            <!-- BEGIN INBOX DROPDOWN -->
            <li class="dropdown dropdown-extended dropdown-inbox" id="header_inbox_bar">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="false">
                <i class="icon-bubbles"></i>
                <?php
                if ($chats["total_unread"] > 0)
                {
                    ?>
                    <span class="badge badge-danger chats_total_unread_1"><?= $chats["total_unread"] ?></span>
                    <?php
                } else
                {
                    echo '<span class="badge badge-danger chats_total_unread_1" style="display:none;"></span>';
                }
                ?>
              </a>
              <ul class="dropdown-menu">
                <li class="external">
                  <h3><span class="bold"><span class="chats_total_unread_2"><?= $chats["total_unread"] > 0 ? $chats["total_unread"] : "no" ?></span> new</span> messages</h3>
                  <a href="javascript:;" id="nav_see_all_chats">See all</a>
                </li>
                <li>
                  <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: auto;"><ul class="dropdown-menu-list scroller" style="height: auto; overflow: hidden; width: auto;" data-handle-color="#637283" data-initialized="1">
                          <?php
                          foreach (array_slice($chats["threads"], 0, 3) as $pal => $thread)
                          {
                              $ud = Data::user_data($pal);
                              ?>
                          <li>
                            <a href="javascript:;" class="open-conv" dx="<?= $pal ?>">
                              <span class="photo">
                                <img src="<?= $ud["avatar"] ?>" class="img-circle" alt="">
                              </span>
                              <span class="subject">
                                <span class="from">
                                  <?= $ud["names"] ?> </span>
                                <span class="time"><?= $ud["time"] ?></span>
                              </span>
                              <span class="message">
                                  <?php
                                  if (strlen($thread[0]["msg"]) > 30)
                                      echo substr($thread[0]["msg"], 0, 30) . "&hellip;";
                                  else
                                      echo $thread[0]["msg"];
                                  ?>
                              </span>
                            </a>
                          </li>
                          <?php
                      }
                      ?>                      
                    </ul><div class="slimScrollBar" style="width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 159.211px; background: rgb(99, 114, 131);"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(234, 234, 234);"></div></div>
                </li>
              </ul>
            </li>
            <!-- END INBOX DROPDOWN -->
            <!-- BEGIN TODO DROPDOWN -->
            <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
            <li class="dropdown dropdown-extended dropdown-tasks" id="header_task_bar">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="false">
                <i class="icon-calendar"></i>
                <?php
                if (count($my_tasks) > 0)
                {
                    ?>
                    <span class="badge badge-primary">
                        <?= count($my_tasks) ?>
                    </span>
                    <?php
                } else
                {
                    echo '<span class="badge badge-primary"></span>';
                }
                ?>
              </a>
              <ul class="dropdown-menu extended tasks">
                <li class="external">
                  <h3><span class="bold"><?= count($my_tasks) > 0 ? count($my_tasks) : "no" ?> pending</span> tasks</h3>                  
                </li>
                <li>
                  <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: auto;"><ul class="dropdown-menu-list scroller" style="height: auto; overflow: hidden; width: auto;" data-handle-color="#637283" data-initialized="1">
                          <?php
                          foreach (array_slice($my_tasks, 0, 3) as $task)
                          {
                              ?>
                          <li>
                            <a href="javascript:;">
                              <span class="task">
                                <span class="desc"><?= strlen($task["text"]) > 30 ? substr($task["text"], 0, 30) . "&hellip;" : $task["text"] ?></span>                            
                              </span>                          
                            </a>
                          </li>
                          <?php
                      }
                      ?>                      
                    </ul><div class="slimScrollBar" style="width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 148.284px; background: rgb(99, 114, 131);"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(234, 234, 234);"></div></div>
                </li>
              </ul>
            </li>
            <!-- END TODO DROPDOWN -->
            <!-- BEGIN USER LOGIN DROPDOWN -->
            <li class="dropdown dropdown-user">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="false">
                <img alt="" class="img-circle" id="user-prof-pic" src="<?= $me["avatar"] ?>">
                <span class="username username-hide-on-mobile">
                    <?= Data::get_disp_name($me["names"]) ?>
                </span>
                <i class="fa fa-angle-down"></i>
              </a>
              <ul class="dropdown-menu dropdown-menu-default">
                <li>
                  <a href="#">
                    <i class="icon-user"></i> My Profile </a>
                </li>                
                <li class="divider"></li>
                <li>
                  <a href="#">
                    <i class="icon-question"></i> FAQs </a>
                </li>
                <li>
                  <a href="#">
                    <i class="icon-book-open"></i> Terms &amp; Conditions </a>
                </li>
                <li>
                  <a href="#">
                    <i class="icon-shield"></i> Privacy Policy </a>
                </li>
                <li class="divider">
                </li>
                <li>
                  <a href="#">
                    <i class="icon-lock"></i> Lock Screen </a>
                </li>
                <li>
                  <a href="logout.php">
                    <i class="icon-key"></i> Log Out </a>
                </li>
              </ul>
            </li>
            <!-- END USER LOGIN DROPDOWN -->
            <!-- BEGIN QUICK SIDEBAR TOGGLER -->
            <li class="dropdown dropdown-quick-sidebar-toggler">
              <a href="javascript:;" class="dropdown-toggle" id="chats-side-swipe">
                <i class="icon-logout"></i>
              </a>
            </li>
            <!-- END QUICK SIDEBAR TOGGLER -->
          </ul>
        </div>
        <!-- END TOP NAVIGATION MENU -->
      </div>
      <!-- END HEADER INNER -->
    </div>
    <!-- END HEADER -->
    <div class="clearfix">
    </div>
    <!-- BEGIN CONTAINER -->
    <div class="page-container">
      <!-- BEGIN SIDEBAR -->
      <div class="page-sidebar-wrapper">
        <div class="page-sidebar navbar-collapse collapse" aria-expanded="false" style="height: 0px;">
          <!-- BEGIN SIDEBAR MENU -->          
          <ul class="page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
            <li class="sidebar-toggler-wrapper">
              <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
              <div class="sidebar-toggler">
              </div>
              <!-- END SIDEBAR TOGGLER BUTTON -->
            </li>            
            <li class="sidebar-search-wrapper">
              <!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->              
              <form class="sidebar-search" action="extra_search.html" method="POST">
                <a href="javascript:;" class="remove">
                  <i class="icon-close"></i>
                </a>
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="Search...">
                  <span class="input-group-btn">
                    <a href="javascript:;" class="btn submit"><i class="icon-magnifier"></i></a>
                  </span>
                </div>
              </form>
              <!-- END RESPONSIVE QUICK SEARCH FORM -->
            </li>
            <li class="start active">
              <a href="javascript:;">
                <i class="icon-home"></i>
                <span class="title">Dashboard</span>
                <span class="selected"></span>
                <span class="arrow"></span>
              </a>              
            </li>
            <li>
              <a href="javascript:;">
                <i class="icon-user"></i>
                <span class="title">Profile</span>
                <span class="arrow "></span>
              </a>              
            </li>

            <li class="heading">
              <h3 class="uppercase">Jobs</h3>
            </li>
            <li>
              <a href="javascript:;">
                <i class="icon-rocket"></i>
                <span class="title">Post</span>
                <span class="arrow "></span>
              </a>              
            </li>
            <li>
              <a href="jobs.php">
                <i class="icon-wallet"></i>
                <span class="title">Browse</span>
                <span class="arrow "></span>
              </a>              
            </li>
            <li>
              <a href="javascript:;">
                <i class="icon-docs"></i>
                <span class="title">Categories</span>
                <span class="arrow "></span>
              </a>              
            </li>
            <li>
              <a href="javascript:;">
                <i class="icon-badge"></i>
                <span class="title">Tags</span>
                <span class="arrow "></span>
              </a>              
            </li>

            <li class="heading">
              <h3 class="uppercase">More</h3>
            </li>
            <li>
              <a href="javascript:;">
                <i class="icon-notebook"></i>
                <span class="title">About Us</span>
                <span class="arrow "></span>
              </a>              
            </li>
            <li>
              <a href="javascript:;">
                <i class="icon-call-end"></i>
                <span class="title">Contact Us</span>
                <span class="arrow "></span>
              </a>              
            </li>

          </ul>
          <!-- END SIDEBAR MENU -->
        </div>
      </div>
      <!-- END SIDEBAR -->
      <!-- BEGIN CONTENT -->
      <div class="page-content-wrapper">
        <div class="page-content" style="min-height:602px">
          <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
          <div class="modal fade" id="new_job_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"  data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">New Job</h4>
                </div>
                <div class="modal-body" style="height: 500px; overflow-y: scroll;">
                  <form id="job_create_form" action="controller/new_job.php" method="post" enctype="multipart/form-data">              
                    <div class="form-group">
                      <label for="job_title">Summary <small>(30 chars)</small></label>
                      <input type="text" class="form-control" name="job_title" id="job_title" placeholder="Very brief summary" required="">
                    </div>
                    <div class="form-group">
                      <label for="job_desc">Complete description</label>
                      <textarea class="form-control" id="job_desc" name="job_desc" placeholder="Be expansive about the job, project, task etc." required="" rows="7" style="resize: none"></textarea>                    
                    </div>
                    <div class="form-group">
                      <label for="job_criteria">Category</label>
                      <select class="form-control" id="job_category" name="job_category" required="">
                        <option value="">Select Category</option>
                        <?php
                        foreach ($job_categories as $cat => $child)
                        {
                            echo '<optgroup label="' . $cat . '">';
                            foreach ($child as $sub)
                            {
                                echo '<option value="' . $sub["id"] . '">' . $sub["child"] . '</option>';
                            }
                            echo '</optgroup>';
                        }
                        ?>                
                      </select>
                    </div>

                    <div class="form-group">
                      <label for="job_tags">Additional Tags</label><br>
                      <input type="text" class="form-control" name="job_tags" id="job_tags" placeholder="e.g. Physics, CPA, C++">
                    </div>
                    <div class="form-group">
                      <strong>Attach Files</strong>&nbsp;&nbsp;<small>(if any) max <strong>25MB</strong> total</small>
                      <div id="job_attach_files"></div>
                    </div>
                    <br>
                    <fieldset>
                    <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="no_workers"><strong>How many freelancers are needed?</strong></label><br>
                            <input type="text" class="form-control" name="no_workers" id="no_workers" placeholder="e.g. 1">
                          </div>
                        </div>
                      </div>
                    </fieldset>
                    <br>
                    <fieldset>
                      <legend>Payment Model</legend>
                      <label class="radio-inline">
                        <input type="radio" name="payment_model" id="fixed_price" value="fixed" checked=""> Fixed Price
                      </label>
                      <label class="radio-inline">
                        <input type="radio" name="payment_model" id="hourly_rate" value="hourly"> Hourly Rate
                      </label>
                    </fieldset>
                      <br>
                      <div class="row" id="hours_no_panel" style="display:none">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="hours_per_week">Hours per Week</label><br>
                            <input type="text" class="form-control" name="hours_per_week" id="hours_per_week" placeholder="e.g. 20">
                            <span class="help-inline">If project duration is 3 days, put number of hours per day</span>
                          </div>
                        </div>
                      </div>                    
                    <br>
                    <fieldset>
                      <legend>Your Budget</legend>
                      <div class="form-group">
                        <label>Minimum</label>
                        <div class="input-group" style="width: 50%;">
                          <span class="input-group-addon">KSH</span>
                          <input type="text" id="job_amount_min" name="job_amount_min" class="form-control" placeholder="Minimum Budget" required="">
                          <span class="input-group-addon">.00</span>
                        </div>                  
                      </div>
                      <div class="form-group">
                        <label>Maximum</label>
                        <div class="input-group" style="width: 50%;">
                          <span class="input-group-addon">KSH</span>
                          <input type="text" id="job_amount_max" name="job_amount_max" class="form-control" placeholder="Maximum Budget" required="">
                          <span class="input-group-addon">.00</span>
                        </div>                  
                      </div>
                    </fieldset>

                    <fieldset>
                      <legend>Duration</legend>
                      <label>Pick duration</label>
                      <div class="form-group">
                        <div class="input-group" style="width: 50%;">
                          <select class="form-control" id="job_deadline" name="job_duration">
                              <optgroup label="Small-Size Projects">
                                <option value="3">3 days</option>
                                <option value="7">1 Week</option>
                                <option value="14">2 Weeks</option>
                                <option value="21">3 Weeks</option>
                                <option value="28">4 Weeks</option>                                
                            </optgroup>
                            <optgroup label="Medium-Size Projects">
                                <option value="35">5 Weeks</option>
                                <option value="42">6 Weeks</option>
                                <option value="61">2 Months</option>
                                <option value="92">3 Months</option>
                                <option value="122">4 Months</option>                                
                            </optgroup>
                            <optgroup label="Large Projects">
                                <option value="153">5 Months</option>
                                <option value="183">6 Months</option>
                                <option value="214">7 Months</option>
                                <option value="244">8 Months</option>
                                <option value="275">9 Months</option>
                                <option value="365">12 Months</option>
                                <option value="548">18 Months</option>                                
                            </optgroup>
                          </select>
                          <span class="input-group-addon"><i class="fa fa-fw fa-calendar"></i></span>
                        </div>
                      </div>                
                    </fieldset>               

                  </form>

                  <form class="feedback" style="display: none; z-index: 100000000;">
                  </form>

                </div>
                <div class="modal-footer">
                  <span id="job-feedback"></span>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                  <button type="button" class="btn btn-primary" id="sb_job_create_form">Submit</button>
                </div>
              </div>
            </div>
          </div>
          <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
          <!-- BEGIN STYLE CUSTOMIZER -->
          <div class="theme-panel hidden-xs hidden-sm">
            <div class="btn-toolbar" role="toolbar" aria-label="...">
              <a class="btn blue" href="#new_job_modal" data-toggle="modal">Post a Job. It is free</a>
              <a class="btn green-meadow" href="jobs.php">Browse Jobs</a>                
            </div>            
          </div>
          <!-- END STYLE CUSTOMIZER -->
          <!-- BEGIN PAGE HEADER-->
          <h3 class="page-title">
            Dashboard <small>activities &amp; data</small>
          </h3>
          <div class="page-bar">
            <ul class="page-breadcrumb">
              <li>
                <i class="fa fa-home"></i>
                <a href="javascript:;">Home</a>
                <i class="fa fa-angle-right"></i>
              </li>
              <li>
                <a href="#">Dashboard</a>
              </li>
            </ul>
            <div class="page-toolbar">
              <div id="dashboard-report" class="pull-right btn btn-fit-height grey-salt">                
                <i class="icon-calendar"></i>&nbsp;
                <span class="thin uppercase visible-lg-inline-block"><?= date("F j, Y") ?></span>                
              </div>
            </div>
          </div>
          <!-- END PAGE HEADER-->
          <!-- BEGIN DASHBOARD STATS -->
          <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
              <div class="dashboard-stat blue-madison">
                <div class="visual">
                  <i class="fa fa-briefcase"></i>
                </div>
                <div class="details">
                  <div class="number">
                      <?= Data::custom_number_format($me_jobs_count[0]) ?>
                  </div>
                  <div class="desc">
                    Jobs
                  </div>
                </div>
                <a class="more" href="javascript:;">
                  My jobs or my interests <i class="m-icon-swapright m-icon-white"></i>
                </a>
              </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
              <div class="dashboard-stat blue-chambray">
                <div class="visual">
                  <i class="fa fa-comments"></i>
                </div>
                <div class="details">
                  <div class="number">
                      <?= Data::custom_number_format($me_jobs_count[1]) ?>
                  </div>
                  <div class="desc">
                    Bids
                  </div>
                </div>
                <a class="more" href="javascript:;">
                  Bids to my jobs or my interests <i class="m-icon-swapright m-icon-white"></i>
                </a>
              </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
              <div class="dashboard-stat green-haze">
                <div class="visual">
                  <i class="fa fa-money"></i>
                </div>
                <div class="details">
                  <div class="number">
                    <?= Data::custom_number_format($me_trade) ?>
                  </div>
                  <div class="desc">
                    Trade
                  </div>
                </div>
                <a class="more" href="javascript:;">
                  Potential trade <i class="m-icon-swapright m-icon-white"></i>
                </a>
              </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
              <div class="dashboard-stat purple-plum">
                <div class="visual">
                  <i class="fa fa-globe"></i>
                </div>
                <div class="details">
                  <div class="number">
                    +<?= $me_growth ?>%
                  </div>
                  <div class="desc">
                    Growth
                  </div>
                </div>
                <a class="more" href="#">
                  Possible trade growth <i class="m-icon-swapright m-icon-white"></i>
                </a>
              </div>
            </div>
          </div>
          <!-- END DASHBOARD STATS -->
          <div class="clearfix">
          </div>
          <div class="row ">
            <div class="col-md-6 col-sm-6">
              <div class="portlet box blue-steel">
                <div class="portlet-title">
                  <div class="caption">
                    <i class="fa fa-bell-o"></i> Notifications
                  </div>
                  <div class="tools">
                    <a href="javascript:;" class="fullscreen" data-original-title="" title="">
                    </a>
                  </div>                  
                </div>
                <div class="portlet-body">
                  <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: auto;"><div class="scroller" style="height: auto; overflow: hidden; width: auto;" data-always-visible="1" data-rail-visible="0" data-initialized="1">
                      <ul class="feeds">
                          <?php
                          if (count($notifications["notifications"]) == 0)
                          {
                              echo '<li>
                          <div class="col1">
                            <div class="cont">                              
                              <div class="cont-col2">
                                <div class="desc">
                                  No notifications yet
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>                          
                        </li>';
                          }

                          foreach (array_slice($notifications["notifications"], 0, 9) as $notif)
                          {
                              ?>
                            <li>
                              <div class="col1">
                                <div class="cont">
                                  <div class="cont-col1">
                                    <div class="label label-sm label-info">
                                      <i class="<?= $activity_icons[$notif["notif_type"]] ?>"></i>
                                    </div>
                                  </div>
                                  <div class="cont-col2">
                                    <div class="desc">
                                        <?= $notif["msg"] ?>
                                      </span>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col2">
                                <div class="date">
                                    <?= $notif["time"] ?>
                                </div>
                              </div>
                            </li>
                            <?php
                        }
                        foreach (array_slice($notifications["notifications"], 9) as $xnotif)
                        {
                            ?>
                            <li style="display: none;" class="xtra-notif">                          
                              <div class="col1">
                                <div class="cont">
                                  <div class="cont-col1">
                                    <div class="label label-sm label-success">
                                      <i class="<?= $activity_icons[$xnotif["notif_type"]] ?>"></i>
                                    </div>
                                  </div>
                                  <div class="cont-col2">
                                    <div class="desc">
                                        <?= $xnotif["msg"] ?>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col2">
                                <div class="date">
                                    <?= $xnotif["time"] ?>
                                </div>
                              </div>
                            </li>
                            <?php
                        }
                        ?>                        
                      </ul>
                    </div><div class="slimScrollBar" style="width: 7px; position: absolute; top: 145px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px; height: 155.172px; background: rgb(187, 187, 187);"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(234, 234, 234);"></div></div>
                  <div class="scroller-footer">
                    <div class="btn-arrow-link pull-right">
                      <a href="javascript:;" id="xtranotif_toggle">See All Notifications</a>
                      <i class="icon-arrow-right"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-sm-6">
              <div class="portlet box blue">
                <div class="portlet-title">
                  <div class="caption">
                    <i class="fa fa-list"></i>Recent Activities
                  </div>
                  <div class="tools">                    
                    <a href="javascript:;" class="fullscreen" data-original-title="" title="">
                    </a>
                  </div>                  
                </div>
                <div class="portlet-body">
                  <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: auto;"><div class="scroller" style="height: auto; overflow: hidden; width: auto;" data-always-visible="1" data-rail-visible="0" data-initialized="1">
                      <ul class="feeds">
                          <?php
                          if (count($activity_log) == 0)
                          {
                              echo '<li>
                          <div class="col1">
                            <div class="cont">                              
                              <div class="cont-col2">
                                <div class="desc">
                                  No activities yet
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>                          
                        </li>';
                          }
                          foreach (array_slice($activity_log, 0, 9) as $activity)
                          {
                              ?>
                            <li>
                              <div class="col1">
                                <div class="cont">
                                  <div class="cont-col1">
                                    <div class="label label-sm label-info">
                                      <i class="<?= $activity_icons[$activity["type"]] ?>"></i>
                                    </div>
                                  </div>
                                  <div class="cont-col2">
                                    <div class="desc">
                                        <?= $activity["text"] ?>
                                      </span>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col2">
                                <div class="date">
                                    <?= $activity["time"] ?>
                                </div>
                              </div>
                            </li>
                            <?php
                        }
                        foreach (array_slice($activity_log, 9) as $xlog)
                        {
                            ?>
                            <li style="display:none" class="xlog">                          
                              <div class="col1">
                                <div class="cont">
                                  <div class="cont-col1">
                                    <div class="label label-sm label-success">
                                      <i class="<?= $activity_icons[$xlog["type"]] ?>"></i>
                                    </div>
                                  </div>
                                  <div class="cont-col2">
                                    <div class="desc">
                                        <?= $xlog["text"] ?>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col2">
                                <div class="date">
                                    <?= $xlog["time"] ?>
                                </div>
                              </div>                          
                            </li>
                            <?php
                        }
                        ?>

                      </ul>
                    </div><div class="slimScrollBar" style="width: 7px; position: absolute; top: 145px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px; height: 155.172px; background: rgb(187, 187, 187);"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(234, 234, 234);"></div></div>
                  <div class="scroller-footer">
                    <div class="btn-arrow-link pull-right">
                      <a href="javascript:;" id="xlog-toggle">See All Activities</a>
                      <i class="icon-arrow-right"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">

            <div class="col-md-6 col-sm-6">
              <div class="portlet box green-haze tasks-widget">
                <div class="portlet-title">
                  <div class="caption">
                    <i class="fa fa-check"></i>Tasks
                  </div>
                  <div class="tools">
                    <a href="javascript:;" class="fullscreen" data-original-title="" title="">
                    </a>
                  </div>                  
                </div>
                <div class="portlet-body">
                  <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: auto;"><div class="scroller" style="height: auto; overflow: hidden; width: auto;" data-always-visible="1" data-rail-visible="0" data-initialized="1">
                      <ul class="feeds">
                          <?php
                          if (count($my_tasks) == 0)
                          {
                              echo '<li>
                          <div class="col1">
                            <div class="cont">                              
                              <div class="cont-col2">
                                <div class="desc">
                                  No tasks pending
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>                          
                        </li>';
                          }
                          foreach (array_slice($my_tasks, 0, 9) as $task)
                          {
                              ?>
                            <li>
                              <div class="col1">
                                <div class="cont">
                                  <div class="cont-col1">
                                    <div class="label label-sm label-info">
                                      <i class="fa fa-wrench"></i>
                                    </div>
                                  </div>
                                  <div class="cont-col2">
                                    <div class="desc">
                                        <?= $my_tasks["text"] ?>
                                      </span>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col2">
                                <div class="date">
                                  pending
                                </div>
                              </div>
                            </li>
                            <?php
                        }
                        foreach (array_slice($my_tasks, 9) as $x_tasks)
                        {
                            ?>
                            <li style="display: none;" class="xtasks">                          
                              <div class="col1">
                                <div class="cont">
                                  <div class="cont-col1">
                                    <div class="label label-sm label-success">
                                      <i class="fa fa-wrench"></i>
                                    </div>
                                  </div>
                                  <div class="cont-col2">
                                    <div class="desc">
                                        <?= $x_tasks["text"] ?>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col2">
                                <div class="date">
                                  pending
                                </div>
                              </div>                          
                            </li>
                            <?php
                        }
                        ?>

                      </ul>
                    </div><div class="slimScrollBar" style="width: 7px; position: absolute; top: 145px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px; height: 155.172px; background: rgb(187, 187, 187);"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(234, 234, 234);"></div></div>
                  <div class="scroller-footer">
                    <div class="btn-arrow-link pull-right">
                      <a href="javascript:;" id="xtasks-toggle">See All Tasks</a>
                      <i class="icon-arrow-right"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-6 col-sm-6">
              <!-- BEGIN PORTLET-->
              <div class="portlet solid grey-cararra bordered">
                <div class="portlet-title">
                  <div class="caption">
                    <i class="fa fa-bullhorn"></i>Trade Chart
                  </div>                  
                </div>
                <div class="portlet-body">
                  <div id="site_activities_loading" style="display: none;">
                    <img src="metronic/admin/layout/img/loading.gif" alt="loading">
                  </div>
                  <div id="site_activities_content" class="display-none" style="display: block;">
                    <div id="site_activities" style="height: 228px; padding: 0px; position: relative;">
                      <canvas class="flot-base" width="123" height="250" 
                              style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 112px; height: 228px;"></canvas>
                      <div class="flot-text" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px; font-size: smaller; color: rgb(84, 84, 84);">
                        <div class="flot-x-axis flot-x1-axis xAxis x1Axis" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px; display: block;">

                          <?php
                          foreach ($trade_chart["x"]["x"] as $x_label => $x_value)
                          {
                              ?>
                              <div style="position: absolute; max-width: 44px; top: 210px; font-style: normal; font-variant: small-caps; font-weight: 400; font-stretch: normal; font-size: 10px; line-height: 18px; font-family: 'Open Sans', sans-serif; color: rgb(111, 123, 138); left: 20px; text-align: center;"><?= $x_label ?></div>
                              <?php
                          }
                          ?>                          
                        </div></div>
                      <div class="flot-y-axis flot-y1-axis yAxis y1Axis" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px; display: block;">
                          <?php
                          foreach ($trade_chart["y"] as $y_value)
                          {
                              ?>
                            <div style="position: absolute; top: 198px; font-style: normal; font-variant: small-caps; font-weight: 400; font-stretch: normal; font-size: 10px; line-height: 14px; font-family: 'Open Sans', sans-serif; color: rgb(111, 123, 138); left: 19px; text-align: right;"><?= $y_value ?></div>
                            <?php
                        }
                        ?>
                      </div></div><canvas class="flot-overlay" width="123" height="250" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 112px; height: 228px;"></canvas>
                  </div>

                </div>
              </div>
              <!-- END PORTLET-->
            </div>  

          </div>

          <div class="clearfix">
          </div>

          <div class="row">

            <div class="col-md-6 col-sm-6">
              <div class="portlet box purple-wisteria">
                <div class="portlet-title">
                  <div class="caption">
                    <i class="fa fa-calendar"></i>General Stats
                  </div>
                  <div class="tools">                    
                    <a href="" class="reload" data-original-title="" title="">
                    </a>
                    <a href="javascript:;" class="fullscreen" data-original-title="" title="">
                    </a>
                  </div>
                </div>
                <div class="portlet-body">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="easy-pie-chart">
                        <div class="number transactions" data-percent="<?= $trade_stats["ct"] ?>">
                          <span>
                            <?= $trade_stats["ct"] ?> </span>
                          %
                          <canvas height="82" width="82" style="height: 75px; width: 75px;"></canvas></div>
                        <a class="title" href="javascript:;">
                          Transactions 
                        </a>
                      </div>
                    </div>
                    <div class="margin-bottom-10 visible-sm">
                    </div>
                    <div class="col-md-4">
                      <div class="easy-pie-chart">
                        <div class="number visits" data-percent="<?= $trade_stats["cr"] ?>">
                          <span>
                            <?= $trade_stats["cr"] ?> </span>
                          %
                          <canvas height="82" width="82" style="height: 75px; width: 75px;"></canvas></div>
                        <a class="title" href="javascript:;">
                          Cash In-Flow 
                        </a>
                      </div>
                    </div>
                    <div class="margin-bottom-10 visible-sm">
                    </div>
                    <div class="col-md-4">
                      <div class="easy-pie-chart">
                        <div class="number bounce" data-percent="<?= $trade_stats["bal"] ?>">
                          <span>
                            <?= $trade_stats["bal"] ?> </span>
                          %
                          <canvas height="82" width="82" style="height: 75px; width: 75px;"></canvas></div>
                        <a class="title" href="javascript:;">
                          Balance 
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>


          <div class="clearfix">
          </div>

        </div>
      </div>
      <!-- END CONTENT -->
      <!-- BEGIN QUICK SIDEBAR -->
      <a href="javascript:;" class="page-quick-sidebar-toggler"><i class="icon-close"></i></a>
      <div class="page-quick-sidebar-wrapper">
        <div class="page-quick-sidebar">
          <div class="nav-justified">
            <ul class="nav nav-tabs nav-justified">
              <li class="active">
                <a href="#quick_sidebar_tab_1" data-toggle="tab" class="">
                  Chats
                </a>
              </li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active page-quick-sidebar-chat" id="quick_sidebar_tab_1">
                <div class="page-quick-sidebar-list" style="position: relative; overflow: hidden; width: auto; height: 538px;">
                  <div class="page-quick-sidebar-chat-users" data-rail-color="#ddd" data-wrapper-class="page-quick-sidebar-list" data-height="538" data-initialized="1" style="overflow: hidden; width: auto; height: 538px;">

                    <ul class="media-list list-items">
                      <li class="media chat-list" vec="0">
                        <div class="media-body">
                          <span class="media-heading"><i class="icon-plus"></i> NEW</span>                          
                        </div>
                      </li>
                    </ul>

                    <h3 class="list-heading">Chats</h3>                      
                    <ul class="media-list list-items" id="chats-list">                        
                        <?php
                        if ($chats["total_unread"] == 0)
                            echo ' <li>No active chats yet</li>';

                        foreach ($chats["threads"] as $pal => $conv)
                        {
                            ?>                        
                          <li class="media chat-list" vec="<?= $pal ?>">
                              <?php
                              if ($chats["pal_unread"][$pal] > 0)
                                  echo '<div class="media-status"><span class="badge badge-danger">' . $chats["pal_unread"][$pal] . '</span></div>';
                              ?>                          
                            <img class="media-object" src="<?= $chats["pal_data"][$pal]["avatar"] ?>" alt="...">
                            <div class="media-body">
                              <h4 class="media-heading"><?= $chats["pal_data"][$pal]["names"] ?></h4>                            
                            </div>
                          </li>
                          <?php
                      }
                      ?>                     

                    </ul>
                  </div>
                  <div class="slimScrollBar" style="width: 7px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px; height: 391.67px; background: rgb(187, 187, 187);"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(221, 221, 221);"></div></div>

                <?php
                foreach ($chats["threads"] as $pal => $conv)
                {
                    ?>                    
                    <div class="page-quick-sidebar-item" vec="<?= $pal ?>">
                      <div class="page-quick-sidebar-chat-user">
                        <div class="page-quick-sidebar-nav">
                          <a href="javascript:;" class="page-quick-sidebar-back-to-list"><i class="icon-arrow-left"></i>Back</a>
                        </div>
                        <div class="page-quick-sidebar-chat-user-messages" vec="<?= $pal ?>">
                            <?php
                            foreach ($conv as $c)
                            {
                                ?>
                              <div class="post <?= $c["is_me"] == 1 ? "out" : "in" ?>">
                                <img class="avatar" alt="" src="<?= $c["usr"]["avatar"] ?>"/>
                                <div class="message">
                                  <span class="arrow"></span>
                                  <a href="javascript:;" class="name"><?= $c["usr"]["names"] ?></a>
                                  <span class="datetime"><?= $c["usr"]["time"] ?></span>
                                  <span class="body">
                                      <?= $c["msg"] ?>
                                  </span>
                                </div>
                              </div>
                              <?php
                          }
                          ?>                        
                        </div>
                        <div class="page-quick-sidebar-chat-user-form">
                          <div class="input-group">
                            <input type="text" class="form-control" vec="<?= $pal ?>" placeholder="Type here ...">
                            <div class="input-group-btn">
                              <button type="button" class="btn blue" vec="<?= $pal ?>"><i class="icon-paper-clip"></i></button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php
                }
                ?>

                <div class="page-quick-sidebar-item" vec="0">
                  <div class="page-quick-sidebar-chat-user">
                    <div class="page-quick-sidebar-nav">
                      <a href="javascript:;" class="page-quick-sidebar-back-to-list" id="new_chat_back"><i class="icon-arrow-left"></i>Back</a>
                    </div>
                    <div class="page-quick-sidebar-chat-user-form">
                      <div class="input-group col-md-11">
                        <select class="form-control" vec="0" id="invite_chat" style="width: 100%; z-index: 1000000000">                          
                        </select>                        
                      </div><br>                     
                      <button type="button" vec="0" class="btn btn-sm blue" id="conf_new_chat"><i class="fa fa-fw fa-check"></i></button>
                    </div>
                  </div>
                </div>

              </div>                

            </div>

          </div>
        </div>
      </div>
    </div>
    <!-- END QUICK SIDEBAR -->
  </div>
  <!-- END CONTAINER -->
  <!-- BEGIN FOOTER -->
  <div class="page-footer">
    <div class="page-footer-inner">
      2016 &copy; KaziOnline
    </div>
    <div class="scroll-to-top" style="display: none;">
      <i class="icon-arrow-up"></i>
    </div>
  </div>
  <!-- END FOOTER -->
  <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
  <!-- BEGIN CORE PLUGINS -->
  <!--[if lt IE 9]>
  <script src="metronic/global/plugins/respond.min.js"></script>
  <script src="metronic/global/plugins/excanvas.min.js"></script> 
  <![endif]-->
  <script src="metronic/global/plugins/jquery.min.js" type="text/javascript"></script>
  <script src="metronic/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
  <!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
  <script src="metronic/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
  <script src="metronic/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
  <script src="metronic/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
  <script src="metronic/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
  <script src="metronic/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
  <script src="metronic/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
  <script src="metronic/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
  <script src="metronic/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
  <!-- END CORE PLUGINS -->
  <!-- BEGIN PAGE LEVEL PLUGINS -->
  <script src="metronic/global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js" type="text/javascript"></script>
  <script src="metronic/global/plugins/flot/jquery.flot.min.js" type="text/javascript"></script>
  <script src="metronic/global/plugins/flot/jquery.flot.resize.min.js" type="text/javascript"></script>
  <script src="metronic/global/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script>
  <script src="metronic/global/plugins/jquery.pulsate.min.js" type="text/javascript"></script>
  <script src="metronic/global/plugins/bootstrap-daterangepicker/moment.min.js" type="text/javascript"></script>
  <script src="metronic/global/plugins/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>
  <!-- IMPORTANT! fullcalendar depends on jquery-ui-1.10.3.custom.min.js for drag & drop support -->
  <script src="metronic/global/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
  <script src="metronic/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js" type="text/javascript"></script>
  <script src="metronic/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
  <!-- END PAGE LEVEL PLUGINS -->
  <!-- BEGIN PAGE LEVEL SCRIPTS -->
  <script src="metronic/global/scripts/metronic.js" type="text/javascript"></script>
  <script src="metronic/admin/layout/scripts/layout.js" type="text/javascript"></script>
  <script src="metronic/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
  <script src="metronic/admin/layout/scripts/demo.js" type="text/javascript"></script>
  <script src="metronic/admin/pages/scripts/index.js" type="text/javascript"></script>    
  <script src="metronic/admin/pages/scripts/tasks.js" type="text/javascript"></script>
  <script src="jquery/jquery.form.js"></script> 
  <script src="jquery/select2.min.js" type="text/javascript"></script>
  <script src="jquery/bootstrap3-typeahead.min.js"></script>
  <script src="bootstrap/js/bootstrap-tagsinput.js"></script>
  <script src="jquery/datepicker.js"></script>
  <script src="jquery/jquery.uploadfile.js"></script>
  <script src="jquery/fundajs.js"></script>
  <!-- END PAGE LEVEL SCRIPTS -->
  <script>
  </script>

  <!-- END JAVASCRIPTS -->

  <!-- END BODY -->
</body>
</html>