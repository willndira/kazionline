  /* global Metronic, Layout, QuickSidebar, Index, Tasks */

  var tomorrow = new Date()
  tomorrow.setDate(tomorrow.getDate() + 1)

  var allowed_exts = "csv,xls,xlsx,txt,png,jpg,gif,bmp,avi,mpg,mpeg,mp4,mp3,wav,flv,pdf,doc,docx,ppt,pptx,psd,pub,7z,ppd,bz,bz2,ico,jar,phar,tex,latex,wma,wmx,wmv,mpga,mp4a,oda,oxt,ogx,oga,ogv,odb,rar,tar,xz,zip,gtar,tiff";

  var wrapper = $('.page-quick-sidebar-wrapper');
  var wrapperChat = wrapper.find('.page-quick-sidebar-chat');

  $(document).ready(function ()
  {
      Metronic.init(); // init metronic core componets
      Layout.init(); // init layout
      QuickSidebar.init(); // init quick sidebar              
      Index.init();
      Index.initCharts(); // init index page's custom scripts
      Index.initChat();
      Index.initMiniCharts();
      Tasks.initDashboardWidget();

      new_chat = {}
      sidebar_fix = 0
      sidebar_on = 0

      $("body").delegate(".chat-list", "click", function ()
      {
          var vec = $(this).attr("vec")

          $(".page-quick-sidebar-item").hide()
          $(".page-quick-sidebar-item[vec='" + vec + "']").css("display", "block")
      })

      $("#xtranotif_toggle").on("click", function ()
      {
          $(".xtra-notif").toggle()
      })

      $("#xlog-toggle").on("click", function ()
      {
          $(".xlog").toggle()
      })

      $("#xtasks-toggle").on("click", function ()
      {
          $(".xtasks").toggle()
      })
      
      
      $("#invite_chat").select2({
          placeholder: "Search Name",
          allowClear: true,
          maximumSelectionLength: 1,
          ajax: {
              url: "controller/search_users_chat.php",
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
              return markup
          },
          minimumInputLength: 3,
          templateResult: formatHint,
          templateSelection: formatHintSelection
      })

      $("#conf_new_chat").on("click", function ()
      {
          var chat_entry = '<li class="media chat-list" vec="' + new_chat.id + '">' +
                  '<img class="media-object" src="' + new_chat.avatar + '" alt="...">' +
                  '<div class="media-body">' +
                  '<h4 class="media-heading">' + new_chat.names + '</h4>' +
                  '<div class="media-heading-sub"><span style="opacity:0">0</span></div>' +
                  '</div></li>'

          $("#chats-list").append(chat_entry)

          var chat = '<div class="page-quick-sidebar-item" vec="' + new_chat.id + '">' +
                  '<div class="page-quick-sidebar-chat-user">' +
                  '<div class="page-quick-sidebar-nav">' +
                  '<a href="javascript:;" class="page-quick-sidebar-back-to-list"><i class="icon-arrow-left"></i>Back</a>' +
                  '</div>' +
                  '<div class="page-quick-sidebar-chat-user-messages"  vec="' + new_chat.id + '">' +
                  '<div class="page-quick-sidebar-chat-user-form">' +
                  '<div class="input-group">' +
                  '<input type="text" class="form-control send-chat-msg" vec="' + new_chat.id + '" placeholder="Type a message here...">' +
                  '<div class="input-group-btn">' +
                  '<button type="button" class="btn blue send-chat" vec="' + new_chat.id + '"><i class="icon-paper-clip"></i></button>' +
                  '</div>' +
                  '</div>' +
                  '</div>' +
                  '</div>' +
                  '</div>'


          $("#quick_sidebar_tab_1").append(chat)
          $("#new_chat_back").trigger("click")

          sidebar_fix = 1

          QuickSidebar.init(); // init quick sidebar
      })

      $(".dropdown-quick-sidebar-toggler a").on("click", function ()
      {
          sidebar_on = sidebar_on == 0 ? 1 : 0
      })

      $("[name='payment_model']").on("change", function ()
      {
          if ($(this).val() == "hourly")
              {
                  $("#hours_no_panel").show()
                  $("#job_amount_min").attr("placeholder", "Minimum per Hour")
                  $("#job_amount_max").attr("placeholder", "Maximum per Hour")
              }
          else
              {
                  $("#job_amount_min").attr("placeholder", "Minimum Budget")
                  $("#job_amount_max").attr("placeholder", "Maximum Budget")
                  $("#hours_no_panel").hide()
              }
      })

      $("#sb_job_create_form").on("click", function ()
      {
          $("#job_create_form").trigger("submit")
      })

      var options1 =
              {
                  complete: function (response)
                  {
                      if (response.responseText != "ok")
                          {
                              $("#job-feedback").html('<span class="text-danger"><strong>Error!:</strong> ' + response.responseText + '</span>')

                          }
                      else
                          {
                              $("#job-feedback").html('<span class="text-success"><strong>Successful!: </strong>People will view and bid for your job<br><em>Just a minute&hellip;</em></div>')

                              setTimeout(function ()
                              {
                                  $("#new_job_modal").modal("hide")
                              }, 2000)
                          }
                  }
              }

      $("#job_create_form").ajaxForm(options1)

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
                      $("#job-feedback").html('<span class="text-warning"><strong>Oops:</strong> Lost Connection</span>')

                  },
                  onSuccess: function (files, data, xhr, pd)
                  {
                      var res = new String(data)
                      if (res.substr(0, 2) == "E:")
                          {
                              $("#job-feedback").html('<span class="text-danger"><strong>Error uploading ' + files + ':</strong> ' + res.substring(2) + '</span>')
                              pd.statusbar.hide()
                          }
                  }
              })

      $("#job_tags, #search_tags").tagsinput({
          tagClass: 'label label-primary',
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
      
      var tags_str = $("#tag-ids").html()      
      var tag_ids = JSON.parse(tags_str)      

      for (var j in tag_ids)
      {
        $("#search_tags").tagsinput('add', tag_ids[j])
      }
      
      $(".pagination li a").on("click", function()
      {
          var dx = $(this).attr("dx")
          if(dx == null)
              return;
          
          $("#search_page").val(dx)
          $("#search_sb").trigger("click")
      })


      wrapperChat.find('.page-quick-sidebar-chat-user-form .btn').click(handleChatMessagePost);
      wrapperChat.find('.page-quick-sidebar-chat-user-form .form-control').keypress(function (e)
      {
          if (e.which == 13)
              {
                  var time = new Date();
                  var source = $(e.target)
                  var msg = source.val()
                  var my_pic = $("#user-prof-pic").attr("src")
                  handleChatMessagePost(e, 'out', (time.getHours() + ':' + time.getMinutes()), "Me", my_pic, msg);
                  return false;
              }
      });

      setInterval(function ()
      {
          if (sidebar_on == 0 && $("body").hasClass("page-quick-sidebar-open") && sidebar_fix > 0)
              {
                  $("body").removeClass("page-quick-sidebar-open")
              }
          else if (sidebar_on == 1 && !$("body").hasClass("page-quick-sidebar-open") && sidebar_fix > 0)
              {
                  $("body").addClass("page-quick-sidebar-open")
              }
      }, 50)

      $("#nav_see_all_chats").on("click", function ()
      {
          $("#chats-side-swipe").trigger("click")
      })
      

      /*
       setInterval(function ()
       {
       $.getJSON("controller/refresh_chats.php", {}, function (data)
       {
       //set up new chats
       for (var i in data.new_threads)
       {
       var chat_entry = '<li class="media chat-list" vec="' + i + '">'
       
       if(data.pal_unread[i] > 0)
       {
       chat_entry += '<div class="media-status"><span class="badge badge-danger">'+data.pal_unread[i]+'</span></div>'
       }
       
       chat_entry += '<img class="media-object" src="' + data.pal_data[i].avatar + '" alt="...">' +
       '<div class="media-body">' +
       '<h4 class="media-heading">' + data.pal_data[i].names + '</h4>' +
       '<div class="media-heading-sub"><span style="opacity:0">0</span></div>' +
       '</div></li>'
       
       $("#chats-list").append(chat_entry)
       
       for (var j in data.new_threads[i])
       {
       var chat = '<div class="page-quick-sidebar-item" vec="' + i + '">' +
       '<div class="page-quick-sidebar-chat-user">' +
       '<div class="page-quick-sidebar-nav">' +
       '<a href="javascript:;" class="page-quick-sidebar-back-to-list"><i class="icon-arrow-left"></i>Back</a>' +
       '</div>' +
       '<div class="page-quick-sidebar-chat-user-messages"  vec="' + i + '">' +
       '<div class="page-quick-sidebar-chat-user-form">' +
       '<div class="input-group">' +
       '<input type="text" class="form-control send-chat-msg" vec="' + i + '" placeholder="Type a message here...">' +
       '<div class="input-group-btn">' +
       '<button type="button" class="btn blue send-chat" vec="' + i + '"><i class="icon-paper-clip"></i></button>' +
       '</div>' +
       '</div>' +
       '</div>' +
       '</div>' +
       '</div>'
       
       
       $("#quick_sidebar_tab_1").append(chat)
       $("#new_chat_back").trigger("click")
       
       sidebar_fix = 1
       
       QuickSidebar.init(); // init quick sidebar
       
       var chatContainer = wrapperChat.find(".page-quick-sidebar-chat-user-messages[vec='" + i + "']");
       
       var tpl = '';
       var dir = data.new_threads[i][j].is_me == 1 ? "out" : "in"
       
       tpl += '<div class="post "' + dir + '">';
       tpl += '<img class="avatar" alt="" src="' + data.new_threads[i][j].avatar + '"/>';
       tpl += '<div class="message">';
       tpl += '<span class="arrow"></span>';
       tpl += '<a href="#" class="name">' + data.new_threads[i][j].names + '</a>&nbsp;';
       tpl += '<span class="datetime">' + data.new_threads[i][j].time + '</span>';
       tpl += '<span class="body">';
       tpl += data.new_threads[i][j].msg;
       tpl += '</span>';
       tpl += '</div>';
       tpl += '</div>';
       
       
       var message = $(tpl);
       chatContainer.append(message);
       var getLastPostPos = function ()
       {
       var height = 0;
       chatContainer.find(".post").each(function ()
       {
       height = height + $(this).outerHeight();
       });
       
       return height;
       };
       
       chatContainer.slimScroll({
       scrollTo: getLastPostPos()
       });
       
       }
       }
       
       if(data.total_unread > 0)
       {
       $(".chats_total_unread_1").css("display", "none").text("")
       $(".chats_total_unread_2").text("no")
       }
       else
       {
       $(".chats_total_unread_1,.chats_total_unread_2").css("display", "block").text(data.total_unread)
       }
       
       
       })
       }, 2000)
       
       */


  });

  function formatHint(hint)
  {
      if (hint.loading)
          return hint.names

      new_chat.names = hint.names
      new_chat.id = hint.id
      new_chat.avatar = hint.avatar

      var markup = '<div class="clearfix"><div class="col-lg-2"><img src="' + hint.avatar + '" style="width: 40px; height:auto;"></div><div class="col-lg-9">&nbsp;&nbsp;&nbsp;' + hint.names + '</div></div>'

      return markup
  }

  function formatHintSelection(hint)
  {
      return hint.names || hint.text
  }

  var handleChatMessagePost = function (e, dir, time, name, avatar, message)
  {
      e.preventDefault();

      var source = $(e.target)
      var vec = source.attr("vec")

      var chatContainer = wrapperChat.find(".page-quick-sidebar-chat-user-messages[vec='" + vec + "']");
      var input = wrapperChat.find('.page-quick-sidebar-chat-user-form .form-control[vec="' + vec + '"]');

      var text = input.val();
      if (text.length === 0 || !vec || vec.length === 0)
          {
              return;
          }

      var preparePost = function ()
      {
          var tpl = '';
          tpl += '<div class="post ' + dir + '">';
          tpl += '<img class="avatar" alt="" src="' + avatar + '"/>';
          tpl += '<div class="message">';
          tpl += '<span class="arrow"></span>';
          tpl += '<a href="#" class="name">' + name + '</a>&nbsp;';
          tpl += '<span class="datetime">' + time + '</span>';
          tpl += '<span class="body">';
          tpl += message;
          tpl += '</span>';
          tpl += '</div>';
          tpl += '</div>';

          return tpl;
      };

      // handle post
      //var time = new Date();
      var message = preparePost();
      message = $(message);
      chatContainer.append(message);

      var getLastPostPos = function ()
      {
          var height = 0;
          chatContainer.find(".post").each(function ()
          {
              height = height + $(this).outerHeight();
          });

          return height;
      };

      chatContainer.slimScroll({
          scrollTo: getLastPostPos()
      });

      input.val("");

      // simulate reply
      /*
       setTimeout(function(){
       var time = new Date();
       var message = preparePost('in', (time.getHours() + ':' + time.getMinutes()), "Ella Wong", 'avatar2', 'Lorem ipsum doloriam nibh...');
       message = $(message);
       chatContainer.append(message);
       
       chatContainer.slimScroll({
       scrollTo: getLastPostPos()
       });
       }, 3000);*/

      $.ajax({url: "controller/send_chat_msg.php", type: "POST", async: false, data: {rdx: vec, msx: text},
          success: function (data)
          {
              if (data != "ok")
                  alert("Error: " + data)
          },
          error: function ()
          {
              alert("Message not sent. Internet connection lost")
          }})
  };
        