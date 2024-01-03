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
					<span class="caption-subject font-green-seagreen">Add Circular</span>
				</div>
			</div>
			<div class="portlet-body form">
				{{ Form::open(array('action'=>'WebsiteAddNewCircular@addNewCircularData', 'method'=>'POST','class'=>'form-horizontal','files'=>true)) }}
				@foreach($circular as $detail)
				{{Form::hidden('Id',$detail->Id)}}
				<div class="form-body">
					<div class="form-group">
						<label class="control-label col-md-3">Circular Title</label>
						<div class="col-md-7">
							<input type="text" name="Title" value="{{$detail->Title}}" class="required form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Circular Type</label>
						<div class="col-md-7">
							<select class="form-control required" name="CircularType" id="circular-type">
								<option value="">---SELECT ONE---</option>
								@foreach ($circularTypes as $circularType)
									<option value="{{$circularType->Id}}" @if($circularType->Id == $detail->CircularTypeId)selected="selected"@endif data-reference="{{$circularType->ReferenceNo}}">{{$circularType->CircularName}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Content</label>
						<div class="col-md-9">
							<textarea name="Content" id="editor" rows="15">{{$detail->Content}}</textarea>
						</div>
					</div>
					@if(!empty($detail->ImageUpload))
						<span>
							<img src="{{asset($detail->ImageUpload)}}" width="100" height="100"/> <i data-id="{{$detail->Id}}" class="fa fa-2x fa-times delete-circular-img"></i>
						</span>
					@endif
					<div class="form-group">
						<label class="control-label col-md-3">Upload Image</label>
						<div class="col-md-7">
							{{ Form::file('Image_Upload') }}
						</div>
					</div>
					@if(!empty($detail->Attachment))
						<span>
							<a href="{{asset($detail->Attachment)}}">Attachment</a>
							<i data-id="{{$detail->Id}}" class="fa fa-times delete-circular-attachment"></i>
						</span>
					@endif
					<div class="form-group">
						<label class="control-label col-md-3">Attachment</label>
						<div class="col-md-7">
							{{ Form::file('Attachment') }}
						</div>
					</div>
					{{Form::hidden('Featured','0',array('id'=>'featuredhidden-toggle','disabled'=>'disabled'))}}
					<div class="form-group" id="featureddiv-toggle">
						<label class="control-label col-md-3">Featured in Home Slider</label>
							<label class="radio-inline">
						      <input type="radio" name="Featured" value="1" <?php if($detail->Featured == 1): ?>checked="checked"<?php endif; ?>/> Yes &nbsp;&nbsp;
						    </label>
						    <label class="radio-inline">
						      <input type="radio" name="Featured" value="0" @if(empty($detail->Id))checked="checked"@else<?php if($detail->Featured == 0): ?>checked="checked"<?php endif; ?>@endif/> No
						    </label>
					</div>
				</div>
				<div class="form-actions">
					<div class="btn-set">
						<button type="submit" class="btn green">Save</button>
						<a href="{{Request::url()}}" class="btn red">Cancel</a>
					</div>
				</div>
				@endforeach
				{{Form::close()}}
			</div>
		</div>
	</div>		
</div>
@stop