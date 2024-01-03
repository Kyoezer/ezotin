@extends('websitemaster')
@section('main-content')
<div class="row">
	<div class="col-md-12">
{{ Form::open(array('url' =>Request::URL(),'role'=>'form','method'=>'get', 'class'=>'form-group')) }}
<h4 class="text-primary"><strong><i class="fa fa-list"></i> List of Architects</strong></h4>
<div class="alert alert-info">
	<p>Search architects by selecting relevant parameters. If you select more than one parameter, the result will be combination of selected parameters.</p>
</div>
<div class="row">
	<div class="col-md-2">
		<div class="form-group">
			<label class="control-label">AR No.</label>
			<input type="text" name="ARNo" class="form-control" value="{{Input::get('ARNo')}}">
		</div>
	</div>
	<div class="col-md-2">
		<div class="form-group">
			<label class="control-label">Name of Architect</label>
			<input type="text" name="ArchitectName" class="form-control" value="{{Input::get('ArchitectName')}}">
		</div>
	</div>
    <div class="col-md-2">
        <label class="control-label">Architect Type</label>
        <select name="ArchitectType" class="form-control">
			<option value="">---SELECT ONE---</option>
			@foreach($architectServiceSectorTypes as $architectServiceSectorType)
				<option value="{{$architectServiceSectorType->Id}}" @if($architectServiceSectorType->Id==$architectType)selected="selected"@endif>{{$architectServiceSectorType->Name}}</option>
			@endforeach
		</select>
    </div>
    <div class="col-md-2">
        <label class="control-label">Dzongkhag</label>
        <select class="form-control" name="CmnDzongkhagId">
			<option value="">---SELECT ONE---</option>
			@foreach($dzongkhagId as $dzongkhag)
				<option value="{{$dzongkhag->Id}}" @if($dzongkhag->Id==Input::get('CmnDzongkhagId')) selected @endif>{{$dzongkhag->NameEn}}</option>
			@endforeach
		</select>
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
		<table class="table table-bordered table-striped table-hover table-condensed flip-content">
			<thead class="flip-content">
				<tr>
					<th>
						Sl#
					</th>
                    <th>
                        AR. No.
                    </th>
					<th>
						Architect Type
					</th>
					<th>
						 Name
					</th>
					<th>
						 Country
					</th>
					<th>
						 Dzongkhag
					</th>
					{{--<th>--}}
						{{--Action--}}
					{{--</th>--}}
				</tr>
			</thead>
			<tbody>
				<?php $slNo=1; ?>
				@forelse($architectLists as $architectList)
				<tr>
					<td>
						{{$slNo}}
					</td>
                    <td>
                        {{$architectList->ARNo}}
                    </td>
					<td>
						{{$architectList->ArchitectType}}
					</td>
					<td>
						{{$architectList->ArchitectName}}
					</td>
					<td>
						 {{$architectList->Country}}
					</td>
					<td>
						 {{$architectList->Dzongkhag}}
					</td>
					{{--<td>--}}
						{{--<a href="{{URL::to('web/viewarchitectdetails/'.$architectList->Id)}}" class="btn btn-info btn-xs"></i>View</a>--}}
					{{--</td>--}}
				</tr>
				<?php $slNo++; ?>
				@empty
				<tr>
					<td class="font-red text-center" colspan="7" style="color:#FE0000;">No data to display</td>
				</tr>
				@endforelse
			</tbody>
		</table>
	</div>
</div>
	</div>
@stop