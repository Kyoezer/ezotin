@extends('websitemaster')
@section('main-content')
<div class="row">
<div class="col-md-12">
<h3 class="font-grey-gallery bold text-center">CDB Secretariat</h3>
	<?php $curDepartment = '---'; ?>
@if(!empty($directorGeneral))

	<center><h4><strong>HEAD OF AGENCY</strong></h4></center>
<div class="col-md-4 col-md-offset-4">
	<div class="thumbnail">
		<img src="{{asset($directorGeneral[0]->Image)}}" alt="">
		<div class="caption">
			<p style="text-align: center;"><strong>{{$directorGeneral[0]->FullName}}<br />{{$directorGeneral[0]->DesignationName}}</strong><br /><strong>Email:</strong> {{$directorGeneral[0]->Email}}<br /><strong>Contact No.:</strong> {{$directorGeneral[0]->PhoneNo}}<br /><strong>CDB Ext. No.:</strong> {{$directorGeneral[0]->ExtensionNo}}</p>
		</div>
	</div>
</div>
@endif

	<div class="clearfix"></div>
	<hr  class="style-eight">
@foreach($departments as $department)
	@if($curDepartment != '---' && $curDepartment != $department->DepartmentName)
			<div class="clearfix"></div>
		<hr class="style-eight"/>
	@endif
	<center><h4 style="text-transform: uppercase;"><strong>{{$department->DepartmentName}}</strong></h4></center>
	@if(count($cdbSecretariats[$department->Id]))
		<div class="container-section col-md-12">
		@foreach($cdbSecretariats[$department->Id] as $secretariat)
			<div class="col-md-4 @if(count($cdbSecretariats[$department->Id]) == 1){{"col-md-offset-4"}}@endif">
				<div class="thumbnail">
					<img src="{{asset($secretariat->Image)}}" alt="">
					<div class="caption">
						<p style="text-align: center;"><strong>{{$secretariat->FullName}}<br />{{$secretariat->DesignationName}}</strong><br /><strong>Email: </strong>{{$secretariat->Email}}<br /><strong>Contact No.:</strong> {{$secretariat->PhoneNo}}<br /><strong>CDB Ext. No.:</strong> {{$secretariat->ExtensionNo}}</p>
					</div>
				</div>
			</div>
		@endforeach
		</div>
	@endif
	@if(count($divisions[$department->Id])>0)
		@foreach($divisions[$department->Id] as $division)
			@if(count($cdbSecretariats[$division->Id])>0)
				<div class="clearfix"></div>
				<div class="container-section col-md-12">
					<center><h5 style="text-decoration:underline;"><strong>{{$division->DivisionName}}</strong></h5></center>
					@foreach($cdbSecretariats[$division->Id] as $secretariat)
						<div class="col-md-4 @if(count($cdbSecretariats[$division->Id]) == 1){{"col-md-offset-4"}}@endif">
							<div class="thumbnail">
								<img src="{{asset($secretariat->Image)}}" alt="">
								<div class="caption">
									<p style="text-align: center;"><strong>{{$secretariat->FullName}}<br />{{$secretariat->DesignationName}}</strong><br /><strong>Email: </strong>{{$secretariat->Email}}<br /><strong>Contact No.:</strong> {{$secretariat->PhoneNo}}<br /><strong>CDB Ext. No.:</strong> {{$secretariat->ExtensionNo}}</p>
								</div>
							</div>
						</div>
					@endforeach
				</div>
			@endif
		@endforeach
	@endif

	<?php $curDepartment = $department->DepartmentName; ?>
@endforeach
</div>
@stop