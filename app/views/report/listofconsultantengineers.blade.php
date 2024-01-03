@extends('reportsmaster')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
			<i class="fa fa-cogs"></i>List of Engineers &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route('engineerrpt.listofconsultantengineer',$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route('engineerrpt.listofconsultantengineer',$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		</div>
	</div>
	<div class="portlet-body flip-scroll">
        @if(Input::get('export') != 'print')
        {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
		<div class="form-body">
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">CDB No</label>
						<input type="text" name="CDBNo" value="{{Input::get('CDBNo')}}" class="form-control" />
					</div>
				</div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Consultant</label>
                        <select class="form-control select2me" name="consultant">
                            <option value="">---SELECT ONE---</option>
                            @foreach($consultants as $consultants)
                                <option value="{{$consultants->NameOfFirm}}" @if($consultants->NameOfFirm == Input::get('NameOfFirm'))selected @endif>{{$consultants->NameOfFirm}}</option>
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
						Concultancy Firm
					</th>
					<th>
						 CDB No.
					</th>
					<th>
						Name
					</th>
					<th class="">
						 CID No.
					</th>
					<th class="">
						 Gender
					</th>
					<th class="">
						 Dzongkhag
					</th>
                    
				</tr>
			</thead>
			<tbody>
            @forelse($engineersList as $engineer)
				<tr>
                    <td>{{$start++}}</td>
                    <td>{{$engineer->NameOfFirm}}</td>
                    <td>{{$engineer->CDBNo}}</td>
                    <td>{{$engineer->Name}}</td>
                    <td>{{$engineer->CIDNo}}</td>
                    <td>{{$engineer->gender}}</td>
                    <td>{{$engineer->NameEn}}</td>
				</tr>
                @empty
                <tr>
                    <td colspan="14" class="font-red text-center">No data to display!</td>
                </tr>
                @endforelse
			</tbody>
		</table>
        <?php pagination($noOfPages,Input::all(),Input::get('page'),"engineerrpt.listofconsultantengineer"); ?>
	</div>
</div>
@stop