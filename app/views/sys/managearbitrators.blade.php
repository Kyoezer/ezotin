@extends('master')
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption font-green-seagreen">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">Manage Arbitrators</span>
		</div>
	</div>
	<div class="portlet-body">
		<div class="row">
			{{Form::open(array('url'=>'web/savearbitrators','files'=>true))}}
			<div class="table-responsive col-md-12 col-sm-12 col-xs-12">
				<table id="tablefilters_1" class="table table-condensed table-striped table-bordered table-hover table-responsive">
					<thead>
						<tr>
							<th></th>
							<th style="width: 90px;">Reg No.</th>
							<th class="col-md-2">Name</th>
							<th class="col-md-2">Designation</th>
							<th style="width: 220px;">Email Address</th>
							<th style="width: 100px;">Contact No.</th>
							<th style="width: 110px;">Cases in Hand</th>
							<th>Image</th>
						</tr>
					</thead>
					<tbody>
						@foreach($arbitrators as $arbitrator)
							<?php $randomKey = randomString(); ?>
						<tr>
							<td>
								<button type="button" class="deletearbitrator"><i class="fa fa-times fa-lg"></i></button>
							</td>
							<td><input type="hidden" name="webarbitrator[{{$randomKey}}][Id]" class="resetKeyForNew form-control input-sm" value="{{$arbitrator->Id}}"/>
								<input type="text" name="webarbitrator[{{$randomKey}}][RegNo]" class="resetKeyForNew form-control input-sm" value="{{$arbitrator->RegNo}}"/></td>
							<td><input type="text" name="webarbitrator[{{$randomKey}}][Name]" class="resetKeyForNew form-control input-sm" value="{{$arbitrator->Name}}"/></td>
							<td><input type="text" name="webarbitrator[{{$randomKey}}][Designation]" class="resetKeyForNew form-control input-sm" value="{{$arbitrator->Designation}}"/></td>
							<td><input type="text" name="webarbitrator[{{$randomKey}}][Email]" class="resetKeyForNew form-control input-sm" value="{{$arbitrator->Email}}"/></td>
							<td><input type="text" name="webarbitrator[{{$randomKey}}][ContactNo]" class="resetKeyForNew form-control input-sm" value="{{$arbitrator->ContactNo}}"/></td>
							<td><input type="text" name="webarbitrator[{{$randomKey}}][CasesInHand]" class="resetKeyForNew form-control input-sm" value="{{$arbitrator->CasesInHand}}"/></td>
							<td>
								@if((bool)$arbitrator->FilePath)
									<div class="arb-image">
										<img src="{{asset($arbitrator->FilePath)}}" class="img-responsive"/>
										<input type="hidden" name="webarbitrator[{{$randomKey}}][OldImage]" value="{{$arbitrator->FilePath}}"/>
										<input type="hidden" name="webarbitrator[{{$randomKey}}][OldImageType]" value="{{$arbitrator->FileType}}"/>
									</div>
								@endif
								<input type="file" name="webarbitrator[{{$randomKey}}][Image]" class="resetKeyForNew form-control input-sm" />
							</td>
						</tr>
						@endforeach
						<tr class="notremovefornew">
							<td>
								<button type="button" class="addtablerow"><i class="fa fa-plus fa-lg"></i></button>
							</td>
							<td colspan="7"></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="form-actions">
				<div class="btn-set">
					<button type="submit" class="btn green">Save</button>
					<a href="{{URL::to('web/arbitratorlist')}}" class="btn red">Cancel</a>
				</div>
			</div>
			{{Form::close()}}
		</div>
	</div>
</div>
@stop