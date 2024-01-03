@extends('reportsmaster')
@section('pagescripts')
    {{HTML::script('assets/global/scripts/report.js')}}
@stop
@section('content')
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
                <i class="fa fa-cogs"></i>List of Engaged Hr &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route('etoolrpt.listofengagedhretool',$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route('etoolrpt.listofengagedhretool',$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
            </div>
        </div>
        <div class="portlet-body flip-scroll">
            @if(Input::get('export') != 'print')
            {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
            <div class="form-body">
        <div class="row">
      
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">CDB No</label>
						<input type="text" name="CDBNo" value="{{Input::get('CDBNo')}}" class="form-control" />
					</div>
				</div>

         				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">CID No</label>
						<input type="text" name="cidNo" value="{{Input::get('cidNo')}}" class="form-control" />
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
      
                <table class="table table-bordered table-striped table-condensed flip-content" id="">
                    <thead class="flip-content">
                    <tr>
                        <th>Sl.No.</th>
                        <th>CDB No</th>
                        <th>Name</th>
                        <th>CIDNo</th>
                        <th>Designation</th>
                    <th>Agency</th>
                        <th>Work Id</th>
         
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($reportData as $data)
                        <tr>
                            <td>{{$start++}}</td>
                            <td>{{$data->CDBNo}}</td>
                            <td>{{$data->hrName}}</td>
                            <td>{{$data->cidNo}}</td>
                            <td>{{$data->Designation}}</td>
                           <td>{{$data->procuringAgency}}</td>
                            <td>{{$data->WorkId}}</td>
                          
                           
                        </tr>
                    @empty
                        <tr><td colspan="4" class="font-red text-center">No data to display</td></tr>
                    @endforelse
                    </tbody>
                </table>
                <?php pagination($noOfPages,Input::all(),Input::get('page'),"etoolrpt.listofengagedhretool"); ?>
        </div>
    </div>
@stop