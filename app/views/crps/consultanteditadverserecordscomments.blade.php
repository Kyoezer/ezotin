@extends('master')
@section('pagescripts')
{{ HTML::script('assets/global/scripts/consultant.js') }}
@stop
@section('content')
<div id="consultantcommentadverserecordedit" class="modal fade" role="dialog" aria-labelledby="consultantcommentadverserecordedit" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			{{ Form::open(array('url' => 'consultant/meditcommentadverserecords','role'=>'form'))}}
			<input type="hidden" name="Id" class="consultantcommentadverserecordid" />
			<input type="hidden" name="CrpConsultantFinalId" value="{{$consultant[0]->Id}}" />
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
							<input type="text" name="Date" class="form-control required datepicker commentadverserecorddate" placeholder="">
						</div>
					</div>
					<div class="form-group">
						<label class="labeladversecomment">Remarks</label>
						<textarea name="Remarks" class="form-control required commentadverserecord" rows="3"></textarea>
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
<a href="{{URL::to('consultant/editcommentsadverserecordslist')}}" class="btn grey-cascade">Back to List</a>
<h3 class="font-blue-madison">{{$consultant[0]->NameOfFirm.' (CDB No.'.$consultant[0]->CDBNo.')'}}</h3>
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
								<input type="hidden" class="consultantcommentdate" value="{{convertDateToClientFormat($commentsAdverseRecord->Date)}}" />
								<input type="hidden" class="consultantcomment" value="{{$commentsAdverseRecord->Remarks}}" />
								<h5 class="bold">{{convertDateToClientFormat($commentsAdverseRecord->Date)}}</h5>
								<p>
									{{$commentsAdverseRecord->Remarks}}
								</p>
								<button type="button" class="btn green-haze btn-sm editconsultantcomment">Edit</button>
								<button type="button" class="btn bg-red-sunglo btn-sm deleteconsultantcomment">Delete</button>
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
								<input type="hidden" class="consultantadverserecorddate" value="{{convertDateToClientFormat($commentsAdverseRecord->Date)}}" />
								<input type="hidden" class="consultantadverserecord" value="{{$commentsAdverseRecord->Remarks}}" />
								<h5 class="bold">{{convertDateToClientFormat($commentsAdverseRecord->Date)}}</h5>
								<p>
									{{$commentsAdverseRecord->Remarks}}
								</p>
								<button type="button" class="btn green-haze btn-sm editconsultantadverserecord">Edit</button>
								<button type="button" class="btn bg-red-sunglo btn-sm deleteconsultantadverserecord">Delete</button>
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