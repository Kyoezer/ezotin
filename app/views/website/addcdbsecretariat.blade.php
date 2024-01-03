@extends('master')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-gift"></i>
					<span class="caption-subject font-green-seagreen">Add CDB Secretariat</span>
				</div>
			</div>updateCDBSecretariat
			<div class="portlet-body form">
			@if((bool)$cdbSecretariat[0]->Id)
				<?php $action = "CDBSecretariatController@updateCDBSecretariat"; ?>
			@else
					<?php $action = "CDBSecretariatController@addSecretariatDetails"; ?>
			@endif
			{{ Form::open(array('action'=>$action, 'files'=>true,'class'=>'form-horizontal')) }}
				@foreach($cdbSecretariat as $secretariat)
				{{Form::hidden('cdbSecretariatId',$secretariat->Id)}}
				<div class="form-body">
					<div class="form-group">
						<label class="control-label col-md-3">Full Name</label>
						<div class="col-md-7">
							<input type="text" name="FullName" value="{{$secretariat->FullName}}" class="required form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Email</label>
						<div class="col-md-7">
							<input type="text" name="Email" value="{{$secretariat->Email}}" class="required form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Designation</label>
						<div class="col-md-7">
							<div class="input-group">
								<select name="DesignationId" class="form-control required">
									<option>---SELECT ONE---</option>
									@foreach($cdbdesignations as $cdbdesignation)
										<option value="{{ $cdbdesignation->Id }}" @if($secretariat->DesignationId == $cdbdesignation->Id)selected="selected"@endif>{{ $cdbdesignation->DesignationName }}</option>
									@endforeach
								</select>
								<span class="input-group-btn">
									<a href="#addNewDesignation" role="button" class="btn btn-primary" data-toggle="modal">Add New Designation</a>
								</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Department</label>
						<div class="col-md-7">
							<div class="input-group">
								<select name="DepartmentId" class="form-control required" id="secretariat-department">
									<option value="">---SELECT ONE---</option>
									@foreach($cdbdepartments as $cdbdepartment)
										<option value="{{ $cdbdepartment->Id }}" @if($secretariat->DepartmentId == $cdbdepartment->Id)selected="selected"@endif>{{ $cdbdepartment->DepartmentName }}</option>
									@endforeach
								</select>
								<span class="input-group-btn">
									<a href="#addNewDepartment" role="button" class="btn btn-large btn-primary" data-toggle="modal">Add New Department</a>
								</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Division</label>
						<div class="col-md-7">
							<div class="input-group">
								<select name="DivisionId" class="form-control" id="secretariat-division">
									<option value="">---SELECT ONE---</option>
									@foreach($cdbdivisions as $division)
										<option value="{{$division->Id}}" @if($division->Id == $secretariat->DivisionId)selected="selected"@endif @if($division->DepartmentId != $secretariat->DepartmentId)disabled="disabled"@endif data-deptid="{{$division->DepartmentId}}">{{ $division->DepartmentName }}</option>
									@endforeach
								</select>
								<span class="input-group-btn">
									<a href="#addNewDivision" role="button" class="btn btn-large btn-primary" data-toggle="modal">Add New Division</a>
								</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Phone No.</label>
						<div class="col-md-7">
							<input type="text" name="PhoneNo" value="{{$secretariat->PhoneNo}}" class="required form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Ext No.</label>
						<div class="col-md-7">
							<input type="text" name="ExtensionNo" value="{{$secretariat->ExtensionNo}}" class="required form-control" />
						</div>
					</div>
					@if((bool)$secretariat->Image)
						{{ Form::hidden('ImageUpload1', $secretariat->Image) }}
						<div class="col-md-4 col-md-offset-3">
							<img src="{{asset($secretariat->Image)}}"/>
						</div><div class="clearfix"></div><br>
					@endif
					<div class="form-group">
						<label class="control-label col-md-3">Image Upload {{--(width 120px, height 175px)--}}</label>
						<div class="col-md-7">
							<input type="file" name="ImageUpload" class="@if(!(bool)$secretariat->Image)required @endif form-control" />
						</div>
					</div>
					<div class="form-group">
						<label for="" class="control-label col-md-3">Is Head of Org</label>
						<div class="col-md-4">
							<div class="radio-list">
								<label for="option1" class="radio-inline">
									<input type="radio" name="IsDirectorGeneral" id="option1" @if((bool)$secretariat->Id)<?php if($secretariat->IsDirectorGeneral == 1): ?>checked="checked"<?php endif; ?>@else{{"checked='checked'"}}@endif class="form-control" value="1">&nbsp;Yes
								</label>
								<label for="option2" class="radio-inline">
									<input type="radio" name="IsDirectorGeneral" id="option2" class="form-control" value="0" <?php if($secretariat->IsDirectorGeneral == 0): ?>checked="checked"<?php endif; ?>>&nbsp;No
								</label>
							</div>
						</div>
					</div>
				</div>
				@endforeach
				<div class="form-actions">
					<div class="btn-set">
						<button type="submit" class="btn green">Save</button>
						<a href="{{URL::to('web/addcdbsecretariat')}}" class="btn red">Cancel</a>
					</div>
				</div>
			</div>	
			{{ Form::close()}}
		</div>
	</div>
