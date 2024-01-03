@extends('horizontalmenumaster')
@section('content')
<div id="awardwork" class="modal fade" role="dialog" aria-labelledby="awardwork" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-warning">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title bold font-green-seagreen">Award Contractor</h4>
			</div>
			<div class="modal-body">
				<h4 class="bold">Are you sure you want to award this work to selected Contractor?</h4>
				<h5 class="bold">Work Id : <span class="font-green-seagreen ">CDB/2014/56647</span></h5>
				<h5 class="bold">Contractor : <span class="font-green-seagreen ">Kinley Construction Pvt Ltd</span></h5>
				<p><span class="bold">Name : </span>Construction of XYZ at Zhemganag</p>
				<form action="#" class="" role="form">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Actual Start date</label>
								<div class="input-group date date-picker" data-date="12-02-2012" data-date-format="yyyy-mm-dd">
									<input type="text" class="form-control input-sm" readonly>
									<span class="input-group-btn">
									<button class="btn default input-sm" type="button"><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Actual End date</label>
								<div class="input-group date date-picker" data-date="12-02-2012" data-date-format="yyyy-mm-dd">
									<input type="text" class="form-control input-sm" readonly>
									<span class="input-group-btn">
									<button class="btn default input-sm" type="button"><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Awarded Amount</label>
								<input type="text" class="form-control input-sm" />
							</div>
						</div>
					</div>
					<div class="form-group">
						<label>Remarks</label>
						<textarea class="form-control" rows="3"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button class="btn green" data-dismiss="modal">Award</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</div>
	</div>
</div>
<div id="deletecontractor" class="modal fade" role="dialog" aria-labelledby="deletecontractor" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-warning">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title bold font-green-seagreen">Delete Contractor</h4>
			</div>
			<div class="modal-body">
				<h4 class="bold">Are you sure you want to delete this Contractor?</h4>
				<form action="#" class="" role="form">
					<div class="form-group">
						<label>Remarks</label>
						<textarea class="form-control" rows="3"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button class="btn green" data-dismiss="modal">Delete</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</div>
	</div>
</div>
<div class="page-bar">
	<ul class="page-breadcrumb">
		<li>
			<a href="#">E-TOOL</a>
			<i class="fa fa-angle-right"></i>
		</li>
		<li>
			<a href="#">Process Result</a>
		</li>
	</ul>
</div>
<div class="portlet box blue-hoki">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>Work Details
		</div>
	</div>
	<div class="portlet-body flip-scroll">
		<div class="form-body">
			<h4 class="bold">Work Id : <span class="font-green-seagreen ">CDB/2014/56647</span></h4>
			<p><span class="bold">Name : </span>Construction of XYZ at Zhemganag</p>
			<p><span class="bold">Description : </span> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ut aspernatur soluta minus, temporibus debitis suscipit nemo officia, at quasi voluptatibus culpa quae deleniti asperiores, fugit ipsa unde. In, dignissimos, eos.</p>
			<table class="table table-bordered table-striped table-condensed flip-content">
				<thead class="flip-content">
					<tr>
						<th width="6%">Sl No.</th>
						<th>
							CDB No.
						</th>
						<th class="">
							 Contractor/Firm
						</th>
						<th>
							Joint Venture
						</th>
						<th>
							Classification
						</th>
						<th class="">
							 Category
						</th>
						<th class="">
							 Result
						</th>
						<th>
							Action
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							1
						</td>
						<td>
							123312
						</td>
						<td>
							Kinley Construction
						</td>
						<td>
							No
						</td>
						<td>
							M
						</td>
						<td>
							W1
						</td>
						<td>
							H1
						</td>
						<td>
							<a href="{{URL::to('workevaluationaddcontractors')}}" class="btn btn-xs bg-green-haze"><i class="fa fa-edit"></i> Edit</a>
							<a href="#awardwork" data-toggle="modal" class="btn btn-xs bg-purple-plum"><i class="fa fa-edit"></i> Award</a>
							<a href="{{URL::to('workevaluationpointdetails')}}" class="btn btn-xs bg-blue-hoki"><i class="fa fa-edit"></i> Point Details</a>
							<a href="#deletecontractor" data-toggle="modal" class="btn btn-xs bg-red-sunglo"><i class="fa fa-edit"></i> Delete</a>
						</td>
					</tr>
					<tr>
						<td>
							2
						</td>
						<td>
							665777
						</td>
						<td>
							Sangay Wangdi Construction
						</td>
						<td>
							Yes
						</td>
						<td>
							M
						</td>
						<td>
							W1
						</td>
						<td>
							Not Qualified
						</td>
						<td>
							<a href="{{URL::to('workevaluationaddcontractors')}}" class="btn btn-xs bg-green-haze"><i class="fa fa-edit"></i> Edit</a>
							<a href="#awardwork" data-toggle="modal" class="btn btn-xs bg-purple-plum"><i class="fa fa-edit"></i> Award</a>
							<a href="{{URL::to('workevaluationpointdetails')}}" class="btn btn-xs bg-blue-hoki"><i class="fa fa-edit"></i> Point Details</a>
							<a href="#deletecontractor" data-toggle="modal" class="btn btn-xs bg-red-sunglo"><i class="fa fa-edit"></i> Delete</a>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="form-controls">
			<div class="btn-set">
				<a href="{{URl::to('workevaluationaddcontractors')}}" class="btn green btn-sm"><i class="fa fa-plus"></i> Add Contractors</a>
				<button type="button" class="btn blue-chambray btn-sm"><i class="fa fa-cogs"></i> Process Result</button>
				<a href="{{URL::to('workevaluationdetails')}}" class="btn grey-cascade btn-sm"><i class="fa fa-refresh"></i> Reset Result</a>
				<button type="button" class="btn red btn-sm"><i class="fa fa-book"></i> View Report</button>
			</div>
		</div>
	</div>
</div>
@stop