@extends('master')
@section('content')

<div class="row">
	<div class="col-md-6">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-gift"></i>
					<span class="caption-subject font-green-seagreen">Edit Photo Gallery</span>
				</div>
			</div>
			<div class="portlet-body form">
			{{ Form::open(array('action'=>'PhotoGalleryController@updateGallery', 'files'=>true,'class'=>'form-horizontal')) }}
				@foreach($image as $value)
				{{Form::hidden('Id',$value->Id)}}
				<div class="form-body">
					<div class="form-group">
						<label class="control-label col-md-3">Image Name</label>
						<div class="col-md-7">
							<input type="text" name="ImageName" value="{{$value->ImageName}}" class="required form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Album</label>
						<div class="col-md-7">
							<select name="PhotoGalleryAlbumId" class="form-control required">
								<option value="">---SELECT---</option>
								@foreach($albumList as $album)
									<option value="{{$album->Id}}" @if($album->Id == $value->PhotoGalleryAlbumId)selected="selected"@endif>{{$album->AlbumName}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Image Description</label>
						<div class="col-md-7">
							<textarea class="form-control" name="ImageDescription">{{$value->ImageDescription}}</textarea>
						</div>
					</div>
					@if(!empty($value->ImageSource))
						&nbsp;&nbsp;&nbsp;&nbsp;<img src="{{asset($value->ImageSource)}}" width="100" height="100"/>
					@endif
					<div class="form-group">
						<label class="control-label col-md-3">Upload Image</label>
						<div class="col-md-7">
							<input type="file" name="GalleryImage" class="<?php if(empty($value->Id)): ?>{{"required "}}<?php endif; ?>form-control" />
						</div>
					</div>
				</div>
				@endforeach
				<div class="form-actions">
					<div class="btn-set">
						<button type="submit" class="btn green">Save</button>
						<a href="{{URL::to('web/editphotogallery')}}" class="btn red">Cancel</a>
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
					<span class="caption-subject font-green-seagreen">Gallery Details</span>
				</div>
			</div>
			<div class="portlet-body form">
				<div class="form-body table-responsive">
					<table class="table table-bordered table-striped table-condensed table-hover">
						<thead>
							<tr class="success">
								<th>Thumbnail</th>
								<th>Album</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($imageList as $imageDetail)
								<tr>
									<td>
										<div class="nanoGallery3">
										<!-- <img src="{{asset($imageDetail->ImageSource)}}" alt="thumb" width="60" height="60" /> -->
											<a href="{{asset($imageDetail->ImageSource)}}" data-ngthumb="{{asset($imageDetail->ImageThumbSource)}}" data-ngdesc="{{$imageDetail->ImageDescription}}">{{$imageDetail->ImageName}}</a>
										</div>
									</td>
									<td>{{$imageDetail->AlbumName}}</td>
									<td>
										<a href="{{URL::to('web/deletephoto/'.$imageDetail->ImageId)}}" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Delete</a>
										<a href="{{URL::to('web/editphotogallery/'.$imageDetail->ImageId)}}" class="btn green btn-sm"><i class="fa fa-edit"></i> Edit</a>
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