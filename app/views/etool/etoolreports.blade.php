@extends('horizontalmenumaster')
@section('pagescripts')
    {{ HTML::script('assets/global/scripts/etool.js') }}
@stop
@section('content')
    <input type="hidden" name="URL" value="{{CONST_APACHESITELINK}}"/>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>Reports
		</div>
	</div>
	<div class="portlet-body">
        <div class="container etoolreport-container">
            @if(in_array(1,$etoolReports))
            <div class="row">
                <div class="col-md-3">1. Contractor's Work in Hand</div>
                <div class="col-md-8">
                    <div class="form-group">
                        <div class="col-md-6">
                            <input name="parameter" type="hidden" value="CDBNo"/>
                            <div class="col-md-9"><input type="text" class="form-control input-sm" placeholder="CDBNo"/></div>
                            <div class="col-md-3"><a href="{{URL::to('etoolrpt/contractorworkinhand')}}" class="btn btn-sm blue-hoki">View</a></div>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            @endif
            @if(in_array(2,$etoolReports))
            <div class="row">
                <div class="col-md-3">2. Contractor's Human Resource</div>
                <div class="col-md-8">
                    <div class="form-group">
                        <div class="col-md-6">
                            <input name="parameter" type="hidden" value="CDBNo"/>
                            <div class="col-md-9"><input type="text" class="form-control input-sm" placeholder="CDBNo"/></div>
                            <div class="col-md-3"><a href="{{URL::to('etoolrpt/contractorhumanresource')}}" class="btn btn-sm blue-hoki">View</a></div>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            @endif
            @if(in_array(3,$etoolReports))
            <div class="row">
                <div class="col-md-3">3. Contractor's Equipment Details</div>
                <div class="col-md-8">
                    <div class="form-group">
                        <div class="col-md-6">
                            <input name="parameter" type="hidden" value="CDBNo"/>
                            <div class="col-md-9"><input type="text" class="form-control input-sm" placeholder="CDBNo"/></div>
                            <div class="col-md-3"><a href="{{URL::to('etoolrpt/contractorequipment')}}" class="btn btn-sm blue-hoki">View</a></div>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            @endif
            @if(in_array(4,$etoolReports))
            <div class="row">
                <div class="col-md-3">4. Work Details</div>
                <div class="col-md-8">
                    <div class="form-group">
                        <div class="col-md-6">
                            <input name="parameter" type="hidden" value="WorkId"/>
                            <div class="col-md-9"><input type="text" class="form-control input-sm" placeholder="Work Id"/></div>
                            <div class="col-md-3"><a href="{{URL::to('etoolrpt/workdetails')}}" class="btn btn-sm blue-hoki">View</a></div>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            @endif
            @if(in_array(5,$etoolReports))
            <div class="row">
                <div class="col-md-3">5. Contractor's Information</div>
                <div class="col-md-8">
                    <div class="form-group">
                        <div class="col-md-6">
                            <input name="parameter" type="hidden" value="CDBNo"/>
                            <div class="col-md-9"><input type="text" class="form-control input-sm" placeholder="CDBNo"/></div>
                            <div class="col-md-3"><a href="{{URL::to('etoolrpt/contractorinfo')}}" id="contractor-info" class="btn btn-sm blue-hoki">View</a></div>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            @endif
            @if(in_array(6,$etoolReports))
            <div class="row">
                <div class="col-md-3">6. Human Resource Check</div>
                <div class="col-md-8">
                    <div class="form-group">
                    <div class="col-md-6">
                            <input name="parameter" type="hidden" value="CIDNo"/>
                            <div class="col-md-9"><input type="text" id="cidNo" class="form-control input-sm" placeholder="CID No (Human)"/></div>
                            <div class="col-md-3">
                            <button type="button" onclick="checkCID()" class="btn btn-sm blue-hoki">View</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            @endif
            @if(in_array(7,$etoolReports))
            <div class="row">
                <div class="col-md-3">7. Equipment Check</div>
                <div class="col-md-8">
                    <div class="form-group">
                        <div class="col-md-6">
                            <input name="parameter" type="hidden" value="RegistrationNo"/>
                            <div class="col-md-9"><input type="text" id="registrationNo" class="form-control input-sm" placeholder="Registration No. (Equipment)"/></div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-9">
                                <select id="equipmentType" class="form-control input-sm">
                                    <option value="">---SELECT ONE---</option>
                                    @foreach($equipments as $equipment)
                                        <option value="{{$equipment->VehicleType}}">{{$equipment->Name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="button" onclick="viewequipmentDetails()" class="btn btn-sm blue-hoki">View</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            @endif
            @if(in_array(8,$etoolReports))
            <div class="row">
                <div class="col-md-3">8. Evaluation Details (for L and M)</div>
                <div class="col-md-8">
                    <div class="form-group">
                        <div class="col-md-6">
                            <input name="parameter" type="hidden" value="WorkId"/>
                            <div class="col-md-9"><input type="text" class="form-control input-sm" placeholder="Work Id"/></div>
                            <div class="col-md-3"><a href="{{URL::to('etoolrpt/evaluationdetails')}}" class="btn btn-sm blue-hoki">View</a></div>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            @endif
            @if(in_array(9,$etoolReports))
                <div class="row">
                    <div class="col-md-3">9. Bid Capacity Calculator</div>
                    <div class="col-md-8">
                        <div class="col-md-6">
                            <input name="parameter" type="hidden" value="WorkId"/>
                            <div class="col-md-3"><a href="{{URL::to('etoolrpt/bidcapacitycalculator')}}" class="btn btn-sm blue-hoki">Click here.</a></div>
                        </div>
                    </div>
                </div>
                <hr/>
            @endif
        </div>
	</div>
</div>
<input type="hidden" name="URL" id="x" value="{{URL::to('/');}}"/>
<div id="eqcheckmodal" class="modal fade in" role="dialog" aria-labelledby="modalmessagebox" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h3 id="modalmessageheader" class="modal-title font-red-intense">Equipment Check</h3>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>


<div id="hrcheckmodal" class="modal fade in" role="dialog" aria-labelledby="modalmessagebox" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h3 id="modalmessageheader" class="modal-title font-red-intense">HR Check</h3>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>
<script>
    function viewequipmentDetails()
    {
        var regNo = $("#registrationNo").val();
        var vehicleType = $("#equipmentType").val();

        regNo = regNo.toString();
        regNo = regNo.trim();
        var URL = $('input[name="URL"]').val();
        $('#addequipments').modal('hide');

        $vehicleTypeDesc = "";     
        if(vehicleType=="2")
        {
            vehicleType = "HEAVY_VEHICLE";
        }
        else if(vehicleType=="3")
        {
            vehicleType = "MEDIUM_VEHICLE";
        }
        else if(vehicleType=="4")
        {
            vehicleType = "EARTH_MOVING_EQUIPMENT";
        }
        else if(vehicleType=="5")
        {
            vehicleType = "LIGHT_VEHICLE";
        }
        else if(vehicleType=="6")
        {
            vehicleType = "TAXI";
        }
        else if(vehicleType=="7")
        {
            vehicleType = "TWO_WHEELER";
        }
        else if(vehicleType=="15")
        {
            vehicleType = "MEDIUM_BUS";
        }
        else if(vehicleType=="16")
        {
            vehicleType = "HEAVY_BUS";
        }
        else if(vehicleType=="17")
        {
            vehicleType = "POWER_TILLER";
        }
        else if(vehicleType=="18")
        {
            vehicleType = "TRACTOR";
        }
        vehicleTypeDesc = vehicleType;  
        var ownerCId="";
        var ownerName="";
        var expiryDate="";
        var vehicleModel="";
        var chassisNumber="";  
        var vehicleType="";
        var engineNumber="";

                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: "http://172.20.60.3:8181/DNPDatahub/api/getToken",
                    error: function(jqXHR, textStatus, errorThrown){
                    },
                    success: function(data){ 
                        accessTokenData = data[0].split(":");
                        accessTokenData = accessTokenData[1].split(",");
                        accessTokenData = accessTokenData[0].split('"');
                        accessTokenData = accessTokenData[1];

                $.ajax   
                ({
                    type : "GET",             
                    url : "http://172.20.60.3:8181/DNPDatahub/api/getVehicleDetails?vehicleNo="+regNo+"&vehicleType="+vehicleTypeDesc+"&token="+accessTokenData,   
                    data : $('form').serialize(),
                    cache : false,
                    dataType : "json",  
                    success : function(responseText, status, xhr) 
                    {
                    

                        if(responseText[0])
                        {
                            ownerCId = responseText[0]['ownerCId'];
                            ownerName = responseText[0]['ownerName'];
                            expiryDate = responseText[0]['expiryDate'];
                            vehicleModel = responseText[0]['vehicleModel'];
                            chassisNumber = responseText[0]['chassisNumber'];
                            vehicleType = responseText[0]['vehicleType'];
                            engineNumber = responseText[0]['engineNumber'];
                        }


                        window.open(URL+'/equipmentcheck?RegistrationNo='+regNo+'&VehicleType='+vehicleType+'&ownerCId='+ownerCId+'&ownerName='+ownerName+'&expiryDate='+expiryDate+'&vehicleModel='+vehicleModel+'&chassisNumber='+chassisNumber+'&engineNumber='+engineNumber, "_blank");

                        // $.ajax({
                        //     url: URL+'/equipmentcheck',
                        //     type: 'get',
                        //     dataType: 'html',
                        //     data: {RegistrationNo: regNo, VehicleType: vehicleType, ownerCId: ownerCId,
                        //         ownerName: ownerName, expiryDate: expiryDate, vehicleModel: vehicleModel,
                        //         chassisNumber: chassisNumber, engineNumber: engineNumber},
                        //     success: function(data){
                        //     $('#eqcheckmodal .modal-body').html(data);
                        //     $('#addhumanresource').modal('hide');
                        //     $('#eqcheckmodal').modal('show');
                        //     }
                        // });

                    }
                });
            }
        });
    }



