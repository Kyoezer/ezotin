@extends('horizontalmenumaster')
@section('pagescripts')
    {{ HTML::script('ckeditor/ckeditor.js') }}
    {{ HTML::script('assets/global/scripts/etool.js') }}
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
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>Seek Clarification Details
		</div>
	</div>
	<div class="portlet-body flip-scroll">
        <div class="portlet-body">
            <div class="row">
                <div class="col-lg-12 table-responsive">
                    {{ Form::open(array('action'=>'MyEtool@submitClarificationRespond','class'=>'form-horizontal')) }}
                    <table class="table table-condensed table-bordered">        
                        <tbody>
                            <tr>
                                <td colspan="2" class="font-blue-madison bold warning">Enquiry Details</td>  
                            </tr>
                            <tr>
                                <td class="col-lg-2"><strong>CDB No.</strong></td>
                                <td>{{$seekClaraificationDtls[0]->CDB_No}}</td>
                            </tr>
                            <tr>
                                <td><strong>Tender Id</strong></td>
                                <td>{{$seekClaraificationDtls[0]->Tender_Id}}</td>
                            </tr>
                            <tr>
                                <td><strong>Enquiry</strong></td>
                                <td>{{$seekClaraificationDtls[0]->Enquiry}}</td>
                            </tr>
                            <tr>
                                <td><strong>Enquiry Date</strong></td>
                                <td>{{$seekClaraificationDtls[0]->Created_On}}</td>
                            </tr>
                            <tr>
                                <td><strong>Respond</strong></td>
                                <td>
                                <textarea required name="Respond" id="editor" class="form-control summernote required input-sm" rows="3">
							    </textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <button type="submit"  class="btn green">Submit</button>
                                    <a href="{{URL::to('newEtl/seekclarification')}}" class="btn red">Cancel</a>
                                    <input type="hidden" name="Id" value="{{$seekClaraificationDtls[0]->Id}}">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
	</div>
</div>
@stop