@extends('homepagemaster')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered" id="form_wizard_1">
			<div class="portlet-title">
				<div class="caption font-green-seagreen">
					<i class="fa fa-gift"></i>
					<span class="caption-subject">Consultant Registration</span> - <span class="step-title">Step 2 of 4 </span>
				</div>
			</div>
			<div class="portlet-body form">
				<div class="form-wizard">
					<div class="form-body">
						<ul class="nav nav-pills nav-justified steps">
							<li class="done">
								<a href="#tab1" data-toggle="tab" class="step">
								<span class="number">
								1 </span>
								<span class="desc">
								<i class="fa fa-check"></i> General Information </span>
								</a>
							</li>
							<li class="active">
								<a href="#tab2" data-toggle="tab" class="step">
								<span class="number">
								2 </span>
								<span class="desc">
								<i class="fa fa-check"></i> Work Classification </span>
								</a>
							</li>
							<li>
								<a href="#tab3" data-toggle="tab" class="step">
								<span class="number">
								3 </span>
								<span class="desc">
								<i class="fa fa-check"></i> Human Resource </span>
								</a>
							</li>
							<li>
								<a href="#tab4" data-toggle="tab" class="step">
								<span class="number">
								4 </span>
								<span class="desc">
								<i class="fa fa-check"></i> Equipment </span>
								</a>
							</li>
						</ul>
						<!-- <div id="bar" class="progress progress-striped" role="progressbar">
							<div class="progress-bar progress-bar-success">
							</div>
						</div> -->
						<div class="tab-content">
							<div class="tab-pane active" id="tab2">
								{{ Form::open(array('url' => 'consultant/mconsultantworkclassification','role'=>'form','class'=>'form-horizontal'))}}
								<input type="hidden" name="IsEdit" value="{{$isEdit}}" />
								@include('crps.consultantworkclassificationcontrols')
								{{Form::close()}}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop