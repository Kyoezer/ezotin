var contractor=function(){
    function initialize(){
        $(".view-auditdetails").on("click",function(e){
            e.preventDefault();
            var id = $(this).data('id');
            var baseUrl = $("input[name='URL']").val();
            $.ajax({
                url: baseUrl+'/contractor/fetchauditdetails',
                dataType: 'JSON',
                type: "POST",
                data: {id:id},
                success: function(data){
                    if(data.response == 1){
                        $("#modalmessageheader").text("Audit Details");
                        $("#modaldimessagetext").html("<h4><strong>Firm: "+data.NameOfFirm+" ("+data.CDBNo+")</strong></h4><strong>Audit Details: </strong><br/>"+data.AuditObservation);
                        $("#modalmessagebox").modal('show');
                    }

                }
            });
        });
        $(".resolve-audit").on("click",function(e){
            e.preventDefault();
            var id = $(this).data('id');
            var baseUrl = $("input[name='URL']").val();
            $.ajax({
                url: baseUrl+'/contractor/fetchauditdetails',
                dataType: 'JSON',
                type: "POST",
                data: {id:id},
                success: function(data){
                    if(data.response == 1){
                        $("#audit-id").val(id);
                        $("#audit-details").html("<h4><strong>Firm: "+data.NameOfFirm+" ("+data.CDBNo+")</strong></h4><strong>Audit Details: </strong><br/>"+data.AuditObservation);
                        $("#audit-resolved-modal").modal('show');
                    }

                }
            });
        });
        $(".suspend-contractor-monitoring").on('click',function(e){
            e.preventDefault();
            var id = $(this).data('id');
            var cdbNo = $(this).data('cdbno');
            var firmName = $(this).data('firmname');
            var contractorClass = $(this).data("class");
            var monitoringId = $(this).data('monitoringid');
            $("#suspend-modal").find("#contractor-id").val(id);
            $("#suspend-modal").find("#monitoring-id").val(monitoringId);
            $("#suspend-modal").find("#cdb-no").text(cdbNo);
            $("#suspend-modal").find("#firm-name").text(firmName);
            $("#suspend-modal").find("#class").text(contractorClass);
            $("#suspend-modal").modal("show");
        });
        $('.deletecontractoradverserecord').on('click',function(){
            var parent = $(this).parents('tr');
            var id = parent.find('.adverserecordid').val();
            var url = $('input[name="URL"]').val();
            var reply = confirm("Do you want to delete this adverse record?");
            if(reply){
                $.post(url+'/contractor/deletecontractorcommentadverserecord',{id:id},function(data){
                    if(data == 1){
                        parent.remove();
                        alert("Adverse record has been deleted");
                    }
                });
            }
        });
        $('.deletecontractorcomment').on('click',function(){
            var parent = $(this).parents('tr');
            var id = parent.find('.commentid').val();
            var url = $('input[name="URL"]').val();
            var reply = confirm("Do you want to delete this comment?");
            if(reply){
                $.post(url+'/contractor/deletecontractorcommentadverserecord',{id:id},function(data){
                    if(data == 1){
                        parent.remove();
                        alert("Comment has been deleted");
                    }
                });
            }
        });
        $('#rejectcontractorregistration').on('click',function(){
            $('.remarksbyrejector').addClass('required');
            submitted = true;
            var flag = false;
            var form = $('#rejectregistrationcontractor');
            var valid = common.Validate(form);
            if(valid){
                $('#rejectregistrationcontractor').submit();
            }
        });
        $('#rejectregistration').on('hidden.bs.modal', function () {
            $('.remarksbyrejector').removeClass('required').removeClass('error');
        });
        $('#verifycontractorregistration').on('click',function(){
            submitted = true;
            var flag = false;
            var form = $('#verifyregistrationcontractor');
            var valid = common.Validate(form);
            if(!valid){
                $('#verify').modal('hide');
            }else{
                $('#verifyregistrationcontractor').submit();
            }
        });
        $('#approvecontractorregistration').on('click',function(){
            submitted = true;
            var flag = false;
            var form = $('#approveregistrationcontractor');
            var valid = common.Validate(form);
            if(!valid){
                $('#approve').modal('hide');
            }else{
                $('#approveregistrationcontractor').submit();
            }
        });
        $('#approvecontractorpaymentregistration').on('click',function(){
            submitted = true;
            var flag = false;
            var form = $('#registrationpaymentdonecontractor');
            var valid = common.Validate(form);
            if(!valid){
                $('#paymentdoneregistration').modal('hide');
            }else{
                 $('#registrationpaymentdonecontractor').submit();
            }
        });
        $('.editcontractorcomment').on('click',function(){
            var id=$(this).parent().find('.commentid').val();
            var date=$(this).parent().find('.contractorcommentdate').val();
            var comment=$(this).parent().find('.contractorcomment').val();
            $('.contractorcommentadverserecordid').val(id);
            $('.commentadverserecorddate').val(date);
            $('.commentadverserecord').val(comment);
            $('#contractorcommentadverserecordedit').modal('show');
        });
        $('.editcontractoradverserecord').on('click',function(){
            var id=$(this).parent().find('.adverserecordid').val();
            var date=$(this).parent().find('.contractoradverserecorddate').val();
            var comment=$(this).parent().find('.contractoradverserecord').val();
            $('.contractorcommentadverserecordid').val(id);
            $('.commentadverserecorddate').val(date);
            $('.commentadverserecord').val(comment);
            $('#contractorcommentadverserecordedit').modal('show');
        });
        $('.deregistercontractor').on('click',function(){
            var contractorId=$(this).parents('tr').find('.contractorid').val();
            var contractrName=$(this).parents('tr').find('.contractorname').val();
            var contractrCDBNo=$(this).parents('tr').find('.contractorcdbno').val();
            $('.deregisterid').val(contractorId);
            $('.displaycontractorname').text(contractrName);
        });
        $('.surrendercertificate').on('click',function(){
            var contractorId=$(this).parents('tr').find('.contractorid').val();
            var contractrName=$(this).parents('tr').find('.contractorname').val();
            var contractrCDBNo=$(this).parents('tr').find('.contractorcdbno').val();
            $('.surrenderid').val(contractorId);
            $('.contractorname').text(contractrName);
            $('.contractorcdbno').text(contractrCDBNo);
        });
        $('.blacklistcontractor').on('click',function(){
            var contractorId=$(this).parents('tr').find('.contractorid').val();
            var contractrName=$(this).parents('tr').find('.contractorname').val();
            var contractrCDBNo=$(this).parents('tr').find('.contractorcdbno').val();
            $('.blacklistId').val(contractorId);
            $('.displaycontractorcdbno').text('CDB No. '+contractrCDBNo);
        });
        $('.revokecontractor').on('click',function(){
            var contractorId=$(this).parents('tr').find('.contractorid').val();
            var contractrName=$(this).parents('tr').find('.contractorname').val();
            var contractrCDBNo=$(this).parents('tr').find('.contractorcdbno').val();
            $('.revokeId').val(contractorId);
            $('.displaycontractorcdbno').text('CDB No. '+contractrCDBNo);
        });
        $('.reregistrationcontractor').on('click',function(){
            var contractorId=$(this).parents('tr').find('.contractorid').val();
            var contractrName=$(this).parents('tr').find('.contractorname').val();
            var contractrCDBNo=$(this).parents('tr').find('.contractorcdbno').val();
            $('.reregisterId').val(contractorId);
            $('.displaycontractorname').text(contractrName);
            $('.displaycontractorcdbno').text('CDB No. '+contractrCDBNo);
        });
        //--------------------------------Name of Firm Check--------------------------------------------
        $("#training-type").on('change',function(){
            if($(this).val()!=''){
                var referenceNo = $('option:selected',this).data('reference').toString();
                if(referenceNo == '1602'){
                    $("#training-module").removeAttr('disabled').addClass('required');
                }else{
                    $("#training-module").val('').attr('disabled','disabled').removeClass('required').removeClass('error');
                    $('#training-module').closest('.form-group').find('span.error-span').remove();
                    if(common.Submitted){
                        validate($(this).closest('form'));
                    }
                }
            }else{
                $("#training-module").val('').attr('disabled','disabled').removeClass('required').removeClass('error');
                $('#training-module').closest('.form-group').find('span.error-span').remove();
                if(common.Submitted){
                    validate($(this).closest('form'));
                }
            }
        });
    }
    return{
        Initialize:initialize
    }
}();
$(document).ready(function(){
    contractor.Initialize();
});
