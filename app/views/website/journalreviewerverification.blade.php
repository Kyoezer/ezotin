@extends('master')

@section('content')
<div class="row">
	<div class="col-md-12">
		@if (Session::get('successreviewer'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong>{{ Session::get('successreviewer') }}</strong>
                        </div>
                    @endif
		<div class="portlet light bordered">
			<div class="portlet-body form">
					
					<!-- Striped table -->
					<div class="table-responsive card pmd-card">
						
						<table class="table pmd-table table-striped">
							<thead class="thead_mytask">
								<h3><b>Journal Reviewer</b></h3>
								<p><b>My Task Table</b></p>
								<tr>
									<th>Sl</th>
									<th>Application No</th>
									<th>Title</th>
									<th>Submitted Date</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php $i=1; ?>
								@foreach($mytask as $detail)
									<tr>
										<td>{{$i++}}</td>
										<td>{{$detail->Application_No}}</td>
										<td class="title">{{ $detail->Name_of_Title }}</td>
										<td>{{date('d/m/Y', strtotime($detail->CreatedOn))}}</td>
										<td><b>{{ $detail->Task_Status }}</b></td>
										<td>
											<a 
											    href="{{ url('/web/journalreviewerdetails/' . $detail->Application_No) }}">
                                                <button class="btn btn-primary" type="button">View Details</button>
                                            </a>
											
									    </td>
									</tr>
								@endforeach
								@foreach($mytask2 as $details)
									<tr>
										<td>{{$i++}}</td>
										<td>{{$details->Application_No}}</td>
										<td class="title">{{ $details->Name_of_Title }}</td>
										<td>{{date('d/m/Y', strtotime($details->CreatedOn))}}</td>
										<td><b>{{ $details->Task_Status }}</b></td>
										<td>
											<a 
											    href="{{ url('/web/journalreviewerdetails/' . $details->Application_No) }}">
                                                <button class="btn btn-primary" type="button">View Details</button>
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
<style>
	.thead{
		 background-color: lightgray
	}
	.thead_mytask{
		background-color: lightblue
	}
	.center{
                text-align: center;
                margin-bottom: 2cm;
            }
            textarea{
                border: hidden;
                width: 7cm;
                background: #10000000;
                resize: none;
            }
			.title{
				max-width: 9cm;
			}
			/* .title::-webkit-scrollbar {
            width: 3px;
            height: 1px;
            }
            .title::-webkit-scrollbar-track {
            border: 1px solid #ffffff;
            border-radius: 10px;
            }
            .title::-webkit-scrollbar-thumb {
            background: lightgray;
            border-radius: 10px;
            }
            .title::-webkit-scrollbar-thumb:hover {
            background: #88ba1c;
            } */
</style>
@stop