@extends('websitemaster')
@section('main-content')

<div class="col-md-12">
{{ Form::open(array('url' =>Request::URL(),'role'=>'form','method'=>'get')) }}
	<h4 class="text-primary"><strong><i class="fa fa-list"></i> List of Specizlized Firm</strong></h4>
	<div class="alert alert-info">
		<p>Search specialized firm by selecting relevant parameters. If you select more than one parameter, the result will be combination of selected parameters.</p>
	</div>
   
		<div class="form-body">
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">SP No</label>
						<input type="text" name="SPNo" value="{{Input::get('SPNo')}}" class="form-control" />
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
                        <label class="control-label">Country</label>
                        <select class="form-control select2me" name="CountryId">
                            <option value="">---SELECT ONE---</option>
                            @foreach($countries as $country)
                                <option value="{{$country->Id}}" @if(Input::has('CountryId'))@if($country->Id == Input::get('CountryId'))selected @endif @else @if($country->Id == CONST_COUNTRY_BHUTAN)selected="selected"@endif @endif>{{$country->Name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Dzongkhag</label>
                        <select class="form-control select2me" name="DzongkhagId">
                            <option value="">---SELECT ONE---</option>
                            @foreach($dzongkhags as $dzongkhag)
                                <option value="{{$dzongkhag->Id}}" @if($dzongkhag->Id == Input::get('DzongkhagId'))selected @endif>{{$dzongkhag->NameEn}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">Category</label>
						<select class="form-control" name="Classification">
							<option value="">---SELECT ONE---</option>
                            @foreach($specializedfirmClassifications as $specializedfirmClassification)
                                <option value="{{$specializedfirmClassification->Code}}" @if($specializedfirmClassification->Code == Input::get('Classification'))selected="selected"@endif>{{$specializedfirmClassification->Name.' ('.$specializedfirmClassification->Code.')'}}</option>
                            @endforeach
						</select>
					</div>
				</div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Application Status</label>
                        <select class="form-control" name="Status">
                            <option value="">---SELECT ONE---</option>
                            @foreach($statuses as $status)
                                <option value="{{$status->Id}}" @if(Input::has('StatusId')) @if($status->Id == Input::get('StatusId'))selected="selected"@endif @else @if($status->Id == CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)selected="selected"@endif @endif>{{$status->Name}}</option>
                            @endforeach
                        </select>
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
 
		<table class="table table-bordered table-striped table-condensed flip-content" id="contractorhumanresource">
			<thead class="flip-content">
				<tr>
                    <th>
                        Sl.No.
                    </th>
					<th class="order">
						 SP No.
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
					<th class="">
						Category
					</th>
               
                    <th>
                        Expiry Date
                    </th>
                    <th>Status</th>
				</tr>
			</thead>
			<tbody>
            @forelse($specializedfirmList as $specializedfirm)
				<tr>
                    <td>{{$start++}}</td>
                    <td>{{$specializedfirm->SPNo}}</td>
                    <td>{{$specializedfirm->NameOfFirm}}</td>
                    <td>{{$specializedfirm->Address}}</td>
                    <td>{{$specializedfirm->Country}}</td>
                    <td>{{$specializedfirm->Dzongkhag}}</td>
                    <td>{{$specializedfirm->Category}}</td>                 
                    <td>{{$specializedfirm->ExpiryDate}}</td>
                    <td>{{$specializedfirm->Status}}</td>
				</tr>
            @empty
                <tr>
                    <td colspan="13" class="font-red text-center">No data to display!</td>
                </tr>
            @endforelse
			</tbody>
		</table>
     
	</div>
</div>
@stop