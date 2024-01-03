@extends('websitemaster')
@section('main-content')
<div class="row">
	<div class="col-md-12">
		<h4 class="text-primary"><strong>Photo Gallery</strong></h4>
		<div class="nanoGallery2">

		@foreach($albums as $album)
			<a href="{{$album->AlbumImage}}"
			    data-ngkind="album"
			    data-ngid="{{$album->Id}}"
			    data-ngthumb="{{asset($album->AlbumImage)}}"
			    data-ngdesc="Description1">{{$album->AlbumName}}</a>
		@endforeach
		@foreach($images as $image)
			<a href="{{asset($image->ImageSource)}}" 
				data-ngthumb="{{asset($image->ImageThumbSource)}}" 
				@if((bool)$image->PhotoGalleryAlbumId)
					data-ngalbumid = "{{$image->PhotoGalleryAlbumId}}"
				@endif
				data-ngdesc="{{$image->ImageDescription}}">{{$image->ImageName}}</a>
		@endforeach
		</div>
	</div>
@stop