@extends('master')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-gift"></i>
					<span class="caption-subject font-green-seagreen">Edit/Delete Downloads</span>
				</div>
			</div>
			<div class="portlet-body">
				<div class="table-responsive">
					<table class="table table-striped table-condensed table-hover table-bordered" data-table="webtrainingdetails">
						{{Form::hidden('Model','WebDownloadsModel',array('class'=>'delete-model'))}}
						<thead>
							<tr class="success">
								<th>File Name</th>
								<th width="30%">Category</th>
								<th>Action</th>
							</tr>
						</thead>

						<tbody>
							<?php $slno = 1; ?>
							@forelse($downloads as $download)
								<tr>
									<input type="hidden" value="{{$download->Id}}" class="rowreference"/>
									<td>{{ $download->FileName }}</td>
									<td>{{ $download->CategoryName }}</td>
									<td>
										<a href="{{URL::to('web/adddownloads/'.$download->Id)}}">Edit</a> |
										<a href="#" class="deletedbrow">Delete</a>
									</td>
								</tr>
							@empty
								<tr>
									<td colspan="3" class="text-center" style="color:#FE0000">No data to display</td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>	
			</div>
		</div>
	</div>
</div> 	
@stop