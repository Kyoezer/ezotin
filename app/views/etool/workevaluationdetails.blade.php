@extends('horizontalmenumaster')
@section('pagescripts')
    {{ HTML::script('assets/global/scripts/etool.js') }}
@stop
@section('content')
<div id="awardwork" class="modal fade" role="dialog" aria-labelledby="awardwork" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title bold font-green-seagreen">Award Contractor</h4>
			</div>
            <form action="{{URL::to('etl/awardcontractor')}}" method="POST" role="form">
			<div class="modal-body">
				<h4 class="bold">Are you sure you want to award this work to selected Contractor?</h4>
                @foreach($tenders as $tender)
                    <h5 class="bold">Work Id : <span class="font-green-seagreen ">{{$tender->WorkId}}</span></h5>
                    <h5 class="bold">Contractor(s) : <span class="font-green-seagreen contractor-name"></span></h5>
                    <p><span class="bold">Name : </span>{{$tender->NameOfWork}}</p>
                @endforeach
                    {{Form::hidden('Id')}}
                    {{Form::hidden('EtlTenderId',$tender->Id)}}
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Actual Start date</label>
								<div class="input-group date datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" name="ActualStartDate" value="{{date_format(date_create($tender->TentativeStartDate),'d-m-Y')}}" class="form-control input-sm" readonly>
									<span class="input-group-btn">
									<button class="btn default input-sm" type="button"><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Actual End date</label>
								<div class="input-group date datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" name="ActualEndDate" value="{{date_format(date_create($tender->TentativeEndDate),'d-m-Y')}}" class="form-control input-sm" readonly>
									<span class="input-group-btn">
									<button class="btn default input-sm" type="button"><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Awarded Amount</label>
								<input type="text" name="AwardedAmount" value="{{$tender->ProjectEstimateCost}}" class="form-control input-sm" />
							</div>
						</div>
					</div>
					<div class="form-group">
						<label>Remarks</label>
						<textarea name="Remarks" class="form-control" rows="3"></textarea>
					</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn green" >Award</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
            </form>
		</div>
	</div>
</div>
<div id="deletecontractor" class="modal fade" role="dialog" aria-labelledby="deletecontractor" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-warning">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title bold font-green-seagreen">Delete</h4>
			</div>
			<div class="modal-body">
                {{Form::hidden('Id')}}
                {{Form::hidden('TableName','etltenderbiddercontractor')}}
				<h4 class="bold">Are you sure you want to delete this Contractor?</h4>
				<form action="#" class="" role="form">
					<div class="form-group">
						<label>Remarks</label>
						<textarea class="form-control" rows="3"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button class="btn green" data-dismiss="modal" id="callToDelete">Delete</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div id="nonResponsiveContractor" class="modal fade" role="dialog" aria-labelledby="nonResponsiveContractor" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-warning">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title bold font-green-seagreen">Non-Response Contractor</h4>
			</div>
			{{Form::open(array('url'=>'etl/addNonResponsive'))}}

			{{-- {{ Form::open(array('action'=>'WorkCompletionFormEtool@postWorkCompletion','class'=>'form-horizontal')) }} --}}

			{{-- {{ Form::open(array('action'=>'etl@addNonResponsive','class'=>'form-horizontal')) }} --}}
			
			<input type="hidden" name="tenderId" value="{{$tenderId}}">
			<div class="modal-body" id="ContractorAdd">
					<div class="form-group">
						<label>CDB No.</label>
						<input type="text" class="form-control cdbno " name="cdbNo">
					</div>
					<div class="form-group">
						<label>Name of Firm</label>
						<input type="text" class="contractorName  form-control input-sm contractor-name" readonly name="nameOfFirm">
					</div>
			</div>
			<div class="modal-footer">
				<button class="btn green" type="submit" >Save</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
			{{ Form::close() }}

		</div>
	</div>
</div>

<div id="cancelTender" class="modal fade" role="dialog" aria-labelledby="cancelTender" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-warning">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title bold font-green-seagreen">Tender Cancel</h4>
			</div>
			{{Form::open(array('url'=>'etl/cancelTender'))}}

			<input type="hidden" name="tenderId" value="{{$tenderId}}">
			<div class="modal-body">
					<div class="form-group">
						<label>Remarks <label class="text-danger">*</label></label>
						<textarea required="required" class="form-control" name="remarks"></textarea>
					</div>
			</div>
			<div class="modal-footer">
				<button class="btn green" type="submit" onclick="validateCancelTender()">Submit</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Close</button>
			</div>
			{{ Form::close() }}

		</div>
	</div>
