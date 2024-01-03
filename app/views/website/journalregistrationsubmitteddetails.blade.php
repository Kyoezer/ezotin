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
                            <thead class="thead">
                                <tr>

                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($content as $item)
                                    <tr>
                                    <tr>
                                        <th>Id:</th>
                                        <td>{{ $item->RegistrationId}}</td>
                                    </tr>
                                    <tr>
                                        <th>Name:</th>
                                        <td>{{ $item->Name }}</td>

                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td>{{ $item->Email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Contact:</th>
                                        <td>{{ $item->Contact }}</td>
                                    </tr>
                                    <tr>
                                        <th>CreatedOn:</th>
                                        <td>{{ $item->CreatedOn }}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href="{{ url('/web/registrationapproved/' . $item->Id) }}">
                                                <button class="btn green" type="button">Approve</button>
                                            </a>

                                            <a href="{{ url('/web/application/' . $item->Id) }}">
                                                <button class="btn btn-danger" type="button">Return</button>
                                            </a>


                                        </td>
                                    </tr>

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
        .thead{
		 background-color: lightgray
	}
    </style>
@stop
