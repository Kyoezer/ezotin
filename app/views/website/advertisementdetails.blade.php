@extends('websitemaster')
@section('main-content')
<div class="row">
<div class="col-md-12">
@foreach ($advertisementDetails as $advertisementDetail)
	<h4 class="head-title">{{ HTML::decode($advertisementDetail->Title) }}
		</h4>
		<iframe src="https://www.facebook.com/plugins/share_button.php?href={{Request::url()}}&layout=button_count&size=small&mobile_iframe=true&width=88&height=20&appId" width="88" height="20" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
		<br>

	<span style="font-size: 9pt;"><strong>Posted on {{date_format(date_create($advertisementDetail->CreatedOn),'jS F, Y')}}</strong></span><br><br>
	@if($advertisementDetail->Image != NULL)
		<img src="{{asset($advertisementDetail->Image)}}" class="" height="200" width="200"/>
		<br>
	@endif
	{{ HTML::decode($advertisementDetail->Content) }}
	<div class="clear"></div>
	@if(count($attachments)>0)
		@foreach($attachments as $attachment)
			<a href="{{ asset($attachment->AttachmentPath) }}" class="btn btn-xs btn-primary">{{$attachment->AttachmentName}}&nbsp;<span class="fa fa-download"></span></a>
				<br><br>
		@endforeach
	@endif

@endforeach
	<br>

</div>
@stop