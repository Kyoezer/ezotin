@extends('reportsmaster')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel'; $route = 'specializedtraderpt.specializedtraderegistrationdetail';?>
            @if(!Input::has('export'))
            <i class="fa fa-cogs"></i>Specialized Trade Registration Details &nbsp;&nbsp;@if(!Input::has('export'))   <?php $parameters['export'] = 'print'; ?><a href="{{route($route,$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		    @endif
        </div>
	</div>
	<div class="portlet-body flip-scroll">
        @if(Input::get('export') != "print")
        {{Form::open(array('url'=>str_replace('.','/',$route),'method'=>'get'))}}
		<div class="form-body">
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">CDB No</label>
						<input type="text" name="SPNo" value="{{Input::get('SPNo')}}" class="form-control" />
					</div>
				</div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Name</label>
                        <input type="text" name="SPName" value="{{Input::get('SPName')}}" class="form-control"/>
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
					<th>
						Name
					</th>
                    <th>Application Date</th>
					<th class="">
						 Type
					</th>
                    <th class="">
                        Verified by
                    </th>
                    <th class="">
                        Approved by
                    </th>
                    <th>Payment Approved by</th>
                    <th>Rejected by</th>
				</tr>
			</thead>
			<tbody>
            @forelse($reportData as $data)
				<tr>
                    <td>{{$data->NameOfFirm}} ({{$data->SPNo}})</td>
                    <td>{{convertDateToClientFormat($data->ApplicationDate)}}</td>
                    <td>{{$data->Type}}</td>
                    <td>@if($data->Verifier){{$data->Verifier."<br/> on ".convertDateToClientFormat($data->RegistrationVerifiedDate)}}@endif</td>
                    <td>@if($data->Approver){{$data->Approver."<br/> on ".convertDateToClientFormat($data->RegistrationApprovedDate)}}@endif</td>
                    <td>@if($data->PaymentApprover){{$data->PaymentApprover."<br/> on ".convertDateToClientFormat($data->PaymentApprovedDate)}}@endif</td>
                    <td>@if($data->Rejector){{$data->Rejector."<br/> on ".convertDateToClientFormat($data->RejectedDate)}}@endif</td>
				</tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center font-red">@if(empty($reportData))No data to display! @else{{"Please select a Contractor or enter a CDB No"}}@endif</td>
                </tr>
            @endforelse
			</tbody>
		</table>
	</div>
</div>
@stop