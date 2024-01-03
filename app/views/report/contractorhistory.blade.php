@extends($master)
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel'; if(Request::segment(1) == 'contractor'):$route="contractor.contractorhistory";else: $route = 'contractorrpt.contractorhistory'; endif;?>
            @if(!Input::has('export'))
            <i class="fa fa-cogs"></i>Contractor Registration History &nbsp;&nbsp;@if(!Input::has('export'))   <?php $parameters['export'] = 'print'; ?><a href="{{route($route,$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		    @endif
        </div>
	</div>
	<div class="portlet-body flip-scroll">
        @if(Input::get('export') != "print")
        {{Form::open(array('url'=>str_replace('.','/',$route),'method'=>'get'))}}
		<div class="form-body">
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">CDB No</label>
						<input type="text" name="CDBNo" value="{{Input::get('CDBNo')}}" class="form-control" />
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
            @foreach($parametersForPrint as $key=>$value)
                <b>{{$key}}: {{$value}}</b><br>
            @endforeach
            <br/>
        @endif
		<table class="table table-bordered table-striped table-condensed flip-content" id="contractorhumanresource">
			<thead class="flip-content">
				<tr>
                    <th>
                        Sl.No.
                    </th>
					<th>
						Firm/Name
					</th>
                    <th>CDB No.</th>
                    <th>Application Date</th>
					<th class="">
						 Type
					</th>
                    <th class="">
                        W1
                    </th>
                    <th class="">
                        W2
                    </th>
                    <th class="">
                        W3
                    </th>
                    <th class="">
                        W4
                    </th>
				</tr>
			</thead>
			<tbody>
            <?php $count = 1; ?>
            @forelse($reportData as $data)
				<tr>
                    <td>{{$count}}</td>
                    <td>{{$data->NameOfFirm}}</td>
                    <td>{{$data->CDBNo}}</td>
                    <td>{{convertDateToClientFormat($data->ApplicationDate)}}</td>
                    <td>{{$data->Action}}</td>
                    <td>{{$data->W1}}</td>
                    <td>{{$data->W2}}</td>
                    <td>{{$data->W3}}</td>
                    <td>{{$data->W4}}</td>
				</tr>
                <?php $count++; ?>
            @empty
                <tr>
                    <td colspan="9" class="text-center font-red">@if(Input::has('CDBNo'))No data to display! @else{{"Please select a CDB No"}}@endif</td>
                </tr>
            @endforelse
			</tbody>
		</table>
	</div>
</div>
@stop