@extends('master')
@section('content')
<div class="portlet box blue-hoki">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>Work Summary
		</div>
		<div class="tools">
			<a href="javascript:;" class="collapse"></a>
		</div>
	</div>
	<div class="portlet-body flip-scroll">
		<div class="row">
			<div class="col-md-6">
				<table class="table table-bordered table-striped table-condensed flip-content">
					<tbody>
						<tr>
							<td><strong>Procuring Agency</strong></td>
							<td>Anti Corruption Commision</td>
						</tr>
						<tr>
							<td><strong>Name of Contractor</strong></td>
							<td>Druk Tendrel Lhurig</td>
						</tr>
						<tr>
							<td><strong>Name of Contract Work</strong></td>
							<td>C/o Acc Office Building</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-md-6">
				<table class="table table-bordered table-striped table-condensed flip-content">
					<tbody>
						<tr>
							<td><strong>Contract Description</strong></td>
							<td>C/o Acc Office Building</td>
						</tr>
						<tr>
							<td><strong>Category of Work</strong></td>
							<td>Building and Airports, Irrigation, Drainage & FO (W3)</td>
						</tr>
						<tr>
							<td><strong>Class</strong></td>
							<td>Large (L)</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="portlet box blue-hoki">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>Work Completion Form
		</div>
	</div>
	<div class="portlet-body flip-scroll">
		<div class="form-body">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Status</label>
						<select class="form-control input-sm">
							<option>---SELECT ONE---</option>
							<option>Awarded (009.001)</option>
							<option>Working (009.003)</option>
							<option>Contract Terminated (009.005)</option>
							<option>Not Awarded (009.006)</option>
							<option>Completed</option>
						</select>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Contract Price (Initial) Nu.</label>
						<input type="text" class="form-control" value="12882826" class="text-right">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Contract Price (Final) Nu.</label>
						<input type="text" class="form-control" value="12882826" class="text-right">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label">Date of Commencement (Offcial)</label>
						<div class="input-group date date-picker" data-date="12-02-2012" data-date-format="yyyy-mm-dd">
							<input type="text" class="form-control" readonly>
							<span class="input-group-btn">
							<button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
							</span>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label">Date of Commencement (Actual)</label>
						<div class="input-group date date-picker" data-date="12-02-2012" data-date-format="yyyy-mm-dd">
							<input type="text" class="form-control" readonly>
							<span class="input-group-btn">
							<button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
							</span>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label">Date of Completion (Offcial)</label>
						<div class="input-group date date-picker" data-date="12-02-2012" data-date-format="yyyy-mm-dd">
							<input type="text" class="form-control" readonly>
							<span class="input-group-btn">
							<button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
							</span>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label">Date of Completion (Actual)</label>
						<div class="input-group date date-picker" data-date="12-02-2012" data-date-format="yyyy-mm-dd">
							<input type="text" class="form-control" readonly>
							<span class="input-group-btn">
							<button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="control-label">Ontime Completion</label>
						<input type="text" class="form-control" />
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="control-label">Quality of Execution</label>
						<input type="text" class="form-control" />
					</div>
				</div>
			</div>
		</div>
		<div class="form-actions">
			<div class="btn-set">
				<button type="submit" class="btn green updateaction">Update</button>
				<button type="button" class="btn red">Cancel</button>
			</div>
		</div>
	</div>
</div>
@stop