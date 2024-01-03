@extends('reportsmaster')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
            @if(!Input::has('export'))
            <i class="fa fa-cogs"></i>Consultant Application Details &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route("consultantrpt.consultantdetail",$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel </a>   <?php $parameters['export'] = 'print'; ?><a href="{{route("consultantrpt.consultantdetail",$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		    @endif
        </div>
	</div>
	<div class="portlet-body flip-scroll">
        @if(Input::get('export') != "print")
        {{Form::open(array('url'=>'consultantrpt/consultantdetail','method'=>'get'))}}
		<div class="form-body">
        <div class="row">
      
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">CDB No</label>
						<input type="text" name="CDBNo" value="{{Input::get('CDBNo')}}" class="form-control" />
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
						Firm/Name
					</th>
                    <th>
						CDB No.
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
            @forelse($consultantLists as $consultant)
                <tr>
        
                    <td>{{$start++}}</td>
                    <td>{{$consultant->ReferenceNo}} <br> {{$consultant->ApplicationDate}}</td>
                    <td>{{$consultant->NameOfFirm}} </td>
                    <td>{{$consultant->CDBNo}} </td>
                    <td>{{$consultant->ServiceType}}</td>
                    <td>{{$consultant->CurrentStatus}}</td>
              
                    <td>{{$consultant->VerifiedBy}} <br>  {{$consultant->ApplicationDate}}</td>
                    <td>{{$consultant->AprroverBy}} <br> {{$consultant->RegistrationVerifiedDate}}</td>
                    <td>{{$consultant->PaymentApprover}}  <br>{{$consultant->RegistrationApprovedDate}}</td>
                    <td>{{$consultant->RejectedBy}} <br> {{$consultant->RejectedDate}}</td>
                    <td>{{$consultant->RemarksByRejector}}</td>
                    <td>{{$consultant->CurrentlyPickedBy}}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="14" class="text-center font-red">No data to display!</td>
                </tr>
            @endforelse
            </tbody>
		</table>
            <?php pagination($noOfPages,Input::all(),Input::get('page'),"consultantrpt.consultantdetail"); ?>
	</div>
</div>
@stop