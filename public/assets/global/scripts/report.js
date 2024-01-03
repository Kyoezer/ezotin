/**
 * Created by Sangay on 4/17/2015.
 */
var report = function(){
    function contractorsByDzongkhag(){
        var total = parseInt($('#contractorsdzongkhag-table tr:last>td:last').text());
        var curRow;
        var dzongkhag;
        var value;
        var percentageDzongkhag;
        var dataArray = [];
        var valueArray = [];
        $('tbody tr:not(:last)').each(function(){
            curRow = $(this);
            dzongkhag = curRow.find('td:first').text();
            value = parseInt(curRow.find('td:last').text());
            percentageDzongkhag = value/total * 100;
            valueArray['dzongkhag'] = dzongkhag;
            valueArray['value'] = percentageDzongkhag.toFixed(2);
            dataArray.push(valueArray);
            valueArray = [];
        });
        Morris.Bar({
            element: 'contractorsbydzongkhag',
            data: dataArray,
            xkey: 'dzongkhag',
            ykeys: ['value'],
            labels: ['Percentage'],
            xLabelAngle: 45
        });
    }
    function contractorsByClass(){
        var largeTotal = 0;
        var mediumTotal = 0;
        var smallTotal = 0;
        var registeredTotal = $('.registered-total').text();
        $('.large-total').each(function(){
            largeTotal += parseInt($(this).text());
        });
        $('.medium-total').each(function(){
            mediumTotal += parseInt($(this).text());
        });
        $('.small-total').each(function(){
            smallTotal += parseInt($(this).text());
        });
        Morris.Bar({
            element: 'contractorsbyclass',
            data: [
                    { class: 'Large(L)',value:largeTotal },
                    { class: 'Medium(M)',value:mediumTotal },
                    { class: 'Small(S)',value:smallTotal },
                    { class: 'Registered(R)',value:registeredTotal }
                ],
            xkey: 'class',
            ykeys: ['value'],
            labels: ['No of Contractors']
        });
    }
    function workDistributionByClass(){
        var array = [];
        var dataArray = [];
        $('#workdistributionbyclass tbody tr:not(:last)').each(function(){
            var label = $(this).find('td:first').text();
            var noOfWorks = $(this).find('td:eq(1)').text();
            var amount = $(this).find('td:last').find('input[name="amount"]').val();
            array = {class: label, a: noOfWorks,b:amount};
            dataArray.push(array);
            array = [];
        })
        Morris.Bar({
            element: 'graph',
            data: dataArray,
            xkey: 'class',
            ykeys: ['a','b'],
            labels: ['No of Works','Contract Amount (Nu.)']
        });
    }
    function initialize(){
        if($('#contractorsdzongkhag-table').length>0){
            contractorsByDzongkhag();
            contractorsByClass();
        }
        if($('#workdistributionbyclass').length>0){
            workDistributionByClass();
        }
        $(".show-tender-details").on('click',function(){
            var id = $(this).data('id');
            var url = $("input[name='URL']").val();
            $('#details-wrapper').empty();
            $('#details-wrapper').load(url+'/etoolrpt/gettenderdownloaddetails/'+id);
            $('#tenderdownloaddetailsmodal').modal('show');
        });
    };
    return{
        ContractorsByClass: contractorsByClass,
        ContractorsByDzongkhag: contractorsByDzongkhag,
        WorkDistributionByClass: workDistributionByClass,
        Initialize: initialize
    }
}();
$(document).ready(function(){
    report.Initialize();
});
