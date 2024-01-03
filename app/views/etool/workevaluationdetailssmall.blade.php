@extends('horizontalmenumaster')
@section('pagescripts')
    {{ HTML::script('assets/global/scripts/etool.js') }}
    <script>
        if($('input[name="ShowModal"]').val() == 1){
            $("#addcontractorssmall").modal('show');
        }else{
            $('.cdbno').val('');
            $('.contractor-id').val('');
            $('input[name="FinancialBidQuoted"]').val('');
        }
    </script>
@stop
@section('content')
<?php $checkAwarded=false;?>
    <input type="hidden" name="URL" value="{{CONST_APACHESITELINK}}"/>
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
                        {{Form::hidden('WorkType','1')}}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Actual Start date</label>
                                    <div class="input-group date datepicker" data-date-format="dd-mm-yyyy">
                                        <input type="text" name="ActualStartDate" value="{{isset($tenders[0]->TentativeStartDate)?date_format(date_create($tender->TentativeStartDate),'d-m-Y'):''}}" class="form-control input-sm" readonly>
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
                                        <input type="text" name="ActualEndDate" value="{{isset($tenders[0]->TentativeEndDate)?date_format(date_create($tender->TentativeEndDate),'d-m-Y'):''}}" class="form-control input-sm" readonly>
									<span class="input-group-btn">
									<button class="btn default input-sm" type="button"><i class="fa fa-calendar"></i></button>
									</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Awarded Amount</label>
                                    <input type="text" name="AwardedAmount" value="{{isset($tenders[0]->ProjectEstimateCost)?$tenders[0]->ProjectEstimateCost:''}}" class="form-control input-sm" />
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
    
