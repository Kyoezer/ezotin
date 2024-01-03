@extends('master')
@section('content')
<div class="col-md-4">
	<div class="portlet light bordered">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-gift"></i> Add Equipment
			</div>
		</div>
		<div class="portlet-body form">
			<?php $array = array(
					'0'=>'--All--',
					'15'=>'Medium Bus',
					'16'=>'Heavy Bus',
					'17'=>'Poser tiller',
					'18'=>'Tractor',
					'2'=>'Heavy Vehicles',
					'3'=>'Medium Vehicles',
					'4'=>'Earth Moving Equipment',
					'5'=>'Light Vehicles',
					'6'=>'Taxis',
					'7'=>'Two-wheeler'
			); ?>
			{{ Form::open(array('url' => 'master/mequipment','role'=>'form'))}}
				@foreach($editEquipments as $editEquipment)
				<input type="hidden" name="Id" value="{{$editEquipment->Id}}">
				<div class="form-body">
					<div class="form-group">
						<label class="control-label">Code</label>
						<input type="text" name="Code" class="form-control" placeholder="Code for equipment" value="{{$editEquipment->Code}}"/>
					</div>
					<div class="form-group">
						<label class="control-label">Name</label>
						<input type="text" name="Name" class="form-control required" placeholder="Name for equipment" value="{{$editEquipment->Name}}"/>				
					</div>
					<div class="form-group">
						<label>Is registered</label>
						<div class="radio-list">
							<label class="radio-inline">
							<input type="radio" name="IsRegistered" id="optionsRadios4" value="1" @if(empty($editEquipment->IsRegistered) || (int)$editEquipment->IsRegistered==1){{'checked'}}@endif> Yes </label>
							<label class="radio-inline">
							<input type="radio" name="IsRegistered" id="optionsRadios5" value="0" @if((int)$editEquipment->IsRegistered==0){{'checked'}}@endif> No </label>
						</div>
					</div>
					<div class="form-group">
						<label>Vehicle Type</label>
						{{Form::select('VehicleType',$array
						,$editEquipment->VehicleType,array('class'=>'form-control'))}}
					</div>
				</div>
				@endforeach
				<div class="form-actions">
					<div class="row">
						<div class="col-md-12">
							<button type="submit" class="btn green">Submit</button>
							<a href="#" class="btn red">Cancel</a>
						</div>
					</div>
				</div>
			{{Form::close()}}
		</div>
	</div>
</div>
<div class="col-md-8">
	<div class="portlet light bordered">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-gift"></i>Edit/Delete Equipment
			</div>
		</div>
		<div class="portlet-body">
			<table id="tablefilters_1" class="table table-bordered table-striped table-condensed">
	            <thead>
	                <tr>
	                    <th>
	                        Code
	                    </th>
	                    <th class="order">
	                        Name
	                    </th>
	                    <th>
	                    	Euipment Type
	                    </th>
						<th>Vehicle Type</th>
	                    <th>
	                        Actions
	                    </th>
	                </tr>
	            </thead>
	            <tbody>
	                @foreach($equipments as $equipment)
	                <tr>
	                    <td>
	                        {{$equipment->Code}}
	                    </td>
	                    <td>
	                        {{$equipment->Name}}
	                    </td>
	                    <td>
	                    	@if((int)$equipment->IsRegistered==1)
	                    		{{'Registered'}}
	                    	@else
	                    		{{'Not Registered'}}
	                    	@endif
	                    </td>
						<td>
							@if((int)$equipment->IsRegistered==1) @if(!empty($equipment->VehicleType)){{$array[$equipment->VehicleType]}}@endif @endif
						</td>
	                    <td>
	                        <a href="{{Request::url().'?sref='.$equipment->Id}}" class="editaction">Edit</a>|
	                        <a href="#" class="deleteaction">Delete</a>
	                    </td>
	                </tr>
	                @endforeach
	            </tbody>
	        </table>
		</div>
	</div>

</div>
<div class="clearfix"></div>
@stop