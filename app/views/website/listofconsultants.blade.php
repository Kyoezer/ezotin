@extends('websitemaster')
@section('main-content')
<div class="row">
	<div class="col-md-12">
{{ Form::open(array('url' =>Request::URL(),'role'=>'form','method'=>'get', 'class'=>'form-group')) }}
<h4 class="text-primary"><strong><i class="fa fa-list"></i> List of Consultants</strong></h4>
<div class="alert alert-info">
	<p>Search consultants by selecting relevant parameters. If you select more than one parameter, the result will be combination of selected parameters.</p>
</div>
<div class="row">
	<div class="col-md-2">
		<div class="form-group">
			<label class="control-label">CDB No</label>
			<input type="text" name="CDBNo" class="form-control" value="{{Input::get('CDBNo')}}">
		</div>
	</div>
    <div class="col-md-2">
        <label class="control-label">Name of Firm</label>
        <input type="text" name="ConsultantName" class="form-control" value="{{Input::get('ConsultantName')}}">
    </div>
    {{--<div class="col-md-2">--}}
        {{--<label class="control-label">From</label>--}}
        {{--<div class="input-icon right">--}}
            {{--<input type="text" name="FromDate" class="form-control datepicker" value="{{Input::get('FromDate')}}" readonly="readonly" placeholder="SELECT DATE">--}}
        {{--</div>--}}
    {{--</div>--}}
    {{--<div class="col-md-2">--}}
        {{--<label class="control-label">To</label>--}}
        {{--<div class="input-icon right">--}}
            {{--<input type="text" name="ToDate" class="form-control datepicker" value="{{Input::get('ToDate')}}" readonly="readonly" placeholder="SELECT DATE">--}}
        {{--</div>--}}
    {{--</div>--}}
    <div class="col-md-2">
        <div class="form-group">
            <label class="control-label">Type:</label>
            {{Form::select('Type',array('1'=>'All','2'=>'Bhutanese','3'=>'Non-Bhutanese'),Input::get('Type'),array('class'=>'form-control'))}}
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label class="control-label">No. of Rows</label>
            {{Form::select('Limit',array(10=>10,20=>20,30=>30,50=>50,'All'=>'All'),Input::has('Limit')?Input::get('Limit'):20,array('class'=>'form-control'))}}
        </div>
    </div>
    <div class="col-md-2">
    	<label>&nbsp;</label>
		<div class="btn-set">
			<button type="submit" class="btn btn-primary">Search</button>
			<a href="{{Request::url()}}" class="btn btn-danger">Clear</a>
		</div>
	</div>
</div>
{{Form::close()}}
<div class="row">
	<div class="col-md-12 table-responsive">
		<table class="table table-bordered table-striped table-hover table-condensed flip-content">
			<thead class="flip-content">
				<tr>
					<th>
						 Sl#
					</th>
					<th>
						CDB No.
					</th>
					<th>
						 Name of Firm
					</th>
					<th>
						Country of Registration
					</th>
					<th>
						 Address
					</th>
					<th style="width:90px;">
						Expiry Date
					</th>
				</tr>
			</thead>
			<tbody>
				<?php $slNo=1; ?>
				@forelse($consultantLists as $consultantList)
				<tr>
					<td>
						 {{$slNo}}
					</td>
					<td>
						 {{$consultantList->CDBNo}}
					</td>
					<td>
						 {{$consultantList->NameOfFirm}}
					</td>
					<td class="">
						 {{$consultantList->Country}}
					</td>
					<td class="">
						 {{$consultantList->Address}}
					</td>
					<td>
						{{convertDateToClientFormat($consultantList->ExpiryDate)}}
					</td>
				</tr>
				<?php $slNo++;?>
				@empty
				<tr>
					<td class="font-red text-center" colspan="9" style="color:#FE0000;">No data to display</td>
				</tr>
				@endforelse
			</tbody>
		</table>
	</div>	
</div>
	</div>
@stop