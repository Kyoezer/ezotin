@extends('master')

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			
			<div class="portlet-body form">
					
					<!-- Striped table -->
					<div class="table-responsive card pmd-card">
						<!-- Table -->
						<table class="table pmd-table table-striped">
							<thead class="thead">
								<tr>
									<th>Sl</th>
									<th>Name</th>
									<th>Email</th>
									<th>Contact</th>
									<th>Submitted Date</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php $i=1; ?>
								@foreach($content as $detail)
									<tr>
										<td>{{$i++}}</td>
										<td>{{$detail->Name}}</td>
										<td>{{$detail->Email}}</td>
										<td>{{$detail->Contact}}</td>
										<td>{{date('d/m/Y', strtotime($detail->CreatedOn))}}</td>
										<td>
											<a href="{{ url('/web/registrationviewdetails/' . $detail->Id) }}">
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
</style>
@stop