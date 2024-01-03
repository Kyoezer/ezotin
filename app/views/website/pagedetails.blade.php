@extends('websitemaster')
@section('main-content')

@foreach ($pagedetails as $pdetails)
	<h4 class="head-title">{{ HTML::decode($pdetails->Title) }}</h4>

	@if($pdetails->ImageUpload != NULL)
		{{ HTML::image($pdetails->ImageUpload, '', array('class'=>'img-responsive TextWrap')) }}
	@endif

	{{ HTML::decode($pdetails->Content) }}

	<div class="clear"></div>

	@if($pdetails->Attachment != NULL)
		<a href="{{ asset($pdetails->Attachment) }}" class="btn btn-xs btn-primary">Download Attachment <span class="fa fa-download"></span></a>
	@endif
@endforeach

@stop