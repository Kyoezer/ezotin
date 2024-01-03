var architect=function(){
    function initialize(){
        $('.deletearchitectadverserecord').on('click',function(){
            var parent = $(this).parent();
            var id = parent.find('.adverserecordid').val();
            var url = $('input[name="URL"]').val();
            var reply = confirm("Do you want to delete this adverse record?");
            if(reply){
                $.post(url+'/architect/deletearchitectcommentadverserecord',{id:id},function(data){
                    if(data == 1){
                        parent.remove();
                        alert("Adverse record has been deleted");
                    }
                });
            }
        });
        $('.deletearchitectcomment').on('click',function(){
            var parent = $(this).parent();
            var id = parent.find('.commentid').val();
            var url = $('input[name="URL"]').val();
            var reply = confirm("Do you want to delete this comment?");
            if(reply){
                $.post(url+'/architect/deletearchitectcommentadverserecord',{id:id},function(data){
                    if(data == 1){
                        parent.remove();
                        alert("Comment has been deleted");
                    }
                });
            }
        });
        $('#rejectarchitectregistration').on('click',function(){
            $('.remarksbyrejector').addClass('required');
            submitted = true;
            var flag = false;
            var form = $('#rejectregistrationarchitect');
            var valid = common.Validate(form);
            if(valid){
                $('#rejectregistrationarchitect').submit();
            }
        });
        $('#rejectregistration').on('hidden.bs.modal', function () {
            $('.remarksbyrejector').removeClass('required').removeClass('error');
        });
        $('#verifyarchitectregistration').on('click',function(){
            submitted = true;
            var flag = false;
            var form = $('#verifyregistrationarchitect');
            var valid = common.Validate(form);
            if(!valid){
                $('#verify').modal('hide');
            }else{
                $('#verifyregistrationarchitect').submit();
            }
        });
        $('#approvearchitectregistration').on('click',function(){
            submitted = true;
            var flag = false;
            var form = $('#approveregistrationarchitect');
            var valid = common.Validate(form);
            if(!valid){
                $('#approve').modal('hide');
            }else{
                $('#approveregistrationarchitect').submit();
            }
        });
        $('#approvearchitectpaymentregistration').on('click',function(){
            submitted = true;
            var flag = false;
            var form = $('#registrationpaymentdonearchitect');
            var valid = common.Validate(form);
            if(!valid){
                $('#paymentdoneregistration').modal('hide');
            }else{
                $('#registrationpaymentdonearchitect').submit();
            }
        });
        $('.editarchitectcomment').on('click',function(){
            var id=$(this).parent().find('.commentid').val();
            var date=$(this).parent().find('.architectcommentdate').val();
            var comment=$(this).parent().find('.architectcomment').val();
            $('.architectcommentadverserecordid').val(id);
            $('.commentadverserecorddate').val(date);
            $('.commentadverserecord').val(comment);
            $('#architectcommentadverserecordedit').modal('show');
        });
        $('.editarchitectadverserecord').on('click',function(){
            var id=$(this).parent().find('.adverserecordid').val();
            var date=$(this).parent().find('.architectadverserecorddate').val();
            var comment=$(this).parent().find('.architectadverserecord').val();
            $('.architectcommentadverserecordid').val(id);
            $('.commentadverserecorddate').val(date);
            $('.commentadverserecord').val(comment);
            $('#architectcommentadverserecordedit').modal('show');
        });
        $('.deregisterarchitect').on('click',function(){
            var architectId=$(this).parents('tr').find('.architectid').val();
             var architectName=$(this).parents('tr').find('.architectname').val();
            var architectARNo=$(this).parents('tr').find('.architectarno').val();
            $('.deregisterid').val(architectId);
            $('.displayarchitectname').text(architectName);
            $('.displayarchitectarno').text('AR No. '+architectARNo);
        });
        $('.blacklistarchitect').on('click',function(){
            var architectId=$(this).parents('tr').find('.architectid').val();
            var architectName=$(this).parents('tr').find('.architectname').val();
            var architectARNo=$(this).parents('tr').find('.architectarno').val();
            $('.blacklistId').val(architectId);
             $('.displayarchitectname').text(architectName);
            $('.displayarchitectarno').text('AR No. '+architectARNo);
        });
         $('.reregistrationarchitect').on('click',function(){
            var architectId=$(this).parents('tr').find('.architectid').val();
            var architectName=$(this).parents('tr').find('.architectname').val();
            var architectARNo=$(this).parents('tr').find('.architectarno').val();
            $('.reregisterId').val(architectId);
            $('.displayarchitectname').text(architectName);
            $('.displayarchitectarno').text('AR No. '+architectARNo);
        });
    }
    return{
        Initialize:initialize
    }
}();
$(document).ready(function(){
    architect.Initialize();
});
