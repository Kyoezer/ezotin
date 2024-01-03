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
{{Form::hidden('AjaxURL','web/deleteadvertisement')}}
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption font-green-seagreen">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">Edit/Delete Advertisement</span>
		</div>
	</div>
	<div class="portlet-body">
		<div class="row">
			<div class="table-responsive col-md-12 col-sm-12 col-xs-12">
				<table id="tablefilters_1" class="table table-condensed table-striped table-bordered table-hover table-responsive">
					<thead>
						<tr>
							<th class="order">Title</th>
							<th>Content</th>
							<th>Time</th>
							<th>Show in Marquee</th>
							<th>Display Order</th>
							<th width="15%">Actions</th>
						</tr>
					</thead>
					<tbody>
						@foreach($advertisements as $advertisement)
						<tr>
							<td>{{$advertisement->Title}}</td>
							<td>{{substr(strip_tags(html_entity_decode($advertisement->Content)),0,300)}}</td>
							<td>{{convertDateToClientFormat($advertisement->CreatedOn)}}</td>
							<td>@if($advertisement->ShowInMarquee == 1){{"Yes"}}@else{{"No"}}@endif</td>
							<td><a href="#" class="display-order" data-type="text" data-pk="{{$advertisement->Id}}" data-url="{{URL::to('web/changeadvertisementdisplayorder')}}" data-title="Change Display Order">{{$advertisement->DisplayOrder or 1}}</a></td>
							<td>
								<input type="hidden" class="record-id" value="{{$advertisement->Id}}"/>
								<a href="{{URL::to('web/addadvertisements/'.$advertisement->Id)}}" class="btn default btn-xs green-seagreen editaction"><i class="fa fa-edit"></i>Edit</a>|
								<a href="#" class="btn default btn-xs red delete-record"><i class="fa fa-times"></i>Delete</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@stop