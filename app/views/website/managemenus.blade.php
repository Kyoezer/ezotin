@extends('master')
@section('content')

<h4 class="head-title">Main Menu List</h4>

<div class="row">
	<div class="col-md-12 col-xs-12 col-sm-12">
		<table class="table table-condensed table-striped table-hover table-responsive">
			<thead>
				<tr class="success">
					<th>Sl#</th>
					<th>Title</th>
					<th>Action</th>
				</tr>
			</thead>

			<tbody>
				@foreach($parentMenuList as $listDetail)
					<tr>
						<td>{{ $slno++ }}</td>
						<td>{{ $listDetail->Title }}</td>
						<td>
							@if($slno>2)
							<a href="{{URL::to('web/menuitemmoveup/'.$listDetail->Id)}}" class="btn btn-info btn-xs">Move Up <i class="fa fa-arrow-circle-up"></i></a>
							@else
								<a href="#" class="btn btn-info btn-xs" disabled="disabled">Move Up <i class="fa fa-arrow-circle-up"></i></a>
							@endif
							@if(($slno-1)<count($parentMenuList))
							<a href="{{URL::to('web/menuitemmovedown/'.$listDetail->Id)}}" class="btn btn-info btn-xs">Move Down <i class="fa fa-arrow-circle-down"></i></a>
							@else
									<a href="#" class="btn btn-info btn-xs" disabled="disabled">Move Down <i class="fa fa-arrow-circle-down"></i></a>
							@endif
							@if($listDetail->ShowInWebsite == 0)
								<a href="{{URL::to('web/mainmenuactivate/'.$listDetail->Id)}}" class="btn btn-success btn-xs">Enable</a>
							@else
								<a href="{{URL::to('web/mainmenudeactivate/'.$listDetail->Id)}}" class="btn btn-default btn-xs">Disable</a>
							@endif
							{{--<a href="{{URL::to('web/showinfooter/'.$listDetail->Id)}}" class="btn btn-success btn-xs">Show in Footer</a>--}}
							<a href="{{URL::to('web/menuitemdelete/'.$listDetail->Id)}}" class="btn btn-danger btn-xs">Delete</a>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>

<div class="clear2" style="border-top:1px #33C963 solid;"></div>

<h4 class="head-title clear">Sub Menu List</h4>

{{Form::open(array('url'=>'web/managemenus','method'=>'get','class'=>'form-group'))}}
	<div class="row">
		<div class="col-md-3">
			{{ Form::label('Select Parent Menu') }}
			<select name="ParentId" class="form-control select2me">
				<option>---SELECT ONE---</option>
				@forelse($parentList as $parentDetail)
					<option value="{{ $parentDetail->Id }}" @if($parentDetail->Id == $parentId){{ "selected" }} @endif>{{ $parentDetail->Title }}</option>
				@empty
					<option>Please Add a Parent Menu</option>
				@endforelse
			</select>
		</div>

		<div class="col-md-6 col-xs-12 col-sm-12">
			{{ Form::label('Action') }} <br />
			<input type="submit" value="List Items" class="btn btn-success">
			<input type="reset" value="Clear" class="btn btn-danger">
		</div>
	</div>
{{ Form::close() }}

<div class="row">
	<div class="col-md-12 col-xs-12 col-sm-12">
		<table class="table table-condensed table-striped table-hover table-responsive">
			<thead>
				<tr class="success">
					<th>Sl#</th>
					<th>Parent Menu</th>
					<th>Title</th>
					<th>Content</th>
					<th>Action</th>
				</tr>
			</thead>

			<tbody>
				@forelse($subMenuList as $listDetail)
					<tr>
						<td>{{ $slno1++ }}</td>
						<td>{{ $listDetail->Title }}</td>
						<td>{{ $listDetail->Title }}</td>
						<td>{{{ Str::limit(HTML::decode($listDetail->Content), 50, '...') }}}</td>
						<td>
							<a href="{{URL::to('web/editsubmenu/'.$listDetail->Id)}}" class="btn btn-primary btn-xs">Edit <i class="fa fa-pencil-square-o"></i></a>
							@if($slno1>2)
								<a href="{{URL::to('web/submenuitemmoveup/'.$listDetail->Id)}}" class="btn btn-info btn-xs">Move Up <i class="fa fa-arrow-circle-up"></i></a>
							@else
								<a href="#" disabled="disabled" class="btn btn-info btn-xs">Move Up <i class="fa fa-arrow-circle-up"></i></a>
							@endif
							@if(($slno1-1)<count($subMenuList))
								<a href="{{URL::to('web/submenuitemmovedown/'.$listDetail->Id)}}" class="btn btn-info btn-xs">Move Down <i class="fa fa-arrow-circle-down"></i></a>
							@else
								<a href="#" disabled="disabled" class="btn btn-info btn-xs">Move Down <i class="fa fa-arrow-circle-down"></i></a>
							@endif

							@if($listDetail->ShowInWebsite == 0)
								<a href="{{URL::to('web/submenuactivate/'.$listDetail->Id)}}" class="btn btn-success btn-xs">Enable</a>
							@else
								<a href="{{URL::to('web/submenudeactivate/'.$listDetail->Id)}}" class="btn btn-default btn-xs">Disable</a>
							@endif

{{--							<a href="{{URL::to('web/showinfooter/'.$listDetail->Id)}}" class="btn btn-success btn-xs">Show in Footer</a>--}}
							<a href="{{URL::to('web/submenuitemdelete/'.$listDetail->Id)}}" class="btn btn-danger btn-xs">Delete</a>
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="6" class="text-center" style="color:red;">No Sub-Items to Display</td>
					</tr>
				@endforelse				
			</tbody>
		</table>
	</div>
</div>

<div class="clear2" style="border-top:1px #33C963 solid;"></div>

{{--<h4 class="head-title clear">Footer Menu</h4>--}}

{{--<div class="row">--}}
	{{--<div class="col-md-12 col-xs-12 col-sm-12">--}}
		{{--<table class="table table-condensed table-striped table-hover table-responsive">--}}
			{{--<thead>--}}
				{{--<tr class="success">--}}
					{{--<th>Sl#</th>--}}
					{{--<th>Title</th>--}}
					{{--<th>Action</th>--}}
				{{--</tr>--}}
			{{--</thead>--}}

			{{--<tbody>--}}
				{{--@forelse($footerList as $footerListDetails)--}}
					{{--<tr>--}}
						{{--<td>{{ $slno2++ }}</td>--}}
						{{--<td>{{ $footerListDetails->Title }}</td>--}}
						{{--<td>--}}
							{{--<a href="{{URL::to('web/footeritemmoveup/'.$footerListDetails->Id)}}" class="btn btn-info btn-xs">Move Up <i class="fa fa-arrow-circle-up"></i></a>--}}
							{{--<a href="{{URL::to('web/footeritemmovedown/'.$footerListDetails->Id)}}" class="btn btn-info btn-xs">Move Down <i class="fa fa-arrow-circle-down"></i></a>--}}
							{{--<a href="{{URL::to('web/removefromfooter/'.$footerListDetails->Id)}}" class="btn btn-default btn-xs">Disable</a>--}}
						{{--</td>--}}
					{{--</tr>--}}
				{{--@empty--}}
					{{--<tr>--}}
						{{--<td colspan="4" class="text-center" style="color:red;">No Items Placed In Footer</td>--}}
					{{--</tr>--}}
				{{--@endforelse--}}
			{{--</tbody>--}}
		{{--</table>--}}
	{{--</div>--}}
{{--</div>--}}

@stop