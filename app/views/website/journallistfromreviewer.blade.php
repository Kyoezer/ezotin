@extends('master')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        {{-- <i class="fa fa-gift"></i> --}}
						<h5><b><a href="{{ url('/web/journalcoordinatorverification')}}">Back </a> / List From Reviewer</b></h5>
                    </div>
                    @if (Session::get('successfully'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong>{{ Session::get('successfully') }}</strong>
                        </div>
                    @endif
                </div>
                <div class="portlet-body form">
                    <!-- Striped table -->
                    <div class="table-responsive card pmd-card">
                        <!-- Table -->
                        <table class="table pmd-table table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Sl</th>
                                    <th>Application No</th>
                                    <th>Remarks</th>
                                    <th>Submitted Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach ($content as $detail)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $detail->Application_No }}</td>
                                        <td>{{ $detail->Remarks }}</td>
                                        <td>{{ date('d/m/Y', strtotime($detail->CreatedOn)) }}</td>
                                        <td>
                                            <a
                                                href="{{ url('/web/journalsendbacktoauthor/' . $detail->Application_No) }}">
                                                <button class="btn btn-danger" type="button">Return</button>
                                            </a>
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