@extends('reportsmaster')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel'; $route="contractorrpt.contractorscategorywise"; ?>
            @if(!Input::has('export'))
            <i class="fa fa-cogs"></i>Contractor Category Report&nbsp;&nbsp;@if(!Input::has('export')) <?php $parameters['export'] = 'print'; ?><a href="{{route($route,$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		    @endif
        </div>
	</div>
	<div class="portlet-body flip-scroll">
        @if(Input::get('export') != "print")
        {{Form::open(array('url'=>'contractorrpt/contractorscategorywise','method'=>'get'))}}
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
                        <input type="text" name="ToDate" class="form-control datepicker" value="{{Input::get('ToDate')}}" readonly="readonly" placeholder="">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Country</label>
                        <select class="form-control select2me" name="CountryId">
                            <option value="">---SELECT ONE---</option>
                            @foreach($countries as $country)
                                <option value="{{$country->Id}}" @if($country->Id == Input::get('CountryId'))selected @endif>{{$country->Name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
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
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Category</label>
						<select class="form-control" name="ContractorCategoryId">
							<option value="">---SELECT ONE---</option>
                            @foreach($contractorCategories as $contractorCategory)
                                <option value="{{$contractorCategory->Id}}" @if($contractorCategory->Id == Input::get('ContractorCategoryId'))selected @endif>{{$contractorCategory->Code}}</option>
                            @endforeach
						</select>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">Classification</label>
						<select class="form-control" name="ContractorClassificationId">
							<option value="">---SELECT ONE---</option>
                            @foreach($contractorClassifications as $contractorClassification)
                                <option value="{{$contractorClassification->Id}}" @if($contractorClassification->Id == Input::get('ContractorClassificationId'))selected="selected"@endif>{{$contractorClassification->Code}}</option>
                            @endforeach
						</select>
					</div>
				</div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Application Status</label>
                        <select class="form-control" name="StatusId">
                            <option value="">---SELECT ONE---</option>
                            @foreach($statuses as $status)
                                <option value="{{$status->Id}}" @if($status->Id == Input::get('StatusId'))selected="selected"@endif>{{$status->Name}}</option>
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
        @else
            @foreach($parametersForPrint as $key=>$value)
                <b>{{$key}}: {{$value}}</b><br>
            @endforeach
            <br/>
        @endif
        <div class="row">
            <div class="col-md-4">
                <table class="table table-bordered table-striped table-condensed flip-content dont-flip" id="contractorhumanresource">
                    <thead class="flip-content">
                        <tr>
                            <th>
                                No. of Contractors
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                    @forelse($reportData as $value)
                        <tr>
                            <td>{{$value->Count}}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="font-red text-center">Please select a parameter!</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
	</div>
</div>
@stop