</div>
<div id="deleteNonResponsiveContractor" class="modal fade" role="dialog" aria-labelledby="deleteNonResponsiveContractor" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-warning">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title bold font-green-seagreen">Delete Non Responsive Contractor</h4>
			</div>
			{{Form::open(array('url'=>'etl/deleteNonResponsive'))}}
			<input type="hidden" name="tenderId" value="{{$tenderId}}">
			<input type="hidden" name="nonResponsiveId" id="nonResponsiveId" >
			<div class="modal-body">
					<div class="form-group">
						<h4>Are you sure you want to delete this Contractor?</h4>
					</div>
			</div>
			<div class="modal-footer">
				<button class="btn green" type="submit" onclick="validateCancelTender()">Yes</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">No</button>
			</div>
			{{ Form::close() }}

		</div>
	</div>
</div>
<div class="page-bar">
	<ul class="page-breadcrumb">
		<li>
			<a href="#">E-TOOL</a>
			<i class="fa fa-angle-right"></i>
		</li>
		<li>
			<a href="#">Details</a>
		</li>
	</ul>
</div>
@if($tenderStatus == 'Cancelled')
<div style="    background: #b4b1b18c;
    color: #b20a0a;
    padding: 8px 22px;
    border-left: 7px solid red;">
		<h3 style="font-weight: bold;">This Tender Is Cancelled</h3>
		<h4 style="font-weight: bold;font-size: 16px;">Cancelled On : {{convertDateToClientFormat($CancelledDate)}}</h4>
		<h4 style="font-weight: bold;font-size: 16px;">Reason for cancellation : {{$CancelledRemarks}}</h4>
