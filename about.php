<?php
require_once 'neuro/Data.php';

session_start();

$me = NULL;

if(isset($_SESSION["sess_id"]))
    $me = Data::user_data($_SESSION["sess"]);
    
?>
<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>About & Contact</title>

    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">    
    <link href="bootstrap/css/metisMenu.css" rel="stylesheet">
    <link href="bootstrap/css/dashboard_custom.css" rel="stylesheet"> 
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
    
    <style>
        body
        {
            background-color: #FFFFFF;
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
          
          <?php
          if(!$me)
              echo '<a class="navbar-brand" href="index.php">KaziOnline <i class="fa fa-fw fa-home"></i></a>';
          else
              echo '<a class="navbar-brand" href="home.php">'.$me["names"].' <i class="fa fa-fw fa-home"></i></a>';
          
          ?>
        </div>

        <ul class="nav navbar-top-links navbar-right">
          <li class="dropdown">
            <a class="dropdown-toggle navlink" data-toggle="dropdown" href="#">
              <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user"> 
              <li><a href="#about">About Us</a>
              <li><a href="#contact">Contact Us</a>
              </li>
              <li class="divider"></li>
              <li><a href="help.php#faq">FAQs</a>
              </li>              
              <li><a href="help.php#terms">Terms &amp; Conditions</a>
              </li>
              <li><a href="help.php#privacy">Privacy Policy</a>
              </li>
              <?php
              if($me)
                  echo '<li class="divider"></li><li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i>Logout</a></li>';
              ?>
            </ul>            
          </li>          
        </ul>        
      </nav>

      <br>
      <div class="container">
        <div class="row">
          <div class="col-lg-10" id="about">
            <fieldset>
              <legend><h2>About Us</h2></legend>
            </fieldset>
            
          </div>
            <div class="col-md-4">
              <img class="img-responsive" style="height: 150px; width: auto;" src="img/avatars/default-avatar2.svg" alt="">
            </div>
            <div class="col-md-8">
              <h3>KaziOnline Ltd &copy;</h3>
                <p>We are a company providing freelance services to Kenya and the East African region in the future</p>
                <p>Our freelance services are simple, cheap and perfectly suited for the targeted market. Our committment is to provide freelance services that make it easier
                for all sets of people to quickly find something they can do and get paid. Unlike most other platforms, you don't have to pay us to get an uplifted profile. 
                It's your job to work your way up there by doing good work and getting good ratings from job creators, creating a bias-free platform.</p>
                <p>This platform is owned and run by KaziOnline Ltd, registered and authorized to conduct legal trade in Kenya</p>
            </div>
          <div class="col-lg-12"><br><br></div>
        </div>
        
        <div class="row"> 
          <div class="col-lg-10" id="contact">
            <fieldset>
              <legend><h2>Contact Us</h2></legend>
            </fieldset>
          </div>
            <div class="col-md-7">
                <!-- Embedded Google Map -->
                <iframe width="100%" height="400px" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?hl=en&amp;ie=UTF8&amp;ll=-1.299193, 36.790727&amp;spn=56.506174,79.013672&amp;t=m&amp;z=15&amp;output=embed"></iframe>
            </div>
            <!-- Contact Details Column -->
            <div class="col-md-5">
                <h3>Details</h3>
                <p>
                    Ngong Road<br>Nairobi, KE<br>
                </p>
                <p><i class="fa fa-phone"></i> 
                    <abbr title="Phone">P</abbr>: (254) 700290354</p>
                <p><i class="fa fa-envelope-o"></i> 
                    <abbr title="Email">E</abbr>: <a href="mailto:info@kazionline.co.ke">info@kazionline.co.ke</a>
                </p>
                <p><i class="fa fa-clock-o"></i> 
                    <abbr title="Hours">H</abbr>: Monday - Friday: 9:00 AM to 4:00 PM</p>
                <ul class="list-unstyled list-inline list-social-icons">
                    <li>
                        <a href="wwww.facebook.com/KaziOnline"><i class="fa fa-facebook-square fa-2x"></i></a>
                    </li>                    
                    <li>
                        <a href="www.twitter.com/KaziOnline"><i class="fa fa-twitter-square fa-2x"></i></a>
                    </li>                    
                </ul>
            </div>
        
        </div>
        
        <div class="row">
            <div class="col-md-8">              
                <h3>Send us a message</h3>
                <form name="sentMessage" id="contactForm" action="controller/abt_user_msg.php" method="post">
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>Names:</label>
                            <?php
                                if(!$me)
                                {
                                    echo '<input type="text" class="form-control" id="name" name="names" required data-validation-required-message="Please enter your name.">';
                                }
                                else
                                {
                                    echo '<input type="text" class="form-control" id="name" name="names" value="'.$me["names"].'" disabled>';
                                }
                                    
                            ?>
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>Email Address:</label>
                            <input type="email" class="form-control" id="email" name="email" required data-validation-required-message="Please enter your email address.">
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>Message:</label>
                            <textarea rows="6" cols="100" class="form-control" name="message" id="message" required data-validation-required-message="Please enter your message" maxlength="999" style="resize:none"></textarea>
                        </div>
                    </div>
                    <div id="success"></div>
                    <!-- For success/fail messages -->
                    <button type="submit" class="btn btn-primary">Send&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-fw fa-send"></i></button>
                </form>
            </div>

        </div>
        <!-- /.row -->

        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; KaziOnline Ltd 2016</p>
                </div>
            </div>
        </footer>
        
      </div>      
              
    </div>

  <script src="jquery/jquery-1.10.2.min.js"></script>   
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <script src="jquery/jquery.form.js"></script>
  <script src="bootstrap/js/dashboard_js.js"></script>
  <script>
      
      $(function()
      {
          var options1 =
              {
                  complete: function (response)
                  {
                      if (response.responseText != "ok")
                          {
                              $("#success").html("<span class='text-error'>"+response.responseText+"</span>")
                          }
                      else
                          {
                              $("#success").html("<span class='text-success'>Thank you very much!</span>")
                          }
                  }
              }

              $("#contactForm").ajaxForm(options1)  
      })      
        
  </script>
  
</body>
</html>