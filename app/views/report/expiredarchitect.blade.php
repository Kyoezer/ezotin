@extends('reportsmaster')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
			<i class="fa fa-cogs"></i>List of Expired Architects &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route('architectrpt.expiredarchitect',$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route('architectrpt.expiredarchitect',$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		</div>
	</div>
	<div class="portlet-body flip-scroll">
        @if(Input::get('export') != "print")
        {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
		<div class="form-body">
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">AR No</label>
						<input type="text" name="ARNo" value="{{Input::get('ARNo')}}" class="form-control" />
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
				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label">Sector Type</label>
						<select class="form-control" name="SectorType">
							<option value="">---SELECT ONE---</option>
                            @foreach($sectors as $sector)
                                <option value="{{$sector->Name}}" @if($sector->Name == Input::get('SectorType'))selected @endif>{{$sector->Name}}</option>
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
						 AR No.
					</th>
					<th>
						Name
					</th>
					<th class="">
						 CID No.
					</th>
					<th class="">
						 Sector
					</th>
					<th>
						Country
					</th>
					<th class="">
						 Dzongkhag
					</th>
					<th class="">
						Gewog
					</th>
                    <th class="">
                        Village
                    </th>
                    <th class="">
                        Email
                    </th>
                    <th class="">
                        Mobile No.
                    </th>
                    <th>
                        Expiry Date
                    </th>
                    <th>Status</th>
				</tr>
			</thead>
			<tbody>
            @forelse($architectsList as $architect)
				<tr>
                    <td>{{$start++}}</td>
                    <td>{{$architect->ARNo}}</td>
                    <td>{{$architect->ArchitectName}}</td>
                    <td>{{$architect->CIDNo}}</td>
                    <td>{{$architect->Sector}}</td>
                    <td>{{$architect->Country}}</td>
                    <td>{{$architect->Dzongkhag}}</td>
                    <td>{{$architect->Gewog}}</td>
                    <td>{{$architect->Village}}</td>
                    <td>{{$architect->Email}}</td>
                    <td>{{$architect->MobileNo}}</td>
                    <td>{{$architect->ExpiryDate}}</td>
                  							<td >
					
							@if($architect->ExpiryDate<=date('Y-m-d G:i:s'))
							<p class="font-red bold warning">Expired</p>
							@else
							{{convertDateToClientFormat($architect->ExpiryDate)}}
							@endif
					
					</td>
				</tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center font-red">No data to display!</td>
                </tr>
            @endforelse
			</tbody>
		</table>
        <?php pagination($noOfPages,Input::all(),Input::get('page'),"architectrpt.expiredarchitect"); ?>
	</div>
</div>
@stop