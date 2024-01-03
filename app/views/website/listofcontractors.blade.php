@extends('websitemaster')
@section('main-content')
<div class="row">
    <div class="col-md-12">
{{ Form::open(array('url' =>Request::URL(),'role'=>'form','method'=>'get')) }}
	<h4 class="text-primary"><strong><i class="fa fa-list"></i> List of Contractors</strong></h4>
	<div class="alert alert-info">
		<p>Search contractors by selecting relevant parameters. If you select more than one parameter, the result will be combination of selected parameters.</p>
	</div>
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
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label">Proprietor's Name/CID:</label>
                <input type="text" class="form-control" name="Proprietor" placeholder="Name/CID of Proprietor"  value="{{Input::get('Proprietor')}}" />
            </div>
        </div>
        <div class="col-md-2">
            <label class="control-label">Dzongkhag:</label>
            <select class="form-control select2me" name="CmnDzongkhagId">
				<option value="">---SELECT ONE---</option>
				@foreach($dzongkhagId as $dzongkhag)
					<option value="{{$dzongkhag->Id}}" @if($dzongkhag->Id == Input::get('CmnDzongkhagId'))selected @endif>{{$dzongkhag->NameEn}}</option>
				@endforeach
			</select>
        </div>
        <div class="col-md-2">
            <label class="control-label">Category:</label>
            <select class="form-control select2me" name="CmnContractorCategoryId">
				<option value="">---SELECT ONE---</option>
				@foreach($contractorCategoryId as $contractorCategory)
					<option value="{{$contractorCategory->Id}}" @if($contractorCategory->Id == Input::get('CmnContractorCategoryId'))selected @endif>{{$contractorCategory->Name.' ('.$contractorCategory->Code.')'}}</option>
				@endforeach
			</select>
        </div><div class="clearfix"></div>
        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label">Classification:</label>
                <select class="form-control select2me" name="CmnContractorClassificationId">
					<option value="">---SELECT ONE---</option>
					@foreach($contractorClassificationId as $contractorClassification)
						<option value="{{$contractorClassification->Id}}" @if($contractorClassification->Id == Input::get('CmnContractorClassificationId'))selected @endif>{{$contractorClassification->Name.' ('.$contractorClassification->Code.')'}}</option>
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
            <label class="control-label">@if($type == 1)Registration @else Expiry @endif Date From</label>
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
        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label">No. of Rows</label>
                {{Form::select('Limit',array(10=>10,20=>20,30=>30,50=>50,'All'=>'All'),Input::has('Limit')?Input::get('Limit'):20,array('class'=>'form-control'))}}
            </div>
        </div>
	</div>
	<div class="row">
		<div class="col-md-2">
			<div class="btn-set">
				<button type="submit" class="btn btn-primary">Search</button>
				<a href="{{Request::url()}}" class="btn btn-danger">Clear</a>
			</div>
		</div>
	</div>
{{Form::close()}}<br />
<div class="row">
	<div class="col-md-12 col-xs-12 col-sm-12 table-responsive">
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
                    Registration Date
                </th>
                <th style="width:90px;">
                    Expiry Date
                </th>
            </tr>
            </thead>
            <tbody>
            <?php $count = 1; ?>
            @forelse($contractorLists as $contractor)
                <tr>
                    <td>{{$count}}</td>
                    <td>{{$contractor->CDBNo}}</td>
                    <td>{{$contractor->NameOfFirm}}</td>
                    <td>{{$contractor->Address}}</td>
                    <td>{{$contractor->Country}}</td>
                    <td>{{$contractor->Dzongkhag}}</td>
                    <td>{{$contractor->Classification1}}</td>
                    <td>{{$contractor->Classification2}}</td>
                    <td>{{$contractor->Classification3}}</td>
                    <td>{{$contractor->Classification4}}</td>
                    <td>{{convertDateToClientFormat($contractor->InitialDate)}}</td>
                    <td>{{convertDateToClientFormat($contractor->ExpiryDate)}}</td>
                </tr>
                <?php $count++; ?>
            @empty
                <tr>
                    <td colspan="14" class="text-center font-red">No data to display!</td>
                </tr>
            @endforelse
            </tbody>
        </table>
	</div>
</div>
</div>
@stop