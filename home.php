<?php 
require_once 'neuro/Jobs.php';
session_start();

//Jobs::cleanup_tmp();

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
    <link href="bootstrap/css/timeline.css" rel="stylesheet">    
    <link href="bootstrap/css/dashboard_custom.css" rel="stylesheet">
    <link href="bootstrap/css/bootstrap-tagsinput.css" rel="stylesheet"> 
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="jquery/css/datepicker.css" rel="stylesheet">
    <link href="jquery/css/uploadfile.css" rel="stylesheet">
    <style>

      .white-bg
      {
          background-color: #ffffff;
      }
      body
      {
          background-color: #ccffff;
      }      
      .data-container
      {
          height: 95%;
      }
      .jobs-section
      {
          border-left: 1px  #e9e9ea solid;
      }
      .bootstrap-tagsinput 
      {
          width: 100% !important;
      }
      .feedback
      {
          position: absolute;
          top: 250px;
          left: 300px;
          z-index: 100000;
      }
      #search_bar_txt
      {
          font-size: 16px;
          font-weight: 600;
      }

    </style>

  </head>

  <body>


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
              echo '<li><a href="javascript:;">No new notifications</a></li>';
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
            <li><a href="#"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
            </li>
          </ul>
          <!-- /.dropdown-user -->
        </li>
        <!-- /.dropdown -->
      </ul>

    </nav>
    <div id="page-container" class="">
              
        <div class="col-lg-12 white-bg data-container">
          <br><br>
          <div class="col-lg-3">
            <div class="col-lg-12 side-col">
              <h3><small>MY DOCKET</small></h3>
              <div class="panel panel-primary">
                <div class="panel-heading">
                  Todo list
                </div>
                <div class="list-group">
                  <a href="#" class="list-group-item">
                    Job 1
                    <span class="pull-right text-muted small"><em>4 minutes ago</em>
                    </span>
                  </a>
                  <a href="#" class="list-group-item">
                    Job 2
                    <span class="pull-right text-muted small"><em>12 minutes ago</em>
                    </span>
                  </a>                            
                </div> 
              </div>
              <div class="panel panel-primary">
                <div class="panel-heading">
                  Jobs I Posted
                </div>
                <div class="list-group">
                  <a href="#" class="list-group-item">
                    Nothing here                    
                  </a>                                             
                </div> 
              </div>
            </div>
          </div>
          <div class="col-lg-9 jobs-section">
            <div class="col-lg-12">
              <div class="alert alert-warning">
                <p>Do you have a job, task, project or homework that you need to get done <strong>fast</strong>? 
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#new_job_modal">Post It</button>
                </p>
              </div>
              <div class="col-lg-12">
                <h3 class="page-header">Latest jobs</h3>
              </div>            
              <div class="col-lg-12 jobs-list-container">
                
                <div class="row col-lg-12">
                  <div class="col-lg-12"><span id="search_bar_txt">Search jobs</span> <i class="fa fa-fw fa-search"></i><br><br></div>
                    <div class="col-lg-4"><input type="text" class="form-control" placeholder="Type anything"></div>
                    <div class="col-lg-4">
                        <select class="form-control" id="search_category" name="search_category">
                            <option value="">Select Category</option>
                            <option value="7">Article Writing</option>
                            <option value="1">Accounting, Business &amp; Finance</option>
                            <option value="2">Agriculture</option>
                            <option value="3">Creating &amp; Design</option>
                            <option value="4">Data Entry</option>
                            <option value="5">Engineering &amp; Construction</option>
                            <option value="6">IT, Websites &amp; Software</option> 
                            <option value="8">Legal</option>
                            <option value="9">Marketing &amp; Sales</option>
                            <option value="10">Product Sourcing &amp; Manufacturing</option>
                            <option value="11">Local Jobs &amp; Services</option>
                            <option value="12">Transport &amp; Logistics</option>
                            <option value="13">Other</option>
                        </select>
                    </div>
                    <div class="col-lg-4">
                      <input type="text" class="form-control" name="search_tags" id="search_tags" placeholder="Search tags">&nbsp;<small>e.g. Physics, CPA, C++, Furniture</small>
                    </div>                
                </div>
                <div class="row col-lg-12"><br><br></div>
                <div class="row col-lg-12">Jobs here</div>
              </div>

            </div>
          </div>
          <div class="col-lg-12"><br></div>
        </div>
        <div class="col-lg-1 side-col"></div>
      
    </div>

    <!-- Modal -->
    <div class="modal fade" id="new_job_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"  data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">New Job</h4>
          </div>
          <div class="modal-body" style="height: 500px; overflow-y: scroll;">
            <form id="job_create_form" action="controller/new_ft.php" method="post" enctype="multipart/form-data">              
              <div class="form-group">
                <label for="job_title">Summary <small>(30 chars)</small></label>
                <input type="text" class="form-control" name="job_title" id="job_title" placeholder="Very brief summary" required="">
              </div>
              <div class="form-group">
                <label for="job_desc">Complete description</label>
                <textarea class="form-control" id="job_desc" name="job_desc" placeholder="Be expansive about the job, project, task etc." required=""></textarea>                    
              </div>
              <div class="form-group">
                <label for="job_criteria">Category</label>
                <select class="form-control" id="job_category" name="job_category" required="">
                  <option value="">Select Category</option>
                  <option value="7">Article Writing</option>
                  <option value="1">Accounting, Business &amp; Finance</option>
                  <option value="2">Agriculture</option>
                  <option value="3">Creating &amp; Design</option>
                  <option value="4">Data Entry</option>
                  <option value="5">Engineering &amp; Construction</option>
                  <option value="6">IT, Websites &amp; Software</option> 
                  <option value="8">Legal</option>
                  <option value="9">Marketing &amp; Sales</option>
                  <option value="10">Product Sourcing &amp; Manufacturing</option>
                  <option value="11">Local Jobs &amp; Services</option>
                  <option value="12">Transport &amp; Logistics</option>
                  <option value="13">Other</option>
                </select>
              </div>

              <div class="form-group">
                <label for="job_tags">Additional Tags</label><br>
                <input type="text" class="form-control" name="job_tags" id="job_tags" placeholder="e.g. Physics, CPA, C++, Pharmacy">
              </div>
              <div class="form-group">
                <strong>Attach Files</strong>&nbsp;&nbsp;<small>(if any) max <strong>25MB</strong> total</small>
                <div id="job_attach_files"></div>
              </div>
              <span class=""
                    <fieldset>
                  <legend>Deadline</legend>
                  <label>Pick date</label>
                  <div class="form-group">
                    <div class="input-group" style="width: 50%;">
                      <input type="text" class="form-control" id="job_deadline" name="job_deadline">
                      <span class="input-group-addon"><i class="fa fa-fw fa-calendar"></i></span>
                    </div>
                  </div>                
                </fieldset>

                <fieldset>
                  <legend>Your Budget</legend>
                  <div class="form-group">
                    <label>Minimum</label>
                    <div class="input-group">
                      <span class="input-group-addon">KSH</span>
                      <input type="text" id="job_amount" style="width: 50%;" name="job_amount_min" class="form-control" placeholder="Minimum amount" required="">
                    </div>                  
                  </div>
                  <div class="form-group">
                    <label>Maximum</label>
                    <div class="input-group">
                      <span class="input-group-addon">KSH</span>
                      <input type="text" id="rewardee_amount" style="width: 50%;" name="job_amount_max" class="form-control" placeholder="Maximum amount" required="">
                    </div>                  
                  </div>
                </fieldset>

            </form>
            
            <form class="feedback" style="display: none;">
            </form>
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="sb_job_create_form">Submit</button>
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
    <script type="text/javascript">

          var tomorrow = new Date()
          tomorrow.setDate(tomorrow.getDate() + 1)
          
          var allowed_exts = "csv,xls,xlsx,txt,png,jpg,gif,bmp,avi,mpg,mpeg,mp4,mp3,wav,flv,pdf,doc,docx,ppt,pptx,psd,pub,7z,ppd,bz,bz2,ico,jar,phar,tex,latex,wma,wmx,wmv,mpga,mp4a,oda,oxt,ogx,oga,ogv,odb,rar,tar,xz,zip,gtar,tiff";

          $(function ()
          {
              $("#job_deadline").datepicker({zIndex: 1000000, autohide: true, startDate: tomorrow, format: 'dd/mm/yyyy'});

              $("#job_tags, #search_tags").tagsinput({
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

              $("#job_attach_files").uploadFile(
                      {
                          url: "controller/job_file_upload.php",
                          fileName: 'jobfile',
                          uploadStr: "<i class='glyphicon glyphicon-paperclip'></i>",
                          multiple: true,
                          dragDrop: true,
                          showPreview: true,
                          showProgress:true,
                          showDelete: true,
                          sequential:true,
                          sequentialCount:1,
                          previewHeight: '100px',
                          previewWidth: 'auto',
                          deletelStr: '<i class="fa fa-fw fa-trash"></i>',
                          fileCounterStyle: '. ',
                          maxFileSize: 26214400,
                          allowedTypes:allowed_exts,
                          extErrorStr:' such files are not allowed. The following are allowed - ',
                          sizeErrorStr:'maximum attachment size is 25MB',
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
                          onError:function(files,status,errMsg,pd)
                          {
                              $(".feedback").html('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Oops:</strong> Lost Connection</div>')
                              $(".feedback").show()
                          },
                          onSuccess:function(files,data,xhr,pd)
                          {
                              var res = new String(data)
                                if(res.substr(0,2) == "E:")
                                {
                                    $(".feedback").html('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Error uploading '+files+':</strong> ' + res.substring(2) + '</div>')
                                    $(".feedback").show()
                                    pd.statusbar.hide()
                                }
                           }
                      })

          })

    </script>

  </body>

</html>
