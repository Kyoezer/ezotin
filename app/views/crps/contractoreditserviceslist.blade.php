@extends('master')
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption font-green-seagreen">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">Edit Application</span>
		</div>
		<div class="tools">
			<a href="javascript:;" class="collapse"></a>
		</div>
	</div>
	<div class="portlet-body">
		<div class="form-body">
			{{ Form::open(array('url' =>Request::URL(),'role'=>'form','method'=>'get')) }}
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">CDB No.</label>
						<input type="text" name="CDBNo" class="form-control" value="{{Input::get('CDBNo')}}">
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">Application No.</label>
						<input type="text" name="ApplicationNo" class="form-control" value="{{Input::get("ApplicationNo")}}">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Application date between</label>
						<div class="input-group input-large date-picker input-daterange">
							<input type="text" name="FromDate" class="form-control datepickerfrom" value="{{Input::get('FromDate')}}" />
							<span class="input-group-addon">
							to </span>
							<input type="text" name="ToDate" class="form-control datepickerto" value="{{Input::get('ToDate')}}" />
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<label class="control-label">&nbsp;</label>
					<div class="btn-set">
						<button type="submit" class="btn blue-hoki btn-sm">Search</button>
						<a href="{{Request::URL()}}" class="btn grey-cascade btn-sm">Clear</a>
					</div>
				</div>
			</div>
		</div>
		{{Form::close()}}
		<div class="table-responsive">
			<table class="table table-bordered table-striped table-condensed flip-content">
				<thead>
					<tr>
						<th>
							 Ref#
						</th>
						<th>
							 Dt.
						</th>
						<th>
							CDB No.
						</th>
						<th>
							Ownership Type
						</th>
						<th>
							 Name of Firm
						</th>
						<th>
							Class
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
							Tel#
						</th>
						<th>
							Email
						</th>
						<th>
							Services
						</th>
						<th>
							Action
						</th>
					</tr>
				</thead>
				<tbody>
				<tr>
					<td>6353</td>
					<td>08-06-2016</td>
					<td>6276</td>
					<td>Sole Proprietorship</td>
					<td>TADONGCHEN Construction</td>
					<td>S</td>
					<td>Bhutan</td>
					<td>Lhuentse</td>
					<td>17495673</td>
					<td>17495673</td>
					<td>norbuboss2012@gmail.com</td>
					<td>Renewal of CDB Certificate,Late Fee</td>
					<td>
						<a href="{{URL::to('contractor/editservicesdetail')}}" class="btn btn-xs green"><i class="fa fa-edit"></i> Edit Services</a>
						<a href="{{URL::to('contractor/deleteapplication')}}" class="btn btn-xs red"><i class="fa fa-times"></i> Delete</a>
					</td>
				</tr>
				</tbody>
			</table>
		</div>	
	</div>
</div>
@stop