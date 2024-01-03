@extends('horizontalmenumaster')
@section('pagescripts')
    {{ HTML::script('assets/global/scripts/etool.js?ver='.randomString()) }}
@stop
@section('content')
<div class="page-bar">
	<ul class="page-breadcrumb">
		<li>
			<a href="#"><i class="fa fa-gavel"></i>E-Tool</a>
			<i class="fa fa-angle-right"></i>
		</li>
		<li>
			<a href="#"><i class="fa fa-keyboard-o"></i>Criteria</a>
		</li>
	</ul>
</div>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>Work Summary
		</div>
		<div class="tools">
			<a href="javascript:;" class="collapse"></a>
		</div>
	</div>
	<div class="portlet-body">
        @forelse($savedTender as $tender)
        <?php $hiddenWorkId=$tender->WorkId; ?>
		<div class="row">
			<div class="col-md-6">
				<table class="table table-bordered table-striped table-condensed flip-content">
					<tbody>
						<tr>
							<td width="30%"><strong>Work Id</strong></td>
							<td>
                                {{$tender->WorkId}}
                            </td>
						</tr>
						<tr>
							<td width="30%"><strong>Name of Work</strong></td>
							<td>{{$tender->NameOfWork}}</td>
						</tr>
                        <tr>
                            <td width="30%"><strong>Contract Description</strong></td>
                            <td>{{html_entity_decode($tender->DescriptionOfWork)}}</td>
                        </tr>
                        <tr>
                            <td width="30%"><strong>Tentative End Date</strong></td>
                            <td>{{$tender->TentativeEndDate}}</td>
                        </tr>
                        <tr>
                            <td width="30%"><strong>Project Cost Estimate</strong></td>
                            <td>{{$tender->ProjectEstimateCost}}</td>
                        </tr>
					</tbody>
				</table>
			</div>
            <div class="col-md-6">
                <table class="table table-bordered table-striped table-condensed flip-content">
                    <tbody>
                         <tr>
                            <td width="30%"><strong>Category of Work</strong></td>
                            <td>{{$tender->Category}}</td>
                        </tr>
                        <tr>
                            <td width="30%"><strong>Class</strong></td>
                            <td>{{$tender->Classification}}</td>
                        </tr>
                        <tr>
                            <td width="30%"><strong>Dzongkhag</strong></td>
                            <td>{{$tender->Dzongkhag}}</td>
                        </tr>
                         <tr>
                             <td width="30%"><strong>Tentative Start Date</strong></td>
                             <td>{{$tender->TentativeStartDate}}</td>
                         </tr>
                         <tr>
                             <td width="30%"><strong>Method</strong></td>
                             <td>
                                @if($tender->Method=='OPEN_TENDER')
                                    Open Tender
                                @elseif($tender->Method=='LIMITED_ENQUIRY')
                                    Limited Enquiry
                                @elseif($tender->Method=='SINGLE_SOURCE')
                                    Single Source Selection
                                @endif
                             </td> 
                         </tr>
                    </tbody>
                </table>
            </div>
		</div>
        @empty
        @endforelse
	</div>
