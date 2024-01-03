@extends('reportsmaster')
@section('pagescripts')
    {{HTML::script('assets/global/scripts/report.js?ver='.randomString())}}
@stop
@section('content')
<div id="tenderdownloaddetailsmodal" class="modal fade in" role="dialog" aria-labelledby="modalmessagebox" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h3 id="modalmessageheader" class="modal-title font-red-intense">Tender Details</h3>
            </div>
            <div class="modal-body">
                <p id="details-wrapper">

                </p>
            </div>
        </div>
    </div>
</div>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
			<i class="fa fa-cogs"></i>Tenders downloaded from website&nbsp;&nbsp;@if(!Input::has('export'))    <?php $parameters['export'] = 'print'; ?><a href="{{route('contractorrpt.categorywisereport',$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		</div>
	</div>
	<div class="portlet-body flip-scroll">
        @if(Input::get('export') != 'print')
        {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
		<div class="form-body">
			<div class="row">
                <div class="col-md-2">
                    <label class="control-label">Uploaded Date From</label>
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
                <div class="col-md-2">
                    <label class="control-label">Work Id</label>
                    <input type="text" class="form-control" name="WorkId" value="{{Input::get('WorkId')}}"/>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Dzongkhag</label>
                        <select class="form-control select2me" name="Dzongkhag">
                            <option value="">---SELECT ONE---</option>
                            @foreach($dzongkhags as $dzongkhag)
                                <option value="{{$dzongkhag->Id}}" @if($dzongkhag->Id == Input::get('Dzongkhag'))selected @endif>{{$dzongkhag->Dzongkhag}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Procuring Agency</label>
                        <select class="form-control select2me" name="Agency">
                            <option value="">---SELECT ONE---</option>
                            @foreach($agencies as $agency)
                                <option value="{{$agency->Id}}" @if($agency->Id == Input::get('ProcuringAgency'))selected @endif>{{$agency->ProcuringAgency}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Classification</label>
                        <select class="form-control select2me" name="Classification">
                            <option value="">---SELECT ONE---</option>
                            @foreach($classifications as $classification)
                                <option value="{{$classification->Id}}" @if($classification->Id == Input::get('Classification'))selected @endif>{{$classification->Classification}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Category</label>
                        <select class="form-control select2me" name="Category">
                            <option value="">---SELECT ONE---</option>
                            @foreach($categories as $category)
                                <option value="{{$category->Id}}" @if($category->Id == Input::get('Category'))selected @endif>{{$category->Category}}</option>
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
		    <table class="table table-bordered table-striped table-condensed flip-content" >
			<thead class="flip-content">
                <tr>
                    <th>Sl.No.</th>
                    <th>Work Id</th>
                    <th>Agency</th>
                    <th>Name of Work</th>
                    <th>Dzongkhag</th>
                    <th>Category</th>
                    <th>Class</th>
                    <th>Contract Period</th>
                    <th>Project Estimate</th>
                    <th>Tentative Start Date</th>
                    <th>Tentative End Date</th>
                    <th>Uploaded on</th>
                    <th>Download Count</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData as $value)
                    <tr>
                        <td>{{$start++}}</td>
                        <td>{{$value->WorkId}}</td>
                        <td>{{$value->Agency}}</td>
                        <td>{{strip_tags($value->NameOfWork)}}</td>
                        <td>{{$value->Dzongkhag}}</td>
                        <td>{{$value->Category}}</td>
                        <td>{{$value->Class}}</td>
                        <td>{{$value->ContractPeriod}}</td>
                        <td>{{$value->ProjectEstimateCost}}</td>
                        <td>{{convertDateToClientFormat($value->TentativeStartDate)}}</td>
                        <td>{{convertDateToClientFormat($value->TentativeEndDate)}}</td>
                        <td>{{convertDateTimeToClientFormat($value->UploadedDate)}}</td>
                        <td class="text-center" style="word-wrap: break-word!important;"><a href="#" class="show-tender-details" data-id="{{$value->TenderId}}">{{$value->Total}}</a></td>
                    </tr>
                @empty
                    <tr><td colspan="12" class="text-center font-red">No data to display</td></tr>
                @endforelse
			</tbody>
		</table>
        <?php pagination($noOfPages,Input::all(),Input::get('page'),"etoolrpt.tendersdownloadedfromweb"); ?>
	</div>
</div>
@stop