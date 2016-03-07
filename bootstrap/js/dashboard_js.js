$(function ()
        {
            $("#see_all_invites").on("click", function ()
            {
                $("#all_invites_modal").modal('show')
            })

            $("#see_all_notifs").on("click", function ()
            {
                $("#all_notifs_modal").modal('show')
            })
            
            $("[vec='kitno_set']").on("click", function()
            {
                var dx = $(this).attr("dx");
                $("[vec='kitno_div'][dx='"+dx+"']").slideToggle()
            })
            
            $("[vec='tr_decl']").on("click", function()
            {
                var dx = $(this).attr("dx");
                $("[vec='tr_decl_opts'][dx='"+dx+"']").slideToggle() 
            })
            
            $("[vec='kitno_sb']").on("click", function()
            {
                var dx = $(this).attr("dx");
                var kit = $("[vec='kitno'][dx='"+dx+"']").val()
                
                $("[vec='fin_deal_feedback'][dx='"+dx+"']").html('<img src="img/jx/loading5.gif">')
                
                $.ajax({
                    url:"controller/complete_transfer.php",
                    type:"POST",
                    data:{trx:dx, jzx:kit},
                    async:true,
                    cache:false,
                    success:function(res)
                    {
                        if(res == "ok")
                        {
                            $("[vec='kitno_div'][dx='"+dx+"']").html('<button type="button" class="btn btn-sm btn-primary disabled" vec="kitno_set">Finalized<i class="fa fa-fw fa-check-circle"></i></button>')
                        }
                    }
                })
                
            })
            
            
            $("[vec='tr_conf']").on("click", function()
            {
                var dx = $(this).attr("dx")
                $("[vec='tr_legend'][dx='"+dx+"']").html('<button class="btn btn-primary disabled">Accepted <i class="fa fa-fw fa-check-circle"></i></button>')
                tr_response(dx, 10)
            })
            
            $("[vec='tr_decl_opts']").on("change", function()
            {
                var resp = $(this).val()
                var dx = $(this).attr("dx")
                $("[vec='tr_legend'][dx='"+dx+"']").html('<button class="btn btn-danger disabled">Rejected <i class="fa fa-fw fa-remove"></i></button>')
                tr_response(dx, resp)
            })
            
            
            
        })
        
    function tr_response(tr, resp)
    {
        $.ajax
        ({
            url:"controller/transfer_response.php",
            type:"POST",
            data:{rpx:resp, trx:tr},
            async:true
        })            
    }

  
  
