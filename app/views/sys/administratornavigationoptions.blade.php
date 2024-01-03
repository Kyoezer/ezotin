@extends('homepagemaster')
@section('content')
<div class="row">
	<div class="col-md-offset-2 col-md-8">
		<div class="portlet light bordered">
			<div class="portlet-body">
				<div class="row">
					<div class="col-md-12 text-center">
						<h2 class="font-green-seagreen">Welcome Administrator</h2>
						<h4>Please select the module you would like to navigate to</h4>
					</div>
					<div class="col-md-12 text-center">
						<a href="{{URL::to('ezhotin/dashboard')}}" class="btn green-seagreen btn-lg">CRPS</a> <strong>or</strong>
						 <a href="{{URL::to('cinet/mydashboard?adminrole='.CONST_ROLE_PROCURINGAGENCYCINET)}}" class="btn blue-hoki btn-lg">CiNET</a> <strong>or</strong>
						 <a href="{{URL::to('etl/mydashboard?adminrole='.CONST_ROLE_PROCURINGAGENCYETOOL)}}" class="btn purple btn-lg">E TOOL</a> <strong>or</strong>
						 <a href="{{URL::to('newEtl/mydashboard?adminrole='.CONST_ROLE_PROCURINGAGENCYETOOL)}}" class="btn purple btn-lg">SPRR</a> <strong>or</strong>
						 <a href="{{URL::to('cb/mydashboard?adminrole='.CONST_ROLE_PROCURINGAGENCYCBUILDER)}}" class="btn green btn-lg">Certified Builders</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop