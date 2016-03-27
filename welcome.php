<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <title>Welcome to KaziOnline</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
    <link href="metronic/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="metronic/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
    <link href="metronic/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="metronic/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
    <link href="metronic/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link rel="stylesheet" type="text/css" href="metronic/global/plugins/select2/select2.css"/>
    <!-- END PAGE LEVEL SCRIPTS -->
    <!-- BEGIN THEME STYLES -->
    <link href="metronic/global/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
    <link href="metronic/global/css/plugins.css" rel="stylesheet" type="text/css"/>
    <link href="metronic/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
    <link id="style_color" href="metronic/admin/layout/css/themes/darkblue.css" rel="stylesheet" type="text/css"/>
    <link href="metronic/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
    <!-- END THEME STYLES -->
    <link rel="shortcut icon" href="favicon.ico"/>

  </head>
  <body class="page-header-fixed page-quick-sidebar-over-content" style="background-color: #ffffff;">

    <br>
    <div class="container-fluid">      
        <div class="page-content">
          <!-- BEGIN PAGE HEADER-->
          <div class="page-bar">
            <ul class="page-breadcrumb">
              <li>
                <i class="fa fa-home"></i>
                <a href="index.php">Home</a>
                <i class="fa fa-angle-right"></i>
              </li>
              <li>
                <a href="#login">Login</a>
                <i class="fa fa-angle-right"></i>
              </li>
              <li>
                <a href="#u_sign_up">Freelancer Sign Up</a>
                <i class="fa fa-angle-right"></i>
              </li>
              <li>
                <a href="#c_sign_up">Company Sign Up</a>                
              </li>              
            </ul>
            
          </div>
          <!-- END PAGE HEADER-->
          <!-- BEGIN PAGE CONTENT-->
          <div class="row" id="login">
            <div class="col-md-12">
                   <div class="portlet light bordered">
                      <div class="portlet-title">
                        <div class="caption">
                          <i class="fa fa-lock font-blue-hoki"></i>
                          <span class="caption-subject font-blue-hoki bold uppercase"></span>
                          <span class="caption-helper">Login</span>
                        </div>
                        
                      </div>
                      <div class="portlet-body form">
                        <!-- BEGIN FORM-->
                        <form action="controller/login.php" class="horizontal-form" id="login-form" method="post">
                          <div class="form-body">                            
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label class="control-label">Phone / Email</label>
                                  <input type="text" id="msie" name="msie" class="form-control" placeholder="Enter phone number or email">
                                  <span class="help-block">
                                  </span>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <!--/span-->
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label class="control-label">Password</label>
                                  <input type="password" id="lastName" id="logpwd" name="passwd" class="form-control" placeholder="Enter Password">
                                  <span class="help-block">
                                  </span>
                                </div>
                              </div>
                              <!--/span-->
                            </div>
                            <!--/row-->
                            <div class="row">                              
                              <div class="col-md-6">
                                <div class="form-group">                                  
                                  <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="rem"> Remember me
                                    </label>
                                  </div>
                                </div>
                              </div>
                              <!--/span-->
                            </div>
                            <!--/row-->
                            
                          <div class="form-actions left">                            
                            <button type="submit" class="btn blue"><i class="fa fa-forward"></i> Login</button>
                            &nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:;">Forgot your password?</a>
                            <span class="help-block" id="login-feedback"></span>
                          </div>
                        </form>
                        <!-- END FORM-->
                      </div>
                    </div>
                    
                  
                  
                
              
            </div>
          </div>
          <!-- END PAGE CONTENT-->
        </div>                    
          
          <div class="row" id="u_sign_up">
            <div class="col-md-12">
                   <div class="portlet light bordered">
                      <div class="portlet-title">
                        <div class="caption">
                          <i class="fa fa-user font-blue-hoki"></i>
                          <span class="caption-subject font-blue-hoki bold uppercase"></span>
                          <span class="caption-helper">Freelancer Sign Up</span>
                        </div>
                        
                      </div>
                      <div class="portlet-body form">
                        <!-- BEGIN FORM-->
                        <form action="controller/reg.php" class="horizontal-form" id="ureg-form" method="post">
                          <div class="form-body">
                            <h3 class="form-section">Person Info</h3>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label class="control-label">Names</label>
                                  <input type="text" id="ureg-name" name="names" class="form-control" placeholder="Enter Names">
                                  <span class="help-block"></span>
                                </div>
                              </div>
                              <!--/span-->
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label class="control-label">Phone Number</label>
                                  <input type="text" id="ureg-msisdn" name="msisdn" class="form-control" placeholder="Enter Phone Number">
                                  <span class="help-block">
                                  </span>
                                </div>
                              </div>
                              <!--/span-->
                            </div>
                            <!--/row-->
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label class="control-label">Set Password</label>
                                  <input type="password" id="ureg-p1" name="passwd"  class="form-control" placeholder="Enter Password">
                                  <span class="help-block">
                                  </span>
                                </div>
                              </div>
                              <!--/span-->
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label class="control-label">Confirm Password</label>
                                  <input type="password" id="ureg-p2" name="passwd2" class="form-control" placeholder="Repeat Password">
                                  <span class="help-block">
                                  </span>
                                </div>
                              </div>
                              <!--/span-->
                            </div>
                            <!--/row-->
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label class="control-label">Location</label>
                                  <input type="text" id="ureg-loc" name="myloc" class="form-control input-location" placeholder="Where do you stay?">
                                  <span class="help-block"></span>
                                </div>
                              </div>                              
                            </div>
                            <!--/row-->
                            
                          <div class="form-actions left">                            
                            <button type="submit" class="btn blue"><i class="fa fa-check"></i> Sign Up</button>
                            &nbsp;&nbsp;&nbsp;&nbsp;<a href="#login">Already Signed Up? Login</a>
                            <span class="help-block" id="ureg-feedback"></span>
                          </div>
                        </form>
                        <!-- END FORM-->
                      </div>
                    </div>
                    
                  
                  
                
              
            </div>
          </div>
          <!-- END PAGE CONTENT-->
        </div>                    
          
          <div class="row" id="c_sign_up">
            <div class="col-md-12">
                   <div class="portlet light bordered">
                      <div class="portlet-title">
                        <div class="caption">
                          <i class="fa fa-bank font-blue-hoki"></i>
                          <span class="caption-subject font-blue-hoki bold uppercase"></span>
                          <span class="caption-helper">Company Sign Up</span>
                        </div>
                        
                      </div>
                      <div class="portlet-body form">
                        <!-- BEGIN FORM-->
                        <form action="controller/creg.php" class="horizontal-form" method="post" id="creg-form">
                          <div class="form-body">
                            <h3 class="form-section">Company Info</h3>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label class="control-label">Name</label>
                                  <input type="text" id="creg-name" name="name" class="form-control" placeholder="Enter Name">
                                  <span class="help-block"></span>
                                </div>
                              </div>
                              <!--/span-->
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label class="control-label">Company Email</label>
                                  <input type="email" id="creg-email" name="email" class="form-control" placeholder="Enter Company Email">
                                  <span class="help-block">
                                  </span>
                                </div>
                              </div>
                              <!--/span-->
                            </div>
                            <!--/row-->
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label class="control-label">Set Password</label>
                                  <input type="password" id="creg-p1" name="passwd" class="form-control" placeholder="Enter Password">
                                  <span class="help-block">
                                  </span>
                                </div>
                              </div>
                              <!--/span-->
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label class="control-label">Confirm Password</label>
                                  <input type="password" id="creg-p2" name="passwd2" class="form-control" placeholder="Repeat Password">
                                  <span class="help-block">
                                  </span>
                                </div>
                              </div>
                              <!--/span-->
                            </div>
                            <!--/row-->
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label class="control-label">Location</label>
                                  <input type="text" id="creg-loc" name="myloc" class="form-control input-location2" placeholder="Where are you based?">
                                  <span class="help-block"></span>
                                </div>
                              </div>                              
                            </div>
                            <!--/row-->
                            
                          <div class="form-actions left">                            
                            <button type="submit" class="btn blue"><i class="fa fa-check"></i> Sign Up</button>
                            &nbsp;&nbsp;&nbsp;&nbsp;<a href="#login">Already Signed Up? Login</a>
                            <span class="help-block" id="creg-feedback"></span>
                          </div>
                        </form>
                        <!-- END FORM-->
                        <!-- END FORM-->
                      </div>
                    </div>
                    
                  
                  
                
              
            </div>
          </div>
          <!-- END PAGE CONTENT-->
        </div>
      
    </div>
      
    <div class="page-footer">
	<div class="page-footer-inner">
		 2016 &copy; KaziOnline
	</div>
	<div class="scroll-to-top">
		<i class="icon-arrow-up"></i>
	</div>
    </div>      
      
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
    <script type="text/javascript" src="metronic/global/plugins/select2/select2.min.js"></script>
    <script src="metronic/global/scripts/metronic.js" type="text/javascript"></script>
    <script src="metronic/admin/layout/scripts/layout.js" type="text/javascript"></script>
    <script src="metronic/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
    <script src="metronic/admin/layout/scripts/demo.js" type="text/javascript"></script>
    <script src="metronic/admin/pages/scripts/form-samples.js"></script>
    <script src="jquery/jquery.form.js"></script>
    <script src="http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places" type="text/javascript"></script>
    <script src="bootstrap/js/index_js.js"></script>
    <script>
          $(document).ready(function ()
          {
              Metronic.init(); // init metronic core components
              Layout.init(); // init current layout
              FormSamples.init();
          });
    </script>
  </body>
</html>