<div id="cancelTender" class="modal fade" role="dialog" aria-labelledby="cancelTender" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-warning">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title bold font-green-seagreen">Tender Cancel</h4>
			</div>
			{{Form::open(array('url'=>'etl/cancelSmallTender'))}}

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
    <div id="deletecontractor" class="modal fade" role="dialog" aria-labelledby="deletecontractor" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title bold font-green-seagreen">Delete Contractor</h4>
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
    <div id="addcontractorssmall" class="modal fade" role="dialog" aria-labelledby="deletecontractor" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title bold font-green-seagreen">Add Contractor</h4>
                </div>
                {{Form::open(array('url'=>'etl/etlsaveaddcontractor'))}}
                {{Form::hidden('Id',(isset($bidContractors[0])?$bidContractors[0]->Id:''))}}
                {{Form::hidden('Source','1')}}
                {{Form::hidden('EtlTenderId',$tenderId)}}
                {{Form::hidden('CurrentTab','XX')}}
                {{Form::hidden('ShowModal',$showModal)}}
                <div class="modal-body">
                    <table class="table table-bordered" id="ContractorAdd">
                        <tbody>
                            <tr>
                                {{--<td class="col-md-4">--}}
                                    {{--<input type="text" class="form-control input-sm cdbno required" value="--}}{{--{{$contList->CDBNo}}--}}{{--" placeholder="CDB No.">--}}
                                {{--</td>--}}
                                {{--<td>--}}
                                    {{--<select name="Contractor[AAAAA][CrpContractorFinalId]" class="form-control input-sm required resetKeyForNew">--}}
                                        {{--<option value="">---SELECT ONE---</option>--}}
                                        {{--@foreach($allContractors as $allContractor)--}}
                                        {{--<option value="{{$allContractor->Id}}" --}}{{--@if($contractor->Id == $contList->CrpContractorFinalId)selected="selected"@endif--}}{{-- data-cdbno="{{$allContractor->CDBNo}}">{{$allContractor->NameOfFirm}}</option>--}}
                                        {{--@endforeach--}}
                                    {{--</select>--}}
                                {{--</td>--}}

                                <td  class="col-md-4">
                                    <input type="text" class="form-control input-sm cdbno required" value="{{isset($bidContractors[0])?$bidContractors[0]->CDBNo:''}}" placeholder="CDB No.">
                                </td>
                                <td>
                                    <input type="hidden" name="Contractor[AAAAA][CrpContractorFinalId]" value="{{isset($bidContractors[0])?$bidContractors[0]->CrpContractorFinalId:''}}" class="contractor-id"/>
                                    <input type="text" class="contractorName required form-control input-sm contractor-name" value="{{isset($bidContractors[0])?$bidContractors[0]->NameOfFirm:''}}" readonly="readonly"/>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Financial Bid Quoted <span class="font-red">*</span></label>
                                <input type="text" name="FinancialBidQuoted" value="{{$bidContractors[0]->FinancialBidQuoted or ''}}" class="form-control required input-sm" placeholder="Financial Bid Quoted">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn green" >Save</button>
                    <button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
                </div>
                {{Form::close()}}
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
        <div class="portlet-body flip-scroll">
            <div class="form-body">
                @foreach($tenders as $tender)
                    <h4 class="bold">Work Id : <span class="font-green-seagreen ">{{$tender->WorkId}}</span></h4>
                    <p><span class="bold">Name : </span>{{$tender->NameOfWork}}</p>
                    <p><span class="bold">Description : </span> {{html_entity_decode($tender->DescriptionOfWork)}}</p>
                    <p><span class="bold">Estimated Project Cost (Nu.): </span> {{number_format($tender->ProjectEstimateCost,2)}}</p>
                @endforeach
                <table class="table table-bordered table-striped table-condensed flip-content">
                    <thead class="flip-content">
                    <tr>
                        <th width="3%">Sl No.</th>
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
                        <th class="" width="8.8%">
                            Result
                        </th>
                        <th>
                            Total <br>Work In <br>Hand
                        </th>
                        @if($tenderStatus != 'Cancelled')
						<th>
							Action
						</th>
						@endif
                    </tr>
                    </thead>
                    <tbody>
                    <?php $count = 1; $rankCount = 1; ?>
                    @forelse($contractors as $contractor)
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
                            <td>
                                @if(isset($contractor->Score10))
                                    @if($contractor->Score10 != NULL)
                                        @if($contractor->ActualStartDate)
                                            Awarded<?php $checkAwarded=true;?>
                                        @else
                                            L{{$contractor->Score10}}
                                        @endif
                                        <?php $rankCount++; ?>
                                    @else
                                        Not Qualified
                                    @endif
                                @else
                                        Process
                                @endif
                            </td>
                            <td>{{$contractor->totalWorkInHand}}</td>
                            @if($tenderStatus != 'Cancelled')
                            <td>
                                
                                    @if(!isset($contractor->ScoreId))
                                        <a href="{{URL::to('etl/smallWorkevaluationaddcontractors/'.$tenderId.'/'.$contractor->Id)}}" class="btn btn-xs bg-green-haze editaction"><i class="fa fa-edit"></i> Edit</a>
                                    @else
                                        @if($contractor->totalWorkInHand<=2)
                                            @if($tender->CmnWorkExecutionStatusId != CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                                            <a href="#" class="btn btn-xs bg-purple-plum fetchcontractor"><i class="fa fa-edit"></i> Award</a>
                                            @endif
                                        @endif
                                    @endif
                                    {{--<a href="#" class="btn btn-xs bg-purple-plum fetchcontractor"><i class="fa fa-edit"></i> Award</a>--}}
                                    {{--<a href="{{URL::to('etl/workevaluationpointdetails/'.$tenderId.'/'.$contractor->Id)}}" class="btn btn-xs bg-blue-hoki" target="_blank"><i class="fa fa-edit"></i> Score Details</a>--}}
                                    @if($tender->CmnWorkExecutionStatusId != CONST_CMN_WORKEXECUTIONSTATUS_AWARDED && $tender->CmnWorkExecutionStatusId != CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED)
                                        <a href="#" class="delete-contractor btn btn-xs bg-red-sunglo"><i class="fa fa-edit"></i> Delete</a>
                                    @endif
                            </td>
                            @endif
                        </tr>
                        <?php $count++; ?>
                    @empty
                        <tr>
                            <td colspan="8">No contractors added</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="form-controls">
            @if($tenderStatus != 'Cancelled')
                <div class="btn-set">
                    @if(!isset($contractor->ScoreId))
                    <a href="{{URL::to('etl/workevaluationsmallprocessresult/'.$tenderId)}}" class="btn blue-chambray btn-sm"><i class="fa fa-cogs"></i> Process Result</a>
                    <a href="{{URL::to('etl/smallWorkevaluationaddcontractors/'.$tenderId)}}" class="btn green btn-sm"><i class="fa fa-plus"></i> Add Contractors</a>
                    <a href="#" class="cancelTender btn red btn-sm"><i class="fa fa-times"></i> Cancel Tender</a>
                    {{-- <a href="#addcontractorssmall" data-toggle="modal" class="btn green btn-sm"><i class="fa fa-plus"></i> Add Contractors</a> --}}
                    @endif
                    @if($tender->CmnWorkExecutionStatusId != CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED && $checkAwarded==false)
                        
                        <a href="{{URL::to('etl/workevaluationsmallresetresult/'.$tenderId)}}" class="btn red btn-sm"><i class="fa fa-refresh"></i> Reset Result</a>
                    @endif
                    @if($scoreCount > 0)
                        <a href="{{URL::to('etl/etoolsmallworksreport/'.$tenderId)}}" target="_blank" class="btn grey-cascade btn-sm"><i class="fa fa-book"></i> View Report</a>
                    @endif
                </div>
            @endif
            </div>
        </div>
    </div>
    <a href="{{URL::to('etl/evaluationetool')}}" class="btn blue"><i class="m-icon-swapleft m-icon-white"></i>&nbsp;Back</a>
@stop