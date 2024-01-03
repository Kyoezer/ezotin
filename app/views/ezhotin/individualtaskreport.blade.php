@extends('master')
@section('content')
	<div class="portlet light bordered">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-cogs"></i>Individual Task Report
			</div>
		</div>
		<div class="portlet-body flip-scroll">
			@if(Input::get('export') != 'print')
				{{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
				<div class="form-body">
					<div class="row">
						<div class="col-md-2">
							<label class="control-label">From</label>
							<div class="input-icon right">
								<i class="fa fa-calendar"></i>
								<input type="text" name="FromDate" class="form-control input-sm datepicker" value="{{Input::get('FromDate')}}" readonly="readonly" placeholder="">
							</div>
						</div>
						<div class="col-md-2">
							<label class="control-label">To</label>
							<div class="input-icon right">
								<i class="fa fa-calendar"></i>
								<input type="text" name="ToDate" class="form-control input-sm datepicker" value="{{Input::get('ToDate')}}" readonly="readonly" placeholder="">
							</div>
						</div>
						@if($isAdmin)
						<div class="col-md-3">
							<label class="control-label">User</label>
							<select class="form-control select2me" name="UserId">
								<option value="">---SELECT ONE---</option>
								@foreach($crpsUsers as $crpsUser)
									<option value="{{$crpsUser->Id}}" @if($crpsUser->Id == Input::get('UserId'))selected @endif>{{$crpsUser->FullName}} ({{$crpsUser->username}})</option>
								@endforeach
							</select>
						</div>
						@endif
						@if(!Input::has('export'))
							<div class="col-md-2">
								<label class="control-label">&nbsp;</label>
								<div class="btn-set">
									<button type="submit" class="btn blue-hoki btn-sm">Search</button>
									<a href="{{Request::url()}}" class="btn grey-cascade btn-sm">Clear</a>
								</div>
							</div>
						@endif
					</div>
				</div>
				{{Form::close()}}
			@else
				@foreach(Input::except('export') as $key=>$value)
					<b>{{$key}}: {{$value}}</b><br>
				@endforeach
				<br/>
			@endif
			@if(in_array(101,$userAllPrivileges))
				<div class="col-md-12"><br><span class="h3" style="color:#4f51a9; text-decoration:underline;"><strong>User Approval ({{$userPendingCount}} pending)</strong></span></div>

				@foreach($userApproveCount as $userApprove)
					<div class="col-md-2">
						<br>
						<span class="h4"><strong>{{$userApprove->Type}}</strong></span><br>
						Approved: {{$userApprove->Total}}
					</div>
				@endforeach
			@endif
			@if(in_array(201,$userAllPrivileges) || in_array(202,$userAllPrivileges) || in_array(203,$userAllPrivileges) || in_array(204,$userAllPrivileges) || in_array(205,$userAllPrivileges)
			 || in_array(206,$userAllPrivileges) || in_array(207,$userAllPrivileges) || in_array(208,$userAllPrivileges) || in_array(209,$userAllPrivileges) || in_array(210,$userAllPrivileges))
				<div class="col-md-12"><br><span class="h3" style="color:#4f51a9; text-decoration:underline;"><strong>Verification ({{$verificationPending}} pending) </strong></span></div>
				@if(in_array(201,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Contractor Registration</strong></span><br>
						Verified: {{$contractorRV}} <br>
					</div>
				@endif
				@if(in_array(202,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Consultant Registration</strong></span><br>
						Verified: {{$consultantRV}} <br>
					</div>
				@endif
				@if(in_array(203,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Architect Registration</strong></span><br>
						Verified: {{$architectRV}} <br>
					</div>
				@endif
				@if(in_array(204,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Engineer Registration</strong></span><br>
						Verified: {{$engineerRV}} <br>
					</div>
				@endif
				@if(in_array(205,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Specialized Trade Registration</strong></span><br>
						Verified: {{$spRV}} <br>
					</div>
				@endif
			<div class="clearfix"></div>
				@if(in_array(206,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Contractor Service</strong></span><br>
						@foreach($contractorServices as $contractorServiceName => $contractorService)
							<strong> {{$contractorServiceV[$contractorService]['Name']}}</strong>-
							Verified: {{$contractorServiceV[$contractorService]['Count']}} <br>
						@endforeach
					</div>
				@endif

				@if(in_array(207,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Consultant Service</strong></span><br>
						@foreach($consultantServices as $consultantServiceName => $consultantService)
							<strong> {{$consultantServiceV[$consultantService]['Name']}}</strong>-
							Verified: {{$consultantServiceV[$consultantService]['Count']}} <br>
						@endforeach
					</div>
				@endif

				@if(in_array(208,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Architect Service</strong></span><br>
						@foreach($architectServices as $architectServiceName => $architectService)
							<strong>{{$architectServiceV[$architectService]['Name']}}</strong>-
							Verified: {{$architectServiceV[$architectService]['Count']}}  <br>
						@endforeach
					</div>
				@endif

				@if(in_array(209,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Engineer Service</strong></span><br>
						@foreach($engineerServices as $engineerServiceName => $engineerService)
							<strong>{{$engineerServiceV[$engineerService]['Name']}}</strong>-
							Verified: {{$engineerServiceV[$engineerService]['Count']}} <br>
						@endforeach
					</div>
				@endif

				@if(in_array(210,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Specialized Trade Service</strong></span><br>
						@foreach($spServices as $specializedTradeServiceName => $specializedTradeService)
							<strong> {{$specializedTradeServiceV[$specializedTradeService]['Name']}}</strong>-
							Verified: {{$specializedTradeServiceV[$specializedTradeService]['Count']}} <br>
						@endforeach
					</div>
				@endif
			@endif

			<hr>

			@if(in_array(301,$userAllPrivileges) || in_array(302,$userAllPrivileges) || in_array(303,$userAllPrivileges) || in_array(304,$userAllPrivileges) || in_array(305,$userAllPrivileges)
			 || in_array(306,$userAllPrivileges) || in_array(307,$userAllPrivileges) || in_array(308,$userAllPrivileges) || in_array(309,$userAllPrivileges) || in_array(310,$userAllPrivileges))
				<div class="col-md-12"><br><span class="h3" style="color:#4f51a9; text-decoration:underline;"><strong>Approval ({{$approvalPending}} pending)</strong></span></div>
				@if(in_array(301,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Contractor Registration</strong></span><br>
						Approved: {{$contractorRA}} <br>
					</div>
				@endif
				@if(in_array(302,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Consultant Registration</strong></span><br>
						Approved: {{$consultantRA}} <br>
					</div>
				@endif
				@if(in_array(303,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Architect Registration</strong></span><br>
						Approved: {{$architectRA}} <br>
					</div>
				@endif
				@if(in_array(304,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Engineer Registration</strong></span><br>
						Approved: {{$engineerRA}} <br>
					</div>
				@endif
				@if(in_array(305,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Specialized Trade Registration</strong></span><br>
						Approved: {{$spRA}} <br>
					</div>
				@endif
				<div class="clearfix"></div>
				@if(in_array(306,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Contractor Service</strong></span><br>
						@foreach($contractorServices as $contractorServiceName => $contractorService)
							<strong> {{$contractorServiceA[$contractorService]['Name']}}</strong>-
							Approved: {{$contractorServiceA[$contractorService]['Count']}} <br>
						@endforeach
					</div>
				@endif

				@if(in_array(307,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Consultant Service</strong></span><br>
						@foreach($consultantServices as $consultantServiceName => $consultantService)
							<strong> {{$consultantServiceA[$consultantService]['Name']}}</strong>-
							Approved: {{$consultantServiceA[$consultantService]['Count']}} <br>
						@endforeach
					</div>
				@endif

				@if(in_array(308,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Architect Service</strong></span><br>
						@foreach($architectServices as $architectServiceName => $architectService)
							<strong>{{$architectServiceA[$architectService]['Name']}}</strong>-
							Approved: {{$architectServiceA[$architectService]['Count']}}  <br>
						@endforeach
					</div>
				@endif

				@if(in_array(309,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Engineer Service</strong></span><br>
						@foreach($engineerServices as $engineerServiceName => $engineerService)
							<strong>{{$engineerServiceA[$engineerService]['Name']}}</strong>-
							Approved: {{$engineerServiceA[$engineerService]['Count']}} <br>
						@endforeach
					</div>
				@endif

				@if(in_array(310,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Specialized Trade Service</strong></span><br>
						@foreach($spServices as $specializedTradeServiceName => $specializedTradeService)
							<strong> {{$specializedTradeServiceA[$specializedTradeService]['Name']}}</strong>-
							Approved: {{$specializedTradeServiceA[$specializedTradeService]['Count']}} <br>
						@endforeach
					</div>
				@endif
			@endif

			<hr>

			@if(in_array(401,$userAllPrivileges) || in_array(402,$userAllPrivileges) || in_array(403,$userAllPrivileges) || in_array(404,$userAllPrivileges) || in_array(405,$userAllPrivileges)
			 || in_array(406,$userAllPrivileges) || in_array(407,$userAllPrivileges) || in_array(408,$userAllPrivileges) || in_array(409,$userAllPrivileges) || in_array(410,$userAllPrivileges))
				<div class="col-md-12"><br><span class="h3" style="color:#4f51a9; text-decoration:underline;"><strong>Payment Approval ({{$paymentPending}} pending)</strong></span></div>
				@if(in_array(401,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Contractor Registration</strong></span><br>
						Payment Approved: {{$contractorRPA}} <br>
					</div>
				@endif
				@if(in_array(402,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Consultant Registration</strong></span><br>
						Payment Approved: {{$consultantRPA}} <br>
					</div>
				@endif
				@if(in_array(403,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Architect Registration</strong></span><br>
						Payment Approved: {{$architectRPA}} <br>
					</div>
				@endif
				@if(in_array(404,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Engineer Registration</strong></span><br>
						Payment Approved: {{$engineerRPA}} <br>
					</div>
				@endif
				<div class="clearfix"></div>
				@if(in_array(406,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Contractor Service</strong></span><br>
						@foreach($contractorServices as $contractorServiceName => $contractorService)
							<strong> {{$contractorServicePA[$contractorService]['Name']}}</strong>-
							Payment Approved: {{$contractorServicePA[$contractorService]['Count']}} <br>
						@endforeach
					</div>
				@endif

				@if(in_array(407,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Consultant Service</strong></span><br>
						@foreach($consultantServices as $consultantServiceName => $consultantService)
							<strong> {{$consultantServicePA[$consultantService]['Name']}}</strong>-
							Payment Approved: {{$consultantServicePA[$consultantService]['Count']}} <br>
						@endforeach
					</div>
				@endif

				@if(in_array(408,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Architect Service</strong></span><br>
						@foreach($architectServices as $architectServiceName => $architectService)
							<strong>{{$architectServicePA[$architectService]['Name']}}</strong>-
							Payment Approved: {{$architectServicePA[$architectService]['Count']}}  <br>
						@endforeach
					</div>
				@endif

				@if(in_array(409,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Engineer Service</strong></span><br>
						@foreach($engineerServices as $engineerServiceName => $engineerService)
							<strong>{{$engineerServicePA[$engineerService]['Name']}}</strong>-
							Payment Approved: {{$engineerServicePA[$engineerService]['Count']}} <br>
						@endforeach
					</div>
				@endif

				@if(in_array(410,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Specialized Trade Service</strong></span><br>
						@foreach($spServices as $specializedTradeServiceName => $specializedTradeService)
							<strong> {{$specializedTradeServicePA[$specializedTradeService]['Name']}}</strong>-
							Payment Approved: {{$specializedTradeServicePA[$specializedTradeService]['Count']}} <br>
						@endforeach
					</div>
				@endif
			@endif

			<hr>
			@if(in_array(201,$userAllPrivileges) || in_array(202,$userAllPrivileges) || in_array(203,$userAllPrivileges) || in_array(204,$userAllPrivileges) || in_array(205,$userAllPrivileges)
			 || in_array(206,$userAllPrivileges) || in_array(207,$userAllPrivileges) || in_array(208,$userAllPrivileges) || in_array(209,$userAllPrivileges) || in_array(210,$userAllPrivileges) || in_array(301,$userAllPrivileges) || in_array(302,$userAllPrivileges) || in_array(303,$userAllPrivileges) || in_array(304,$userAllPrivileges) || in_array(305,$userAllPrivileges)
			 || in_array(306,$userAllPrivileges) || in_array(307,$userAllPrivileges) || in_array(308,$userAllPrivileges) || in_array(309,$userAllPrivileges) || in_array(310,$userAllPrivileges) || in_array(401,$userAllPrivileges) || in_array(402,$userAllPrivileges) || in_array(403,$userAllPrivileges) || in_array(404,$userAllPrivileges) || in_array(405,$userAllPrivileges)
			 || in_array(406,$userAllPrivileges) || in_array(407,$userAllPrivileges) || in_array(408,$userAllPrivileges) || in_array(409,$userAllPrivileges) || in_array(410,$userAllPrivileges))
				<div class="col-md-12"><br><span class="h3" style="color:#4f51a9; text-decoration:underline;"><strong>Rejection</strong></span></div>
				@if(in_array(401,$userAllPrivileges) || in_array(301,$userAllPrivileges) || in_array(201,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Contractor Registration</strong></span><br>
						Rejected: {{$contractorRR}} <br>
					</div>
				@endif
				@if(in_array(402,$userAllPrivileges) || in_array(302,$userAllPrivileges) || in_array(202,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Consultant Registration</strong></span><br>
						Rejected: {{$consultantRR}} <br>
					</div>
				@endif
				@if(in_array(403,$userAllPrivileges) || in_array(303,$userAllPrivileges) || in_array(203,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Architect Registration</strong></span><br>
						Rejected: {{$architectRR}} <br>
					</div>
				@endif
				@if(in_array(404,$userAllPrivileges) || in_array(304,$userAllPrivileges) || in_array(204,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Engineer Registration</strong></span><br>
						Rejected: {{$engineerRR}} <br>
					</div>
				@endif
				@if(in_array(305,$userAllPrivileges) || in_array(205,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Specialized Trade Registration</strong></span><br>
						Rejected: {{$spRR}} <br>
					</div>
				@endif
				<div class="clearfix"></div>
				@if(in_array(406,$userAllPrivileges)  || in_array(306,$userAllPrivileges) || in_array(206,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Contractor Service</strong></span><br>
						@foreach($contractorServices as $contractorServiceName => $contractorService)
							<strong> {{$contractorServiceR[$contractorService]['Name']}}</strong>-
							Rejected: {{$contractorServiceR[$contractorService]['Count']}} <br>
						@endforeach
					</div>
				@endif

				@if(in_array(407,$userAllPrivileges) || in_array(307,$userAllPrivileges) || in_array(207,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Consultant Service</strong></span><br>
						@foreach($consultantServices as $consultantServiceName => $consultantService)
							<strong> {{$consultantServiceR[$consultantService]['Name']}}</strong>-
							Rejected: {{$consultantServiceR[$consultantService]['Count']}} <br>
						@endforeach
					</div>
				@endif

				@if(in_array(408,$userAllPrivileges) || in_array(308,$userAllPrivileges) || in_array(208,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Architect Service</strong></span><br>
						@foreach($architectServices as $architectServiceName => $architectService)
							<strong>{{$architectServiceR[$architectService]['Name']}}</strong>-
							Rejected: {{$architectServiceR[$architectService]['Count']}}  <br>
						@endforeach
					</div>
				@endif

				@if(in_array(409,$userAllPrivileges) || in_array(309,$userAllPrivileges) || in_array(209,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Engineer Service</strong></span><br>
						@foreach($engineerServices as $engineerServiceName => $engineerService)
							<strong>{{$engineerServiceR[$engineerService]['Name']}}</strong>-
							Rejected: {{$engineerServiceR[$engineerService]['Count']}} <br>
						@endforeach
					</div>
				@endif

				@if(in_array(410,$userAllPrivileges) || in_array(310,$userAllPrivileges) || in_array(210,$userAllPrivileges))
					<div class="col-md-4">
						<br>
						<span class="h4"><strong>Specialized Trade Service</strong></span><br>
						@foreach($spServices as $specializedTradeServiceName => $specializedTradeService)
							<strong> {{$specializedTradeServiceR[$specializedTradeService]['Name']}}</strong>-
							Rejected: {{$specializedTradeServiceR[$specializedTradeService]['Count']}} <br>
						@endforeach
					</div>
				@endif
			@endif

			<hr>
			<div class="clearfix"></div>
	</div>
</div>
@stop