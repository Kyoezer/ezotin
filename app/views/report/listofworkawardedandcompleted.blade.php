@extends('reportsmaster')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
            @if(!Input::has('export'))
            <i class="fa fa-cogs"></i>List of work awarded and completed &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route("contractorrpt.listofworkawardedandcompleted",$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel </a>   <?php $parameters['export'] = 'print'; ?><a href="{{route("contractorrpt.listofworkawardedandcompleted",$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		    @endif
        </div>
	</div>
	<div class="portlet-body flip-scroll">
        @if(Input::get('export') != "print")
        {{Form::open(array('url'=>'contractorrpt/listofworkawardedandcompleted','method'=>'get'))}}
		<div class="form-body">
        <div class="row">

        <div class="col-md-2">
                    <label class="control-label">From</label>
                    <div class="input-icon right">
                        <i class="fa fa-calendar"></i>
                        <input type="text" name="FromDate" class="form-control datepicker" value="{{Input::get('FromDate')}}" readonly="readonly" placeholder="">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="control-label">To</label>
                    <div class="input-icon right">
                        <i class="fa fa-calendar"></i>
                        <input type="text" name="ToDate" class="form-control datepicker" value="{{Input::has('ToDate')?Input::get('ToDate'):date('d-m-Y')}}" readonly="readonly" placeholder="">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Work Status</label>
                        
                
                            <input type="text" name="WorkStatus" value="{{Input::get('WorkStatus')}}" class="form-control" />
                    
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
                Work Start Date
                </th>
                <th class="order">
                Classification
                </th>
                <th>
                Project Category
                </th>
                <th>
                Ontime Completion Score
                </th>
                <th class="">
                Quality of Execution Score
                </th>
                <th class="">
                Work Completion Date
                </th>
                <th class="">
                WorkId
                </th>   
                <th class="">
                Name of Work
                </th>   
                <th class="">
                Actual End Date
                </th>   
                <th class="">
                Awarded Amount
                </th>  
                <th class="">
                Bid Amount
                </th>    
                <th class="">
                Evaluated Amount
                </th>    
                <th class="">
                 Procuring Agency

                </th>    
                <th class="">
                Work Status
                </th>    
                <th class="">
                LD Imposed
                </th>    
                <th class="">
                LD No. of Days
                </th>    
                <th class="">
                LD Amount
                </th>    
                <th class="">
                Hindrance
                </th>    
                <th class="">
                Hindrance No of Days
                </th> 
                <th class="">
                Final Amount
                </th> 
                <th class="">
                CostOv
                </th> 
                <th class="">
                Completion Date Final
                </th> 
                <th class="">
                Name_exp_23
                </th> 
            </tr>
            </thead>
            <tbody>
            @forelse($contractorLists as $contractor)
                <tr>
                    <td>{{$start++}}</td>
                    <td>{{$contractor->WorkStartDate}}</td>
                    <td>{{$contractor->classification}}</td>
                    <td>{{$contractor->ProjectCategory}}</td>
                    <td>{{$contractor->OntimeCompletionScore}}</td>
                    <td>{{$contractor->QualityOfExecutionScore}}</td>
                    <td>{{$contractor->WorkCompletionDate}}</td>
                    <td>{{$contractor->WorkId}}</td>
                    <td>{{$contractor->NameOfWork}}</td>
                    <td>{{$contractor->ActualEndDate}}</td>
                    <td>{{$contractor->AwardedAmount}}</td>
                    <td>{{$contractor->BidAmount}}</td>
                    <td>{{$contractor->EvaluatedAmount}}</td>
                    <td>{{$contractor->ProcuringAgency}}</td>
                    <td>{{$contractor->WorkStatus}}</td>
                    <td>{{$contractor->LDImposed}}</td>
                    <td>{{$contractor->LDNoOfDays}}</td>
                    <td>{{$contractor->LDAmount}}</td>
                    <td>{{$contractor->Hindrance}}</td>
                    <td>{{$contractor->HindranceNoOfDays}}</td>
                    <td>{{$contractor->FinalAmount}}</td>
                    <td>{{$contractor->CostOv}}</td>
                    <td>{{$contractor->CompletionDateFinal}}</td>
                    <td>{{$contractor->Name_exp_23}}</td>
       
                </tr>
            @empty
                <tr>
                    <td colspan="14" class="text-center font-red">No data to display!</td>
                </tr>
            @endforelse
            </tbody>
		</table>
            <?php pagination($noOfPages,Input::all(),Input::get('page'),"contractorrpt.listofworkawardedandcompleted"); ?>
	</div>
</div>
@stop