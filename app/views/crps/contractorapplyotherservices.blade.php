@extends('horizontalmenumaster')
@section('content')
<div id="selectservicesmodal" class="modal fade in" role="dialog" aria-labelledby="modalmessagebox" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3 id="modalmessageheader" class="modal-title font-red-intense">Select services you want to apply</h3>
			</div>
			<div class="modal-body">
				{{Form::open(array('url'=>'contractor/saveservices','role'=>'form'))}}
				<div class="form-body">
					<div class="form-group">
						<div class="checkbox-list">
							<label>
								<input type="checkbox" name="ChangeOfLocationOwner[]" value="{{CONST_SERVICETYPE_CHANGELOCATION}}" data-type="1"> Change of Location</label>
							<label>
								<input type="checkbox" name="ChangeOfLocationOwner[]" value="{{CONST_SERVICETYPE_CHANGEOWNER}}" data-type="2"> Change of Owner</label>
							<label>
								<input type="checkbox" name="ChangeOfLocationOwner[]" value="{{CONST_SERVICETYPE_CHANGEOFFIRMNAME}}" data-type="3"> Change of Firm Name</label>
							<label>
								<input type="checkbox" name="OtherServices[]" value="{{CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION}}"> Upgrade/Downgrade/Add Category/Classification</label>
							<label>
								<input type="checkbox" name="OtherServices[]" value="{{CONST_SERVICETYPE_UPDATEHUMANRESOURCE}}"> Update Human Resource</label>
							<label>
								<input type="checkbox" name="OtherServices[]" value="{{CONST_SERVICETYPE_UPDATEEQUIPMENT}}"> Update Equipment</label>
							<label>
								<input type="checkbox" name="OtherServices[]" value="{{CONST_SERVICETYPE_INCORPORATION}}"> Incorporation</label>
						</div>
					</div>
				</div>
				<div class="form-action">
					<div class="row">
						<div class="col-md-2 pull-right">
							<button type="submit" class="btn red"><i class="fa fa-arrow-circle-o-right"></i>&nbsp;&nbsp;&nbsp;Proceed</button>
						</div>
					</div>
				</div>
				{{Form::close()}}
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-offset-2 col-md-8">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption font-green-seagreen">
					<i class="fa fa-cogs"></i>
					<span class="caption-subject">Application for Other Services</span>
				</div>
			</div>
			<div class="portlet-body">
				<blockquote>
					<p class="text-justify">
						You can apply for multiple services in this section of your application togather.Relevant fees will be applicable. You can skip updating/upgrading of other information or services if you wish to apply for only one service. Below is the list of all the services that you can avail with this application.
					</p>
				</blockquote>
				<div class="col-md-12">
					<h4 class="font-green-seagreen">Services Fee Structure</h4>
					<table class="table table-bordered table-striped table-condensed">
						<thead>
							<tr>
								<th>Service Name</th>
								<th>Fees (Nu.)</th>
							</tr>
						</thead>
						<tbody>
							{{--<tr>--}}
								{{--<td>Update General Information</td>--}}
								{{--<td>Not Applicable</td>--}}
							{{--</tr>--}}
							<tr>
								<td>Incorporation</td>
								<td>500</td>
							</tr>
							<tr>
								<td>Change of Location</td>
								<td>500</td>
							</tr>
							<tr>
								<td>Change of Owner/Partner and other Controlling intrest</td>
								<td>1000</td>
							</tr>
							<tr>
								<td>Upgrade/Downgrade/Add Category or Classification</td>
								<td>Applicable based on category type (<i>Refer right side table</i>)</td>
							</tr>
							<tr>
								<td>Update Human Resource</td>
								<td>Not Applicable</td>
							</tr>
							<tr>
								<td>Update Equipment</td>
								<td>Not Applicable</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="col-md-12">
					<h4 class="font-green-seagreen">Fee Structure for Category Change</h4>
					<table class="table table-bordered table-striped table-condensed">
						<thead>
							<tr>
								<th>Category</th>
								<th class="text-right">Amount (Nu.)</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Large</td>
								<td class="text-right">
									7500
								</td>
							</tr>
							<tr>
								<td>Medium</td>
								<td class="text-right">
									5000
								</td>
							</tr>
							<tr>
								<td>Small</td>
								<td class="text-right">
									2500
								</td>
							</tr>
							<tr>
								<td>Registered</td>
								<td class="text-right">
									1000
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="form-actions">
					<div class="row">
						<div class="col-md-12">
							{{--<a href="#selectservicesmodal" data-toggle="modal" class="btn purple">Select Services</a>--}}
							<a href="{{URL::to('contractor/applyservicegeneralinfo/'.$contractorId)}}" class="btn green">Proceed <i class="fa fa-arrow-circle-o-right"></i></a>
							<a href="{{URL::to('contractor/profile')}}" class="btn red">Cancel <i class="fa fa-times"></i></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop