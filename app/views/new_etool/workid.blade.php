@extends('horizontalmenumaster')
@section('pagescripts')
    {{ HTML::script('assets/global/scripts/etool.js') }}
@stop
@section('content')
<div id="deletetender" class="modal fade" role="dialog" aria-labelledby="deletetender" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-warning">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title bold font-green-seagreen">Tender</h4>
			</div>
			<div class="modal-body">
				<h4 class="bold">Are you sure you want to delete this tender?</h4>
				<form action="#" class="" role="form">
					<div class="form-group">
						<label>Remarks</label>
						<textarea class="form-control" rows="3"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button class="btn green" data-dismiss="modal">Delete</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</div>
	</div>
</div>
<div class="portlet bordered light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>Works
		</div>
	</div>
	<div class="portlet-body flip-scroll">
        {{Form::open(array('url'=>'newEtl/workidetool','method'=>'get'))}}
        <div class="form-body">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Work Id</label>
                        <input type="text" name="WorkId" value="{{Input::get('WorkId')}}" class="form-control input-sm" />
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
					<table class="table table-bordered table-striped table-condensed flip-content">
						<thead class="flip-content">
							<tr>
								<th>
									Tender Id
								</th>
								<th>
									Work Id
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
									 Contract Period
								</th>
								<th class="">
									 Method
								</th>
								<th class="">
									 Tender Status
								</th>
								<th>
									Criteria
								</th>
							</tr>
						</thead>
						<tbody>
			            @forelse($uploadedTenders[$distinctYear->Year] as $uploadedTender)
							<tr>
			                    <td>{{$uploadedTender->EGPTenderId}}</td>
			                    <td>{{$uploadedTender->EtlTenderWorkId}}</td>
			                    <td>{{$uploadedTender->TenderOpeningDateAndTime}}</td>
			                    <td>{{$uploadedTender->Category}}</td>
			                    <td>{{$uploadedTender->Classification}}</td>
			                    <td>{{strip_tags($uploadedTender->NameOfWork)}}</td>
			                    <td>{{$uploadedTender->ContractPeriod}}</td>
			                    <td>
									@if($uploadedTender->Method=='OPEN_TENDERING')
										Open Tendering Method
									@elseif($uploadedTender->Method=='LIMITED_TENDERING')
										Limited Tendering Method
									@elseif($uploadedTender->Method=='DIRECT_CONTRACTING')
										Direct Contracting Method
									@elseif($uploadedTender->Method=='LIMITED_ENQUIRY')
										Limited Enquiry Method
									@endif
								</td>
								<td>
								@if($uploadedTender->TenderStatus=="Re-tendere")
									Re-Tender
								@else
									Open
								@endif
								</td>
								<td>
									<a href="{{URL::to("newEtl/setcriteriaetool/$uploadedTender->Id")}}" class="btn default btn-xs bg-green-haze"><i class="fa fa-edit"></i> Set</a>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="7" class="font-red text-center">No data to display</td>
							</tr>
			            @endforelse
						</tbody>
					</table>
				</div>
        </div>
        <?php $count++; ?>
        @endforeach
        </div>
	</div>
</div>
@stop