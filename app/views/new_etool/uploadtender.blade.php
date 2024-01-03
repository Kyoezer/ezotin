@extends('horizontalmenumaster')
@section('pagescripts')
    {{ HTML::script('assets/global/scripts/etool.js') }}
@stop
@section('content')

<input type="hidden" name="URL" value="{{CONST_APACHESITELINK}}"/>
<?php $routePrefix = Request::segment(1); ?>
<div class="row">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://momentjs.com/downloads/moment.js"></script>

	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-gift"></i>Upload Tender
                    <!-- {{Form::open(array('url'=>'etl/savecriteria'))}}
                     {{Form::hidden('EtlTenderId','a01a9c90-24cf-11e6-a114-9c2a70cc8e06')}}
                        
                        <input type="text" name="HiddenWorkId" value="a01a9c90-24cf-11e6-a114-9c2a70cc8e06">
                        <a href="{{URL::to('etl/workidetool')}}" class="btn blue"><i class="m-icon-swapleft m-icon-white"></i>&nbsp;Back</a>
                        <button type="submit" class="btn green">Update</button>
                    {{Form::close()}} -->
				</div>
			</div>
			<div class="portlet-body form">
                <!-- BEGIN FORM-->
               
                {{Form::open(array('url' => "$routePrefix/etooluploadtender",'id'=>'tenderForm','role'=>'form','class'=>'form-horizontal','files'=>true))}}
					
                
                <div id="hrCriteria" style="display:none"></div>
                <div id="equipmentCriteria" style="display:none"></div>
                <div id="bidderFinancial" style="display:none"></div>
                <div id="commiteeDetails" style="display:none"></div>
                
                    <div class="form-body">
                        <div class="note note-info">
                            Date and Time Selection is based on 24 hour format
                        </div>
                        <input type="hidden" id="tenderStatus" name="TenderStatus" value="Fresh">
                        @foreach($savedTenders as $savedTender)
                        {{Form::hidden('WorkId',$savedTender->WorkId)}}
						 <div class="form-group">
                            <label class="control-label col-md-3">e-GP Tender Id</label>
                            <div class="col-md-3">
                            @if($savedTender->EGPTenderId=='test-901')
                            <input  type="text" name="EGPTenderId" id="TenderId" 
                                value="901" 
                                class="form-control input-medium" placeholder="e-GP Tender ID">
                            @else
                                <input  type="text" name="EGPTenderId" id="TenderId" 
                                value="{{$savedTender->EGPTenderId?$savedTender->EGPTenderId:Input::old('EGPTenderId')}}" 
                                class="form-control input-medium" placeholder="e-GP Tender ID">
                            @endif
							    
                                
                                <span class="help-block error-span required-message egpTenderIdErrorMessage" style="display:none"></span>
                            </div>
                             <div class="col-md-4">
                                <a class="btn btn-primary" onclick="getEgpTenderDtls()"><span class="fa fa-search" style="margin-right: 1px;"></span>Search</a>
                                <a class="btn btn-warning" onclick="resetForm()">Clear</a>
                            </div> 
						</div> 
						<div class="form-group">
                            <label class="control-label col-md-3">Reference No./Letter No. <span class="font-red">*</span></label>
                            <div class="col-md-4">
                            <input type="hidden" name="TenderSource" value="{{$tenderSource}}" />
                            <input type="hidden" name="Id" value="{{$savedTender->Id?$savedTender->Id:Input::old('Id')}}" />
							<input type="text"  id="ReferenceNo" name="ReferenceNo" value="{{$savedTender->ReferenceNo?$savedTender->ReferenceNo:Input::old('ReferenceNo')}}" class="form-control input-medium required" placeholder="Reference No.">
                            </div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Name of Work <span class="font-red">*</span></label>
                            <div class="col-md-9">
							    <input type="text"  name="NameOfWork" id="NameOfWork" value="{{$savedTender->NameOfWork?$savedTender->NameOfWork:Input::old('NameOfWork')}}" class="form-control required" />
                            </div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Description of Work <span class="font-red">*</span></label>
                            <div class="col-md-9">
							    <textarea  name="DescriptionOfWork" id="DescriptionOfWork" class="wysihtml5 form-control required" rows="4">{{$savedTender->DescriptionOfWork?$savedTender->DescriptionOfWork:Input::old('DescriptionOfWork')}}</textarea>
                            </div>
						</div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Contact Person</label>
                                <div class="col-md-4">
                                    <input  type="text" id="ContactPerson" value="{{$savedTender->ContactPerson?$savedTender->ContactPerson:Input::old('ContactPerson')}}" name="ContactPerson" class="form-control">

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Contact No.</label>
                                <div class="col-md-4">
                                    <input  type="text" value="{{$savedTender->ContactNo?$savedTender->ContactNo:Input::old('ContactNo')}}" class="form-control" id="ContactNo" name="ContactNo">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Contact Email</label>
                                <div class="col-md-4">
                                    <input  type="text" name="ContactEmail" id="ContactEmail" value="{{$savedTender->ContactEmail?$savedTender->ContactEmail:Input::old('ContactEmail')}}" class="form-control email">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Dzongkhag <span class="font-red">*</span></label>
                                <div class="col-md-4">
                                    <select  class="form-control required" name="CmnDzongkhagId" id="CmnDzongkhagId">
                                        <option value="">---SELECT ONE---</option>
                                        @foreach($dzongkhags as $dzongkhag)
                                            <option @if($dzongkhag->Id == $savedTender->CmnDzongkhagId || $dzongkhag->Id ==Input::old('CmnDzongkhagId'))selected="selected"@endif value="{{$dzongkhag->Id}}">{{$dzongkhag->NameEn}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Classification <span class="font-red">*</span></label>
                                <div class="col-md-4">
                                    <select  class="form-control required classification" id="CmnContractorClassificationId" name="CmnContractorClassificationId">
                                        <option value="">---SELECT ONE---</option>
                                        @foreach($contractorClassifications as $contractorClassification)
                                            <option data-reference="{{$contractorClassification->ReferenceNo}}" @if($contractorClassification->Id == $savedTender->CmnContractorClassificationId || $contractorClassification->Id == Input::old('CmnContractorClassificationId'))selected="selected"@endif value="{{$contractorClassification->Id}}">{{$contractorClassification->Name.' ('.$contractorClassification->Code.')'}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Category <span class="font-red">*</span></label>
                                <div class="col-md-4">
                                    <select  class="form-control required category" id="CmnContractorCategoryId" name="CmnContractorCategoryId">
                                        <option value="">---SELECT ONE---</option>
                                        @foreach($contractorCategories as $contractorCategory)
                                            <option data-reference="{{$contractorCategory->ReferenceNo}}" @if($contractorCategory->Id == $savedTender->CmnContractorCategoryId || $contractorCategory->Id == Input::old('CmnContractorCategoryId'))selected="selected"@endif value="{{$contractorCategory->Id}}">{{$contractorCategory->Name.' ('.$contractorCategory->Code.')'}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Method <span class="font-red">*</span></label>
                                <div class="col-md-4">
                                    <select  class="form-control required category" id="method" name="Method">   
                                        <option value="">---SELECT ONE---</option>
                                        <option value="OPEN_TENDERING">Open Tendering Method</option>
                                        <option value="LIMITED_TENDERING">Limited Tendering Method</option>
                                        <option value="DIRECT_CONTRACTING">Direct Contracting Method</option>
                                        <option value="LIMITED_ENQUIRY">Limited Enquiry Method</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Contract Period (Months) <span class="font-red">*</span></label>
                                <div class="col-md-4">
                                    <input  type="text" id="ContractPeriod" value="{{$savedTender->ContractPeriod?$savedTender->ContractPeriod:Input::old('ContractPeriod')}}" name="ContractPeriod" class="form-control number required durationnumber" placeholder="Contract period in months">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Tentative Start Date <span class="font-red">*</span></label>
                                <div class="col-md-4">
                                    <div class="input-icon right">
                                        <i class="fa fa-calendar"></i>
                                        <input type="text" name="TentativeStartDate" autocomplete="off" id="TentativeStartDate" class="form-control datepicker required calculateenddate" value="{{$savedTender->TentativeStartDate?convertDateToClientFormat($savedTender->TentativeStartDate):Input::old('TentativeStartDate')}}" placeholder="">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Tentative End Date <span class="font-red">*</span></label>
                                <div class="col-md-4">
                                    <div class="input-icon right">
                                        <i class="fa fa-calendar"></i>
                                        <input  type="text" name="TentativeEndDate" id="TentativeEndDate" autocomplete="off" class="form-control datepicker required durationendresult" value="{{$savedTender->TentativeEndDate?convertDateToClientFormat($savedTender->TentativeEndDate):Input::old('TentativeEndDate')}}"  placeholder="">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Project Estimate Cost <span class="font-red">*</span></label>
                                <div class="col-md-4">
                                    <input  type="text" value="{{$savedTender->ProjectEstimateCost?$savedTender->ProjectEstimateCost:Input::old('ProjectEstimateCost')}}" name="ProjectEstimateCost" id="ProjectEstimateCost" class="form-control number required" placeholder="Project Estimate Cost">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Show Project Estimate Cost in Website <span class="font-red">*</span></label>
                                <div class="col-md-4">
                                    <div class="radio-list">
                                        <label class="radio-inline">
                                            <input  type="radio" name="ShowCostInWebsite" id="optionsRadios4" value="1" @if($savedTender->ShowCostInWebsite == NULL || $savedTender->ShowCostInWebsite == 1)checked @endif> Yes </label>
                                        <label class="radio-inline">
                                            <input  type="radio" name="ShowCostInWebsite" id="optionsRadios5" value="0" @if($savedTender->ShowCostInWebsite == '0')checked @endif> No </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Date of Sale of Tender Document <span class="font-red">*</span></label>
                                <div class="col-md-4">
                                    <div class="input-icon right">
                                        <i class="fa fa-calendar"></i>
                                        <input  type="text" name="DateOfSaleOfTender" autocomplete="off" id="DateOfSaleOfTender" class="form-control datepicker required"value="{{$savedTender->DateOfSaleOfTender?convertDateToClientFormat($savedTender->DateOfSaleOfTender):Input::old('DateOfSaleOfTender')}}"  placeholder="">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Closing Date of Sale of Tender <span class="font-red">*</span></label>
                                <div class="col-md-4">
                                    <div class="input-icon right">
                                        <i class="fa fa-calendar"></i>
                                        <input  type="text" name="DateOfClosingSaleOfTender" autocomplete="off" id="DateOfClosingSaleOfTender" class="form-control datepicker required" value="{{$savedTender->DateOfClosingSaleOfTender?convertDateToClientFormat($savedTender->DateOfClosingSaleOfTender):Input::old('DateOfClosingSaleOfTender')}}"  placeholder="">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Last Date & Time of Submission <span class="font-red">*</span></label>
                                <div class="col-md-4">
                                    <div class="input-icon right">
                                        <i class="fa fa-calendar"></i>
                                        <input  type="text" name="LastDateAndTimeOfSubmission" autocomplete="off" id="LastDateAndTimeOfSubmission" class="form-control form_datetime required" value="{{$savedTender->LastDateAndTimeOfSubmission?convertDateTimeToClientFormat($savedTender->LastDateAndTimeOfSubmission):Input::old('LastDateAndTimeOfSubmission')}}"  placeholder="">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Opening Date & Time <span class="font-red">*</span></label>
                                <div class="col-md-4">
                                    <div class="input-icon right">
                                        <i class="fa fa-calendar"></i>
                                        <input  type="text" name="TenderOpeningDateAndTime" autocomplete="off" id="TenderOpeningDateAndTime" class="form-control form_datetime required" value="{{$savedTender->TenderOpeningDateAndTime?convertDateTimeToClientFormat($savedTender->TenderOpeningDateAndTime):Input::old('TenderOpeningDateAndTime')}}"  placeholder="">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Cost of Tender Document <span class="font-red">*</span></label>
                                <div class="col-md-4">
                                    <input  type="text" value="{{$savedTender->CostOfTender?$savedTender->CostOfTender:Input::old('CostOfTender')}}" name="CostOfTender" id="CostOfTender" class="form-control number required" placeholder="Cost of Tender Document">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">EMD/Bid Security <span class="font-red">*</span></label>
                                <div class="col-md-4">
                                    <input   type="text" value="{{$savedTender->EMD?$savedTender->EMD:Input::old('EMD')}}" name="EMD" id="EMD" class="form-control required" placeholder="EMD">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Publish in Website <span class="font-red">*</span></label>
                                <div class="col-md-4">
                                    <div class="radio-list">
                                        <label class="radio-inline">
                                            @if($savedTender->PublishInWebsite == NULL)
                                                <?php $case = 1; ?>
                                            @endif
                                            @if($savedTender->PublishInWebsite == '0')
                                                <?php $case = 2; ?>
                                            @endif
                                            @if($savedTender->PublishInWebsite == '1')
                                                <?php $case = 3; ?>
                                            @endif
                                        <input  type="radio" name="PublishInWebsite" id="optionsRadios4" value="1" @if($case == 1 || $case == 3) checked @endif > Yes </label>
                                        <label class="radio-inline">
                                        <input  type="radio" name="PublishInWebsite" id="optionsRadios5" value="0"  @if($case == 2) checked @endif> No </label>
                                    </div>
                                </div>
                            </div>

                        @endforeach
                        <?php $count = 1; ?>
                        @if(isset($tenderAttachments[0]->Id))
                            <div class="col-md-6 table-responsive">
                                <h5 class="font-blue-madison bold">Uploaded Tender Related Documents</h5>
                                <table class="table table-bordered table-striped table-condensed">
                                    <thead>
                                        <tr>
                                            <th>Sl. No.</th>
                                            <th>Document Name</th>   
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $count = 1; ?>
                                    @foreach($tenderAttachments as $tenderAttachment)
                                        <tr>
                                            <td>{{$count}}<input type="hidden" name="DocumentId" value="{{$tenderAttachment->Id}}"/></td>
                                            <td><a href="{{asset($tenderAttachment->DocumentPath)}}" target="_blank"><u>{{$tenderAttachment->DocumentName}}</u></a></td>
                                            <td><a href="#" class="deletefile btn-sm"><i class="fa fa-edit"></i>Delete</a></td>
                                        </tr>
                                        <?php $count++; ?>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        {{--<div class="row">--}}
                            <div class="col-md-6 table-responsive">
                                <h5 class="font-blue-madison bold">Upload Tender Related Documents</h5>
                                <table id="contractorhumanresource" class="table table-bordered table-striped table-condensed">
                                    <thead>
                                    <tr>
                                        <th>Document Name</th>
                                        <th>Upload File</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <input type="hidden" name="DocumentId[]" class="form-control input-sm">
                                            <input type="text" name="DocumentName[]" class="form-control input-sm">
                                        </td>
                                        <td>
                                            <input type="file" name="attachments[]" value="" class="input-sm" multiple="multiple" />
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div><div class="clearfix"></div>
                        {{--</div>--}}
					</div>
					<div class="form-actions">
						<div class="btn-set">
							<button type="submit" id="save" onclick="onsubmit()" class="btn green">Save</button>
                            @if($tenderSource == 1)
                                <?php $cancelRoute = 'newEtl/uploadedtenderlistetool'; ?>
                            @else
                                <?php $cancelRoute = "cinet/uploadedtenderlistcinet"; ?>
                            @endif
							<a href="{{URL::to($cancelRoute)}}" class="btn red">Cancel</a>
						</div>
					</div>
                {{Form::close()}}
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
    function onsubmit()
    {
        $("#save").attr('disabled','disabled');
    }

	function getEgpTenderDtls()
    {
        //$('#CmnDzongkhagId').children('option').hide();
        //$('#CmnContractorClassificationId').children('option').hide();
        //$('#CmnContractorCategoryId').children('option').hide();
        $(".egpTenderIdErrorMessage").hide();
        var tenderId = $("#TenderId").val();
        $("#TenderId").attr('readonly','readonly');
        if(tenderId!="")
        {
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "validateEgpTenderId/"+tenderId,
                error: function(jqXHR, textStatus, errorThrown){
                   $(".egpTenderIdErrorMessage").text("Something went wrong while trying to pull data from e-GP");
                   $(".egpTenderIdErrorMessage").show();
                },
                success: function(data){ 
                    var checkTenderId = data[0].rowCount;
                    var accessTokenData = "";
                    if(checkTenderId==0)
                    {
                        $.ajax({
                        type: "GET",
                        dataType: "json",
                        url: "http://119.2.104.81:8181/DNPDatahub/api/getToken",
                        error: function(jqXHR, textStatus, errorThrown){
                        },
                        success: function(data){ 
                            accessTokenData = data[0].split(":");
                            accessTokenData = accessTokenData[1].split(",");
                            accessTokenData = accessTokenData[0].split('"');
                            accessTokenData = accessTokenData[1];
                             
                        $.ajax({
                            type: "GET",
                            dataType: "json",   
                            url: "http://119.2.104.81:8181/DNPDatahub/api/getTenderDetails?tenderId="+tenderId+"&token="+accessTokenData,
                            error: function(jqXHR, textStatus, errorThrown){
                            $(".egpTenderIdErrorMessage").text("Something went wrong while trying to pull data from e-GP");
                            $(".egpTenderIdErrorMessage").show();
                            },
                            success: function(data){ 
                                if(data.length<1)
                                {
                                    $(".egpTenderIdErrorMessage").text("Invalid e-GP tender Id or could not pull data from e-GP");
                                    $(".egpTenderIdErrorMessage").show();
                                }
                                else
                                {
                                    // $('#CmnDzongkhagId option:contains('+data[0].dzongkhag+')').show();
                                    //$('#CmnContractorClassificationId option:contains('+data[0].classification+')').show();
                                    //$('#CmnContractorCategoryId option:contains('+data[0].category+')').show();
                                    //var auCode =data[0].auCode;






                                    $("#CmnDzongkhagId").attr('readonly','readonly');
                                    $("#CmnContractorClassificationId").attr('readonly','readonly');
                                    $("#CmnContractorCategoryId").attr('readonly','readonly');
                                    $("#method").attr('readonly','readonly');
                                    $("#tenderStatus").attr('readonly','readonly');
                                    $("#ReferenceNo").attr('readonly','readonly');
                                    $("#NameOfWork").attr('readonly','readonly');
                                    $("#ContactPerson").attr('readonly','readonly');
                                    $("#ContactNo").attr('readonly','readonly');
                                    $("#ContactEmail").attr('readonly','readonly');
                                    $("#TentativeStartDate").attr('readonly','readonly');
                                    $("#TentativeEndDate").attr('readonly','readonly');
                                    $("#ProjectEstimateCost").attr('readonly','readonly');
                                    $("#ContractPeriod").attr('readonly','readonly');
                                    $("#DateOfSaleOfTender").attr('readonly','readonly');
                                    $("#DateOfClosingSaleOfTender").attr('readonly','readonly');
                                    $("#LastDateAndTimeOfSubmission").attr('readonly','readonly');
                                    $("#TenderOpeningDateAndTime").attr('readonly','readonly');
                                    $("#CostOfTender").attr('readonly','readonly');
                                    $("#EMD").attr('readonly','readonly');
                                    $("#DescriptionOfWork").attr('readonly','readonly');
                                    $(".wysihtml5").attr('readonly','readonly');
                                    
                                   
                                    $("#CmnDzongkhagId option:contains("+data[0].dzongkhag+")").attr('selected', true);
                                    $("#CmnContractorClassificationId option:contains("+data[0].classification+")").attr('selected', true);
                                    $("#CmnContractorCategoryId option:contains("+data[0].category+")").attr('selected', true);

                                    var methodData = data[0].method;
                                    if(methodData.includes("Limited Enquiry"))
                                    {
                                        $("#method option:contains(Limited Enquiry)").attr('selected', true);
                                    }
                                    else if(methodData.includes("Open Tendering Method"))
                                    {
                                        $("#method option:contains(Open Tendering)").attr('selected', true);
                                    }
                                    else if(methodData.includes("Limited Tendering Method (LTM)"))
                                    {
                                        $("#method option:contains(Limited Enquiry Method)").attr('selected', true);
                                    }
                                    $("#tenderStatus").val(data[0].retenderStatus);
                                    $("#ReferenceNo").val(data[0].referenceNo);
                                    $("#NameOfWork").val(data[0].nameofWork);
                                    $('iframe').contents().find('.wysihtml5-editor').html(data[0].descriptionofWork);

                                // $("#DescriptionOfWork").html(data[0].descriptionofWork);
                                    $("#ContactPerson").val(data[0].contactPerson);
                                    $("#ContactNo").val(data[0].contactNo);
                                    $("#ContactEmail").val(data[0].contactEmail);
                                    var TentativeStartDate = moment(data[0].contractStartDate, "DD/MM/YYYY");
                                    var TentativeEndDate = moment(data[0].contractEndDate, "DD/MM/YYYY");
                                    $("#TentativeStartDate").val(TentativeStartDate.format("DD-MM-YYYY"));
                                    $("#TentativeEndDate").val(TentativeEndDate.format("DD-MM-YYYY"));
                                    $("#ProjectEstimateCost").val(data[0].projectEstimateCost);

                                    var dateofSaleofTenderDocument = moment(data[0].dateofSaleofTenderDocument);
                                    var closingDateofSaleofTender = moment(data[0].closingDateofSaleofTender);
                                    var lastDateTimeofSubmission = moment(data[0].lastDateTimeofSubmission);
                                    var openingDateTime = moment(data[0].openingDateTime);

                                    const diffInMonths = (end, start) => {
                                        var timeDiff = Math.abs(end.getTime() - start.getTime());
                                        return Math.round(timeDiff / (2e3 * 3600 * 365.25));
                                    }

                                    const ContractPeriod = diffInMonths(new Date(TentativeStartDate.format("YYYY"),TentativeStartDate.format("M"),TentativeStartDate.format("D")), new Date(TentativeEndDate.format("YYYY"),TentativeEndDate.format("M"),TentativeEndDate.format("D")));
                                    
                                   
                                
                                    $("#ContractPeriod").val(ContractPeriod);
                                    $("#DateOfSaleOfTender").val(dateofSaleofTenderDocument.format("DD-MM-YYYY"));
                                    $("#DateOfClosingSaleOfTender").val(closingDateofSaleofTender.format("DD-MM-YYYY"));
                                    $("#LastDateAndTimeOfSubmission").val(lastDateTimeofSubmission.format("DD-MM-YYYY HH:mm"));
                                    $("#TenderOpeningDateAndTime").val(openingDateTime.format("DD-MM-YYYY HH:mm"));
                                    $("#CostOfTender").val(data[0].costofTenderDocument);
                                    $("#EMD").val(data[0].bidSecurity);
                                }
                            }
                        });

                        //EQUIPMENT DETAILS   
                        //tenderId = 569; 


                        
                        // TENDER FINANCIAL


                        $.ajax({
                            type: "GET",
                            dataType: "json",   
                            url: "http://119.2.104.81:8181/DNPDatahub/api/tenderCommitee?tenderId="+tenderId+"&token="+accessTokenData,
                            error: function(jqXHR, textStatus, errorThrown){
                            $(".egpTenderIdErrorMessage").text("Something went wrong while trying to pull tender commitee data from e-GP");
                            $(".egpTenderIdErrorMessage").show();
                            },
                            success: function(data){ 
                                if(data.length>0)
                                {
                                    for(var i =0;i<data.length;i++)
                                    {
                                        var commiteeName = data[i].commiteeName
                                        var memberName = data[i].memberName;
                                        var designation = data[i].designation;
                                        <?php $randomKey = randomString(); ?>
                                        if(commiteeName=="TC member")
                                        {
                                            commiteeName = "2";
                                        }
                                        else if(commiteeName=="Evaluation  member")
                                        {
                                            commiteeName = "1";
                                        }

                                        $("#commiteeDetails").append("<input type='text' name='commiteeMember[<?=$randomKey?>"+i+"][Type]' value='"+commiteeName+"'>"+
                                        "<input type='text' name='commiteeMember[<?=$randomKey?>"+i+"][Name]' value='"+memberName+"'>"+
                                        "<input type='text' name='commiteeMember[<?=$randomKey?>"+i+"][Designation]' value='"+designation+"'>");
                                    }
                                }
                                else
                                {
                                    $(".egpTenderIdErrorMessage").text("Something went wrong while trying to pull tender commitee data from e-GP");
                                    $(".egpTenderIdErrorMessage").show();
                                }
                            }
                        });


                        $.ajax({
                            type: "GET",
                            dataType: "json",   
                            url: "http://119.2.104.81:8181/DNPDatahub/api/tenderFinancialLargeWork?tenderId="+tenderId+"&token="+accessTokenData,
                            error: function(jqXHR, textStatus, errorThrown){
                            $(".egpTenderIdErrorMessage").text("Something went wrong while trying to pull financial quoted data from e-GP");
                            $(".egpTenderIdErrorMessage").show();
                            },
                            success: function(data){ 
                                if(data.length>0)
                                {
                                    for(var i =0;i<data.length;i++)
                                    {
                                        var companyName = data[i].companyName
                                        var amount = data[i].amount;
                                        var cdb = data[i].cdb;
                                        <?php $randomKey = randomString(); ?>
                                        $("#bidderFinancial").append("<input type='text' name='Contractor[<?=$randomKey?>"+i+"][CDBNo]' value='"+cdb+"'>"+
                                        "<input type='text' name='Contractor[<?=$randomKey?>"+i+"][companyName]' value='"+companyName+"'>"+
                                        "<input type='text' name='Contractor[<?=$randomKey?>"+i+"][FinancialBidQuoted]' value='"+amount+"'>");
                                    }
                                }
                                else
                                {
                                    $(".egpTenderIdErrorMessage").text("Something went wrong while trying to pull financial quoted data from e-GP");
                                    $(".egpTenderIdErrorMessage").show();
                                }
                                
                            }
                        });

                        //return true;

                        $.ajax({
                        type: "GET",
                        dataType: "json",   
                        url: "http://119.2.104.81:8181/DNPDatahub/api/tenderBidderinformationdetail?tenderId="+tenderId+"&token="+accessTokenData,
                        error: function(jqXHR, textStatus, errorThrown){
                        $(".egpTenderIdErrorMessage").text("Something went wrong while trying to pull  data from e-GP");
                        $(".egpTenderIdErrorMessage").show();
                        },
                        success: function(data){ 
                        }

                        });


                          //EQ DETAILS
                          $.ajax({
                        type: "GET",
                        dataType: "json",   
                        url: "http://119.2.104.81:8181/DNPDatahub/api/tenderEquipmentLargeWork?tenderId="+tenderId+"&token="+accessTokenData,
                        error: function(jqXHR, textStatus, errorThrown){
                        $(".egpTenderIdErrorMessage").text("Something went wrong while trying to pull equipment data from e-GP");
                        $(".egpTenderIdErrorMessage").show();
                        },
                        success: function(data){ 
                            if(data.length>0)
                            {
                                var col=1;
                                var tier;
                                var name;
                                var quantity;
                                var points;
                                var vehicleNo;
                                var Total;
                                var Hired;
                                var Owned;
                                var companyName;
                                for(var i =0;i<data.length;i++)
                                {
                                    
                                    companyName = data[i].companyName;
                                    tier = data[i].valuePA;
                                    i++;
                                    name = data[i].valuePA;  
                                    name = name.replace("&amp;", "and");
                                    i++;
                                    quantity = 1;//data[i].valuePA;  
                                    //i++;
                                    points = data[i].valuePA;  
                                    i++;   
                                    vehicleNo = data[i].valueBidder;  
                                    i++;
                                    if(data[i].valueBidder=='1')
                                    {
                                        Owned = data[i].valueBidder;
                                    }
                                    else
                                    {
                                        Hired = data[i].valueBidder;  
                                    }
                                   // i++;  
                                   // Total = data[i].valueBidder;  
                                    <?php $randomKey = randomString(); ?>
                                    $("#equipmentCriteria").append("<input type='text' name='etlcriteriaequipment[<?=$randomKey?>"+i+"][EtlTierId]' value='"+tier+"'>"+
                                    "<input type='text' name='etlcriteriaequipment[<?=$randomKey?>"+i+"][companyName]' value='"+companyName+"'>"+
                                    "<input type='text' name='etlcriteriaequipment[<?=$randomKey?>"+i+"][Quantity]' value='"+quantity+"'>"+
                                    "<input type='text' name='etlcriteriaequipment[<?=$randomKey?>"+i+"][Points]' value='"+points+"'>"+   
                                    "<input type='text' name='etlcriteriaequipment[<?=$randomKey?>"+i+"][CmnEquipmentId]' value='"+name+"'>"+
                                    "<input type='text' name='etlcriteriaequipment[<?=$randomKey?>"+i+"][RegistrationNo]' value='"+vehicleNo+"'>"+
                                    "<input type='text' name='etlcriteriaequipment[<?=$randomKey?>"+i+"][Owned]' value='"+Owned+"'>"
                                    // "<input type='text' name='etlcriteriaequipment[<?=$randomKey?>"+i+"][Hired]' value='"+Hired+"--Hired'>"
                                    );
                                }
                            }
                            else
                            {
                                $(".egpTenderIdErrorMessage").text("Something went wrong while trying to pull equipment data from e-GP");
                                $(".egpTenderIdErrorMessage").show();
                            }
                        }

                        });

                         //HR DETAILS

                         $.ajax({
                        type: "GET",
                        dataType: "json",   
                        url: "http://119.2.104.81:8181/DNPDatahub/api/tenderHRLargeWork?tenderId="+tenderId+"&token="+accessTokenData,
                        error: function(jqXHR, textStatus, errorThrown){
                        $(".egpTenderIdErrorMessage").text("Something went wrong while trying to pull human resource data from e-GP");
                        $(".egpTenderIdErrorMessage").show();
                        },
                        success: function(data){    
                            if(data.length>0)
                            {

                                var col=1;
                                var tier;
                                var paPosition;
                                var paQualification;
                                var paScore;
                                var bCID;
                                var bName;
                                var bQualification;
                                var companyName;
                                for(var i =0;i<data.length;i++)
                                {
                                    
                                    companyName = data[i].companyName;
                                    tier = data[i].valuePA;
                                    i++;
                                    paPosition = data[i].valuePA;  
                                    i++;
                                    paQualification = data[i].valuePA;  
                                    i++;
                                    paScore = data[i].valuePA;  
                                    i++;   
                                    bCID = data[i].valueBidder;  
                                    i++;
                                    bName = data[i].valueBidder;  
                                    
                                    
                                    <?php $randomKey = randomString(); ?>


                                    
                                    $("#hrCriteria").append(
                                    // "<input type='text' name='etlcriteriahumanresource[<?=$randomKey?>"+i+"][EtlTierId]' value='"+tier+"---tier'>"+
                                    "<input type='text' name='etlcriteriahumanresource[<?=$randomKey?>"+i+"][EtlTierId]' value='"+tier+"'>"+
                                    "<input type='text' name='etlcriteriahumanresource[<?=$randomKey?>"+i+"][companyName]' value='"+companyName+"'>"+
                                    // "<input type='text' name='etlcriteriahumanresource[<?=$randomKey?>"+i+"][CmnDesignationId]' value='"+paPosition+"-paPosition'>"+
                                    // "<input type='text' name='etlcriteriahumanresource[<?=$randomKey?>"+i+"][CmnDesignationId]' value='"+paPosition+"-paPosition'>"+
                                    "<input type='text' name='etlcriteriahumanresource[<?=$randomKey?>"+i+"][Qualification]' value='"+paQualification+"'>"+   
                                    "<input type='text' name='etlcriteriahumanresource[<?=$randomKey?>"+i+"][Points]' value='"+paScore+"'>"+
                                    "<input type='text' name='etlcriteriahumanresource[<?=$randomKey?>"+i+"][CIDNo]' value='"+bCID+"'>"+
                                    "<input type='text' name='etlcriteriahumanresource[<?=$randomKey?>"+i+"][Name]' value='"+bName+"'>"+
                                    "<input type='text' name='etlcriteriahumanresource[<?=$randomKey?>"+i+"][CmnCountryId]' value='8f897032-c6e6-11e4-b574-080027dcfac6'>"+
                                    "<input type='text' name='etlcriteriahumanresource[<?=$randomKey?>"+i+"][CmnDesignationId]' value='"+paPosition+"'>"
                                    // "<input type='text' name='etlcriteriahumanresource[<?=$randomKey?>"+i+"][Qualification]' value='"+bQualification+"-bQualification'>"
                                    );
                                }
                            }
                            else
                            {
                                $(".egpTenderIdErrorMessage").text("Something went wrong while trying to pull human resource  data from e-GP");
                                $(".egpTenderIdErrorMessage").show();
                            }
                        }

                        });
                    }
                    });

                    }
                    else
                    {
                        alert("Duplicate Tender Id");
                    }
                }
            });
        }
        else
        {
            $(".egpTenderIdErrorMessage").text("This field is required!");
            $(".egpTenderIdErrorMessage").show();
        }
    }
    function resetForm()
    {

        $("#TenderId").attr('readonly',false);
        $('input[type=text]').val('');
        $('#DescriptionOfWork').data("wysihtml5").editor.clear() 
        $('select').val(''); 
        $('input[type=select]').val('');
        $('input[type=radio]').val('');
        $('input[type=checkbox]').val(''); 
    }

</script> 
@stop