@extends('reportsmaster')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
			<i class="fa fa-cogs"></i>Equipment Summary &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route('contractorrpt.equipmentreport',$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route('contractorrpt.equipmentreport',$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		</div>
	</div>
	<div class="portlet-body flip-scroll">
        @if(Input::get('export') != "print")
            {{Form::open(array('url'=>Request::segment(1).'/'.Request::segment(2),'method'=>'get'))}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Dzongkhag</label>
                            <select class="form-control select2me" name="Dzongkhag">
                                <option value="">---SELECT ONE---</option>
                                @foreach($dzongkhags as $dzongkhag)
                                    <option value="{{$dzongkhag->NameEn}}" @if($dzongkhag->NameEn == Input::get('Dzongkhag'))selected @endif>{{$dzongkhag->NameEn}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Type</label>
                            {{Form::select('Type',array(''=>'All','1'=>'Registered','2'=>'Not Registered'),Input::get('Type'),array('class'=>'form-control'))}}
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
        @else
            @if(Input::has('Dzongkhag'))
                <b>Dzongkhag: {{Input::get('Dzongkhag')}}</b> <br/>
            @else
                <b>Dzongkhag: All</b> <br/>
            @endif
            @if(Input::has('Type'))
                <b>Type: @if(Input::get('Type') == 1){{"Registered"}}@else{{"Not Registered"}}@endif</b> <br/>
            @else
                <b>Type: All</b><br/>
            @endif
        @endif
		<table class="table table-bordered table-striped table-condensed flip-content" id="contractorhumanresource">
			<thead class="flip-content">
				<tr>
					<th class="order">
						 Sl. No.
					</th>
                    <th>
                        Type
                    </th>
					<th>
						Equipment Name
					</th>
					<th>
                        Quantity
                    </th>
				</tr>
			</thead>
			<tbody>
            <?php $count = 1; $total = 0; ?>
            @forelse($equipmentGroups as $equipmentGroup)
				<tr>
                    <td class="text-center">{{$count}}</td>
                    <td>{{$equipmentGroup->Type}}</td>
                    <td>{{$equipmentGroup->Name}}</td>
                    <td class="text-right">{{$equipmentGroup->Quantity}}<?php $total+=$equipmentGroup->Quantity; ?></td>
				</tr>
                <?php $count++; ?>
            @empty
                <tr>
                    <td colspan="4" class="text-center font-red">No data to display</td>
                </tr>
            @endforelse
            @if(count($equipmentGroups) > 0)
            <tr>
                <td class="text-right bold">Total: </td>
                <td></td>
                <td></td>
                <td class="bold text-right">{{$total}}</td>
            </tr>
            @endif
			</tbody>
		</table>
	</div>
</div>
@stop