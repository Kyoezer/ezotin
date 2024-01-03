@extends('reportsmaster')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
			<i class="fa fa-cogs"></i>Engineers &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route('contractorrpt.engineers',$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route('contractorrpt.engineers',$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		</div>
	</div>
	<div class="portlet-body flip-scroll">
        {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
		<div class="form-body">
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">CDB No</label>
						<input type="text" name="CDBNo" value="{{Input::get('CDBNo')}}" class="form-control" />
					</div>
				</div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Dzongkhag</label>
                        <select class="form-control select2me" name="Dzongkhag">
                            <option value="">---SELECT ONE---</option>
                            @foreach($dzongkhags as $dzongkhag)
                                <option value="{{$dzongkhag->Dzongkhag}}" @if($dzongkhag->Dzongkhag == Input::get('Dzongkhag'))selected @endif>{{$dzongkhag->Dzongkhag}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
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
		<table class="table table-bordered table-striped table-condensed flip-content" id="contractorhumanresource">
			<thead class="flip-content">
				<tr>
					<th class="order">
						 Sl. No.
					</th>
					<th>
						Firm, Proprietor
					</th>
					<th class="">
						 W1
					</th>
					<th class="">
						 W2
					</th>
					<th>
						W3
					</th>
					<th>
						W4
					</th>
					<th class="">
						 Civil (Degree)
					</th>
					<th class="">
						Civil (Diploma)
					</th>
                    <th class="">
                        Electrical (Degree)
                    </th>
                    <th class="">
                        Electrical (Diploma)
                    </th>
				</tr>
			</thead>
			<tbody>
            @foreach($contractorsList as $contractor)
				<tr>
                    <td>{{$start++}}</td>
                    <td>{{$contractor->Contractor}}</td>
                    <td>{{$contractor->Classification1}}</td>
                    <td>{{$contractor->Classification2}}</td>
                    <td>{{$contractor->Classification3}}</td>
                    <td>{{$contractor->Classification4}}</td>
                    <td class="text-right">{{$civilDegree[$contractor->Id][0]->CivilDegree}}</td>
                    <td class="text-right">{{$civilDiploma[$contractor->Id][0]->CivilDiploma}}</td>
                    <td class="text-right">{{$electricalDegree[$contractor->Id][0]->ElectricalDegree}}</td>
                    <td class="text-right">{{$electricalDiploma[$contractor->Id][0]->ElectricalDiploma}}</td>
				</tr>
                @endforeach
			</tbody>
		</table>
		<?php pagination($noOfPages,Input::all(),Input::get('page'),"contractorrpt.engineers"); ?>
	</div>
</div>
@stop