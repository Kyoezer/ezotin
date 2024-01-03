@extends('master')
@section('content')
 <div class="col-md-4">
	<div class="portlet light bordered">
		<div class="portlet-title">
			<div class="caption font-green-seagreen">
				<i class="fa fa-gift"></i> 
				<span class="caption-subject">{{$title}}</span>
			</div>
		</div>
		<div class="portlet-body form">
			{{ Form::open(array('url' => 'master/mcmnlistitem','role'=>'form'))}}
				@foreach($cmnItems as $cmnItem)
				<div class="form-body">
					<input type="hidden" name="Id" value="{{$cmnItem->Id}}" />
					<input type="hidden" name="CmnListId" value="{{$listId}}" />
					<input type="hidden" name="RedirectRoute" value="{{$redirectRoute}}" />
					<div class="form-group">
						<label class="control-label">Code</label>
						<input type="text" name="Code" class="form-control" placeholder="Code" value="{{$cmnItem->Code}}" />
					</div>
					<div class="form-group">
						<label class="control-label">Name</label>
						<input type="text" name="Name" class="form-control required" placeholder="Name" value="{{$cmnItem->Name}}" />
					</div>
				</div>
				@endforeach
				<div class="form-actions">
					<div class="row">
						<div class="col-md-12">
							<button type="submit" class="btn green">Save</button>
							<a href="{{URL::to(Request::url())}}" class="btn red">Cancel</a>
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
			<div class="caption font-green-seagreen">
				<i class="fa fa-gift"></i> 
				<span class="caption-subject">{{'Edit/Delete '.$title}}</span>
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
	                        Actions
	                    </th>
	                </tr>
	            </thead>
	            <tbody>
	                @foreach($cmnMasterItems as $cmnMasterItem)
	                <tr>
	                    <td>
	                        {{$cmnMasterItem->Code}}
	                    </td>
	                    <td>
	                        {{$cmnMasterItem->Name}}
	                    </td>
	                    <td>
	                        <a href="{{Request::url().'?sref='.$cmnMasterItem->Id}}" class="editaction">Edit</a>|
	                        <a data-id="{{$cmnMasterItem->Id}}" href="#" class="deleteaction deleteitem">Delete</a>
	                    </td>
	                </tr>
	                @endforeach
	            </tbody>
	        </table>
		</div>
	</div>
</div>	<div class="clearfix"></div>
@stop