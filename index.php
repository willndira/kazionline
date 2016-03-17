<?php
    require_once 'neuro/db_setup.php';
?>
<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>KaziOnline</title>

    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">    
    <link href="bootstrap/css/metisMenu.css" rel="stylesheet">
    <link href="bootstrap/css/dashboard_custom.css" rel="stylesheet"> 
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
    
    <style>
        body
        {
            background-color: #ccffff;
        }
        .feedback
        {
          position: absolute;
          top: 250px;
          left: 300px;
          z-index: 100000;
        }
        .welcome-text
        {
            color: #666666;
        }
    </style>

  </head>
  <body>

    <div id="wrapper">
      <!-- Navigation -->
      <nav class="navbar navbar-inverse navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="javascript:">KaziOnline</a>
        </div>

        <ul class="nav navbar-top-links navbar-right">
          <li class="dropdown">
            <a class="dropdown-toggle navlink" data-toggle="dropdown" href="#">
              <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
              <li><a href="#"><i class="fa fa-user fa-fw"></i>About Us</a>
              </li>
              <li><a href="#"><i class="fa fa-gear fa-fw"></i>FAQs</a>
              </li>
              <li class="divider"></li>
              <li><a href="#"><i class="fa fa-sign-out fa-fw"></i>Terms &amp; Conditions</a>
              </li>
            </ul>            
          </li>          
        </ul>        
      </nav>

      <br>
      <div class="container">
        <div class="row">
          <div class="col-lg-7">
            <form class="form-inline login_form" method="post" action="controller/login.php">
              <div class="panel panel-info">
                <div class="panel-heading">
                  <strong>Have an account? Log in </strong>
                </div>
                <div class="panel-body">
                  <div class="form-group">
                    <label class="sr-only" for="login_msisdn">Mobile Number</label>
                    <input type="text" class="form-control" name="login_msisdn" id="login_msisdn" placeholder="Mobile Number" required>
                  </div>
                  <div class="form-group">
                    <label class="sr-only" for="login_passwd">Password</label>
                    <input type="password" name="login_passwd" class="form-control" id="login_passwd" placeholder="Password" required>
                  </div>
                  &nbsp;&nbsp;
                  <div class="checkbox">
                    <label>
                      <input type="checkbox"> Remember me
                    </label>
                  </div>
                  &nbsp;&nbsp;&nbsp;<button type="submit" id="login" class="btn btn-primary">Log In</button>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="row">          
            <div class="col-lg-7 welcome-text">
              <h3>Work from home</h3>
            </div>
          <div class="col-lg-5">
            <form class="form-horizontal reg_form" method="post" action="controller/reg.php">
              <div class="panel panel-info">
                <div class="panel-heading">
                  <strong>Sign Me Up </strong>
                </div>
                <div class="panel-body">
                  <div class="form-group">                    
                    <div class="col-sm-10 col-lg-12">
                      <input type="text" class="form-control" name="names" id="input_names" placeholder="Names" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-10 col-lg-12">
                      <input type="text" class="form-control" name="msisdn" id="input_email" placeholder="Cell Number e.g. +254712345678" required>
                    </div>
                  </div>
                  <div class="form-group">                    
                    <div class="col-sm-10 col-lg-12">
                      <input type="password" class="form-control" name="passwd" id="input_password1" placeholder="Password" required>
                    </div>
                  </div>
                  <div class="form-group">                    
                    <div class="col-sm-10 col-lg-12">
                      <input type="password" class="form-control" name="passwd2" id="input_password_2" placeholder="Repeat Password" required>
                    </div>
                  </div>
                  <div class="form-group">                    
                    <div class="col-sm-10 col-lg-12">
                      <input type="text" class="form-control" name="myloc" id="input_location" placeholder="My Location" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-10 col-lg-12">
                      <p>Sign Up <strong><u>only if</u></strong> you accept our <a href="javascript:">Terms &amp; Conditions</a></p>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-10 col-lg-12">
                      <button type="submit" id="register" class="btn btn-primary">Sign Up</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>

      </div>

      <form class="feedback" style="display: none;">
      </form>    
    </div>


  </div>

  <script src="jquery/jquery-1.10.2.min.js"></script>   
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <script src="jquery/jquery.form.js"></script>
  <script src="bootstrap/js/dashboard_js.js"></script>
  <script src="bootstrap/js/index_js.js"></script>
  <script src="http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places" type="text/javascript"></script> 
  <script>
      
      function initialize() {
                       var input = document.querySelector("#input_location")                      
                       new google.maps.places.Autocomplete(input) 
               }
               google.maps.event.addDomListener(window, 'load', initialize)
      
    $(document).ready(function() {
        
    });
        
  </script>
</body>
</html>
