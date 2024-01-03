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
					<span class="caption-subject font-green-seagreen">{{$pageTitle}}</span>
				</div>
			</div>
			<div class="portlet-body form">
				{{ Form::open(array('url'=>'web/saveregistrationpage', 'class'=>'form-horizontal', 'files'=>true))}}
				@foreach($saved as $detail)
				<div class="form-body">
					{{Form::hidden('Id',$detail->Id)}}
					{{Form::hidden('Type',$type)}}
					{{ Form::label('Page Content:') }}
					{{ Form::textarea('Content', $detail->Content, array('class'=>'form-control','id'=>'editor')) }}
				</div>
				@endforeach
				<div class="form-actions">
					<div class="btn-set">
						<button type="submit" class="btn green">Save</button>
						<a href="{{URL::to('web/editwebreg/'.$type)}}" class="btn red">Cancel</a>
					</div>
				</div>
				{{Form::close()}}
			</div>
		</div>
	</div>		
</div>
@stop