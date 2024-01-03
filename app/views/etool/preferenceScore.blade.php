@extends('master')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-gift"></i>
					<span class="caption-subject font-green-seagreen">Edit Preference Score</span>
				</div>
			</div>
			<div class="portlet-body form">
				{{ Form::open(array('url' => 'etoolsysadm/postSaveBhutaneseEmploymentPreference','role'=>'form','class'=>'form-horizontal'))}}
					<div class="form-body">
						 
                        <div class="col-md-6">
						<div class="table-responsive">
							<table id="ContractorAdd" class="table table-bordered table-striped table-condensed">
								<thead>
									<tr>
										<th>
										</th>
										<th>
											Criteria
										</th>
										<th width="20%" class="numeric">
											Points
										</th>
									</tr>
								</thead>
								<tbody>
									<?php $count=0; ?>
									@foreach($parameters as $param)
									<tr>
										<?php $randomKey=randomString();?>
										<td>
											<button type="button" class="deletetablerow"><i class="fa fa-times"></i></button>
										</td>
										<td>
											<input type="text" name="ChildTable[{{$randomKey}}][Name]" class="form-control resetKeyForNew input-sm required" value="{{$param->Name}}">
                                            <input type="hidden" name="ChildTable[{{$randomKey}}][Id]" class="form-control resetKeyForNew input-sm" value="{{$param->Id}}">
                                        </td>
										<td>
											<input type="text" name="ChildTable[{{$randomKey}}][Points]" class="form-control resetKeyForNew input-sm resetKeyForNew text-right required number" value="{{$param->Points}}">
										</td>
									</tr>
									<?php $count++;?>
									@endforeach
									<tr class="notremovefornew">
										<td>
											<button type="button" class="addtablerow"><i class="fa fa-plus fa-lg"></i></button>
										</td>
										<td colspan="3"></td>
									</tr>
								</tbody>
							</table>
						</div>
                        </div><div class="clearfix"></div>
					</div>
					<div class="form-actions">
						<div class="btn-set">
                            <a href="{{URL::to('etoolsysadm/postSaveBhutaneseEmploymentPreference')}}" class="btn purple"><i class="m-icon-swapleft m-icon-white"></i>&nbsp;Back</a>
							<button type="submit" class="btn green">Update</button>
							<a href="{{URL::to(Request::url())}}" class="btn red">Cancel</a>
						</div>
					</div>
				{{Form::close()}}
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>
@stop