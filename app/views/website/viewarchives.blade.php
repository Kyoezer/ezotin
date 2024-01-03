@extends('websitemaster')
@section('main-content')

<h4 class="head-title">Archives</h4>

{{ Form::open(array('url' =>Request::URL(),'role'=>'form','method'=>'get')) }}
	<div class="row">
		<div class="col-md-6 col-xs-12 col-sm-12">
			{{ Form::label('Circular Type:') }}
			<select class="form-control" name="CircularType">
				<option>---SELECT ONE---</option>
				@foreach ($circularTypes as $circularType)
					<option value="{{$circularType->Id}}" @if($circularTypeId == $circularType->Id) {{ "selected" }} @endif>{{$circularType->CircularName}}</option>
				@endforeach
			</select>
		</div>

		<div class="col-md-6 col-xs-12 col-sm-12">
			{{ Form::label('Action') }} <br />
			<input type="submit" value="List" class="btn btn-success">
		</div>
	</div>
{{ Form::close() }}

<table class="table table-striped table-hover table-responsive table-condensed">
	<thead>
		<tr class="success">
			<th>Sl#</th>
			<th>Title</th>
			<th>Circular Type</th>
			<th>Content</th>
			<th>Posted On</th>
			<th>Action</th>
		</tr>
	</thead>

	<tbody>
		@forelse($circularLists as $circularDetail)
			<tr>
				<td>{{ $slno++ }}</td>
				<td>{{ $circularDetail->Title }}</td>
				<td>{{ $circularDetail->CircularName }}</td>
				<td>{{ HTML::decode(Str::limit($circularDetail->Content, 80, '...')) }}</td>
				<td>{{ $circularDetail->CreatedOn }}</td>
				<td><a href="{{URL::to('web/viewarchivedetails/'.$circularDetail->Id)}}" class="btn btn-info btn-xs">View</a></td>
			</tr>
		@empty
			<tr>
				<td colspan="5" class="text-center" style="color:red;">Please Select Circular Type</td>
			</tr>
		@endforelse
	</tbody>
</table>

@stop