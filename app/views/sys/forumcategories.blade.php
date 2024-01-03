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
                        <span class="caption-subject">Forum Categories</span> <a href="{{URL::to('sys/managearbitrationforum')}}" class="btn blue">Back</a>
                    </div>
                </div>
                <div class="portlet-body form">
                    <div class="note note-info">
                        <strong>NOTE: </strong>Here you can edit the categories for the forum. Just click on the field that you want to edit. If you delete a category, all associated topics and posts will also be deleted!
                    </div>
                    <div class="col-md-10">
                    <div class="table-responsive">
                        <table class="table table-condensed table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Category Name</th>
                                    <th>Category Description</th>
                                    <th style="width: 20%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                    <tr>
                                        <td>
                                            <a href="#" class="forum-editable" id="CategoryName" data-type="text" data-pk="{{$category->Id}}" data-url="{{URL::to('sys/editforumcategory')}}" data-title="Edit Name">{{$category->CategoryName}}</a>
                                        </td>
                                        <td>
                                            <a href="#" class="forum-editable" id="CategoryDescription" data-type="text" data-pk="{{$category->Id}}" data-url="{{URL::to('sys/editforumcategory')}}" data-title="Edit Description">{{$category->CategoryDescription}}</a>
                                        </td>
                                        <td>
                                            <center>
                                                <a href="{{URL::to("sys/forumdelete?id=$category->Id"."&type=1")}}" class="deleteaction btn btn-xs red"><i class="fa fa-times"></i> Delete</a>
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
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
@stop