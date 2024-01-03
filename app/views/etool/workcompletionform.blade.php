@extends('horizontalmenumaster')
@section('content')
<div class="page-bar">
	<ul class="page-breadcrumb">
		<li>
			<a href="#">etool</a>
			<i class="fa fa-angle-right"></i>
		</li>
		<li>
			<a href="#">Work Completion Form</a>
		</li>
	</ul>
</div>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>Work Summary
		</div>
		<div class="tools">
			<a href="javascript:;" class="collapse"></a>
		</div>
	</div>
	<div class="portlet-body flip-scroll">
		<div class="row">
            @foreach($tenderDetails as $tenderDetail)
            <div class="col-md-12">
                <p><b>Name of Work:  </b>{{$tenderDetail->NameOfWork}}</p>
            </div>
			<div class="col-md-6">
				<table class="table table-bordered table-striped table-condensed flip-content">
					<tbody>
                        <tr>
                            <td><strong>Work Id</strong></td>
                            <td>{{$tenderDetail->WorkId}}</td>
                        </tr>
						<tr>
							<td><strong>Name of Contractor</strong></td>
							<td>{{$tenderDetail->Contractor}}</td>
						</tr>
                        <tr>
                            <td><strong>Contract Period (in Months)</strong></td>
                            <td>{{$tenderDetail->ContractPeriod}}</td>
                        </tr>
					</tbody>
				</table>
			</div>
			<div class="col-md-6">
				<table class="table table-bordered table-striped table-condensed flip-content">
					<tbody>
						<tr>
							<td><strong>Contract Description</strong></td>
							<td>{{html_entity_decode($tenderDetail->DescriptionOfWork)}}</td>
						</tr>
						<tr>
							<td><strong>Category of Work</strong></td>
							<td>{{$tenderDetail->Category}}</td>
						</tr>
						<tr>
							<td><strong>Class</strong></td>
							<td>{{$tenderDetail->Classification}}</td>
						</tr>
                        <tr>
                            <td><strong>EMD</strong></td>
                            <td>{{$tenderDetail->EMD}}</td>
                        </tr>
					</tbody>
				</table>
			</div>
            @endforeach
		</div>
	</div>
