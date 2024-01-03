@extends('master')
@section('content')
<div class="portlet light bordered col-md-6">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-gift"></i> Country
		</div>
	</div>
	<div class="portlet-body form">
		{{ Form::open(array('url' => 'master/mcountry','role'=>'form'))}}
			<div class="form-body">
				<div class="form-group">
					<label>Code</label>
					<input type="text" name="Code" class="form-control" placeholder="Code">
				</div>
				<div class="form-group">
					<label>Name</label>
					<input type="text" name="Name" class="form-control" placeholder="Country">
				</div>
				<div class="form-group">
					<label>Alternate Name</label>
					<input type="text" name="AlternateName" class="form-control" placeholder="Alternate Country">
				</div>
				<div class="form-group">
					<label>Nationality</label>
					<input type="text" name="Nationality" class="form-control" placeholder="Nationality">
				</div>
			</div>
			<div class="form-actions">
				<div class="row">
					<div class="col-md-12">
						<button type="submit" class="btn green">Save</button>
						<a href="#" class="btn red">Cancel</a>
					</div>
				</div>
			</div>
		{{Form::close()}}
	</div>
</div>

@stop