@extends('master')
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption font-green-seagreen">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">Edit/Delete User</span>
		</div>
	</div>
	<div class="portlet-body">
		<div class="row">
			<div class="tabbable-custom nav-justified">
				<ul class="nav nav-tabs nav-justified" id="add-detail-tab">
					<li class="active">
						<a href="#crps" data-toggle="tab">
							CRPS
						</a>
					</li>
					<li>
						<a href="#etool" data-toggle="tab">
							Etool</a>
					</li>
					<li>
						<a href="#cinet" data-toggle="tab">
							Cinet</a>
					</li>
					<li>
						<a href="#etoolcinet" data-toggle="tab">
							Etool & Cinet
						</a>
					</li>
					<li>
						<a href="#certifiedbuilder" data-toggle="tab">
							Certified Builder
						</a>
					</li>
					<li>
						<a href="#applicant" data-toggle="tab">
							Applicant
						</a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane" id="etool">
						<div class="table-responsive col-md-12 col-sm-12 col-xs-12">
							<table id="tablefilters_1" class="table table-condensed table-striped table-bordered table-hover table-responsive">
								<thead>
								<tr>
									<th>Sl#</th>
									<th class="order">Role</th>
									<th>User Full Name</th>
									<th>Username</th>
									<th>Agency</th>
									<th>Action</th>
								</tr>
								</thead>
								<tbody>
								<?php $slNo = 1; ?>
								@foreach($etoolUsers as $etoolUser)
									<tr>
										<td>{{$slNo++}}</td>
										<td>
											{{$etoolUser->Role}}
										</td>
										<td>{{$etoolUser->FullName}}</td>
										<td>{{$etoolUser->username}}</td>
										<td>{{$etoolUser->Agency}}</td>
										<td>
											<a href="{{URL::to('sys/user/'.$etoolUser->Id)}}" class="resetuserpassword btn default btn-xs green-seagreen"><i class="fa fa-edit"></i>Edit</a>|
											<a href="{{URL::to('sys/deleteuser/'.$etoolUser->Id)}}" class="resetuserpassword btn default btn-xs red"><i class="fa fa-times"></i>Delete</a>
										</td>
									</tr>
								@endforeach
								</tbody>
							</table>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="tab-pane" id="cinet">
						<div class="table-responsive col-md-12 col-sm-12 col-xs-12">
							<table id="tablefilters_1" class="table table-condensed table-striped table-bordered table-hover table-responsive">
								<thead>
								<tr>
									<th>Sl#</th>
									<th class="order">Role</th>
									<th>User Full Name</th>
									<th>Username</th>
									<th>Agency</th>
									<th>Action</th>
								</tr>
								</thead>
								<tbody>
								<?php $slNo = 1; ?>
								@foreach($cinetUsers as $cinetUser)
									<tr>
										<td>{{$slNo++}}</td>
										<td>
											{{$cinetUser->Role}}
										</td>
										<td>{{$cinetUser->FullName}}</td>
										<td>{{$cinetUser->username}}</td>
										<td>{{$cinetUser->Agency}}</td>
										<td>
											<a href="{{URL::to('sys/user/'.$cinetUser->Id)}}" class="resetuserpassword btn default btn-xs green-seagreen"><i class="fa fa-edit"></i>Edit</a>|
											<a href="{{URL::to('sys/deleteuser/'.$cinetUser->Id)}}" class="resetuserpassword btn default btn-xs red"><i class="fa fa-times"></i>Delete</a>
										</td>
									</tr>
								@endforeach
								</tbody>
							</table>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="tab-pane" id="certifiedbuilder">
						<div class="table-responsive col-md-12 col-sm-12 col-xs-12">
							<table id="tablefilters_1" class="table table-condensed table-striped table-bordered table-hover table-responsive">
								<thead>
								<tr>
									<th>Sl#</th>
									<th class="order">Role</th>
									<th>User Full Name</th>
									<th>Username</th>
									<th>Agency</th>
									<th>Action</th>
								</tr>
								</thead>
								<tbody>
								<?php $slNo = 1; ?>
								@foreach($certifiedbuilderUsers as $certifiedbuilderUser)
									<tr>
										<td>{{$slNo++}}</td>
										<td>
											{{$certifiedbuilderUser->Role}}
										</td>
										<td>{{$certifiedbuilderUser->FullName}}</td>
										<td>{{$certifiedbuilderUser->username}}</td>
										<td>{{$certifiedbuilderUser->Agency}}</td>
										<td>
											<a href="{{URL::to('sys/user/'.$certifiedbuilderUser->Id)}}" class="resetuserpassword btn default btn-xs green-seagreen"><i class="fa fa-edit"></i>Edit</a>|
											<a href="{{URL::to('sys/deleteuser/'.$certifiedbuilderUser->Id)}}" class="resetuserpassword btn default btn-xs red"><i class="fa fa-times"></i>Delete</a>
										</td>
									</tr>
								@endforeach
								</tbody>
							</table>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="tab-pane" id="etoolcinet">
						<div class="table-responsive col-md-12 col-sm-12 col-xs-12">
							<table id="tablefilters_1" class="table table-condensed table-striped table-bordered table-hover table-responsive">
								<thead>
								<tr>
									<th>Sl#</th>
									<th class="order">Role</th>
									<th>User Full Name</th>
									<th>Username</th>
									<th>Agency</th>
									<th>Action</th>
								</tr>
								</thead>
								<tbody>
								<?php $slNo=1; ?>
								@foreach($etoolCinetUsers as $etoolCinetUser)
									<tr>
										<td>{{$slNo++}}</td>
										<td>
											{{$etoolCinetUser->Role}}
										</td>
										<td>{{$etoolCinetUser->FullName}}</td>
										<td>{{$etoolCinetUser->username}}</td>
										<td>{{$etoolCinetUser->Agency}}</td>
										<td>
											<a href="{{URL::to('sys/user/'.$etoolCinetUser->Id)}}" class="resetuserpassword btn default btn-xs green-seagreen"><i class="fa fa-edit"></i>Edit</a>|
											<a href="{{URL::to('sys/deleteuser/'.$etoolCinetUser->Id)}}" class="resetuserpassword btn default btn-xs red"><i class="fa fa-times"></i>Delete</a>
										</td>
									</tr>
								@endforeach
								</tbody>
							</table>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="tab-pane active" id="crps">
						<div class="table-responsive col-md-12 col-sm-12 col-xs-12">
							<table id="tablefilters_1" class="table table-condensed table-striped table-bordered table-hover table-responsive">
								<thead>
								<tr>
									<th>Sl#</th>
									<th class="order">Role</th>
									<th>User Full Name</th>
									<th>Username</th>
									<th>Agency</th>
									<th>Action</th>
								</tr>
								</thead>
								<tbody>
								<?php $slNo=1; ?>
								@foreach($crpsUsers as $crpsUser)
									<tr>
										<td>{{$slNo++}}</td>
										<td>
											{{$crpsUser->Role}}
										</td>
										<td>{{$crpsUser->FullName}}</td>
										<td>{{$crpsUser->username}}</td>
										<td>{{$crpsUser->Agency}}</td>
										<td>
											<a href="{{URL::to('sys/user/'.$crpsUser->Id)}}" class="resetuserpassword btn default btn-xs green-seagreen"><i class="fa fa-edit"></i>Edit</a>|
											<a href="{{URL::to('sys/deleteuser/'.$crpsUser->Id)}}" class="resetuserpassword btn default btn-xs red"><i class="fa fa-times"></i>Delete</a>
										</td>
									</tr>
								@endforeach
								</tbody>
							</table>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="tab-pane" id="applicant">
						<div class="table-responsive col-md-12 col-sm-12 col-xs-12">
							<table id="tablefilters_1" class="table table-condensed table-striped table-bordered table-hover table-responsive">
								<thead>
								<tr>
									<th>Sl#</th>
									<th class="order">Role</th>
									<th>CDB No.</th>
									<th>User Full Name</th>
									<th>Username</th>
									<th>Action</th>
								</tr>
								</thead>
								<tbody>
								<?php $slNo = 1; ?>
								@foreach($applicantUsers as $applicantUser)
									<tr>
										<td>{{$slNo++}}</td>
										<td>
											{{$applicantUser->Role}}
										</td>
										<td>{{$applicantUser->CDBNo}}</td>
										<td>{{$applicantUser->FullName}}</td>
										<td>{{$applicantUser->username}}</td>

										<td>
											<a href="{{URL::to('sys/user/'.$applicantUser->Id)}}" class="resetuserpassword btn default btn-xs green-seagreen"><i class="fa fa-edit"></i>Edit</a>|
											<a href="{{URL::to('sys/deleteuser/'.$applicantUser->Id)}}" class="resetuserpassword btn default btn-xs red"><i class="fa fa-times"></i>Delete</a>
										</td>
									</tr>
								@endforeach
								</tbody>
							</table>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>
@stop