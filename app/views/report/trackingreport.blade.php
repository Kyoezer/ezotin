@extends('master')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
			<i class="fa fa-cogs"></i>Tracking Report {{$workId?"($workId)":''}} &nbsp;&nbsp;@if(!Input::has('export')) <?php $parameters['export'] = 'print'; ?><a href="{{route('etoolrpt.'.Request::segment(2),$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		</div>
	</div>
	<div class="portlet-body flip-scroll">
        @if(Input::get('export') != 'print')
            {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
            <div class="form-body">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Work Id</label>
                        <input type="text" name="WorkId" value="{{Input::get('WorkId')}}" class="form-control" />
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="control-label">&nbsp;</label>
                    <div class="btn-set">
                        <button type="submit" class="btn blue-hoki btn-sm">Search</button>
                        <a href="{{Request::url()}}" class="btn grey-cascade btn-sm">Clear</a>
                    </div>
                </div>
            </div>
            {{--END--}}
            {{Form::close()}}
        @endif
        @if(Input::has('WorkId'))
        <h4 class="bold">Equipment Details</h4>
		<table class="table table-bordered table-striped table-condensed flip-content" id="contractorhumanresource">
			<thead class="flip-content">
				<tr>
                    <th>Sl#</th>
					<th class="order">
						 CDB No.
					</th>
					<th>
						Tier
					</th>
					<th class="">
						 Equipment
					</th>
					<th class="">
						 RegistrationNo
					</th>
					<th>
						Hired/Owned
					</th>
					<th>
						Points
					</th>
					<th class="">
						 Operation
					</th>
					<th class="">
						Performed By
					</th>
                    <th class="">
                        Update Time
                    </th>
				</tr>
			</thead>
			<tbody>
            <?php $slNo = 1; ?>
            @forelse($equipmentDetails as $equipmentDetail)
				<tr>
                    <td>{{$slNo++}}</td>
                    <td>{{$equipmentDetail->CDBNo}}</td>
                    <td>{{$equipmentDetail->Tier}}</td>
                    <td>{{$equipmentDetail->Equipment}}</td>
                    <td>{{$equipmentDetail->RegistrationNo}}</td>
                    <td>{{$equipmentDetail->OwnedOrHired}}</td>
                    <td>{{$equipmentDetail->Points}}</td>
                    <td>{{$equipmentDetail->Operation}}</td>
                    <td>{{$equipmentDetail->PerformedBy}}</td>
                    <td>{{date_format(date_create($equipmentDetail->OperationTime),'d/m/Y G:i:s')}}</td>
				</tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center font-red">No data to display!</td>
                </tr>
            @endforelse
			</tbody>
		</table>

        <h4 class="bold">Human Resource Details</h4>
        <table class="table table-bordered table-striped table-condensed flip-content" id="contractorhumanresource">
            <thead class="flip-content">
            <tr>
                <th>Sl#</th>
                <th class="order">
                    CDB No.
                </th>
                <th>
                    Tier
                </th>
                <th>
                    CID No.
                </th>
                <th class="">
                    Designation
                </th>
                <th class="">
                    Name
                </th>
                <th class="">
                    Qualification
                </th>
                <th>
                    Points
                </th>
                <th class="">
                    Operation
                </th>
                <th class="">
                    Performed By
                </th>
                <th class="">
                    Update Time
                </th>
            </tr>
            </thead>
            <tbody>
            <?php $slNo=1; ?>
            @forelse($hrDetails as $hrDetail)
                <tr>
                    <td>{{$slNo++}}</td>
                    <td>{{$hrDetail->CDBNo}}</td>
                    <td>{{$hrDetail->Tier}}</td>
                    <td>{{$hrDetail->CIDNo}}</td>
                    <td>{{$hrDetail->Name}}</td>
                    <td>{{$hrDetail->HRName}}</td>
                    <td>{{$hrDetail->Qualification}}</td>
                    <td>{{$hrDetail->Points}}</td>
                    <td>{{$hrDetail->Operation}}</td>
                    <td>{{$hrDetail->PerformedBy}}</td>
                    <td>{{date_format(date_create($hrDetail->OperationTime),'d/m/Y G:i:s')}}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center font-red">No data to display!</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <h4 class="bold">Equipment Check Details</h4>
        <table class="table table-bordered table-striped table-condensed flip-content" id="contractorhumanresource">
            <thead class="flip-content">
            <tr>
                <th>Sl#</th>
                <th>
                    Registration No
                </th>
                <th class="order">
                    CDB No.
                </th>
                <th class="">
                    Operation
                </th>
                <th class="">
                    Performed By
                </th>
                <th class="">
                    Update Time
                </th>
            </tr>
            </thead>
            <tbody>
            <?php $slNo =1; ?>
            @forelse($engagedEquipmentDetails as $engagedEquipmentDetail)
                <tr>
                    <td>{{$slNo++}}</td>
                    <td>{{$engagedEquipmentDetail->RegistrationNo}}</td>
                    <td>{{$engagedEquipmentDetail->CDBNo}}</td>
                    <td>{{$engagedEquipmentDetail->Operation}}</td>
                    <td>{{$engagedEquipmentDetail->PerformedBy}}</td>
                    <td>{{date_format(date_create($engagedEquipmentDetail->OperationTime),'d/m/Y G:i:s')}}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center font-red">No data to display!</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <h4 class="bold">Human Resource Check Details</h4>
        <table class="table table-bordered table-striped table-condensed flip-content" id="contractorhumanresource">
            <thead class="flip-content">
            <tr>
                <th>Sl#</th>
                <th>
                    CID No.
                </th>
                <th class="order">
                    CDB No.
                </th>
                <th class="">
                    Operation
                </th>
                <th class="">
                    Performed By
                </th>
                <th class="">
                    Update Time
                </th>
            </tr>
            </thead>
            <tbody>
            <?php $slNo = 1; ?>
            @forelse($engagedHRDetails as $engagedHRDetail)
                <tr>
                    <td>{{$slNo++}}</td>
                    <td>{{$engagedHRDetail->CIDNo}}</td>
                    <td>{{$engagedHRDetail->CDBNo}}</td>
                    <td>{{$engagedHRDetail->Operation}}</td>
                    <td>{{$engagedHRDetail->PerformedBy}}</td>
                    <td>{{date_format(date_create($engagedHRDetail->OperationTime),'d/m/Y G:i:s')}}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center font-red">No data to display!</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        <h4 class="bold">Bid details Track</h4>
        <table class="table table-bordered table-striped table-condensed flip content">
            <thead class="flip-content">
                <tr>
                    <th>Sl#</th>
                    <th>CDB No</th>
                    <th>Financial Bid Quoted</th>
                    <th>Credit Line</th>
                    <th>Performed By</th>
                    <th>Operation Time</th>
                </tr>
            </thead>
            <tbody>
                <?php $slNo = 1; ?>
                @forelse($trackBidDetails as $trackBidDetail)
                    <tr>
                        <td>{{$slNo++}}</td>
                        <td>{{$trackBidDetail->CDBNo}}</td>
                        <td>{{$trackBidDetail->FinancialBidQuoted}}</td>
                        <td>{{$trackBidDetail->CreditLine}}</td>
                        <td>{{$trackBidDetail->PerformedBy}}</td>
                        <td>{{convertDateTimeToClientFormat($trackBidDetail->OperationTime)}}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center font-red">No data to display!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <h4 class="bold">Process/ Reset Result /Award Track</h4>
        <table class="table table-bordered table-striped table-condensed flip-content" id="contractorhumanresource">
            <thead class="flip-content">
            <tr>
                <th>Sl#</th>
                <th>
                    Operation
                </th>
                <th class="">
                    Performed By
                </th>
                <th class="">
                    Update Time
                </th>
            </tr>
            </thead>
            <tbody>
            <?php $slNo = 1; ?>
            @forelse($evaluationDetails as $evaluationDetail)
                <tr>
                    <td>{{$slNo++}}</td>
                    <td>{{$evaluationDetail->Operation}}</td>
                    <td>{{$evaluationDetail->PerformedBy}}</td>
                    <td>{{date_format(date_create($evaluationDetail->OperationTime),'d/m/Y G:i:s')}}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center font-red">No data to display!</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <h4 class="bold">Tender Track</h4>
        <table class="table table-condensed table-striped table-bordered">
            <thead>
                <tr>
                    <th>Sl#</th>
                    <th>Column</th>
                    <th>Old Value</th>
                    <th>New Value</th>
                    <th>Action Date</th>
                </tr>
            </thead>
            <tbody>
                <?php $slNo = 1; ?>
                @foreach($auditTrailDetails as $auditTrailDetail)
                    <tr>
                        <td>{{$slNo++}}</td>
                        <td>{{preg_replace('/(?<!\ )[A-Z]/', ' $0', $auditTrailDetail->ColumnName)}}</td>
                        <td>{{$auditTrailDetail->OldValue}}</td>
                        <td>{{$auditTrailDetail->NewValue}}</td>
                        <td>{{convertDateTimeToClientFormat($auditTrailDetail->ActionDate)}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
	    @endif
    </div>
</div>
@stop