@extends('master')
@section('content')
<h4 class="head-title">CDB Secretariat</h4>

<table class="table table-striped table-bordered table-hover table-condensed table-responsive">
	<thead>
		<tr class="success">
			<th>Image</th>
			<th>Full Name</th>
			<th>Designation</th>
			<th>Email</th>
			<th>Phone#</th>
			<th>Ext#</th>
			<th>Action</th>
		</tr>
	</thead>

	<tbody>
		@foreach($isDirectorGeneral as $secretariatDetail)
			<tr>
				<td>{{ HTML::image($secretariatDetail->Image,'',array('class'=>'thumbnail4')) }}</td>
				<td>{{ $secretariatDetail->FullName }}</td>
				<td>{{ $secretariatDetail->DesignationName }}</td>
				<td>{{ $secretariatDetail->Email }}</td>
				<td>{{ $secretariatDetail->PhoneNo }}</td>
				<td>{{ $secretariatDetail->ExtensionNo }}</td>
				<td>
					<a href="{{URL::to('web/addcdbsecretariat/'.$secretariatDetail->Id)}}" class="btn btn-xs btn-primary">Edit <i class="fa fa-pencil-square-o"></i></a>
					<p></p>
					<a href="{{URL::to('web/deletecdbsecretariat/'.$secretariatDetail->Id)}}" class="btn btn-danger btn-xs">Delete</a>
				</td>
			</tr>
		@endforeach
	</tbody>
</table>

@foreach($cdbdepartments as $departmentDetails)
	@foreach($hasEmployees as $hasEmployee)
		@if($hasEmployee->DepartmentId == $departmentDetails->Id)
			<h4>{{$departmentDetails->DepartmentName}}</h4>
			<table class="table table-striped table-bordered table-hover table-condensed table-responsive">
				<thead>
					<tr class="success">
						<th>Image</th>
						<th>Full Name</th>
						<th>Designation</th>
						<th>Department</th>
						<th>Division</th>
						<th>Email</th>
						<th>Phone#</th>
						<th>Ext#</th>
						<th>Action</th>
					</tr>
				</thead>

				<tbody>
					@foreach($cdbSecretariat as $secretariatDetail)
						@if($secretariatDetail->DepartmentId == $departmentDetails->Id)
							<tr>
								<td>{{ HTML::image($secretariatDetail->Image,'',array('class'=>'thumbnail4')) }}</td>
								<td>{{ $secretariatDetail->FullName }}</td>
								<td>{{ $secretariatDetail->DesignationName }}</td>
								<td>{{ $secretariatDetail->DepartmentName }}</td>
								<td>{{ $secretariatDetail->DivisionName }}</td>
								<td>{{ $secretariatDetail->Email }}</td>
								<td>{{ $secretariatDetail->PhoneNo }}</td>
								<td>{{ $secretariatDetail->ExtensionNo }}</td>
								<td>
									<a href="{{URL::to('web/addcdbsecretariat/'.$secretariatDetail->Id)}}" class="btn btn-xs btn-primary">Edit <i class="fa fa-pencil-square-o"></i></a>
									<a href="{{URL::to('web/deletecdbsecretariat/'.$secretariatDetail->Id)}}" class="btn btn-danger btn-xs">Delete</a>
									<a href="{{URL::to('web/cdbsecretariatmoveup/'.$secretariatDetail->Id)}}" class="btn btn-info btn-xs">Move Up <i class="fa fa-arrow-circle-up"></i></a>
									<a href="{{URL::to('web/cdbsecretariatmovedown/'.$secretariatDetail->Id)}}" class="btn btn-info btn-xs">Move Down <i class="fa fa-arrow-circle-down"></i></a>

								</td>
							</tr>
						@endif
					@endforeach
				</tbody>
			</table>
		@endif
	@endforeach
@endforeach

@stop