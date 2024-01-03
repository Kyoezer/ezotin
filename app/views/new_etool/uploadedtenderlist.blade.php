@extends('horizontalmenumaster')
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
        {{Form::open(array('url'=>$routePrefix.'/uploadedtenderlistetool','method'=>'get'))}}
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
						<label class="control-label">Category</label>
						<select class="form-control select2me" name="ContractorCategoryId">
							<option value="">---SELECT ONE---</option>
                            @foreach($contractorCategories as $contractorCategory)
                                <option value="{{$contractorCategory->Id}}" @if($contractorCategory->Id == Input::get('ContractorCategoryId'))selected @endif>{{$contractorCategory->Name.' ('.$contractorCategory->Code.')'}}</option>
                            @endforeach
						</select>
					</div>
				</div>
				<div class="col-md-4">
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
					<label class="control-label">&nbsp;</label>
					<div class="btn-set">
						<button type="submit" class="btn blue-hoki btn-sm">Search</button>
                        <a href="{{Request::url()}}" class="btn grey-cascade btn-sm">Clear</a>
					</div>
				</div>
			</div>
		</div>
        {{Form::close()}}
        <div class="panel-group accordion" id="tender-accordion">
        <?php $count = 1; ?>
        @foreach($distinctYears as $distinctYear)
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#tender-accordion" href="#collapse_{{$distinctYear->Year}}">
                        {{$distinctYear->Year}}
                    </a>
                </h4>
            </div>
            <div id="collapse_{{$distinctYear->Year}}" class="panel-collapse @if($count==1){{"in"}}@else{{"collapse"}}@endif">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-condensed flip-content" id="contractorhumanresource">
                        <thead class="flip-content">
                            <tr>
                                <th class="order">
                                     Sl. No.
                                </th>
                                <th>
                                    Work Id
                                </th>
                                <th class="">
                                     Last Dt and Time of Submission
                                </th>
                                <th class="">
                                     Opening Dt. and Time
                                </th>
                                <th>
                                    Method
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
                                <th>
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $count = 1; ?>
                        @forelse($uploadedTenders[$distinctYear->Year] as $uploadedTender)
                            <tr>
                                <td>
                                    <input type="hidden" name="Id" value="{{$uploadedTender->Id}}"/>
                                     {{$count}}
                                </td>
                                <td>
                                     {{$uploadedTender->EtlTenderWorkId}}
                                </td>
                                <td class="">
                                     {{convertDateTimeToClientFormat($uploadedTender->LastDateAndTimeOfSubmission)}}
                                </td>
                                <td class="">
                                    {{convertDateTimeToClientFormat($uploadedTender->TenderOpeningDateAndTime)}}
                                </td>
                                <td>
                                @if($uploadedTender->Method=='OPEN_TENDER')
                                    Open Tender
                                @elseif($uploadedTender->Method=='LIMITED_ENQUIRY')
                                    Limited Enquiry
                                @elseif($uploadedTender->Method=='SINGLE_SOURCE')
                                    Single Source
                                @endif
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
                                    @if($uploadedTender->CmnWorkExecutionStatusId != CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED && $uploadedTender->CmnWorkExecutionStatusId != CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                                            @if(Request::segment(1) == "cinet")
                                                <?php $routeSuffix = "cinet"; ?>
                                            @else
                                                <?php $routeSuffix = "etool"; ?>
                                            @endif
                                            {{--@if(date('Y-m-d G:i:s') < $uploadedTender->DateOfClosingSaleOfTender)--}}
                                                    <a href="{{URL::to("$routePrefix/uploadtender$routeSuffix/$uploadedTender->Id")}}" class="btn default btn-xs bg-green-haze editaction"><i class="fa fa-edit"></i> Edit</a>
                                            {{--@endif--}}
                                        <a href="#" class="deleterowdb btn default btn-xs bg-red-sunglo"><i class="fa fa-edit"></i> Delete</a>
                                    @endif
                                </td>
                            </tr>
                            <?php $count++; ?>
                            @empty
                            <tr><td colspan="9" class="font-red text-center">No data to display</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php $count++; ?>
        @endforeach
        </div>
	</div>
</div>
@stop