@extends('master')
@section('pagescripts')
    {{ HTML::script('assets/global/scripts/etool.js') }}
@stop
@section('content')
<div class="portlet bordered light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>Click on parameter to edit
		</div>
	</div>
	<div class="portlet-body flip-scroll">
        @foreach($pointTypes as $pointType)
            <a href="{{URL::to("etoolsysadm/editevaluationparameters/$pointType->Id")}}">{{$pointType->Name}}</a> <br/>
        @endforeach
	</div>
</div>
@stop