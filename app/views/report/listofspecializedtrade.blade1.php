@extends('reportsmaster')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
			<i class="fa fa-cogs"></i>List of Specialized Trade &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route('specializedtraderpt.listofspecializedtrade',$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route('specializedtraderpt.listofspecializedtrade',$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		</div>
	</div>
	<div class="portlet-body flip-scroll">
        {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
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
                        <label class="control-label">Dzongkhag</label>
                        <select class="form-control select2me" name="Dzongkhag">
                            <option value="">---SELECT ONE---</option>
                            @foreach($dzongkhags as $dzongkhag)
                                <option value="{{$dzongkhag->Dzongkhag}}" @if($dzongkhag->Dzongkhag == Input::get('Dzongkhag'))selected @endif>{{$dzongkhag->Dzongkhag}}</option>
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
                                <option value="{{$status->Status}}" @if($status->Status == Input::get('Status'))selected="selected"@endif>{{$status->Status}}</option>
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
						Name
					</th>
					<th class="">
						 CID No.
					</th>
					<th class="">
						 Gewog
					</th>
					<th>
						Village
					</th>
					<th>
						Telephone No.
					</th>
					<th class="">
						 Mobile No.
					</th>
					<th class="">
						Dzongkhag
					</th>
                    <th>
                        Expiry Date
                    </th>
                    <th class="">
                        Status
                    </th>
				</tr>
			</thead>
			<tbody>
            @foreach($specializedTradeList as $value)
				<tr>
                    <td>{{$start++}}</td>
                    <td>{{$value->SPNo}}</td>
                    <td>{{$value->Name}}</td>
                    <td>{{$value->CIDNo}}</td>
                    <td>{{$value->Gewog}}</td>
                    <td>{{$value->Village}}</td>
                    <td>{{$value->TelephoneNo}}</td>
                    <td>{{$value->MobileNo}}</td>
                    <td>{{$value->Dzongkhag}}</td>
                    <td>{{$value->RegistrationExpiryDate}}</td>
                    <td>{{$value->Status}}</td>
				</tr>
            @endforeach
			</tbody>
		</table>
        <?php pagination($noOfPages,Input::all(),Input::get('page'),"specializedtraderpt.listofspecializedtrade"); ?>
	</div>
</div>
@stop