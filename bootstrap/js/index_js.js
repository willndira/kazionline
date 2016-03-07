$(function ()
          {
              var options =
                      {
                          complete: function (response)
                          {
                              if (response.responseText !== "ok")
                                  {
                                      $(".feedback").html('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Error:</strong> ' + response.responseText + '</div>')
                                      $(".feedback").show()
                                  }
                              else
                                  {
                                      $(".feedback").html('<div class="alert alert-success" role="alert"><strong>Successful!</strong>&nbsp; Just a moment&hellip;</div>')
                                      $(".feedback").show()

                                      setTimeout(function ()
                                      {
                                          window.location = "home.php"
                                      }, 1500)
                                  }
                          }
                      }

              $(".login_form").ajaxForm(options)

              var options2 =
                      {
                          complete: function (response)
                          {
                              if (response.responseText !== "ok")
                                  {
                                      $(".feedback").html('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Error:</strong> ' + response.responseText + '</div>')
                                      $(".feedback").show()
                                  }
                                  
                              else
                                  {
                                      $(".feedback").html('<div class="alert alert-success" role="alert"><strong>Successful!</strong>&nbsp;Just a moment&hellip;</div>')
                                      $(".feedback").show()

                                      setTimeout(function ()
                                      {
                                          window.location = "home.php"
                                      }, 1500)
                                  }
                          }
                      }

              $(".reg_form").ajaxForm(options2)

          })