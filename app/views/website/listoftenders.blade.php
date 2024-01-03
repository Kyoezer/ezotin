@extends('websitemaster')
@section('main-content')
<div class="row">
	<div class="col-md-12">
		<span class="angrytext" >Please read the following carefully:
				To download tender click on view.
				If the tender is available for download
				a blue button download tender document
				will be seen. Click to download.
				Before the download starts you will need
				to provide your email or contact number.
				Once you have provided the above detail,
				the download will automatically start.
				If the tender is not available for
				download the blue button will not be
				visible. In such cases kindly get in
				touch with relevant procuring agency and
				contact person provided in the tender
				notice.</span> <br><br>
{{Form::open(array('url'=>'web/tenderlist','method'=>'get', 'class'=>'form-group'))}}
	<h4 class="text-primary"><strong><i class="fa fa-list"></i> List of Tenders</strong></h4>
	<div class="alert alert-info">
		<p>Search tenders by selecting any parameters below. If you select two parameters then the result will be combination of both the parameters.</p>
	</div>
	<div class="row">
        <div class="col-md-3">
            <label class="control-label">Classification:</label>
            <select class="form-control" name="CmnContractorClassificationId">
				<option value="">---SELECT ONE---</option>
				@foreach($contractorClassificationId as $contractorClassification)
					<option value="{{$contractorClassification->Id}}" @if($contractorClassification->Id == Input::get('CmnContractorClassificationId'))selected="selected"@endif>{{$contractorClassification->Name.' ('.$contractorClassification->Code.')'}}</option>
				@endforeach
			</select>
        </div>
        <div class="col-md-3">
            <label class="control-label">Category:</label>
            <select class="form-control" name="CmnContractorCategoryId">
				<option value="">---SELECT ONE---</option>
				@foreach($contractorCategoryId as $contractorCategory)
					<option value="{{$contractorCategory->Id}}" @if($contractorCategory->Id == Input::get('CmnContractorCategoryId'))selected="selected"@endif>{{$contractorCategory->Name.' ('.$contractorCategory->Code.')'}}</option>
				@endforeach
			</select>
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
		<div class="col-md-3">
			<label class="control-label">Agency:</label>
			<select class="form-control" name="CmnProcuringAgencyId">
				<option value="">---SELECT ONE---</option>
				@foreach($agencies as $agency)
					<option value="{{$agency->Id}}" @if($agency->Id == Input::get('CmnProcuringAgencyId'))selected="selected"@endif>{{$agency->Name}}</option>
				@endforeach
			</select>
		</div>
        <div class="col-md-4">
        	<label>&nbsp;</label>
			<div class="btn-set">
				<button type="submit" class="btn btn-primary">Search</button>
				<a href="{{Request::url()}}" class="btn btn-danger">Clear</a>
			</div>
		</div>
	</div>
{{Form::close()}}

<div class="row">
	<div class="col-md-12 col-xs-12 col-sm-12 table-responsive">
		<table class="table table-bordered table-condensed table-striped" id="tenderlist">
			<thead>
				<tr class="success">
					<th>Sl#</th>
					{{--<th>Work Id</th>--}}
					<th>Procuring Agency</th>
					<th>Name of Work</th>
					<th>Description of Work</th>
					<th>Dzongkhag</th>
					<th>Category</th>
					<th>Classification</th>
					<th>Contract Period</th>
					<th width="10%">Dt. of Closing Sale of Tender</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				@forelse ($listoftenders as $listoftender)
					<tr>
						<td>{{ $slno++ }}</td>
						{{--<td>{{ $listoftender -> WorkId }}</td>--}}
						<td>{{ $listoftender -> ProcuringAgencyName }}</td>
						<td>{{strip_tags($listoftender -> NameOfWork) }}</td>
						<td>{{ HTML::decode(strip_tags($listoftender -> DescriptionOfWork)) }}</td>
						<td>{{ $listoftender -> Dzongkhag }}</td>
						<td>{{ $listoftender -> WorkCategory }}</td>
						<td>{{ $listoftender -> WorkClassification }}</td>
						<td>{{ $listoftender -> ContractPeriod }}</td>
						<td>{{ convertDateToClientFormat($listoftender -> DateOfClosingSaleOfTender) }}</td>
						<td><a href="{{ URL::to('web/webtenderdetails/'.$listoftender -> Id) }}" class="btn btn-info btn-sm">View</a></td>
					</tr>
				@empty
					<tr>
						<td class="font-red text-center" colspan="10" style="color:#FE0000;">No data to display</td>
					</tr>
				@endforelse
			</tbody>
		</table>
	</div>
</div>
</div>
@stop