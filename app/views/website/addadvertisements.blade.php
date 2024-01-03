@extends('master')
@section('pagescripts')
	{{ HTML::script('ckeditor/ckeditor.js') }}
	<script>
        CKEDITOR.replace( 'editor' ,{
            toolbar: [
                { name: 'document', items: [ 'Print' ] },
//                { name: 'clipboard', items: [ 'Undo', 'Redo' ] },
                { name: 'clipboard', groups: [ 'Clipboard', 'Undo','Redo' ] },
                { name: 'styles', items: [ 'Format', 'Font', 'FontSize' ] },
                { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline','Subscript','Superscript', 'Strike', 'RemoveFormat', 'CopyFormatting' ] },
                { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                { name: 'align', items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
                { name: 'links', items: [ 'Link', 'Unlink' ] },
                { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] },
                { name: 'insert', items: [ 'Image', 'Table' ] },
                { name: 'tools', items: [ 'Maximize' ] },
                { name: 'editing', items: [ 'Scayt' ] }
            ],
		});
	</script>
@stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-gift"></i>
					<span class="caption-subject font-green-seagreen">Post Advertisements</span>
				</div>
			</div>
			<div class="portlet-body form">
				{{ Form::open(array('action'=>'WebAddAdvertisement@addAdvertisementDetails', 'class'=>'form-horizontal', 'files'=>true))}}
				@foreach($advertisement as $detail)
				<div class="form-body">
					{{Form::hidden('Id',$detail->Id)}}
					{{ Form::label('Title:') }}
					{{ Form::text('Title', $detail->Title, array('class'=>'form-control','autofocus','placeholder'=>'File Name')) }}
					{{ Form::label('Circular Content:') }}
					{{ Form::textarea('Content', $detail->Content, array('class'=>'form-control summernote','id'=>'editor')) }}
					{{ Form::label('Upload Image:') }}
					{{ Form::file('ImageUpload') }}
					@if(isset($detail->Image) && !empty($detail->Image))
						<span>
						<img src="{{asset($detail->Image)}}" width="400" height="400"/>
						<i data-id="{{$detail->Id}}" class="fa fa-times delete-ad-img"></i>
						</span>
					@endif
					<br><br>
{{--					{{ Form::label('Upload File:') }}--}}
					<div class="col-md-7">
						<caption>Upload File:</caption>
						<table id="ad-attachment" class="table table-condensed table-bordered">
							<thead>
								<tr>
									<th></th>
									<th>Document Name</th>
									<th style="width: 250px;">File</th>
								</tr>
							</thead>
							<tbody>
							<tr>
								<td>
									<button type="button" class="deletetablerow"><i class="fa fa-times fa-lg"></i></button>
								</td>
								<td>
									<input type="text" name="AttachmentName[]" class="form-control input-sm" />
								</td>
								<td>
									<input type="file" name="FileUpload[]" class="form-control input-sm" />
								</td>
							</tr>
							<tr class="notremovefornew">
								<td>
									<button type="button" class="addtablerow"><i class="fa fa-plus fa-lg"></i></button>
								</td>
								<td colspan="2"></td>
							</tr>
							</tbody>
						</table>
					</div>
					<div class="clearfix"></div>
					@if(count($attachments) > 0)
						<br>
						<?php $count = 1; ?>
						@foreach($attachments as $attachment)
							<span>
							<a href="{{asset($attachment->AttachmentPath)}}" target="_blank">{{$attachment->AttachmentName}}</a>
							<i data-id="{{$attachment->Id}}" class="fa fa-times delete-ad-attachment"></i>
							</span> <br>
						@endforeach
					@endif
				</div>
				<div class="form-group">
					<label class="control-label col-md-2 pull-left">Show in Marquee

					</label>
					<div class="col-md-4">
						<div class="radio-list">
							<label class="radio-inline">
								<input type="radio" name="ShowInMarquee" value="1" <?php if(isset($detail->ShowInMarquee)): ?>@if($detail->ShowInMarquee == 1)checked="checked"@endif<?php else: ?>checked="checked"<?php endif; ?>/>
								Yes
							</label>

							<label class="radio-inline">
								<input type="radio" name="ShowInMarquee" value="0" <?php if($detail->ShowInMarquee == 0): ?>checked="checked"<?php endif; ?> />
								No
							</label>
						</div>
					</div>
				</div>
				@endforeach
				<div class="form-actions">
					<div class="btn-set">
						<button type="submit" class="btn green">Save</button>
						<a href="{{URL::to('web/editadvertisements')}}" class="btn red">Cancel</a>
					</div>
				</div>
				{{Form::close()}}
			</div>
		</div>
	</div>		
</div>
@stop