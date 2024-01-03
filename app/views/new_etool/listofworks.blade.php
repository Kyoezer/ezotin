@extends('horizontalmenumaster')
@section('pagescripts')
    {{ HTML::script('assets/global/scripts/etool.js') }}
@stop
@section('content')
    <input type="hidden" name="URL" value="{{CONST_APACHESITELINK}}"/>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>List of Works
		</div>
	</div>
	<div class="portlet-body">
        {{Form::open(array('url'=>'newEtl/listofworksetool','method'=>'get'))}}
		<div class="form-body">
			<div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label" for="cdbno">CDB No.:</label>
                        <input class="form-control" id="cdbno" type="text" class="cdbno" name="cdbno"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Contractor/Firm</label>
                        <div class="ui-widget">
                            <input type="hidden" class="contractor-id" name="CrpContractorFinalId" value="{{Input::get('ContractorId')}}"/>
                            <input type="text" name="Contractor" value="{{Input::get('Contractor')}}" class="form-control contractor-autocomplete"/>
                        </div>
                    </div>
                </div>
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">Work Id</label>
						<input type="text" name="WorkId" value="{{Input::get('WorkId')}}" class="form-control" class="text-right">
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
                    <th>Work Id</th>
					<th>
						 Procuring Agency
					</th>
					<th class="">
						 Contractor
					</th>
					<th class="">
						 Contract Work
					</th>
					<th class="">
						 Category of Work
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
                @forelse($tenders[$distinctYear->Year] as $tender)
				<tr>
                    <td>{{$tender->WorkId}}</td>
					<td>
						 {{$tender->ProcuringAgency}}
					</td>
					<td class="">
						 {{$tender->Contractor}}
					</td>
					<td class="">
						 {{$tender->NameOfWork}}
					</td>
					<td class="">
						 {{$tender->Category}}
					</td>
					<td>
						{{$tender->Status}}
					</td>
					<td>
						<a href="{{URL::to('newEtl/workcompletionformetool/'.$tender->Id)}}" class="btn default btn-xs green-seagreen editaction"><i class="fa fa-edit"></i> Edit</a>
					</td>
				</tr>
				@empty
					<tr>
						<td colspan="6" class="font-red text-center">No data to display</td>
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