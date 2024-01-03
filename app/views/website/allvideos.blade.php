@extends('websitemaster')
@section('main-content')
<h4 class="head-title">List of Videos</h4>

<table class="table table-striped table-hover table-responsive table-condensed" style="width: 80%;" data-table="webcircular">
	<thead>
		<tr class="success">
			<th>Sl#</th>
			<th>Video</th>
			<th>Uploaded On</th>
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
			</tr>
		@empty
			<tr>
				<td colspan="3" class="text-center" style="color:red;">No videos</td>
			</tr>
		@endforelse
	</tbody>
</table>

@stop