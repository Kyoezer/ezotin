@extends('websitemaster')
@section('main-content')
<div class="row">
<div class="col-md-12">
{{ Form::open(array('url' =>Request::URL(),'role'=>'form','method'=>'get', 'class'=>'form-group')) }}
<h4 class="text-primary"><strong><i class="fa fa-list"></i> List of Surveyor</strong></h4>
<div class="alert alert-info">
	<p>Search surveyor by selecting relevant parameters. If you select more than one parameter, the result will be combination of selected parameters.</p>
</div>
<div class="row">
	<div class="col-md-2">
		<div class="form-group">
			<label class="control-label">Surveyor Type:</label>
			<select name="CmnServiceSectorTypeId" class="form-control">
				<option value="">---SELECT ONE---</option>
				@foreach($serviceSectorTypes as $serviceSectorType)
					<option value="{{$serviceSectorType->Id}}" @if(Input::get('CmnServiceSectorTypeId')==$serviceSectorType->Id)selected="selected"@endif>{{$serviceSectorType->Name}}</option>
				@endforeach
			</select>
		</div>
	</div>
    <div class="col-md-2">
        <label class="control-label">Trade:</label>
        <select name="CmnTradeId" class="form-control">
			<option value="">---SELECT ONE---</option>
			@foreach($trades as $trade)
				<option value="{{$trade->Id}}" @if(Input::get('CmnTradeId')==$trade->Id)selected="selected"@endif>{{$trade->Name}}</option>
			@endforeach
		</select>
    </div>
    <div class="col-md-4">
        <label class="control-label">Name</label>
        <input type="text" name="SurveyorName" class="form-control" value="{{Input::get('SurveyorName')}}">
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label class="control-label">No. of Rows</label>
            {{Form::select('Limit',array(10=>10,20=>20,30=>30,50=>50,'All'=>'All'),Input::has('Limit')?Input::get('Limit'):20,array('class'=>'form-control'))}}
        </div>
    </div>
    <div class="col-md-2">
    	<label>|</label>
		<div class="btn-set">
			<button type="submit" class="btn btn-primary">Search</button>
			<a href="{{Request::url()}}" class="btn btn-danger">Clear</a>
		</div>
	</div>
</div>
{{Form::close()}}
<div class="row">
	<div class="col-md-12 col-xs-12 col-sm-12 table-responsive">
		<table class="table table-bordered table-striped table-hover table-condensed">
			<thead>
				<tr>
					<th>
						 Sl#
					</th>
					<th>
						AR No.
					</th>
					<!-- <th>
                    Surveyor Type
					</th> -->
					<th>
						Trade
					</th>
					<th>
						 Name
					</th>
					<!-- <th>
						CID/Work Permit No.
					</th> -->
					<th>
						 Country
					</th>
					<th>
						 Dzongkhag
					</th>
					<!-- <th>
						Email
					</th> -->
					{{--<th>--}}
						{{--Action--}}
					{{--</th>--}}
				</tr>
			</thead>
			<tbody>
				<?php $slNo=1;?>
				@forelse($surveyorLists as $surveyorList)
				<tr>
					<td>
						{{$slNo}}
					</td>
					<td>
						{{$surveyorList->ARNo}}
					</td>
					
					<td>
						{{$surveyorList->Trade}}
					</td>
					<td>
						{{$surveyorList->Salutation.' '.$surveyorList->SurveyName}} 
					</td>
					<td>
						 {{$surveyorList->Country}}
					</td>
					<td>
						 {{$surveyorList->Dzongkhag}}
					</td>
					{{--<td>--}}
						{{--<a href="{{URL::to('web/viewengineerdetails/'.$surveyorList->Id)}}" class="btn btn-info btn-xs">View</a>--}}
					{{--</td>--}}
				</tr>
				<?php $slNo++; ?>
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