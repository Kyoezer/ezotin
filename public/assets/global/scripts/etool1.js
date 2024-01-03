var etool = function(){
    function checkPoints(){
        var hrTable = $('#etoolcriteriahumanresource');
        var equipmentTable = $('#etlequipments');
        var hrTierArray = [];
        var equipmentTierArray = [];
        var designationArray = [];
        var flag = true;
        /* START HERE */
        hrTable.find('tbody tr:not(:last-child)').each(function(){
            var currentRow = $(this);
            var hrTier = currentRow.find('option:selected','select[name$="[EtlTierId]"]').data('maxpoints');
            if(!isNaN(hrTier)){
                if(hrTierArray.indexOf(parseInt(hrTier))==-1){
                    hrTierArray.push(hrTier);
                }
            }
            //switch(parseInt(hrTier)){
            //    case 50:
            //        if(hrTierArray.indexOf(50)==-1){
            //            hrTierArray.push(50);
            //        }
            //        break;
            //    case 30:
            //        if(hrTierArray.indexOf(30)==-1){
            //            hrTierArray.push(30);
            //        }
            //        break;
            //    case 20:
            //        if(hrTierArray.indexOf(20)==-1){
            //            hrTierArray.push(20);
            //        }
            //        break;
            //    default: break;
            //}
        });
        for(var x in hrTierArray){
            designationArray[hrTierArray[x]] = new Array();
            hrTable.find('tbody tr:not(:last-child)').each(function(){
                var currentRow = $(this);
                if(currentRow.find('option:selected','select[name$="[EtlTierId]"]').data('maxpoints') == hrTierArray[x]){
                    var designation = currentRow.find('select[name$="[CmnDesignationId]"]').val();
                    if(designationArray[hrTierArray[x]].indexOf(designation) == -1){
                        designationArray[hrTierArray[x]].push(designation);
                    }
                }
            });
        }
        for(var z in designationArray){
            var sum = 0;
            for(var y in designationArray[z]){
                var max = 0;
                hrTable.find('tbody tr:not(:last-child)').each(function(){
                    var currentRow = $(this);
                    if((currentRow.find('option:selected','select[name$="[EtlTierId]"]').data('maxpoints') == z)&&(currentRow.find('select[name$="[CmnDesignationId]"]').val() == designationArray[z][y])){
                        //sum += parseFloat(currentRow.find('input[name$="[Points]"]').val());
                        max = max>parseFloat(currentRow.find('input[name$="[Points]"]').val())?max:parseFloat(currentRow.find('input[name$="[Points]"]').val());
                    }
                });
                sum += max;
            }
            if(sum > z){
                flag = false;
            }
        }
        /* END HERE */
        equipmentTable.find('tbody tr').each(function(){
            var currentRow = $(this);
            var equipmentTier = currentRow.find('option:selected','select[name$="[EtlTierId]"]').data('maxpoints');
            if(!isNaN(equipmentTier)){
                if(equipmentTierArray.indexOf(parseInt(equipmentTier))==-1){
                    equipmentTierArray.push(parseInt(equipmentTier));
                }
            }
            //switch(parseInt(equipmentTier)){
            //    case 50:
            //        if(equipmentTierArray.indexOf(50)==-1){
            //            equipmentTierArray.push(50);
            //        }
            //        break;
            //    case 30:
            //        if(equipmentTierArray.indexOf(30)==-1){
            //            equipmentTierArray.push(30);
            //        }
            //        break;
            //    case 20:
            //        if(equipmentTierArray.indexOf(20)==-1){
            //            equipmentTierArray.push(20);
            //        }
            //        break;
            //    default: break;
            //}
        });
        for(var x in equipmentTierArray){
            var sum = 0;
            var points;
            equipmentTable.find('tbody tr').each(function(){
                var currentRow = $(this);
                if(currentRow.find('option:selected','select[name$="[EtlTierId]"]').data('maxpoints') == equipmentTierArray[x]){
                    sum += parseFloat(currentRow.find('input[name$="[Points]"]').val());
                }
            });
            if(sum != equipmentTierArray[x]){
                flag = false;
            }
        }
        if(!flag) {
            $('#modalmessageheader').html("<strong>Error !</strong>");
            $('#modaldimessagetext').html("<strong>Please check your point allocation!<br/>For Human Resource: <br/>Tier I should have a maximum of 50 points<br/>Tier II a maximum of 30 points<br/>Tier III a maximum of 20 points<br/><br/>For Equipment:<br/> Tier 1 should have total of 50 points,<br/> Tier 2 a total of 30 points,<br/>Tier 3 a total of 20 points.<br/><br/>It is not necessary to have all 3 Tiers</strong>");
            $('#modalmessagebox').modal('show');
            return false;
        }else{
            return true;
        }
    }
    function checkHundredPercent(){
        if($('.stake').length) {
            var total = 0;
            $('.stake').each(function () {
                total += $(this).val() ? parseFloat($(this).val()) : 0.00;
            });
            if (total != 100) {
                $('#modalmessageheader').html("<strong>Error !</strong>");
                $('#modaldimessagetext').html("<strong>Stakes for all contractors must total to 100!</strong>");
                $('#modalmessagebox').modal('show');
                return false;
            } else {
                return true;
            }
        }else{
            return true;
        }
    }
    function getContractor(cdbno,curRow){
        var url = $('input[name="URL"]').val();
        if(cdbno){
            $.ajax({
                url: url+'/etl/fetchcontractoroncdbno',
                type: 'post',
                dataType: 'json',
                data: {cdbno: cdbno},
                success: function(data){
                    if(data.length > 0){
                        curRow.find('.contractor-id').val(data[0].Id);
                        curRow.find('.contractor-name').val(data[0].NameOfFirm);
                    }else{
                        curRow.find('.contractor-id').val('');
                        curRow.find('.contractor-name').val('');
                    }
                }
            });
        }
    }
    /*Added on 23rd June*/
    function addOptionOfDeletedRow(tierId, option,table,dataLabel){
        var curTable = $('#'+table);
        curTable.find('.EtlEqEquipmentId').each(function(){
            var curRow = $(this).parents('tr');
            var currentTierId = curRow.find('.EtlEqTierId option:selected').val();
            if(currentTierId == tierId){
                $(this).find('option[data-'+dataLabel+'="'+option+'"]:not(:selected)').removeClass('hide').removeAttr('disabled');
            }
        });
    }
    /*End*/
    /*Added on 24th June*/
    function checkUniqueNo(){

    }
    function calculateTotal(table){
        var total = 0;
        table.find('.etool-total').each(function(){
            var curElement = $(this);
            total += !isNaN(parseFloat(curElement.find(':selected').data('points')))?parseFloat(curElement.find(':selected').data('points')):0.00;
        });

        var maxHrPoints = parseFloat($('input[name="MaxHrPoints"]').val());
        var finalTotal = total/maxHrPoints * 25;
        if(finalTotal > 25){
            finalTotal = 25;
        }
        table.find('.total').val(finalTotal.toFixed(3));
    }
    function calculateTotal2(table){
        var total = 0;
        table.find('.etool-total').each(function(){
            var curRow = $(this).parents('tr');
            total += !isNaN(parseFloat(curRow.find('.calculatedpoints').val())/0.25)?parseFloat(curRow.find('.calculatedpoints').val())/0.25:0.00;
        });
        var maxEqPoints = parseFloat($('input[name="MaxEqPoints"]').val());
        var finalTotal = total/maxEqPoints * 25;
        if(finalTotal > 25){
            finalTotal = 25;
        }
        table.find('.total').val(finalTotal.toFixed(3));
    }
    /*End*/
    function initialize(){
        var deletedRow;
        if($('#editId').length){
            if(!$('#editId').val()){
                $('#addcontractormodal').modal("show");
            }
        }
        $('.delete-contractor').on('click',function(){
            deletedRow = $(this).parents('tr');
            var id = deletedRow.find('td:first-child').data('id');
            $('#deletecontractor').find('input[name="Id"]').val(id);
            $('#deletecontractor').modal("show");
        });
        $(document).on('click','#saveAddContractor',function(){
            if($('input[name="FinancialBidQuoted"]').val()){
                return true;
            } else {
                $('#addcontractormodal').modal("show");
                return false;
            }
        });
        $(".work-id-rr").on('change',function(){
            var curForm = $(this).closest('form');
            var value = $(this).val();
            var type = $(this).data('fetch');
            var baseUrl = $("input[name='URL']").val();
            var module = $("#module").val();
            var html;
            if(value){
                if(parseInt(type) == 1){
                    $.ajax({
                        url: baseUrl+"/etl/rrfetchhrdetails",
                        dataType: "JSON",
                        type: "POST",
                        data: {id:value,module:module},
                        success:function(data){
                            html = "<option value=''>SELECT</option>";
                            for(var x in data){
                                html+="<option value='"+data[x].CIDNo+"'>"+data[x].CIDNo+"</option>";
                            }
                            curForm.find('.cid-rr').empty().html(html);
                        }
                    });
                }else{
                    $.ajax({
                        url: baseUrl+"/etl/rrfetcheqdetails",
                        dataType: "JSON",
                        type: "POST",
                        data: {id:value,module:module},
                        success:function(data){
                            html = "<option value=''>SELECT</option>";
                            for(var x in data){
                                html+="<option value='"+data[x].RegistrationNo+"'>"+data[x].RegistrationNo+"</option>";
                            }
                            curForm.find('.regno-rr').empty().html(html);
                        }
                    });
                }

            }else{
                curForm.find('.cid-rr').empty().html("<option value=''>SELECT</option>");
                curForm.find('.regno-rr').empty().html("<option value=''>SELECT</option>");
            }
        });
        $('.deleterowdb').on('click',function(){
            deletedRow = $(this).parents('tr');
            var id = deletedRow.find('input[name="Id"]').val();
            $('#deleteModal').find('input[name="Id"]').val(id);
            $('#deleteModal').modal("show");
        });
        $('#callToDelete').on('click',function(){
            var modal = $(this).parents('.modal');
            var id = modal.find('input[name="Id"]').val();
            var tableName = modal.find('input[name="TableName"]').val();
            var remarks = modal.find('textarea').val();
            if(remarks!=""){
                var url = $('input[name="URL"]').val();
                var routePrefix = $('input[name="RoutePrefix"]').val();
                $.ajax({
                    url: url+'/etl/deletefromdb',
                    data: {id: id, tableName: tableName, remarks: remarks},
                    type: 'post',
                    dataType: 'json',
                    success: function(){
                        deletedRow.remove();
                        $('#deleteModal').modal('hide');
                    }
                });
            }else{
                alert("Please enter remarks");
            }

        });
        $('.deletefile').on('click',function(){
            var curElement = $(this);
            var id = $(this).parents('tr').find('input[name="DocumentId"]').val();
            var url = $('input[name="URL"]').val();
            $.ajax({
                url: url+'/etl/deletefile',
                data: {id: id},
                type: 'post',
                dataType: 'json',
                success: function(){
                    curElement.closest('tr').remove();
                },
                error: function(error){
                    alert(JSON.stringify(error));
                }
            });
        });
        $(document).on('change','.EtlHrTierId',function(){
            var table = $(this).parents('table');
            var hrTierId = $(this).val();
            var curRow = $(this).parents('tr');
            curRow.find('.EtlHrDesignationId').val('');
            curRow.find('.EtlHrQualificationId').val('');
            curRow.find('.EtlHrPoints').val('');
            curRow.find('.EtlHrPointsReduced').val('');
            var curRowIndex = table.find('tbody tr').index(curRow);
            var designationArray = [];
            if(hrTierId != ''){
                var count = 0;
                table.find('.EtlHrDesignationId:first option[value!=""]').each(function(){
                    if($(this).data('tierid') == hrTierId){
                        count++;
                    }
                });
                table.find('tbody tr:not(:eq('+curRowIndex+')):not(:last)').each(function(){
                    var curTableRow = $(this);
                    if(curTableRow.find('.EtlHrDesignationId').val()!=""){
                        var curElement = curTableRow.find('.EtlHrDesignationId');
                        if($('option:selected',curElement).data('tierid') == hrTierId){
                            designationArray.push($('option:selected', curElement).data('criteriahrid'));
                        }
                    }
                });
                curRow.find('.EtlHrDesignationId option[value!=""]').each(function(){
                    if($(this).data('tierid') == hrTierId){
                        if(designationArray.length>0){
                            if(designationArray.indexOf($(this).data('criteriahrid'))==-1){
                                if(designationArray.length < count){ //CHECK
                                    $(this).removeClass('hide');
                                    $(this).removeAttr('disabled');
                                }
                            }
                        }else{
                            $(this).removeClass('hide');
                            $(this).removeAttr('disabled');
                        }
                    }else{
                        $(this).addClass('hide');
                        $(this).attr('disabled','disabled');
                    }
                });
            }else{
                curRow.find('.EtlHrDesignationId option[value!=""]').addClass('hide').attr('disabled','disabled');
                curRow.find('.EtlHrQualificationId option[value!=""]').addClass('hide').attr('disabled','disabled');
                curRow.find('.EtlHrPoints').val('');
            }
            calculateTotal(table);
        });
        $(document).on('change','.EtlHrDesignationId',function(){
            var curElement = $(this);
            var table = curElement.parents('table');
            var hrDesignationId = curElement.val();
            var criteriaHRId = $('option:selected', curElement).data('criteriahrid');
            var curRow = curElement.parents('tr');
            /*Added on 19th June*/
            var curRowIndex = table.find('tbody tr').index(curRow);
            /*End*/
            var hrTierId = curRow.find('option:selected, .EtlHrQualificationId').val();
            $('option:selected',curElement).addClass('selected');
            var curTierId = curRow.find('.EtlHrTierId').val();
            curRow.find('.EtlHrQualificationId').val('');
            curRow.find('.EtlHrPoints').val('');
            curRow.find('.EtlHrPointsReduced').val('');
            if(hrDesignationId != ''){
                curRow.find('.EtlHrQualificationId option[value!=""]').each(function(){
                    if(($(this).data('designationid') == hrDesignationId) && ($(this).data('tierid') == hrTierId)){
                        $(this).removeClass('hide');
                        $(this).removeAttr('disabled');
                    }else{
                        $(this).addClass('hide');
                        $(this).attr('disabled','disabled');
                    }
                });
            }else{
                curRow.find('.EtlHrQualificationId option[value!=""]').addClass('hide').attr('disabled','disabled');
                curRow.find('.EtlHrPoints').val('');
                curRow.find('.EtlHrPointsReduced').val('');
                table.find('tbody tr:eq('+curRowIndex+') .EtlHrDesignationId option[value!=""]').each(function(){
                    var curRowTierId = $(this).parents('tr').find('.EtlHrTierId').val();
                    if(curRowTierId == curTierId){
                        if($(this).data('tierid') == curTierId){
                            $(this).removeAttr('disabled').removeClass('hide');
                        }
                    }
                });
            }

            /*Added on 19th June*/
            table.find('tbody tr:not(:eq('+curRowIndex+')):not(:last)').each(function(){
                var currentTableRow = $(this);
                currentTableRow.find('.EtlHrDesignationId option[value!=""]').each(function(){
                    if(hrDesignationId != ''){
                        if($(this).data('criteriahrid') == criteriaHRId){
                            $(this).addClass('selected').attr('disabled','disabled').addClass('hide');
                        }
                    }else{
                        var curRowTierId = currentTableRow.find('.EtlHrTierId').val();
                        if(curRowTierId == curTierId){
                            if($(this).data('tierid') == curTierId){
                                $(this).removeAttr('disabled').removeClass('hide');
                                currentTableRow.find('.EtlHrDesignationId').val('');
                                currentTableRow.find('.EtlHrQualificationId').val('');
                                currentTableRow.find('.EtlHrQualificationId option[value!=""]').addClass('hide').attr('disabled','disabled');
                                currentTableRow.find('.EtlHrPoints').val('');
                                currentTableRow.find('.EtlHrPointsReduced').val('');
                            }
                        }
                    }
                });
                if(currentTableRow.find('.EtlDesignationId').val() == hrDesignationId){
                    currentTableRow.find('.EtlHrDesignationId').val('');
                }
            });
            /*End*/

            calculateTotal(table);
        });



        $(document).on('change','.EtlSmallHrDesignationId',function(){
            var curElement = $(this);
            var table = curElement.parents('table');
            var hrDesignationId = curElement.val();
            var criteriaHRId = $('option:selected', curElement).data('criteriahrid');
            var curRow = curElement.parents('tr');
            /*Added on 19th June*/
            var curRowIndex = table.find('tbody tr').index(curRow);
            /*End*/
            var hrTierId = curRow.find('option:selected, .EtlHrQualificationId').val();
            $('option:selected',curElement).addClass('selected');
            var curTierId = curRow.find('.EtlHrTierId').val();
            curRow.find('.EtlHrQualificationId').val('');
            curRow.find('.EtlHrPoints').val('');
            curRow.find('.EtlHrPointsReduced').val('');
            if(hrDesignationId != ''){
                curRow.find('.EtlHrQualificationId option[value!=""]').each(function(){
                    if(($(this).data('designationid') == hrDesignationId) ){
                        $(this).removeClass('hide');
                        $(this).removeAttr('disabled');
                    }else{
                        $(this).addClass('hide');
                        $(this).attr('disabled','disabled');
                    }
                });
            }else{
                curRow.find('.EtlHrQualificationId option[value!=""]').addClass('hide').attr('disabled','disabled');
                curRow.find('.EtlHrPoints').val('');
                curRow.find('.EtlHrPointsReduced').val('');
                table.find('tbody tr:eq('+curRowIndex+') .EtlHrDesignationId option[value!=""]').each(function(){
                    var curRowTierId = $(this).parents('tr').find('.EtlHrTierId').val();
                    if(curRowTierId == curTierId){
                        if($(this).data('tierid') == curTierId){
                            $(this).removeAttr('disabled').removeClass('hide');
                        }
                    }
                });
            }

            /*Added on 19th June*/
            table.find('tbody tr:not(:eq('+curRowIndex+')):not(:last)').each(function(){
                var currentTableRow = $(this);
                currentTableRow.find('.EtlHrDesignationId option[value!=""]').each(function(){
                    if(hrDesignationId != ''){
                        if($(this).data('criteriahrid') == criteriaHRId){
                            $(this).addClass('selected').attr('disabled','disabled').addClass('hide');
                        }
                    }else{
                        var curRowTierId = currentTableRow.find('.EtlHrTierId').val();
                        if(curRowTierId == curTierId){
                            if($(this).data('tierid') == curTierId){
                                $(this).removeAttr('disabled').removeClass('hide');
                                currentTableRow.find('.EtlHrDesignationId').val('');
                                currentTableRow.find('.EtlHrQualificationId').val('');
                                currentTableRow.find('.EtlHrQualificationId option[value!=""]').addClass('hide').attr('disabled','disabled');
                                currentTableRow.find('.EtlHrPoints').val('');
                                currentTableRow.find('.EtlHrPointsReduced').val('');
                            }
                        }
                    }
                });
                if(currentTableRow.find('.EtlDesignationId').val() == hrDesignationId){
                    currentTableRow.find('.EtlHrDesignationId').val('');
                }
            });
            /*End*/

            calculateTotal(table);
        });




        $(document).on('change','.EtlHrQualificationId',function(){
            var table = $(this).parents('table');
            var curElement = $(this);
            var curRow = curElement.parents('tr');
            if($(this).val()!=''){
                var points = $('option:selected',curElement).data('points');
                var maxHrPoints = $('input[name="MaxHrPoints"]').val();
                var calculatedPoints = points * .25;
                curRow.find('.EtlHrPoints').val(calculatedPoints);
                curRow.find('.EtlHrPointsReduced').val(calculatedPoints);
            }else{
                curRow.find('.EtlHrPoints').val('');
                curRow.find('.EtlHrPointsReduced').val('');
            }
            calculateTotal(table);
            //common.CalculateTotal(table);
        });
        $(document).on('change','.EtlEqTierId',function(){
            var table = $(this).parents('table');
            var eqTierId = $(this).val();
            var curRow = $(this).parents('tr');
            curRow.find('.EtlEqEquipmentId').val('');
            curRow.find('.EtlEqPoints').val('');
			/*Added on 20th June*/
			var curRowIndex = table.find('tbody tr').index(curRow);
            var equipmentArray = [];
			/*End*/
            if(eqTierId != ''){
				/*Added on 20th June*/
				var count = 0;
                table.find('.EtlEqEquipmentId:first option[value!=""]').each(function(){
                    if($(this).data('tierid') == eqTierId){
                        count++;
                    }
                });
				table.find('tbody tr:not(:eq('+curRowIndex+')):not(:last)').each(function(){
                    var curTableRow = $(this);
                    if(curTableRow.find('.EtlEqEquipmentId').val()!=""){
                        var curElement = curTableRow.find('.EtlEqEquipmentId');
                        if($('option:selected',curElement).data('tierid') == eqTierId){
                            equipmentArray.push($('option:selected', curElement).data('criteriaeqid'));
                        }
                    }
                });
				/*End */
                curRow.find('.EtlEqEquipmentId option[value!=""]').each(function(){
                    if(($(this).data('tierid') == eqTierId)){
						/*Added on 20th June*/
						if(equipmentArray.length>0){
                            if(equipmentArray.indexOf($(this).data('criteriaeqid'))==-1){
                                //if(equipmentArray.length < count){
                                    $(this).removeClass('hide');
                                    $(this).removeAttr('disabled');
                                //}
                            }else{
                                var individualEqCount = 0;
                                $('.EtlEqEquipmentId option[value!=""][data-criteriaeqid="'+$(this).data('criteriaeqid')+'"]').each(function(){
                                    if($(this).is(':selected')){
                                        individualEqCount++;
                                    }
                                });
                                if(individualEqCount < $(this).data('quantity')){
                                    $(this).removeClass('hide');
                                    $(this).removeAttr('disabled');
                                }
                            }
                        }else{
                            $(this).removeClass('hide');
                            $(this).removeAttr('disabled');
                        }
						/*End*/
                        /*(this).removeClass('hide');
                        $(this).removeAttr('disabled');*/
                    }else{
                        $(this).addClass('hide');
                        $(this).attr('disabled','disabled');
                    }
                });
            }else{
                curRow.find('.EtlEqEquipmentId option[value!=""]').each(function(){
                    //if($(this).not(':selected')){
                        $(this).addClass('hide').attr('disabled','disabled');
                    //}
                });
                curRow.find('.EtlEqPoints').val('');
                curRow.find('.calculatedpoints').val('');
                curRow.find('select[name$="[OwnedOrHired]"]').val('');
            }
            calculateTotal2(table);
        });
        $(document).on('change','.EtlEqEquipmentId',function(){
            var table = $(this).parents('table');
            var curElement = $(this);
            var curRow = curElement.parents('tr');
            var curRowIndex = table.find('tbody tr').index(curRow);
            if($(this).val()!=''){
                var points = $('option:selected',curElement).data('points');
                curRow.find('.EtlEqPoints').val(points);

                /* Filtering */
                curElement.find('option:not(:disabled)[value!=""]').each(function(){
                    if(!$(this).hasClass('hide')){
                        var criteriaEQId = $(this).data('criteriaeqid');
                        var tierId = curRow.find('option:selected','.EtlTierId').val();
                        var count = table.find('.EtlEqEquipmentId option[data-criteriaeqid="'+criteriaEQId+'"]:selected').length;
                        table.find('tbody tr:not(:last)').each(function(){
                            var curTableRow = $(this);
                            var curList = curTableRow.find('.EtlEqEquipmentId');
                            var quantity = curList.find('option[data-tierid="'+tierId+'"][data-criteriaeqid="'+criteriaEQId+'"][value!=""]').data('quantity');
                            var curTableRowTierId = curTableRow.find('option:selected','.EtlTierId').val();
                            if(curTableRowTierId == tierId){
                                if(count >= quantity){
                                    if($(this).find('.EtlEqEquipmentId option:selected').data('criteriaeqid') != criteriaEQId){
                                        $(this).find('.EtlEqEquipmentId option[data-tierid="'+tierId+'"][data-criteriaeqid="'+criteriaEQId+'"][value!=""]').attr('disabled','disabled').addClass('hide');
                                    }
                                }else{
                                    $(this).find('.EtlEqEquipmentId option[data-tierid="'+tierId+'"][data-criteriaeqid="'+criteriaEQId+'"][value!=""]').removeAttr('disabled').removeClass('hide');
                                }
                            }
                        });
                    }
                });
                /*End filtering */

                if($('option:selected', curRow.find('select[name$="[OwnedOrHired]"]').val())!=''){
                    var selectedOption = $('option:selected', curRow.find('select[name$="[OwnedOrHired]"]')).val();
                    if(selectedOption != ''){
                        if(selectedOption == 1){
                            var calculatedpoints = points * 1;
                        }else{
                            var calculatedpoints = points * 0.75;
                        }
                        calculatedpoints = calculatedpoints * 0.25;
                        curRow.find('.calculatedpoints').val(calculatedpoints.toFixed(3));
                    }else{
                        curRow.find('.calculatedpoints').val('');
                    }
                }else{
                    curRow.find('.calculatedpoints').val('');
                }
            }else{
                curElement.find('option:not(:disabled)[value!=""]').each(function(){
                    if(!$(this).hasClass('hide')){
                        var criteriaEQId = $(this).data('criteriaeqid');
                        var tierId = curRow.find('option:selected','.EtlTierId').val();
                        var count = table.find('.EtlEqEquipmentId option[data-criteriaeqid="'+criteriaEQId+'"]:selected').length;
                        table.find('tbody tr:not(:last)').each(function(){
                            var curTableRow = $(this);
                            var curList = curTableRow.find('.EtlEqEquipmentId');
                            var quantity = curList.find('option[data-tierid="'+tierId+'"][data-criteriaeqid="'+criteriaEQId+'"][value!=""]').data('quantity');
                            var curTableRowTierId = curTableRow.find('option:selected','.EtlTierId').val();
                            if(curTableRowTierId == tierId){
                                if(count >= quantity){
                                    if($(this).find('.EtlEqEquipmentId option:selected').data('criteriaeqid') != criteriaEQId){
                                        $(this).find('.EtlEqEquipmentId option[data-tierid="'+tierId+'"][data-criteriaeqid="'+criteriaEQId+'"][value!=""]').attr('disabled','disabled').addClass('hide');
                                    }
                                }else{
                                    $(this).find('.EtlEqEquipmentId option[data-tierid="'+tierId+'"][data-criteriaeqid="'+criteriaEQId+'"][value!=""]').removeAttr('disabled').removeClass('hide');
                                }
                            }
                        });
                    }
                });
                curRow.find('.EtlEqPoints').val('');
                curRow.find('.calculatedpoints').val('');
                curRow.find('select[name$="[OwnedOrHired]"]').val('');
            }
            calculateTotal2(table);
        });
        $(document).on('change','select[name$="[OwnedOrHired]"]',function(){
            var table = $(this).parents('table');
            var curElement = $(this);
            var curRow = curElement.parents('tr');
            if($(this).val()!=''){
                var points = $('option:selected',curRow.find('.EtlEqEquipmentId')).data('points');
                //curRow.find('.EtlEqPoints').val(points);
                if($(this).val() == 1){
                    var calculatedpoints = points * 1;
                }else{
                    var calculatedpoints = points * 0.75;
                }
                calculatedpoints = calculatedpoints * 0.25;
                curRow.find('.calculatedpoints').val(calculatedpoints.toFixed(3));
            }else{
                curRow.find('.calculatedpoints').val('');
            }
            calculateTotal2(table);
        });
        $(document).on('click','#jointventure-toggle input[type="radio"]',function(){
            if($(this).is(':checked')){
                var value = $(this).val();
                if(value == 0){
                    $('table#ContractorAdd').find('.to-hide').addClass('hide');
                    $('table#ContractorAdd').find('.notremovefornew').addClass('hide');
                    $('table#ContractorAdd tr:not(:nth-child(0)):not(:nth-child(1)):not(:last-child)').remove();
                    $('.to-clone:not(:first-child)').remove();
                    $('table#ContractorAdd').find('.stake').val('100').attr('value','100');
                }else{
                    $('table#ContractorAdd').find('.to-hide').removeClass('hide');
                    $('table#ContractorAdd').find('.notremovefornew').removeClass('hide');
                    $('table#ContractorAdd').find('.stake').val('').attr('value','');
                }
            };
        });
        $(document).on('blur','.cdbno',function(){
            var cdbno = $(this).val();
            var curRow = $(this).parents('tr');
            getContractor(cdbno,curRow);
        });
        $(document).on('blur','#cdbno',function(){
            var cdbno = $(this).val();
            var url = $('input[name="URL"]').val();
            if(cdbno && !isNaN(cdbno)){
                $.ajax({
                    url: url+'/etl/fetchcontractoroncdbno',
                    type: 'post',
                    dataType: 'json',
                    data: {cdbno: cdbno},
                    success: function(data){
                        if(data.length > 0){
                            $('.contractor-id').val(data[0].Id);
                            $('.contractor-name').val(data[0].NameOfFirm);
                        }else{
                            $('.contractor-id').val('');
                            $('.contractor-name').val('');
                        }
                    }
                });
            }
        });
        $(".toggle-cinet-table").on('change',function(){
            var curElement = $(this);
            var form = curElement.closest('form');
            var id = curElement.attr('id');
            var table;
            if(id == 'add-hr-cinet'){
                table = $("#cinet-hr-table");
            }else{
                table = $("#cinet-eq-table");
            }
            if(curElement.is(":checked")){
                table.find("input[type!='hidden'],select").removeAttr('disabled');
                table.find("input[type!='hidden']:not(.notrequired),select").addClass('required');
            }else{
                table.find("tbody tr:not(:first):not(:last)").remove();
                table.find("input[type!='hidden'],select").attr('disabled','disabled').val('');
                table.find("input[type!='hidden']:not(.notrequired),select").removeClass('required');

                if($("button[type='submit']").attr('disabled')=='disabled'){
                    //SUBMITTED AND FAILED VALIDATION
                    table.find("input[type!='hidden'],select").each(function(){
                        if($(this).hasClass('error')){
                            $(this).removeClass('error');
                            $(this).closest('td').find('span.error-span').remove();
                        }
                    });
                    common.Validate(form);
                }
            }
        });
       // $(document).on('blur','table#ContractorAdd .cdbno',function(){
            // var curRow = $(this).parents('tr');
            // var val = $(this).val();
            // var dataVal;
            // var id;
            // var flag = false;
            // curRow.find('select[name$="[CrpContractorFinalId]"] option').each(function(){
            //     if(!flag) {
            //         dataVal = $(this).data('cdbno');
            //         id = $(this).val();
            //         if (val == dataVal) {
            //             flag = true;
            //             curRow.find('select[name$="[CrpContractorFinalId]"]').val(id);
            //         } else {
            //             curRow.find('select[name$="[CrpContractorFinalId]"]').val('');
            //             //curRow.find('input[name$="[Stake]"]').val('');
            //         }
            //     }
            // });

            /*var cdbno = $(this).val();
            var curRow = $(this).parents('tr');
            var url = $('input[name="URL"]').val();
            if(cdbno && !isNaN(cdbno)){
                $.ajax({
                    url: url+'/etl/fetchcontractoroncdbno',
                    type: 'post',
                    dataType: 'json',
                    data: {cdbno: cdbno},
                    success: function(data){
                        if(data.length > 0){
                            curRow.find('.contractor-id').val(data[0].Id);
                            curRow.find('.contractor-name').val(data[0].NameOfFirm);
                        }
                    }
                });
            }
        });*/
        $(document).on('click', '.nav-tabs a[href="#humanresource"]',function(){
            $('.tab-pane#equipment').find('input:not(.not-required), select').removeClass('required');
            $('.tab-pane#humanresource').find('input:not(.not-required), select').addClass('required');
        });
        $(document).on('click', '.nav-tabs a[href="#equipment"]',function(){
            $('.tab-pane#equipment').find('input:not(.not-required), select').addClass('required');
            $('.tab-pane#humanresource').find('input:not(.not-required), select').removeClass('required');
        });
        $(document).on('click', '.nav-tabs a[href="#contractorhumanresource"]',function(){
            $('.tab-pane#contractorequipment').find('input:not(.not-required), select').removeClass('required');
            $('.tab-pane#contractorhumanresource').find('input:not(.not-required), select').addClass('required');
        });
        $(document).on('click', '.nav-tabs a[href="#contractorequipment"]',function(){
            $('.tab-pane#contractorequipment').find('input:not(.not-required), select').addClass('required');
            $('.tab-pane#contractorhumanresource').find('input:not(.not-required), select').removeClass('required');
        });
        if($('input[name="CurrentTab"]').length > 0){
            var currentTab = $('input[name="CurrentTab"]').val();
            if(currentTab != "XX"){
                $('.tab-pane').each(function(){
                    var curTab = $(this);
                    var id = curTab.attr('id');
                    if('#'+id == currentTab){
                        curTab.find('input:not(.not-required), select').addClass('required');
                    }else{
                        curTab.find('input:not(.not-required), select').removeClass('required');
                    }
                });
            }
        }
        $(document).on('click','.etoolreport-container a',function(e){
            e.preventDefault();
            var curElement = $(this);
            var href = $(this).attr('href');
            var param = $(this).parents('.form-group').find('input[type="text"]').val();
            var paramText = $(this).parents('.form-group').find('input[name="parameter"]').val();
            if(param != ''){
                param = encodeURIComponent(param);
                window.open(href+"?"+paramText+"="+param,'_blank');
            }
        });
        $(document).on('change','.classification',function(){
            if($(this).val()){
                var reference = $('option:selected',this).data('reference');
                if(reference == 4){
                    $('.category option').each(function(){
                        if($(this).data('reference') == 6002){
                            $(this).removeAttr('disabled').removeClass('hide');
                        }else{
                            $(this).attr('disabled','disabled').addClass('hide');
                        }
                    });
                }else{
                    $('.category option').each(function(){
                        if($(this).data('reference') == 6002){
                            $(this).attr('disabled','disabled').addClass('hide');
                        }else{
                            $(this).removeAttr('disabled').removeClass('hide');
                        }
                    });
                }
            }
            $('.category').val('').find('.select2-chosen').text($('.category option:first').text());
        });
        $(document).on('click','.fetchcontractor',function(){
            var curRow = $(this).parents('tr');
            var id = curRow.find('td:first-child').data('id');
            var url = $('input[name="URL"]').val();
            $.ajax({
                url: url+'/etl/fetchcontractor',
                data: {id: id},
                type: 'post',
                dataType: 'json',
                success: function(data){
                    $('span.contractor-name').text(data[0].Contractor);
                    $('#awardwork').find('input[type="hidden"][name="Id"]').val(data[0].Id);
                    $('#awardwork').find('input[name="AwardedAmount"]').val(data[0].FinancialBidQuoted);
                    $('#awardwork').modal('show');
                }
            });
        });
        /*Added on 27th June*/
        $(document).on('click','#add-detail-tab a',function(){
            $('input[name="CurrentTab"]').val($(this).attr('href'));
        });
        /*End*/
        //Added on 4th August
        //$(document).on('click','.deleterowfromdb',function(){
        //    var curRow = $(this).parents('tr');
        //    var id = curRow.find('.row-id').val();
        //    var table = curRow.find('.row-id').data('table');
        //    var curTable = curRow.parents('table');
        //    var baseUrl = $('input[name="URL"]').val();
        //    if(id){
        //        $.post(baseUrl+'/etl/deleteevaldetail',{id: id, table: table},function(data){
        //            calculateTotal(curTable);
        //            calculateTotal2(curTable);
        //        });
        //    }
        //});
        //End
    };
    return {
        CalculateTotal :calculateTotal,
        CalculateTotal2 :calculateTotal2,
        CheckUniqueNo :checkUniqueNo,
        AddOptionOfDeletedRow:addOptionOfDeletedRow,
        GetContractor:getContractor,
        CheckHundredPercent: checkHundredPercent,
        CheckPoints: checkPoints,
        Initialize: initialize
    }
}();
$(document).ready(function(){
    etool.Initialize();
});