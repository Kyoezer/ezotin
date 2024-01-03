@extends('master')
@section('pagescripts')
    {{ HTML::script('assets/global/scripts/etool.js') }}
@stop
@section('content')
    <input type="hidden" name="URL" value="{{CONST_APACHESITELINK}}"/>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>Procuring Agency Report
		</div>
	</div>
	<div class="portlet-body">
        {{Form::open(array('url'=>'etoolsysadm/viewprocuringagencyreport','method'=>'get'))}}
		<div class="col-md-6">
		<div class="table-responsive">
			<table class="table table-bordered table-condensed">
				<tbody>
					<tr>
						<td>Report Type</td>
						<td>
							<select name="ReportType" class="form-control required">
								<option value="1">Tenders Uploaded</option>
								<option value="2">Work Id</option>
								<option value="3">Evaluation</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Procuring Agency</td>
						<td>
							<select name="ProcuringAgency" class="form-control select2me">
								<option value="">--SELECT--</option>
								@foreach($procuringAgencies as $procuringAgency)
									<option value="{{$procuringAgency->Id}}">{{$procuringAgency->Name}}</option>
								@endforeach
							</select>
						</td>
					</tr>
					<tr>
						<td>Classification</td>
						<td>
							<select name="Class" class="form-control">
								<option value="">--SELECT--</option>
								@foreach($classifications as $class)
									<option value="{{$class->Id}}">{{$class->Code}}</option>
								@endforeach
							</select>
						</td>
					</tr>
					<tr>
						<td>Category</td>
						<td>
							<select name="Category" class="form-control">
								<option value="">--SELECT--</option>
								@foreach($categories as $category)
									<option value="{{$category->Id}}">{{$category->Code}}</option>
								@endforeach
							</select>
						</td>
					</tr>
					<tr>
						<td>From Date</td>
						<td>
							<input type="text" name="FromDate" class="date-picker form-control" readonly/>
						</td>
					</tr>
					<tr>
						<td>To Date</td>
						<td>
							<input type="text" name="ToDate" class="date-picker form-control" readonly/>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<center><button type="submit" class="btn green">View</button></center>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		</div><div class="clearfix"></div>
        {{Form::close()}}
	</div>
</div>
@stop