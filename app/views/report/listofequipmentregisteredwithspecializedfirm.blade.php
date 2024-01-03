@extends('reportsmaster')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
            @if(!Input::has('export'))
            <i class="fa fa-cogs"></i>List of Equipment Registered with Specialized Firm  &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route("specializedfirmrpt.listofeuipmentregisteredwithspecializedfirm",$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel </a>   <?php $parameters['export'] = 'print'; ?><a href="{{route("specializedfirmrpt.listofeuipmentregisteredwithspecializedfirm",$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		    @endif
        </div>
	</div>
	<div class="portlet-body flip-scroll">
        @if(Input::get('export') != "print")
        {{Form::open(array('url'=>'specializedfirmrpt/listofeuipmentregisteredwithspecializedfirm','method'=>'get'))}}
		<div class="form-body">
        <div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">SP No</label>
						<input type="text" name="SPNo" value="{{Input::get('SPNo')}}" class="form-control" />
					</div>
				</div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Firm</label>
                        <input type="text" class="form-control" name="SpecializedfirmId" placeholder="Name of Firm"  value="{{Input::get('SpecializedfirmId')}}" />
                    </div>
                </div>
              
                <div class="col-md-2">
                    <label class="control-label">Equipment Name:</label>
                    <select class="form-control select2me" name="CmnEquipmentId">
                        <option value="">---SELECT ONE---</option>
                        @foreach($equipmentId as $equipment)
                            <option value="{{$equipment->Name}}" @if($equipment->Name == Input::get('CmnEquipmentId'))selected @endif>{{$equipment->Name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Registration No.</label>
                        <input type="text" class="form-control" name="FirmId"  value="{{Input::get('FirmId')}}" />
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
                    Name Of Firm
                </th>
                <th>
                    Equipment Name
                </th>
                <th class="">
                    Registration No.
                </th>
                <th class="">
                    SPNo
                </th>
                      
            </tr>
            </thead>
            <tbody>
            @forelse($specializedfirmLists as $specializedfirm)
                <tr>
                    <td>{{$start++}}</td>
                    <td>{{$specializedfirm->Firm}}</td>
                    <td>{{$specializedfirm->EquimentName}}</td>
                    <td>{{$specializedfirm->RegistrationNo}}</td>
                    <td>{{$specializedfirm->SPNo}}</td>
                
       
                </tr>
            @empty
                <tr>
                    <td colspan="14" class="text-center font-red">No data to display!</td>
                </tr>
            @endforelse
            </tbody>
		</table>
            <?php pagination($noOfPages,Input::all(),Input::get('page'),"specializedfirmrpt.listofeuipmentregisteredwithspecializedfirm"); ?>
	</div>
</div>
@stop