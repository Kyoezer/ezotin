@extends('master')

@section('content')
<div id="addhumanresource" class="modal fade" role="dialog" aria-labelledby="addhumanresource" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        	{{ Form::open(array('url' => 'forum/save-topic','role'=>'form','files'=>true))}}
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title font-green-seagreen bold">Add Forum Topic</h4>
              </div>
              <div class="modal-body">
				<div class="row">
					
					<div class="col-md-12">
						<div class="form-group">
							<label class="control-label">Topic</label>
							<input type="text" class="form-control required" name="topic"/>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label class="control-label">Content</label>
							<textarea class="form-control required" name="content"></textarea>
						</div>
					</div>
				</div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn green">Save</button>
				<button type="button" class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
              </div>
              {{Form::close()}}
        </div>
    </div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered" id="form_wizard_1">
			
			<div class="portlet-body form">
				<div class="form-wizard">
					<div class="form-body">
						<div class="portlet box blue-madison">
							<div class="portlet-body">
								<div class="scroller" style="height: 280px;">
						            <div class="row">
										<div class="col-md-12">
											<div class="table-responsive">
												<table class="table table-bordered table-striped table-condensed">
													<thead class="">
														<tr>
															<th colspan="4" class="bold">List of Forums</th>
														</tr>
														<tr>
															<th>Sl/No</th>
															<th>Forum Topic</th>
															<th>Category</th>
															<th>Actions</th>
														</tr>
													</thead>
													<tbody>
														<?php 
															$i = 0;
														?>
														@foreach($list as $lists)
														<tr>
															<td>{{ ++$i }}
															</td>
															<td>
																<a href="topic/view/{{ $lists->id}}"> {{ $lists->topic }}</a>
															</td>
															<td>{{ $lists->content }}</td>
															<td>
																<a href="topic/edit/{{ $lists->id}}" class="editaction"> <i class="fa fa-edit"></i> Edit</a> |
																<a href="topic/delete/{{ $lists->id}}" class="deleteaction"> <i class="fa fa-transh"></i> Delete</a>
															</td>
														</tr>
														@endforeach
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>	
						</div>		
						 <div class="table-toolbar">
		                    <div class="row">
		                        <div class="col-md-6">
		                            <div class="btn-group">
		                                <a href="#addhumanresource" data-toggle="modal" class="btn green btn-sm"> <i class="fa fa-plus"></i> Add New Forum</a>
		                            </div>
		                        </div>
		                    </div>
		                </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop