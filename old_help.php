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

    <title>Help & Policy</title>

    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">    
    <link href="bootstrap/css/metisMenu.css" rel="stylesheet">
    <link href="bootstrap/css/dashboard_custom.css" rel="stylesheet"> 
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
    
    <style>
        body
        {
                       
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
        .panel-yellow .accordion-toggle
        {
            color: #FFFFFF;
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
              <li><a href="about.php#about">About Us</a>
              <li><a href="about.php#contact">Contact Us</a>
              </li>
              <li class="divider"></li>
              <li><a href="#faq">FAQs</a>
              </li>              
              <li><a href="#terms">Terms &amp; Conditions</a>
              </li>
              <li><a href="#privacy">Privacy Policy</a>
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
        

        <!-- Page Heading/Breadcrumbs -->
        <div class="row">
            <div class="col-lg-12" id="faq">
                <h3 class="page-header">Help &amp; Policy </h3>
                <ol class="breadcrumb">
                    <li><a href="javascript:;">Help</a>
                    </li>
                    <li class="active">FAQ</li>
                </ol>
            </div>
        </div>
        <!-- /.row -->

        <!-- Content Row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel-group" id="accordion">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">What is KaziOnline?</a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse">
                            <div class="panel-body">
                                KaziOnline is a Kenyan freelance system. It's created for people who want extra cash, quick money, or even those who prefer working from home.
                                The payment systems used are MPESA and Airtel Money
                            </div>
                        </div>
                    </div>
                    <!-- /.panel -->
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">How do I join? Is it free?</a>
                            </h4>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse">
                            <div class="panel-body">
                              Just visit our <a href="index.php">Start</a> page. You will see the login and sign up boxes. And joining is absolutely free
                            </div>
                        </div>
                    </div>
                    <!-- /.panel -->
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">How do I look and apply for jobs?</a>
                            </h4>
                        </div>
                        <div id="collapseThree" class="panel-collapse collapse">
                            <div class="panel-body">
                              On your home page, you will see a list of the latest jobs. You can search through them and choose the one that Interests you
                            </div>
                        </div>
                    </div>
                    <!-- /.panel -->
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseFour">I have something that I need done. How do I post it? Will I be charged?</a>
                            </h4>
                        </div>
                        <div id="collapseFour" class="panel-collapse collapse">
                            <div class="panel-body">
                              On your home, you will see a prompt box at the top asking if you have something that you need done. Click on the <strong>Post It</strong>
                              button. A dialog will appear, containing fields that ask for information about the task you have, when you need it done, and your budget. All fields except file attachments are <strong>mandatory</strong>.
                              Posting a job is absolutely free. Once posted, interested people will be free to bid for the job
                              
                            </div>
                        </div>
                    </div>
                    <!-- /.panel -->
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseFive">How will I see bids for my job, and how do I approve one's bid?</a>
                            </h4>
                        </div>
                        <div id="collapseFive" class="panel-collapse collapse">
                            <div class="panel-body">
                              On your home page, check the docke section on your left, on the tab <strong>Jobs I posted</strong>. Click on the job and you will be taken to the job's information page.<br>
                              Here you will see the bids made on the center of your screen. You can click on <strong>Accept</strong> button.
                            </div>
                        </div>
                    </div>
                    <!-- /.panel -->
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseSix">Where do I get charged?</a>
                            </h4>
                        </div>
                        <div id="collapseSix" class="panel-collapse collapse">
                            <div class="panel-body">
                              On accepting a person's bid, the amount requested by the bidder is deducted from your account, for assurance that you are able to fully pay a bidder on job completion.<br>
                              The money is kept somewhere temporarily. Once you receive the job done by bidder, you can close the job and the money is sent to the bidder's account. 10% is deducted as service commission
                            </div>
                        </div>
                    </div>
                    <!-- /.panel -->
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseSeven">How do I submit my work to the job owner?</a>
                            </h4>
                        </div>
                        <div id="collapseSeven" class="panel-collapse collapse">
                            <div class="panel-body">
                              Once your bid has been accepted, you can always submit the job whenever you want.
                              Just visit that particular job's page. On the extreme right of the page, you will see an upload area where you can upload your work for inspection by the job owner
                            </div>
                        </div>
                    </div>
                    <!-- /.panel -->
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseEight">How do I see work submitted by the bidder?</a>
                            </h4>
                        </div>
                        <div id="collapseEight" class="panel-collapse collapse">
                            <div class="panel-body">
                              You can see submitted work, if any, after accepting a bid from a bidder. If so, visit the particular job's page and on the extreme right, on the <strong>submitted work</strong>
                              section, you can download and inspect the work submitted by the bidder
                            </div>
                        </div>
                    </div>
                    <!-- /.panel -->
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseNine">What if my work was poorly done? How do I rate submitted work?</a>
                            </h4>
                        </div>
                        <div id="collapseNine" class="panel-collapse collapse">
                            <div class="panel-body">
                              Once the work has been submitted, you can rate the work recieved. Rate out of 5 starts the work received.
                              If the work was poorly done, (1-2 stars), you have the option to reopen the job for other bidders to bid
                            </div>
                        </div>
                    </div>
                    <!-- /.panel -->
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseTen">Can I contact my bidder directly?</a>
                            </h4>
                        </div>
                        <div id="collapseTen" class="panel-collapse collapse">
                            <div class="panel-body">
                              Direct contacting is not allowed. This is so that we can protect both parties from fraud, and ensure the job owners get their job done 
                              transparently and perfectly, and bidders get fully paid
                            </div>
                        </div>
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.panel-group -->
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        
        <!-- Page Heading/Breadcrumbs -->
        <div class="row">
            <div class="col-lg-12" id="terms">
                <ol class="breadcrumb">
                    <li><a href="javascript:;">Help</a>
                    </li>
                    <li class="active">Terms &amp; Conditions</li>
                </ol>
            </div>
        </div>
        <!-- /.row -->

        <!-- Content Row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel-group" id="accordion">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseT1">1. Introduction</a>
                            </h4>
                        </div>
                        <div id="collapseT1" class="panel-collapse collapse">
                            <div class="panel-body">
                              <ol>
                                <li>These terms and conditions shall govern your use of our website.</li>
                                <li>By using our website, you accept these terms and conditions in full; accordingly, if you disagree with these terms and conditions or any part of these terms and conditions, you must not use our website.</li>
                                <li>If you register with our website, submit any material to our website or use any of our website services, we will ask you to expressly agree to these terms and conditions.</li>
                                <li>You must be at least 16 years of age to use our website; by using our website or agreeing to these terms and conditions, you warrant and represent to us that you are at least 16 years of age.</li>
                                <li>Our website uses cookies; by using our website or agreeing to these terms and conditions, you consent to our use of cookies in accordance with the terms of our privacy policy.</li>
                              </ol>
                              	
                            </div>
                        </div>
                    </div>
                    <!-- /.panel -->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseT2">2. Copyright notice</a>
                            </h4>
                        </div>
                        <div id="collapseT2" class="panel-collapse collapse">
                            <div class="panel-body">
                                <ol>
                                  <li>Copyright &copy; 2016 KaziOnline Ltd.</li>
                                <li>Subject to the express provisions of these terms and conditions, we, together with our licensors, own and control all the copyright and other intellectual property rights in our website and the material on our website; and</li>
                                <li>all the copyright and other intellectual property rights in our website and the material on our website are reserved.</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /.panel -->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseT3">3. Licence to use website</a>
                            </h4>
                        </div>
                        <div id="collapseT3" class="panel-collapse collapse">
                            <div class="panel-body">
                              <ol>
                              <li>You may:
                                <ol>
                                <li>view pages from our website in a web browser;</li>
                                <li>download pages from our website for caching in a web browser;</li>
                                <li>print pages from our website;</li>
                                <li>use our website services by means of a web browser</li>
                                </ol>
                                subject to the other provisions of these terms and conditions.
                              </li>
                              <li>Except as expressly permitted by Section 4.1 or the other provisions of these terms and conditions, you must not download any material from our website or save any such material to your computer.</li>
                              <li>You may only use our website for [your own personal and business purposes], and you must not use our website for any other purposes.</li>
                              <li>Except as expressly permitted by these terms and conditions, you must not edit or otherwise modify any material on our website.</li>
                              <li>Unless you own or control the relevant rights in the material, you must not:
                                <ol>
                                <li>republish material from our website (including republication on another website);</li>
                                <li>sell, rent or sub-license material from our website;</li>
                                <li>show any material from our website in public;</li>
                                <li>redistribute material from our website.</li>
                                </ol>                                    
                              </li>
                              <li>We reserve the right to restrict access to areas of our website, or indeed our whole website, at our discretion; you must not circumvent or bypass, or attempt to circumvent or bypass, any access restriction measures on our website.</li>                              
                              </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /.panel -->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseT4">4. Acceptable use</a>
                            </h4>
                        </div>
                        <div id="collapseT4" class="panel-collapse collapse">
                            <div class="panel-body">
                              <ol>
                                <li>
                                  You must not:
                                  <ol>
                                    <li>use our website in any way or take any action that causes, or may cause, damage to the website or impairment of the performance, availability or accessibility of the website;</li>
                                    <li>use our website in any way that is unlawful, illegal, fraudulent or harmful, or in connection with any unlawful, illegal, fraudulent or harmful purpose or activity;</li>
                                    <li>use our website to copy, store, host, transmit, send, use, publish or distribute any material which consists of (or is linked to) any spyware, computer virus, Trojan horse, worm, keystroke logger, rootkit or other malicious computer software;</li>
                                    <li>conduct any systematic or automated data collection activities (including without limitation scraping, data mining, data extraction and data harvesting) on or in relation to our website without our express written consent;</li>
                                    <li>access or otherwise interact with our website using any robot, spider or other automated means, except for the purpose of search engine indexing;</li>
                                    <li>violate the directives set out in the robots.txt file for our website; or</li>
                                    <li>use data collected from our website for any direct marketing activity (including without limitation email marketing, SMS marketing, telemarketing and direct mailing).</li>
                                  </ol>
                                </li>
                                <li>You must not use data collected from our website to contact individuals, companies or other persons or entities.</li>
                                <li>You must ensure that all the information you supply to us through our website, or in relation to our website, is true, accurate, current, complete and non-misleading.</li>
                              </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /.panel -->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                              <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseT5">5. Registration &amp; Accounts</a>
                            </h4>
                        </div>
                        <div id="collapseT5" class="panel-collapse collapse">
                            <div class="panel-body">
                              <ol>
                                <li>To be eligible for an individual account on our website under this Section 6, you must be at least 16 years of age and resident in East Africa.</li>
                                <li>You may register for an account with our website by completing and submitting the account registration form on our website.</li>
                                <li>You must not allow any other person to use your account to access the website.</li>
                                <li>You must notify us in writing immediately if you become aware of any unauthorised use of your account.</li>
                                <li>You must not use any other person's account to access the website, unless you have that person's express permission to do so.</li>                                
                              </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /.panel -->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseT6">6. User login details</a>
                            </h4>
                        </div>
                        <div id="collapseT6" class="panel-collapse collapse">
                            <div class="panel-body">
                              <ol>
                                <li>If you register for an account with our website you will be asked to provide your names, location, mobile phone number and choose a password.</li>
                                <li>The personal details given must not be liable to mislead and must comply with the content rules set out in Section 10; you must not use your account or user ID for or in connection with the impersonation of any person. </li>
                                <li>You must keep your password confidential.</li>
                                <li>You must notify us in writing immediately if you become aware of any disclosure of your password.</li>
                                <li>You are responsible for any activity on our website arising out of any failure to keep your password confidential, and may be held liable for any losses arising out of such a failure.</li>                                
                              </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /.panel -->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseT7">7. Cancellation and suspension of account</a>
                            </h4>
                        </div>
                        <div id="collapseT7" class="panel-collapse collapse">
                            <div class="panel-body">
                              <ol>
                                <li>We may:
                                    <ol>                                    
                                    <li>suspend your account;</li>
                                    <li>cancel your account; and/or</li>
                                    <li>edit your account details.</li>                                
                                    </ol>
                                  at any time in our sole discretion without notice.
                                </li>
                                <li>You may cancel your account on our website on your personal profile pade on the website.</li>
                              </ol>                                
                            </div>
                        </div>
                    </div>
                    <!-- /.panel -->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseT8">8. Your content: licence</a>
                            </h4>
                        </div>
                        <div id="collapseT8" class="panel-collapse collapse">
                            <div class="panel-body">
                                <ol>                                    
                                <li>In these terms and conditions, "your content" means all works and materials including without limitation text, graphics, images, audio material, video material, audio-visual material, scripts and files that you submit on our website.</li>
                                <li>You grant to us a worldwide, irrevocable, non-exclusive, royalty-free licence to use, reproduce, store, adapt, publish, translate and distribute your content in any existing or future media OR reproduce, store and publish your content on and in relation to this website and any successor website OR reproduce, store and, with your specific consent, publish your content on and in relation to this website.</li>
                                <li>You grant to us the right to sub-license the rights licensed under Section 9.2.</li>
                                <li>You grant to us the right to bring an action for infringement of the rights licensed under Section 9.2.</li>
                                <li>You hereby waive all your moral rights in your content to the maximum extent permitted by applicable law; and you warrant and represent that all other moral rights in your content have been waived to the maximum extent permitted by applicable law.</li>
                                <li>You may edit your content to the extent permitted using the editing functionality made available on our website.</li>
                                <li>Without prejudice to our other rights under these terms and conditions, if you breach any provision of these terms and conditions in any way, or if we reasonably suspect that you have breached these terms and conditions in any way, we may delete, unpublish or edit any or all of your content.</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /.panel -->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseT9">9. Your content: rules</a>
                            </h4>
                        </div>
                        <div id="collapseT9" class="panel-collapse collapse">
                            <div class="panel-body">
                                <ol>                                    
                                <li>You warrant and represent that your content will comply with these terms and conditions.</li>
                                <li>Your content must not be illegal or unlawful, must not infringe any person's legal rights, and must not be capable of giving rise to legal action against any person (in each case in any jurisdiction and under any applicable law).</li>
                                <li>Your content, and the use of your content by us in accordance with these terms and conditions, must not:
                                  <ol>
                                    <li>be libellous or maliciously false;</li>
                                    <li>be obscene or indecent;</li>
                                    <li>infringe any copyright, moral right, database right, trade mark right, design right, right in passing off, or other intellectual property right;</li>
                                    <li>infringe any right of confidence, right of privacy or right under data protection legislation;</li>
                                    <li>constitute negligent advice or contain any negligent statement;</li>
                                    <li>constitute an incitement to commit a crime, instructions for the commission of a crime or the promotion of criminal activity;</li>
                                    <li>be in contempt of any court, or in breach of any court order;</li>
                                    <li>be in breach of racial or religious hatred or discrimination legislation;</li>
                                    <li>be blasphemous;</li>
                                    <li>be in breach of official secrets legislation;</li>
                                    <li>be in breach of any contractual obligation owed to any person;</li>
                                    <li>depict violence in an explicit, graphic or gratuitous manner;</li>
                                    <li>be pornographic, lewd, suggestive or sexually explicit;</li>
                                    <li>be untrue, false, inaccurate or misleading;</li>
                                    <li>consist of or contain any instructions, advice or other information which may be acted upon and could, if acted upon, cause illness, injury or death, or any other loss or damage;</li>
                                    <li>constitute spam;</li>
                                    <li>be offensive, deceptive, fraudulent, threatening, abusive, harassing, anti-social, menacing, hateful, discriminatory or inflammatory; or</li>
                                    <li>cause annoyance, inconvenience or needless anxiety to any person.</li>
                                  </ol>
                                </li>                                
                               </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /.panel -->
                    
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseT10">10. Limited warranties</a>
                            </h4>
                        </div>
                        <div id="collapseT10" class="panel-collapse collapse">
                            <div class="panel-body">
                                <ol>                                    
                                <li>We do not warrant or represent:
                                  <ol>
                                   <li>that the website or any service on the website will remain available.</li>
                                  </ol>
                                </li>
                                <li>We reserve the right to discontinue or alter any or all of our website services, and to stop publishing our website, at any time in our sole discretion without notice or explanation; and save to the extent expressly provided otherwise in these terms and conditions, you will not be entitled to any compensation or other payment upon the discontinuance or alteration of any website services, or if we stop publishing the website.</li>
                                <li>To the maximum extent permitted by applicable law and subject to Section 12.1, we exclude all representations and warranties relating to the subject matter of these terms and conditions, our website and the use of our website.</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /.panel -->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseT11">11. Limitations and exclusions of liability</a>
                            </h4>
                        </div>
                        <div id="collapseT11" class="panel-collapse collapse">
                            <div class="panel-body">
                                <ol>                                    
                                <li>Nothing in these terms and conditions will:
                                  <ol>
                                    <li>limit or exclude any liability for death or personal injury resulting from negligence;</li>
                                    <li>limit or exclude any liability for fraud or fraudulent misrepresentation;</li>
                                    <li>limit any liabilities in any way that is not permitted under applicable law; or</li>
                                    <li>exclude any liabilities that may not be excluded under applicable law.</li>
                                  </ol>
                                </li>
                                <li>The limitations and exclusions of liability set out in this Section 12 and elsewhere in these terms and conditions: 
                                  <ol>
                                    <li>are subject to Section 12.1; and</li>
                                    <li>govern all liabilities arising under these terms and conditions or relating to the subject matter of these terms and conditions, including liabilities arising in contract, in tort (including negligence) and for breach of statutory duty, except to the extent expressly provided otherwise in these terms and conditions.</li>
                                  </ol>
                                </li>
                                <li>To the extent that our website and the information and services on our website are provided free of charge, we will not be liable for any loss or damage of any nature.</li>
                                <li>We will not be liable to you in respect of any losses arising out of any event or events beyond our reasonable control.</li>
                                <li>We will not be liable to you in respect of any business losses, including (without limitation) loss of or damage to profits, income, revenue, use, production, anticipated savings, business, contracts, commercial opportunities or goodwill.</li>
                                <li>We will not be liable to you in respect of any loss or corruption of any data, database or software.</li>
                                <li>We will not be liable to you in respect of any special, indirect or consequential loss or damage.</li>
                                <li>You accept that we have an interest in limiting the personal liability of our officers and employees and, having regard to that interest, you acknowledge that we are a limited liability entity; you agree that you will not bring any claim personally against our officers or employees in respect of any losses you suffer in connection with the website or these terms and conditions (this will not, of course, limit or exclude the liability of the limited liability entity itself for the acts and omissions of our officers and employees).</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /.panel -->
                    <!-- /.panel -->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseT12">12. Breaches of these terms and conditions</a>
                            </h4>
                        </div>
                        <div id="collapseT12" class="panel-collapse collapse">
                            <div class="panel-body">
                                <ol>
                                  <li>Without prejudice to our other rights under these terms and conditions, if you breach these terms and conditions in any way, or if we reasonably suspect that you have breached these terms and conditions in any way, we may: 
                                    <ol>
                                      <li>send you one or more formal warnings;</li>
                                      <li>temporarily suspend your access to our website;</li>
                                      <li>permanently prohibit you from accessing our website;</li>
                                      <li>block computers using your IP address from accessing our website;</li>
                                      <li>contact any or all of your internet service providers and request that they block your access to our website;</li>
                                      <li>commence legal action against you, whether for breach of contract or otherwise; and/or</li>
                                      <li>suspend or delete your account on our website.</li>
                                    </ol>
                                  </li>
                                  <li>Where we suspend or prohibit or block your access to our website or a part of our website, you must not take any action to circumvent such suspension or prohibition or blocking, including (without limitation) creating and/or using a different account.</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /.panel -->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseT13">13. Variation</a>
                            </h4>
                        </div>
                        <div id="collapseT13" class="panel-collapse collapse">
                            <div class="panel-body">
                                <ol> 
                                  <li>We may revise these terms and conditions from time to time.</li>
                                  <li>We will give you written notice of any revision of these terms and conditions, and the revised terms and conditions will apply to the use of our website from the date that we give you such notice; if you do not agree to the revised terms and conditions, you must stop using our website.</li>
                                  <li>If you have given your express agreement to these terms and conditions, we will ask for your express agreement to any revision of these terms and conditions; and if you do not give your express agreement to the revised terms and conditions within such period as we may specify, we will disable or delete your account on the website, and you must stop using the website.</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /.panel -->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseT14">14. Assignment</a>
                            </h4>
                        </div>
                        <div id="collapseT14" class="panel-collapse collapse">
                            <div class="panel-body">
                                <ol>
                                  <li>You hereby agree that we may assign, transfer, sub-contract or otherwise deal with our rights and/or obligations under these terms and conditions.</li>
                                  <li>You may not without our prior written consent assign, transfer, sub-contract or otherwise deal with any of your rights and/or obligations under these terms and conditions. </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    
                    <!-- /.panel -->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseT15">15. Severability</a>
                            </h4>
                        </div>
                        <div id="collapseT15" class="panel-collapse collapse">
                            <div class="panel-body">
                                <ol>                                    
                                  <li>If a provision of these terms and conditions is determined by any court or other competent authority to be unlawful and/or unenforceable, the other provisions will continue in effect.</li>
                                  <li>If any unlawful and/or unenforceable provision of these terms and conditions would be lawful or enforceable if part of it were deleted, that part will be deemed to be deleted, and the rest of the provision will continue in effect. </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /.panel -->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseT16">16. Entire agreement</a>
                            </h4>
                        </div>
                        <div id="collapseT16" class="panel-collapse collapse">
                            <div class="panel-body">
                                <ol>
                                  <li>Subject to Section 12.1, these terms and conditions, together with our privacy policy, shall constitute the entire agreement between you and us in relation to your use of our website and shall supersede all previous agreements between you and us in relation to your use of our website.</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /.panel -->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseT17">17. Law and jurisdiction</a>
                            </h4>
                        </div>
                        <div id="collapseT17" class="panel-collapse collapse">
                            <div class="panel-body">
                                <ol>
                                  <li>These terms and conditions shall be governed by and construed in accordance with Kenyan &amp; East African law.</li>
                                  <li>Any disputes relating to these terms and conditions shall be subject to the non-exclusive jurisdiction of the courts of Kenya.</li>                                  
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /.panel -->
                    
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseT18">18. Statutory and regulatory disclosures</a>
                            </h4>
                        </div>
                        <div id="collapseT18" class="panel-collapse collapse">
                            <div class="panel-body">
                                <ol>
                                  <li>We are registered in [trade register]; you can find the online version of the register at [URL], and our registration number is [number].</li>
                                  <li>We are subject to [authorisation scheme], which is supervised by [supervisory authority].</li>
                                  <li>We are registered as  with [professional body] in Kenya and are subject to [rules], which can be found at [URL].</li>
                                  <li>We subscribe to [code(s) of conduct], which can be consulted electronically at [URL(s)].</li>
                                  <li>Our VAT number is [number].</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseT19">19. Our details</a>
                            </h4>
                        </div>
                        <div id="collapseT19" class="panel-collapse collapse">
                            <div class="panel-body">
                                <ol>
                                  <li>This website is owned and operated by KaziOnline Ltd.</li>
                                  <li>We are registered in Kenya under registration number [number], and our registered office is at Ngong Road, Nairobi.</li>
                                  <li>You can contact us:
                                    <ol>
                                      <li>using our website contact form on the contacts page;</li>
                                      <li>by telephone, on the contact number published on our website from time to time; or</li>
                                      <li>by email, using the email address published on our website from time to time.</li>
                                    </ol>
                                  </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.panel-group -->
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        
        
        <!-- Page Heading/Breadcrumbs -->
        <div class="row">
            <div class="col-lg-12" id="privacy">                
                <ol class="breadcrumb">
                    <li><a href="javascript:;">Help</a>
                    </li>
                    <li class="active">Privacy policy</li>
                </ol>
            </div>
        </div>
        <!-- /.row -->

        <!-- Content Row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel-group" id="accordion">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                              <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse30">What is this Privacy Policy for?</a>
                            </h4>
                        </div>
                        <div id="collapse30" class="panel-collapse collapse">
                            <div class="panel-body">
                              This privacy policy is for this website and served by KaziOnline Ltd and governs the privacy of its users who choose to use it. The policy sets out the different areas where user privacy is concerned and outlines the 
                              obligations &amp; requirements of the users, the website and website owners. Furthermore the way this website processes, stores and protects user data and information will also be detailed within this policy.
                            </div>
                        </div>
                    </div>
                    <!-- /.panel -->
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse31">The Website</a>
                            </h4>
                        </div>
                        <div id="collapse31" class="panel-collapse collapse">
                            <div class="panel-body">
                              This website and its owners take a proactive approach to user privacy and ensure the necessary steps are taken to protect the privacy of its users throughout their visiting experience. This website complies to all Kenyan &amp; East African laws and requirements for user privacy.
                            </div>
                        </div>
                    </div>
                    <!-- /.panel -->
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse32">Use of Cookies</a>
                            </h4>
                        </div>
                        <div id="collapse32" class="panel-collapse collapse">
                            <div class="panel-body">
                              <p>This website uses cookies to better the users experience while visiting the website. Where applicable this website uses a cookie control system allowing the user on their first visit to the website to allow or disallow the use of cookies on their computer / device. This complies with recent legislation requirements for websites to obtain explicit consent from users before leaving behind or reading files such as cookies on a user's computer / device.</p> 
                              <p>Cookies are small files saved to the user's computers hard drive that track, save and store information about the user's interactions and usage of the website. This allows the website, through its server to provide the users with a tailored experience within this website.<br>
                                 Users are advised that if they wish to deny the use and saving of cookies from this website on to their computers hard drive they should take necessary steps within their web browsers security settings to block all cookies from this website and its external serving vendors.
                              </p>
                              <p>
                                This website uses tracking software to monitor its visitors to better understand how they use it. This software is provided by Google Analytics which uses cookies to track visitor usage. The software will save a cookie to your computers hard drive in order to track and monitor your engagement and usage of the website, but will not store, save or collect personal information. You can read Google's privacy policy here for further information <a href="http://www.google.com/privacy.html">Google Privacy</a>
                              </p>
                              <p>
                                  Other cookies may be stored to your computers hard drive by external vendors when this website uses referral programs, sponsored links or adverts. Such cookies are used for conversion and referral tracking and typically expire after 30 days, though some may take longer. No personal information is stored, saved or collected.
                              </p>
                            </div>
                        </div>
                    </div>
                    <!-- /.panel -->
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                              <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse33">Contact &amp; Communication</a>
                            </h4>
                        </div>
                        <div id="collapse33" class="panel-collapse collapse">
                            <div class="panel-body">
                              <p>Users contacting this website and/or its owners do so at their own discretion and provide any such personal details requested at their own risk. Your personal information is kept private and stored securely until a time it is no longer required or has no use. Every effort has been made to ensure a safe and secure form to email submission process but advise users using such form to email processes that they do so at their own risk.</p>
                              <p>This website and its owners use any information submitted to provide you with further information about the products / services they offer or to assist you in answering any questions or queries you may have submitted. This includes using your details to subscribe you to any email newsletter program the website operates but only if this was made clear to you and your express permission was granted when submitting any form to email process. Or whereby you the consumer have previously purchased from or enquired about purchasing from the company a product or service that the email newsletter relates to. This is by no means an entire list of your user rights in regard to receiving email marketing material. Your details are not passed on to any third parties.</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                              <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse34">External Links</a>
                            </h4>
                        </div>
                        <div id="collapse34" class="panel-collapse collapse">
                            <div class="panel-body">
                              <p>Although this website only looks to include quality, safe and relevant external links, users are advised adopt a policy of caution before clicking any external web links mentioned throughout this website. External links are clickable text, banner or image links to other websites</p>
                              <p>The owners of this website cannot guarantee or verify the contents of any externally linked website despite their best efforts. Users should therefore note they click on external links at their own risk and this website and its owners cannot be held liable for any damages or implications caused by visiting any external links mentioned.</p>
                            </div>
                        </div>
                    </div>     
                </div>
                <!-- /.panel-group -->
            </div>
            <!-- /.col-lg-12 -->
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

  <script src="jquery/jquery-1.10.2.min.js"></script>   
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <script src="jquery/jquery.form.js"></script>
  <script src="bootstrap/js/dashboard_js.js"></script>
  <script>
      
      
        
  </script>
  
</body>
</html>
