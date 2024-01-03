@extends('reportsmaster')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
            @if(!Input::has('export'))
            <i class="fa fa-cogs"></i>List of Contractor with works &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route("contractorrpt.workinhand",$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel </a>   <?php $parameters['export'] = 'print'; ?><a href="{{route("contractorrpt.workinhand",$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		    @endif
        </div>
	</div>
	<div class="portlet-body flip-scroll">
        @if(Input::get('export') != "print")
        {{Form::open(array('url'=>'contractorrpt/workinhand','method'=>'get'))}}
		<div class="form-body">
        <div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">CDB No</label>
						<input type="text" name="CDBNo" value="{{Input::get('CDBNo')}}" class="form-control" />
					</div>
				</div>
              
                <div class="col-md-2">
                    <label class="control-label">Dzongkhag:</label>
                    <select class="form-control select2me" name="CmnDzongkhagId">
                        <option value="">---SELECT ONE---</option>
                        @foreach($dzongkhagId as $dzongkhag)
                            <option value="{{$dzongkhag->NameEn}}" @if($dzongkhag->NameEn == Input::get('CmnDzongkhagId'))selected @endif>{{$dzongkhag->NameEn}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Classification:</label>
                        <select class="form-control select2me" name="CmnContractorClassificationId">
                            <option value="">---SELECT ONE---</option>
                            @foreach($contractorClassificationId as $contractorClassification)
                                <option value="{{$contractorClassification->Code}}" @if($contractorClassification->Code == Input::get('CmnContractorClassificationId'))selected @endif>{{$contractorClassification->Name.' ('.$contractorClassification->Code.')'}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">No. Of Works:</label>
                        <input type="text" class="form-control" name="ContractorId" placeholder="No. Of Works"  value="{{Input::get('ContractorId')}}" />
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
                <th class="order">
                CDB No.
                </th>
                <th>
                    Dzongkhag
                </th>
                <th>
                    Class
                </th>
               
                <th class="">
                    No of Works
                </th>
                      
            </tr>
            </thead>
            <tbody>
            @forelse($contractorLists as $contractor)
                <tr>
                    <td>{{$start++}}</td>
                    <td>{{$contractor->CDBNo}}</td>
                    <td>{{$contractor->Dzongkhag}}</td>
                    <td>{{$contractor->classification}}</td>
                    <td>{{$contractor->Works}}</td>
                   
       
                </tr>
            @empty
                <tr>
                    <td colspan="14" class="text-center font-red">No data to display!</td>
                </tr>
            @endforelse
            </tbody>
		</table>
            <?php pagination($noOfPages,Input::all(),Input::get('page'),"contractorrpt.workinhand"); ?>
	</div>
</div>
@stop