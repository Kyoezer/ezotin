@extends('master')
@section('pagescripts')
    {{HTML::script("assets/global/plugins/bootstrap-editable/bootstrap-editable/js/bootstrap-editable.js")}}
    <script>
        $(".forum-editable").editable();
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
                        <span class="caption-subject">Forum Posts</span> <a href="{{URL::to('sys/managearbitrationforum')}}" class="btn blue">Back</a>
                    </div>
                </div>
                <div class="portlet-body form">
                    <div class="note note-danger">
                        Please select a Topic first to view Posts
                    </div>
                    {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="TopicId">Topic</label>
                                    <select name="TopicId" required="required" class="resetKeyForNew select2me form-control input-xs">
                                        <option value="">---PICK---</option>
                                        <?php $lastGroup = '--'; ?>
                                        @foreach($topics as $topic)
                                        @if($lastGroup != $topic->Category)
                                        @if($lastGroup != '--')
                                        </optgroup>
                                        @endif
                                        <optgroup label="{{$topic->Category}}">
                                            @endif
                                            <option value="{{$topic->Id}}" <?php if($topic->Id == Input::get('TopicId')): ?>selected="selected"<?php endif; ?>>{{$topic->Topic}}</option>
                                            <?php $lastGroup = $topic->Category; ?>
                                            @endforeach
                                        </optgroup>
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
                    <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-condensed table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Posted By</th>
                                    <th>Content</th>
                                    <th style="width: 20%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($posts as $post)
                                    <tr>
                                        <td>
                                            {{$post->User}}<br>{{date_format(date_create($post->PostDate),'d-M-Y')}}<br/>{{date_format(date_create($post->PostDate),'G:i')}}
                                        </td>
                                        <td>{{$post->Content}}</td>
                                        <td>
                                            <center>
                                                <a href="{{URL::to("sys/forumdelete?id=$post->Id"."&type=3")}}" class="deleteaction btn btn-xs red"><i class="fa fa-times"></i> Delete</a>
                                            </center>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="font-red text-center">No data to display!</td>
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