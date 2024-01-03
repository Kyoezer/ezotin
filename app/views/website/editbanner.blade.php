@extends('master')
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption font-green-seagreen">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">Edit Website Header Banner</span>
		</div>
	</div>
	<div class="portlet-body">
			{{ Form::open(array('action'=>'UpdateBannerWebsite@updateBanner', 'files'=>true)) }}
				<div class="form-group">
					{{ Form::label('bannerImage','Upload Banner')}}
					{{ Form::file('bannerImage',array('class'=>'required'))}}
				</div>
				<div class="form-actions">
					<div class="row">
						<div class="col-md-12">
							<button type="submit" class="btn green">Submit <i class="fa fa-arrow-circle-o-right"></i></button>
							<a href="{{Request::url()}}" class="btn red">Cancel <i class="fa fa-times"></i></a>
						</div>
					</div>
				</div>
			{{ Form::close() }}
		</div>
	</div>
</div>	
@stop