@extends('websitemaster')
@section('main-content')
<div class="row">

	<div class="col-md-12">
	<h4 class="text-primary"><strong>Contractor Registration</strong>
<!-- <a href="{{URL::to('contractor/default')}}" class="btn btn-sm btn-success">-->
<a href="{{URL::to('https://www.citizenservices.gov.bt/construction-services/')}}" class="btn btn-sm btn-success">
Click here to apply for the service</a></h4>
	{{HTML::decode($content)}}

	</div>
@stop
