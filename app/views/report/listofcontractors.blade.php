@extends('reportsmaster')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
            @if(!Input::has('export'))
            <i class="fa fa-cogs"></i>List of Contractors &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route("contractorrpt.listofcontractors",$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel </a>   <?php $parameters['export'] = 'print'; ?><a href="{{route("contractorrpt.listofcontractors",$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		    @endif
        </div>
	</div>
	<div class="portlet-body flip-scroll">
        @if(Input::get('export') != "print")
        {{Form::open(array('url'=>'contractorrpt/listofcontractors','method'=>'get'))}}
		<div class="form-body">
			<div class="row">
                <div class="col-md-1">
                    <div class="form-group">
                        <label class="control-label">CDB No.</label>
                        <input type="text" class="form-control" name="CdbRegistrationNo" placeholder="CDB No."  value="{{Input::get('CdbRegistrationNo')}}" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Contractor/Firm:</label>
                        <input type="text" class="form-control" name="ContractorId" placeholder="Name of Firm"  value="{{Input::get('ContractorId')}}" />
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="control-label">Dzongkhag:</label>
                    <select class="form-control select2me" name="CmnDzongkhagId">
                        <option value="">---SELECT ONE---</option>
                        @foreach($dzongkhags as $dzongkhag)
                            <option value="{{$dzongkhag->Id}}" @if($dzongkhag->NameEn == Input::get('CmnDzongkhagId'))selected @endif>{{$dzongkhag->NameEn}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="control-label">Category:</label>
                    <select class="form-control select2me" name="CmnContractorCategoryId">
                        <option value="">---SELECT ONE---</option>
                        @foreach($contractorCategories as $contractorCategory)
                            <option value="{{$contractorCategory->Id}}" @if($contractorCategory->Id == Input::get('CmnContractorCategoryId'))selected @endif>{{$contractorCategory->Name.' ('.$contractorCategory->Code.')'}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Classification:</label>
                        <select class="form-control select2me" name="CmnContractorClassificationId">
                            <option value="">---SELECT ONE---</option>
                            @foreach($contractorClassifications as $contractorClassification)
                                <option value="{{$contractorClassification->Id}}" @if($contractorClassification->Code == Input::get('CmnContractorClassificationId'))selected @endif>{{$contractorClassification->Name.' ('.$contractorClassification->Code.')'}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Type:</label>
                        {{Form::select('Type',array('1'=>'All','2'=>'Bhutanese','3'=>'Non-Bhutanese'),Input::get('Type'),array('class'=>'form-control'))}}
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="control-label">From</label>
                    <div class="input-icon right">
                        <input type="text" name="FromDate" class="form-control datepicker" value="{{Input::get('FromDate')}}" readonly="readonly" placeholder="SELECT DATE">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="control-label">To</label>
                    <div class="input-icon right">
                        <input type="text" name="ToDate" class="form-control datepicker" value="{{Input::get('ToDate')}}" readonly="readonly" placeholder="SELECT DATE">
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
            @foreach(Input::all() as $key=>$value)
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
<th>Owner Name</th>
<th>OwnershipType</th>
                <th>
                    Firm/Name
                </th>
                <th class="">
                    Address
                </th>
                <th class="">
                    Country
                </th>
                <th>
                    Dzongkhag
                </th>
 <th>
                    Email
                </th>
                <th>
                    Tel.No.
                </th>
                <th class="">
                    Mobile No.
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
                <th>
                    Expiry Date
                </th>
          
           
            </tr>
            </thead>
            <tbody>
            <?php $count = 1; ?>
            @forelse($contractorsList as $contractor)
                <tr>
                    <td>{{$count}}</td>
                    <td>{{$contractor->CDBNo}}</td>
                  <td>{{$contractor->NAME}}</td>
<td>{{$contractor->OwnershipType}}</td>

                    <td>{{$contractor->NameOfFirm}}</td>
                    <td>{{$contractor->Address}}</td>
                    <td>{{$contractor->Country}}</td>
                    <td>{{$contractor->Dzongkhag}}</td>
<td>{{$contractor->Email}}</td>
                    <td>{{$contractor->TelephoneNo}}</td>
                    <td>{{$contractor->MobileNo}}</td>
                    <td>{{$contractor->Classification1}}</td>
                    <td>{{$contractor->Classification2}}</td>
                    <td>{{$contractor->Classification3}}</td>
                    <td>{{$contractor->Classification4}}</td>
                    <td>{{convertDateToClientFormat($contractor->ExpiryDate)}}</td>
               
                </tr>
                <?php $count++; ?>
            @empty
                <tr>
                    <td colspan="15" class="text-center font-red">No data to display!</td>
                </tr>
            @endforelse
            </tbody>
		</table>
            <?php pagination($noOfPages,Input::all(),Input::get('page'),"contractorrpt.listofcontractors"); ?>
	</div>
</div>
@stop