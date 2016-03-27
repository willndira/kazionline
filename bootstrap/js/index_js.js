  /* global google */

  $(function ()
  {
      var options =
              {
                  complete: function (response)
                  {
                      if (response.responseText !== "ok")
                          {
                              $("#login-feedback").html('<span class="text-danger"><strong>Error:</strong> ' + response.responseText + '</span>')
                          }
                      else
                          {
                              $("#login-feedback").html('<span class="text-success"><strong>Successful!</strong>&nbsp; Just a moment&hellip;</div>')

                              setTimeout(function ()
                              {
                                  window.location = "home.php"
                              }, 1500)
                          }
                  }
              }

      $("#login-form").ajaxForm(options)

      var options2 =
              {
                  complete: function (response)
                  {
                      if (response.responseText !== "ok")
                          {
                              $("#ureg-feedback").html('<span class="text-danger"><strong>Error:</strong> ' + response.responseText + '</span>')
                          }
                      else
                          {
                              $("#ureg-feedback").html('<span class="text-success"><strong>Successful!</strong>&nbsp; Just a moment&hellip;</div>')

                              setTimeout(function ()
                              {
                                  window.location = "home.php"
                              }, 1500)
                          }
                  }
              }

      $("#ureg-form").ajaxForm(options2)

      var options3 =
              {
                  complete: function (response)
                  {
                      if (response.responseText !== "ok")
                          {
                              $("#creg-feedback").html('<span class="text-danger"><strong>Error:</strong> ' + response.responseText + '</span>')
                          }
                      else
                          {
                              $("#creg-feedback").html('<span class="text-success"><strong>Successful!</strong>&nbsp; Just a moment&hellip;</div>')

                              setTimeout(function ()
                              {
                                  window.location = "home.php"
                              }, 1500)
                          }
                  }
              }

      $("#creg-form").ajaxForm(options3)


  })

  function initialize()
  {
      var input = document.querySelector(".input-location")
      new google.maps.places.Autocomplete(input)
      
      input = document.querySelector(".input-location2")
      new google.maps.places.Autocomplete(input)
  }
  
  google.maps.event.addDomListener(window, 'load', initialize)