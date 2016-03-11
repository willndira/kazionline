<?php
require_once 'neuro/Jobs.php';
session_start();

//Jobs::cleanup_tmp();
//$jobs_data = Jobs::loadJobs();
//$docket = Jobs::loadDocket($_SESSION["sess_id"]);
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
  
  <div id="page-container">

    <div class="col-lg-12 white-bg data-container">
      <br><br>
      <div class="col-lg-3">            
        <h3><small>CREATOR</small></h3>
        
        <div class="row"><br>
          <div class="col-lg-4"><img src="img/avatars/default_avatar.png" style="height: 100px; width: auto;"></div>
          <div class="col-lg-8">Brian Kim<br><i class="fa fa-fw fa-map-marker"></i><small>Nairobi, Kenya</small></div></div>
        <br>
        <h3><small>JOB INFO</small></h3>
        <strong>Brief</strong><br>
        <span>bluh bluh bluh</span><br><br>
        <strong>Category</strong><br>
        <span>Article Writing</span><br><br>
        <strong>Tags</strong><br>
        <span>C++, Graphics</span><br><br>
        <strong>Attachments</strong><br>
        <span>bluh bluh bluh</span><br><br>
        <strong>Description</strong><br>
        <span>bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh</span>

      </div>
      <div class="col-lg-7 bids-section">            
        <div class="col-lg-8">
          <p><strong>Added On:</strong> 28th Feb 2016 </p>
          <p><strong>Due by:</strong> 28th Mar 2016 </p>
          <p><strong>Budget:</strong> KSH 12,000 - 18,000 </p>
          <br><br>
        </div>
              
        <div class="col-lg-12 jobs-list-container">
          <fieldset>
            <legend><h3><small>BIDS</small></h3></legend>
          </fieldset>
          <div class="row col-lg-12">
            <div><strong><small>29 bids</small></strong><br><br><br></div>
            
            <table class="table">              
              <tbody id="bids-listings">
                <tr dx="1">
                  <td dx="1">
                    <div class="col-lg-2"><img src="img/avatars/default_avatar.png" style="width: 80px; height: auto"></div>
                    <div class="col-lg-10"><div class="col-lg-4">Name</div><div class="col-lg-4"><i class="fa fa-fw fa-map-marker"></i><small>Nairobi, Kenya</small></div><div class="col-lg-4"><span class="pull-right"><a href="javascript:;" vec="bid-edit" dx="1"><i class="fa fa-fw fa-pencil"></i></a><a href="javascript:;" vec="bid-delete" dx="1"><i class="fa fa-fw fa-trash-o"></i></a>&nbsp;<small><i class="fa fa-clock-o fa-fw"></i>12h</small></span></div><div class="col-lg-3"><i class="fa fa-fw fa-credit-card"></i> <small><span class="bid-amount">12,500</span></small></div></div>
                    <div class="col-lg-12"><br><span class="bid-comment">bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh</span> </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="col-lg-2"><img src="img/avatars/default_avatar.png" style="width: 80px; height: auto"></div>
                    <div class="col-lg-10">Name&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-fw fa-map-marker"></i>Nairobi, Kenya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-fw fa-credit-card"></i> 12,500&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="pull-right"><i class="fa fa-fw fa-pencil"></i><i class="fa fa-fw fa-trash-o"></i> 12h</span></div>
                    <div class="col-lg-12"><br>bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="col-lg-2"><img src="img/avatars/default_avatar.png" style="width: 80px; height: auto"></div>
                    <div class="col-lg-10">Name&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-fw fa-map-marker"></i>Nairobi, Kenya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-fw fa-credit-card"></i> 12,500&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="pull-right"><i class="fa fa-fw fa-pencil"></i><i class="fa fa-fw fa-trash-o"></i> 12h</span></div>
                    <div class="col-lg-12"><br>bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="col-lg-2"><img src="img/avatars/default_avatar.png" style="width: 80px; height: auto"></div>
                    <div class="col-lg-10">Name&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-fw fa-map-marker"></i>Nairobi, Kenya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-fw fa-credit-card"></i> 12,500&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="pull-right"><i class="fa fa-fw fa-pencil"></i><i class="fa fa-fw fa-trash-o"></i> 12h</span></div>
                    <div class="col-lg-12"><br>bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="col-lg-2"><img src="img/avatars/default_avatar.png" style="width: 80px; height: auto"></div>
                    <div class="col-lg-10">Name&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-fw fa-map-marker"></i>Nairobi, Kenya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-fw fa-credit-card"></i> 12,500&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="pull-right"><i class="fa fa-fw fa-pencil"></i><i class="fa fa-fw fa-trash-o"></i> 12h</span></div>
                    <div class="col-lg-12"><br>bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="col-lg-2"><img src="img/avatars/default_avatar.png" style="width: 80px; height: auto"></div>
                    <div class="col-lg-10">Name&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-fw fa-map-marker"></i>Nairobi, Kenya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-fw fa-credit-card"></i> 12,500&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="pull-right"><i class="fa fa-fw fa-pencil"></i><i class="fa fa-fw fa-trash-o"></i> 12h</span></div>
                    <div class="col-lg-12"><br>bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh bluh </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <br><br>
                    <fieldset><legend>Submit Bid <small>150 chars</small></legend></fieldset>
                    <div class="col-lg-7"><textarea class="form-control" style="min-height: 150px;" placeholder="Why you want this job"></textarea></div>
                    <div class="col-lg-5">
                      <label>How much do you want?</label>
                      <div class="input-group"><span class="input-group-addon">KSH</span><input type="text" class="form-control edit-bid-amount" placeholder="0">
                            <span class="input-group-addon">.00</span>
                        </div>
                      <br><br>
                      <button class="btn btn-primary">Submit <i class="fa fa-fw fa-angle-double-right"></i></button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            

        </div>
        <div class="col-lg-12"><br></div>
      </div>  

      
    </div>
      <div class="col-lg-2">
          waddup
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
                  <legend>Due by</legend>
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
                    <div class="input-group" style="width: 50%;">
                      <span class="input-group-addon">KSH</span>
                      <input type="text" id="job_amount_min" name="job_amount_min" class="form-control" placeholder="Minimum amount" required="">
                      <span class="input-group-addon">.00</span>
                    </div>                  
                  </div>
                  <div class="form-group">
                    <label>Maximum</label>
                    <div class="input-group" style="width: 50%;">
                      <span class="input-group-addon">KSH</span>
                      <input type="text" id="job_amount_max" name="job_amount_max" class="form-control" placeholder="Maximum amount" required="">
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
            <button type="button" class="btn btn-primary" id="sb_job_create_form">Submit</button>
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
              //$("#delete_bid_modal").modal('show')
              
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

              $(".jobs-search").on("keypress", function (e)
              {
                  if (e.keyCode == 13)
                    {
                        load_jobs(1);
                    }
              })
              
              $("ul.pagination li a").on("click", function()
              {
                  var page = $(this).attr("dx")
                  load_jobs(page)
              })
              
              $("[vec='bid-edit']").on("click", function()
              {
                  var dx = $(this).attr("dx");
                  var bid_amount = $("td[dx='"+dx+"']").find("span.bid-amount").html()
                  var bid_text = $("td[dx='"+dx+"']").find("span.bid-comment").html()
                  $(".edit-bid-comment").val(bid_text)
                  $(".edit-bid-amount").val(bid_amount)
                  $("#save-bid-edits").attr("dx", dx);                  
                  $("#edit_bid_modal").modal('show')
                  
              })
              
              $("[vec='bid-delete']").on("click", function()
              {
                  var dx = $(this).attr("dx");
                  $("#delete-bid-sb").attr("dx", dx); 
                  $("#delete_bid_modal").modal('show')
              })
              

          })

          function load_jobs(page)
          {
              var term = $("#search_text").val()
              var cat = $("#search_category").val()
              var tags = $("#search_tags").val()
              var amt = $("#search_amount").val()

              var fdback = $("#jobs-load")

              fdback.show().html("<img src='img/jx/loading29.gif'> Loading&hellip;")

              $.ajax
                      ({
                          url: "controller/load_jobs.php",
                          type: "GET",
                          cache: true,
                          async: true,
                          dataType: 'json',
                          data: {key: term, ctg: cat, tgs: tags, amt: amt, page: page},
                          success: function (result)
                          {
                              $("#jobs-listings").html(result.jobs)
                              $("#pagination-div").html(result.pagination)
                              fdback.hide()
                          },
                          error: function ()
                          {
                              fdback.html("<div class='alert alert-warning'>Lost Internet connection</div>")
                              setTimeout(function (){fdback.hide()}, 1500);
                          }
                      })
          }

    </script>



</html>
