var common=function(){
    var submitted;
    var audioElement;
    var curTableDeletion;
    var hrregvalid = true;
    var eqregvalid = true;
    function randomKey() {
        var key = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for (var i = 0; i < 5; i++) {
            key += possible.charAt(Math.floor(Math.random() * possible.length));    
        }
        return key;
    };
    function refreshDashboard(){
        var url = $('input[name="URL"]').val();
        //$('#dashboard_count').load(url+'/loadcount');

        $.ajax({
            url: url+"/loadcount",
            dataType: "JSON",
            type: "POST",
            success: function(data){
                var notificationCount = data.totalCount;
                var hasNewNotifications = data.hasNewNotifications;

                if(parseInt(hasNewNotifications)==1){
                    //PLAY SOUND
                    audioElement.play();
                }
                var notifications = data.notifications;

                $("#notification-badge").text(notificationCount);
                $(".notification-count").text(notificationCount);
                var html = "";
                var type,linkUrl,linkClass,linkPrefix;
                linkClass = "pickaction";
                for(var x in notifications){
                    type = notifications[x].TypeCode;
                    switch(parseInt(type)){
                        case 101: //APPROVE USER ACCOUNT
                            linkClass = "";
                            linkUrl = "sys/approveexistingusersregistration";
                            break;
                        case 201: //CONTRACTOR REGISTRATION VERIFY
                            linkUrl = "contractor/verifyregistration";
                            linkPrefix = "contractor";
                            break;
                        case 202: //CONSULTANT REGISTRATION VERIFY
                            linkUrl = "consultant/verifyregistration";
                            linkPrefix = "consultant";
                            break;
                        case 203: //ARCHITECT REGISTRATION VERIFY
                            linkUrl = "architect/verifyregistration";
                            linkPrefix = "architect";
                            break;
                        case 204: //ENGINEER REGISTRATION VERIFY
                            linkUrl = "engineer/verifyregistration";
                            linkPrefix = "engineer";
                            break;
                        case 205: //SP REGISTRATION VERIFY
                            linkUrl = "specializedtrade/verifyregistration";
                            linkPrefix = "specializedtrade";
                            break;
                        case 206: //CONTRACTOR SERVICE VERIFY
                            linkUrl = "contractor/verifyserviceapplicationlist";
                            linkPrefix = "contractor";
                            break;
                        case 207: //CONSULTANT SERVICE VERIFY
                            linkUrl = "consultant/verifyserviceapplicationlist";
                            linkPrefix = "consultant";
                            break;
                        case 208: //ARCHITECT SERVICE VERIFY
                            linkUrl = "architect/verifyserviceapplicationlist";
                            linkPrefix = "architect";
                            break;
                        case 209: //ENGINEER SERVICE VERIFY
                            linkUrl = "engineer/verifyserviceapplicationlist";
                            linkPrefix = "engineer";
                            break;
                        case 210: //SP SERVICE VERIFY
                            linkUrl = "specializedtrade/verifyserviceapplicationlist";
                            linkPrefix = "specializedtrade";
                            break;
                        case 301://CONTRACTOR REGISTRATION APPROVE
                            linkUrl = "contractor/approveregistration";
                            linkPrefix = "contractor";
                            break;
                        case 302: //CONSULTANT REGISTRATION APPROVE
                            linkUrl = "consultant/approveregistration";
                            linkPrefix = "consultant";
                            break;
                        case 303: //ARCHITECT REGISTRATION APPROVE
                            linkUrl = "architect/approveregistration";
                            linkPrefix = "architect";
                            break;
                        case 304: //ENGINEER REGISTRATION APPROVE
                            linkUrl = "engineer/approveregistration";
                            linkPrefix = "engineer";
                            break;
                        case 305: //SP REG APPROVE
                            linkUrl = "specializedtrade/approveregistration";
                            linkPrefix = "specializedtrade";
                            break;
                        case 306: //CONTRACTOR SERVICE APPROVE
                            linkUrl = "contractor/approveserviceapplicationlist";
                            linkPrefix = "contractor";
                            break;
                        case 307: //CONSULTANT SERVICE APPROVE
                            linkUrl = "consultant/approveserviceapplicationlist";
                            linkPrefix = "consultant";
                            break;
                        case 308: //ARCHITECT SERVICE APPROVE
                            linkUrl = "architect/approveserviceapplicationlist";
                            linkPrefix = "architect";
                            break;
                        case 309: //ENGINEER SERVICE APPROVE
                            linkUrl = "engineer/approveserviceapplicationlist";
                            linkPrefix = "engineer";
                            break;
                        case 310: //SP SERVICE APPROVE
                            linkUrl = "specializedtrade/approveserviceapplicationlist";
                            linkPrefix = "specializedtrade";
                            break;
                        case 401://CONTRACTOR REG PAYMENT
                            linkUrl = "contractor/approvefeepayment";
                            linkPrefix = "contractor";
                            break;
                        case 402:
                            linkUrl = "consultant/approvefeepayment";
                            linkPrefix = "consultant";
                            break;
                        case 403:
                            linkUrl = "architect/approvefeepayment";
                            linkPrefix = "architect";
                            break;
                        case 404:
                            linkUrl = "engineer/approvefeepayment";
                            linkPrefix = "engineer";
                            break;
                        case 406:
                            linkUrl = "contractor/approveserviceapplicationfeepaymentlist";
                            linkPrefix = "contractor";
                            break;
                        case 407:
                            linkUrl = "consultant/approveserviceapplicationfeepaymentlist";
                            linkPrefix = "consultant";
                            break;
                        case 408:
                            linkUrl = "architect/approveserviceapplicationfeepaymentlist";
                            linkPrefix = "architect";
                            break;
                        case 409:
                            linkUrl = "engineer/approveserviceapplicationfeepaymentlist";
                            linkPrefix = "engineer";
                            break;
                        case 410:
                            linkUrl = "specializedtrade/approveserviceapplicationfeepaymentlist";
                            linkPrefix = "specializedtrade";
                            break;
                        default:
                            break;
                    }
                    if(parseInt(type) == 101){
                        html += "<li><a href='"+url+"/"+linkUrl+"' class='"+linkClass+"'>"+notifications[x].Message+"<br/><button type='button' class='btn btn-xs purple'><i class='fa fa-edit'></i> View</button></a></li>";
                    }else{
                        html += "<li><a href='"+url+"/"+linkPrefix+"/lockapplication/"+notifications[x].ApplicationId+"?redirectUrl="+linkUrl+"&notification=true' class='"+linkClass+"'>"+notifications[x].Message+"<br/><button type='button' class='btn btn-xs green'><i class='fa fa-edit'></i> Pick</button></a></li>";
                    }
                    
                }
                $("#notification-list").html(html);
            }
        });
        setTimeout(function() {
            refreshDashboard();
        },30000);
    }
    function calculateTotal(table){
        var total = 0;
        table.find('.to-total').each(function(){
            total += $(this).val()?parseFloat($(this).val()):0.00;
        });
        table.find('.total').val(total.toFixed(3));
    }
    function addNewRow(tableId) {
        var lastRow = $('#'+ tableId +' tr:not(.notremovefornew):not(.dont-clone):last');
        var row = lastRow.clone();
        row.find('span.help-block:not(.info-block)').remove();
        row.find('.remove-on-add').empty();
        row.find('input,select').removeClass('error');
        row.find('.arb-image').remove();
        /*Added by Sangay Wangdi */
        if(typeof(etool)!='undefined'){
            row.find('.EtlHrDesignationId option[value!=""]').addClass('hide').attr('disabled','disabled');
            row.find('.EtlHrQualificationId option[value!=""]').addClass('hide').attr('disabled','disabled');
            row.find('.EtlHrPoints').val('');
            row.find('.EtlEqEquipmentId option[value!=""]').addClass('hide').attr('disabled','disabled');
            row.find('.EtlEqPoints').val('');
            if(row.find('.increment').length){
                var lastValue = lastRow.find('.increment').val();
                var newValue = parseInt(lastValue)+1;
                row.find('.increment').val(newValue).attr('value',newValue);
                var clonedTable = $('.to-clone:last-child').clone();
                clonedTable.find('input[type="text"]').val('');
                clonedTable.find('table tbody tr').each(function(){
                    var curRow = $(this);
                    var key = randomKey();
                    curRow.find('input').each(function(){
                        var aa = $(this).attr('name');
                        var startIndexOfKey = aa.indexOf('[');
                        var lastKey = aa.substring(startIndexOfKey+1);
                        lastKey=lastKey.substring(0,lastKey.indexOf(']'));
                        $(this).attr('name', aa.replace(lastKey,key));
                    });
                });
                clonedTable.find('.contractor-no').text(newValue);
                clonedTable.find('.sequence').val(newValue);
                clonedTable.find('table').attr('id',clonedTable.find('table').attr('id').substr(0,clonedTable.find('table').attr('id').length-1) + newValue);
                $('.to-clone:last-child').after(clonedTable);
            }
        }
        /*End of Code by Sangay Wangdi */
        row.insertAfter(lastRow);
        var key = randomKey();
        row.find('td').each(function () {
            var $this = $(this);
            $this.find('.resetKeyForNew').each(function (index, item) {
                var aa = $(item).attr('name');
                var startIndexOfKey = aa.indexOf('[');
                var lastKey = aa.substring(startIndexOfKey+1);
                lastKey=lastKey.substring(0,lastKey.indexOf(']'));
                $(item).attr('name', aa.replace(lastKey,key));
            });
            var vClear = $this.find('input:not(.notclearfornew)');
            if (vClear) vClear.val('');vClear.attr("placeholder","");
            var vSelect = $this.find('select:not(.notclearfornew)');
            if (vSelect) vSelect.val('');
            var vCheck = $this.find('input[type="checkbox"]');
            if (vCheck) vCheck.removeAttr('checked');vCheck.parents('span').removeClass('checked');
        });
        $('#' + tableId + ' tr:last td:first' + ' .rowIndex').attr("value", key);
        return key;
    };
    function validate(form) {
        submitted=true;
        var requiredvalid = true;
        var numbervalid = true;
        var passwordvalid = true;
        var captchavalid = true;
        var confirmpasswordvalid = true;
        var emailvalid = true;
        var fixedLengthValid=true;
        var uniqueValid = true;
        var regNoValid = true;
        var cidValid = true;
        var message = '';
        form.find('input, textarea').each(function () {
            var curInput = $(this);
            var curInputName = $(this).attr('name');
            if (curInput.hasClass('required')) {
                if (!$(this).val()) {
                    requiredvalid = false;
                    curInput.parents('.form-group').removeClass('has-success');
                    curInput.parents('.form-group').find('span.help-block:not(.info-block)').remove();
                    curInput.parents('td').find('span.help-block:not(.info-block)').remove();
                    if (!curInput.parents('.form-group').find('span.help-block.required-message').length && !curInput.parents('td').find('span.help-block.required-message').length) {
                        curInput.after("<span class='help-block error-span required-message'>This field is required!</span>");
                    }
                    curInput.addClass('error');
                } else {
                    if(curInput.hasClass('check-hr-registration')){
                        if(hrregvalid){
                            curInput.removeClass('error');
                            curInput.parents('.form-group').find('span.help-block.required-message').remove();
                            curInput.parents('td').find('span.help-block.required-message').remove();
                            curInput.parents('.form-group').addClass('has-success');
                        }else{
                            curInput.parents('.form-group').removeClass('has-success');
                            curInput.addClass('error');
                        }
                    }else{
                        if(!curInput.hasClass('checkhrtr') || typeof(etool)!='undefined'){
                            curInput.removeClass('error');
                            curInput.parents('.form-group').find('span.help-block.required-message').remove();
                            curInput.parents('td').find('span.help-block.required-message').remove();
                            curInput.parents('.form-group').addClass('has-success');
                        }else{
                            if(curInput.next('span').hasClass('required-message')){
                                curInput.removeClass('error');
                                curInput.parents('.form-group').find('span.help-block.required-message').remove();
                                curInput.parents('td').find('span.help-block.required-message').remove();
                                curInput.parents('.form-group').addClass('has-success');
                            }
                        }

                    }


                }
            }
            if ($(this).hasClass('captcha')) {
                if (!$(this).val()) {
                    captchavalid = false;
                    curInput.parents('.form-group').removeClass('has-success');
                    curInput.parents('.form-group').find('span.help-block:not(.info-block)').remove();
                    curInput.parents('td').find('span.help-block:not(.info-block)').remove();
                    if (!curInput.parents('.form-group').find('span.help-block.required-message').length && !curInput.parents('td').find('span.help-block.required-message').length) {
                        curInput.after("<span class='help-block error-span captcha-message'>Please answer the security question!</span>");
                    }
                    curInput.addClass('error');
                } else {
                    var number1 = $('#first-no').text();
                    var number2 = $('#second-no').text();
                    var answer = parseInt(number1) + parseInt(number2);
                    if(parseInt($(this).val()) == parseInt(answer)){
                        curInput.removeClass('error');
                        curInput.parents('.form-group').find('span.help-block.captcha-message').remove();
                        curInput.parents('td').find('span.help-block.captcha-message').remove();
                        curInput.parents('.form-group').addClass('has-success');
                    }else{
                        captchavalid = false;
                        curInput.parents('.form-group').removeClass('has-success');
                        curInput.parents('.form-group').find('span.help-block:not(.info-block)').remove();
                        curInput.parents('td').find('span.help-block:not(.info-block)').remove();
                        if (!curInput.parents('.form-group').find('span.help-block.required-message').length && !curInput.parents('td').find('span.help-block.required-message').length) {
                            curInput.after("<span class='help-block error-span captcha-message'>The answer is wrong!</span>");
                        }
                        curInput.addClass('error');
                    }
                }
            }
            if(($(this).hasClass("registration-no"))){
                var curParent = curInput.parents(".eq-container");
                var isRegistered = curParent.find(".equipment option:selected").data('isregistered');
                if(parseInt(isRegistered) == 1){
                    var regNo = curInput.val().toString();
                    if(curInput.val()){
                        var testRegExp = /^(BP|BG)-\d-[A-Z]?\d{4}$/.test(regNo);
                        if(!testRegExp){
                            regNoValid =false;
                            curInput.parents('.form-group').removeClass('has-success');
                            if (!curInput.parents('.form-group').find('span.help-block.regno-message').length && !curInput.parents('td').find('span.help-block.regno-message').length) {
                                curInput.after("<span class='help-block error-span regno-message'>Please enter correct format (BP-1-A3222 / BG-2-3211)!</span>");
                            }
                            curInput.addClass('error');
                        }else{
                            curInput.removeClass('error');
                            curInput.parents('.form-group').find('span.help-block.regno-message').remove();
                            curInput.parents('td').find('span.help-block.regno-message').remove();
                            curInput.parents('.form-group').addClass('has-success');
                        }
                    }else{
                        if(!curInput.hasClass('required')){
                            regNoValid =true;
                            curInput.removeClass('error');
                            curInput.parents('.form-group').find('span.help-block.regno-message').remove();
                            curInput.parents('td').find('span.help-block.regno-message').remove();
                            curInput.parents('.form-group').addClass('has-success');
                        }
                    }
                }else{
                    regNoValid =true;
                    curInput.parents('.form-group').find('span.help-block.regno-message').remove();
                    curInput.parents('td').find('span.help-block.regno-message').remove();

                    if(!curInput.hasClass('required')){
                        curInput.removeClass('error');
                        curInput.parents('.form-group').addClass('has-success');
                    }else{
                        validate(form);
                    }

                }
            }
            if ($(this).hasClass('number')) {
                if (isNaN($(this).val())) {
                    numbervalid = false;
                    curInput.parents('.form-group').removeClass('has-success');
                    if (!curInput.parents('.form-group').find('span.help-block.number-message').length && !curInput.parents('td').find('span.help-block.number-message').length) {
                        curInput.after("<span class='help-block error-span number-message'>Please enter a number!</span>");
                    }
                    curInput.addClass('error');
                } else {
                    /* NEW (SWM)*/
                    if(curInput.hasClass('range')){
                        var min = curInput.data('min');
                        var max = curInput.data('max');
                        var numericValue = curInput.val();
                        if(numericValue <= max && numericValue >= min){
                            curInput.removeClass('error');
                            curInput.parents('.form-group').find('span.help-block.number-message').remove();
                            curInput.parents('td').find('span.help-block.number-message').remove();
                        }else{
                            numbervalid = false;
                            if (!curInput.parents('.form-group').find('span.help-block.number-message').length && !curInput.parents('td').find('span.help-block.number-message').length) {
                                curInput.after("<span class='help-block error-span number-message'>Number should be in the range of "+min+" to "+max+"! </span>");
                            }
                            curInput.addClass('error');
                        }
                    /*END*/
                    }else{
                        if (requiredvalid && curInput.val()) {
                            curInput.removeClass('error');
                        }
                        curInput.parents('.form-group').find('span.help-block.number-message').remove();
                        curInput.parents('td').find('span.help-block.number-message').remove();
                        if (!curInput.hasClass('required')) {
                            numbervalid = true;
                            curInput.removeClass('error');
                            curInput.parents('.form-group').find('span.help-block.number-message').remove();
                            curInput.parents('td').find('span.help-block.number-message').remove();
                            curInput.parents('.form-group').addClass('has-success');
                        }
                    }    
                }
            }
            if($(this).hasClass('fixedlengthvalidate')){
                var value = $(this).val();
                var valueLength = value.length;
                var fixedlength=parseInt($(this).data('fixedlength'));
                if(valueLength==fixedlength) {
                    if (requiredvalid) {
                        curInput.removeClass('error');
                    }
                    curInput.parents('.form-group').find('span.help-block.fixed-length-message').remove();
                    curInput.parents('td').find('span.help-block.fixed-length-message').remove();
                    curInput.parents('.form-group').addClass('has-success');
                } else {
                    fixedLengthValid = false;
                    curInput.parents('.form-group').removeClass('has-success');
                    if (!curInput.hasClass('error') && curInput.val()) {
                        if (!curInput.parents('.form-group').find('span.help-block.fixed-length-message').length && !curInput.parents('td').find('span.help-block.number-message').length) {
                            curInput.after("<span class='help-block error-span fixed-length-message'>Please enter a valid "+fixedlength+" digit mobile number!</span>");
                        }
                        curInput.addClass('error');
                    } else {
                        if (!curInput.hasClass('required') && !curInput.val()) {
                            fixedLengthValid = true;
                            curInput.removeClass('error');
                            curInput.parents('.form-group').find('span.help-block.fixed-length-message').remove();
                            curInput.parents('td').find('span.help-block.fixed-length-message').remove();
                            curInput.parents('.form-group').addClass('has-success');
                        }
                    }
                }
            }
            if ($(this).hasClass('password')){
                var value = $(this).val();
                var number = /\d/;
                var specialchar = /[^A-z0-9_]/;
                var alpha = /[A-z]/;
                if (/*(value.search(number) !== -1) && (value.search(specialchar) !== -1) && (value.search(alpha) !== -1) && */(value.length >= 5)) {
                    if (requiredvalid) {
                        curInput.removeClass('error');
                    }
                    curInput.parents('.form-group').find('span.help-block.password-message').remove();
                    curInput.parents('td').find('span.help-block.password-message').remove();
                    curInput.parents('.form-group').addClass('has-success');
                } else {
                    passwordvalid = false;
                    curInput.parents('.form-group').removeClass('has-success');
                    if (!curInput.hasClass('error') && curInput.val()) {
                        if (!curInput.parents('.form-group').find('span.help-block.password-message').length && !curInput.parents('td').find('span.help-block.number-message').length) {
                            //curInput.after("<span class='help-block error-span password-message'>Password should contain numbers, alphabets and a special character and must be more than five characters long!</span>");
                            curInput.after("<span class='help-block error-span password-message'>Password should be more than five characters long!</span>");
                        }
                        curInput.addClass('error');
                    } else {
                        if (!curInput.hasClass('required') && !curInput.val()) {
                            passwordvalid = true;
                            curInput.removeClass('error');
                            curInput.parents('.form-group').find('span.help-block.password-message').remove();
                            curInput.parents('td').find('span.help-block.password-message').remove();
                            curInput.parents('.form-group').addClass('has-success');
                        }
                    }
                }
            }
            if ($(this).hasClass('confirmpassword')) {
                if ($(this).val() == form.find('.password').val() && $(this).val()!='') {
                    if (requiredvalid) {
                        curInput.removeClass('error');
                    }
                    curInput.parents('.form-group').find('span.help-block.confirmpassword-message').remove();
                    curInput.parents('td').find('span.help-block.confirmpassword-message').remove();
                    curInput.parents('.form-group').addClass('has-success');
                } else {

                    confirmpasswordvalid = false;
                    curInput.parents('.form-group').removeClass('has-success');
                    if ((!curInput.hasClass('error') || !confirmpasswordvalid) && curInput.val()) {
                        if (!curInput.parents('.form-group').find('span.help-block.confirmpassword-message').length && !curInput.parents('td').find('span.help-block.number-message').length) {
                            curInput.after("<span class='help-block error-span confirmpassword-message'>Passwords should match!</span>");
                        }
                        curInput.addClass('error');
                    } else {
                        if (!curInput.hasClass('required') && !curInput.val()) {
                            confirmpasswordvalid = true;
                            curInput.removeClass('error');
                            curInput.parents('.form-group').find('span.help-block.confirmpassword-message').remove();
                            curInput.parents('td').find('span.help-block.confirmpassword-message').remove();
                            curInput.parents('.form-group').addClass('has-success');
                        }
                    }
                }
            }
            if ($(this).hasClass('email')) {
                var str = $(this).val();
                var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                if (re.test(str)) {
                    //emailvalid = true;
                    if (requiredvalid || emailvalid) {
                        curInput.removeClass('error');
                    }
                    curInput.parents('.form-group').find('span.help-block.email-message').remove();
                    curInput.parents('td').find('span.help-block.email-message').remove();
                    curInput.parents('.form-group').addClass('has-success');
                } else {
                    emailvalid = false;
                    curInput.parents('.form-group').removeClass('has-success');
                    if (!curInput.hasClass('error') && curInput.val()) {
                        if (!curInput.parents('.form-group').find('span.help-block.email-message').length && !curInput.parents('td').find('span.help-block.number-message').length) {
                            curInput.after("<span class='help-block error-span email-message'>Enter a valid Email!</span>");
                        }
                        curInput.addClass('error');
                    } else {
                        if (!curInput.hasClass('required') && !curInput.val()) {
                            emailvalid = true;
                            curInput.removeClass('error');
                            curInput.parents('.form-group').find('span.help-block.email-message').remove();
                            curInput.parents('td').find('span.help-block.email-message').remove();
                            curInput.parents('.form-group').addClass('has-success');
                        }
                    }
                }
            }


        });
        form.find('select').each(function () {
            if ($(this).hasClass('required')) {
                var curInput = $(this);
                var name = curInput.attr('name');
                if ($(this).val() == '') {
                    requiredvalid = false;
                    curInput.parents('.form-group').removeClass('has-success');
                    if (!curInput.parents('.form-group').find('span.help-block:not(.info-block)').length && !curInput.parents('td').find('span.help-block:not(.info-block)').length) {
                        curInput.after("<span class='help-block error-span required-message'>This field is required!</span>");
                    }
                    curInput.addClass('error');
                } else {
                    curInput.removeClass('error');
                    curInput.parents('.form-group').find('span.help-block.required-message').remove();
                    curInput.parents('td').find('span.help-block.required-message').remove();
                    curInput.parents('.form-group').addClass('has-success');
                }
            }
        });
        if (!requiredvalid || !hrregvalid || !cidValid || !eqregvalid || !uniqueValid || !numbervalid || !passwordvalid || !captchavalid || !regNoValid || !confirmpasswordvalid || !emailvalid || !fixedLengthValid){
            form.find('button[type="submit"],input[type="submit"]').attr('disabled','disabled');
            return false;
        } else {
            if($('.checkhrtr').length>0){
                var checkhrtrValid = true;
                $('.checkhrtr').each(function(){
                    if($(this).hasClass('error')){
                        checkhrtrValid = false;
                    }
                });
                if(checkhrtrValid){
                    $('button[type="submit"],input[type="submit"]').removeAttr('disabled');
                    return true;
                }else {
                    $('button[type="submit"],input[type="submit"]').attr('disabled', 'disabled');
                    return false;
                }
            }else{
                form.find('button[type="submit"],input[type="submit"]').removeAttr('disabled');
                return true;
            }

        }
    }
    function checkHumanResourceRegistration(cidNo,firmId,type,partnerOwner){
        var url = $('input[name="URL"]').val();
        $.ajax({
            url: url+'/checkhumanresourceregistration',
            type: 'post',
            dataType: 'json',
            data: {cidNo:cidNo, firmId: firmId?firmId:0,type:type,partnerOwner:partnerOwner}, //type = 1 for contractor, 2 for consultant
            success: function(data){
                if(data){
                    if(data.message != 1){
                        $('.check-hr-registration').each(function () {
                            if ($(this).val() == cidNo) {
                                hrregvalid = false;
                                $(this).parent().find('span.error-span').remove();
                                $(this).after('<span class="help-block error-span hr-error-message">'+data.message+'</span>');
                                $(this).addClass('error');
                                validate($(this).parents('form'));

                            }
                        });
                        $('.checkhrtr').each(function () {
                            if ($(this).val() == cidNo) {
                                $(this).parent().find('span.error-span').remove();
                                $(this).addClass('error');
                                $(this).after('<span class="help-block error-span hr-error-message">'+data.message+'</span>');
                            }
                        });
                        //if(data.reason == 3 || data.reason == 4){

                        //}
                    }else{
                        $('.checkHumanResource').each(function(){
                            if ($(this).val() == cidNo) {
                                hrregvalid = true;
                                $(this).removeClass('error');
                                $(this).parents('td').find('span.error-span').remove();
                                validate($(this).parents('form'));

                            }
                        });
                    }
                }else{
                    $('.check-hr-registration').each(function(){
                        if ($(this).val() == cidNo) {
                            hrregvalid = true;
                            $(this).removeClass('error');
                            $(this).parents('td').find('span.error-span').remove();
                            validate($(this).parents('form'));

                        }
                    });
                    $('.checkhrtr').each(function(){
                        if ($(this).val() == cidNo) {
                            $(this).removeClass('error');
                            $(this).parents('td').find('span.error-span').remove();
                        }
                    });
                    if(hrregvalid){
                        $('.check-hr-registration').each(function () {
                            if ($(this).val() == cidNo) {
                                $(this).parent().find('span.error-span').remove();
                                $(this).removeClass('error');
                                $(this).parents('form').find('button[type="submit"]').removeAttr('disabled');
                            }
                        });

                    }
                }
                $('#hrcheckmodal .modal-body').load(url+'/hrcheck?CIDNo='+cidNo,{'from': 'addcontractor'});
                $('#addhumanresource').modal('hide');
                $('#hrcheckmodal').modal('show');
            }
        });
    }
    function checkHumanResource(cidNo,etlTenderBidderContractorId){
        var url = $('input[name="URL"]').val();
        var etlTenderId = $('.etltenderid').val();
        $.ajax({
            url: url+'/checkhumanresource',
            type: 'post',
            dataType: 'json',
            data: {cidNo:cidNo, etlTenderId:etlTenderId, etlTenderBidderContractorId: etlTenderBidderContractorId},
            success: function(data){
                if(data.message != 1){
                    $('.checkHumanResource').each(function () {
                        if ($(this).val() == cidNo) {
                            $(this).parents('td').find('span.error-span').remove();
                            $(this).after('<span class="help-block error-span hr-message">'+data.message+'</span>');
                            $(this).addClass('error');
                        }
                    });
                    if(data.reason == 1){
                        //$('.checkHumanResource').each(function () {
                        //    if ($(this).val() == cidNo) {
                        //        $('#hrcheckmodal .modal-body').load(url+'/etoolrpt/hrcheck?CIDNo='+cidNo,{'from': 'addcontractor'});
                        //        $('#hrcheckmodal').modal('show');
                        //    }
                        //});
                    }
                    if(data.reason == 2){
                        $('.checkHumanResource').each(function () {
                            if ($(this).val() == cidNo) {
                                $(this).parents('td').find('span.error-span').remove();
                                $(this).after('<span class="help-block error-span hr-message">'+data.message+'</span>');
                                $(this).addClass('error');
                                //$('#hrcheckmodal .modal-body').load(url+'/etoolrpt/hrotherfirms?CIDNo='+cidNo+'&etlTenderBidderContractorId='+etlTenderBidderContractorId,{'from': 'addcontractor'});
                                //$('#hrcheckmodal').modal('show');
                            }
                        });
                    }
			if(data.reason == 3){
                        //$('.checkHumanResource').each(function () {
                        //    if ($(this).val() == cidNo) {
                        //        $('#hrcheckmodal .modal-body').load(url+'/etoolrpt/hrcheck?CIDNo='+cidNo,{'from': 'addcontractor'});
                        //        $('#hrcheckmodal').modal('show');
                        //    }
                        //});
                    }
                }else{
                    $('.checkHumanResource').each(function(){
                        if ($(this).val() == cidNo) {
                            $(this).removeClass('error');
                            $(this).parents('td').find('span.error-span').remove();
                        }
                    });
                }
            }
        });
    }
    function checkHumanResourceOccupied(cdbNo){
        var url = $('input[name="URL"]').val();
        $.ajax({
            url: url + '/checkhumanresourceoccupied',
            type: 'post',
            dataType: 'json',
            data: {
                cdbNo: cdbNo
            },
            success: function (data) {
                if(data.message != 1){
                    $('.checkHumanResource').each(function () {
                        if ($(this).val() == cdbNo) {
                            $(this).parents('td').find('span.error-span').remove();
                            $(this).after('<span class="help-block error-span required-message">'+data.message+'</span>');
                            $(this).addClass('error');
                        }
                    });
                }else{
                    $('.checkHumanResource').each(function(){
                        if ($(this).val() == cdbNo) {
                            $(this).removeClass('error');
                            $(this).parents('td').find('span.error-span').remove();
                        }
                    });
                }
            }
        });
    }
    function checkEquipmentOccupied(registrationNo){
        var url = $('input[name="URL"]').val();
    }
    function checkEquipment(registrationNo, etlTenderBidderContractorId){
        var url = $('input[name="URL"]').val();
        var etlTenderId = $('.etltenderid').val();
        $.ajax({
            url: url+'/checkequipment',
            type: 'post',
            dataType: 'json',
            data: {registrationNo:registrationNo, etlTenderId:etlTenderId, etlTenderBidderContractorId: etlTenderBidderContractorId},
            success: function(data){
                if(data.message != 1){
                    $('.checkEquipment').each(function () {
                        if ($(this).val() == registrationNo) {
                            $(this).parents('td').find('span.error-span').remove();
                            $(this).after('<span class="help-block error-span required-message">'+data.message+'</span>');
                            $(this).addClass('error');
                        }
                    });
                    if((data.reason == 3) || (data.reason == 4)){
                        $('.checkEquipment').each(function () {
                            if ($(this).val() == registrationNo) {
                                //$('#eqcheckmodal .modal-body').load(url+'/etoolrpt/equipmentcheck?RegistrationNo='+registrationNo,{'from': 'addcontractor'});
                                //$('#eqcheckmodal').modal('show');
                                //$('#eqcheckmodal .modal-body').load(URL+'/etoolrpt/equipmentcheck?RegistrationNo='+registrationNo+'&VehicleType='+vehicleType,{'from': 'addcontractor'});
                                //$('#eqcheckmodal').modal('show');
                            }
                        });
                    }
                }else{
                    $('.checkEquipment').each(function () {
                        if ($(this).val() == registrationNo) {
                            /*$('#eqcheckmodal .modal-body').load(url+'/etoolrpt/equipmentcheck?RegistrationNo='+registrationNo,{'from': 'addcontractor'});
                            $('#eqcheckmodal').modal('show');*/
				$(this).removeClass('error')
				$(this).parents('td').find('span.error-span').remove();
                        }
                    });
                }
            }
        });
    }
    function initializeAutocomplete(){
        if($('.etooluser-autocomplete').length>0){
            var ajaxUrl = '/sys/fetchetoolusers';
            var URL = $('input[name="URL"]').val();
            $( ".etooluser-autocomplete" ).autocomplete({
                source: URL+ajaxUrl,
                minLength: 2,
                select: function( event, ui ) {
                    $(this).parents('.ui-widget').find('.etooluser-id').val(ui.item.id);
                }
            });
        }
        if($('.cinetuser-autocomplete').length>0){
            var ajaxUrl = '/sys/fetchcinetusers';
            var URL = $('input[name="URL"]').val();
            $( ".cinetuser-autocomplete" ).autocomplete({
                source: URL+ajaxUrl,
                minLength: 2,
                select: function( event, ui ) {
                    $(this).parents('.ui-widget').find('.cinetuser-id').val(ui.item.id);
                }
            });
        }
        if($('.contractor-autocomplete').length>0){
            var URL = $('input[name="URL"]').val();
            $( ".contractor-autocomplete" ).autocomplete({
                source: URL+'/contractor/fetchcontractorsjson',
                minLength: 2,
                select: function( event, ui ) {
                    $(this).parents('.ui-widget').find('.contractor-id').val(ui.item.id);
                    var currentRow = $(this).closest('tr');
                    if($("#monitoring-office").length>0){
                        $.ajax({
                            url: URL+"/contractor/fetchcontractorsdetails",
                            type: "POST",
                            dataType: "JSON",
                            data: {id: ui.item.id},
                            success: function(data){
                                var hrDetails = data.hrDetails;
                                var eqDetails = data.eqDetails;
                                var categoryDetails = data.categoryDetails;
                                if(hrDetails.length>0){
                                    var hrHtml = "<div class='checkbox-list'>";
                                    for(var x in hrDetails){
                                        hrHtml+="<label><input type='checkbox'/>"+hrDetails[x].Designation+" ("+hrDetails[x].Personnel+")</label>";
                                    }
                                    hrHtml+="</div>";
                                    currentRow.find('.hr-details').html(hrHtml);
                                }
                                if(eqDetails.length>0){
                                    var eqHtml = "<div class='checkbox-list'>";
                                    for(var x in eqDetails){
                                        eqHtml+="<label><input type='checkbox'/>"+eqDetails[x].Equipment+" ("+eqDetails[x].RegistrationNo+")</label>";
                                    }
                                    eqHtml+="</div>";
                                    currentRow.find('.eq-details').html(eqHtml);
                                }
                                if(categoryDetails.length>0){
                                    var categoryHtml = "<table class='table table-bordered table-striped table-condensed dont-flip'><thead class=''><tr class='dont-clone'><th width='5%' class='table-checkbox'></th><th width='40%'>Category</th><th>Class</th></tr></thead><tbody><tr class='dont-clone'><td><input type='checkbox' checked='checked'/></td><td>W2</td><td><select class='form-control input-sm'><option value=''></option><option value=''>Large</option><option value='' selected='selected'>Medium</option><option value=''>Small</option></select></td></tr><tr class='dont-clone'><td><input type='checkbox' /></td><td>W1</td><td><select class='form-control input-sm' disabled='disabled'><option value=''>---SELECT---</option><option value=''>Registered</option></select></td></tr><tr class='dont-clone'><td><input type='checkbox' checked='checked'/></td><td>W3</td><td><select class='form-control input-sm'><option value=''></option><option value=''>Large</option><option value='' selected='selected'>Medium</option><option value=''>Small</option></select></td></tr><tr class='dont-clone'><td><input type='checkbox' checked='checked'/></td><td>W4</td><td><select class='form-control input-sm'><option value=''></option><option value'' selected='selected'>Large</option><option value'>Medium</option><option value'>Small</option></select></td></tr></tbody></table>";
                                    //for(var x in categoryDetails){
                                    //    categoryHtml+="<label><input type='checkbox'/>"+eqDetails[x].Equipment+" ("+eqDetails[x].RegistrationNo+")</label>";
                                    //}
                                    //categoryHtml+="</div>";
                                    currentRow.find('.category-details').html(categoryHtml);
                                }

                            }
                        });
                    }
                }
            });
        }
        if($('.consultant-autocomplete').length>0){
            var URL = $('input[name="URL"]').val();
            $( ".consultant-autocomplete" ).autocomplete({
                source: URL+'/consultant/fetchconsultantsjson',
                minLength: 2,
                select: function( event, ui ) {
                    $(this).parents('.ui-widget').find('.consultant-id').val(ui.item.id);
                }
            });
        }
        if($('.architect-autocomplete').length>0){
            var URL = $('input[name="URL"]').val();
            $( ".architect-autocomplete" ).autocomplete({
                source: URL+'/architect/fetcharchitectsjson',
                minLength: 2,
                select: function( event, ui ) {
                    $(this).parents('.ui-widget').find('.architect-id').val(ui.item.id);
                }
            });
        }
        if($('.pauser-autocomplete').length>0){
            var URL = $('input[name="URL"]').val();
            $( ".pauser-autocomplete" ).autocomplete({
                source: URL+'/sys/fetchpausersjson',
                minLength: 2,
                select: function( event, ui ) {
                    $(this).parents('.ui-widget').find('.pauser-id').val(ui.item.id);
                }
            });
        }
        if($('.engineer-autocomplete').length>0){
            var URL = $('input[name="URL"]').val();
            $( ".engineer-autocomplete" ).autocomplete({
                source: URL+'/engineer/fetchengineersjson',
                minLength: 2,
                select: function( event, ui ) {
                    $(this).parents('.ui-widget').find('.engineer-id').val(ui.item.id);
                }
            });
        }
        if($('.specializedtrade-autocomplete').length>0){
            var URL = $('input[name="URL"]').val();
            $( ".specializedtrade-autocomplete" ).autocomplete({
                source: URL+'/specializedtrade/fetchspecializedtradesjson',
                minLength: 2,
                select: function( event, ui ) {
                    $(this).parents('.ui-widget').find('.specializedtrade-id').val(ui.item.id);
                }
            });
        }
    }
    function checkEquipmentRSTA(vehicleType,regNo,flag){
        var url = $('input[name="URL"]').val();
        $.post(url+'/rstawebservice',{vehicleType: vehicleType, regNo: regNo},function(data){
            var html = "";
            if(data.success == 1){
                var results = data.results;
                for(var x in results){
                    if(html!=''){
                        html+="<br/>";
                    }
                    html+='<b>Registered To: </b>'+results[x].Owner+"&nbsp; ";
                    html+='<b>Owner CID: </b>'+results[x].CIDNo+"&nbsp; ";
                    html+='<b>Vehicle Type: </b>'+results[x].VehicleType+"&nbsp; ";
                    html+='<b>Region: </b>'+results[x].Region;
                }
                html = "<h4>Equipment Details</h4>"+html+"<br/><br/>";
                if(flag){
                    $("#equipmentrstamodal .modal-body").html(html);
                    $('#equipmentrstamodal').modal('show');
                }else{
                    $('#equipment-details').empty().html(html);
                    $('#equipment-details').removeClass('hide');
                }
            }else{
                if(flag){
                    $("#equipmentrstamodal .modal-body").html("<h4>Equipment Details</h4><strong>---</strong>");
                    $('#equipmentrstamodal').modal('show');    
                }else{
                    $('#equipment-details').empty().html("<h4>Equipment Details</h4><strong>---</strong>");
                    $('#equipment-details').removeClass('hide');
                }
            }

            

        });
    }
    function checkSession(){
        var userId = $('#loggeduser-id').val();
        var url = $("#apache-sitelink").val();
        $.ajax({
            url: url+"/checksession",
            dataType: "JSON",
            type: "POST",
            data: {userId: userId},
            success: function(data){
                if(parseInt(data.Response) == 0){
                    $("#sessionexpiredmodal").modal("show");
                }else{
                    $("#sessionexpiredmodal").modal("hide");
                }
            }
        });
        setTimeout(function(){
            checkSession()
        },30000);
    }
    function initialize(){
        var deletedRow;
        $('[data-rel="tooltip"]').tooltip();
        initializeAutocomplete();

        //$(document).on("click","#submit-application",function(e){
        //    if($("#agreement-checkbox").is(":checked")){
        //        return true;
        //    }else{
        //        e.preventDefault();
        //        $("#modalmessageheader").text("Error!");
        //        $("#modaldimessagetext").text("Please agree with the terms and conditions in order to continue.");
        //        $("#modalmessagebox").modal("show");
        //    }
        //});
        //$("#CmnCountryId, #CmnOwnershipTypeId").on('change',function(){
        //    var isBhutan = $("#CmnCountryId option:selected").data('bhutan');
        //    if(isBhutan == 0){
        //
        //    }
        //});
        if($("#sessionexpiredmodal").length>0){
            checkSession();
        }
        //$("#architect-cid").on('change',function(){
        //    var value = $(this).val();
        //    if(value!=''){
        //        var baseUrl = $("input[name='URL']").val();
        //        $.post(baseUrl+"/architect/checkarchitectisregistered",{cid:value},function(data){
        //            if(data == 1){
        //
        //            }
        //        });
        //    }
        //
        //});
        $(document).on("click",".change-expirydate",function(){
            var id = $(this).data('id');
            var date = $(this).data('expirydate');
            $("#current-expdate").text(date);
            $("#Id").val(id);
        });
        $(document).on('change','#registration-hrdesignation',function(){
            var curElement = $(this);
            if(curElement.val()){
                if(curElement.find('option:selected').data('reference') == 101){
                    $('#propcumengineerwrapper').removeClass('hide');
                    $('#propcumengineerwrapper').find('input[type="file"]').addClass('required');
                }else{
                    $('#propcumengineerwrapper').addClass('hide');
                    $('#propcumengineerwrapper').find('input[type="file"]').removeClass('required');
                }
            }else{
                 $('#propcumengineerwrapper').removeClass('hide');
                 $('#propcumengineerwrapper').find('input[type="file"]').addClass('required');
            }
        });
        $('#services-list input[type="checkbox"]').on('click',function(e){
            var curCheckbox, serviceType;
            var one = false;
            var two = false;
            $('#services-list input[type="checkbox"]').each(function(){
                curCheckbox = $(this);
                if(curCheckbox.is(':checked')){
                    serviceType = curCheckbox.data('servicetype');
                    if(serviceType == 1){
                        one = true;
                    }
                    if(serviceType == 2){
                        two = true;
                    }
                }
            });
            if(one){
                $('.services-btn').attr('type','button');
                $('.services-btn').attr('id','changeoflocationowner');
            }else{
                if(two){
                    $('.services-btn').attr('type','submit');
                    $('.services-btn').attr('id','submit-services');
                }
            }
        });
        $(".services-btn").on('click',function(e){
            var renewal = $('input[name="RenewalService"]').length;
            if(renewal==1){
                var serviceSelected = false;
                var documentValid = true;
                $("#services-list input[type='checkbox']").each(function(){
                    if($(this).is(':checked')){
                        serviceSelected = true;
                    }
                });

                //if(serviceSelected){
                    if($("#refresher-attachment").length && ($("#IsRejected").length==0)){
                        if(!$("#refresher-attachment").val()){
                            e.preventDefault();
                            documentValid = false;
                            $(".refresherattachmenterror").removeClass('hide');
                            setTimeout(function() {
                                $('#generalinfoservice').modal('show');
                            },800);
                        }else{
                            $('.refresherattachmenterror').addClass('hide');
                        }
                    }
                    if(documentValid){
                        return true;
                    }

                //}else{
                //    e.preventDefault();
                //    alert("Please select a service");
                //}
            }else{
                var serviceSelected = false;
                var documentValid = true;
                $("#services-list input[type='checkbox']").each(function(){
                    if($(this).is(':checked')){
                        serviceSelected = true;
                    }
                });

                if(!serviceSelected){
                    e.preventDefault();
                }
            }
        });
        $('#cdbnoforreg, #RegistrationType').on('change',function(){
            var URL = $('input[name="URL"]').val();
            var cdbNo = $('#cdbnoforreg').val();
            var regType = $('#RegistrationType').val();
            if(regType && cdbNo){
                $.post(URL+'/pullfirmnameregistration',{regType:regType,cdbNo:cdbNo},function(data){
                    if(!data.Name){
                        $('#FirmName').val('');
                        $('#ApplicantEmail').val('');
                        alert('Invalid CDB No. or not registered');
                    }else{
                        if(data.UserId == 0){
                            $('#FirmName').val(data.Name);
                            if(data.Email){
                                $('#ApplicantEmail').val(data.Email);
                            }
                            var form = $('#RegistrationType').parents('form');
                            if(submitted)
                                validate(form);
                        }else{
                            $('#FirmName').val('');
                            $('#ApplicantEmail').val('');
                            alert('This applicant already has a User Account');
                        }

                    }

                });
            }
        });
        $('table:not(.dont-flip), table.flip').each(function(){
            var curTable = $(this);
            var parentContainer = curTable.parent();
            if(parentContainer.parent().find('div.table-responsive').length==0 && parentContainer.parent().find('.form-body').length>0){
                curTable.after('<div class="table-responsive flipped" style="overflow-x: scroll;"></div>');
                var copy = curTable.clone();
                curTable.remove();
                parentContainer.find('div.table-responsive').html(copy);
            }
            if(parentContainer.parent().find('div.table-responsive').length && parentContainer.parent().find('.form-body').length>0){
                parentContainer.parent().find('div.table-responsive').addClass('flipped').css('overflow-x','scroll');
            }

        });
        $('#updateetoolstatus').on('click',function(e){
            var status = $('select[name="CmnWorkExecutionStatusId"]').find(':selected').text();
            var reply = confirm('You are going to change the status of the work to '+status+'. Are you sure?');
            if(reply){
                return true;
            }else{
                return false;
            }
        });
        if($('.marquee').length){
            $('.marquee').removeClass('hide');
            $('.marquee').marquee({
                duration: 15000,
                duplicated: true,
                pauseOnHover: true
            });
        }
        $('#hide-heading').on('click',function(){
            $('#marquee-heading').addClass('hide');
            $('#marquee-heading').find('input[type="text"]').val('');
            $('#marquee-heading').find('input[type="text"]').removeClass('required');
        });
        $('#show-heading').on('click',function(){
            $('#marquee-heading').removeClass('hide');
            $('#marquee-heading').find('input[type="text"]').addClass('required');
        });

        $('.delete-circular-img').on('click',function(){
            var curElement = $(this);
            var parentSpan = curElement.parents('span');
            var id = curElement.data('id');
            var reply = confirm("Are you sure that you want to delete this image?");
            if(reply){
                var URL = $('input[name="URL"]').val();
                $.post(URL+"/web/deletecircularimage",{id:id,table:"webcircular"},function(){
                    parentSpan.remove();
                });
            }
        });
        $('.delete-circular-attachment').on('click',function(){
            var curElement = $(this);
            var parentSpan = curElement.parents('span');
            var id = curElement.data('id');
            var reply = confirm("Are you sure that you want to delete this image?");
            if(reply){
                var URL = $('input[name="URL"]').val();
                $.post(URL+"/web/deletecircularfile",{id:id,table:"webcircular"},function(){
                    parentSpan.remove();
                });
            }
        });

        $('.delete-ad-img').on('click',function(){
            var curElement = $(this);
            var parentSpan = curElement.parents('span');
            var id = curElement.data('id');
            var reply = confirm("Are you sure that you want to delete this image?");
            if(reply){
                var URL = $('input[name="URL"]').val();
                $.post(URL+"/web/deletecircularimage",{id:id,table:"webadvertisements"},function(){
                    parentSpan.remove();
                });
            }
        });
        $('.delete-ad-attachment').on('click',function(){
            var curElement = $(this);
            var parentSpan = curElement.parents('span');
            var id = curElement.data('id');
            var reply = confirm("Are you sure that you want to delete this image?");
            if(reply){
                var URL = $('input[name="URL"]').val();
                $.post(URL+"/web/deletecircularfile",{id:id,table:"webadvertisementattachment"},function(){
                    parentSpan.remove();
                });
            }
        });

        //$(document).on('change', '.equipmentforwebservicetr',function(){
        //    var vehicleType = $('option:selected',this).data('vehicletype');
        //    var curRow = $(this).parents('tr');
        //    var regno = curRow.find('.regnoforwebservicetr').val();
        //    regno = regno.toString();
        //    regno = regno.trim();
        //    if(vehicleType && regno){
        //        checkEquipmentRSTA(vehicleType,regno,true);
        //    }
        //});
        $(document).on('click','.checkhrdbandwebservice',function(){
            var curElement = $(this);
            var cid = curElement.data('cid');
            cid = cid.toString();
            cid = cid.trim();
            var URL = $('input[name="URL"]').val();
            $('#hrcheckmodal .modal-body').load(URL+'/etoolrpt/hrcheck?CIDNo='+cid,{'from': 'addcontractor'});
            $('#hrcheckmodal').modal('show');
        });
        $(document).on('blur','.checkeqdbandwebservicemodal',function(){
            var curElement = $(this);
            var regNo = curElement.val();
            regNo = regNo.toString();
            if($('input[name="CrpContractorId"]').length){
                var id = $('input[name="CrpContractorId"]').val();
                var type = 1;
            }else{
                var id = $('input[name="CrpConsultantId"]').val();
                var type =2;
            }
            var URL = $('input[name="URL"]').val();
            $.ajax({
                url: URL+'/checkeqdbandwebservice',
                type: 'post',
                dataType: 'json',
                data: {regNo: regNo.trim(), id: id, type: type},
                success: function(data){
                    if(data.Reason == 1 || data.Reason == 2 || data.Reason == 3){
                        eqregvalid = false;
                        if(data.Reason == 1){
                            var message = "This equipment has already been registered with your firm";
                        }else if(data.Reason == 2){
                            var message = "This equipment belongs to another firm";
                        }else if(data.Reason == 3){
                            var message = "This equipment is engaged in work(s)";
                        }
                        curElement.parent().find('span.error-span').remove();
                        curElement.addClass('error');
                        curElement.after('<span class="help-block error-span eq-error-message">'+message+'&nbsp;&nbsp;<button data-regno="'+regNo+'" id="view-eq-details" type="button">Details</button></span>');
                        curElement.parents('form').find('button[type="submit"]').attr('disabled','disabled');
                    }else{
                        eqregvalid = true;
                        curElement.parent().find('span.error-span').remove();
                        curElement.removeClass('error');
                        validate(curElement.parents('form'));
                    }
                }
            })
        });
        $(document).on('click','#view-eq-details',function(){
            var regNo = $(this).data('regno');
            regNo = regNo.toString();
            regNo = regNo.trim();
            var URL = $('input[name="URL"]').val();
            $('#addequipments').modal('hide');
            $('#eqcheckmodal .modal-body').load(URL+'/equipmentcheck?RegistrationNo='+regNo,{'from': 'addcontractor'});
            $('#eqcheckmodal').modal('show');
        });
        $(document).on('click','.checkeqdbandwebservice',function(){
            var curElement = $(this);
            var regNo = curElement.data('regno');
            var vehicleType = curElement.data('vehicletype');
            var URL = $('input[name="URL"]').val();
            $('#eqcheckmodal .modal-body').load(URL+'/etoolrpt/equipmentcheck?RegistrationNo='+regNo+'&VehicleType='+vehicleType,{'from': 'addcontractor'});
            $('#eqcheckmodal').modal('show');
        });
        $(document).on('change', '.regnoforwebservicetr',function(){
            var regno = $(this).val();
            var curRow = $(this).parents('tr');
            var vehicleType = curRow.find('.equipmentforwebservicetr').find('option:selected').data('vehicletype');
            if(vehicleType && regno){
                checkEquipmentRSTA(vehicleType,regno,true);
            }
        });

        $(document).on('change', '.equipmentforwebservicemodal',function(){
            var vehicleType = $('option:selected',this).data('vehicletype');
            var curRow = $(this).parents('.modal');
            var regno = curRow.find('.regnoforwebservicemodal').val();
            if(vehicleType && regno){
                checkEquipmentRSTA(vehicleType,regno,false);
            }
        });
        $(document).on('blur', '.regnoforwebservicemodal',function(){
            var regno = $(this).val();
            var curRow = $(this).parents('.modal');
            var vehicleType = curRow.find('.equipmentforwebservicemodal').find('option:selected').data('vehicletype');
            if(vehicleType && regno){
                checkEquipmentRSTA(vehicleType,regno,false);
            }
        });


        $(document).on('change','#circular-type',function(){
            var optionSelected = $(this).find('option:selected');
            if(optionSelected.data('reference') == 4){
                $('#featuredhidden-toggle').removeAttr('disabled');
                $('#featureddiv-toggle').find('input[type="radio"]').attr('disabled','disabled');
                $('#featureddiv-toggle').hide();
            }else{
                $('#featuredhidden-toggle').attr('disabled','disabled');
                $('#featureddiv-toggle').find('input[type="radio"]').removeAttr('disabled');
                $('#featureddiv-toggle').show();
            }
        });
        $(document).on('click','.delete-record',function(){
            var parent = $(this).parents('tr');
            var id=parent.find('.record-id').val();
            var reply = confirm("Are you sure you want to delete this record?");
            var url = $('input[name="URL"]').val();
            var ajaxUrl = $('input[name="AjaxURL"]').val();
            if(reply){
                $.post(url+'/'+ajaxUrl,{id:id},function(){
                    parent.remove();
                    alert("Successfully deleted!");
                });
            }
        });

        $('.enable-input').on('click',function(){
            if($(this).is(':checked')){
                $(this).parents('.form-group').find('.input').removeAttr('disabled').addClass('required');
            }else{
                $(this).parents('.form-group').find('.input').attr('disabled','disabled').val('').removeClass('required');
            }
        });

        $('#ld-toggle').on('click',function(){
            if($(this).is(':checked')){
                $('.ld-input').removeAttr('disabled').addClass('required');
            }else{
                $('.ld-input').attr('disabled','disabled').val('').removeClass('required');
            }
        });

        $(document).on('change','.cidforwebservicetr',function(){
            var currentRow = $(this).parents('tr');
            var cidNo = $(this).val();
            var url = $('input[name="URL"]').val();
            $.post(url+'/webserviceretrievedetails',{cidNo:cidNo},function(data){
                if(currentRow.find('.namefromwebservice').length>0){
                    currentRow.find('.namefromwebservice').val(data.name);
                }
                if(currentRow.find('.sexfromwebservice').length>0){
                    var currentDDL = currentRow.find('.sexfromwebservice');
                    currentDDL.find('option').each(function(){
                        if($(this).text() == data.gender){
                            $(this).attr('selected','selected');
                        }
                    });
                }
                if(currentRow.find('.dobfromwebservice').length>0){
                    currentRow.find('.dobfromwebservice').val(data.dob);
                }
                if(data.IsCivilServant == 1){
                    currentRow.parents('form').find('button[type="submit"]').attr('disabled','disabled');
                    alert('Personnel is a Government Employee');
                }else{
                    currentRow.parents('form').find('button[type="submit"]').removeAttr('disabled');
                }
                validate(currentRow.parents('form'));
            });
        });
        $(document).on('change','.cidforwebservicemodal',function(){
            var currentModal = $(this).parents('.modal-body');
            var cidNo = $(this).val();
            var url = $('input[name="URL"]').val();
            $.post(url+'/webserviceretrievedetails',{cidNo:cidNo},function(data){
                if(currentModal.find('.namefromwebservice').length>0){
                    currentModal.find('.namefromwebservice').val(data.name);
                }
                if(currentModal.find('.sexfromwebservice').length>0){
                    var currentDDL = currentModal.find('.sexfromwebservice');
                    currentDDL.find('option').each(function(){
                        if($(this).text() == data.gender){
                            $(this).attr('selected','selected');
                        }
                    });
                }
                if(currentModal.find('.dobfromwebservice').length>0){
                    currentModal.find('.dobfromwebservice').val(data.dob);
                }
                if(data.IsCivilServant == 1){
                    currentModal.find('button[type="submit"]').attr('disabled','disabled');
                    alert('Personnel is a Government Employee');
                }else{
                    currentModal.find('button[type="submit"]').removeAttr('disabled');
                }
                validate(currentModal.parents('form'));
            });
        });
        $(document).on('change','.cidforwebservice',function(){
            var curElement = $(this);
            var cidNo = curElement.val();
            var url = $('input[name="URL"]').val();
            $.post(url+'/webserviceretrievedetails',{cidNo:cidNo},function(data){
                if($('.namefromwebservice').length>0){
                    $('.namefromwebservice').val(data.name);
                }
                if($('.sexfromwebservice').length>0){
                    var currentDDL = $('.sexfromwebservice');
                    currentDDL.find('option').each(function(){
                        if($(this).text() == data.gender){
                            $(this).attr('selected','selected');
                        }
                    });
                }
                if($('.dzongkhagfromwebservice').length>0){
                    var currentDDL = $('.dzongkhagfromwebservice');
                    currentDDL.find('option').each(function(){
                        if($(this).text() == data.dzongkhag){
                            $(this).attr('selected','selected');
                            $('.select2-container.dzongkhagfromwebservice').find('.select2-chosen').text(data.dzongkhag);
                        }
                    });
                }
                if($('.gewogfromwebservice').length>0){
                    $('.gewogfromwebservice').val(data.gewog);
                }
                if($('.villagefromwebservice').length>0){
                    $('.villagefromwebservice').val(data.village);
                }
                if($('.dobfromwebservice').length>0){
                    $('.dobfromwebservice').val(data.dob);
                }
                validate(curElement.parents('form'));
            });
        });

        /*Added by SWM on 27th June*/
        var duplicateCheck = false;
        var hrDuplicateCheck = false;
        var eqDuplicateCheck = false;
        /*End*/

        $(document).on('blur','.check-hr-registration',function(){
            var value = $(this).val();
            var modal = $(this).parents('.modal');
                if($(this).val()){
                    if($('input[name="CrpContractorId"]').length > 0){
                        if($('input[name="CrpContractorId"]').val()) {
                            checkHumanResourceRegistration(value, $('input[name="CrpContractorId"]').val(),1,0);
                        }else{
                            checkHumanResourceRegistration(value, false,1,0);
                        }
                    }else{
                        if($('input[name="CrpConsultantId"]').val()) {
                            checkHumanResourceRegistration(value, $('input[name="CrpConsultantId"]').val(),2,0);
                        }else{
                            checkHumanResourceRegistration(value, false,2,0);
                        }
                    }
                }
        });
        $(document).on('change','.checkhrtr',function(){
            var value = $(this).val();
            var form = $(this).parents('form');
            if($(this).val()){
                if($('#contractorgeneralinforegistrationform').length > 0){
                    checkHumanResourceRegistration(value, false,1,1);
                }else{
                    checkHumanResourceRegistration(value, false,2,1);
                }
            }
        });

        /*Modified on 27th June*/
         $(document).on('change','.checkHumanResource',function(){
             var cinetHr = $(this).hasClass('cinet-hr');
             var value = $(this).val();
             var table = $(this).parents('table');
             var currentRowIndex = table.find('tbody tr').index($(this).parents('tr'));
             if(typeof(etool) != 'undefined'){
                 if($(this).val()){
                     //Check for duplicate
                     var count = 0;
                     table.find('.checkHumanResource:not(:eq('+currentRowIndex+'))').each(function(){
                         if(($(this).val()).toUpperCase() == value.toUpperCase()){
                             count++;
                             duplicateCheck = true;
                             hrDuplicateCheck = true;
                             table.find('.checkHumanResource:eq('+currentRowIndex+')').removeClass('error');
                             table.find('.checkHumanResource:eq('+currentRowIndex+')').parents('td').find('span.error-span').remove();
                             $('button[type="submit"]').attr('disabled','disabled');
                             $('#modalmessageheader').html("<strong>Error !</strong>");
                             $('#modaldimessagetext').html("<strong>You have already entered this CID No.</strong>");
                             $('#modalmessagebox').modal('show');
                         }
                     });
                     if(count == 0){
                         hrDuplicateCheck = false;
                     }
                     if((eqDuplicateCheck == false) && (hrDuplicateCheck == false)){
                         $('button[type="submit"]').removeAttr('disabled','disabled');
                     }
                     if(hrDuplicateCheck == false){
                         //UNCOMMented on 14th June
                         if($('input[name="Id"]').val()) {
                             checkHumanResource(value, $('input[name="Id"]').val());
                             var url = $('input[name="URL"]').val();
                             $('#hrcheckmodal .modal-body').load(url+'/hrcheck?CIDNo='+value,{'from': 'addcontractor'});
                             $('#addhumanresource').modal('hide');
                             $('#hrcheckmodal').modal('show');
                         }
                         if(cinetHr){
                             var url = $('input[name="URL"]').val();
                             $('#hrcheckmodal .modal-body').load(url+'/hrcheck?CIDNo='+value,{'from': 'addcontractor'});
                             $('#hrcheckmodal').modal('show');
                         }
                     }
                     //End
                 }
             }else{
                 checkHumanResourceOccupied(value);
             }
        });
        $(document).on('change','.checkEquipment',function(){
            var table = $(this).parents('table');
            var currentRow = $(this).parents('tr');
            var regNo = $(this).val();
            var vehicleType = currentRow.find('.EtlEqEquipmentId').find('option:selected').data('vehicletype');
            var isRegistered = currentRow.find('.EtlEqEquipmentId').find('option:selected').data('isregistered');
            var currentRowIndex = table.find('tbody tr').index($(this).parents('tr'));

            if(typeof(etool) != 'undefined'){
                if(parseInt(isRegistered) == 1){
                    if($(this).val()){
                        var value = $(this).val();
                        //Check for duplicate
                        var count = 0;
                        table.find('.checkEquipment:not(:eq('+currentRowIndex+'))').each(function(){
                            if($(this).val() == value){
                                count++;
                                duplicateCheck = true;
                                eqDuplicateCheck = true;
                                table.find('.checkEquipment:eq('+currentRowIndex+')').removeClass('error');
                                table.find('.checkEquipment:eq('+currentRowIndex+')').parents('td').find('span.error-span').remove();
                                $('button[type="submit"]').attr('disabled','disabled');
                                $('#modalmessageheader').html("<strong>Error !</strong>");
                                $('#modaldimessagetext').html("<strong>You have already entered this Registration No.</strong>");
                                $('#modalmessagebox').modal('show');
                            }
                        });
                        if(count == 0){
                            eqDuplicateCheck = false;
                        }
                        if((eqDuplicateCheck == false) && (hrDuplicateCheck == false)){
                            $('button[type="submit"]').removeAttr('disabled','disabled');
                        }
                        if(eqDuplicateCheck == false){
                            if($('input[name="Id"]').val()) {
                                checkEquipment(value, $('input[name="Id"]').val());
                            }
                        }
                        //End
                    }
                }
            }else{
                checkEquipmentOccupied($(this).val());
            }

            if(regNo){
                regNo = regNo.toString();
                regNo = regNo.trim();
                var URL = $('input[name="URL"]').val();
                $('#addequipments').modal('hide');
                $('#eqcheckmodal .modal-body').load(URL+'/equipmentcheck?RegistrationNo='+regNo+'&VehicleType='+vehicleType,{'from': 'addcontractor'});
                $('#eqcheckmodal').modal('show');
            }

        });
        /*End*/
        $('.popoverdefaultopen').popover({
            html:true
        });
        $('.savedsuccessmessage').delay(5000).slideUp(500);
        var nowTemp = new Date();
        var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
        var fromDate = $('.datepickerfrom').datepicker({
            format:'dd-mm-yyyy',
            autoclose: true,
        }).on('changeDate', function (ev) {
            if (ev.date.valueOf() > toDate.datepicker("getDate").valueOf() || !toDate.datepicker("getDate").valueOf()) {
                var newDate = new Date(ev.date);
                newDate.setDate(newDate.getDate() + 1);
                toDate.datepicker("update", newDate);
            }
            $('.datepickerto')[0].focus();
        });
        var toDate = $('.datepickerto').datepicker({
            beforeShowDay: function (date) {
                if (!fromDate.datepicker("getDate").valueOf()) {
                    return date.valueOf() >= new Date().valueOf();
                } else {
                    return date.valueOf() > fromDate.datepicker("getDate").valueOf();
                }
            },
            autoclose: true,
            format:'dd-mm-yyyy',
        }).on('changeDate', function (ev) {});
        $('.datepicker').datepicker({
            autoclose:true,
            format:'dd-mm-yyyy'
        });
        /*-----------------calculate enddate based on duration-------------------------------------------------------------*/
        $('.calculateenddate').datepicker({
            format:'dd-mm-yyyy',
            autoclose: true
        }).on('changeDate', function (ev) {
            var duration=parseFloat($('.durationnumber').val());
            var durationLabel=$('.durationnumber').data('alertlabel');
            if(duration>0){
                var flooredMonths = Math.floor(duration);
                var days = (duration-parseFloat(flooredMonths)) * 30;
                days--;
                var startDate = new Date(ev.date);
                startDate.setMonth(startDate.getMonth( ) + 1 + flooredMonths );
                if(startDate.getMonth() == 0){
                    var month = '12';
                }else{
                    var month = ('0'+startDate.getMonth()).slice(-2);
                }
                var newDate = new Date(startDate.getTime()+days*24*60*60*1000);
                if(newDate.getMonth() == 0){
                    var month = '12';
                }else{
                    var month = ('0'+newDate.getMonth()).slice(-2);
                }
                //if(duration > 11){
                //    alert('greater');
                //    var year = newDate.getFullYear() - 1;
                //}else{
                //    alert('smaller');
                    var year = newDate.getFullYear();
                //}
                //if((flooredMonths%12) == 0){
                //    year += 1;
                //}
                var endDate=('0'+newDate.getDate()).slice(-2)+'-'+month+'-'+year;
                $('.durationendresult').val(endDate);
            }else{
                $('.calculateenddate').val("");
                alert('Please select the '+durationLabel+' field');
            }
        });
        /*$('.durationnumber').on('keyup',function(){
            var duration=parseInt($(this).val());
            if(!isNaN(duration) && duration>0){
                var selectedStartDate=$('.calculateenddate').val();
                if(!selectedStartDate){
                    return false;
                }
                var startDate=new Date(selectedStartDate);
                alert(startDate);
                startDate.setMonth(startDate.getDate( ) + 1 + duration );
                var endDate=('0'+startDate.getDate()).slice(-2)+'-'+('0'+startDate.getMonth()).slice(-2)+'-'+startDate.getFullYear();
                $('.durationendresult').val(endDate);
            }
        });*/
        /*-----------------------------------------------------------------------------------------------------------------*/
        $('.formonth').datepicker({
            autoclose:true,
            format:'mm-yyyy',
            startView: "months", 
            minViewMode: "months"
        });
        $('.yearonly').datepicker({
            autoclose:true,
            format:'yyyy',
            startView: "years", 
            minViewMode: "years"
        });
        $(document).on('change','.tablerowcheckbox',function(){
            if($(this).is(':checked')){
                $(this).parents('tr').find('.tablerowcontrol').addClass('required');
                $(this).parents('tr').find('.tablerowcontrol').removeAttr('disabled');
            }else{
                $(this).parents('tr').find('.tablerowcontrol').attr('disabled','disabled');
                $(this).parents('tr').find('.tablerowcontrol').removeClass('required');
                $(this).parents('tr').find('.error-span').remove();
                $(this).parents('tr').find('select').val("");
                $(this).parents('tr').find('span.select2-chosen').html("---SELECT ONE---");
            }
        });
         $('.hideshowtogglecontrol').on('change',function(){
             if($(this).is(':checked'))
                $('.showhidetoggle').removeClass("hide");
             else
                 $('.showhidetoggle').addClass("hide");
        });
         //--------------------check only one checkbox from multiple checkboxes
        $(document).on('click','.addrowcheckboxsinglecheck',function(){
            if($(this).is(':checked')){
                $(this).parents('span').addClass("checked");
                var index=$('.addrowcheckboxsinglecheck').index($(this));
                var length=$('.addrowcheckboxsinglecheck').length;
                for(var i=1;i<=length;i++){
                    if((i-1)!=index){
                        $(this).closest('tr').find('.tablerowcontrol').addClass('required');
                        $(this).closest('tr').find('.tablerowcontrol').removeAttr('disabled');
                        $('select.tablerowcontrol:eq('+(i-1)+')').attr('disabled','disabled').removeClass('required');
                        $('.addrowcheckboxsinglecheck:eq('+(i-1)+')').removeAttr('checked').parents('span').removeClass('checked');
                    }
                }
            }else{
                $(this).parents('span').removeClass("checked");
                $(this).closest('tr').find('.tablerowcontrol').attr('disabled','disabled');
                $(this).closest('tr').find('.tablerowcontrol').removeClass('required');
            }
        });
        //--------------------End check only one checkbox from multiple checkboxes
        $(document).on('click','.addrowcheckbox',function(){
            if($(this).is(':checked')){
                $(this).parents('span').addClass("checked");
            }else{
                $(this).parents('span').removeClass("checked");
            }
        });
        $('.countryselect').on('change',function(){
            var currentValue=$('option:selected',this).text();
            if(currentValue!="Bhutan"){
                $('.isbhutanese').attr('disabled','disabled');
                $('.isbhutanese').removeClass('required');
                $('.isbhutanese').closest('.form-group').find('.error-span').remove();
                $('.isbhutanese').removeClass('error');
                $(".isnonbhutanese").addClass('required');
            }else{
                $('.isbhutanese').removeAttr('disabled');
                $('.isbhutanese').addClass('required');
                $(".isnonbhutanese").removeClass('required');
                $('.isnonbhutanese').closest('.form-group').find('.error-span').remove();
                $('.isnonbhutanese').removeClass('error');
            }
        });

        $(document).on('click','.editaction',function(e){
            var reply = confirm('Are you sure you want to edit this record?');
            if(!reply){
                e.preventDefault();
            }
        });
        $(document).on('click','.updateaction',function(e){
            var reply = confirm('Are you sure you want to update this record?');
            if(!reply){
                e.preventDefault();
            }
        });
        $(document).on('click','.rejectaction',function(e){
            var reply = confirm('Are you sure you want to reject this application?');
            if(!reply){
                e.preventDefault();
            }else{
                if($("#remarks").length){
                    e.preventDefault();
                    var remarks = $("#remarks").val();
                    var href = $(this).attr('href');
                    href+="?remarks="+remarks;
                    window.location.href=href;
                }
            }
        });
        $(document).on('click','.deleteaction',function(e){
            var curElement = $(this);
            var reply = confirm('Are you sure you want to delete this record?');
            if(!reply){
                e.preventDefault();
            }else{
                if(curElement.hasClass('deleteitem')){
                    var id = curElement.data('id');
                    var url = $('input[name="URL"]').val();
                    $.post(url+'/master/deleteitem',{id:id},function(data){
                        if(data == 1){
                            alert('Item has been successfully deleted!');
                            curElement.parents('tr').remove();
                        }else{
                            alert('This record cannot be deleted as it is being used somewhere!');
                        }

                    });
                }
            }
        });
        $(document).on('click','.delete-websiteentry',function(){
            var curElement = $(this);
            var reply = confirm("Are you sure you want to delete this record?");
            if(reply){
                var table = curElement.parents('table').data('table');
                var id = curElement.data('id');
                var url = $('input[name="URL"]').val();
                $.post(url+'/master/deletefromdb',{table:table,id:id},function(data){
                    if(data == 1){
                        alert('Item has been successfully deleted!');
                        curElement.parents('tr').remove();
                    }else{
                        alert('This record cannot be deleted as it is being used somewhere!');
                    }
                });
            }
        });
        $(document).on('click','.processaction',function(e){
            var reply = confirm('Are you sure you want to process this record?');
            if(!reply){
                e.preventDefault();
            }
        });
        $(document).on('click','.pickaction',function(e){
            var reply = confirm('Are you sure you want to pick this record?');
            if(!reply){
                e.preventDefault();
            }
        });
        $(document).on('click','.dropaction',function(e){
            var reply = confirm('Are you sure you want to drop this record?');
            if(!reply){
                e.preventDefault();
            }
        });
        $(document).on('click','.deletebid',function(e){
            var reply = confirm("Are you sure you want to delete this bid?");
            if(!reply){
                e.preventDefault();
            }else{
                var id = $(this).data('id');
                var url = $('input[name="FullURL"]').val();
                var curRow = $(this).parents('tr');
                $.post(url+"/all/deletebid",{id:id},function(){
                    alert("Bid has been successfully deleted");
                    curRow.remove();
                });
            }
        });

        /*---------------------------------------datepicker function---------------------------------------------------------------*/
        $(document).on('click','.addtablerow',function (e){
            var currentElement = $(this);
            var table=currentElement.closest('table').attr('id');
            if(!currentElement.hasClass('disabled-control')){
                addNewRow(table);
                initializeAutocomplete();
            }
        });
        $(document).on('click','.deletetablerow',function(e){
            var curForm = $(this).parents('form');
            var currentElement = $(this);
            e.preventDefault();
            if(!currentElement.hasClass('disabled-control')){
                var reply = confirm('Are you sure you want to delete this row?');
                if(reply){
                    var thisrow=$(this);
                    var table=thisrow.closest('table').attr('id');
                    var rowCount = $('#'+table+' >tbody >tr:not(.notcountforclone)').length;
                    for(i=0;i<=rowCount;i++){
                        if(rowCount<=2){
                            $('#modalmessageheader').html("<strong>Warning !</strong>");
                            $('#modaldimessagetext').html("<strong>Sorry you cannot delete all the rows.</strong>");
                            $('#modalmessagebox').modal('show');
                            return false;
                        }else{
                            /*Added by Sangay Wangdi on 23rd June*/
                            if(i == 0){
                                if($('#'+table+' .EtlEqEquipmentId').length > 0){
                                    var curRow = thisrow.parents('tr');
                                    var tierId = curRow.find('.EtlEqTierId').val();
                                    var option = curRow.find('.EtlEqEquipmentId option:selected').data('criteriaeqid');
                                    var dataLabel = "criteriaeqid";
                                    etool.AddOptionOfDeletedRow(tierId,option,table,dataLabel);
                                }
                                if($('#'+table+' .EtlHrDesignationId').length > 0){
                                    var curRow = thisrow.parents('tr');
                                    var tierId = curRow.find('.EtlHrTierId').val();
                                    var option = curRow.find('.EtlHrDesignationId option:selected').data('criteriahrid');
                                    var dataLabel = "criteriahrid";
                                    etool.AddOptionOfDeletedRow(tierId,option,table,dataLabel);
                                }
                                if(thisrow.hasClass('deleterowfromdb')){
                                    var curRow = $(this).parents('tr');
                                    var id = curRow.find('.row-id').val();
                                    var table = curRow.find('.row-id').data('table');
                                    curTableDeletion = curRow.parents('table');
                                    var baseUrl = $('input[name="URL"]').val();
                                    if(id){
                                        $.post(baseUrl+'/etl/deleteevaldetail',{id: id, table: table},function(data){
                                            if(curTableDeletion.attr('id') == 'addcontractor-hr'){
                                                etool.CalculateTotal(curTableDeletion);
                                            }else{
                                                etool.CalculateTotal2(curTableDeletion);
                                            }

                                            //
                                        });
                                    }/*else{
                                     if(typeof('etool')!='undefined'){
                                     curTableDeletion = curRow.parents('table');
                                     if(curTableDeletion.attr('id') == 'addcontractor-hr'){
                                     etool.CalculateTotal(curTableDeletion);
                                     }else{
                                     etool.CalculateTotal2(curTableDeletion);
                                     }
                                     }
                                     }*/
                                }

                            }
                            /*END*/
                            var curTableDeletion = thisrow.closest('tr').parents('table');
                            thisrow.closest('tr').remove();
                            if(typeof(etool)!= 'undefined'){
                                if(curTableDeletion.attr('id') == 'addcontractor-hr'){
                                    etool.CalculateTotal(curTableDeletion);
                                }else{
                                    etool.CalculateTotal2(curTableDeletion);
                                }
                            }
                            if(submitted){
                                validate(curForm);
                            }
                        }
                    }
                    /* BY SWM */
                    if(typeof(etool) != 'undefined'){
                        $('#capacitytable_'+thisrow.parents('tr').find('.increment').val()).parents('div.to-clone').remove();
                        var count = 1;
                        $('#ContractorAdd tbody tr:not(:last)').each(function(){
                            $(this).find('.increment').val(count);
                            count++;
                        });
                        count = 1;
                        $('.contractor-no').each(function(){
                            var table = $(this).parents('.to-clone').find('table');
                            $(this).text(count);
                            table.find('.sequence').val(count);
                            //alert(table.attr('id').substr(0,table.attr('id').length-1) + count);
                            table.attr('id',table.attr('id').substr(0,table.attr('id').length-1) + count);
                            count++;
                        });
                        //calculateTotal($('#'+table));
                        var curTable = $('#'+table);
                        if(table == 'addcontractor-hr'){
                            etool.CalculateTotal(curTable);
                        }
                        if(table == 'addContractorEquipments'){
                            etool.CalculateTotal2(curTable);
                        }

                    }
                }
            }
            if($('.checkhrtr').length>0){
                $('button[type="submit"]').removeAttr('disabled');
            }


            /* END OF CODE BY SWM */
        });
        $('.workcompletionstatuscontrol').on('change',function(){
                var statusreferenceNo=$('option:selected',this).data('referenceno');
                if(statusreferenceNo==3003){
                    $('.workcompletedinfo').removeClass("hide");
                    $('.workcompletedinfo').addClass("show");
                    $('.workstatuscompletedcontrol').addClass('required');
                }else{
                    $('.workcompletedinfo').removeClass("show");
                    $('.workcompletedinfo').addClass("hide");
                    $('.workstatuscompletedcontrol').val("");
                    $('.workstatuscompletedcontrol').removeClass('required');
                }
            });
        $(document).on('keyup change','input, textarea',function(){
            var form = $(this).parents('form');
            if(submitted){
                validate(form);
            }
        });
        $(document).on('change','select',function(){
            var form = $(this).parents('form');
            if(submitted){
                validate(form);
            }
        });
        $(document).on('change','.monthpicker',function(){
            var form = $(this).parents('form');
            if(submitted){
                validate(form);
            }
        });
        $(document).on('click','button[type="submit"], input[type="submit"]',function(e){
            var curButton = $(this);
            var curTab = curButton.parents('.tab-pane');
            submitted = true;
            var flag = false;
            var form = curButton.parents('form');
            var valid = validate(form);
            var isServiceApplication = form.find($('#is-service-application')).length;
            var isTenderDownload = form.find($('#downloadtenderdocuments')).length;

            if(!valid){
                return false;
            }else{
                 /* Added By Sangay Wangdi */
                if(typeof(etool) != "undefined"){
                    var pointsValid = etool.CheckPoints();
                    if(curButton.attr('id') == 'saveAddContractor'){
                        var total100Valid = true;
                    }else{
                        var total100Valid = etool.CheckHundredPercent();
                    }
                    if(pointsValid && total100Valid){
                        curButton.attr('disabled','disabled');
                        curButton.parents('form').submit();
                        return true;
                    }else{
                        return false;
                    }
                    //return false;
                }

                if($('.checkhrtr').length>0){
                    var hrCidArray = [];
                    var cidUniqueValid = true;
                    $('.checkhrtr').each(function(){
                        var cid = $(this).val();
                        cid = cid.toString();
                        if(cid){
                            if(hrCidArray.indexOf(cid.trim())>-1){
                                cidUniqueValid = false;
                            }else{
                                hrCidArray.push(cid.trim());
                            }

                        }
                    });
                    if(cidUniqueValid){
                        if(curButton.attr('id') == "submit-application"){
                            if($("#agreement-checkbox").is(":checked")){
                                curButton.attr('disabled','disabled');
                                curButton.parents('form').submit();
                                return true;
                            }else{
                                e.preventDefault();
                                $("#modalmessageheader").text("Error!");
                                $("#modaldimessagetext").text("Please agree with the terms and conditions in order to continue.");
                                $("#modalmessagebox").modal("show");
                            }
                        }else{
                            if(isServiceApplication == 0 && isTenderDownload == 0){
                                curButton.attr('disabled','disabled');
                                curButton.parents('form').submit();
                            }

                            return true;
                        }

                    }else{
                        alert('You have entered the same HR personnel twice! Please correct this!');
                        return false;
                    }
                }
                /* End of code by Sangay Wangdi */
                if(curButton.attr('id') == "submit-application"){
                    if($("#agreement-checkbox").is(":checked")){
                        curButton.attr('disabled','disabled');
                        curButton.parents('form').submit();
                        return true;
                    }else{
                        e.preventDefault();
                        $("#modalmessageheader").text("Error!");
                        $("#modaldimessagetext").text("Please agree with the terms and conditions in order to continue.");
                        $("#modalmessagebox").modal("show");
                    }
                }else{
                    if(isServiceApplication == 0 && isTenderDownload == 0){
                        curButton.attr('disabled','disabled');
                        curButton.parents('form').submit();
                    }
                    return true;
                }
            }
        });
        $('#reselectchangeownerlocation').on('click',function(){
            $('input[type="text"],select').removeAttr('readonly');
            $('#generalinfoservice').modal('show');
        });
        $("#reselectchangeownerlocation").on('click',function(){
            $('.changeoffirmnameattachmentcontrol').find('input[type="file"]').removeClass('required').removeClass('error').parent().find('.required-message').remove();
            $('.changeoffirmnameattachmentcontrol').addClass('hide');
        });
        $(document).on('click','#changeoflocationowner',function(){
            var flag=false;
            var type1;
            var type2;
            var type3;
            var type4;
            var curModal=$(this).closest('.modal-content');
            var curForm = $(this).parents('form');
            var oldOwnershipTypeRef = $("select.companyownershiptype option:selected").data('reference');
            curModal.find('input[type="checkbox"]').each(function(){
                if($(this).is(':checked')){
                    flag=true;
                    if($(this).data('type')==1){
                        type1=true;
                    }else if($(this).data('type')==2){
                        type2=true;
                    }else if($(this).data('type') == 3){
                        type3=true;
                    }else if($(this).data('type') == 4){
                        type4=true;
                    }
                }
            });


            if(!flag){
                var renewal = $('input[name="RenewalService"]').length;
                if(renewal == 0){
                    $('.locationownererror').removeClass('hide');
                }
                $('.changeoffirmnameattachmentcontrol').find('input[type="file"]').removeClass('required');
            }else{
                $('.locationownererror').addClass('hide');
                $('#generalinfoservice').modal('hide');
                if(type1 && type2 && type3 && type4){
                    $('#changeofownershiptable').find('input[type!="file"]').addClass('required');
                    $('#changeoffirmnametable').find('input').addClass('required');
                    $('.changeofownerattachmentcontrol').removeClass('hide');
                    $('.companyownershiptypeattachmentcontrol').removeClass('hide');
                    $('.changeoffirmnameattachmentcontrol').removeClass('hide');
                    $('input:not(.changeofowner):not(.changeoflocation):not(.dontdisable):not(.changeoffirmname), select:not(.changeofowner):not(.changeoflocation):not(.dontdisable)').attr('readonly','readonly');
                    $('#ownerpartnerdetails').find('input, select').removeAttr('disabled').removeAttr('readonly');
                    $('#ownerpartnerdetails').find('input[type="checkbox"]').parents('div.checker').removeClass('disabled');
                    $(".companyownershiptype option").removeAttr("selected"); $(".companyownershiptype option[data-reference='14002']").attr('selected','selected');  $('.companyownershiptype').find('.select2-chosen').text($(".companyownershiptype option[data-reference='14002']").text());
                }else{
                    if((type1 && type2 && type3) || (type1 && type3 && type4) || (type2 && type3 && type4) || (type1 && type2 && type4)){
                        if((type1 && type2 && type3)){
                            $('#changeofownershiptable').find('input[type!="file"]').addClass('required');
                            $('#changeoffirmnametable').find('input').addClass('required');
                            $('.changeofownerattachmentcontrol').removeClass('hide');
                            $('.companyownershiptypeattachmentcontrol').addClass('hide');
                            $("#certificateofincorporation").find('input[type="file"]').removeClass('required');
                            $('.changeoffirmnameattachmentcontrol').removeClass('hide');
                            $('input:not(.changeofowner):not(.changeoflocation):not(.dontdisable):not(.changeoffirmname), select:not(.changeofowner):not(.changeoflocation):not(.dontdisable)').attr('readonly','readonly');
                            $('#ownerpartnerdetails').find('input, select').removeAttr('disabled');
                            $('#ownerpartnerdetails').find('input[type="checkbox"]').parents('div.checker').removeClass('disabled');
                            $('.companyownershiptype option').each(function(){
                                if($(this).val() != ''){
                                    if($(this).data('reference') == 14002 || $(this).data('reference') == 14003 || $(this).data('reference') == 14004){
                                        if(oldOwnershipTypeRef != $(this).data('reference')){
                                            $(this).attr('disabled','disabled');
                                        }
                                    }else{
                                        $(this).removeAttr('disabled');
                                    }
                                }
                            });
                        }
                        if((type1 && type3 && type4)){
                            $('#changeofownershiptable').find('input[type!="file"]').removeClass('required');
                            $('#changeoffirmnametable').find('input').removeClass('required');
                            $('.changeofownerattachmentcontrol').addClass('hide');
                            //$('.companyownershiptypeattachmentcontrol').removeClass('hide');
                            $('.changeoffirmnameattachmentcontrol').removeClass('hide');
                            $('input:not(.changeoflocation):not(.dontdisable):not(.changeoffirmname), select:not(.changeoflocation):not(.dontdisable)').attr('readonly','readonly');
                            $('#ownerpartnerdetails').find('input, select').removeAttr('disabled').removeAttr('readonly');
                            $('#ownerpartnerdetails').find('input[type="checkbox"]').parents('div.checker').removeClass('disabled');
                            if(oldOwnershipTypeRef == 14001){
                                $('.companyownershiptype').removeAttr('readonly').val('');
                                $('.companyownershiptype').find('.select2-chosen').text('---SELECT ONE---');
                                $('.companyownershiptypeattachmentcontrol').addClass('hide');
                                $("#certificateofincorporation").find('input[type="file"]').removeClass('required');
                            }else{
                                $('.companyownershiptypeattachmentcontrol').removeClass('hide');
                                $("#certificateofincorporation").find('input[type="file"]').addClass('required');
                            }
                            $('.companyownershiptype option').each(function(){
                                if($(this).val() != ''){
                                    if($(this).data('reference') == 14002 || $(this).data('reference') == 14003 || $(this).data('reference') == 14004){
                                        $(this).removeAttr('disabled');
                                    }else{
                                        $(this).attr('disabled','disabled');
                                    }
                                }
                            });
                            $(".companyownershiptype option").removeAttr("selected"); $(".companyownershiptype option[data-reference='14002']").attr('selected','selected');  $('.companyownershiptype').find('.select2-chosen').text($(".companyownershiptype option[data-reference='14002']").text());
                            $('input.changeofowner, select.changeofowner').removeAttr('disabled');
                            $('input.changeoffirmname').removeAttr('readonly');
                        }
                        if((type2 && type3 && type4)){
                            $('#changeofownershiptable').find('input[type!="file"]').removeClass('required');
                            $('#changeoffirmnametable').find('input').removeClass('required');
                            $('.changeofownerattachmentcontrol').removeClass('hide');
                            //$('.companyownershiptypeattachmentcontrol').removeClass('hide');
                            $('.changeoffirmnameattachmentcontrol').removeClass('hide');
                            $('input:not(.changeofowner):not(.dontdisable):not(.changeoffirmname), select:not(.changeofowner):not(.changeoffirmname):not(.dontdisable)').attr('readonly','readonly');
                            $('#ownerpartnerdetails').find('input, select').removeAttr('disabled').removeAttr('readonly');
                            $('#ownerpartnerdetails').find('input[type="checkbox"]').parents('div.checker').removeClass('disabled');
                            if(oldOwnershipTypeRef == 14001){
                                $('.companyownershiptype').removeAttr('readonly').val('');
                                $('.companyownershiptype').find('.select2-chosen').text('---SELECT ONE---');
                                $('.companyownershiptypeattachmentcontrol').addClass('hide');
                                $("#certificateofincorporation").find('input[type="file"]').removeClass('required');
                            }else{
                                $('.companyownershiptypeattachmentcontrol').removeClass('hide');
                                $("#certificateofincorporation").find('input[type="file"]').addClass('required');
                            }
                            $('.companyownershiptype option').each(function(){
                                if($(this).val() != ''){
                                    if($(this).data('reference') == 14002 || $(this).data('reference') == 14003 || $(this).data('reference') == 14004){
                                        $(this).removeAttr('disabled');
                                    }else{
                                        $(this).attr('disabled','disabled');
                                    }
                                }
                            });
                            $(".companyownershiptype option").removeAttr("selected"); $(".companyownershiptype option[data-reference='14002']").attr('selected','selected');  $('.companyownershiptype').find('.select2-chosen').text($(".companyownershiptype option[data-reference='14002']").text());
                            $('input.changeofowner, select.changeofowner').removeAttr('disabled');
                            $('input.changeoffirmname').removeAttr('readonly');
                        }
                        if((type1 && type2 && type4)){
                            $('#changeofownershiptable').find('input[type!="file"]').removeClass('required');
                            $('#changeoffirmnametable').find('input').addClass('required');
                            $('.changeofownerattachmentcontrol').removeClass('hide');
                            //$('.companyownershiptypeattachmentcontrol').removeClass('hide');
                            $('.changeoffirmnameattachmentcontrol').addClass('hide');
                            $('input:not(.changeofowner):not(.dontdisable):not(.changeoflocation), select:not(.changeofowner):not(.changeoflocation):not(.dontdisable)').attr('readonly','readonly');
                            $('#ownerpartnerdetails').find('input, select').removeAttr('disabled').removeAttr('readonly');
                            $('#ownerpartnerdetails').find('input[type="checkbox"]').parents('div.checker').removeClass('disabled');
                            if(oldOwnershipTypeRef == 14001){
                                $('.companyownershiptype').removeAttr('readonly').val('');
                                $('.companyownershiptype').find('.select2-chosen').text('---SELECT ONE---');
                                $('.companyownershiptypeattachmentcontrol').addClass('hide');
                                $("#certificateofincorporation").find('input[type="file"]').removeClass('required');
                            }else{
                                $('.companyownershiptypeattachmentcontrol').removeClass('hide');
                                $("#certificateofincorporation").find('input[type="file"]').addClass('required');
                            }
                            $('.companyownershiptype option').each(function(){
                                if($(this).val() != ''){
                                    if($(this).data('reference') == 14002 || $(this).data('reference') == 14003 || $(this).data('reference') == 14004){
                                        $(this).removeAttr('disabled');
                                    }else{
                                        $(this).attr('disabled','disabled');
                                    }
                                }
                            });
                            $(".companyownershiptype option").removeAttr("selected"); $(".companyownershiptype option[data-reference='14002']").attr('selected','selected');  $('.companyownershiptype').find('.select2-chosen').text($(".companyownershiptype option[data-reference='14002']").text());

                            $('input.changeoffirmname').removeAttr('readonly');
                        }
                    }else{
                        if((type1 && type2) || (type1 && type3) || (type2 && type3) || (type1 && type4) || (type2 && type4) || (type3 && type4)){
                            if(type1 && type2){
                                $('#changeofownershiptable').find('input[type!="file"]').addClass('required');
                                $('.changeofownerattachmentcontrol').removeClass('hide');
                                $('.companyownershiptypeattachmentcontrol').addClass('hide');
                                $("#certificateofincorporation").find('input[type="file"]').removeClass('required');
                                $('input:not(.changeofowner):not(.changeoflocation):not(.dontdisable), select:not(.changeofowner):not(.changeoflocation):not(.dontdisable)').attr('readonly','readonly');
                                $('#ownerpartnerdetails').find('input, select').removeAttr('disabled');
                                $('#ownerpartnerdetails').find('input[type="checkbox"]').parents('div.checker').removeClass('disabled');
                                $('.companyownershiptype option').each(function(){
                                    if($(this).val() != ''){
                                        if($(this).data('reference') == 14002 || $(this).data('reference') == 14003 || $(this).data('reference') == 14004){
                                            //$(this).attr('disabled','disabled');
                                            if(oldOwnershipTypeRef != $(this).data('reference')){
                                                $(this).attr('disabled','disabled');
                                            }
                                        }else{
                                            $(this).removeAttr('disabled');
                                        }
                                    }
                                });
                            }
                            if(type1 && type3){
                                $('.changeoffirmnameattachmentcontrol').removeClass('hide');
                                $('#changeoffirmnametable').find('input').addClass('required');
                                $('#changeofownershiptable').find('input[type!="file"]').removeClass('required');
                                $('.changeofownerattachmentcontrol').addClass('hide');
                                $('.companyownershiptypeattachmentcontrol').addClass('hide');
                                $("#certificateofincorporation").find('input[type="file"]').removeClass('required');
                                $('input:not(.changeoffirmname):not(.changeoflocation):not(.dontdisable), select:not(.changeoffirmname):not(.changeoflocation):not(.dontdisable)').attr('readonly','readonly');
                                $('#ownerpartnerdetails').find('input, select').attr('disabled','disabled');
                                $('#ownerpartnerdetails').find('input[type="checkbox"]').parents('div.checker').addClass('disabled');
                                $('#propcumengineerwrapper').addClass('hide');
                                $('#propcumengineerwrapper').find('input[type="file"]').removeClass('required');
                            }
                            if(type2 && type3){
                                $('#changeofownershiptable').find('input[type!="file"]').addClass('required'); //Change of owner
                                $('.changeofownerattachmentcontrol').removeClass('hide'); //Change of owner
                                $('.companyownershiptypeattachmentcontrol').addClass('hide'); //Change of owner
                                $("#certificateofincorporation").find('input[type="file"]').removeClass('required');
                                $('.changeoffirmnameattachmentcontrol').removeClass('hide'); //Change of firm name
                                $('#changeoffirmnametable').find('input').addClass('required'); //Change of firm name
                                $('input:not(.changeoffirmname):not(.changeofowner):not(.dontdisable), select:not(.changeoffirmname):not(.changeofowner):not(.dontdisable)').attr('readonly','readonly');
                                $('#ownerpartnerdetails').find('input, select').removeAttr('disabled');
                                $('#ownerpartnerdetails').find('input[type="checkbox"]').parents('div.checker').removeClass('disabled');
                                $('.companyownershiptype option').each(function(){
                                    if($(this).val() != ''){
                                        if($(this).data('reference') == 14002 || $(this).data('reference') == 14003 || $(this).data('reference') == 14004){
                                            if(oldOwnershipTypeRef != $(this).data('reference')){
                                                $(this).attr('disabled','disabled');
                                            }
                                        }else{
                                            $(this).removeAttr('disabled');
                                        }
                                    }
                                });
                            }
                            if((type1 && type4)){
                                $('#changeofownershiptable').find('input[type!="file"]').removeClass('required'); //Change of owner
                                $('.changeofownerattachmentcontrol').addClass('hide'); //Change of owner
                                //$('.companyownershiptypeattachmentcontrol').removeClass('hide'); //Change of owner
                                $('.changeoffirmnameattachmentcontrol').addClass('hide'); //Change of firm name
                                $('#changeoffirmnametable').find('input').removeClass('required'); //Change of firm name
                                $('input:not(.changeoflocation):not(.dontdisable), select:not(.changeoflocation):not(.dontdisable)').attr('readonly','readonly');

                                //$('#ownerpartnerdetails').find('input, select').attr('disabled','disabled');
                                $('#ownerpartnerdetails').find('input[type="checkbox"]').parents('div.checker').addClass('disabled');
                                if(oldOwnershipTypeRef == 14001){
                                    $('.companyownershiptype').removeAttr('readonly').val('');
                                    $('.companyownershiptype').find('.select2-chosen').text('---SELECT ONE---');
                                    $('.companyownershiptypeattachmentcontrol').addClass('hide');
                                    $("#certificateofincorporation").find('input[type="file"]').removeClass('required');
                                }else{
                                    $('.companyownershiptypeattachmentcontrol').removeClass('hide');
                                    $("#certificateofincorporation").find('input[type="file"]').addClass('required');
                                }
                                $('.companyownershiptype option').each(function(){
                                    if($(this).val() != ''){
                                        if($(this).data('reference') == 14002 || $(this).data('reference') == 14003 || $(this).data('reference') == 14004){
                                            $(this).removeAttr('disabled');
                                        }else{
                                            $(this).attr('disabled','disabled');
                                        }
                                    }
                                });
                                $(".companyownershiptype option").removeAttr("selected"); $(".companyownershiptype option[data-reference='14002']").attr('selected','selected');  $('.companyownershiptype').find('.select2-chosen').text($(".companyownershiptype option[data-reference='14002']").text());
                                $('input.changeofowner, select.changeofowner').removeAttr('readonly');
                                $('input.changeofowner, select.changeofowner').removeAttr('disabled');
                                $('input[type="checkbox"].changeofowner').parents('div.checker').removeClass('disabled');
                                $('input.changeoffirmname').removeAttr('readonly');
                            }
                            if((type2 && type4)){
                                $('#changeofownershiptable').find('input[type!="file"]').addClass('required'); //Change of owner
                                $('.changeofownerattachmentcontrol').removeClass('hide'); //Change of owner
                                //$('.companyownershiptypeattachmentcontrol').removeClass('hide'); //Change of owner
                                $('.changeoffirmnameattachmentcontrol').addClass('hide'); //Change of firm name
                                $('#changeoffirmnametable').find('input').removeClass('required'); //Change of firm name
                                $('input:not(.changeofowner):not(.dontdisable), select:not(.changeofowner):not(.dontdisable)').attr('readonly','readonly');

                                //$('#ownerpartnerdetails').find('input, select').removeAttr('disabled');
                                $('#ownerpartnerdetails').find('input[type="checkbox"]').parents('div.checker').removeClass('disabled');
                                if(oldOwnershipTypeRef == 14001){
                                    $('.companyownershiptype').removeAttr('readonly').val('');
                                    $('.companyownershiptype').find('.select2-chosen').text('---SELECT ONE---');
                                    $('.companyownershiptypeattachmentcontrol').addClass('hide');
                                    $("#certificateofincorporation").find('input[type="file"]').removeClass('required');
                                }else{
                                    $('.companyownershiptypeattachmentcontrol').removeClass('hide');
                                    $("#certificateofincorporation").find('input[type="file"]').addClass('required');
                                }
                                $('.companyownershiptype option').each(function(){
                                    if($(this).val() != ''){
                                        if($(this).data('reference') == 14002 || $(this).data('reference') == 14003 || $(this).data('reference') == 14004){
                                            $(this).removeAttr('disabled');
                                        }else{
                                            $(this).attr('disabled','disabled');
                                        }
                                    }
                                });
                                $(".companyownershiptype option").removeAttr("selected"); $(".companyownershiptype option[data-reference='14002']").attr('selected','selected');  $('.companyownershiptype').find('.select2-chosen').text($(".companyownershiptype option[data-reference='14002']").text());
                                $('input.changeofowner, select.changeofowner').removeAttr('readonly');
                                $('input.changeofowner, select.changeofowner').removeAttr('disabled');
                                $('input[type="checkbox"].changeofowner').parents('div.checker').removeClass('disabled');
                                $('input.changeoffirmname').removeAttr('readonly');
                            }
                            if((type3 && type4)){
                                $('#changeofownershiptable').find('input[type!="file"]').removeClass('required'); //Change of owner
                                $('.changeofownerattachmentcontrol').addClass('hide'); //Change of owner
                                //$('.companyownershiptypeattachmentcontrol').removeClass('hide'); //Change of owner
                                $('.changeoffirmnameattachmentcontrol').removeClass('hide'); //Change of firm name
                                $('#changeoffirmnametable').find('input').addClass('required'); //Change of firm name
                                $('input:not(.changeoffirmname):not(.dontdisable), select:not(.changeoffirmname):not(.dontdisable)').attr('readonly','readonly');

                                //$('#ownerpartnerdetails').find('input, select').attr('disabled','disabled');
                                $('#ownerpartnerdetails').find('input[type="checkbox"]').parents('div.checker').addClass('disabled');
                                if(oldOwnershipTypeRef == 14001){
                                    $('.companyownershiptype').removeAttr('readonly').val('');
                                    $('.companyownershiptype').find('.select2-chosen').text('---SELECT ONE---');
                                    $('.companyownershiptypeattachmentcontrol').addClass('hide');
                                    $("#certificateofincorporation").find('input[type="file"]').removeClass('required');
                                }else{
                                    $('.companyownershiptypeattachmentcontrol').removeClass('hide');
                                    $("#certificateofincorporation").find('input[type="file"]').addClass('required');
                                }
                                $('.companyownershiptype option').each(function(){
                                    if($(this).val() != ''){
                                        if($(this).data('reference') == 14002 || $(this).data('reference') == 14003 || $(this).data('reference') == 14004){
                                            $(this).removeAttr('disabled');
                                        }else{
                                            $(this).attr('disabled','disabled');
                                        }
                                    }
                                });
                                $(".companyownershiptype option").removeAttr("selected"); $(".companyownershiptype option[data-reference='14002']").attr('selected','selected');  $('.companyownershiptype').find('.select2-chosen').text($(".companyownershiptype option[data-reference='14002']").text());
                                $('input.changeofowner, select.changeofowner').removeAttr('readonly');
                                $('input.changeofowner, select.changeofowner').removeAttr('disabled');
                                $('input[type="checkbox"].changeofowner').parents('div.checker').removeClass('disabled');
                                $('input.changeoffirmname').removeAttr('readonly');
                            }
                        }else{
                            if(type1){
                                $('input:not(.changeoflocation):not(.dontdisable), select:not(.changeoflocation):not(.dontdisable)').attr('readonly','readonly');
                                $('.companyownershiptypeattachmentcontrol').addClass('hide');
                                $("#certificateofincorporation").find('input[type="file"]').removeClass('required');
                                $('.changeofownerattachmentcontrol').addClass('hide');
                                $('#changeofownershiptable').find('input[type!="file"]').removeClass('required');
                                $('.changeoffirmnameattachmentcontrol').addClass('hide');
                                $('#changeoffirmnametable').find('input').removeClass('required');
                                $('#ownerpartnerdetails').find('input, select').attr('disabled','disabled');
                                $('#ownerpartnerdetails').find('input[type="checkbox"]').parents('div.checker').addClass('disabled');
                                $('#propcumengineerwrapper').addClass('hide');
                                $('#propcumengineerwrapper').find('input[type="file"]').removeClass('required');
                            }
                            if(type2){
                                $('#changeofownershiptable').find('input[type!="file"]').addClass('required');
                                $('.changeofownerattachmentcontrol').removeClass('hide');
                                $('.companyownershiptypeattachmentcontrol').addClass('hide');
                                $("#certificateofincorporation").find('input[type="file"]').removeClass('required');
                                $('.changeoffirmnameattachmentcontrol').addClass('hide');
                                $('#changeoffirmnametable').find('input').removeClass('required');
                                $('input:not(.changeofowner):not(.dontdisable), select:not(.changeofowner):not(.dontdisable)').attr('readonly','readonly');
                                $('#ownerpartnerdetails').find('input, select').removeAttr('disabled');
                                $('#ownerpartnerdetails').find('input[type="checkbox"]').parents('div.checker').removeClass('disabled');
                                $('.companyownershiptype option').each(function(){
                                    if($(this).val() != ''){
                                        if($(this).data('reference') == 14002 || $(this).data('reference') == 14003 || $(this).data('reference') == 14004){
                                            if(oldOwnershipTypeRef != $(this).data('reference')){
                                                $(this).attr('disabled','disabled');
                                            }
                                        }else{
                                            $(this).removeAttr('disabled');
                                        }
                                    }
                                });
                                $(".companyownershiptype option").removeAttr("selected"); $(".companyownershiptype option[data-reference='14002']").attr('selected','selected');  $('.companyownershiptype').find('.select2-chosen').text($(".companyownershiptype option[data-reference='14002']").text());
                            }
                            if(type3){
                                $('#changeofownershiptable').find('input[type!="file"]').removeClass('required');
                                $('.changeofownerattachmentcontrol').addClass('hide');
                                $('.companyownershiptypeattachmentcontrol').addClass('hide');
                                $('.changeoffirmnameattachmentcontrol').removeClass('hide');
                                $("#certificateofincorporation").find('input[type="file"]').removeClass('required');
                                $('#changeoffirmnametable').find('input').addClass('required');
                                $('input:not(.changeoffirmname):not(.dontdisable), select:not(.changeoffirmname):not(.dontdisable)').attr('readonly','readonly');
                                $('#ownerpartnerdetails').find('input, select').attr('disabled','disabled');
                                $('#ownerpartnerdetails').find('input[type="checkbox"]').parents('div.checker').addClass('disabled');
                                $('#propcumengineerwrapper').addClass('hide');
                                $('#propcumengineerwrapper').find('input[type="file"]').removeClass('required');
                            }
                            if(type4){
                                $('#changeofownershiptable').find('input[type!="file"]').addClass('required');
                                $('.changeofownerattachmentcontrol').addClass('hide');
                                //$('.companyownershiptypeattachmentcontrol').addClass('hide');
                                $('.changeoffirmnameattachmentcontrol').addClass('hide');
                                $('#changeoffirmnametable').find('input').removeClass('required');
                                $('input:not(.dontdisable), select:not(.dontdisable)').attr('readonly','readonly');
                                //$('#ownerpartnerdetails').find('input, select').attr('disabled','disabled');
                                $('#ownerpartnerdetails').find('input[type="checkbox"]').parents('div.checker').addClass('disabled');
                                if(oldOwnershipTypeRef == 14001){
                                    $('.companyownershiptype').removeAttr('readonly').val('');
                                    $('.companyownershiptype').find('.select2-chosen').text('---SELECT ONE---');
                                    $('.companyownershiptypeattachmentcontrol').addClass('hide');
                                    $("#certificateofincorporation").find('input[type="file"]').removeClass('required');
                                }else{
                                    $('.companyownershiptypeattachmentcontrol').removeClass('hide');
                                    $("#certificateofincorporation").find('input[type="file"]').addClass('required');
                                }
                                $('.companyownershiptype option').each(function(){
                                    if($(this).val() != ''){
                                        if($(this).data('reference') == 14002 || $(this).data('reference') == 14003 || $(this).data('reference') == 14004){
                                            $(this).removeAttr('disabled');
                                        }else{
                                            $(this).attr('disabled','disabled');
                                        }
                                    }
                                });
                                $(".companyownershiptype option").removeAttr("selected"); $(".companyownershiptype option[data-reference='14002']").attr('selected','selected');  $('.companyownershiptype').find('.select2-chosen').text($(".companyownershiptype option[data-reference='14002']").text());
                                $('input.changeofowner, select.changeofowner').removeAttr('readonly');
                                $('input.changeofowner, select.changeofowner').removeAttr('disabled');
                                $('input[type="checkbox"].changeofowner').parents('div.checker').removeClass('disabled');
                                $('input.changeoffirmname').removeAttr('readonly');
                            }
                        }
                    }

                }
                if(type1){
                    $("#reasonforlocationchange").find("textarea").addClass('required');
                }else{
                    $("#reasonforlocationchange").find("textarea").removeClass('required');
                }
                validate($("#changeoflocationaddressserviceform"));
            }
        });
        $('#reselectchangeofcategoryclassification').on('click',function(){
            $('#workclassificationservice').modal('show');
            $('input.tablerowcheckbox').attr('disabled','disabled');
        });
        $('#changeofcategoryclassification').on('click',function(){
            var flag=false;
            var type1;
            var curModal=$(this).closest('.modal-content');
            curModal.find('input[type="checkbox"]').each(function(){
                if($(this).is(':checked')){
                    flag=true;
                    if($(this).data('type')==1){
                        type1=true;
                    }
                }
            });
            if(!flag){
                $('.changeofcategoryclasserror').removeClass('hide');
            }else{
                $('.changeofcategoryclasserror').addClass('hide');
                $('#workclassificationservice').modal('hide');
                if(type1){
                    $('input.tablerowcheckbox').removeAttr('disabled');
                    $('div.checker').removeClass('disabled');
                }
            }
        });

        $('#reselecthumanresourceservice').on('click',function(){
            $('.addhumanresourcebutton').addClass('hide');
            $('#humanresourceservice').modal('show');
        });
        $('#updatehumanresourceservice').on('click',function(){
            var flag=false;
            var curModal=$(this).closest('.modal-content');
            curModal.find('input[type="checkbox"]').each(function(){
                if($(this).is(':checked')){
                    flag=true;
                    if($(this).data('type')==1){
                        type1=true;
                    }
                }
            });
            if(!flag){
                $('.humanresourceserviceerror').removeClass('hide');
            }else{
                $('.humanresourceserviceerror').addClass('hide');
                $('#humanresourceservice').modal('hide');
                if(type1){
                    $('.addhumanresourcebutton').removeClass('hide');
                }
            }
        });
        $('#reselectequipmentservice').on('click',function(){
            $('.addequipmentbutton').addClass('hide');
            $('#equipmentservice').modal('show');
        });
        $('#updateequipmentservice').on('click',function(){
            var flag=false;
            var curModal=$(this).closest('.modal-content');
            curModal.find('input[type="checkbox"]').each(function(){
                if($(this).is(':checked')){
                    flag=true;
                    if($(this).data('type')==1){
                        type1=true;
                    }
                }
            });
            if(!flag){
                $('.equipmentserviceerror').removeClass('hide');
            }else{
                $('.equipmentserviceerror').addClass('hide');
                $('#equipmentservice').modal('hide');
                if(type1){
                    $('.addequipmentbutton').removeClass('hide');
                }
            }
        });
        $('.equipmentselectregisteredtype').on('change',function(){
            var curRegistrationType=parseInt($('option:selected',this).data('registered'));
            if(curRegistrationType==0){
                $('.isregisteredequipment').attr('disabled','disabled');
                $('.disableifregisteredequipment').removeAttr('disabled');
            }else{
                $('.isregisteredequipment').removeAttr('disabled');
                $('.disableifregisteredequipment').attr('disabled','disabled');
            }
        });
        $('.selectconsultantservice').on('click',function(){
            var curRow=$(this).closest('tr');
            var element=curRow.find('.setselectservicecontrol');
            var checkedCounter=0;
            if($(this).is(':checked')){
                if(element.is(':disabled')){
                    element.removeAttr('disabled');
                }
            }else{
                curRow.find('.selectconsultantservice').not($(this)).each(function(){
                    if($(this).is(':checked')){
                        checkedCounter+=1;
                    }
                });
                if(checkedCounter==0){
                    element.attr('disabled','disabled');
                }
            }
        });
        /*$(document).on('keyup','table#ContractorAdd .cdbno',function(){
            var curRow = $(this).parents('tr');
            var val = $(this).val();
            var dataVal;
            var id;
            var flag = false;
            curRow.find('select[name$="[CrpContractorFinalId]"] option').each(function(){
                if(!flag) {
                    dataVal = $(this).data('cdbno');
                    id = $(this).val();
                    if (val == dataVal) {
                        flag = true;
                        curRow.find('select[name$="[CrpContractorFinalId]"]').val(id);
                    } else {
                        curRow.find('select[name$="[CrpContractorFinalId]"]').val('');
                    }
                }
            });
        });*/
        $('.checkcdbno').on('blur',function(){
            var curInput=$(this);
            var url = $('input[name="URL"]').val();
            var urlCDBNoLink=$(this).parents().find('.cdbnocheckurl').val();
            var inputCDBNo=$(this).val();
            $.get(url+'/'+urlCDBNoLink,{inputCDBNo:inputCDBNo},function(data){
                if(data==0){
                    if(!curInput.hasClass('error')){
                        curInput.after("<span class='help-block error-span required-message'>CDB No. has been already taken.</span>");
                        curInput.addClass('error');
                        $('.form-actions').find('a').attr('disabled','disabled');
                    }
                }else{
                    curInput.removeClass('error');
                    curInput.parents('.form-group').find('span.help-block.required-message').remove();
                    $('.form-actions').find('a').removeAttr('disabled');
                }
            });
        });
        $('.removerowdbconfirmation').on('click',function(){
            var selectedRow = $(this).parents('tr');
            var selectedRowReference = selectedRow.find('.rowreference').val();
            var selectedRowModelReference = $('#addnesandnoticebox').find('.rowreferencemodel').val();
            $('#deleteconfirmation').find('.recordreference').val(selectedRowReference);
            $('#deleteconfirmation').find('.recordreferencemodel').val(selectedRowModelReference);
            $('#deleteconfirmation').modal("show");
        });
        $('.deleterowfromdb').on('click',function(){
            var selectedRow = $(this).parents('tr');
            deletedRow = selectedRow;
            var selectedRowReference = selectedRow.find('.rowreference').val();
            var selectedRowModelReference = selectedRow.parents('table').find('.delete-model').val();
            $('#deleteconfirmation').find('.recordreference').val(selectedRowReference);
            $('#deleteconfirmation').find('.recordreferencemodel').val(selectedRowModelReference);
            $('#deleteconfirmation').modal("show");
        });
        $('.deletedbrow').on('click',function(){
            var applicationId = 'xx';
            if($(this).hasClass('deleterequest')){
                applicationId = $("#application-id").val();
            }
            var curRow = $(this).parents('tr');
            var id = curRow.find('.rowreference').val();
            var model = curRow.parents('table').find('.delete-model').val();
            var url = $('input[name="URL"]').val();
            var reply = confirm('Do you want to delete this record?');
            if(reply){
                $.post(url+'/deletedbrow',{id:id,model:model,applicationId:applicationId},function(data){
                    if(data ==1){
                        curRow.remove();
                        alert('Record has been Deleted');
                    }else{
                        alert('There was a problem while deleting the record');
                    }
                });
            }
        });
        $('.removerecorddb').on('click',function(){
            var curInput=$(this);

            var reply = confirm("Do you really want to delete this record? This action is irreversible!");
            if(reply){
                deletedRow = curInput.parents('tr');
                var url = $('input[name="URL"]').val();
                var deleteReference=$(this).parents('tr').find('.recordreference').val();
                var deleteReferenceModel=$(this).parents('tr').find('.recordreferencemodel').val();
                $.post(url+'/actiondeleterecord',{deleteReference:deleteReference,deleteReferenceModel:deleteReferenceModel},function(data){
                    var hasdeleted=data["hasdeleted"];
                    if(hasdeleted==1){
                        deletedRow.remove();
                        alert(data['message']);
                    }else{
                        alert(data);
                    }
                });
            }

        });
        $('.companyownershiptype').on('change',function(){
            var curElement = $(this);
            var referenceIncorporatedType=parseInt($('option:selected',this).data('reference'));
            if(referenceIncorporatedType==14002 || referenceIncorporatedType==14003){
                $('.companyownershiptypeattachmentcontrol').removeClass('hide');
                $('table#certificateofincorporation').find("input").addClass('required');
            }else{
                $('.companyownershiptypeattachmentcontrol').addClass('hide');
                $('table#certificateofincorporation').find("input").removeClass('required');

            }
            var form = curElement.parents('form');
            validate(form);
        });
        $(document).on('blur','table#ContractorAdd .cdbno',function(){
            var cdbno = $(this).val();
            var curRow = $(this).parents('tr');
            var url = $('input[name="URL"]').val();
            if(curRow.find('.consultant-hidden').length > 0){
                var consultant = true;
                var ajaxUrl = '/consultant/fetchconsultantoncdbno';

            }else if(curRow.find('.contractor-hidden').length > 0){
                var contractor = true;
                var ajaxUrl = '/etl/fetchcontractoroncdbno';

            }else{
                var specializedtrade = false;
                var ajaxUrl = '/etl/fetchspecializedtradeoncdbno';
            }

	        var status = '';
            if(cdbno){
                $.ajax({
                    url: url+ajaxUrl,
                    type: 'post',
                    dataType: 'json',
                    data: {cdbno: cdbno},
                    success: function(data){
                        if(data.length > 0){
                            status = data[0].Status;
                            if(status == 1){
                                $('#modalmessageheader').html("<strong>Warning !</strong>");
                                if(consultant){
                                    $('#modaldimessagetext').load(url+'/consultant/blacklistedconsultant?cdbNo='+cdbno);
                                }else if(contractor){
                                    $('#modaldimessagetext').load(url+'/etl/blacklistedcontractor?cdbNo='+cdbno);
                                }else{
                                    $('#modaldimessagetext').load(url+'/etl/blacklistedspecializedtrade?cdbNo='+cdbno);
                                }

                                $('#modalmessagebox').modal('show');

                            }else{
                                if(consultant){
                                    curRow.find('.consultant-id').val(data[0].Id);
                                    curRow.find('.consultant-name').val(data[0].NameOfFirm);
                                }else if(contractor){
                                    curRow.find('.contractor-id').val(data[0].Id);
                                    curRow.find('.contractor-name').val(data[0].NameOfFirm);
                                }else{
                                    curRow.find('.specializedtrade-id').val(data[0].Id);
                                    curRow.find('.specializedtrade-name').val(data[0].NameOfFirm);
                            }
                        }
                        }else{
                            $('#modalmessageheader').html("<strong>Warning !</strong>");
                            $('#modaldimessagetext').html("<strong>The CDB No. you provided did not match our records. Please verify the CDB No. and try again.</strong>");
                            $('#modalmessagebox').modal('show');
                        }
                    }
                });
            }else{
                //$('#modalmessageheader').html("<strong>Warning !</strong>");
                //$('#modaldimessagetext').html("<strong>CDB No. must be a number.</strong>");
                //$('#modalmessagebox').modal('show');
            }
        });
        $('#downloadtenderdocuments').on('click',function(e){
            var tenderdownloademail = $('#tenderdownloademail').val();
            var tenderdownloadphone = $('#tenderdownloadphone').val();
            if(!tenderdownloademail && !tenderdownloadphone){
                e.preventDefault();
                $("#tendererrormessage").removeClass('hide');
            }else{
                $("#tendererrormessage").addClass('hide');
            }
        });

        $('.senddeleterequest').on('click',function(){
            var curElement = $(this);
            var type = curElement.data('type');
            var id = curElement.val();
            if(curElement.is(':checked')){
                var deleted = 1;
            }else{
                var deleted = 0;
            }
            $.ajax({
                url: $('input[name="URL"]').val()+'/senddeleterequest',
                type: 'post',
                dataType: 'json',
                data: {type: type, id: id, deleted: deleted},
                success: function(data){

                }
            });
        });
        $(document).on('click',"#waiver",function(){
            var textElement = $(this).parents('tr').find('input[type="text"]')
            if($(this).is(':checked')){
                textElement.removeAttr('disabled').addClass('required');
            }else{
                textElement.attr('disabled','disabled').removeClass('required');
                textElement.removeClass('error');
                textElement.parent().find('span.error-span').remove();
                textElement.val('');
            }
            var form = $(this).parents('form');
            validate(form);
        });
        if($("#header_notification_bar").length>0){
            var url = $("input[name='URL'").val();
            audioElement = document.createElement('audio');
            audioElement.setAttribute('src',url+"/assets/sounds/bell.mp3");
            refreshDashboard();

        }

        $("#secretariat-department").on('change',function(){
            if($(this).val()!=''){
                var value = $(this).val();
                var deptId;
                var optionValue;
                $("#secretariat-division option[value!='']").each(function(){
                    deptId = $(this).data('deptid');
                    optionValue = $(this).val();
                    if(deptId != value){
                        $(this).addClass('hide').attr('disabled','disabled').removeAttr('selected');
                    }else{
                        $(this).removeClass('hide').removeAttr('disabled');
                    }
                    $('#secretariat-division').val("");
                });
            }else{
                $('#secretariat-division').val("");
            }
        });
        $(document).on('click',".deletearbitrator",function(){
            var curRow = $(this).closest('tr');
            var id = curRow.find("input[name$='[Id]']").val();
            if(id!=''){
                var reply = confirm("Are you sure you want to delete this arbitrator?");
                if(reply){

                    var baseUrl = $("input[name='URL']").val();
                    $.ajax({
                        url: baseUrl+"/web/deletearbitrator",
                        data: {id:id},
                        type: "POST",
                        dataType:'JSON',
                        success:function(data){
                            if(data.response == 1){
                                alert("Successfully deleted!");
                                curRow.remove();
                            }
                        }
                    });
                }
            }else{
                curRow.remove();
            }
        });
        $(document).on('change','.cid-no-check',function(){
            var form = $(this).parents('form');
            var value = $(this).val();
            var baseUrl = $("input[name='URL']").val();
            var id = form.find("input[name='WebTrainingDetailsId']").val();
                if(value != ''){
                    $.ajax({
                        url: baseUrl+"/web/cidnocheckfortraining",
                        type: "POST",
                        dataType: "JSON",
                        data: {no:value,id:id},
                        success: function(data){
                            if(data.passed == false){
                                alert('CID no. '+value+' has already been registered for this training!');
                            }
                        }
                    });
                }
        });
        $(document).on('change','.cdb-no-check',function(){
            var form = $(this).parents('form');
            var value = $(this).val();
            var baseUrl = $("input[name='URL']").val();
            var id = form.find("input[name='WebTrainingDetailsId']").val();
            if(value != ''){
                $.ajax({
                    url: baseUrl+"/web/cdbnocheckfortraining",
                    type: "POST",
                    dataType: "JSON",
                    data: {no:value,id:id},
                    success: function(data){
                        if(data.passed == false){
                            alert('CDB No. '+value+' has already registered for this training!');
                        }
                    }
                });
            }
        });
	 $(document).on('change','#refereshers-cdb',function(){
            var value = $(this).val();
            var form = $(this).closest('form');
            var trainingId = form.find("input[name='WebTrainingDetailsId']").val();
            var baseUrl = $("input[name='URL']").val();
            $.ajax({
                url: baseUrl+"/web/fetchdetails",
                type: "POST",
                dataType: "JSON",
                data:{cdb:value,trainingId:trainingId},
                success:function(data){
                    if(data == false){
                        form.find('#contractor-details').val('');
                        form.find("button[type='submit']").addClass('hide');
                    }else{
                        form.find('#contractor-details').val(data[0].Firm+" ("+data[0].Class+")");
                        if(data[0].Valid == false){
                            form.find("button[type='submit']").addClass('hide');
                            alert(data[0].message);
                        }else{
                            form.find("button[type='submit']").removeClass('hide');
                        }

                    }
                }
            });
        });
    }
    return{
        RefreshDashboard: refreshDashboard,
        CalculateTotal:calculateTotal,
        CheckHumanResource: checkHumanResource,
        CheckHumanResourceRegistration: checkHumanResourceRegistration,
        CheckEquipment: checkEquipment,
        CheckHumanResourceOccupied: checkHumanResourceOccupied,
        CheckEquipmentOccupied: checkEquipmentOccupied,
        CheckEquipmentRSTA: checkEquipmentRSTA,
        AddNewRow: addNewRow,
        Validate: validate,
        Initialize:initialize,
        Submitted:submitted,
        InitializeAutoComplete: initializeAutocomplete
    }
}();
$(document).ready(function(){
    common.Initialize();
});
