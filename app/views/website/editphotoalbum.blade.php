@extends('master')
@section('content')

<div class="row">
	<div class="col-md-6">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-gift"></i>
					<span class="caption-subject font-green-seagreen">Edit Album</span>
				</div>
			</div>
			<div class="portlet-body form">
			{{ Form::open(array('url'=>'web/addnewalbum', 'files'=>true,'class'=>'form-horizontal')) }}
				@foreach($album as $value)
				<div class="form-body">
					{{Form::hidden('Id',$value->Id)}}
					<div class="form-group">
						<label class="control-label col-md-3">Album Name</label>
						<div class="col-md-7">
							<input type="text" name="AlbumName" value="{{$value->AlbumName}}" class="required form-control" />
						</div>
					</div>
					@if((bool)$value->AlbumImage)
						&nbsp;&nbsp;&nbsp;&nbsp;<img src="{{asset($value->AlbumImage)}}" width="100" height="100"/>
					@endif
					<div class="form-group">
						<label class="control-label col-md-3">Upload Image</label>
						<div class="col-md-7">
							<input type="file" name="AlbumImage" class="@if(!(bool)$value->AlbumImage)required @endif form-control" />
						</div>
					</div>
				</div>
				@endforeach
				<div class="form-actions">
					<div class="btn-set">
						<button type="submit" class="btn green">Save</button>
						<a href="{{URL::to('web/photoalbum')}}" class="btn red">Cancel</a>
					</div>
				</div>
				{{Form::close()}}
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-gift"></i>
					<span class="caption-subject font-green-seagreen">Album Details</span>
				</div>
			</div>
			<div class="portlet-body form">
				<div class="form-body table-responsive">
					<table class="table table-bordered table-striped table-condensed table-hover">
						<thead>
							<tr class="success">
								<th>Album</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@forelse ($saved as $imageDetail)
								<tr>
									<td>
										<div class="nanoGallery3">
											<a href="{{asset($imageDetail->AlbumImage)}}" >{{$imageDetail->AlbumName}}</a>
										</div>
									</td>
									<td>
										<a href="{{URL::to('web/deletealbum/'.$imageDetail->Id)}}" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Delete</a>
										<a href="{{URL::to('web/photoalbum/'.$imageDetail->Id)}}" class="btn green btn-sm"><i class="fa fa-edit"></i> Edit</a>
									</td>
								</tr>
							@empty
								<tr>
									<td colspan="2" class="text-center font-red">No albums!</td>
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