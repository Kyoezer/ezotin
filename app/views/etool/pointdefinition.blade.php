@extends('master')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-gift"></i>
					<span class="caption-subject font-green-seagreen">Edit Evaluation Parameter</span>
				</div>
			</div>
			<div class="portlet-body form">
				{{ Form::open(array('url' => 'etoolsysadm/saveevaluationparameter','role'=>'form','class'=>'form-horizontal'))}}
					<div class="form-body">
						@foreach($pointType as $value)
                        {{Form::hidden('Id',$value->Id)}}
                            <h5 class="bold font-blue-madison">{{$value->Name}}</h5>
						<div class="form-group">
							<label class="control-label col-md-2">Max Points</label>
							<div class="col-md-2">
								<input type="text" name="MaxPoints" class="form-control input-sm required number text-right" placeholder="Max Points" value="{{$value->MaxPoints}}"/>
							</div>
						</div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Min Points</label>
                            <div class="col-md-2">
                                <input type="text" name="MinPoints" class="form-control input-sm required number text-right" placeholder="Min Points" value="{{$value->MinPoints}}"/>
                            </div>
                        </div>
						@endforeach
                        <div class="col-md-6">
						<div class="table-responsive">
							<table id="ContractorAdd" class="table table-bordered table-striped table-condensed">
								<thead>
									<tr>
										<th>
										</th>
										<th>
											Lower Limit
										</th>
										<th>
											Upper Limit
										</th>
										<th width="20%" class="numeric">
											Points
										</th>
									</tr>
								</thead>
								<tbody>
									<?php $count=0; ?>
									@foreach($pointDefinitions as $pointDefinition)
									<tr>
										<?php $randomKey=randomString();?>
										<td>
											<button type="button" class="deletetablerow"><i class="fa fa-times"></i></button>
										</td>
										<td>
											<input type="text" name="ChildTable[{{$randomKey}}][LowerLimit]" class="form-control resetKeyForNew input-sm text-right required number" value="{{$pointDefinition->LowerLimit}}">
                                        </td>
                                        <td>
                                            <input type="text" name="ChildTable[{{$randomKey}}][UpperLimit]" class="form-control resetKeyForNew input-sm text-right required number" value="{{$pointDefinition->UpperLimit}}">
                                        </td>
										<td>
											<input type="text" name="ChildTable[{{$randomKey}}][Points]" class="form-control resetKeyForNew input-sm resetKeyForNew text-right required number" value="{{$pointDefinition->Points}}">
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
                            <a href="{{URL::to('etoolsysadm/selectevaluationparameters')}}" class="btn purple"><i class="m-icon-swapleft m-icon-white"></i>&nbsp;Back</a>
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