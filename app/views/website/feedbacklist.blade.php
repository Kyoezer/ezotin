@extends('master')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-gift"></i>
					<span class="caption-subject font-green-seagreen">List of Feedback</span>
				</div>
			</div>
			<div class="portlet-body form">
				<div class="form-body">
					<table class="table table-bordered table-striped table-hover table-condensed table-responsive">
						<thead>
							<tr class="success">
								<th>Sl#</th>
								<th>Content</th>
								<th>Posted On</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@forelse ($feedbacks as $feedbackDetails)
								<tr>
									<td>{{$slNo++}}</td>
									<td>{{HTML::decode(Str::limit($feedbackDetails->Content, 80, '...'))}}</td>
									<td>{{convertDateToClientFormat($feedbackDetails->CreatedOn)}}</td>
									<td>
										<a href="{{URL::to('web/feedbackdetails/'.$feedbackDetails->Id)}}" class="btn btn-info btn-xs">View</a>
										<a href="#deleteFeedback" role="button" class="btn btn-danger btn-xs" data-toggle="modal">Delete</a>
									</td>
								</tr>
							@empty
								<tr>
									<td class="font-red text-center" colspan="4" style="color:#FE0000;">No data to display</td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>
			</div>
		</div>		
	</div>
</div>
<div id="deleteFeedback" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Delete Feedback</h4>
			</div>

			<div class="modal-body">
				<p>Are you sure you want to delete this feedback?</p>
			</div>

			<div class="modal-footer">
				<a href="{{URL::to('web/deletefeedback/'.$feedbackDetails->Id)}}" class="btn btn-danger">Delete</a>
				<button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>
@stop