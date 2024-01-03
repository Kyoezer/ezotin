@extends('master')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-green-seagreen">
                    <i class="fa fa-cogs"></i>Search {{$title}}
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
                                <a href="{{URL::to($redirectUrl)}}" class="btn red-sunglo btn-sm">
                                Cancel <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <table id="tablefilters_1" class="table table-bordered table-striped table-condensed flip-content">
                    <thead>
                        <tr>
                            @foreach($columns as $column)
                                <th @if((int)$column->DataTableOrderBy==1)class="order"@endif>{{$column->ColumnText}}</th>
                            @endforeach
                            <th>
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($searchResults as $searchResult)
                        <tr>
                            @foreach($columns as $column)
                            <?php $value=$column->ColumnName; ?>
                            <td>{{$searchResult->$value}}</td>
                            @endforeach
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