</div>			
<!-- Modals Start -->
<div id="addNewDesignation" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Manage Designation</h4>
			</div>
			<div class="col-md-6">
			{{ Form::open(array('action'=>'CDBSecretariatController@addCDBDesignation', 'method'=>'POST')) }}


				<div class="modal-body">
					<p>Please enter the new designation name.</p>

					<label>Designation Name:</label>
					<input type="text" name="DesignationName" class="form-control required" placeholder="Designation Name" autofocus>
				</div>

				<div class="modal-footer">
					<button type="submit" class="btn btn-success">Submit</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
				</div>
			{{ Form::close() }}
			</div>
			<div class="col-md-6" style="height: 400px;overflow-y: scroll;">
				<h4>Designation</h4>
				<table class="table table-condensed table-bordered">
					<thead>
						<tr>
							<th>Sl#</th>
							<th>Designation</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php $count = 1; ?>
						@foreach($cdbdesignations as $designation)
						<tr>
							<td>{{$count++}}</td>
							<td>{{$designation->DesignationName}}</td>
							<td class="text-center"><a class="btn btn-xs red" href="{{URL::to("web/deletecdbdesignation/$designation->Id")}}"><i class="fa fa-times"></i>&nbsp;Delete</a></td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div><div class="clearfix"></div>
		</div>
	</div>
</div>
<div id="addNewDepartment" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Add New Department Category</h4>
				</div>
			<div class="col-md-6">
			{{ Form::open(array('action'=>'CDBSecretariatController@addCDBDDepartment', 'method'=>'POST')) }}
				<div class="modal-body">
					<p>Please enter the new department name.</p>

					<label>Department Name:</label>
					<input type="text" name="DepartmentName" class="form-control required" placeholder="Department Name" autofocus>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success">Submit</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
				</div>
			{{ Form::close() }}
			</div>
			<div class="col-md-6" style="height: 400px; overflow-y: scroll;">
				<h4>Departments</h4>
				<table class="table table-condensed table-bordered">
					<thead>
					<tr>
						<td>Sl#</td>
						<td>Department</td>
						<td>Action</td>
					</tr>
					</thead>
					<tbody>
						<?php $count = 1; ?>
						@foreach($cdbdepartments as $cdbdepartment)
							<tr>
								<td>{{$count++}}</td>
								<td>{{$cdbdepartment->DepartmentName}}</td>
								<td class="text-center"><a class="btn btn-xs red" href="{{URL::to("web/deletecdbdepartment/$cdbdepartment->Id")}}"><i class="fa fa-times"></i>&nbsp;Delete</a></td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div><div class="clearfix"></div>
		</div>
	</div>
</div>
<div id="addNewDivision" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Add New Division</h4>
			</div>
			<div class="col-md-6">
				{{ Form::open(array('action'=>'CDBSecretariatController@addCDBDivision', 'method'=>'POST')) }}
				<div class="modal-body">
					<p>Please enter the new division name.</p>
					<div class="col-md-12">
						<label>Department:</label>
						<select name="DepartmentId" class="form-control required">
							<option value="">--DEPARTMENT--</option>
							@foreach($cdbdepartments as $dept)
								<option value="{{$dept->Id}}">{{$dept->DepartmentName}}</option>
							@endforeach
						</select>
					</div>

					<div class="col-md-12">
						<br>
						<label>Division Name:</label>
						<input type="text" name="DepartmentName" class="form-control required" placeholder="Division Name" autofocus>
					</div> <div class="clearfix"></div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success">Submit</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
				</div>
				{{ Form::close() }}
			</div>
			<div class="col-md-6" style="height: 400px; overflow-y: scroll;">
				<h4>Division</h4>
				<table class="table table-condensed table-bordered">
					<thead>
					<tr>
						<td>Sl#</td>
						<td>Division</td>
						<td>Under</td>
						<td>Action</td>
					</tr>
					</thead>
					<tbody>
					<?php $count = 1; ?>
					@foreach($cdbdivisions as $cdbdivision)
						<tr>
							<td>{{$count++}}</td>
							<td>{{$cdbdivision->DepartmentName}}</td>
							<td>{{$cdbdivision->Department}}</td>
							<td class="text-center"><a class="btn btn-xs red" href="{{URL::to("web/deletecdbdivision/$cdbdivision->Id")}}"><i class="fa fa-times"></i>&nbsp;Delete</a></td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div><div class="clearfix"></div>
		</div>
	</div>
</div>
@stop