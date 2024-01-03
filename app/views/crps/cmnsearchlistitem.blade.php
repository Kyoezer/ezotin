@extends('master')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs"></i>
                    <span class="caption-subject">Search {{$title}}</span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-toolbar">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="btn-group">
                                <a href="{{$redirectUrl}}" class="btn red-sunglo btn-sm">
                                Cancel <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <table id="tablefilters_1" class="table table-bordered table-striped table-condensed">
                    <thead>
                        <tr>
                            <th>
                                Code
                            </th>
                            <th class="order">
                                Name
                            </th>
                            <th>
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($searchResults as $searchResult)
                        <tr>
                            <td>
                                {{$searchResult->Code}}
                            </td>
                            <td>
                                {{$searchResult->Name}}
                            </td>
                            <td>
                                <a href="#" class="editaction">Edit</a>|
                                <a href="#" class="deleteaction">Delete</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop