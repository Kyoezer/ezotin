@extends('websitemaster')
@section('main-content')
<div class="row">
	<div class="col-md-12 col-xs-12 col-sm-12">
	<h4 class="text-primary"> Arbitrators registered with Construction arbitration
		facilitation centre (cafc), cdb
	</h4>
	</div>
	<div class="col-md-12 col-xs-12 col-sm-12 table-responsive">
		<table class="table table-bordered table-striped table-hover table-condensed flip-content">
			<thead class="flip-content">
				<tr>
					<th>
						Reg#
					</th>
                    <th>
                       	Name
                    </th>
					<th>
						Designation
					</th>
					<th>
						 Mail Id
					</th>
					<th>
						Contact No.
					</th>
					<th>Cases in hand</th>
					<th>Image</th>
				</tr>
			</thead>
			<tbody>
			@foreach($arbitrators as $arbitrator)
				<tr>
					<td>{{$arbitrator->RegNo}}</td>
					<td>{{$arbitrator->Name}}</td>
					<td>{{$arbitrator->Designation}}</td>
					<td>{{$arbitrator->Email}}</td>
					<td>{{$arbitrator->ContactNo}}</td>
					<td>{{$arbitrator->CasesInHand}}</td>
					<td>
						@if((bool)$arbitrator->FilePath)
						<img src="{{asset($arbitrator->FilePath)}}" style="width:180px; height: 200px;"/>
						@endif
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
@stop