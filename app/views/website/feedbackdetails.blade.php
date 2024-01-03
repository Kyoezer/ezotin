@extends('master')
@section('content')

<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-gift"></i>
					<span class="caption-subject font-green-seagreen">Feedback</span>
				</div>
			</div>
			<div class="portlet-body form">
				<div class="form-body">
					<div class="note note-success">
						{{html_entity_decode($feedback)}}
					</div>
				</div>
				<div class="form-actions">
					<div class="btn-set">
						<a href="#deleteFeedback" role="button" class="btn btn-danger" data-toggle="modal">Delete</a>
					</div>
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
				<a href="{{URL::to('web/deletefeedback/'.$feedbackReference)}}" class="btn btn-danger btn-xs">Delete</a>
				<button type="button" class="btn btn-primary btn-xs" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>
@stop