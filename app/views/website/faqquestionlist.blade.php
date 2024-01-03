@extends('master')
@section('content')
<h4 class="text-primary">Manage Frequently Asked Questions (FAQ)</h4>
<div class="table-responsive">
	<table class="table table-striped table-hover table-condensed table-responsive table-bordered">
		<thead>
			<tr class="success">
				<th>Sl#</th>
				<th>Question</th>
				<th>Posted On</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			@forelse ($faqQustions as $questionDetails)
				<tr>
					<td>{{$slNo}}</td>
					<td>{{HTML::decode($questionDetails->Question)}}</td>
					<td>{{convertDateToClientFormat($questionDetails->CreatedOn)}}</td>
					<td>

						<a href="{{URL::to('web/movefaqquestionup/'.$questionDetails->Id.'/'.$questionDetails->DisplayOrder)}}" @if($slNo == 1)disabled="disabled"@endif class="btn btn-xs green"><i class="fa fa-arrow-up"></i>&nbsp;Move Up</a>
						<a href="{{URL::to('web/movefaqquestiondown/'.$questionDetails->Id.'/'.$questionDetails->DisplayOrder)}}" @if($slNo == count($faqQustions))disabled="disabled"@endif class="btn btn-xs blue"><i class="fa fa-arrow-down"></i>&nbsp;Move Down</a>
						<a href="{{URL::to('web/faqquestiondetails/'.$questionDetails->Id)}}" class="btn btn-info btn-xs">Edit</a>
						<a href="#deleteQuestion" role="button" class="btn btn-danger btn-xs" data-toggle="modal">Delete</a>
					</td>
					<?php $slNo++; ?>
				</tr>
				<div id="deleteQuestion" class="modal fade">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title">Delete Question</h4>
							</div>

							<div class="modal-body">
								<p>Are you sure you want to delete this question?</p>
							</div>

							<div class="modal-footer">
								<a href="{{URL::to('web/deletequestion/'.$questionDetails->Id)}}" class="btn btn-danger btn-xs">Delete</a>
								<button type="button" class="btn btn-primary btn-xs" data-dismiss="modal">Cancel</button>
							</div>
						</div>
					</div>
				</div>
			@empty
				<tr>
					<td class="font-red text-center" colspan="4" style="color:#FE0000;">No data to display</td>
				</tr>
			@endforelse
		</tbody>
	</table>
</div>
@stop