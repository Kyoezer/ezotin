@extends('master')
@section('pagescripts')
    {{HTML::script("assets/global/plugins/bootstrap-editable/bootstrap-editable/js/bootstrap-editable.js")}}
    <script>
        $(".forum-editable").editable();
        if($(".forum-editable-select").length>0){
            var baseUrl = $('input[name="URL"]').val();
            $(".forum-editable-select").each(function(){
                var curElement = $(this);
                $(".forum-editable-select").editable({
                    value : $(this).data('value'),
                    source: baseUrl+'/web/fetchforumcategories',
                    success: function(response,newValue){

                    }
                });
            });
        }
    </script>
@stop
@section('pagestyles')
    {{HTML::style("assets/global/plugins/bootstrap-editable/bootstrap-editable/css/bootstrap-editable.css")}}
@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption font-green-seagreen">
                        <i class="fa fa-cogs"></i>
                        <span class="caption-subject">Forum Topics</span> <a href="{{URL::to('sys/managearbitrationforum')}}" class="btn blue">Back</a>
                    </div>
                </div>
                <div class="portlet-body form">
                    <div class="note note-info">
                        <strong>NOTE: </strong>Here you can edit the topics for the forum. Just click on the field that you want to edit. If you delete a topic, all associated posts will also be deleted!
                    </div>
                    {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="CategoryId">Category</label>
                                    <select name="CategoryId" class="form-control input-sm select2me">
                                        <option value="">---PICK---</option>
                                        @foreach($categories as $category)
                                            <option value="{{$category->value}}" @if($category->value == Input::get('CategoryId'))selected="selected"@endif>{{$category->text}}</option>
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
                    <div class="row">
                    <div class="col-md-10">
                    <div class="table-responsive">
                        <table class="table table-condensed table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Topic</th>
                                    <th style="width: 20%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topics as $topic)
                                    <?php $randomKey = randomString().randomString(); ?>
                                    <tr>
                                        <td>
                                            <a href="#" class="forum-editable-select" id="CategoryId" data-value="{{$topic->CategoryId}}" data-type="select" data-pk="{{$topic->Id}}" data-url="{{URL::to('sys/editforumtopic')}}" data-title="Edit Category">{{$topic->CategoryName}}</a>
                                        </td>
                                        <td>
                                            <a href="#" class="forum-editable" id="Subject" data-type="text" data-pk="{{$topic->Id}}" data-url="{{URL::to('sys/editforumtopic')}}" data-title="Edit Subject">{{$topic->Subject}}</a>
                                        </td>
                                        <td>
                                            <center>
                                                <a href="{{URL::to("sys/forumdelete?id=$topic->Id"."&type=2")}}" class="deleteaction btn btn-xs red"><i class="fa fa-times"></i> Delete</a>
                                            </center>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="font-red text-center">No data to display!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    </div>
                    </div>

                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
@stop