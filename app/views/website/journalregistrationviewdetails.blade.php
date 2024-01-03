@extends('master')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-gift"></i>

                    </div>
                </div>
                <div class="portlet-body form">
                    @if (Session::get('messagesuccess'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong>{{ Session::get('messagesuccess') }}</strong>
                        </div>
                    @endif
                    <!-- Striped table -->
                    <div class="table-responsive card pmd-card">
                        <!-- Table -->
                        <table class="table pmd-table table-striped">
                            <thead>
                                <tr>
                                    <th>Sl.</th>
                                    <th>Title</th>
                                    <th>Application No.</th>
                                    <th>Status</th>
                                    <th>CreatedOn:</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1; ?>
                                @foreach ($content as $item)
                                
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $item->Name_of_Title }}</td>
                                        <td>{{ $item->Application_No }}</td>
                                        <td>{{ $item->Task_Status }}</td>
                                        <td>{{ $item->CreatedOn }}</td>

                                    </tr>
                                    
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                </div>
            </div>
        </div>
    </div>
    <style>
        thead{
            background-color: #423A39;
            color: #FFF;
        }
    </style>
@stop
