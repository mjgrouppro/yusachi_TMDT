<div class="payment-button-group">
    <button type="button" class="btn btn-default" onclick='backCheckout()'>Back</button>
    <input id="button-confirm" type="button" class="btn btn-primary" value="Confirm" />
</div>
<script type="text/javascript">
	$("#button-confirm").click(function(){
        $this = $(this);

        $this.prop('disabled',true);

        $.ajax({
            url:'<?= $gatewayData['payment_confirmation'] ?>',
            type:'POST',
            dataType:'json',
            data:$('[name^="comment"]').serialize(),
            beforeSend:function(){$("#button-confirm").btn("loading");},
            complete:function(){$("#button-confirm").btn("reset");},
            success:function(json){
                $container = $("#checkout-confirm");
                $container.find(".has-error").removeClass("has-error");
                $container.find("span.text-danger").remove();

                if(json['errors']){
                    $.each(json['errors']['comment'], function(ii,jj){
                        $ele = $container.find('#comment_textarea'+ ii);
                        if($ele){
                            $ele.parents(".form-group").addClass("has-error");
                            $ele.after("<span class='text-danger'>"+ jj +"</span>");
                        }
                    });
                }

                if(json['success']){
                    $.ajax({
                        url:'<?= $gatewayData['confirm_payment'] ?>',
                        type:'POST',
                        dataType:'json',
                        data:$("#yandex_form").serialize(),
                        beforeSend:function(){
                            $this.prop("disabled",1);
                        },
                        complete:function(){
                            $this.prop("disabled",0);
                        },
                        success:function(json){
                            if(json['confirmationUrl'])
                            	window.location.href = json['confirmationUrl']
                        },
                    });
                }
            },
        });
    })
</script>