</div>
<div class="portlet light bordered">
    <div class="tabbable-custom nav-justified">
        <ul class="nav nav-tabs nav-justified" id="add-detail-tab">
            <li class="active">
                <a href="#humanresource" data-toggle="tab">
                    Human Resource</a>
            </li>
            <li>
                <a href="#equipment" data-toggle="tab">
                    Equipments
                </a>
            </li>
        </ul>
        {{Form::open(array('url'=>'etl/savecriteria'))}}
        {{Form::hidden('EtlTenderId',$tenderId)}}
        {{Form::hidden('CurrentTab',Input::has('currentTab')?'#'.Input::get('currentTab'):'#humanresource')}}
        <input type="hidden" name="HiddenWorkId" value="{{$hiddenWorkId}}">
        <div class="tab-content">
            <div class="tab-pane active" id="humanresource">
		<div class="form-body">
            <h4>Human Resource</h4>
            @if($tender->classficationCode!="S")
            <table class="table table-bordered table-striped table-condensed flip-content" id="etoolcriteriahumanresource">
                <thead class="flip-content">
                <tr>
                    <th></th>
                    <th>
                        Tier
                    </th>
                    <th class="">
                        Designation
                    </th>
                    <th class="">
                        Qualification
                    </th>
                    <th class="">
                        Points
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($criteriaHR as $hr)
                    <?php $randomKey = randomString(); ?>
                <tr>
                    <td>
                        <button type="button" class="deletetablerow"><i class="fa fa-times fa-lg"></i></button>
                    </td>
                    <td>
                        <select name="etlcriteriahumanresource[{{$randomKey}}][EtlTierId]" id="" class="form-control required input-sm resetKeyForNew">
                            <option value="">---SELECT ONE---</option>
                            @foreach($etlTiersHR as $etlTier)
                                <option value="{{$etlTier->Id}}" data-maxpoints="{{$etlTier->HR_Points}}" data-name="{{$etlTier->Name}}" @if($etlTier->Id == $hr->EtlTierId)selected="selected"@endif>{{$etlTier->Name}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="etlcriteriahumanresource[{{$randomKey}}][CmnDesignationId]" id="" class="form-control required input-sm resetKeyForNew">
                            <option value="">---SELECT ONE---</option>
                            @foreach($designations as $designation)
                                <option value="{{$designation->Id}}" @if($designation->Id == $hr->CmnDesignationId)selected="selected"@endif>{{$designation->Name}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" name="etlcriteriahumanresource[{{$randomKey}}][Qualification]" value="{{$hr->Qualification}}" class="form-control input-sm required resetKeyForNew" />
                    </td>
                    <td>
                        <input type="text" name="etlcriteriahumanresource[{{$randomKey}}][Points]" value="{{$hr->Points}}" class="form-control input-sm required number resetKeyForNew" />
                    </td>
                </tr>
                @endforeach
                <tr class="notremovefornew">
                    <td>
                        <button type="button" class="addtablerow"><i class="fa fa-plus fa-lg"></i></button>
                    </td>
                    <td colspan="4"></td>
                </tr>
                </tbody>
            </table>
            @else
            <table class="table table-bordered table-striped table-condensed flip-content" id="etoolcriteriahumanresourcefors">
                <thead class="flip-content">
                <tr>
                    <th></th>
                    <th class="">
                        Designation
                    </th>
                    <th class="">
                        Qualification
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($criteriaHR as $hr)
                    <?php $randomKey = randomString(); ?>
                <tr>
                    <td>
                        <button type="button" class="deletetablerow"><i class="fa fa-times fa-lg"></i></button>
                    </td>
                   
                        <input type="hidden" name="etlcriteriahumanresource[{{$randomKey}}][EtlTierId]" value="0">

                    <td>
                        <select name="etlcriteriahumanresource[{{$randomKey}}][CmnDesignationId]" id="" class="form-control required input-sm resetKeyForNew">
                            <option value="">---SELECT ONE---</option>
                            @foreach($designations as $designation)
                                <option value="{{$designation->Id}}" @if($designation->Id == $hr->CmnDesignationId)selected="selected"@endif>{{$designation->Name}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" name="etlcriteriahumanresource[{{$randomKey}}][Qualification]" value="{{$hr->Qualification}}" class="form-control input-sm required resetKeyForNew" />
                    </td>
                </tr>
                @endforeach
                <tr class="notremovefornew">
                    <td>
                        <button type="button" class="addtablerow"><i class="fa fa-plus fa-lg"></i></button>
                    </td>
                    <td colspan="4"></td>
                </tr>
                </tbody>
            </table>
            @endif
            </div>
                </div>
                <div class="tab-pane" id="equipment">
            <h4>Equipments</h4>
            @if($tender->classficationCode!="S")
            <table class="table table-bordered table-striped table-condensed flip-content" id="etlequipments">
                <thead class="flip-content">
                <tr>
                    <th></th>
                    
                    <th>
                        Tier
                    </th>
                    <th class="">
                        Name
                    </th>
                    <th class="">
                        Quantity
                    </th>
                    <th class="">
                        Points
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($criteriaEquipments as $eq)
                    <?php $randomKey = randomString(); ?>
                <tr>
                    <td>
                        <button type="button" class="deletetablerow"><i class="fa fa-times fa-lg"></i></button>
                    </td>
                    <td>
                        <select name="etlcriteriaequipment[{{$randomKey}}][EtlTierId]" id="" class="form-control input-sm resetKeyForNew">
                            <option value="">---SELECT ONE---</option>
                            @foreach($etlTiersEqp as $etlTier)
                                <option value="{{$etlTier->Id}}"  data-name="{{$etlTier->Name}}" @if($etlTier->Id == $eq->EtlTierId)selected="selected"@endif data-maxpoints="{{$etlTier->Eq_Points}}">{{$etlTier->Name}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="etlcriteriaequipment[{{$randomKey}}][CmnEquipmentId]" id="" class="form-control input-sm resetKeyForNew">
                            <option value="">---SELECT ONE---</option>
                            @foreach($equipments as $equipment)
                                <option value="{{$equipment->Id}}" @if($equipment->Id == $eq->CmnEquipmentId)selected="selected"@endif>{{$equipment->Name}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" name="etlcriteriaequipment[{{$randomKey}}][Quantity]" value="{{$eq->Quantity}}" class="form-control number input-sm resetKeyForNew" />
                    </td>
                    <td>
                        <input type="text" name="etlcriteriaequipment[{{$randomKey}}][Points]" value="{{$eq->Points}}" class="form-control number input-sm resetKeyForNew" />
                    </td>
                </tr>
                @endforeach
                <tr class="notremovefornew">
                    <td>
                        <button type="button" class="addtablerow"><i class="fa fa-plus fa-lg"></i></button>
                    </td>
                    <td colspan="4"></td>
                </tr>
                </tbody>
            </table>
            @else
            <table class="table table-bordered table-striped table-condensed flip-content" id="etlequipmentsfors">
                <thead class="flip-content">
                <tr>
                    <th></th>
                    <th class="">
                        Name
                    </th>
                    <th class="">
                        Quantity
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($criteriaEquipments as $eq)
                    <?php $randomKey = randomString(); ?>
                <tr>
                    <td>
                        <button type="button" class="deletetablerow"><i class="fa fa-times fa-lg"></i></button>
                    </td>
                    <input type="hidden" name="etlcriteriaequipment[{{$randomKey}}][EtlTierId]" value="0">
                    <td>
                        <select name="etlcriteriaequipment[{{$randomKey}}][CmnEquipmentId]" id="" class="form-control input-sm resetKeyForNew">
                            <option value="">---SELECT ONE---</option>
                            @foreach($equipments as $equipment)
                                <option value="{{$equipment->Id}}" @if($equipment->Id == $eq->CmnEquipmentId)selected="selected"@endif>{{$equipment->Name}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" name="etlcriteriaequipment[{{$randomKey}}][Quantity]" value="{{$eq->Quantity}}" class="form-control number input-sm resetKeyForNew" />
                    </td>
                </tr>
                @endforeach
                <tr class="notremovefornew">
                    <td>
                        <button type="button" class="addtablerow"><i class="fa fa-plus fa-lg"></i></button>
                    </td>
                    <td colspan="4"></td>
                </tr>
                </tbody>
            </table>
            @endif
                    </div>
            {{--</div>--}}
		<div class="form-actions">
			<div class="btn-set">
                <a href="{{URL::to('etl/workidetool')}}" class="btn blue"><i class="m-icon-swapleft m-icon-white"></i>&nbsp;Back</a>
				<button type="submit" class="btn green">Update</button>
                <a href="{{URL::to('etl/workidetool')}}" class="btn red">Cancel</a>
			</div>
		</div>

	</div>
    </div>
</div>
    {{Form::close()}}
@stop