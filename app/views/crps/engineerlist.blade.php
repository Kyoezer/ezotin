@extends('master')
@section('lastcdbno')
<div class="page-toolbar">
	<div id="dashboard-report-range" class="pull-right tooltips btn btn-fit-height purple-plum" data-placement="top" data-original-title="Change dashboard date range">
		<span class="thin visible-lg-inline-block">Last used CDB No.: <span class="bold">7898</span></span>
	</div>
</div>
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption font-green-seagreen">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">{{$pageTitle}}</span>
		</div>
	</div>
	<div class="portlet-body">
		<div class="form-body">
			{{ Form::open(array('url' =>Request::URL(),'role'=>'form','method'=>'get')) }}
			<div class="row">

	<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">CDB No.</label>
						<input type="text" name="CDBNo" class="form-control" value="{{$CDBNo}}">
					</div>
				</div>


				<div class="col-md-2">
					<div class="form-group">



						<label class="control-label">Engineer Type</label>
						<select name="CmnServiceSectorTypeId" class="form-control">
							<option value="">---SELECT ONE---</option>
							@foreach($serviceSectorTypes as $serviceSectorType)
								<option value="{{$serviceSectorType->Id}}" @if($serviceSectorTypeId==$serviceSectorType->Id)selected="selected"@endif>{{$serviceSectorType->Name}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">Trade</label>
						<select name="CmnTradeId" class="form-control">
							<option value="">---SELECT ONE---</option>
							@foreach($trades as $trade)
								<option value="{{$trade->Id}}" @if($tradeId==$trade->Id)selected="selected"@endif>{{$trade->Name}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label">Name</label>
						<select name="CrpEngineerId" class="form-control select2me">
							<option value="">---SELECT ONE---</option>
							@foreach($engineerLists as $engineerList)
								<option value="{{$engineerList->Id}}" @if($engineerId==$engineerList->Id)selected="selected"@endif>{{$engineerList->EngineerName}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">No. of Rows</label>
                        {{Form::select('Limit',array(10=>10,20=>20,30=>30,'All'=>'All'),Input::has('Limit')?Input::get('Limit'):20,array('class'=>'form-control'))}}
                    </div>
                </div>
				<div class="col-md-2">
					<div class="btn-set">
						<button type="submit" class="btn blue-hoki btn-sm">Search</button>
						<a href="{{Request::URL()}}" class="btn grey-cascade btn-sm">Clear</a>
					</div>
				</div>
			</div>
		</div>
		{{Form::close()}}<br />
		<div class="table-responsive">
			<table class="table table-bordered table-striped table-condensed">
				<thead>
					<tr>
					
						<th>
							 CDB No.
						</th>
						<th>
							Engineer Type
						</th>
						<th>
							Trade
						</th>
						<th>
							 Name
						</th>
						<th>
							CID/Work Permit No.
						</th>
						<th>
							 Country
						</th>
						<th>
							 Dzongkhag
						</th>
						<th>
							Mobile#
						</th>
						<th>
							Email
						</th>
<th>Status</th>
						<!-- <th>
							Certificate
						</th> -->
						<th>
							Info
						</th>
					</tr>
				</thead>
				<tbody>
					@forelse($engineerLists as $engineerList)
					<tr>
					
						<td>
							{{$engineerList->CDBNo}}
						</td>
						<td>
							{{$engineerList->EngineerType}}
						</td>
						<td>
							{{$engineerList->Trade}}
						</td>
						<td>
							{{$engineerList->EngineerName}}
						</td>
						<td>
							{{$engineerList->CIDNo}}
						</td>
						<td>
							 {{$engineerList->Country}}
						</td>
						<td>
							 {{$engineerList->Dzongkhag}}
						</td>
						<td>
							 {{$engineerList->MobileNo}}
						</td>
						<td>
							{{$engineerList->Email}}
						</td>
		<td>
						@if((int)$engineerList->StatusReference == 12003)
							@if($engineerList->RegistrationExpiryDate<=date('Y-m-d G:i:s'))
									<p class="font-red bold warning">Expired </p>
							@else
								Valid/Approved
							@endif
						@else
							<p class="font-red bold warning">{{$engineerList->Status}}</p>
						@endif
					</td>
						<!-- <td>
							<a href="{{URL::to('engineer/certificate/'.$engineerList->Id)}}" class="btn default btn-xs blue" target="_blank"><i class="fa fa-edit"></i>View/Print</a>
						</td> -->
						<td>
							<a href="{{URL::to($link.$engineerList->Id)}}" class="btn default btn-xs green-seagreen @if($linkText=="Edit"){{"editaction"}}@endif" @if($linkText=="View/Print"){{"target='_blank'"}}@endif><i class="fa fa-edit"></i> {{$linkText}}</a>
						</td>
					</tr>
					@empty
					<tr>
						<td class="font-red text-center" colspan="12">No data to display</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>
@stop