function checkCID()
{
    var cid = $("#cidNo").val();
    $.ajax({
            type: "GET",
            dataType: "json",
            url: "http://172.20.60.3:8181/DNPDatahub/api/getToken",
            error: function(jqXHR, textStatus, errorThrown){
            },
            success: function(data){ 
                accessTokenData = data[0].split(":");
                accessTokenData = accessTokenData[1].split(",");
                accessTokenData = accessTokenData[0].split('"');
                accessTokenData = accessTokenData[1];

            $.ajax   
            ({
                type : "GET",             
                url : "http://172.20.60.3:8181/DNPDatahub/api/getRcscDetails?cid="+cid+"&token="+accessTokenData,   
                data : $('form').serialize(),
                cache : false,
                dataType : "json",  
                success : function(responseText, status, xhr) 
                {
                    var isEmployed = false; 
                    var workingAgency = null; 
                    if(responseText[0])
                    {
                        isEmployed = true;
                        workingAgency = responseText[0]['workingAgency'];
                        
                    }
                   // checkHumanResource(cid, $('input[name="Id"]').val(),isEmployed);
                    $.ajax   
                    ({
                        type : "GET",             
                        url : "http://172.20.60.3:8181/DNPDatahub/api/citizenDetails?cid="+cid+"&token="+accessTokenData,   
                        data : $('form').serialize(),
                        cache : false,
                        dataType : "json",  
                        success : function(responseText, status, xhr) 
                        {
                            
                            var isBhutanese = false;
                            var name = "";
                            var dzongkhag = "";
                            var gewog = "";
                            var village = "";
                            var dob = "";
                            var gender = "";
                            if(responseText[0])
                            {
                                isBhutanese = true;
                                name = responseText[0]['name'];
                                dzongkhag = responseText[0]['dzongkhag'];
                                gewog = responseText[0]['gewog'];
                                village = responseText[0]['village'];
                                dob = responseText[0]['dob'];
                                gender = responseText[0]['gender'];
                            }
                            var url = $('input[name="URL"]').val();
                            if(isEmployed)
                            {
                                isEmployed="true";
                            }
                            else
                            {
                                isEmployed="false";
                            }

                            window.open(url+'/hrcheck?CIDNo='+cid+'&isBhutanese='+isBhutanese+'&name='+name+'&dzongkhag='+dzongkhag+'&gewog='+gewog+'&village='+village+'&dob='+dob+'&gender='+gender+'&isCivilServant='+isEmployed+'&workingAgency='+workingAgency, "_blank");

                            
                            // $.ajax({
                            //     url: url+'/hrcheck',
                            //     type: 'post',
                            //     dataType: 'text',
                            //     data: {CIDNo: cid, isBhutanese: isBhutanese, name: name, dzongkhag: dzongkhag, gewog: gewog, village: village, dob: dob, gender: gender, isCivilServant: isEmployed, workingAgency: workingAgency},
                            //     success: function(data){
                            //         $('#hrcheckmodal .modal-body').html(data);
                            //         $('#addhumanresource').modal('hide');
                            //         $('#hrcheckmodal').modal('show');
                            //     }
                            // });

                        }
                    });
                }
            });
        }
    });
}
</script>
@stop