<?php
require_once 'neuro/Jobs.php';
require_once 'neuro/Data.php';
require_once 'neuro/security.php';

session_start();

Security::check_session(TRUE);

/* Jobs::cleanup_tmp();
  $job_categories = Data::load_job_categories(); */

$me = Data::user_data($_SESSION["sess_id"]);
$me_jobs_count = Data::get_me_jobs_bids_count($_SESSION["sess_id"]);
$me_trade = Data::get_me_trade($_SESSION["sess_id"]);
$my_tasks = Data::load_tasks($_SESSION["sess_id"]);
$actvity_log = Data::load_activity_log($_SESSION["sess_id"]);
$notifications = Data::load_notifications($_SESSION["sess_id"]);
$trade_chart = Data::get_trade_chart($_SESSION["sess_id"]);
$chats = Data::load_chats($_SESSION["sess_id"]);

//prevent possible division by 0
$me_div = 1;
if ($me_trade > 0 || $me["total_transacted"] > 0)
    $me_div = $me_trade + $me["total_transcated"];

$me_growth = ceil($me_trade / ($me_div)) * 100;

//echo strlen($me["names"]);
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
    <!-- END THEME STYLES -->
    <link rel="shortcut icon" href="favicon.ico">
    <style type="text/css">.
      /*jqstooltip { position: absolute;
                   left: 0px;top: 0px;visibility: hidden;
                   background: rgb(0, 0, 0);
                   background-color: rgba(0,0,0,0.6);
                   filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);
                   -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";
                   color: white;
                   font: 10px arial, san serif;
                   text-align: left;white-space: nowrap;padding: 5px;border: 1px solid white;z-index: 10000;}
      .jqsfield { color: white;font: 10px arial, san serif;text-align: left;}*/
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
          <!-- BEGIN STYLE CUSTOMIZER -->
          <div class="theme-panel hidden-xs hidden-sm">
            <div class="btn-toolbar" role="toolbar" aria-label="...">
              <a class="btn blue" href="#">Post a Job. It is free</a>
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
                <span class="thin uppercase visible-lg-inline-block">March 22, 2016</span>                
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
                    <?= Data::custom_number_format($me_trade) ?>/-
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
                    <a href="" class="reload" data-original-title="" title="">
                    </a>
                    <a href="javascript:;" class="fullscreen" data-original-title="" title="">
                    </a>
                  </div>                  
                </div>
                <div class="portlet-body">
                  <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 300px;"><div class="scroller" style="height: 300px; overflow: hidden; width: auto;" data-always-visible="1" data-rail-visible="0" data-initialized="1">
                      <ul class="feeds">
                        <li>
                          <div class="col1">
                            <div class="cont">
                              <div class="cont-col1">
                                <div class="label label-sm label-info">
                                  <i class="fa fa-check"></i>
                                </div>
                              </div>
                              <div class="cont-col2">
                                <div class="desc">
                                  You have 4 pending tasks. <span class="label label-sm label-warning ">
                                    Take action <i class="fa fa-share"></i>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col2">
                            <div class="date">
                              Just now
                            </div>
                          </div>
                        </li>
                        <li>
                          <a href="#">
                            <div class="col1">
                              <div class="cont">
                                <div class="cont-col1">
                                  <div class="label label-sm label-success">
                                    <i class="fa fa-bar-chart-o"></i>
                                  </div>
                                </div>
                                <div class="cont-col2">
                                  <div class="desc">
                                    Finance Report for year 2013 has been released.
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col2">
                              <div class="date">
                                20 mins
                              </div>
                            </div>
                          </a>
                        </li>
                        <li>
                          <div class="col1">
                            <div class="cont">
                              <div class="cont-col1">
                                <div class="label label-sm label-danger">
                                  <i class="fa fa-user"></i>
                                </div>
                              </div>
                              <div class="cont-col2">
                                <div class="desc">
                                  You have 5 pending membership that requires a quick review.
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col2">
                            <div class="date">
                              24 mins
                            </div>
                          </div>
                        </li>
                        <li>
                          <div class="col1">
                            <div class="cont">
                              <div class="cont-col1">
                                <div class="label label-sm label-info">
                                  <i class="fa fa-shopping-cart"></i>
                                </div>
                              </div>
                              <div class="cont-col2">
                                <div class="desc">
                                  New order received with <span class="label label-sm label-success">
                                    Reference Number: DR23923 </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col2">
                            <div class="date">
                              30 mins
                            </div>
                          </div>
                        </li>
                        <li>
                          <div class="col1">
                            <div class="cont">
                              <div class="cont-col1">
                                <div class="label label-sm label-success">
                                  <i class="fa fa-user"></i>
                                </div>
                              </div>
                              <div class="cont-col2">
                                <div class="desc">
                                  You have 5 pending membership that requires a quick review.
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col2">
                            <div class="date">
                              24 mins
                            </div>
                          </div>
                        </li>
                        <li>
                          <div class="col1">
                            <div class="cont">
                              <div class="cont-col1">
                                <div class="label label-sm label-default">
                                  <i class="fa fa-bell-o"></i>
                                </div>
                              </div>
                              <div class="cont-col2">
                                <div class="desc">
                                  Web server hardware needs to be upgraded. <span class="label label-sm label-default ">
                                    Overdue </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col2">
                            <div class="date">
                              2 hours
                            </div>
                          </div>
                        </li>
                        <li>
                          <a href="#">
                            <div class="col1">
                              <div class="cont">
                                <div class="cont-col1">
                                  <div class="label label-sm label-default">
                                    <i class="fa fa-briefcase"></i>
                                  </div>
                                </div>
                                <div class="cont-col2">
                                  <div class="desc">
                                    IPO Report for year 2013 has been released.
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col2">
                              <div class="date">
                                20 mins
                              </div>
                            </div>
                          </a>
                        </li>
                        <li>
                          <div class="col1">
                            <div class="cont">
                              <div class="cont-col1">
                                <div class="label label-sm label-info">
                                  <i class="fa fa-check"></i>
                                </div>
                              </div>
                              <div class="cont-col2">
                                <div class="desc">
                                  You have 4 pending tasks. <span class="label label-sm label-warning ">
                                    Take action <i class="fa fa-share"></i>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col2">
                            <div class="date">
                              Just now
                            </div>
                          </div>
                        </li>
                        <li>
                          <a href="#">
                            <div class="col1">
                              <div class="cont">
                                <div class="cont-col1">
                                  <div class="label label-sm label-danger">
                                    <i class="fa fa-bar-chart-o"></i>
                                  </div>
                                </div>
                                <div class="cont-col2">
                                  <div class="desc">
                                    Finance Report for year 2013 has been released.
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col2">
                              <div class="date">
                                20 mins
                              </div>
                            </div>
                          </a>
                        </li>
                        <li>
                          <div class="col1">
                            <div class="cont">
                              <div class="cont-col1">
                                <div class="label label-sm label-default">
                                  <i class="fa fa-user"></i>
                                </div>
                              </div>
                              <div class="cont-col2">
                                <div class="desc">
                                  You have 5 pending membership that requires a quick review.
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col2">
                            <div class="date">
                              24 mins
                            </div>
                          </div>
                        </li>
                        <li>
                          <div class="col1">
                            <div class="cont">
                              <div class="cont-col1">
                                <div class="label label-sm label-info">
                                  <i class="fa fa-shopping-cart"></i>
                                </div>
                              </div>
                              <div class="cont-col2">
                                <div class="desc">
                                  New order received with <span class="label label-sm label-success">
                                    Reference Number: DR23923 </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col2">
                            <div class="date">
                              30 mins
                            </div>
                          </div>
                        </li>
                        <li>
                          <div class="col1">
                            <div class="cont">
                              <div class="cont-col1">
                                <div class="label label-sm label-success">
                                  <i class="fa fa-user"></i>
                                </div>
                              </div>
                              <div class="cont-col2">
                                <div class="desc">
                                  You have 5 pending membership that requires a quick review.
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col2">
                            <div class="date">
                              24 mins
                            </div>
                          </div>
                        </li>
                        <li>
                          <div class="col1">
                            <div class="cont">
                              <div class="cont-col1">
                                <div class="label label-sm label-warning">
                                  <i class="fa fa-bell-o"></i>
                                </div>
                              </div>
                              <div class="cont-col2">
                                <div class="desc">
                                  Web server hardware needs to be upgraded. <span class="label label-sm label-default ">
                                    Overdue </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col2">
                            <div class="date">
                              2 hours
                            </div>
                          </div>
                        </li>
                        <li>
                          <a href="#">
                            <div class="col1">
                              <div class="cont">
                                <div class="cont-col1">
                                  <div class="label label-sm label-info">
                                    <i class="fa fa-briefcase"></i>
                                  </div>
                                </div>
                                <div class="cont-col2">
                                  <div class="desc">
                                    IPO Report for year 2013 has been released.
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col2">
                              <div class="date">
                                20 mins
                              </div>
                            </div>
                          </a>
                        </li>
                      </ul>
                    </div><div class="slimScrollBar" style="width: 7px; position: absolute; top: 145px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px; height: 155.172px; background: rgb(187, 187, 187);"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(234, 234, 234);"></div></div>
                  <div class="scroller-footer">
                    <div class="btn-arrow-link pull-right">
                      <a href="#">See All Records</a>
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
                    <a href="" class="reload" data-original-title="" title="">
                    </a>
                    <a href="javascript:;" class="fullscreen" data-original-title="" title="">
                    </a>
                  </div>                  
                </div>
                <div class="portlet-body">
                  <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 300px;"><div class="scroller" style="height: 300px; overflow: hidden; width: auto;" data-always-visible="1" data-rail-visible="0" data-initialized="1">
                      <ul class="feeds">
                        <li>
                          <div class="col1">
                            <div class="cont">
                              <div class="cont-col1">
                                <div class="label label-sm label-info">
                                  <i class="fa fa-check"></i>
                                </div>
                              </div>
                              <div class="cont-col2">
                                <div class="desc">
                                  You have 4 pending tasks. <span class="label label-sm label-warning ">
                                    Take action <i class="fa fa-share"></i>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col2">
                            <div class="date">
                              Just now
                            </div>
                          </div>
                        </li>
                        <li>
                          <a href="#">
                            <div class="col1">
                              <div class="cont">
                                <div class="cont-col1">
                                  <div class="label label-sm label-success">
                                    <i class="fa fa-bar-chart-o"></i>
                                  </div>
                                </div>
                                <div class="cont-col2">
                                  <div class="desc">
                                    Finance Report for year 2013 has been released.
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col2">
                              <div class="date">
                                20 mins
                              </div>
                            </div>
                          </a>
                        </li>
                        <li>
                          <div class="col1">
                            <div class="cont">
                              <div class="cont-col1">
                                <div class="label label-sm label-danger">
                                  <i class="fa fa-user"></i>
                                </div>
                              </div>
                              <div class="cont-col2">
                                <div class="desc">
                                  You have 5 pending membership that requires a quick review.
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col2">
                            <div class="date">
                              24 mins
                            </div>
                          </div>
                        </li>
                        <li>
                          <div class="col1">
                            <div class="cont">
                              <div class="cont-col1">
                                <div class="label label-sm label-info">
                                  <i class="fa fa-shopping-cart"></i>
                                </div>
                              </div>
                              <div class="cont-col2">
                                <div class="desc">
                                  New order received with <span class="label label-sm label-success">
                                    Reference Number: DR23923 </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col2">
                            <div class="date">
                              30 mins
                            </div>
                          </div>
                        </li>
                        <li>
                          <div class="col1">
                            <div class="cont">
                              <div class="cont-col1">
                                <div class="label label-sm label-success">
                                  <i class="fa fa-user"></i>
                                </div>
                              </div>
                              <div class="cont-col2">
                                <div class="desc">
                                  You have 5 pending membership that requires a quick review.
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col2">
                            <div class="date">
                              24 mins
                            </div>
                          </div>
                        </li>
                        <li>
                          <div class="col1">
                            <div class="cont">
                              <div class="cont-col1">
                                <div class="label label-sm label-default">
                                  <i class="fa fa-bell-o"></i>
                                </div>
                              </div>
                              <div class="cont-col2">
                                <div class="desc">
                                  Web server hardware needs to be upgraded. <span class="label label-sm label-default ">
                                    Overdue </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col2">
                            <div class="date">
                              2 hours
                            </div>
                          </div>
                        </li>
                        <li>
                          <a href="#">
                            <div class="col1">
                              <div class="cont">
                                <div class="cont-col1">
                                  <div class="label label-sm label-default">
                                    <i class="fa fa-briefcase"></i>
                                  </div>
                                </div>
                                <div class="cont-col2">
                                  <div class="desc">
                                    IPO Report for year 2013 has been released.
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col2">
                              <div class="date">
                                20 mins
                              </div>
                            </div>
                          </a>
                        </li>
                        <li>
                          <div class="col1">
                            <div class="cont">
                              <div class="cont-col1">
                                <div class="label label-sm label-info">
                                  <i class="fa fa-check"></i>
                                </div>
                              </div>
                              <div class="cont-col2">
                                <div class="desc">
                                  You have 4 pending tasks. <span class="label label-sm label-warning ">
                                    Take action <i class="fa fa-share"></i>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col2">
                            <div class="date">
                              Just now
                            </div>
                          </div>
                        </li>
                        <li>
                          <a href="#">
                            <div class="col1">
                              <div class="cont">
                                <div class="cont-col1">
                                  <div class="label label-sm label-danger">
                                    <i class="fa fa-bar-chart-o"></i>
                                  </div>
                                </div>
                                <div class="cont-col2">
                                  <div class="desc">
                                    Finance Report for year 2013 has been released.
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col2">
                              <div class="date">
                                20 mins
                              </div>
                            </div>
                          </a>
                        </li>
                        <li>
                          <div class="col1">
                            <div class="cont">
                              <div class="cont-col1">
                                <div class="label label-sm label-default">
                                  <i class="fa fa-user"></i>
                                </div>
                              </div>
                              <div class="cont-col2">
                                <div class="desc">
                                  You have 5 pending membership that requires a quick review.
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col2">
                            <div class="date">
                              24 mins
                            </div>
                          </div>
                        </li>
                        <li>
                          <div class="col1">
                            <div class="cont">
                              <div class="cont-col1">
                                <div class="label label-sm label-info">
                                  <i class="fa fa-shopping-cart"></i>
                                </div>
                              </div>
                              <div class="cont-col2">
                                <div class="desc">
                                  New order received with <span class="label label-sm label-success">
                                    Reference Number: DR23923 </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col2">
                            <div class="date">
                              30 mins
                            </div>
                          </div>
                        </li>
                        <li>
                          <div class="col1">
                            <div class="cont">
                              <div class="cont-col1">
                                <div class="label label-sm label-success">
                                  <i class="fa fa-user"></i>
                                </div>
                              </div>
                              <div class="cont-col2">
                                <div class="desc">
                                  You have 5 pending membership that requires a quick review.
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col2">
                            <div class="date">
                              24 mins
                            </div>
                          </div>
                        </li>
                        <li>
                          <div class="col1">
                            <div class="cont">
                              <div class="cont-col1">
                                <div class="label label-sm label-warning">
                                  <i class="fa fa-bell-o"></i>
                                </div>
                              </div>
                              <div class="cont-col2">
                                <div class="desc">
                                  Web server hardware needs to be upgraded. <span class="label label-sm label-default ">
                                    Overdue </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col2">
                            <div class="date">
                              2 hours
                            </div>
                          </div>
                        </li>
                        <li>
                          <a href="#">
                            <div class="col1">
                              <div class="cont">
                                <div class="cont-col1">
                                  <div class="label label-sm label-info">
                                    <i class="fa fa-briefcase"></i>
                                  </div>
                                </div>
                                <div class="cont-col2">
                                  <div class="desc">
                                    IPO Report for year 2013 has been released.
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col2">
                              <div class="date">
                                20 mins
                              </div>
                            </div>
                          </a>
                        </li>
                      </ul>
                    </div><div class="slimScrollBar" style="width: 7px; position: absolute; top: 145px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px; height: 155.172px; background: rgb(187, 187, 187);"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(234, 234, 234);"></div></div>
                  <div class="scroller-footer">
                    <div class="btn-arrow-link pull-right">
                      <a href="#">See All Records</a>
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
                    <a href="" class="reload" data-original-title="" title="">
                    </a>
                    <a href="javascript:;" class="fullscreen" data-original-title="" title="">
                    </a>
                  </div>                  
                </div>
                <div class="portlet-body">
                  <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 300px;"><div class="scroller" style="height: 300px; overflow: hidden; width: auto;" data-always-visible="1" data-rail-visible="0" data-initialized="1">
                      <ul class="feeds">
                        <li>
                          <div class="col1">
                            <div class="cont">
                              <div class="cont-col1">
                                <div class="label label-sm label-info">
                                  <i class="fa fa-check"></i>
                                </div>
                              </div>
                              <div class="cont-col2">
                                <div class="desc">
                                  You have 4 pending tasks. <span class="label label-sm label-warning ">
                                    Take action <i class="fa fa-share"></i>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col2">
                            <div class="date">
                              Just now
                            </div>
                          </div>
                        </li>
                        <li>
                          <a href="#">
                            <div class="col1">
                              <div class="cont">
                                <div class="cont-col1">
                                  <div class="label label-sm label-success">
                                    <i class="fa fa-bar-chart-o"></i>
                                  </div>
                                </div>
                                <div class="cont-col2">
                                  <div class="desc">
                                    Finance Report for year 2013 has been released.
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col2">
                              <div class="date">
                                20 mins
                              </div>
                            </div>
                          </a>
                        </li>
                        <li>
                          <div class="col1">
                            <div class="cont">
                              <div class="cont-col1">
                                <div class="label label-sm label-danger">
                                  <i class="fa fa-user"></i>
                                </div>
                              </div>
                              <div class="cont-col2">
                                <div class="desc">
                                  You have 5 pending membership that requires a quick review.
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col2">
                            <div class="date">
                              24 mins
                            </div>
                          </div>
                        </li>
                        <li>
                          <div class="col1">
                            <div class="cont">
                              <div class="cont-col1">
                                <div class="label label-sm label-info">
                                  <i class="fa fa-shopping-cart"></i>
                                </div>
                              </div>
                              <div class="cont-col2">
                                <div class="desc">
                                  New order received with <span class="label label-sm label-success">
                                    Reference Number: DR23923 </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col2">
                            <div class="date">
                              30 mins
                            </div>
                          </div>
                        </li>
                        <li>
                          <div class="col1">
                            <div class="cont">
                              <div class="cont-col1">
                                <div class="label label-sm label-success">
                                  <i class="fa fa-user"></i>
                                </div>
                              </div>
                              <div class="cont-col2">
                                <div class="desc">
                                  You have 5 pending membership that requires a quick review.
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col2">
                            <div class="date">
                              24 mins
                            </div>
                          </div>
                        </li>
                        <li>
                          <div class="col1">
                            <div class="cont">
                              <div class="cont-col1">
                                <div class="label label-sm label-default">
                                  <i class="fa fa-bell-o"></i>
                                </div>
                              </div>
                              <div class="cont-col2">
                                <div class="desc">
                                  Web server hardware needs to be upgraded. <span class="label label-sm label-default ">
                                    Overdue </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col2">
                            <div class="date">
                              2 hours
                            </div>
                          </div>
                        </li>
                        <li>
                          <a href="#">
                            <div class="col1">
                              <div class="cont">
                                <div class="cont-col1">
                                  <div class="label label-sm label-default">
                                    <i class="fa fa-briefcase"></i>
                                  </div>
                                </div>
                                <div class="cont-col2">
                                  <div class="desc">
                                    IPO Report for year 2013 has been released.
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col2">
                              <div class="date">
                                20 mins
                              </div>
                            </div>
                          </a>
                        </li>
                        <li>
                          <div class="col1">
                            <div class="cont">
                              <div class="cont-col1">
                                <div class="label label-sm label-info">
                                  <i class="fa fa-check"></i>
                                </div>
                              </div>
                              <div class="cont-col2">
                                <div class="desc">
                                  You have 4 pending tasks. <span class="label label-sm label-warning ">
                                    Take action <i class="fa fa-share"></i>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col2">
                            <div class="date">
                              Just now
                            </div>
                          </div>
                        </li>
                        <li>
                          <a href="#">
                            <div class="col1">
                              <div class="cont">
                                <div class="cont-col1">
                                  <div class="label label-sm label-danger">
                                    <i class="fa fa-bar-chart-o"></i>
                                  </div>
                                </div>
                                <div class="cont-col2">
                                  <div class="desc">
                                    Finance Report for year 2013 has been released.
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col2">
                              <div class="date">
                                20 mins
                              </div>
                            </div>
                          </a>
                        </li>
                        <li>
                          <div class="col1">
                            <div class="cont">
                              <div class="cont-col1">
                                <div class="label label-sm label-default">
                                  <i class="fa fa-user"></i>
                                </div>
                              </div>
                              <div class="cont-col2">
                                <div class="desc">
                                  You have 5 pending membership that requires a quick review.
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col2">
                            <div class="date">
                              24 mins
                            </div>
                          </div>
                        </li>
                        <li>
                          <div class="col1">
                            <div class="cont">
                              <div class="cont-col1">
                                <div class="label label-sm label-info">
                                  <i class="fa fa-shopping-cart"></i>
                                </div>
                              </div>
                              <div class="cont-col2">
                                <div class="desc">
                                  New order received with <span class="label label-sm label-success">
                                    Reference Number: DR23923 </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col2">
                            <div class="date">
                              30 mins
                            </div>
                          </div>
                        </li>
                        <li>
                          <div class="col1">
                            <div class="cont">
                              <div class="cont-col1">
                                <div class="label label-sm label-success">
                                  <i class="fa fa-user"></i>
                                </div>
                              </div>
                              <div class="cont-col2">
                                <div class="desc">
                                  You have 5 pending membership that requires a quick review.
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col2">
                            <div class="date">
                              24 mins
                            </div>
                          </div>
                        </li>
                        <li>
                          <div class="col1">
                            <div class="cont">
                              <div class="cont-col1">
                                <div class="label label-sm label-warning">
                                  <i class="fa fa-bell-o"></i>
                                </div>
                              </div>
                              <div class="cont-col2">
                                <div class="desc">
                                  Web server hardware needs to be upgraded. <span class="label label-sm label-default ">
                                    Overdue </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col2">
                            <div class="date">
                              2 hours
                            </div>
                          </div>
                        </li>
                        <li>
                          <a href="#">
                            <div class="col1">
                              <div class="cont">
                                <div class="cont-col1">
                                  <div class="label label-sm label-info">
                                    <i class="fa fa-briefcase"></i>
                                  </div>
                                </div>
                                <div class="cont-col2">
                                  <div class="desc">
                                    IPO Report for year 2013 has been released.
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col2">
                              <div class="date">
                                20 mins
                              </div>
                            </div>
                          </a>
                        </li>
                      </ul>
                    </div><div class="slimScrollBar" style="width: 7px; position: absolute; top: 145px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px; height: 155.172px; background: rgb(187, 187, 187);"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(234, 234, 234);"></div></div>
                  <div class="scroller-footer">
                    <div class="btn-arrow-link pull-right">
                      <a href="#">See All Records</a>
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
                    <i class="fa fa-bullhorn"></i>Revenue
                  </div>                  
                </div>
                <div class="portlet-body">
                  <div id="site_activities_loading" style="display: none;">
                    <img src="metronic/admin/layout/img/loading.gif" alt="loading">
                  </div>
                  <div id="site_activities_content" class="display-none" style="display: block;">
                    <div id="site_activities" style="height: 228px; padding: 0px; position: relative;">
                      <canvas class="flot-base" width="123" height="250" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 112px; height: 228px;"></canvas><div class="flot-text" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px; font-size: smaller; color: rgb(84, 84, 84);"><div class="flot-x-axis flot-x1-axis xAxis x1Axis" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px; display: block;"><div style="position: absolute; max-width: 44px; top: 210px; font-style: normal; font-variant: small-caps; font-weight: 400; font-stretch: normal; font-size: 10px; line-height: 18px; font-family: 'Open Sans', sans-serif; color: rgb(111, 123, 138); left: 20px; text-align: center;">DEC</div><div style="position: absolute; max-width: 44px; top: 210px; font-style: normal; font-variant: small-caps; font-weight: 400; font-stretch: normal; font-size: 10px; line-height: 18px; font-family: 'Open Sans', sans-serif; color: rgb(111, 123, 138); left: 29px; text-align: center;">JAN</div><div style="position: absolute; max-width: 44px; top: 210px; font-style: normal; font-variant: small-caps; font-weight: 400; font-stretch: normal; font-size: 10px; line-height: 18px; font-family: 'Open Sans', sans-serif; color: rgb(111, 123, 138); left: 37px; text-align: center;">FEB</div><div style="position: absolute; max-width: 44px; top: 210px; font-style: normal; font-variant: small-caps; font-weight: 400; font-stretch: normal; font-size: 10px; line-height: 18px; font-family: 'Open Sans', sans-serif; color: rgb(111, 123, 138); left: 42px; text-align: center;">MAR</div><div style="position: absolute; max-width: 44px; top: 210px; font-style: normal; font-variant: small-caps; font-weight: 400; font-stretch: normal; font-size: 10px; line-height: 18px; font-family: 'Open Sans', sans-serif; color: rgb(111, 123, 138); left: 52px; text-align: center;">APR</div><div style="position: absolute; max-width: 44px; top: 210px; font-style: normal; font-variant: small-caps; font-weight: 400; font-stretch: normal; font-size: 10px; line-height: 18px; font-family: 'Open Sans', sans-serif; color: rgb(111, 123, 138); left: 59px; text-align: center;">MAY</div><div style="position: absolute; max-width: 44px; top: 210px; font-style: normal; font-variant: small-caps; font-weight: 400; font-stretch: normal; font-size: 10px; line-height: 18px; font-family: 'Open Sans', sans-serif; color: rgb(111, 123, 138); left: 69px; text-align: center;">JUN</div><div style="position: absolute; max-width: 44px; top: 210px; font-style: normal; font-variant: small-caps; font-weight: 400; font-stretch: normal; font-size: 10px; line-height: 18px; font-family: 'Open Sans', sans-serif; color: rgb(111, 123, 138); left: 78px; text-align: center;">JUL</div><div style="position: absolute; max-width: 44px; top: 210px; font-style: normal; font-variant: small-caps; font-weight: 400; font-stretch: normal; font-size: 10px; line-height: 18px; font-family: 'Open Sans', sans-serif; color: rgb(111, 123, 138); left: 83px; text-align: center;">AUG</div><div style="position: absolute; max-width: 44px; top: 210px; font-style: normal; font-variant: small-caps; font-weight: 400; font-stretch: normal; font-size: 10px; line-height: 18px; font-family: 'Open Sans', sans-serif; color: rgb(111, 123, 138); left: 93px; text-align: center;">SEP</div></div><div class="flot-y-axis flot-y1-axis yAxis y1Axis" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px; display: block;"><div style="position: absolute; top: 198px; font-style: normal; font-variant: small-caps; font-weight: 400; font-stretch: normal; font-size: 10px; line-height: 14px; font-family: 'Open Sans', sans-serif; color: rgb(111, 123, 138); left: 19px; text-align: right;">0</div><div style="position: absolute; top: 149px; font-style: normal; font-variant: small-caps; font-weight: 400; font-stretch: normal; font-size: 10px; line-height: 14px; font-family: 'Open Sans', sans-serif; color: rgb(111, 123, 138); left: 7px; text-align: right;">500</div><div style="position: absolute; top: 101px; font-style: normal; font-variant: small-caps; font-weight: 400; font-stretch: normal; font-size: 10px; line-height: 14px; font-family: 'Open Sans', sans-serif; color: rgb(111, 123, 138); left: 1px; text-align: right;">1000</div><div style="position: absolute; top: 52px; font-style: normal; font-variant: small-caps; font-weight: 400; font-stretch: normal; font-size: 10px; line-height: 14px; font-family: 'Open Sans', sans-serif; color: rgb(111, 123, 138); left: 1px; text-align: right;">1500</div><div style="position: absolute; top: 3px; font-style: normal; font-variant: small-caps; font-weight: 400; font-stretch: normal; font-size: 10px; line-height: 14px; font-family: 'Open Sans', sans-serif; color: rgb(111, 123, 138); left: 1px; text-align: right;">2000</div></div></div><canvas class="flot-overlay" width="123" height="250" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 112px; height: 228px;"></canvas></div>
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
                        <div class="number transactions" data-percent="55">
                          <span>
                            +55 </span>
                          %
                          <canvas height="82" width="82" style="height: 75px; width: 75px;"></canvas></div>
                        <a class="title" href="#">
                          Transactions <i class="icon-arrow-right"></i>
                        </a>
                      </div>
                    </div>
                    <div class="margin-bottom-10 visible-sm">
                    </div>
                    <div class="col-md-4">
                      <div class="easy-pie-chart">
                        <div class="number visits" data-percent="85">
                          <span>
                            +85 </span>
                          %
                          <canvas height="82" width="82" style="height: 75px; width: 75px;"></canvas></div>
                        <a class="title" href="#">
                          New Visits <i class="icon-arrow-right"></i>
                        </a>
                      </div>
                    </div>
                    <div class="margin-bottom-10 visible-sm">
                    </div>
                    <div class="col-md-4">
                      <div class="easy-pie-chart">
                        <div class="number bounce" data-percent="46">
                          <span>
                            -46 </span>
                          %
                          <canvas height="82" width="82" style="height: 75px; width: 75px;"></canvas></div>
                        <a class="title" href="#">
                          Bounce <i class="icon-arrow-right"></i>
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
                      <li class="media chat-list" vec="1">
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
                      <li class="media chat-list" vec="1">
                        <img class="media-object" src="metronic/admin/layout/img/avatar1.jpg" alt="...">
                        <div class="media-body">
                          <h4 class="media-heading">Nick Larson</h4>
                          <div class="media-heading-sub">
                            Art Director
                          </div>
                        </div>
                      </li>
                      <li class="media chat-list" vec="1">
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
                      <li class="media chat-list" vec="3">
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
                <div class="page-quick-sidebar-item" vec="1">
                  <div class="page-quick-sidebar-chat-user">
                    <div class="page-quick-sidebar-nav">
                      <a href="javascript:;" class="page-quick-sidebar-back-to-list"><i class="icon-arrow-left"></i>Back</a>
                    </div>
                    <div class="page-quick-sidebar-chat-user-messages">
                      <div class="post out">
                        <img class="avatar" alt="" src="metronic/admin/layout/img/avatar3.jpg"/>
                        <div class="message">
                          <span class="arrow"></span>
                          <a href="#" class="name">Bob Nilson</a>
                          <span class="datetime">20:15</span>
                          <span class="body">
                            When could you send me the report ? </span>
                        </div>
                      </div>
                      <div class="post in">
                        <img class="avatar" alt="" src="metronic/admin/layout/img/avatar2.jpg"/>
                        <div class="message">
                          <span class="arrow"></span>
                          <a href="#" class="name">Ella Wong</a>
                          <span class="datetime">20:15</span>
                          <span class="body">
                            Its almost done. I will be sending it shortly </span>
                        </div>
                      </div>
                      <div class="post out">
                        <img class="avatar" alt="" src="metronic/admin/layout/img/avatar3.jpg"/>
                        <div class="message">
                          <span class="arrow"></span>
                          <a href="#" class="name">Bob Nilson</a>
                          <span class="datetime">20:15</span>
                          <span class="body">
                            Alright. Thanks! :) </span>
                        </div>
                      </div>
                      <div class="post in">
                        <img class="avatar" alt="" src="metronic/admin/layout/img/avatar2.jpg"/>
                        <div class="message">
                          <span class="arrow"></span>
                          <a href="#" class="name">Ella Wong</a>
                          <span class="datetime">20:16</span>
                          <span class="body">
                            You are most welcome. Sorry for the delay. </span>
                        </div>
                      </div>
                      <div class="post out">
                        <img class="avatar" alt="" src="metronic/admin/layout/img/avatar3.jpg"/>
                        <div class="message">
                          <span class="arrow"></span>
                          <a href="#" class="name">Bob Nilson</a>
                          <span class="datetime">20:17</span>
                          <span class="body">
                            No probs. Just take your time :) </span>
                        </div>
                      </div>
                      <div class="post in">
                        <img class="avatar" alt="" src="metronic/admin/layout/img/avatar2.jpg"/>
                        <div class="message">
                          <span class="arrow"></span>
                          <a href="#" class="name">Ella Wong</a>
                          <span class="datetime">20:40</span>
                          <span class="body">
                            Alright. I just emailed it to you. </span>
                        </div>
                      </div>
                      <div class="post out">
                        <img class="avatar" alt="" src="metronic/admin/layout/img/avatar3.jpg"/>
                        <div class="message">
                          <span class="arrow"></span>
                          <a href="#" class="name">Bob Nilson</a>
                          <span class="datetime">20:17</span>
                          <span class="body">
                            Great! Thanks. Will check it right away. </span>
                        </div>
                      </div>
                      <div class="post in">
                        <img class="avatar" alt="" src="metronic/admin/layout/img/avatar2.jpg"/>
                        <div class="message">
                          <span class="arrow"></span>
                          <a href="#" class="name">Ella Wong</a>
                          <span class="datetime">20:40</span>
                          <span class="body">
                            Please let me know if you have any comment. </span>
                        </div>
                      </div>
                      <div class="post out">
                        <img class="avatar" alt="" src="metronic/admin/layout/img/avatar3.jpg"/>
                        <div class="message">
                          <span class="arrow"></span>
                          <a href="#" class="name">Bob Nilson</a>
                          <span class="datetime">20:17</span>
                          <span class="body">
                            Sure. I will check and buzz you if anything needs to be corrected. </span>
                        </div>
                      </div>
                    </div>
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

              <div class="page-quick-sidebar-item" vec="0" style="display:none;">
                <div class="page-quick-sidebar-chat-user">
                  <div class="page-quick-sidebar-nav">
                    <a href="javascript:;" class="page-quick-sidebar-back-to-list" id="new_chat_back"><i class="icon-arrow-left"></i>Back</a>
                  </div>
                  <div class="page-quick-sidebar-chat-user-form">
                    <div class="input-group col-md-11">
                      <select class="form-control" id="invite_chat" style="width: 100%; z-index: 1000000000">                          
                      </select>                        
                    </div><br>                     
                    <button type="button" vec-out="0" class="btn btn-sm blue" id="conf_new_chat"><i class="fa fa-fw fa-check"></i></button>
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
  <script src="jquery/select2.min.js" type="text/javascript"></script>
  <!-- END PAGE LEVEL SCRIPTS -->
  <script>
        $(document).ready(function ()
        {
            Metronic.init(); // init metronic core componets
            Layout.init(); // init layout
            QuickSidebar.init(); // init quick sidebar              
            Index.init();
            Index.initCharts(); // init index page's custom scripts
            Index.initChat();
            Index.initMiniCharts();
            Tasks.initDashboardWidget();

            new_chat = {}
            sidebar_fix = 0
            sidebar_on = 0

            $("body").delegate(".chat-list", "click", function ()
            {
                var vec = $(this).attr("vec")

                $(".page-quick-sidebar-item").hide()
                $(".page-quick-sidebar-item[vec='" + vec + "']").css("display", "block")
            })

            $("#invite_chat").select2({
                placeholder: "Search Name",
                allowClear: true,
                maximumSelectionLength: 1,
                ajax: {
                    url: "controller/search_users_chat.php",
                    dataType: 'json',
                    data: function (params)
                    {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function (data, params)
                    {
                        return {
                            results: data
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup)
                {
                    return markup
                },
                minimumInputLength: 3,
                templateResult: formatHint,
                templateSelection: formatHintSelection
            })

            $("#conf_new_chat").on("click", function ()
            {
                var chat_entry = '<li class="media chat-list" vec="' + new_chat.id + '">' +
                        '<img class="media-object" src="' + new_chat.avatar + '" alt="...">' +
                        '<div class="media-body">' +
                        '<h4 class="media-heading">' + new_chat.names + '</h4>' +
                        '<div class="media-heading-sub"><span style="opacity:0">0</span></div>' +
                        '</div></li>'

                $("#chats-list").append(chat_entry)

                var chat = '<div class="page-quick-sidebar-item" vec="'+new_chat.id+'">'+
                  '<div class="page-quick-sidebar-chat-user">'+
                    '<div class="page-quick-sidebar-nav">'+
                      '<a href="javascript:;" class="page-quick-sidebar-back-to-list"><i class="icon-arrow-left"></i>Back</a>'+
                    '</div>'+
                    '<div class="page-quick-sidebar-chat-user-messages">'+
                    '<div class="page-quick-sidebar-chat-user-form">'+
                      '<div class="input-group">'+
                        '<input type="text" class="form-control send-chat-msg" vec="'+new_chat.id+'" placeholder="Type a message here...">'+
                        '<div class="input-group-btn">'+
                          '<button type="button" class="btn blue send-chat" vec-out="'+new_chat.id+'"><i class="icon-paper-clip"></i></button>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</div>'+
                '</div>'


                $("#quick_sidebar_tab_1").append(chat)
                $("#new_chat_back").trigger("click")

                sidebar_fix = 1

                QuickSidebar.init(); // init quick sidebar
            })

            $(".dropdown-quick-sidebar-toggler a").on("click", function ()
            {
                sidebar_on = sidebar_on == 0 ? 1 : 0
            })

            $("body").delegate(".send-chat", "click", function ()
            {
                var vec = $(this).attr("vec-out")                
                var msg = $(".send-chat-msg[vec="+vec+"]").val()
                
                var msg_add = '<div class="post out">'+
                        '<img class="avatar" alt="" src="metronic/admin/layout/img/avatar3.jpg">'+
                        '<div class="message">'+
                          '<span class="arrow"></span>'+
                          '<a href="#" class="name">Bob Nilson</a>'+
                          '<span class="datetime">20:15</span>'+
                          '<span class="body">'+
                            msg +' </span>'+
                        '</div>'+
                      '</div>'

                $(".page-quick-sidebar-chat-user-messages[vec=" + vec + "]").append(msg_add)

            })

            setInterval(function ()
            {
                if (sidebar_on == 0 && $("body").hasClass("page-quick-sidebar-open") && sidebar_fix > 0)
                    {
                        //alert("yebo")
                        $("body").removeClass("page-quick-sidebar-open")
                    }
                else if (sidebar_on == 1 && !$("body").hasClass("page-quick-sidebar-open") && sidebar_fix > 0)
                    {
                        $("body").addClass("page-quick-sidebar-open")
                    }
            }, 50)

        });

        function formatHint(hint)
        {
            if (hint.loading)
                return hint.names

            new_chat.names = hint.names
            new_chat.id = hint.id
            new_chat.avatar = hint.avatar

            var markup = '<div class="clearfix"><div class="col-lg-2"><img src="' + hint.avatar + '" style="width: 40px; height:auto;"></div><div class="col-lg-9">&nbsp;&nbsp;&nbsp;' + hint.names + '</div></div>'

            return markup
        }

        function formatHintSelection(hint)
        {
            return hint.names || hint.text
        }


  </script>

  <!-- END JAVASCRIPTS -->

  <!-- END BODY -->
</body>
</html>