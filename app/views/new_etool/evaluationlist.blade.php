@extends('horizontalmenumaster')
@section('content')
<div class="portlet bordered light">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>Evaluation
		</div>
	</div>
	<div class="portlet-body flip-scroll">
        {{Form::open(array('url'=>'newEtl/evaluationetool','method'=>'get'))}}
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
        					<th>Sl No.</th>
        					<th>
        						eGP Tender Id
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
        					<th>
        						Method
        					</th>
        					<th>
        						Status
        					</th>
        					<th>
        						Action
        					</th>
        				</tr>
        			</thead>
        			<tbody>
                        <?php $count = 1; ?>
                        @forelse($uploadedTenders[$distinctYear->Year] as $tender)
                            <tr>
                                <td>{{$count}}</td>
                                <td>
                                     {{$tender->EGPTenderId}}  
                                </td>
                                <td>
                                     {{$tender->EtlTenderWorkId}}
                                </td>
                                <td class="">
                                     {{convertDateTimeToClientFormat($tender->TenderOpeningDateAndTime)}}
                                </td>
                                <td>
                                    {{$tender->Category}}
                                </td>
                                <td>
                                    {{$tender->Classification}}
                                </td>
                                <td class="">
                                    {{strip_tags($tender->NameOfWork)}}
                                </td>
                                <td class="">

                                    @if($tender->Method=='OPEN_TENDERING')
                                        Open Tendering Method
                                    @elseif($tender->Method=='LIMITED_TENDERING')
                                        Limited Tendering Method
                                    @elseif($tender->Method=='DIRECT_CONTRACTING')
                                        Direct Contracting Method
                                    @elseif($tender->Method=='LIMITED_ENQUIRY')
                                        Limited Enquiry Method
                                    @endif
                                </td>
                               <td>
                               <span style="color:
                                    @if($tender->tenderStatus=='Awarded')
                                        green
                                    @elseif($tender->tenderStatus=='Evaluated')
                                        purple
                                    @elseif($tender->tenderStatus=='Under Process')
                                        blue
                                    @elseif($tender->tenderStatus=='Cancelled')
                                        red
                                    @endif">
                                    {{$tender->tenderStatus}}

                                </span>
                                
                                </td>
                                <td>
                                    <a href="{{URL::to('newEtl/evaluationcommiteeetool/'.$tender->Id)}}" class="btn default btn-xs bg-green-haze"><i class="fa fa-edit"></i> Evaluation Commitee</a>
                                    <a href="{{URL::to('newEtl/awardingcommiteeetool/'.$tender->Id)}}" class="btn default btn-xs purple"><i class="fa fa-edit"></i> Tender Commitee</a>
                                    @if($tender->Method=='LIMITED_ENQUIRY' || $tender->Method=='SINGLE_SOURCE')
                                        <a href="{{URL::to('newEtl/workevaluationdetailssmallcontractors/'.$tender->Id)}}" class="btn default btn-xs bg-red-sunglo"><i class="fa fa-edit"></i> Details</a>
                                    @else
                                        <a href="{{URL::to(((($tender->Classification=='S') || $tender->Classification=='R'))
                                        ?'newEtl/workevaluationdetailssmallcontractors/'.$tender->Id:'newEtl/workevaluationdetails/'.$tender->Id)}}" class="btn default btn-xs bg-red-sunglo"><i class="fa fa-edit"></i> Details</a>

                                    @endif




                                </td>
                            </tr>
                            <?php $count++; ?>
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