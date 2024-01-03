var survey=function(){
    function initialize(){

        $('.editsurveycomment').on('click',function(){
            var id=$(this).parent().find('.commentid').val();
            var date=$(this).parent().find('.surveycommentdate').val();
            var comment=$(this).parent().find('.surveycomment').val();
            $('.surveycommentadverserecordid').val(id);
            $('.commentadverserecorddate').val(date);
            $('.commentadverserecord').val(comment);
            $('#surveycommentadverserecordedit').modal('show');
        });
        $('.editsurveyadverserecord').on('click',function(){
            var id=$(this).parent().find('.adverserecordid').val();
            var date=$(this).parent().find('.surveyadverserecorddate').val();
            var comment=$(this).parent().find('.surveyadverserecord').val();
            $('.surveycommentadverserecordid').val(id);
            $('.commentadverserecorddate').val(date);
            $('.commentadverserecord').val(comment);
            $('#surveycommentadverserecordedit').modal('show');
        });

        $('.deletesurveyadverserecord').on('click',function(){
            var parent = $(this).parent();
            var id = parent.find('.adverserecordid').val();
            var url = $('input[name="URL"]').val();
            var reply = confirm("Do you want to delete this adverse record?");
            if(reply){
                $.post(url+'/surveyor/deletesurveycommentadverserecord',{id:id},function(data){
                    if(data == 1){
                        parent.remove();
                        alert("Adverse record has been deleted");
                    }
                });
            }
        });
        $('.deletesurveycomment').on('click',function(){
            var parent = $(this).parent();
            var id = parent.find('.commentid').val();
            var url = $('input[name="URL"]').val();
            var reply = confirm("Do you want to delete this comment?");
            if(reply){
                $.post(url+'/surveyor/deleteasurveycommentadverserecord',{id:id},function(data){
                    if(data == 1){
                        parent.remove();
                        alert("Comment has been deleted");
                    }
                });
            }
        });
    
        $('.deregistersurvey').on('click',function(){
            var surveyId=$(this).parents('tr').find('.surveyid').val();
             var surveyName=$(this).parents('tr').find('.surveyname').val();
            var surveyARNo=$(this).parents('tr').find('.surveyarno').val();
            $('.deregisterid').val(surveyId);
            $('.displaysurveyname').text(surveyName);
            $('.displaysurveyarno').text('AR No. '+surveyARNo);
        });
        $('.blacklistsurvey').on('click',function(){
            var surveyId=$(this).parents('tr').find('.surveyid').val();
            var surveyName=$(this).parents('tr').find('.surveyname').val();
            var surveyARNo=$(this).parents('tr').find('.surveyarno').val();
            $('.blacklistId').val(surveyId);
             $('.displaysurveyname').text(surveyName);
            $('.displaysurveyarno').text('AR No. '+surveyARNo);
        });
         $('.reregistrationsurvey').on('click',function(){
            var surveyId=$(this).parents('tr').find('.surveyid').val();
            var surveyName=$(this).parents('tr').find('.surveyname').val();
            var surveyARNo=$(this).parents('tr').find('.surveyarno').val();
            $('.reregisterId').val(surveyId);
            $('.displaysurveyname').text(surveyName);
            $('.displaysurveyarno').text('AR No. '+surveyARNo);
        });
    }
 
return{
    Initialize:initialize
}
}();
$(document).ready(function(){
    survey.Initialize();
});
