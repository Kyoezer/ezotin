@extends('master')
@section('pagescripts')
    {{ HTML::script('assets/global/scripts/etool.js') }}
@stop
@section('content')
<div class="portlet bordered light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>Replace/Release HR or Equipment
		</div>
	</div>
	<div class="portlet-body flip-scroll">
        {{Form::open(array('url'=>'etoolsysadm/replacereleasehrequipment','method'=>'post'))}}
        <div class="form-body">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Work Id</label>
                        <input type="text" name="WorkId" value="{{Input::get('WorkId')}}" class="form-control input-sm" />
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">CDB No</label>
                        <input type="text" id="rr-cdbno" name="CDBNo" value="{{$cdbNo}}" class="form-control input-sm" />
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="control-label">&nbsp;</label>
                    <div class="btn-set">
                        <button type="submit" id="rr-submit" class="btn blue-hoki btn-sm">Search</button>
                        <a href="{{Request::url()}}" class="btn grey-cascade btn-sm">Clear</a>
                    </div>
                </div>
            </div>
        </div>
        {{Form::close()}}
        @if(!empty($tenderDetails))
            <div class="table-responsive">
                <table class="table table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th>Sl #</th>
                            <th>Work Id</th>
                            <th>Name of Work</th>
                            <th>Classification</th>
                            <th>Category</th>
                            <th>Estimated Cost</th>
                            <th>Contract Period</th>
                            <th>Agency</th>
                            <th>Dzongkhag</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $slNo = 1; ?>
                        @foreach($tenderDetails as $tender)
                            <tr>
                                <td>{{$slNo++}}</td>
                                <td>{{$tender->WorkId}}</td>
                                <td>{{$tender->NameOfWork}}</td>
                                <td>{{$tender->Classification}}</td>
                                <td>{{$tender->Category}}</td>
                                <td>{{$tender->ProjectEstimateCost}}</td>
                                <td>{{$tender->ContractPeriod}}</td>
                                <td>{{$tender->ProcuringAgency}}</td>
                                <td>{{$tender->Dzongkhag}}</td>
                                <td>
                                    {{Form::open(array('url'=>'etoolsysadm/replacereleasehrequipment','method'=>'post'))}}
                                        <input type="hidden" name="WorkId" value="{{trim($tender->WorkId)}}" class="form-control input-sm" />
                                        <button type="submit" class="btn btn-xs blue">View</button>
                                    {{Form::close()}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
	</div>
</div>
@stop