@extends('horizontalmenumaster')
@section('content')
<div class="row">
	<div class="col-md-offset-2 col-md-8">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption font-green-seagreen">
					<i class="fa fa-cogs"></i>
					<span class="caption-subject">Application for Other Services</span>
				</div>
			</div>
			<div class="portlet-body">
				<blockquote>
					<p class="text-justify">
						You can apply for multiple services in this section of your application togather.Relevant fees will be applicable. You can skip updating/upgrading of other information or services if you wish to apply for only one service. Below is the list of all the services that you can avail with this application.
					</p>
				</blockquote>
				<div class="col-md-12">
					<h4 class="font-green-seagreen">Service Fee Structure</h4>
					<table class="table table-bordered table-striped table-condensed">
						<thead>
							<tr>
								<th>Service</th>
								<th>Fee</th>
							</tr>
						</thead>
						<tbody>
							@foreach($feeStructures as $feeStructure)
							<tr>
								<td>{{$feeStructure->Name}}</td>
								<td>
									@if((bool)$feeStructure->ConsultantAmount!=null)
										{{number_format($feeStructure->ConsultantAmount,2)}}
									@else
										- 
									@endif
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				<div class="form-actions">
					<div class="row">
						<div class="col-md-12">
							<a href="{{URL::to('consultant/applyservicegeneralinfo/'.$consultantId)}}" class="btn green">Proceed <i class="fa fa-arrow-circle-o-right"></i></a>
							<a href="{{URL::to('consultant/profile')}}" class="btn red">Cancel <i class="fa fa-times"></i></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop