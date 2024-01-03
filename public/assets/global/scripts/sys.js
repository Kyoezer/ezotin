var system=function(){
	function initialize(){
		$('.resetuserpassword').on('click',function(){
			var reference=$(this).closest('td').find('.userreferencemodel').val();
			var referenceName=$(this).closest('td').find('.userreferencename').val();
			var confirmatonMessage='Are you sure that you want to reset the password for '+referenceName+' ?';
			$('input[name="SysUserId"]').val(reference);
			$('#resetconfirmation').html(confirmatonMessage);
			$('#resetpassworddialog').modal('show');
		});
		$(".resetarbpassword").on('click',function(e){
			e.preventDefault();
			var id = $(this).data('id');
			var userName = $(this).data('name');
			$("#resetpasswordarb").find("input[name='Id']").val(id);
			$("#resetpasswordarb").find('#user-name').text(userName);
			$("#resetpasswordarb").modal('show');
		});
		$('.checkall').on('click',function(){
			var curRow=$(this).closest('tr');
			if($(this).is(':checked')){
				curRow.find('.menuid').removeAttr('disabled');
				curRow.find('input[type="checkbox"]').each(function(){
					if(!$(this).hasClass('checkall')){
						$(this).parents('span').addClass('checked');
						$(this).attr('checked','checked');
					}
				});
			}else{
				curRow.find('.menuid').attr('disabled','disabled');
				curRow.find('input[type="checkbox"]').each(function(){
					if(!$(this).hasClass('checkall')){
						$(this).parents('span').removeClass('checked');
						$(this).removeAttr('checked','checked');
					}
				});
			}
		});
		$('.checkinputrole').on('click',function(){
			var curRow=$(this).closest('tr');
			var element=curRow.find('.menuid');
			var checkedCounter=0;
			if($(this).is(':checked')){
				if(element.is(':disabled')){
					element.removeAttr('disabled');
				}
			}else{
				curRow.find('.checkinputrole').not($(this)).each(function(){
					if($(this).is(':checked')){
						checkedCounter+=1;
					}
				});
				if(checkedCounter==0){
					element.attr('disabled','disabled');
				}
			}
		});
		$("#arbitrator-list").on("change",function(){
			if($(this).val()){
				var email = $("option:selected",this).data('email');
				if(email){
					$("#username").val(email);
				}else{
					$("#username").val('');
				}
			}else{
				$("#username").val('');
			}
		});
		$('.mailandsmsto').on('change',function(){
			var selectedOption=$('option:selected',this).val();
			if(selectedOption == 9){
				$('#single-user-email').removeClass('hide');
				$('#single-user-email').find('input').addClass('required');
				$('#etool-user').removeClass('hide');
				$('.etooluser-id').removeAttr('disabled');
				$('.cinetuser-id').attr('disabled','disabled');
				$('.contractor-id').attr('disabled','disabled');
				$('.consultant-id').attr('disabled','disabled');
				$('.engineer-id').attr('disabled','disabled');
			}else{
				$('#single-user-email').addClass('hide');
				$('#single-user-email').find('input').removeClass('required');
			}
            if(selectedOption == 2){
				$('#single-user-email').addClass('hide');
				$('#single-user-email').find('input').removeClass('required');
                $('#etool-user').removeClass('hide');
                $('.etooluser-id').removeAttr('disabled');
                $('.cinetuser-id').attr('disabled','disabled');
                $('.contractor-id').attr('disabled','disabled');
                $('.consultant-id').attr('disabled','disabled');
                $('.engineer-id').attr('disabled','disabled');
            }else{
                $('#etool-user').addClass('hide');
                $('.etooluser-id').attr('disabled','disabled');
                $('.etooluser-autocomplete').val('');
                $('.etooluser-id').val('');
            }
            if(selectedOption == 3){
                $('#cinet-user').removeClass('hide');
                $('.cinetuser-id').removeAttr('disabled');
                $('.etooluser-id').attr('disabled','disabled');
                $('.contractor-id').attr('disabled','disabled');
                $('.engineer-id').attr('disabled','disabled');
				$('#single-user-email').addClass('hide');
				$('#single-user-email').find('input').removeClass('required');
            }else{
                $('#cinet-user').addClass('hide');
                $('.cinetuser-id').attr('disabled','disabled');
                $('.cinetuser-autocomplete').val('');
                $('.cinetuser-id').val('');
            }
            if(selectedOption==4){
				$('#single-user-email').addClass('hide');
				$('#single-user-email').find('input').removeClass('required');
                $('.contractoroptions').removeClass('hide');
            }else{
                $('.contractoroptions').addClass('hide');
            }
			if(selectedOption==5){
				$('#single-user-email').addClass('hide');
				$('#single-user-email').find('input').removeClass('required');
				$('.consultantoptions').removeClass('hide');
			}else{
				$('.consultantoptions').addClass('hide');
			}
			if(selectedOption==8){
				$('#single-user-email').addClass('hide');
				$('#single-user-email').find('input').removeClass('required');
				$('.spoptions').removeClass('hide');
			}else{
				$('.spoptions').addClass('hide');
			}
			if(selectedOption == 4 || selectedOption == 5 || selectedOption == 6 || selectedOption == 7 || selectedOption == 8){
				$('.commonoptions').removeClass('hide');
			}else{
				$('.commonoptions').addClass('hide');
			}
		});
		$('.relieveengineer').on('click',function(){
			var engineerReference=$(this).closest('tr').find('.engineerid').val();
			var cidNo=$(this).closest('tr').find('.cidno').val();
			var nameOfEngineer=$(this).closest('tr').find('.nameofengineer').val();
			$('#manageengineerprofiledialog').find('.relieveengineerid').val(engineerReference);
			$('#manageengineerprofiledialog').find('.cidnoofengineerdisplay').html(cidNo);
			$('#manageengineerprofiledialog').find('.nameofengineerdisplay').html(nameOfEngineer);

			$('#manageengineerprofiledialog').modal('show');
		});
		$('#relievegovermentengineersubmit').on('click',function(){
            submitted = true;
            var flag = false;
            var form = $('#relievegovermentengineer');
            var valid = common.Validate(form);
            if(valid){
                $('#relievegovermentengineer').submit();
            }
		});
		$(document).on('click','.toggle-hidden',function(){
			var curElement = $(this);
			var currentRow = $(this).parents('tr');
			if(curElement.is(':checked')){
				currentRow.find('.page-view').val('1');
			}else{
				currentRow.find('.page-view').val('0');
			}
		});
		//$(document).on('click','.clickable-treeitem, .clickable-treeitemchild',function(){
		//	var currentElement = $(this);
		//	alert(currentElement.attr('class'));
		//	var id = currentElement.data('id');
		//	var currentParent = currentElement.parents('tr');
		//	currentParent.find('.idtoset').val(id);
		//});

	}
	return{
		Initialize:initialize
	}
}();
$(document).ready(function(){
	system.Initialize();
});