@extends('websitemaster')
@section('main-content')
<h4 class="text-primary"><strong><i class="fa fa-list"></i> {{$pageTitle}}</strong></h4>
<div class="row">
	<div class="col-md-12 table-responsive">
		<table class="table table-bordered table-hover table-condensed">
			<thead>
				<tr class="success">
					<th>Sl#</th>
					<th>Type</th>
					<th>Title</th>
					<th>Content</th>
					<th>Date</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				@forelse($circularDetails as $circularDetail)
					<tr>
						<td>{{$slNo++}}</td>
						<td>{{strip_tags(html_entity_decode($circularDetail->CircularName))}}</td>
						<td>{{strip_tags(html_entity_decode($circularDetail->Title))}}</td>
						<td>{{ HTML::decode(Str::limit(strip_tags(html_entity_decode($circularDetail->Content)), 100, '...')) }}</td>
						<td>{{date_format(date_create($circularDetail->CreatedOn),'d-m-Y')}}</td>
						<td><a href="{{URL::to('web/circulardetails/'.$circularDetail->Id.'?backRoute='.$backRoute)}}" class="btn btn-info btn-xs">View</a></td>
					</tr>
				@empty
					<tr>
					<td colspan="6" class="text-center font-red" style="color:red;">No news/events!</td>
					</tr>
				@endforelse
			</tbody>
		</table>
	</div>	
</div>
@stop