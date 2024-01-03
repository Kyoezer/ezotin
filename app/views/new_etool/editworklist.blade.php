@extends('master')
@section('pagescripts')
    {{ HTML::script('assets/global/scripts/etool.js') }}
@stop
@section('content')
<input type="hidden" name="RoutePrefix" value="{{Request::segment(1)}}"/>
<div id="deleteModal" class="modal fade" role="dialog" aria-labelledby="deletetender" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-warning">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title bold font-green-seagreen">Tender</h4>
			</div>
			<div class="modal-body">
                <input type="hidden" name="Id" />
                <input type="hidden" name="TableName" value="etltender"/>
				<h4 class="bold">Are you sure you want to delete this tender?</h4>
				<form action="#" class="" role="form">
					<div class="form-group">
						<label>Remarks</label>
						<textarea class="form-control" rows="3"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button class="btn green" id="callToDelete">Delete</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</div>
	</div>
</div>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>Uploaded Tenders
		</div>
	</div>
	<div class="portlet-body flip-scroll">
        <?php $routePrefix = Request::segment(1); ?>
        {{Form::open(array('url'=>'etoolsysadm/editwork','method'=>'get'))}}
		<div class="form-body">
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">Work Id</label>
						<input type="text" name="WorkId" value="{{Input::get('WorkId')}}" class="form-control" />
					</div>
				</div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">Procuring Agency</label>
                        <select class="form-control select2me" name="CmnProcuringAgencyId">
                            <option value="">---SELECT ONE---</option>
                            @foreach($procuringAgencies as $procuringAgency)
                                <option value="{{$procuringAgency->Id}}" @if($procuringAgency->Id == Input::get('CmnProcuringAgencyId'))selected="selected"@endif>{{$procuringAgency->Name.' ('.$procuringAgency->Code.')'}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label">Category</label>
						<select class="form-control select2me" name="ContractorCategoryId">
							<option value="">---SELECT ONE---</option>
                            @foreach($contractorCategories as $contractorCategory)
                                <option value="{{$contractorCategory->Id}}" @if($contractorCategory->Id == Input::get('ContractorCategoryId'))selected @endif>{{$contractorCategory->Name.' ('.$contractorCategory->Code.')'}}</option>
                            @endforeach
						</select>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label">Classification</label>
						<select class="form-control select2me" name="ContractorClassificationId">
							<option value="">---SELECT ONE---</option>
                            @foreach($contractorClassifications as $contractorClassification)
                                <option value="{{$contractorClassification->Id}}" @if($contractorClassification->Id == Input::get('ContractorClassificationId'))selected="selected"@endif>{{$contractorClassification->Name.' ('.$contractorClassification->Code.')'}}</option>
                            @endforeach
						</select>
					</div>
				</div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="FromDate" class="control-label">From Date</label>
                        <div class="input-icon right">
                            <i class="fa fa-calendar"></i>
                            <input type="text" id="FromDate" name="FromDate" readonly class="datepicker form-control" value="{{Input::get('FromDate')}}"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <label for="ToDate" class="control-label">To Date</label>
                    <div class="input-icon right">
                        <i class="fa fa-calendar"></i>
                        <input type="text" id="ToDate" name="ToDate" readonly class="datepicker form-control" value="{{Input::get('ToDate')}}">
                    </div>
                </div>
				<div class="col-md-2">
					<label class="control-label">&nbsp;</label>
					<div class="btn-set">
						<button type="submit" class="btn blue-hoki btn-sm">Search</button>
                        <a href="{{Request::url()}}" class="btn grey-cascade btn-sm">Clear</a>
					</div>
				</div>
			</div>
		</div>
        {{Form::close()}}
        @if($uploadedTenders)
            <table class="table table-bordered table-striped table-condensed flip-content" id="contractorhumanresource">
                <thead class="flip-content">
                    <tr>
                        <th>Sl#</th>
                        <th class="order">
                             Ref#
                        </th>
                        <th>
                            Work Id
                        </th>
                        <th>Agency</th>
                        <th class="">
                             Last Dt and Time of Submission
                        </th>
                        <th class="">
                             Opening Dt. and Time
                        </th>
                        <th>
                            Category
                        </th>
                        <th>
                            Classification
                        </th>
                        <th class="">
                             Name of the Work
                        </th>
                        <th class="">
                             Contract Period (Months)
                        </th>
                        <th>
                            Estimated Project Cost
                        </th>
                        <th>Tender Uploaded On</th>
                        <th>
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                @forelse($uploadedTenders as $uploadedTender)
                    <tr>
                        <td>{{$start++}}</td>
                        <td>
                            <input type="hidden" name="Id" value="{{$uploadedTender->Id}}"/>
                             {{$uploadedTender->ReferenceNo}}
                        </td>
                        <td>
                             {{$uploadedTender->EtlTenderWorkId}}
                        </td>
                        <td>
                            {{$uploadedTender->Agency}}
                        </td>
                        <td class="">
                             {{convertDateTimeToClientFormat($uploadedTender->LastDateAndTimeOfSubmission)}}
                        </td>
                        <td class="">
                            {{convertDateTimeToClientFormat($uploadedTender->TenderOpeningDateAndTime)}}
                        </td>
                        <td>
                            {{$uploadedTender->Category}}
                        </td>
                        <td>
                            {{$uploadedTender->Classification}}
                        </td>
                        <td class="">
                            {{strip_tags($uploadedTender->NameOfWork)}}
                        </td>
                        <td>
                            {{$uploadedTender->ContractPeriod}}
                        </td>
                        <td>
                            {{$uploadedTender->ProjectEstimateCost}}
                        </td>
                        <td>
                            {{convertDateToClientFormat($uploadedTender->UploadedDate)}}
                        </td>
                        <td>
                            {{--@if($uploadedTender->CmnWorkExecutionStatusId != CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED && $uploadedTender->CmnWorkExecutionStatusId != CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)--}}
                                    @if($uploadedTender->TenderSource == 2)
                                        <?php $routeSuffix = "cinet"; $routePrefix="cinet";?>
                                    @else
                                        <?php $routeSuffix = "etool"; $routePrefix = 'etl';?>
                                    @endif
                                    <a href="{{URL::to("$routePrefix/uploadtender$routeSuffix/$uploadedTender->Id")}}" class="btn default btn-xs bg-green-haze editaction"><i class="fa fa-edit"></i> Edit</a>
                                    @if($uploadedTender->TenderSource == 1)
                                    <a href="{{URL::to("newEtl/workcompletionformetool/$uploadedTender->Id")}}" class="btn default btn-xs purple editaction"><i class="fa fa-edit"></i> Edit Completion</a>
                                    @endif
                                <a href="#" class="deleterowdb btn default btn-xs bg-red-sunglo"><i class="fa fa-edit deleteaction"></i> Delete</a>
                            {{--@endif--}}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="11" class="font-red text-center">No data to display</td></tr>
                    @endforelse
                </tbody>
            </table>
            <?php pagination($noOfPages,Input::all(),Input::get('page'),"etoolsysadm.editwork"); ?>
        @endif
	</div>
</div>
@stop