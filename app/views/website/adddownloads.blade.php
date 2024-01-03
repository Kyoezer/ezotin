@extends('master')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-gift"></i>
					<span class="caption-subject font-green-seagreen">Add New Downloads</span>
				</div>
			</div>
			<div class="portlet-body form">
				{{ Form::open(array('action'=>'WebsiteDownloadsController@addDownloadData', 'method'=>'POST','class'=>'form-horizontal','files'=>true)) }}
				@foreach($download as $data)
					{{Form::hidden('Id',$data->Id)}}
				<div class="form-body">
					<div class="form-group">
						<label class="control-label col-md-3">File Name</label>
						<div class="col-md-7">
							<input type="text" name="FileName" value="{{$data->FileName}}" class="required form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Category</label>
						<div class="col-md-7">
							<div class="input-group">
								<select name="FileCategory" class="form-control select2me required">
									<option>---SELECT ONE---</option>
									@foreach ($downloadCategories as $downloadCategory)
										<option value="{{ $downloadCategory->Id }}" @if($data->CategoryId == $downloadCategory->Id)selected="selected"@endif>{{ $downloadCategory->CategoryName }}</option>
									@endforeach
								</select>
								<span class="input-group-btn">
									<a href="#addNewCategory" role="button" class="btn btn-large btn-primary" data-toggle="modal">Add New Category</a>
								</span>	
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">PDF File</label>
						<div class="col-md-9">
							{{ Form::file('PDF') }}

						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Word File</label>
						<div class="col-md-7">
							{{ Form::file('Word') }}
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Others</label>
						<div class="col-md-7">
							{{ Form::file('Others') }}
						</div>
					</div>
					@if((bool)$data->PDF || (bool)$data->Word || (bool)$data->Other)
						<br>
						<div class="col-md-4 col-md-offset-2">
							<caption><strong>OLD DOCUMENTS: </strong></caption>
							<table class="table table-striped dont-flip table-condensed">
								<thead>
									<tr>
										<th>Document</th>
										<th width="20%">Action</th>
									</tr>
								</thead>
								<tbody>
								@if((bool)$data->PDF)
									<tr>
										<td><a href="{{asset($data->PDF)}}"><strong>PDF document </strong></a></td><td> <a href="{{URL::to("web/deletedocument?id=$data->Id".'&type=PDF')}}" class="deleterowdb btn default btn-xs bg-red-sunglo"><i class="fa fa-times"></i> Delete</a></td>
									</tr>
								@endif
								@if((bool)$data->Word)
									<tr>
										<td><a href="{{asset($data->Word)}}"><strong>Word document </strong></a></td><td> <a href="{{URL::to("web/deletedocument?id=$data->Id".'&type=Word')}}" class="deleterowdb btn default btn-xs bg-red-sunglo"><i class="fa fa-times"></i> Delete</a></td>
									</tr>
								@endif
								@if((bool)$data->Other)
									<tr>
										<td><a href="{{asset($data->Other)}}"><strong>Other document </strong></a></td><td> <a href="{{URL::to("web/deletedocument?id=$data->Id".'&type=Other')}}" class="deleterowdb btn default btn-xs bg-red-sunglo"><i class="fa fa-times"></i> Delete</a></td>
									</tr>
								@endif
								</tbody>
							</table>
						</div>
						<div class="clearfix"></div>
					@endif
				</div>
				<div class="form-actions">
					<div class="btn-set">
						<button type="submit" class="btn green">Save</button>
						<a href="{{URL::to('web/adddownloads')}}" class="btn red">Cancel</a>
					</div>
				</div>
				@endforeach
				{{Form::close()}}
			</div>
		</div>
	</div>		
</div>
<div id="addNewCategory" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			{{ Form::open(array('action'=>'WebsiteDownloadsController@addNewCategory', 'method'=>'POST')) }}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"><strong>Add New Document Category</strong></h4>
				</div>
				<div class="modal-body">
					<p class="bold">Please enter the new category name.</p>
					<label>Category Name:</label>
					<input type="text" name="CategoryName" class="form-control" placeholder="Category Name">
				</div>

				<div class="modal-footer">
					<button type="submit" class="btn btn-success">Submit</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
				</div>
			{{ Form::close() }}
		</div>
	</div>
</div>

@stop