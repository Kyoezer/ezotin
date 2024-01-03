@extends('master')
@section('content')
<div class="portlet bordered light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>Click on fee structure to edit
		</div>
	</div>
	<div class="portlet-body flip-scroll">
            <a href="{{URL::to("contractor/editfeestructure")}}">Contractor</a> <br/>
            <a href="{{URL::to("consultant/editfeestructure")}}">Consultant</a> <br/>
            <a href="{{URL::to("architect/editfeestructure")}}">Architect</a> <br/>
            <a href="{{URL::to("engineer/editfeestructure")}}">Engineer</a> <br/>
            <a href="{{URL::to("specializedtrade/editfeestructure")}}">Specialized Trade</a> <br/>
	</div>
</div>
@stop