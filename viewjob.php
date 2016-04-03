<?php
require_once 'neuro/Jobs.php';
require_once 'neuro/Data.php';
require_once 'neuro/security.php';

session_start();

Security::check_session(TRUE);

$me = $_SESSION["sess_id"];

$job_id = filter_input(INPUT_GET, "job");

if ($job_id)
    $_SESSION["job_id"] = $job_id;

Jobs::job_exists($_SESSION["job_id"]);
Jobs::cleanup_tmp();

$job_data = Jobs::loadInfo();
$job_categories = Data::load_job_categories();
$me = Data::user_data($_SESSION["sess_id"]);
$my_tasks = Data::load_tasks($_SESSION["sess_id"]);
$activity_log = Data::load_activity_log($_SESSION["sess_id"]);
$notifications = Data::load_notifications($_SESSION["sess_id"]);
$chats = Data::load_chats($_SESSION["sess_id"]);
$activity_icons = Data::get_activity_types();
?>
<!DOCTYPE html>
<html lang="en" class="no-js">
  <!-- BEGIN HEAD -->
  <head>
    <meta charset="utf-8">
    <title><?= $job_data["uj_info"]["title"] ?></title>
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
    <!-- END THEME STYLES -->
    <link rel="shortcut icon" href="favicon.ico">
    <style type="text/css">
      .category-list
      {
          list-style-type: none;
          font-size: 13.5px; 
          line-height: 220%;
      }
      .category-side
      {
          border-right: #dbdbdc 1px solid;
      }
      .job-main-link
      {
          font-size: 15px;
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
                <span class="badge badge-default">
                  7 </span>
              </a>
              <ul class="dropdown-menu">
                <li class="external">
                  <h3><span class="bold">12 pending</span> notifications</h3>
                  <a href="#">view all</a>
                </li>
                <li>
                  <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 250px;"><ul class="dropdown-menu-list scroller" style="height: 250px; overflow: hidden; width: auto;" data-handle-color="#637283" data-initialized="1">
                      <li>
                        <a href="javascript:;">
                          <span class="time">just now</span>
                          <span class="details">
                            <span class="label label-sm label-icon label-success">
                              <i class="fa fa-plus"></i>
                            </span>
                            New user registered. </span>
                        </a>
                      </li>
                      <li>
                        <a href="javascript:;">
                          <span class="time">3 mins</span>
                          <span class="details">
                            <span class="label label-sm label-icon label-danger">
                              <i class="fa fa-bolt"></i>
                            </span>
                            Server #12 overloaded. </span>
                        </a>
                      </li>
                      <li>
                        <a href="javascript:;">
                          <span class="time">10 mins</span>
                          <span class="details">
                            <span class="label label-sm label-icon label-warning">
                              <i class="fa fa-bell-o"></i>
                            </span>
                            Server #2 not responding. </span>
                        </a>
                      </li>
                      <li>
                        <a href="javascript:;">
                          <span class="time">14 hrs</span>
                          <span class="details">
                            <span class="label label-sm label-icon label-info">
                              <i class="fa fa-bullhorn"></i>
                            </span>
                            Application error. </span>
                        </a>
                      </li>
                      <li>
                        <a href="javascript:;">
                          <span class="time">2 days</span>
                          <span class="details">
                            <span class="label label-sm label-icon label-danger">
                              <i class="fa fa-bolt"></i>
                            </span>
                            Database overloaded 68%. </span>
                        </a>
                      </li>
                      <li>
                        <a href="javascript:;">
                          <span class="time">3 days</span>
                          <span class="details">
                            <span class="label label-sm label-icon label-danger">
                              <i class="fa fa-bolt"></i>
                            </span>
                            A user IP blocked. </span>
                        </a>
                      </li>
                      <li>
                        <a href="javascript:;">
                          <span class="time">4 days</span>
                          <span class="details">
                            <span class="label label-sm label-icon label-warning">
                              <i class="fa fa-bell-o"></i>
                            </span>
                            Storage Server #4 not responding dfdfdfd. </span>
                        </a>
                      </li>
                      <li>
                        <a href="javascript:;">
                          <span class="time">5 days</span>
                          <span class="details">
                            <span class="label label-sm label-icon label-info">
                              <i class="fa fa-bullhorn"></i>
                            </span>
                            System Error. </span>
                        </a>
                      </li>
                      <li>
                        <a href="javascript:;">
                          <span class="time">9 days</span>
                          <span class="details">
                            <span class="label label-sm label-icon label-danger">
                              <i class="fa fa-bolt"></i>
                            </span>
                            Storage server failed. </span>
                        </a>
                      </li>
                    </ul><div class="slimScrollBar" style="width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 121.359px; background: rgb(99, 114, 131);"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(234, 234, 234);"></div></div>
                </li>
              </ul>
            </li>
            <!-- END NOTIFICATION DROPDOWN -->
            <!-- BEGIN INBOX DROPDOWN -->
            <li class="dropdown dropdown-extended dropdown-inbox" id="header_inbox_bar">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="false">
                <i class="icon-bubbles"></i>
                <span class="badge badge-danger">
                  4 </span>
              </a>
              <ul class="dropdown-menu">
                <li class="external">
                  <h3>You have <span class="bold">7 New</span> Messages</h3>
                  <a href="#">view all</a>
                </li>
                <li>
                  <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 275px;"><ul class="dropdown-menu-list scroller" style="height: 275px; overflow: hidden; width: auto;" data-handle-color="#637283" data-initialized="1">
                      <li>
                        <a href="inbox.html?a=view">
                          <span class="photo">
                            <img src="metronic/admin/layout3/img/avatar2.jpg" class="img-circle" alt="">
                          </span>
                          <span class="subject">
                            <span class="from">
                              Lisa Wong </span>
                            <span class="time">Just Now </span>
                          </span>
                          <span class="message">
                            Vivamus sed auctor nibh congue nibh. auctor nibh auctor nibh... </span>
                        </a>
                      </li>
                      <li>
                        <a href="inbox.html?a=view">
                          <span class="photo">
                            <img src="metronic/admin/layout3/img/avatar3.jpg" class="img-circle" alt="">
                          </span>
                          <span class="subject">
                            <span class="from">
                              Richard Doe </span>
                            <span class="time">16 mins </span>
                          </span>
                          <span class="message">
                            Vivamus sed congue nibh auctor nibh congue nibh. auctor nibh auctor nibh... </span>
                        </a>
                      </li>
                      <li>
                        <a href="inbox.html?a=view">
                          <span class="photo">
                            <img src="metronic/admin/layout3/img/avatar1.jpg" class="img-circle" alt="">
                          </span>
                          <span class="subject">
                            <span class="from">
                              Bob Nilson </span>
                            <span class="time">2 hrs </span>
                          </span>
                          <span class="message">
                            Vivamus sed nibh auctor nibh congue nibh. auctor nibh auctor nibh... </span>
                        </a>
                      </li>
                      <li>
                        <a href="inbox.html?a=view">
                          <span class="photo">
                            <img src="metronic/admin/layout3/img/avatar2.jpg" class="img-circle" alt="">
                          </span>
                          <span class="subject">
                            <span class="from">
                              Lisa Wong </span>
                            <span class="time">40 mins </span>
                          </span>
                          <span class="message">
                            Vivamus sed auctor 40% nibh congue nibh... </span>
                        </a>
                      </li>
                      <li>
                        <a href="inbox.html?a=view">
                          <span class="photo">
                            <img src="metronic/admin/layout3/img/avatar3.jpg" class="img-circle" alt="">
                          </span>
                          <span class="subject">
                            <span class="from">
                              Richard Doe </span>
                            <span class="time">46 mins </span>
                          </span>
                          <span class="message">
                            Vivamus sed congue nibh auctor nibh congue nibh. auctor nibh auctor nibh... </span>
                        </a>
                      </li>
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
                <span class="badge badge-primary">
                  3 </span>
              </a>
              <ul class="dropdown-menu extended tasks">
                <li class="external">
                  <h3>You have <span class="bold">12 pending</span> tasks</h3>
                  <a href="#">view all</a>
                </li>
                <li>
                  <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 275px;"><ul class="dropdown-menu-list scroller" style="height: 275px; overflow: hidden; width: auto;" data-handle-color="#637283" data-initialized="1">
                      <li>
                        <a href="javascript:;">
                          <span class="task">
                            <span class="desc">New release v1.2 </span>
                            <span class="percent">30%</span>
                          </span>
                          <span class="progress">
                            <span style="width: 40%;" class="progress-bar progress-bar-success" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"><span class="sr-only">40% Complete</span></span>
                          </span>
                        </a>
                      </li>
                      <li>
                        <a href="javascript:;">
                          <span class="task">
                            <span class="desc">Application deployment</span>
                            <span class="percent">65%</span>
                          </span>
                          <span class="progress">
                            <span style="width: 65%;" class="progress-bar progress-bar-danger" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"><span class="sr-only">65% Complete</span></span>
                          </span>
                        </a>
                      </li>
                      <li>
                        <a href="javascript:;">
                          <span class="task">
                            <span class="desc">Mobile app release</span>
                            <span class="percent">98%</span>
                          </span>
                          <span class="progress">
                            <span style="width: 98%;" class="progress-bar progress-bar-success" aria-valuenow="98" aria-valuemin="0" aria-valuemax="100"><span class="sr-only">98% Complete</span></span>
                          </span>
                        </a>
                      </li>
                      <li>
                        <a href="javascript:;">
                          <span class="task">
                            <span class="desc">Database migration</span>
                            <span class="percent">10%</span>
                          </span>
                          <span class="progress">
                            <span style="width: 10%;" class="progress-bar progress-bar-warning" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"><span class="sr-only">10% Complete</span></span>
                          </span>
                        </a>
                      </li>
                      <li>
                        <a href="javascript:;">
                          <span class="task">
                            <span class="desc">Web server upgrade</span>
                            <span class="percent">58%</span>
                          </span>
                          <span class="progress">
                            <span style="width: 58%;" class="progress-bar progress-bar-info" aria-valuenow="58" aria-valuemin="0" aria-valuemax="100"><span class="sr-only">58% Complete</span></span>
                          </span>
                        </a>
                      </li>
                      <li>
                        <a href="javascript:;">
                          <span class="task">
                            <span class="desc">Mobile development</span>
                            <span class="percent">85%</span>
                          </span>
                          <span class="progress">
                            <span style="width: 85%;" class="progress-bar progress-bar-success" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"><span class="sr-only">85% Complete</span></span>
                          </span>
                        </a>
                      </li>
                      <li>
                        <a href="javascript:;">
                          <span class="task">
                            <span class="desc">New UI release</span>
                            <span class="percent">38%</span>
                          </span>
                          <span class="progress progress-striped">
                            <span style="width: 38%;" class="progress-bar progress-bar-important" aria-valuenow="18" aria-valuemin="0" aria-valuemax="100"><span class="sr-only">38% Complete</span></span>
                          </span>
                        </a>
                      </li>
                    </ul><div class="slimScrollBar" style="width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 148.284px; background: rgb(99, 114, 131);"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(234, 234, 234);"></div></div>
                </li>
              </ul>
            </li>
            <!-- END TODO DROPDOWN -->
            <!-- BEGIN USER LOGIN DROPDOWN -->
            <li class="dropdown dropdown-user">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="false">
                <img alt="" class="img-circle" src="metronic/admin/layout/img/avatar3_small.jpg">
                <span class="username username-hide-on-mobile">
                  Nick </span>
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
              <a href="javascript:;" class="dropdown-toggle">
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
              <form class="sidebar-search" action="jobs.php" method="GET">
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
            <li>
              <a href="javascript:;">
                <i class="icon-home"></i>
                <span class="title">Dashboard</span>                
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
              <a href="javascript:;">
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
        <div class="page-content">
          <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
          <div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                  <h4 class="modal-title">Modal title</h4>
                </div>
                <div class="modal-body">
                  Widget settings form goes here
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn blue">Save changes</button>
                  <button type="button" class="btn default" data-dismiss="modal">Close</button>
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>
          <!-- /.modal -->
          <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->          
          <!-- BEGIN PAGE HEADER-->          
          <div class="page-bar">
            <ul class="page-breadcrumb">
              <li>
                <i class="fa fa-home"></i>
                <a href="home.php">Home</a>
                <i class="fa fa-angle-right"></i>
              </li>
              <li>
                <a href="jobs.php">Jobs</a>
                <i class="fa fa-angle-right"></i>
              </li>
              <li>
                <a href="javascript:;"><?= $job_data["uj_info"]["title"] ?></a>
              </li>
            </ul>            
          </div>
          <!-- END PAGE HEADER-->
          <!-- BEGIN PAGE CONTENT-->
          <div class="row">
            <div class="col-md-12 news-page blog-page">
              <div class="row">
                <div class="col-md-9 blog-tag-data">
                  <h3><?= $job_data["uj_info"]["title"] ?></h3>
                  <div class="row">
                    <div class="col-md-5"><?= $job_data["uj_info"]["category"] ?><br><br></div>
                  </div>
                  <div class="row">
                    <div class="col-md-9">
                      <ul class="list-inline sidebar-tags">
                          <?php
                          foreach ($job_data["tags"] as $tag)
                          {
                              ?>
                            <li>
                              <a href="javascript:;">
                                <i class="fa fa-tags"></i> <?= $tag["name"] ?> </a>
                            </li>
                            <?php
                        }
                        ?>

                      </ul>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-9 blog-tag-data-inner">
                      <ul class="list-inline">
                        <li>
                          <i class="fa fa-money"></i>
                          KSH <?php
                        echo number_format($job_data["uj_info"]["amount_min"], 0, ".", ",") . " - " . number_format($job_data["uj_info"]["amount_max"], 0, ".", ",");
                        if ($job_data["uj_info"]["job_type"] == 2)
                            echo "/hr";
                        ?>
                        </li>
                        <li>
                          <i class="fa fa-calendar"></i>
<?= $job_data["uj_info"]["duration"] ?>
                        </li>
                        <li>
                          <i class="fa fa-users"></i>
<?= $job_data["uj_info"]["workers_wanted"] ?> freelancers <?= "<span class='badge badge-success'>" . ($job_data["uj_info"]["workers_wanted"] - count($job_data["lucky_bidders"])) . " left</span>"; ?>
                        </li>
                        <li>
                          <i class="fa fa-comments"></i>                           
<?= count($job_data["bids"]) ?> bids
                        </li>
                      </ul>
                    </div>
                    <div class="col-md-3">
                      <ul class="list-inline">
                        <li>
                          <i class="fa fa-clock-o"></i>
                          posted <?= $job_data["posted"] ?>
                        </li>
                      </ul>
                    </div>
                  </div>
                  <div class="row"><div class="col-md-12"><br></div></div>

                  <div class="news-item-page">
                    <p>
<?= $job_data["uj_info"]["description"] ?>
                    </p>
                    <p>
                    <ul class="list-inline sidebar-tags">                      
                      <li>
                        <i class="fa fa-paperclip"></i>
                      </li>  

                      <?php
                      if (count($job_data["attachments"]) == 0)
                          echo "<li>No files attached</li>";

                      foreach ($job_data["attachments"] as $file)
                      {
                          $filename = pathinfo($file["basename"], PATHINFO_FILENAME);
                          $ext = pathinfo($file["basename"], PATHINFO_EXTENSION);
                          $nice_name = substr($filename, 0, strrpos($filename, "_")) .".".$ext;

                          echo "<li><a href='download_file.php?mode=1&id={$file["id"]}'>{$nice_name}</a></li>";
                      }
                      ?>                                            
                      
                    </ul>
                    </p>

                  </div>
                  <hr>
                  <h4>Awarded bidders</h4>
                  <div class="news-item-page">
                    <div class="row">
                      <?php
                      if(count($job_data["lucky_bidders"]) == 0)
                      {
                          echo '<div class="col-md-6">No bidders awarded yet</div>';
                      }
                      
                      foreach($job_data["lucky_bidders"] as $lucky)
                      {
                      
                      ?>
                      <div class="col-md-6">
                        <br>
                        <div class="col-md-2"><img alt="" src="<?= $lucky["avatar"] ?>" style="width: 100%; height: auto;" class="media-object avatar"></div>
                        <div class="col-md-10"><?= $lucky["names"] ?> &nbsp;&nbsp;<i class='fa fa-star' style='color:#FFD700;'></i> <?= $lucky["reputation"] ?> <br>Ksh <?=number_format($lucky["amount"], 0, ".", ",")?> for <?= $lucky["duration"] ?><br><i class="fa fa-fw fa-map-marker"></i><?= $lucky["location"] ?></div>
                      </div>
                      <?php
                      }
                      ?>
                    </div>
                    
                    <hr>
                  </div>
                  <div class="media">
                    <h3>Bids</h3>
                    <a href="#" class="pull-left">
                      <img alt="" src="metronic/admin/pages/media/blog/9.jpg" class="media-object">
                    </a>
                    <div class="media-body">
                      <h4 class="media-heading">Media heading <span>
                          5 hours ago / <a href="#">
                            Reply </a>
                        </span>
                      </h4>
                      <p>
                        Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui.
                      </p>
                      <hr>
                      <!-- Nested media object -->
                      <div class="media">
                        <a href="#" class="pull-left">
                          <img alt="" src="metronic/admin/pages/media/blog/5.jpg" class="media-object">
                        </a>
                        <div class="media-body">
                          <h4 class="media-heading">Media heading <span>
                              17 hours ago / <a href="#">
                                Reply </a>
                            </span>
                          </h4>
                          <p>
                            Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui.
                          </p>
                        </div>
                      </div>
                      <!--end media-->
                      <hr>
                      <div class="media">
                        <a href="#" class="pull-left">
                          <img alt="" src="metronic/admin/pages/media/blog/7.jpg" class="media-object">
                        </a>
                        <div class="media-body">
                          <h4 class="media-heading">Media heading <span>
                              2 days ago / <a href="#">
                                Reply </a>
                            </span>
                          </h4>
                          <p>
                            Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui.
                          </p>
                        </div>
                      </div>
                      <!--end media-->
                    </div>
                  </div>
                  <!--end media-->
                  <div class="media">
                    <a href="#" class="pull-left">
                      <img alt="" src="metronic/admin/pages/media/blog/6.jpg" class="media-object">
                    </a>
                    <div class="media-body">
                      <h4 class="media-heading">Media heading <span>
                          July 5,2013 / <a href="#">
                            Reply </a>
                        </span>
                      </h4>
                      <p>
                        Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui.
                      </p>
                    </div>
                  </div>
                  <!--end media-->
                  <hr>
                  <div class="post-comment">
                    <h3>Leave a Comment</h3>
                    <form role="form" action="#">
                      <div class="form-group">
                        <label class="control-label">Name <span class="required">
                            * </span>
                        </label>
                        <input type="text" class="form-control">
                      </div>
                      <div class="form-group">
                        <label class="control-label">Email <span class="required">
                            * </span>
                        </label>
                        <input type="text" class="form-control">
                      </div>
                      <div class="form-group">
                        <label class="control-label">Message <span class="required">
                            * </span>
                        </label>
                        <textarea class="col-md-10 form-control" rows="8"></textarea>
                      </div>
                      <button class="margin-top-20 btn blue" type="submit">Post a Comment</button>
                    </form>
                  </div>
                </div>
                <div class="col-md-3">
                  <h3>News Feeds</h3>
                  <div class="top-news">
                    <a href="javascript:;" class="btn blue">
                      <span>Open </span>                      
                      <em>3 spots remaining </em>
                      <i class="fa fa-bullseye top-news-icon"></i>
                    </a>
                    <a href="#" class="btn yellow">
                      <span>Work underway</span>                      
                      <em><i class="fa fa-users"></i> 7 freelancers working </em>
                      <i class="fa fa-spinner top-news-icon"></i>
                    </a>                    
                    <a href="#" class="btn green">
                      <span>Assesment</span>                      
                      <i class="fa fa-tags"></i>
                      Pending assesment from hirer </em>
                      <i class="fa fa-gavel top-news-icon"></i>
                    </a>
                    <hr>
                  </div>
                  <div class="space20">
                    <div class="row">
                      <div class="col-md-12">
                        <button class="btn green-seagreen col-md-10">Edit Job <i class="pull-right fa fa-pencil"></i></button>
                      </div>
                    </div>
                    <hr>
                  </div>
                  <div class="space20">
                    <div class="row">
                      <div class="col-md-12">
                        <button class="btn red-flamingo col-md-10">Delete Job <i class="pull-right fa fa-trash"></i></button>
                      </div>
                    </div>
                    <hr>
                  </div>                  
                  <div class="space20">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="col-md-12"><h4>Work completed?</h4></div>

                        <div class="col-md-12">
                          <div class="checkbox">
                            <label>Mark job as complete&nbsp;&nbsp;&nbsp;<input type="checkbox">
                            </label>
                          </div>                          
                        </div>

                        <div class="col-md-12">
                          <div id="sb-files-upload">Drop files here</div>
                          <button class="btn blue col-md-10">Confirm <i class="pull-right fa fa-check"></i></button>
                        </div>

                      </div>
                    </div>
                    <hr>
                  </div>

                  <div class="space20">
                    <div class="row">
                      <div class="col-md-12"><h4>Rate submitted work</h4></div>
                      <div class="col-md-12">
                        <button class="btn red-flamingo col-md-10">Reopen Job <i class="pull-right fa fa-refresh"></i></button>
                      </div>                        
                      <div class="col-md-12">
                        <br>
                        <button class="btn blue col-md-10">Close Job <i class="pull-right fa fa-check-circle"></i></button>
                      </div>

                    </div>
                    <hr>
                  </div>                  

                </div>
              </div>
            </div>
          </div>
          <!-- END PAGE CONTENT-->
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
                      <li class="media">
                        <div class="media-body">
                          <span class="media-heading"><i class="icon-plus"></i> NEW</span>                          
                        </div>
                      </li>
                    </ul>

                    <h3 class="list-heading">Chats</h3>
                    <ul class="media-list list-items">
                      <li class="media">
                        <div class="media-status">
                          <span class="badge badge-success">8</span>
                        </div>
                        <img class="media-object" src="metronic/admin/layout/img/avatar3.jpg" alt="...">
                        <div class="media-body">
                          <h4 class="media-heading">Bob Nilson</h4>
                          <div class="media-heading-sub">
                            Project Manager
                          </div>
                        </div>
                      </li>
                      <li class="media">
                        <img class="media-object" src="metronic/admin/layout/img/avatar1.jpg" alt="...">
                        <div class="media-body">
                          <h4 class="media-heading">Nick Larson</h4>
                          <div class="media-heading-sub">
                            Art Director
                          </div>
                        </div>
                      </li>
                      <li class="media">
                        <div class="media-status">
                          <span class="badge badge-danger">3</span>
                        </div>
                        <img class="media-object" src="metronic/admin/layout/img/avatar4.jpg" alt="...">
                        <div class="media-body">
                          <h4 class="media-heading">Deon Hubert</h4>
                          <div class="media-heading-sub">
                            CTO
                          </div>
                        </div>
                      </li>
                      <li class="media">
                        <img class="media-object" src="metronic/admin/layout/img/avatar2.jpg" alt="...">
                        <div class="media-body">
                          <h4 class="media-heading">Ella Wong</h4>
                          <div class="media-heading-sub">
                            CEO
                          </div>
                        </div>
                      </li>
                    </ul>
                  </div>
                  <div class="slimScrollBar" style="width: 7px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px; height: 391.67px; background: rgb(187, 187, 187);"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(221, 221, 221);"></div></div>
                <div class="page-quick-sidebar-item">
                  <div class="page-quick-sidebar-chat-user">
                    <div class="page-quick-sidebar-nav">
                      <a href="javascript:;" class="page-quick-sidebar-back-to-list"><i class="icon-arrow-left"></i>Back</a>
                    </div>
                    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 433px;"><div class="page-quick-sidebar-chat-user-messages" data-height="433" data-initialized="1" style="overflow: hidden; width: auto; height: 433px;">
                        <div class="post out">
                          <img class="avatar" alt="" src="metronic/admin/layout/img/avatar3.jpg">
                          <div class="message">
                            <span class="arrow"></span>
                            <a href="#" class="name">Bob Nilson</a>
                            <span class="datetime">20:15</span>
                            <span class="body">
                              When could you send me the report ? </span>
                          </div>
                        </div>
                        <div class="post in">
                          <img class="avatar" alt="" src="metronic/admin/layout/img/avatar2.jpg">
                          <div class="message">
                            <span class="arrow"></span>
                            <a href="#" class="name">Ella Wong</a>
                            <span class="datetime">20:15</span>
                            <span class="body">
                              Its almost done. I will be sending it shortly </span>
                          </div>
                        </div>
                        <div class="post out">
                          <img class="avatar" alt="" src="metronic/admin/layout/img/avatar3.jpg">
                          <div class="message">
                            <span class="arrow"></span>
                            <a href="#" class="name">Bob Nilson</a>
                            <span class="datetime">20:15</span>
                            <span class="body">
                              Alright. Thanks! :) </span>
                          </div>
                        </div>
                        <div class="post in">
                          <img class="avatar" alt="" src="metronic/admin/layout/img/avatar2.jpg">
                          <div class="message">
                            <span class="arrow"></span>
                            <a href="#" class="name">Ella Wong</a>
                            <span class="datetime">20:16</span>
                            <span class="body">
                              You are most welcome. Sorry for the delay. </span>
                          </div>
                        </div>
                        <div class="post out">
                          <img class="avatar" alt="" src="metronic/admin/layout/img/avatar3.jpg">
                          <div class="message">
                            <span class="arrow"></span>
                            <a href="#" class="name">Bob Nilson</a>
                            <span class="datetime">20:17</span>
                            <span class="body">
                              No probs. Just take your time :) </span>
                          </div>
                        </div>
                        <div class="post in">
                          <img class="avatar" alt="" src="metronic/admin/layout/img/avatar2.jpg">
                          <div class="message">
                            <span class="arrow"></span>
                            <a href="#" class="name">Ella Wong</a>
                            <span class="datetime">20:40</span>
                            <span class="body">
                              Alright. I just emailed it to you. </span>
                          </div>
                        </div>
                        <div class="post out">
                          <img class="avatar" alt="" src="metronic/admin/layout/img/avatar3.jpg">
                          <div class="message">
                            <span class="arrow"></span>
                            <a href="#" class="name">Bob Nilson</a>
                            <span class="datetime">20:17</span>
                            <span class="body">
                              Great! Thanks. Will check it right away. </span>
                          </div>
                        </div>
                        <div class="post in">
                          <img class="avatar" alt="" src="metronic/admin/layout/img/avatar2.jpg">
                          <div class="message">
                            <span class="arrow"></span>
                            <a href="#" class="name">Ella Wong</a>
                            <span class="datetime">20:40</span>
                            <span class="body">
                              Please let me know if you have any comment. </span>
                          </div>
                        </div>
                        <div class="post out">
                          <img class="avatar" alt="" src="metronic/admin/layout/img/avatar3.jpg">
                          <div class="message">
                            <span class="arrow"></span>
                            <a href="#" class="name">Bob Nilson</a>
                            <span class="datetime">20:17</span>
                            <span class="body">
                              Sure. I will check and buzz you if anything needs to be corrected. </span>
                          </div>
                        </div>
                      </div><div class="slimScrollBar" style="width: 7px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px; height: 272.513px; background: rgb(187, 187, 187);"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(234, 234, 234);"></div></div>
                    <div class="page-quick-sidebar-chat-user-form">
                      <div class="input-group">
                        <input type="text" class="form-control" placeholder="Type a message here...">
                        <div class="input-group-btn">
                          <button type="button" class="btn blue"><i class="icon-paper-clip"></i></button>
                        </div>
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
    <!-- END PAGE LEVEL SCRIPTS -->
    <script>
          jQuery(document).ready(function ()
          {
              Metronic.init(); // init metronic core componets
              Layout.init(); // init layout
              QuickSidebar.init(); // init quick sidebar              
              Index.init();
              Index.initDashboardDaterange();
              Index.initJQVMAP(); // init index page's custom scripts
              Index.initCalendar(); // init index page's custom scripts
              Index.initCharts(); // init index page's custom scripts
              Index.initChat();
              Index.initMiniCharts();
              Tasks.initDashboardWidget();
          });
    </script>
    <!-- END JAVASCRIPTS -->

    <!-- END BODY -->
  </body>
</html>