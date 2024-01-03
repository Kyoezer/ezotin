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
			{{ Form::open(array('action'=>'PhotoGalleryController@saveAlbum', 'files'=>true,'class'=>'form-horizontal')) }}
				@foreach($album as $detail)
				<div class="form-body">
					<div class="form-group">
						<label class="control-label col-md-3">Album Name</label>
						<div class="col-md-7">
							<input type="text" name="AlbumName" value="{{$detail->AlbumName}}" class="required form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Album Description</label>
						<div class="col-md-7">
							<textarea class="form-control" value="{{$detail->AlbumDescription}}" name="AlbumDescription"></textarea>
						</div>
					</div>

					@if((bool)$detail->AlbumImage)
						<img src="{{$detail->AlbumImage}}"/>
					@endif
					<div class="form-group">
						<label class="control-label col-md-3">Upload Image</label>
						<div class="col-md-7">
							<input type="file" name="AlbumImage" class="required form-control" />
						</div>
					</div>
				</div>
				@endforeach
				<div class="form-actions">
					<div class="btn-set">
						<button type="submit" class="btn green">Save</button>
						<a href="{{Request::url()}}" class="btn red">Cancel</a>
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
					<span class="caption-subject font-green-seagreen">Saved Albums</span>
				</div>
			</div>
			<div class="portlet-body form">
				<div class="form-body table-responsive">
					<table class="table table-bordered table-striped table-condensed table-hover">
						<thead>
							<tr class="success">
								<th>Thumbnail</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($albumList as $singleAlbum)
								<tr>
									<td>
										<img src="{{asset($singleAlbum->AlbumImage)}}" alt="thumb" width="60" height="60" />
									</td>
									<td>
										<a href="{{URL::to('web/deletealbum/'.$singleAlbum->Id)}}" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Delete</a>
										<a href="{{URL::to('web/addphotoalbum/'.$singleAlbum->Id)}}" class="btn green btn-sm"><i class="fa fa-edit"></i> Edit</a>
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
@stop