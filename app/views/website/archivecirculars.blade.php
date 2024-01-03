@extends('master')
@section('pagestyles')
	{{HTML::style('assets/global/plugins/bootstrap-editable/bootstrap-editable/css/bootstrap-editable.css')}}
@stop
@section('pagescripts')
	{{HTML::script('assets/global/plugins/bootstrap-editable/bootstrap-editable/js/bootstrap-editable.js')}}
	<script>
		$('.display-order').editable();
	</script>
@stop
@section('content')

<h4 class="head-title">List of Circulars</h4>

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

<table class="table table-striped table-hover table-responsive table-condensed" data-table="webcircular">
	<thead>
		<tr class="success">
			<th>Sl#</th>
			<th>Title</th>
			<th>Circular Type</th>
			<th>Content</th>
			<th>Posted On</th>
			<th>Display Order</th>
			<th>Display</th>
			<th>Action</th>
		</tr>
	</thead>

	<tbody>
		@forelse($circularLists as $circularDetail)
			<tr>
				<td>{{ $slno++ }}</td>
				<td>{{ strip_tags($circularDetail->Title) }}</td>
				<td>{{ strip_tags($circularDetail->CircularName) }}</td>
				<td>{{ HTML::decode(Str::limit(strip_tags($circularDetail->Content), 80, '...')) }}</td>
				<td>{{ $circularDetail->CreatedOn }}</td>
				<td><a href="#" class="display-order" data-type="text" data-pk="{{$circularDetail->Id}}" data-url="{{URL::to('web/changecirculardisplayorder')}}" data-title="Change Display Order">{{$circularDetail->DisplayOrder or 1}}</a></td>
				<td>{{ ($circularDetail->Display==1)?'Yes':'No' }}</td>
				<td>
					<a href="{{URL::to('web/showhidecircular/'.$circularDetail->Id."?display=$circularDetail->Display"."&circulartype=".Input::get('CircularType'))}}" class="btn btn-primary btn-xs">@if($circularDetail->Display == 1)Hide @else Show @endif<i class="fa fa-archive"></i></a>
					<a href="{{URL::to('web/addnewcircular')}}/{{$circularDetail->Id}}" class="btn purple btn-xs">Edit&nbsp;<i class="fa fa-edit"></i></a>
					<a data-id="{{$circularDetail->Id}}" href="#" class="delete-websiteentry btn green btn-xs">Delete&nbsp;<i class="fa fa-times"></i></a>
				</td>
			</tr>
		@empty
			<tr>
				<td colspan="5" class="text-center" style="color:red;">Please Select Circular Type</td>
			</tr>
		@endforelse
	</tbody>
</table>

@stop