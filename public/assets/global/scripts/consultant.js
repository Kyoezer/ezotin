var consultant=function(){
    function initialize(){
        $('.deleteconsultantadverserecord').on('click',function(){
            var parent = $(this).parents('tr');
            var id = parent.find('.adverserecordid').val();
            var url = $('input[name="URL"]').val();
            var reply = confirm("Do you want to delete this adverse record?");
            if(reply){
                $.post(url+'/consultant/deleteconsultantcommentadverserecord',{id:id},function(data){
                    if(data == 1){
                        parent.remove();
                        alert("Adverse record has been deleted");
                    }
                });
            }
        });
        $('.deleteconsultantcomment').on('click',function(){
            var parent = $(this).parents('tr');
            var id = parent.find('.commentid').val();
            var url = $('input[name="URL"]').val();
            var reply = confirm("Do you want to delete this comment?");
            if(reply){
                $.post(url+'/consultant/deleteconsultantcommentadverserecord',{id:id},function(data){
                    if(data == 1){
                        parent.remove();
                        alert("Comment has been deleted");
                    }
                });
            }
        });
         $('#rejectconsultantregistration').on('click',function(){
            $('.remarksbyrejector').addClass('required');
            submitted = true;
            var flag = false;
            var form = $('#rejectregistrationconsultant');
            var valid = common.Validate(form);
            if(valid){
                $('#rejectregistrationconsultant').submit();
            }
        });
        $('#rejectregistration').on('hidden.bs.modal', function () {
            $('.remarksbyrejector').removeClass('required').removeClass('error');
        });
        $('#verifyconsultantregistration').on('click',function(){
            submitted = true;
            var flag = false;
            var form = $('#verifyregistrationconsultant');
            var valid = common.Validate(form);
            if(!valid){
                $('#verify').modal('hide');
            }else{
                $('#verifyregistrationconsultant').submit();
            }
        });
        $('#approveconsultantregistration').on('click',function(){
            submitted = true;
            var flag = false;
            var form = $('#approveregistrationconsultant');
            var valid = common.Validate(form);
            if(!valid){
                $('#approve').modal('hide');
            }else{
                $('#approveregistrationconsultant').submit();
            }
        });
        $('#approveconsultantpaymentregistration').on('click',function(){
            submitted = true;
            var flag = false;
            var form = $('#registrationpaymentdoneconsultant');
            var valid = common.Validate(form);
            if(!valid){
                $('#paymentdoneregistration').modal('hide');
            }else{
                $('#registrationpaymentdoneconsultant').submit();
            }
        });
        $('.editconsultantcomment').on('click',function(){
            var id=$(this).parent().find('.commentid').val();
            var date=$(this).parent().find('.consultantcommentdate').val();
            var comment=$(this).parent().find('.consultantcomment').val();
            $('.consultantcommentadverserecordid').val(id);
            $('.commentadverserecorddate').val(date);
            $('.commentadverserecord').val(comment);
            $('#consultantcommentadverserecordedit').modal('show');
        });
        $('.editconsultantadverserecord').on('click',function(){
            var id=$(this).parent().find('.adverserecordid').val();
            var date=$(this).parent().find('.consultantadverserecorddate').val();
            var comment=$(this).parent().find('.consultantadverserecord').val();
            $('.consultantcommentadverserecordid').val(id);
            $('.commentadverserecorddate').val(date);
            $('.commentadverserecord').val(comment);
            $('#consultantcommentadverserecordedit').modal('show');
        });
        $('.deregisterconsultant').on('click',function(){
            var consultantId=$(this).parents('tr').find('.consultantid').val();
            var consultantName=$(this).parents('tr').find('.consultantname').val();
            var consultantCDBNo=$(this).parents('tr').find('.consultantcdbno').val();
            $('.deregisterid').val(consultantId);
            $('.displaycontractorname').text(consultantName);
            $('.displaycontractorcdbno').text('CDB No. '+consultantCDBNo);
        });
        $('.blacklistconsultant').on('click',function(){
            var consultantId=$(this).parents('tr').find('.consultantid').val();
            var consultantName=$(this).parents('tr').find('.consultantname').val();
            var consultantCDBNo=$(this).parents('tr').find('.consultantcdbno').val();
            $('.blacklistId').val(consultantId);
            $('.displaycontractorname').text(consultantName);
            $('.displaycontractorcdbno').text('CDB No. '+consultantCDBNo);
        });
        $('.reregistrationconsultant').on('click',function(){
            var contractorId=$(this).parents('tr').find('.consultantid').val();
            var consultantName=$(this).parents('tr').find('.consultantname').val();
            var consultantCDBNo=$(this).parents('tr').find('.consultantcdbno').val();
            $('.reregisterId').val(contractorId);
            $('.displaycontractorname').text(consultantName);
            $('.displaycontractorcdbno').text('CDB No. '+consultantCDBNo);
        });
        $('.consultantservicecheck').on('change',function(){
            if($(this).is(':checked')){
                $(this).parents('span').find('.setcheckcontrol').removeAttr('disabled');
            }else{
                $(this).parents('span').find('.setcheckcontrol').attr('disabled','disabled');
            }
        });
        //--------------------------------Name of Firm Check--------------------------------------------
    }
    return{
        Initialize:initialize
    }
}();
$(document).ready(function(){
    consultant.Initialize();
});