</div>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>Work Completion Form
		</div>
	</div>
	<div class="portlet-body flip-scroll">
        {{Form::open(array('url'=>'etl/etlworkcompletion','files'=>'true'))}}
        {{Form::hidden('Id',$tenderId)}}
        @foreach($completionDetails as $completionDetail)
		<div class="form-body">
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label">Status <span class="font-red">*</span></label>
						<select name="CmnWorkExecutionStatusId" class="form-control required workcompletionstatuscontrol">
							<option value="">---SELECT ONE---</option>
							@foreach($workStatuses as $workStatus)
                                <option value="{{$workStatus->Id}}" data-referenceno="{{$workStatus->ReferenceNo}}" @if($completionDetail->CmnWorkExecutionStatusId == $workStatus->Id)selected="selected"@endif>{{$workStatus->Name}}</option>
                            @endforeach
						</select>
					</div>
				</div>
            </div>
            <div class="workcompletedinfo @if(empty($completionDetail->CmnWorkExecutionStatusId) || $completionDetail->CmnWorkExecutionStatusId==CONST_CMN_WORKEXECUTIONSTATUS_TERMINATED){{'hide'}}@endif">
                <div class="row">
                <div class="col-md-3">
					<div class="form-group">
					<script>
							function checkDigit(inputId)
							{
								var totalValue = $("#"+inputId).val();
								if(totalValue<10000)
								{
									alert('Value cannot be less than 5 digit');
									$("#"+inputId).val('');
								}
								
							}
						</script>
						<label class="control-label">Contract Price (Initial) Nu. <span class="font-red">*</span></label>
						<input type="text" title="Amount should not be less than 5 digits" onchange="checkDigit(this.id)" name="ContractPriceInitial" id="ContractPriceInitial" class="form-control number required workstatuscompletedcontrol" value="{{$completionDetail->ContractPriceInitial or $tenderDetail->AwardedAmount}}" class="text-right">
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label">Contract Price (Final) Nu. <span class="font-red">*</span></label>
						<input type="text" title="Amount should not be less than 5 digits" onchange="checkDigit(this.id)" name="ContractPriceFinal" id="ContractPriceFinal" onchange="validateInitailFirnalPrice()" class="form-control number required workstatuscompletedcontrol" value="{{$completionDetail->ContractPriceFinal}}" class="text-right">
					</div>
				</div>
				<script>
					function validateInitailFirnalPrice()
					{
						var initialPrice = $("#ContractPriceInitial").val();
						var ContractPriceFinal = $("#ContractPriceFinal").val();
						var totalDifference = initialPrice - ContractPriceFinal;
						var reply = confirm('Total difference between initial and final price is  '+totalDifference+'. Are you sure?');
						if(reply){
							return true;
						}else{
							$("#ContractPriceFinal").val('');
							return false;
						}

					}
 
 

					function checkCompletionDate(event)
					{
						$("#dateCommencementMessage").addClass("hidden");
						var commencementDateFinal = $("#commencementDateFinal").val();
						var completionDateFinal = $("#completionDateFinal").val();

						if(commencementDateFinal!="" && completionDateFinal!="")   
						{   
							if ($.datepicker.parseDate('dd-mm-yy', commencementDateFinal) > $.datepicker.parseDate('dd-mm-yy', completionDateFinal)) {
								$("#dateCommencementMessage").removeClass("hidden");
								$("#completionDateFinal").val()="";
							}
						}

					}
				</script>
			{{--<div class="row">--}}
				<div class="col-md-3">
					<div class="form-group">
                        <label class="control-label">Date of Commencement (Official) <span class="font-red">*</span></label>
                        <div class="input-icon right">
                            <i class="fa fa-calendar"></i>
                            <input type="text" name="CommencementDateOfficial" id="commencementDateOfficial"   class="form-control required workstatuscompletedcontrol" value="{{$completionDetail->CommencementDateOfficial?convertDateToClientFormat($completionDetail->CommencementDateOfficial):convertDateToClientFormat($tenderDetail->ActualStartDate)}}" readonly>
                        </div>
					</div>
				</div>
				
				<div class="col-md-3">
					<div class="form-group">
                        <label class="control-label">Date of Commencement (Actual) <span class="font-red">*</span></label>
                        <div class="input-icon right">
                            <i class="fa fa-calendar"></i>
                            <input type="text" name="CommencementDateFinal" id="commencementDateFinal" onchange="checkCompletionDate()"   value="{{$completionDetail->CommencementDateFinal?convertDateToClientFormat($completionDetail->CommencementDateFinal):''}}" class="form-control datepicker required workstatuscompletedcontrol" readonly>
                        </div>
					</div>
				</div>
                </div>
                <div class="row">
				<div class="col-lg-12">
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Date of Completion (Official) <span class="font-red">*</span></label>
							<div class="input-icon right">
								<i class="fa fa-calendar"></i>
								<input type="text" name="CompletionDateOfficial" value="{{$completionDetail->CompletionDateOfficial?convertDateToClientFormat($completionDetail->CompletionDateOfficial):convertDateToClientFormat($tenderDetail->ActualEndDate)}}" class="form-control required workstatuscompletedcontrol" readonly>
							</div>
						</div>
					</div>
					
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Date of Completion (Actual) <span class="font-red">*</span></label>
							<div class="input-icon right">
								<i class="fa fa-calendar"></i>
								<input type="text" name="CompletionDateFinal"  id="completionDateFinal" onchange="checkCompletionDate()" class="form-control required datepicker workstatuscompletedcontrol" value="{{$completionDetail->CompletionDateFinal?convertDateToClientFormat($completionDetail->CompletionDateFinal):''}}" readonly>
							</div>
						</div>
						<div class="text-danger bold hidden" id="dateCommencementMessage">Date of completion cannot be less than date of commencement</div>
					</div>
					<script>
						function validateInitailFirnalDate()
						{
							var initialPrice = $("#ContractPriceInitial").val();
							var ContractPriceFinal = $("#ContractPriceFinal").val();
							var totalDifference = initialPrice - ContractPriceFinal;
							var reply = confirm('Total difference between initial and final price is  '+totalDifference+'. Are you sure?');
							if(reply){
								return true;
							}else{  
								$("#ContractPriceFinal").val('');
								return false;
							}

						}

					</script>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Ontime Completion (out of 30) <span class="font-red">*</span></label>
							<input type="text" class="form-control number range required workstatuscompletedcontrol" name="OntimeCompletionScore" value="{{$completionDetail->OntimeCompletionScore}}" data-min="0" data-max="30"/>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Quality of Execution (out of 70) <span class="font-red">*</span></label>
							<input type="text" class="form-control number range required workstatuscompletedcontrol" name="QualityOfExecutionScore" value="{{$completionDetail->QualityOfExecutionScore}}" data-min="0" data-max="70"/>
						</div>
					</div>
				</div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label"><input type="checkbox" id="ld-toggle" name="LDImposed" value="1" @if($completionDetail->LDImposed == 1)checked="checked"@endif/>LD Imposed</label>
                        <input type="text" placeholder="No. of days" class="form-control ld-input" @if($completionDetail->LDImposed != 1)disabled @endif name="LDNoOfDays" value="{{$completionDetail->LDNoOfDays or ''}}"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">&nbsp;</label>
                        <input type="text" placeholder="Amount" class="form-control ld-input" @if($completionDetail->LDImposed != 1)disabled @endif name="LDAmount" value="{{$completionDetail->LDAmount or ''}}"/>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label"><input type="checkbox" name="Hindrance" value="1" class="enable-input" @if($completionDetail->Hindrance == 1)checked="checked"@endif/>Hindrance</label>
                        <input type="text" placeholder="No. of days" class="form-control input" @if($completionDetail->Hindrance != 1)disabled @endif name="HindranceNoOfDays" value="{{$completionDetail->HindranceNoOfDays or ''}}"/>
                    </div>
                </div>
				<div class="col-md-3">
					<div class="form-group">
					<label class="control-label">APS Form <span class="font-red">*</span></label>
					<input type="file" name="APSForm" required="required" class="workstatuscompletedcontrol form-control input @if(!$isAdmin){{'required'}}@endif" />
					</div>
				</div>
            </div>
            </div>
            <div class="form-group">
                <label class="control-label">Remarks </label>
                <textarea name="Remarks" class="form-control">{{$completionDetail->Remarks}}</textarea>
            </div>
		</div>
        @endforeach
		<div class="form-actions">
			<div class="btn-set">
				<button type="submit" class="btn green" id="updateetoolstatus">Update</button>
                <a href="{{URL::to('etl/listofworksetool')}}" class="btn red">Cancel</a>
			</div>
		</div>
        {{Form::close()}}
	</div>
</div>
@stop