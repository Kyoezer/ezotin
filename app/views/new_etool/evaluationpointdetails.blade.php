@extends('horizontalmenumaster')
@section('content')
<div class="page-bar">
	<ul class="page-breadcrumb">
		<li>
			<a href="#">E-TOOL</a>
			<i class="fa fa-angle-right"></i>
		</li>
		<li>
			<a href="#">Details</a>
		</li>
	</ul>
</div>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>Work Details
		</div>
	</div>
	<div class="portlet-body flip-scroll">
		<div class="form-body">
            @foreach($tenderDetails as $tenderDetail)
			<h4 class="bold">Contractor(s) : <span class="font-green-seagreen ">{{$tenderDetail->Contractor}}</span></h4>
			<h4 class="bold">Work Id : <span class="font-green-seagreen ">{{$tenderDetail->EtlTenderWorkId}}</span></h4>
			<p><span class="bold">Name : </span>{{$tenderDetail->NameOfWork}}</p>
			<p><span class="bold">Description : </span> {{$tenderDetail->DescriptionOfWork}}</p>
			@endforeach
            <table class="table table-bordered table-striped table-condensed flip-content">
				<tbody>
                @forelse($points as $point)
					<tr>
						<td class="font-blue-madison bold" colspan="2">Capability</td>
					</tr>
					<tr>
						<td>
							Similiar Work Experience
						</td>
						<td>
							{{number_format($point->Score1,2)}}
						</td>
					</tr>
					<tr>
						<td>
							Access to adequate equipment
						</td>
						<td>
                            {{number_format($point->Score2,2)}}
						</td>
					</tr>
					<tr>
						<td>
							Availability of skilled manpower
						</td>
						<td>
                            {{number_format($point->Score3,2)}}
						</td>
					</tr>
					<tr>
						<td>
							Average performance score from previous work
						</td>
						<td>
                            {{number_format($point->Score4,2)}}
						</td>
					</tr>
					<tr>
						<td class="font-blue-madison bold" colspan="2">Capacity</td>
					</tr>
					<tr>
						<td>
							Bid Capacity
						</td>
						<td>
                            {{number_format($point->Score5,2)}}
						</td>
					</tr>
					<tr>
						<td>
							Credit Line Available
						</td>
						<td>
                            {{number_format($point->Score6,2)}}
						</td>
					</tr>
					<tr class="bold">
						<td>Total</td>
						<td>{{number_format(($point->Score6 + $point->Score5 + $point->Score4 + $point->Score3 + $point->Score2 + $point->Score1),2)}}</td>
					</tr>
					<tr>
						<td class="font-blue-madison bold" colspan="2">Preference Score</td>
					</tr>
					<tr>
						<td>
							Status (incorporated, propiertorship, IV etc)
						</td>
						<td>
                            {{number_format($point->Score7,2)}}
						</td>
					</tr>

					<tr>
						<td>
							Bhutanese Employment
						</td>
						<td>
                            {{number_format($point->Score11,2)}}
						</td>
					</tr>
					{{--OLD <tr>
						<td>
							Employment of VTI Graduate/Local Skilled Labour
						</td>
						<td>
                            {{number_format($point->Score8,2)}}
						</td>
					</tr>
					<tr>
						<td>
							Committment of Internships to VTI Graduate
						</td>
						<td>
                            {{number_format($point->Score9,2)}}
						</td>
					</tr> --}}
					<tr class="bold">
						<td>Total</td>
						{{--OLD <td>{{number_format(($point->Score7 + $point->Score8 + $point->Score9),2)}}</td> --}}
						<td>{{number_format(($point->Score7 + $point->Score11),2)}}</td>
					</tr>
                @empty
                    <tr><td>Results have not been processed or Human Resource and Equipment Details have not been set for this contractor!</td></tr>
                @endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>
@stop