</div>
@endif
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>Work Details
		</div>
	</div>
	<?php $checkAwarded = false;?>
	<div class="portlet-body flip-scroll">
		<div class="form-body">
            @forelse($tenders as $tender)
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered table-striped table-condensed flip-content">
                            <tbody>
	                            <tr>
	                                <td width="30%"><strong>Work Id</strong></td>
	                                <td>{{$tender->WorkId}} </td>
	                            </tr>
	                            <tr>
	                                <td width="30%"><strong>Name of Work</strong></td>
	                                <td>{{$tender->NameOfWork}}</td>
	                            </tr>
	                            <tr>
                                    <td><strong>Contract Description</strong></td>
                                    <td>{{html_entity_decode($tender->DescriptionOfWork)}}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tentative Start Date</strong></td>
                                    <td>{{convertDateToClientFormat($tender->TentativeStartDate)}}</td>
                                </tr>
                                <tr>
                                    <td><strong>Project Cost Estimate (Nu.)</strong></td>
                                    <td>{{number_format($tender->ProjectEstimateCost,2)}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                    	<table class="table table-bordered table-striped table-condensed flip-content">
                    		<tbody>
                    			<tr>
	                                <td width="30%"><strong>Category of Work</strong></td>
	                                <td>{{$tender->Category}}</td>
	                            </tr>
	                            <tr>
	                                <td width="30%"><strong>Class</strong></td>
	                                <td>{{$tender->Classification}}</td>
	                            </tr>
	                            <tr>
	                                <td width="30%"><strong>Dzongkhag</strong></td>
	                                <td>{{$tender->Dzongkhag}}</td>
	                            </tr>
                                <tr>
                                    <td><strong>Tentative End Date</strong></td>
                                    <td>{{convertDateToClientFormat($tender->TentativeEndDate)}}</td>
                                </tr>
                                <tr>
                                    <td><strong>Method</strong></td>
                                    <td>@if($tender->Method=='LIMITED_ENQUIRY')
											Limited Enquiry
										@elseif($tender->Method=='OPEN_TENDER')
											Open Tender
										@elseif($tender->Method=='SINGLE_SOURCE')
											Single Source
										@else
											-
										@endif
									</td>
                                </tr>
                    		</tbody>
                    	</table>
                    </div>
                </div>
            @empty
            @endforelse
			<h3>List of Response Contractor</h3>
            <table class="table table-bordered table-striped table-condensed flip-content">
				<thead class="flip-content">
					<tr>
						<th width="3%">Sl#</th>
						<th>
							CDB No.
						</th>
						<th class="">
							 Contractor/Firm
						</th>
						<th>
							Joint Venture
						</th>
						<th>
							Classification
						</th>
						<th class="">
							 Category
						</th>
						
                        <th>
                            Total <br>Work In <br>Hand
                        </th>
						<th class="" width="8.8%">
							 Result
						</th>
						@if($tenderStatus != 'Cancelled')
						<th>
							Action
						</th>
						@endif
					</tr>
				</thead>
				<tbody>
                    <?php $count = 1; $rankCount = 1; $grandTotalArray = array(); $grandTotal = 0; ?>
					@if($scoreCount > 0)
						@foreach($contractors as $contractorScore)
							<?php
							
							//die($contractorScore->Score11.'////');
							$technicalScore = $contractorScore->Score1+$contractorScore->Score2+$contractorScore->Score3+$contractorScore->Score4+$contractorScore->Score5+$contractorScore->Score6;
							if($contractorScore->IsBhutaneseEmp=="Y")
							{
								//die($contractorScore->Score7+$contractorScore->Score11);
								$preferenceScore = ($contractorScore->Score7+$contractorScore->Score11)/10;	
							}
							else
							{
								//die('ffff');
								$preferenceScore = ($contractorScore->Score7+$contractorScore->Score8+$contractorScore->Score9)/10;	
							}			
											
							$financialScore = ($technicalScore>=$qualifyingScore)?(90 * ($lowestBid/$contractorScore->FinancialBidQuoted)):0;
							$grandTotal = ($technicalScore>=$qualifyingScore)?($financialScore + $preferenceScore):0;
							array_push($grandTotalArray,$grandTotal);
							//die($grandTotal);
							
							?>
						@endforeach
					@endif
					<?php rsort($grandTotalArray);  ?>
                    @forelse($contractors as $contractor)
						<?php
								if($scoreCount > 0):
									$technicalScore = $contractor->Score1+$contractor->Score2+$contractor->Score3+$contractor->Score4+$contractor->Score5+$contractor->Score6;
									//$technicalScore = $contractorScore->Score1+$contractorScore->Score2+$contractorScore->Score3+$contractorScore->Score4+$contractorScore->Score5+$contractorScore->Score6;
									if($contractor->IsBhutaneseEmp=="Y")
									{
										//die($contractorScore->Score7+$contractorScore->Score11);
										$preferenceScore = ($contractor->Score7+$contractor->Score11)/10;	
									}
									else
									{
										//die('ffff');
										$preferenceScore = ($contractor->Score7+$contractor->Score8+$contractor->Score9)/10;	
									}			
									//$preferenceScore = ($contractor->Score7+$contractor->Score8+$contractor->Score9)/10;
									$financialScore = ($technicalScore>=$qualifyingScore)?(90 * ($lowestBid/$contractor->FinancialBidQuoted)):0;
									$grandTotal = ($technicalScore>=$qualifyingScore)?($financialScore + $preferenceScore):0;
								endif;
						?>
                        <?php $notQualified = false; ?>
                        <tr>
                            <td data-id="{{$contractor->Id}}">
                                {{$count}}
                            </td>
                            <td>
                                {{$contractor->CDBNo}}
                            </td>
                            <td>
                                {{$contractor->NameOfFirm}}
                            </td>
                            <td>
                                {{($contractor->JointVenture == 1)?"Yes":"No"}}
                            </td>
                            <td>
                                {{$contractor->Classification}}
                            </td>
                            <td>
                                {{$contractor->Category}}
                            </td>
							<td>{{$contractor->totalWorkInHand}}</td>
                            <td>
                                @if(isset($contractor->Score10))
                                    @if($contractor->Score10 != NULL && $contractor->Score10 != 0)
                                        @if($contractor->ActualStartDate && $contractor->ActualEndDate && $contractor->AwardedAmount)
                                            <?php $checkAwarded = true;?>Awarded {{$grandTotal}}
                                        @else
											H{{array_search($grandTotal,$grandTotalArray) + 1 }}, {{number_format($grandTotal,4)}}
                                        @endif
                                    @else
										<?php unset($grandTotalArray[array_search($grandTotal,$grandTotalArray)]); ?>
                                        <?php $notQualified = true; ?>
                                        Not Qualified
                                    @endif
                                @else
									<?php unset($grandTotalArray[array_search($grandTotal,$grandTotalArray)]); ?>
                                    @if(!empty($contractor->ScoreId))
                                        <?php $notQualified = true; ?>
                                        Not Qualified
                                    @else
                                        Process
                                    @endif
                                @endif
                            </td>
							@if($tenderStatus != 'Cancelled')
                            <td>
                                @if($tender->CmnWorkExecutionStatusId != CONST_CMN_WORKEXECUTIONSTATUS_AWARDED && $tender->CmnWorkExecutionStatusId != CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED)
                                    @if(!isset($contractor->ScoreId))
                                        <a href="{{URL::to('etl/workevaluationaddcontractors/'.$tenderId.'/'.$contractor->Id)}}" class="btn btn-xs bg-green-haze editaction"><i class="fa fa-edit"></i> Edit</a>
                                    @else
										@if($notQualified == false)
											@if($contractor->totalWorkInHand<=8 &&  $tender->Classification == 'Large (L)' || $contractor->totalWorkInHand<=4 &&  $tender->Classification == 'Medium (M)')
                                        		<a href="#" class="btn btn-xs bg-purple-plum fetchcontractor"><i class="fa fa-edit"></i> Award</a>
                                        	@endif
										@endif
                                    @endif
                                @endif
                                <a href="{{URL::to('etl/workevaluationpointdetails/'.$tenderId.'/'.$contractor->Id)}}" class="btn btn-xs bg-blue-hoki" target="_blank"><i class="fa fa-edit"></i> Score Details</a>
                                @if($tender->CmnWorkExecutionStatusId != CONST_CMN_WORKEXECUTIONSTATUS_AWARDED && $tender->CmnWorkExecutionStatusId != CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED)
                                    <a href="#" class="delete-contractor btn btn-xs bg-red-sunglo"><i class="fa fa-edit"></i> Delete</a>
                                @endif
                            </td>
							@endif
                        </tr>
                        <?php $count++; ?>
                    @empty
                        <tr>
                            <td colspan="8" class="font-red text-center">No contractors added</td>
                        </tr>
                    @endforelse
				</tbody>
			</table>
			<br><br><br>
			<h3>List of Non-Response Contractor</h3>
			<table class="table table-bordered table-striped table-condensed flip-content">
				<thead class="flip-content">
					<tr>
						<th width="3%">Sl#</th>
						<th>
							CDB No.
						</th>
						<th class="">
							 Contractor/Firm
						</th>
						<th class="">
							 Action
						</th>
					</tr>
				</thead>
				<tbody>
                    <?php $count = 1;  ?>
                    @forelse($nonResponse as $row)
						<tr>
							<td>{{$count++}}</td>
							<td>{{$row->CdbNo}}</td>
							<td>{{$row->FirmName}}</td>
							<td>
								<a href="#deleteNonResponsiveContractor" onclick="deleteNonResponsive('{{$row->Id}}')" data-toggle="modal" class="btn red btn-sm"><i class="fa fa-trash-o"></i> Delete</a>
							</td>
						</tr>

					@endforeach
				</tbody>
			</table>
		</div>
		<div class="form-controls">
			<div class="btn-set">
				<a href="{{URL::to('etl/evaluationetool')}}" class="btn blue btn-sm"><i class="m-icon-swapleft m-icon-white"></i> Back to List</a>
				@if($tenderStatus != 'Cancelled')
					@if(!isset($contractor->ScoreId))
						<a href="{{URL::to('etl/workevaluationaddcontractors/'.$tenderId)}}" class="btn green btn-sm"><i class="fa fa-plus"></i> Add Contractors</a>
						<a href="#" class="nonResponsiveContractor btn blue-chambray btn-sm"><i class="fa fa-edit"></i> Add Non-Responsive Contractor</a>
						<a href="#" class="cancelTender btn red btn-sm"><i class="fa fa-times"></i> Cancel Tender</a>
					@endif
					@if($tender->CmnWorkExecutionStatusId != CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED)
					@if($scoreCount == 0)

						<a href="{{URL::to('etl/workevaluationprocessresult/'.$tenderId)}}" class="btn blue-chambray btn-sm">
						<i class="fa fa-cogs"></i> 
						Process Result</a>
					@endif
					@if($checkAwarded==false)
						<a href="{{URL::to('etl/workevaluationresetresult/'.$tenderId)}}" class="btn red btn-sm"><i class="fa fa-refresh"></i> Reset Result</a>
						@endif
					@endif
							@if($scoreCount > 0)
							@if($tender->Method=='OPEN_TENDER')
								<a href="{{URL::to('etl/etoolresultreport/'.$tenderId)}}" target="_blank" class="btn grey-cascade btn-sm"><i class="fa fa-book"></i> View Report</a>
							@elseif($tender->Method=='LIMITED_ENQUIRY' || $tender->Method=='SINGLE_SOURCE')
							<a href="{{URL::to('etl/etoolsmallworksreport/'.$tenderId)}}" target="_blank" class="btn grey-cascade btn-sm"><i class="fa fa-book"></i> View Report</a>
							@else
								<a href="{{URL::to('etl/etoolresultreport/'.$tenderId)}}" target="_blank" class="btn grey-cascade btn-sm"><i class="fa fa-book"></i> View Report</a>
							@endif
					@endif
				@endif
			</div>
		</div>

	</div>
</div>
<script>
	function deleteNonResponsive(rowId)
	{
		$("#nonResponsiveId").val(rowId);
	}
</script>
@stop