@extends('homepagemaster')
@section('content')
<div class="row">
	<div class="col-md-offset-2 col-md-8">
		<div class="portlet light bordered">
			<div class="portlet-body">
				<div class="row">
					<div class="col-md-12 text-center">
						<h2 class="font-green-seagreen">Welcome Procuring Agency User</h2>
						<h4>Please select the module you would like to navigate to</h4>
					</div>
					<div class="col-md-12 text-center">
						<?php if($isCINET){?>
						<a href="{{URL::to('cinet/mydashboard?adminrole='.CONST_ROLE_PROCURINGAGENCYCINET)}}" class="btn blue-hoki btn-lg">CiNET</a>
						or
						<?php }?>
						 <a href="{{URL::to('etl/mydashboard?adminrole='.CONST_ROLE_PROCURINGAGENCYETOOL)}}" class="btn purple btn-lg">E TOOL</a>
						or <a href="{{URL::to('newEtl/mydashboard?adminrole='.CONST_ROLE_PROCURINGAGENCYETOOL)}}" class="btn purple btn-lg">SPRR</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop