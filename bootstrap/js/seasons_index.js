  var c_teams_sel = null
  var t_pick_teams = null
  var t_pref_team = null
  var c_comp_id = 0


  $(function ()
  {
      $('[data-toggle="popover"]').popover()
      $('[data-toggle="tooltip"]').tooltip()

      $("#upload_ppic_modal").modal('show')

      $("#ppic_browse_btn").on("click", function ()
      {
          $("#hidden_browse_btn").trigger("click")
      })

      $("#hidden_browse_btn").on("change", function ()
      {
          imgPreview(this)
      })


      $(".cup_list_div").on("click", function ()
      {
          c_teams_sel.val(null).trigger('change')

          if (!$(this).hasClass("cup_list_div_clicked"))
              {
                  $(".cup_list_div_clicked").find("i").remove()
                  $(".cup_list_div_clicked").removeClass("cup_list_div_clicked")
                  var curr = $(this).html()
                  curr += " <i class='glyphicon glyphicon-ok'></i> "

                  $(this).html(curr)
                  $(this).addClass("cup_list_div_clicked")
                  var chosen_tournament = $(this).attr("vector")
                  c_comp_id = chosen_tournament
                  load_teams(chosen_tournament)
                  get_target_odds()
              }

          else
              {
                  $(this).removeClass("cup_list_div_clicked")
                  $(this).find("i").remove()
                  $("#c_pick_team").empty();
              }

      })

      $("#c_pick_team, #c_trg").on("change", function ()
      {
          get_target_odds()
      })
      
      $("#t_tournament_type").on("change", function()
      {
          if($(this).val() == "0") //league
          {
              $("#t_team_no").html("<option value='4'>4</option><option value='6'>6</option><option value='8'>8</option><option value='10'>10</option><option value='12'>12</option><option value='14'>14</option><option value='16'>16</option><option value='18'>18</option><option value='20'>20</option>")
          }
          else
              $("#t_team_no").html("<option value='8'>8</option><option value='16'>16</option><option value='32'>32</option><option value='14'>14</option><option value='16'>16</option>")
      })

      $("#c_amount").on("keyup", function ()
      {
          var odds = $("#c_odds").val()
          odds = parseFloat(odds)
          var amt = $("#c_amount").val()

          if (amt == null || amt.length === 0)
              amt = 0

          var psw = Math.ceil(odds * amt)
          $("#c_poss_win").val(psw)

      })

      load_ft_teams($("#t_team_type").val())

      $("#t_team_type").on("change", function ()
      {
          $("#t_pref_team").empty()

          t_pref_team.val(null).trigger("change")
          t_pick_teams.val(null).trigger("change")

          var val = $(this).val()

          load_ft_teams(val)
      })


      var c_team = $("#c_pick_team").select2({
          placeholder: "Choose Team",
          allowClear: true
      })

      var max_sel = $("#t_team_no").val()

      var t_pick_teams = $("#t_pick_teams").select2({
          placeholder: "Pick Teams",
          allowClear: true,
          maximumSelectionLength: max_sel
      })

      var t_pref_team = $("#t_pref_team").select2({
          placeholder: "Your Team",
          allowClear: true
      })

      $("#t_invite_friends").select2({
          placeholder: "Friend Name",
          allowClear: true,
          ajax: {
              url: "controller/suggest_invites.php",
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
              return markup;
          },
          minimumInputLength: 3,
          templateResult: formatHint,
          templateSelection: formatHintSelection
      })


      $("#invite_accept_chooseteam").select2({
          placeholder: "Available Teams",
          allowClear: true
      })

      $("#t_team_no").on("change", function ()
      {
          var max_sel = $(this).val()
          var new_options = {placeholder: "Pick Teams", allowClear: true, maximumSelectionLength: max_sel}

          t_pick_teams.select2("destroy")

          t_pick_teams = $("#t_pick_teams").select2(new_options)

      })

      $("#t_pick_teams").on("change", function ()
      {
          if ($(this).val() == null)
              {
                  $("#t_teams_picked").html("*0 teams picked")
                  $("#t_pref_team").empty()
                  return;
              }

          var str = new String($(this).val())
          var _values = str.split(",")

          $("#t_teams_picked").html("*" + _values.length + " teams picked")

          var prefs = ''

          for (var i in _values)
          {
              var _name = $("option[value=" + _values[i] + "]").attr("name")
              prefs += '<option value="' + _values[i] + '">' + _name + '</option>'
          }

          $("#t_pref_team").html(prefs)

      })

      set_select2_objs(c_team, t_pick_teams, t_pref_team)


      var options1 =
              {
                  complete: function (response)
                  {
                      var resp = parseInt(response.responseText)
                      if (isNaN(resp))
                          {
                              $(".new_season_feedback").show()
                              $("#new_challenge_sb, #new_challenge_dismiss").removeClass("disabled")
                              $(".new_season_feedback").html('<span class="text-danger"><strong>Error:</strong> ' + response.responseText + '</span>')
                          }
                      else
                          {
                              $(".new_season_feedback").show()
                              $(".new_season_feedback").html('<span class="text-success"><strong>Successful!</strong>&nbsp; Just a moment&hellip;</span>')

                              setTimeout(function ()
                              {
                                  window.location="dashboard.php"
                              }, 1500)
                          }
                  }
              }

      $("#new_challenge_form").ajaxForm(options1)

      var options2 =
              {
                  complete: function (response)
                  {
                      var resp = parseInt(response.responseText)
                      
                      if (isNaN(resp))
                          {
                              $("#ft_create_sb, #ft_create_dismiss").removeClass("disabled")
                              $(".new_tournament_feedback").show()
                              $(".new_tournament_feedback").html('<span class="text-danger"><strong>Error:</strong> ' + response.responseText + '</span>')
                          }
                      else
                          {
                              $(".new_tournament_feedback").show()
                              $(".new_tournament_feedback").html('<span class="text-success"><strong>Successful!</strong>&nbsp; Just a moment&hellip;</span>')

                              setTimeout(function ()
                              {
                                  window.location="dashboard.php"
                              }, 1500)
                          }
                  }
              }

      $("#ft_create_form").ajaxForm(options2)


      var options =
              {
                  uploadProgress: function (event, position, total, percentComplete)
                  {
                      var percentVal = percentComplete + '%';
                      $("#ppic_upload_progress_val").attr("aria-valuenow", percentComplete)
                      $("#ppic_upload_progress_val").css("width", percentVal)
                      $("#ppic_upload_progress_txt").html(percentVal)
                  },
                  success: function ()
                  {
                      $("#ppic_upload_progress_val").attr("aria-valuenow", "100")
                      $("#ppic_upload_progress_val").css("width", "100%")
                  },
                  complete: function (response)
                  {
                      if (response.responseText !== "ok")
                          {
                              $("#ppic_upload_progress_txt").html('<span class="text-danger"><strong>Error:</strong> ' + response.responseText + '</span>')
                          }
                      else
                          {
                              $("#ppic_upload_progress_txt").html('<span class="text-success"><strong>100% Successful!</strong>&nbsp; Just a moment&hellip;</span>')

                              setTimeout(function ()
                              {
                                  $("#upload_ppic_modal").modal('hide')
                              }, 1500)
                          }
                  }
              }

      $("#upload_ppic_form").ajaxForm(options)

      $("#upload_ppic_sb").on("click", function ()
      {
          $("#ppic_upload_progress_val").attr("aria-valuenow", "0")
          $("#ppic_upload_progress_val").css("width", "0%")

          $("#ppic_upload_progress_txt").show(500).html("0%")
          $("#upload_ppic_form").trigger("submit")
      })

      $("[ivec=0]").on("click", function ()
      {
          $("#load_teams_accept_invite").show().html('<em>Loading available teams</em>&nbsp;<img src="img/jx/loading5.gif"></span>')
          var szn = $(this).attr("idx")
          var ttl = $(this).attr("idnm")
          var ftc = $(this).attr("idc")

          $("#accept_invite_sb").attr("idx", szn)
          $("#ft_invite_name").html("Invitation to " + ttl)
          $("#ft_contrib").html("<strong>KSH " + ftc + "</strong>&nbsp;&nbsp;&nbsp;<small><em>as requested by tournament organizer</em></small>")

          $.ajax
                  ({
                      url: "controller/get_unmanagedteams_sel2.php",
                      type: "GET",
                      cache: false,
                      async: true,
                      data: {szn: szn},
                      success: function (result)
                      {
                          $("#invite_accept_chooseteam").html(result)
                          $("#load_teams_accept_invite").hide(200)
                      },
                      error: function ()
                      {
                          $("#load_teams_accept_invite").html("<span class='text-warning'>Internet connection lost</span>")
                      }
                  })

      })

      $("[ivec=1]").on("click", function ()
      {
          var szn = $(this).attr("idx")
          var tm = $("#invite_accept_chooseteam").val()
          $.ajax
                  ({
                      url: "controller/respond2_invites.php",
                      type: "GET",
                      cache: false,
                      async: true,
                      data: {szn: szn, resp: 1, team: tm},
                      success: function (result)
                      {
                          if (result != "bad")
                              $("tr[idx=" + szn + "]").slideUp()
                      },
                      error: function ()
                      {

                      }
                  })
      })

      $("#accept_invite_sb").on("click", function ()
      {
          var szn = $(this).attr("idx")

          $("#load_teams_accept_invite").show().html('<em>Setting things up&hellip;</em>&nbsp;<img src="img/jx/loading5.gif"></span>')

          $.ajax
                  ({
                      url: "controller/respond2_invites.php",
                      type: "GET",
                      cache: false,
                      async: true,
                      data: {szn: szn, resp: 1},
                      success: function (result)
                      {
                          if (result == "ok")
                              {
                                  $("tr[idx=" + szn + "]").slideUp()
                                  $("#invitation_accept_modal").modal('hide')
                              }
                          else
                              {
                                  $("#load_teams_accept_invite").html("<span class='text-danger'><strong>Error: </strong>"+result+"</span>")
                              }

                      },
                      error: function ()
                      {
                          $("#load_teams_accept_invite").html("<span class='text-warning'>Internet connection lost</span>")
                      }
                  })
      })
      
      
      $("#new_challenge_sb").on("click", function ()
      {
          $("#hidden_comp_id").val(c_comp_id)
          $("#new_challenge_sb, #new_challenge_dismiss").addClass("disabled")
          $(".new_season_feedback").show().html("<em>Setting up data and generating fixtures&hellip;</em><img src='img/jx/loading5.gif'>")
          
          $("#new_challenge_form").trigger("submit");
      })
      
      $("#ft_create_sb").on("click", function ()
      {
          $(".new_tournament_feedback").show().html("<em>Setting up data and generating fixtures&hellip;</em><img src='img/jx/loading5.gif'>")
          $("#ft_create_sb, #ft_create_dismiss").addClass("disabled")
          $("#ft_create_form").trigger("submit");
      })

  })

  function load_teams(comp)
  {
      $("#c_loading_gif").html('<img src="img/jx/loading5.gif"> <em>Loading Teams ..</em>')
      $.ajax({
          url: "controller/get_comp_teams.php",
          contentType: "application/x-www-form-urlencoded; charset=utf-8",
          type: "GET",
          async: true,
          cache: true,
          data: {comp_id: comp},
          success: function (result)
          {
              if (result != "bad")
                  {
                      $("#c_pick_team").html(result)

                      var _ctype = $("[vector='" + comp + "']").attr("vector2")

                      var _opts = $("#c_pick_team option").toArray()
                      var num_opts = _opts.length
                      num_opts /= 4

                      var rank_opts = ''

                      if (_ctype == 0)
                          {

                              for (var i = 1; i <= num_opts; ++i)
                              {
                                  rank_opts += '<option value="' + i + '">' + i + '</option>'
                              }

                          }
                      else
                          {
                              rank_opts += '<option value="1">Winner</option><option value="2">Final</option>'
                              rank_opts += '<option value="4">Semi-Final</option>'
                          }

                      $("#c_trg").html(rank_opts)

                  }

          }
      })

      $("#c_loading_gif").empty()
  }

  function set_select2_objs(cteams, tteams, pref)
  {
      c_teams_sel = cteams
      t_pick_teams = tteams
      t_pref_team = pref
  }

  function get_target_odds()
  {
      var team = $("#c_pick_team").val()
      var comp = $(".cup_list_div_clicked").attr("vector")
      var trg = $("#c_trg").val()

      $("#c_loading_odds").html('<br><img src="img/jx/loading5.gif"> &nbsp;&nbsp;<em>Calculating Odds</em>')
      $.ajax({
          url: "controller/get_target_odds.php",
          type: "GET",
          async: true,
          cache: true,
          data: {team: team, comp: comp, trg: trg},
          success: function (result)
          {
              if (result != "bad")
                  {
                      $("#c_odds").val(result)
                      var odds = parseFloat(result)
                      var amt = $("#c_amount").val()
                      if (amt == null || amt.length === 0)
                          amt = 0

                      var psw = Math.ceil(odds * amt)
                      $("#c_poss_win").val(psw)

                  }
          }
      })

      $("#c_loading_odds").empty()
  }

  function load_ft_teams(teams_type)
  {
      $.ajax({
          url: "controller/get_ft_teams.php",
          type: "GET",
          data: {type: teams_type},
          cache: true,
          async: true,
          success: function (result)
          {
              $("#t_pick_teams").html(result)
          }
      })
  }

  function imgPreview(input)
  {

      if (input.files && input.files[0])
          {
              var reader = new FileReader();

              reader.onload = function (e)
              {
                  $('#img_preview').attr('src', e.target.result);
              }

              reader.readAsDataURL(input.files[0]);
          }
  }

  function formatHint(hint)
  {
      var markup = '<div class="clearfix"><div class="col-lg-2"><img src="' + hint.avr + '" style="width: 40px; height:auto;"></div><div class="col-lg-8">' + hint.names + '</div></div>'

      return markup
  }

  function formatHintSelection(hint)
  {
      return hint.names || hint.text
  }
