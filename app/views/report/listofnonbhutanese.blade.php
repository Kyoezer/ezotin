@extends('reportsmaster')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
			<i class="fa fa-cogs"></i>List of Non Bhutanese Contractors &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route('contractorrpt.listofnonbhutanesecontractors',$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route('contractorrpt.listofnonbhutanesecontractors',$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		</div>
	</div>
	<div class="portlet-body flip-scroll">
        @if(Input::get('export') != "print")
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
                        <label class="control-label">Country</label>
                        <select class="form-control select2me" name="Country">
                            <option value="">---SELECT ONE---</option>
                            @foreach($countries as $country)
                                <option value="{{$country->Name}}" @if($country->Name == Input::get('Country'))selected @endif>{{$country->Name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Dzongkhag</label>
                        <select class="form-control select2me" name="Dzongkhag">
                            <option value="">---SELECT ONE---</option>
                            @foreach($dzongkhags as $dzongkhag)
                                <option value="{{$dzongkhag->NameEn}}" @if($dzongkhag->NameEn == Input::get('Dzongkhag'))selected @endif>{{$dzongkhag->NameEn}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">Classification</label>
						<select class="form-control" name="Classification">
							<option value="">---SELECT ONE---</option>
                            @foreach($contractorClassifications as $contractorClassification)
                                <option value="{{$contractorClassification->Code}}" @if($contractorClassification->Code == Input::get('Classification'))selected="selected"@endif>{{$contractorClassification->Name.' ('.$contractorClassification->Code.')'}}</option>
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
                                <option value="{{$status->Name}}" @if($status->Name == Input::get('Status'))selected="selected"@endif>{{$status->Name}}</option>
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
            @foreach(Input::all() as $key=>$value)
                @if($key != 'export')
                    <b>{{$key}}: {{$value}}</b><br>
                @endif
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
						Firm/Name
					</th>
					<th class="">
						 Proprietor Name
					</th>
					<th class="">
						 Gender
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
                    <th>Status</th>

                    <th>Refresher Course <br> Modules Attended</th>

				</tr>
			</thead>
			<tbody>
            @forelse($contractorList as $contractor )
            <tr>
                    <td>{{$start++}}</td>
                    <td>{{$contractor->CDBNo}}</td>
                    <td>{{$contractor->NameOfFirm}}</td>
                    <td>{{$contractor->ownerName}}</td>
                    <td>{{$contractor->gender}}</td>
                    <td>{{$contractor->Address}}</td>
                    <td>{{$contractor->Country}}</td>
                    <td>{{$contractor->Dzongkhag}}</td>
    
                    <td>{{$contractor->TelephoneNo}}</td>
                    <td>{{$contractor->MobileNo}}</td>
                    <td>{{$contractor->Classification1}}</td>
                    <td>{{$contractor->Classification2}}</td>
                    <td>{{$contractor->Classification3}}</td>
                    <td>{{$contractor->Classification4}}</td>
                    <td>{{convertDateTimeToClientFormat($contractor->ExpiryDate)}}</td>
                    <td>{{$contractor->Status}}</td>

                    <td>@if(isset($contractor->RefresherCourseModules)){{$contractor->RefresherCourseModules}}@endif</td>

				</tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center font-red">No data to display!</td>
                </tr>
            @endforelse
			</tbody>
		</table>
        <?php pagination($noOfPages,Input::all(),Input::get('page'),"contractorrpt.listofnonbhutanesecontractors"); ?>
	</div>
</div>
@stop