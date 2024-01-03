@extends('reportsmaster')
@section('pagescripts')
    {{ HTML::script('assets/global/scripts/etool.js') }}
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>Tenders Uploaded from CiNET
		</div>
	</div>
	<div class="portlet-body flip-scroll">
        {{Form::open(array('url'=>Request::path(),'method'=>'get'))}}
        <div class="form-body">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Agency</label>
                        <select class="form-control select2me" name="ProcuringAgency">
                            <option value="">---SELECT ONE---</option>
                            @foreach($procuringAgencies as $agency)
                                <option value="{{$agency->Id}}" @if($agency->Id == Input::get('ProcuringAgency'))selected @endif>{{$agency->Name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Category</label>
                        <select class="form-control select2me" name="Category">
                            <option value="">---SELECT ONE---</option>
                            @foreach($contractorCategories as $contractorCategory)
                                <option value="{{$contractorCategory->Id}}" @if($contractorCategory->Id == Input::get('Category'))selected @endif>{{$contractorCategory->Code}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Classification</label>
                        <select class="form-control select2me" name="Class">
                            <option value="">---SELECT ONE---</option>
                            @foreach($contractorClassifications as $contractorClassification)
                                <option value="{{$contractorClassification->Id}}" @if($contractorClassification->Id == Input::get('Class'))selected="selected"@endif>{{$contractorClassification->Code}}</option>
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

                <table class="table table-bordered table-striped table-condensed flip-content" id="contractorhumanresource">
                    <thead class="flip-content">
                        <tr>
                            <th class="order">
                                 Sl. No.
                            </th>
                            <th>
                                Work Id
                            </th>
                            <th>Procuring Agency</th>
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
                        </tr>
                    </thead>
                    <tbody>
                    <?php $count = 1; ?>
                    @forelse($reportData as $uploadedTender)
                        <tr>
                            <td>
                                 {{$start++}}
                            </td>
                            <td>
                                 {{$uploadedTender->WorkId}}
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
                                {{$uploadedTender->Class}}
                            </td>
                            <td class="">
                                {{stripslashes(strip_tags($uploadedTender->NameOfWork))}}
                            </td>
                            <td>
                                {{$uploadedTender->ContractPeriod}}
                            </td>
                            <td>
                                {{$uploadedTender->ProjectEstimateCost}}
                            </td>
                        </tr>
                        <?php $count++; ?>
                        @empty
                        <tr><td colspan="10" class="font-red text-center">No data to display</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <?php pagination($noOfPages,Input::all(),Input::get('page'),"cinet.tendersuploaded"); ?>
	</div>
</div>
@stop