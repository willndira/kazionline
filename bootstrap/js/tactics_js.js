  /* 
   * To change this license header, choose License Headers in Project Properties.
   * To change this template file, choose Tools | Templates
   * and open the template in the editor.
   */


  /* global buckets, tactic_formations */

  var selecao = buckets.Dictionary()
  var chip_map = buckets.Dictionary()
  var chip_xy = buckets.Dictionary()
  
  var chips = []

  function player_drag_evt(event)
  {
      var player_name = event.target.getAttribute("data-player_name")
      var player_id = event.target.getAttribute("data-player_id")
      var ihtml = event.target.innerHTML

      event.dataTransfer.setData("player_name", player_name)
      event.dataTransfer.setData("player_id", player_id)
      event.dataTransfer.setData("ihtml", ihtml)
  }

  function player_drop_event(event)
  {
      event.preventDefault()

      var pid = event.dataTransfer.getData("player_id")
      var pname = event.dataTransfer.getData("player_name")

      if (selecao.containsKey(event.target.innerHTML))
          {
              selecao.remove(event.target.innerHTML)
              chip_map.remove(pid)
          }

      if (selecao.containsKey(pname))
          return false

      if (pname === null || pname.length === 0)
          return false

      event.target.innerHTML = pname
      event.target.setAttribute("cdata-player_id", pid)
            
      selecao.set(pname, pid)
      chip_map.set(pid, event.target)
  }

  function player_rep_drop(event)
  {
      var ihtml = event.dataTransfer.getData("ihtml")
      var src_id = event.dataTransfer.getData("player_id");
      var src_pname = event.dataTransfer.getData("player_name")
      var src = document.querySelector("[data-player_id='" + src_id + "']")
      var myhtml = event.target.innerHTML
      var my_pid = event.target.getAttribute("data-player_id")
      var my_pname = event.target.getAttribute("data-player_name")
      src.setAttribute("data-player_id", my_pid)
      src.setAttribute("data-player_name", my_pname)
      event.target.setAttribute("data-player_id", src_id)
      event.target.setAttribute("data-player_name", src_pname)

      event.target.innerHTML = ihtml
      src.innerHTML = myhtml

      if (selecao.containsKey(my_pname) && selecao.containsKey(src_pname))
          {
              var chip1 = chip_map.get(my_pid)
              var chip2 = chip_map.get(src_id)
                            
              chip1.html(src_pname)
              chip1.attr("cdata-player_id", src_id);
              chip2.html(my_pname)
              chip2.attr("cdata-player_id", my_pid)

              chip_map.set(src_id, chip1)
              chip_map.set(my_pid, chip2)
          }
      else if (selecao.containsKey(my_pname))
          {
              selecao.remove(my_pname)
              selecao.set(src_pname, src_id)

              var chip = chip_map.get(my_pid)
              chip.html(src_pname)
              chip_map.remove(my_pid)
              chip.attr("cdata-player_id", src_id)
              chip_map.set(src_id, chip)
          }


  }

  function allowDrop(event)
  {
      event.preventDefault()
  }

  function get_label_class(fpos)
  {
      switch (fpos)
      {
          case "GK":
              return "label-success"
              break
          case "RB":
          case "CB":
          case "LB":
              return "label-warning"
          case "DM":
              return "label-info"
          case "AM":
          case "CM":
              return "label-primary"
          case "ST":
              return "label-danger"
      }
  }

  function load_formation()
  {
      $.ajax
              ({
                  url: "controller/get_teamsheet.php",
                  type: "GET",
                  dataType:'json',
                  data: {live: 0},
                  success: function (data)
                  {
                      $("#tactic_formations").val(data.formation)
                      
                      for (var j in tactic_formations[data.formation])
                      {
                          var jersey = data.xi[tactic_formations[data.formation-1][j].pos].jersey
                          var disp = jersey + ". " + data.xi[tactic_formations[data.formation-1][j].pos].lname
                          var pid = data.xi[tactic_formations[data.formation-1][j].pos].pid
                          var itr = tactic_formations[data.formation-1][j]
                          
                          var chip = $("<span class='chip' data-chip_id='"+j+"' cdata-player_id='"+pid+"' ondragover='allowDrop(event)' ondrop='player_drop_event(event)'>"+jersey+"</span>")
                          selecao.set(jersey, pid)
                          chip_map.set(pid, chip)
                          chips.push(chip)
                          
                          $(".field_view").append(chip)
                          chip.css({top: itr.t, left: itr.l, position: "absolute"})                          
                          var html = '<a href="#" class="list-group-item  player_tag" data-player_name="'+jersey+'" data-player_id="'+pid+'" draggable="true" ondragstart="player_drag_evt(event)" ondragover="allowDrop(event)" ondrop="player_rep_drop(event)"> <i class="label ' + get_label_class(data.xi[tactic_formations[data.formation-1][j].pos].pref) + '">'+data.xi[tactic_formations[data.formation-1][j].pos].pref+'</i> ' + disp + '</a>'
                          $("#starting_xi").append(html)
                      }

                      for (var j in data.subs)
                      {
                          var jersey = data.subs[j].jersey
                          var disp = data.subs[j].jersey + ". " + data.subs[j].lname
                          var pid = data.subs[j].pid
                          var html = '<a href="#" class="list-group-item player_tag" data-player_name="'+jersey+'" data-player_id="' + pid + '" draggable="true" ondragstart="player_drag_evt(event)" ondragover="allowDrop(event)" ondrop="player_rep_drop(event)"> <i class="label ' + get_label_class(data.subs[j].pref) + '">'+data.subs[j].pref+"</i> " + disp + '</a>'
                          $("#subs_bench").append(html)
                      }                      
                                            
                      if(data.tactic == 1)
                          $("#option1").prop("checked", true)
                      else if(data.tactic == 2)
                          $("#option2").prop("checked", true)
                      else
                          $("#option3").prop("checked", true)

                  }
              })
  }

  $(document).ready(function ()
  {

      load_formation()

      $("#tactic_formations").on("change", function ()
      {
          for (var i in chips) //get rid of existing chips, if any
          {
              chips[i].remove()
          }
          
          selecao.clear()
          chip_map.clear()

          var idx = parseInt($(this).val()) - 1

          if (idx < 0)
              return false

          for (var j in tactic_formations[idx])
          {
              var itr = tactic_formations[idx][j]
              var chip = $("<span class='chip' data-chip_id='"+j+"' ondragover='allowDrop(event)' ondrop='player_drop_event(event)' style='margin:2px;'></span>")
              $(".field_view").append(chip)
              chips.push(chip)

              chip_xy.set(j, {x: itr.init_x, y: itr.init_y})

              chip.css({"top": itr.t, "left": itr.l, "position": "absolute"})
          }

      })

      $("#save_btn").on("click", function ()
      {
          var xi = [], subs = []
          
          for (var i in chips)
          {
              var _chip = chips[i]
              var pid = _chip.attr("cdata-player_id")
              xi.push(pid)
          }

          $("#subs_bench a").each(function ()
          {
              subs.push($(this).attr("data-player_id"))
          })

          var game_style

          if ($("#option1").is(":checked"))
              game_style = 1
          else if ($("#option2").is(":checked"))
              game_style = 2
          else
              game_style = 3
          
          
          var formation = $("#tactic_formations").val()
          
          $.ajax({
              url: "controller/update_tactics.php",
              type: "GET",
              async: true,
              cache: false,
              data: {xi: xi, subs: subs, style: game_style, form: formation},
              success: function (result)
              {
                  if (result === "bad")
                      {
                          $("#save_feedback").removeClass("text-success").addClass("text-danger").html("Save Failed")
                      }
                  else if (result === "ok")
                      {
                          $("#save_feedback").removeClass("text-danger").addClass("text-success").html("Saved!")
                      }
                  else
                      {
                          $("#save_feedback").html(result)
                      }
              },
              error: function ()
              {
                  $("#save_feedback").removeClass("text-success").addClass("text-danger").html("Lost internet connection")
              }
          })

      })

  })
