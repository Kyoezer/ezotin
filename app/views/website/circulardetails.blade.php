@extends('websitemaster')
@section('main-content')
<div class="row">
	<div class="col-md-12">
@foreach ($circularDetails as $circularDetail)


	<div class="col-md-10">
		<h4 style="margin-left:-16px;" class="text-primary"><strong>{{{$circularDetail->Title}}}</strong></h4>
		<iframe src="https://www.facebook.com/plugins/share_button.php?href={{Request::url()}}&layout=button_count&size=small&mobile_iframe=true&width=88&height=20&appId" width="88" height="20" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
		<br>
	</div>
	<div class="col-md-2">
		@for($i=1; $i<=12;$i++)
			&nbsp;
		@endfor
		<a href="{{URL::to(Input::get('backRoute'))}}" class="btn btn-primary">Back</a>
	</div><div class="clearfix"></div>

	<span style="font-size: 9pt;"><strong>Posted on {{date_format(date_create($circularDetail->CreatedOn),'jS F, Y')}}</strong></span><br><br>

	<p>{{html_entity_decode($circularDetail->Content)}}</p>

	<?php if($circularDetail->Attachment!=NULL){ ?><a href="{{ asset($circularDetail->Attachment) }}" class="btn btn-large btn-primary">Download Attachment <span class="fa fa-download"></span></a><?php } ?>

	<?php if($circularDetail->ImageUpload != NULL) { ?>
	{{ HTML::image($circularDetail->ImageUpload,'',array('class'=>'Textwrap1 img-responsive')) }}
	<?php } ?>
	<div class="clear2"></div>

@endforeach
	</div>

@stop