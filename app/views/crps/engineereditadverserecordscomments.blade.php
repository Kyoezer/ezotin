@extends('master')
@section('pagescripts')
{{ HTML::script('assets/global/scripts/engineer.js') }}
@stop
@section('content')
<div class="page-bar">
	<ul class="page-breadcrumb">
		<li>
			<a href="#">Engineer</a>
			<i class="fa fa-angle-right"></i>
		</li>
		<li>
			<a href="#">Edit Comments/Adverse Records</a>
		</li>
	</ul>
</div>
<div id="engineercommentadverserecordedit" class="modal fade" role="dialog" aria-labelledby="engineercommentadverserecordedit" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			{{ Form::open(array('url' => 'engineer/meditcommentadverserecords','role'=>'form'))}}
			<input type="hidden" name="Id" class="engineercommentadverserecordid" />
			<input type="hidden" name="CrpEngineerFinalId" value="{{$engineer[0]->Id}}" />
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3 class="modal-title font-green-seagreen">Edit</h3>
			</div>
			<div class="modal-body">
				<div class="form-body">
					<div class="form-group">
						<label for="Date">Date</label>
						<div class="input-icon right input-medium">
							<i class="fa fa-calendar"></i>
							<input type="text" name="Date" class="form-control datepicker commentadverserecorddate required" placeholder="">
						</div>
					</div>
					<div class="form-group">
						<label class="labeladversecomment">Remarks</label>
						<textarea name="Remarks" class="form-control commentadverserecord required" rows="3"></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn green">Update</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
			{{Form::close()}}
		</div>
	</div>
</div>
<a href="{{URL::to('engineer/editcommentsadverserecordslist')}}" class="btn grey-cascade">Back to List</a>
<h3 class="font-blue-madison">{{$engineer[0]->Name.' (CDB No.'.$engineer[0]->CDBNo.')'}}</h3>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption font-green-seagreen">
					<i class="fa fa-cogs"></i>
					<span class="caption-subject">Comments</span>
					<span class="caption-helper">Inorder to edit comments click on the dropdown icon at right hand corner to expand</span>
				</div>
				<div class="tools">
					<a href="javascript:;" class="expand"></a>
				</div>
			</div>
			<div class="portlet-body display-hide">
				<div class="form-body">
					@forelse($commentsAdverseRecords as $commentsAdverseRecord)
						@if((int)$commentsAdverseRecord->Type==1)
							<div class="note note-info">
								<input type="hidden" class="commentid" value="{{$commentsAdverseRecord->Id}}">
								<input type="hidden" class="engineercommentdate" value="{{convertDateToClientFormat($commentsAdverseRecord->Date)}}" />
								<input type="hidden" class="engineercomment" value="{{$commentsAdverseRecord->Remarks}}" />
								<h5 class="bold">{{convertDateToClientFormat($commentsAdverseRecord->Date)}}</h5>
								<p>
									{{$commentsAdverseRecord->Remarks}}
								</p>
								<button type="button" class="btn green-haze btn-sm editengineercomment">Edit</button>
								<button type="button" class="btn bg-red-sunglo btn-sm deleteengineercomment">Delete</button>
							</div>
						@endif
					@empty
						<div class="note note-danger">
							<h5 class="bold font-red">No Comments.</h5>
						</div>
					@endforelse
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption font-green-seagreen">
					<i class="fa fa-cogs"></i>
					<span class="caption-subject">Adverse Records</span>
					<span class="caption-helper">Inorder to edit adverse records click on the dropdown icon at right hand corner to expand</span>
				</div>
				<div class="tools">
					<a href="javascript:;" class="expand"></a>
				</div>
			</div>
			<div class="portlet-body display-hide">
				<div class="form-body">
					@forelse($commentsAdverseRecords as $commentsAdverseRecord)
						@if((int)$commentsAdverseRecord->Type==2)
							<div class="note note-info">
								<input type="hidden" class="adverserecordid" value="{{$commentsAdverseRecord->Id}}">
								<input type="hidden" class="engineeradverserecorddate" value="{{convertDateToClientFormat($commentsAdverseRecord->Date)}}" />
								<input type="hidden" class="engineeradverserecord" value="{{$commentsAdverseRecord->Remarks}}" />
								<h5 class="bold">{{convertDateToClientFormat($commentsAdverseRecord->Date)}}</h5>
								<p>
									{{$commentsAdverseRecord->Remarks}}
								</p>
								<button type="button" class="btn green-haze btn-sm editengineeradverserecord">Edit</button>
								<button type="button" class="btn bg-red-sunglo btn-sm deleteengineeradverserecord">Delete</button>
							</div>
						@endif
					@empty
						<div class="note note-danger">
							<h5 class="bold font-red">No Adverse Records.</h5>
						</div>
					@endforelse
				</div>
			</div>
		</div>
	</div>
</div>
@stop