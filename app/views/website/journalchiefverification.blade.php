@extends('master')

@section('content')
<div class="row">
	<div class="col-md-12">
		@if (Session::get('successchief'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong>{{ Session::get('successchief') }}</strong>
                        </div>
                    @endif
		<div class="portlet light bordered">
			<div class="portlet-body form">
					
					<!-- Striped table -->
					<div class="table-responsive card pmd-card">
						<!-- Table -->
						<table class="table pmd-table table-striped">
							<thead class="thead">
								<h3><b>Journal Editor In-Chief</b></h3>
                                    <p class="home"><b>Group Task Table / </b><a href="journalchiefmytask"> My Task Table </a></p>
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
								@foreach($grouptask as $detail)
									<tr>
										<td>{{$i++}}</td>
										<td>{{$detail->Application_No}}</td>
										<td class="title">{{ $detail->Name_of_Title }}</td>
										<td>{{date('d/m/Y', strtotime($detail->CreatedOn))}}</td>
										<td><b>{{ $detail->Task_Status }}</b></td>
										<td>
											<a
                                                href="{{ url('/web/journalclaimapplicationbychief/' . $detail->Application_No) }}">
                                                <button class="btn btn-primary" type="button">Claim Application</button>
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