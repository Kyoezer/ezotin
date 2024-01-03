@extends('reportsmaster')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
            @if(!Input::has('export'))
            <i class="fa fa-cogs"></i>Surveyor Application Details &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route("surveyorrpt.surveyordetail",$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel </a>   <?php $parameters['export'] = 'print'; ?><a href="{{route("surveyorrpt.surveyordetail",$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		    @endif
        </div>
	</div>
	<div class="portlet-body flip-scroll">
        @if(Input::get('export') != "print")
        {{Form::open(array('url'=>'surveyorrpt/surveyordetail','method'=>'get'))}}
		<div class="form-body">
        <div class="row">
      
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">AR No</label>
						<input type="text" name="ARNo" value="{{Input::get('ARNo')}}" class="form-control" />
					</div>
				</div>

       
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">Reference No</label>
						<input type="text" name="ReferenceNo" value="{{Input::get('ReferenceNo')}}" class="form-control" />
					</div>
				</div>
       <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Application Status</label>
                        <select class="form-control" name="CurrentStatus">
                            <option value="">---SELECT ONE---</option>
                            @foreach($statuses as $status)
                                <option value="{{$status->Name}}" @if($status->Name == Input::get('Status'))selected="selected"@endif>{{$status->Name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
        <div class="col-md-2">
                    <label class="control-label">From</label>
                    <div class="input-icon right">
                        <i class="fa fa-calendar"></i>
                        <input type="text" name="FromDate" class="form-control datepicker" value="{{Input::get('FromDate')}}" readonly="readonly" placeholder="">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="control-label">To</label>
                    <div class="input-icon right">
                        <i class="fa fa-calendar"></i>
                        <input type="text" name="ToDate" class="form-control datepicker" value="{{Input::has('ToDate')?Input::get('ToDate'):date('d-m-Y')}}" readonly="readonly" placeholder="">
                    </div>
                </div>

      
				
                @if(!Input::has('export'))
                    <div class="col-md-2">
                        <label class="control-label">&nbsp;</label>
                        <div class="btn-set">
                            <button type="submit" class="btn blue-hoki btn-sm">Search</button>
                            <a href="{{Request::url()}}" class="btn grey-cascade btn-sm">Clear</a>
                        </div>
                    </div>
                @endif
			</div>
		</div>
        {{Form::close()}}
        @else
            @foreach($parametersForPrint as $key=>$value)
                <b>{{$key}}: {{$value}}</b><br>
            @endforeach
            <br/>
        @endif
		<table class="table table-bordered table-striped table-condensed flip-content" id="contractorhumanresource">
            <thead class="flip-content">
            <tr>
                    <th>Sl #</th>
                    <th>Application</th>
					<th>
						Name
					</th>
                    <th>
						AR No.
					</th>
					<th class="">
						 Type
					</th>
                    <th>Current Status</th>
                    <th class="">
                        Verified by
                    </th>
                    <th class="">
                        Approved by
                    </th>
                    <th>Payment Approved by</th>
                    <th>Rejected by</th>
                    <th>Remarks By Rejector</th>
                    <th>Currently Picked By</th>
				</tr>
            </thead>
            <tbody>
            @forelse($surveyLists as $surveyor)
                <tr>
        
                    <td>{{$start++}}</td>
                    <td>{{$surveyor->ReferenceNo}} <br> {{$surveyor->ApplicationDate}}</td>
                    <td>{{$surveyor->Name}} </td>
                    <td>{{$surveyor->ARNo}} </td>
                    <td>{{$surveyor->ServiceType}}</td>
                    <td>{{$surveyor->CurrentStatus}}</td>
              
                    <td>{{$surveyor->VerifiedBy}} <br> {{$surveyor->RegistrationVerifiedDate}}</td>
                    <td>{{$surveyor->AprroverBy}} <br> {{$surveyor->RegistrationApprovedDate}}</td>
                    <td>{{$surveyor->PaymentApprover}} <br> {{$surveyor->RegistrationPaymentApprovedDate}}</td>
                    <td>{{$surveyor->RejectedBy}} <br> {{$surveyor->RejectedDate}}</td>
                    <td>{{$surveyor->RemarksByRejector}}</td>
                    <td>{{$surveyor->CurrentlyPickedBy}}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="14" class="text-center font-red">No data to display!</td>
                </tr>
            @endforelse
            </tbody>
		</table>
            <?php pagination($noOfPages,Input::all(),Input::get('page'),"surveyorrpt.surveyordetail"); ?>
	</div>
</div>
@stop