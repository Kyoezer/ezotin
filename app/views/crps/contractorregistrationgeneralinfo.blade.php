@extends('homepagemaster')
@section('pagescripts')
{{ HTML::script('assets/global/scripts/contractor.js') }}
	@if(empty($contractorGeneralInfo[0]->Id) && (int)$serviceByContractor==0)
	<script>
	 $('#contractorgeneralinforegistrationform').bootstrapValidator({
	    fields: {
	        NameOfFirm: {
	            validators: {
	                remote: {
	                    message: 'The Proposed Name for your firm is already taken. Try another name for your firm.',
	                   	url: "<?php echo CONST_SITELINK.'checkproposednamecontractor'?>",
	                    delay: 2000
	                }
	            }
	        },
	        Email: {
	            validators: {
	                remote: {
	                    message: 'The email address is already registered with CDB.',
	                    url: "<?php echo CONST_SITELINK.'checkemailavailabilityapplicants'?>",
	                    delay: 2000
	                }
	            }
	        }
	    }
	});
	</script>
	@endif
@stop
@section('content')
	<div id="hrcheckmodal" class="modal fade in" role="dialog" aria-labelledby="modalmessagebox" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h3 id="modalmessageheader" class="modal-title font-red-intense">HR Check</h3>
				</div>
				<div class="modal-body">
				</div>
			</div>
		</div>
	</div>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption font-green-seagreen">
					<i class="fa fa-info-circle"></i>
					<span class="caption-subject">Contractor Registration (New Application) </span> - <span class="step-title">Step 1 of 4 </span>
				</div>
			</div>
			<div class="portlet-body form">
				<div class="form-wizard">
					<div class="form-body">
						<ul class="nav nav-pills nav-justified steps">
							<li class="active">
								<a href="#tab1" data-toggle="tab" class="step">
								<span class="number">
								1 </span>
								<span class="desc">
								<i class="fa fa-check"></i> General Information </span>
								</a>
							</li>
							<li>
								<a href="#" data-toggle="tab" class="step">
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
						<div class="tab-content">
							<div class="tab-pane active" id="tab1">
								{{ Form::open(array('url' => 'contractor/mcontractorgeneralinfo','role'=>'form','id'=>'contractorgeneralinforegistrationform','files'=>true))}}
								@include('crps.contractorgeneralinfocontrols')
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