@extends('websitemaster')
@section('main-content')

<h1>Hello JC</h1>

    {{-- <div class="container">
        @if (Auth::user())
            <h4>Journal Coordinator</h4>
        @endif
        <div class="row">


            <div class="border border-color:#ffffff">
                <h6><strong>User lists</strong></h6>

                <!-- Striped table -->
                <div class="table-responsive card pmd-card">
                    <!-- Table -->
                    <table class="table pmd-table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>Application no</th>
                                <th>Type of Work</th>
                                <th>Submission Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($users as $item)
                                <tr>
                                    <td><a href="{{ url('application/' . $item->id) }}">{{ $item->id }}</a></td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->contact }}</td>
                                </tr>
                            @endforeach


                        </tbody>
                    </table>
                </div>

            </div>

            <div class="col-sm-6 pt-5 px-1 ">
                
                <div class="border border-color:#ffffff rounded">
                    <h6><strong>Lists From Reviewer</strong></h6>
                    <!-- Striped table -->
                    <div class="table-responsive card pmd-card">
                        <!-- Table -->
                        <table class="table pmd-table table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Application no</th>
                                    <th>Type of Work</th>
                                    <th>Submission Date</th>

                                </tr>
                            </thead>
                            <tbody> --}}

                                {{-- @foreach ($status as $items)
                                <tr>
                                <td><a href="{{ url('application_jc/'.$items->id)}}">{{$items->id}}</a></td>
                                <td>{{$items->name}}</td>
                                <td>{{$items->contact}}</td>
                                </tr>
                                @endforeach --}}


                            {{-- </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="border border-color:#ffffff rounded mt-5">
                    <h6><strong>Lists From Editor In Chief</strong></h6>
                    <!-- Striped table -->
                    <div class="table-responsive card pmd-card">
                        <!-- Table -->
                        <table class="table pmd-table table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Application no</th>
                                    <th>Type of Work</th>
                                    <th>Submission Date</th>

                                </tr>
                            </thead>
                            <tbody> --}}

                                {{-- @foreach ($status as $item)
                                    <tr>
                                        <td><a href="{{ url('application_jc/' . $item->id) }}">{{ $item->id }}</a></td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->contact }}</td>
                                    </tr>
                                @endforeach --}}


                            {{-- </tbody>
                        </table>
                    </div>
                </div>
            </div> --}}
            {{-- <div class="font-weight-bold col-sm-11 mt-2 pb-2">
                User lists /
                <a href="from_reviewer">Reviewer</a> /
                <a href="from_chief">Editor In Chief</a>
            </div> --}}

        {{-- </div>
    </div> --}}

@stop
