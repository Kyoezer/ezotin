var engineer=function(){
    function initialize(){
        $('#rejectengineerregistration').on('click',function(){
            $('.remarksbyrejector').addClass('required');
            submitted = true;
            var flag = false;
            var form = $('#rejectregistrationengineer');
            var valid = common.Validate(form);
            if(valid){
                $('#rejectregistrationengineer').submit();
            }
        });
        $('#rejectregistration').on('hidden.bs.modal', function () {
            $('.remarksbyrejector').removeClass('required').removeClass('error');
        });
        $('#verifyengineerregistration').on('click',function(){
            submitted = true;
            var flag = false;
            var form = $('#verifyregistrationengineer');
            var valid = common.Validate(form);
            if(!valid){
                $('#verify').modal('hide');
            }else{
                $('#verifyregistrationengineer').submit();
            }
        });
        $('#approveengineerregistration').on('click',function(){
            submitted = true;
            var flag = false;
            var form = $('#approveregistrationengineer');
            var valid = common.Validate(form);
            if(!valid){
                $('#approve').modal('hide');
            }else{
                $('#approveregistrationengineer').submit();
            }
        });
        $('#approveengineerpaymentregistration').on('click',function(){
            submitted = true;
            var flag = false;
            var form = $('#registrationpaymentdoneengineer');
            var valid = common.Validate(form);
            if(!valid){
                $('#paymentdoneregistration').modal('hide');
            }else{
                $('#registrationpaymentdoneengineer').submit();
            }
        });
         $('.editengineercomment').on('click',function(){
            var id=$(this).parent().find('.commentid').val();
            var date=$(this).parent().find('.engineercommentdate').val();
            var comment=$(this).parent().find('.engineercomment').val();
            $('.engineercommentadverserecordid').val(id);
            $('.commentadverserecorddate').val(date);
            $('.commentadverserecord').val(comment);
            $('#engineercommentadverserecordedit').modal('show');
        });
        $('.editengineeradverserecord').on('click',function(){
            var id=$(this).parent().find('.adverserecordid').val();
            var date=$(this).parent().find('.engineeradverserecorddate').val();
            var comment=$(this).parent().find('.engineeradverserecord').val();
            $('.engineercommentadverserecordid').val(id);
            $('.commentadverserecorddate').val(date);
            $('.commentadverserecord').val(comment);
            $('#engineercommentadverserecordedit').modal('show');
        });
        $('.deleteengineeradverserecord').on('click',function(){
            var parent = $(this).parent();
            var id = parent.find('.adverserecordid').val();
            var url = $('input[name="URL"]').val();
            var reply = confirm("Do you want to delete this adverse record?");
            if(reply){
                $.post(url+'/engineer/deleteengineercommentadverserecord',{id:id},function(data){
                    if(data == 1){
                        parent.remove();
                        alert("Adverse record has been deleted");
                    }
                });
            }
        });
        $('.deleteengineercomment').on('click',function(){
            var parent = $(this).parent();
            var id = parent.find('.commentid').val();
            var url = $('input[name="URL"]').val();
            var reply = confirm("Do you want to delete this comment?");
            if(reply){
                $.post(url+'/engineer/deleteengineercommentadverserecord',{id:id},function(data){
                    if(data == 1){
                        parent.remove();
                        alert("Comment has been deleted");
                    }
                });
            }
        });
        $('.deregisterengineer').on('click',function(){
            var engineerId=$(this).parents('tr').find('.engineerid').val();
            var engineerName=$(this).parents('tr').find('.engineername').val();
            var engineerARNo=$(this).parents('tr').find('.engineercdbno').val();
            $('.deregisterid').val(engineerId);
            $('.displayengineername').text(engineerName);
            $('.displayengineercdbno').text('CDB No. '+engineerARNo);
        });
        $('.blacklistengineer').on('click',function(){
            var engineerId=$(this).parents('tr').find('.engineerid').val();
            var engineerName=$(this).parents('tr').find('.engineername').val();
            var engineerARNo=$(this).parents('tr').find('.engineercdbno').val();
            $('.blacklistId').val(engineerId);
             $('.displayengineername').text(engineerName);
            $('.displayengineercdbno').text('CDB No. '+engineerARNo);
        });
        $('.reregistrationengineer').on('click',function(){
            var engineerId=$(this).parents('tr').find('.engineerid').val();
            var engineerName=$(this).parents('tr').find('.engineername').val();
            var engineerARNo=$(this).parents('tr').find('.engineercdbno').val();
            $('.reregisterId').val(engineerId);
            $('.displayengineername').text(engineerName);
            $('.displayengineercdbno').text('CDB No. '+engineerARNo);
        });
    }
    return{
        Initialize:initialize
    }
}();
$(document).ready(function(){
    engineer.Initialize();
});
