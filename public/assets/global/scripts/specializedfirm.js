var specializedtrade=function(){
    function initialize(){
        $('.deletespecializedtradeadverserecord').on('click',function(){
            var parent = $(this).parents('tr');
            var id = parent.find('.adverserecordid').val();
            var url = $('input[name="URL"]').val();
            var reply = confirm("Do you want to delete this adverse record?");
            if(reply){
                $.post(url+'/specializedfirm/deletespecializedtradecommentadverserecord',{id:id},function(data){
                    if(data == 1){
                        parent.remove();
                        alert("Adverse record has been deleted");
                    }
                });
            }
        });
        $('.deletespecializedtradecomment').on('click',function(){
            var parent = $(this).parents('tr');
            var id = parent.find('.commentid').val();
            var url = $('input[name="URL"]').val();
            var reply = confirm("Do you want to delete this comment?");
            if(reply){
                $.post(url+'/specializedfirm/deletespecializedtradecommentadverserecord',{id:id},function(data){
                    if(data == 1){
                        parent.remove();
                        alert("Comment has been deleted");
                    }
                });
            }
        });
         
        $('.editspecializedtradecomment').on('click',function(){
            var id=$(this).parent().find('.commentid').val();
            var date=$(this).parent().find('.specializedtradecommentdate').val();
            var comment=$(this).parent().find('.specializedtradecomment').val();
            $('.specializedtradecommentadverserecordid').val(id);
            $('.commentadverserecorddate').val(date);
            $('.commentadverserecord').val(comment);
            $('#specializedtradecommentadverserecordedit').modal('show');
        });
        $('.editspecializedtradeadverserecord').on('click',function(){
            var id=$(this).parent().find('.adverserecordid').val();
            var date=$(this).parent().find('.specializedtradeadverserecorddate').val();
            var comment=$(this).parent().find('.specializedtradeadverserecord').val();
            $('.specializedtradecommentadverserecordid').val(id);
            $('.commentadverserecorddate').val(date);
            $('.commentadverserecord').val(comment);
            $('specializedtradecommentadverserecordedit').modal('show');
        });

        $('.deregisterspecializedtrade').on('click',function(){
            var specializedtradeId=$(this).parents('tr').find('.specializedtradeid').val();
            var specializedTradeName=$(this).parents('tr').find('.specializedtradename').val();
            var specializedTradeARNo=$(this).parents('tr').find('.specializedtradespno').val();
            $('.deregisterid').val(specializedtradeId);
            $('.displayspecializedtradename').text(specializedTradeName);
            $('.displayspecializedtradespno').text('SP No. '+specializedTradeARNo);
        });
        $('.blacklistspecializedtrade').on('click',function(){
            var specializedtradeId=$(this).parents('tr').find('.specializedtradeid').val();
            var specializedTradeName=$(this).parents('tr').find('.specializedtradename').val();
            var specializedTradeARNo=$(this).parents('tr').find('.specializedtradespno').val();
            $('.blacklistId').val(specializedtradeId);
            $('.displayspecializedtradename').text(specializedTradeName);
            $('.displayspecializedtradespno').text('SP No. '+specializedTradeARNo);

        });
        $('.reregistrationspecializedtrade').on('click',function(){
            var specializedtradeId=$(this).parents('tr').find('.specializedtradeid').val();
            var specializedTradeName=$(this).parents('tr').find('.specializedtradename').val();
            var specializedTradeARNo=$(this).parents('tr').find('.specializedtradespno').val();
            $('.reregisterId').val(specializedtradeId);
            $('.displayspecializedtradename').text(specializedTradeName);
            $('.displayspecializedtradespno').text('SP No. '+specializedTradeARNo);
        });
        

        //-------------------------------Certified Builder -----------------------------------------

        $('.deregistercertifiedbuilder').on('click',function(){
            var specializedtradeId=$(this).parents('tr').find('.specializedtradeid').val();
            var specializedTradeName=$(this).parents('tr').find('.specializedtradename').val();
            var specializedTradeARNo=$(this).parents('tr').find('.specializedtradespno').val();
            $('.deregisterid').val(specializedtradeId);
            $('.displayspecializedtradename').text(specializedTradeName);
            $('.displayspecializedtradespno').text('Certified Builder No. '+specializedTradeARNo);
        });
        $('.blacklistcertifiedbuilder').on('click',function(){
            var specializedtradeId=$(this).parents('tr').find('.specializedtradeid').val();
            var specializedTradeName=$(this).parents('tr').find('.specializedtradename').val();
            var specializedTradeARNo=$(this).parents('tr').find('.specializedtradespno').val();
            $('.blacklistId').val(specializedtradeId);
            $('.displayspecializedtradename').text(specializedTradeName);
            $('.displayspecializedtradespno').text('Certified Builder No. '+specializedTradeARNo);

        });
        $('.reregistrationcertifiedbuilder').on('click',function(){
            var specializedtradeId=$(this).parents('tr').find('.specializedtradeid').val();
            var specializedTradeName=$(this).parents('tr').find('.specializedtradename').val();
            var specializedTradeARNo=$(this).parents('tr').find('.specializedtradespno').val();
            $('.reregisterId').val(specializedtradeId);
            $('.displayspecializedtradename').text(specializedTradeName);
            $('.displayspecializedtradespno').text('Certified Builder No. '+specializedTradeARNo);
        });

        //--------------------------------Name of Firm Check--------------------------------------------
    }
    return{
        Initialize:initialize
    }
}();
$(document).ready(function(){
    specializedtrade.Initialize();
});
