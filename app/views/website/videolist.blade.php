@extends('master')
@section('content')
<h4 class="head-title">List of Videos</h4>

<table class="table table-striped table-hover table-responsive table-condensed" style="width: 80%;" data-table="webcircular">
	<thead>
		<tr class="success">
			<th>Sl#</th>
			<th>Video</th>
			<th>Uploaded On</th>
			<th>Shown on website</th>
			<th>Action</th>
		</tr>
	</thead>

	<tbody>
		<?php $slno=1; ?>
		@forelse($videos as $video)
			<tr>
				<td>{{ $slno++ }}</td>
				<td>
					<video id=0 controls width=260 height=200 >
						<source src="{{asset(asset($video->OggVideoPath))}}" type='video/ogg; codecs="theora, vorbis"'/>
						<source src="{{asset(asset($video->WebmVideoPath))}}" type='video/webm' >
						<source src="{{asset(asset($video->Mp4VideoPath))}}" type='video/mp4'>
						<p>Video is not visible, most likely your browser does not support HTML5 video</p>
					</video>
				</td>
				<td>
					{{convertDateTimeToClientFormat($video->UploadedDate)}}
				</td>
				<td>@if((int)$video->DisplayStatus == 1)<center><img src="{{asset('assets/global/img/tick.png')}}" width="16"/></center>@endif</td>
				<td>
					@if((int)$video->DisplayStatus != 1)
						<a href="{{URL::to('web/showvideo')}}/{{$video->Id}}" class="btn blue btn-xs">Show on website&nbsp;<i class="fa fa-eye"></i></a>
					@endif
					<a href="{{URL::to('web/newvideo')}}/{{$video->Id}}" class="btn purple btn-xs editaction">Edit&nbsp;<i class="fa fa-edit"></i></a>
					<a href="{{URL::to('web/deletevideo')}}/{{$video->Id}}" class="btn green btn-xs deleteaction">Delete&nbsp;<i class="fa fa-times"></i></a>
				</td>
			</tr>
		@empty
			<tr>
				<td colspan="5" class="text-center" style="color:red;">Please Select Circular Type</td>
			</tr>
		@endforelse
	</tbody>
</table>

@stop