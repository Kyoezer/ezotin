@extends('horizontalmenumaster')
@section('content')
<div id="addbidsuploadedtender" class="modal fade" role="dialog" aria-labelledby="approve" aria-hidden="true">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3 class="modal-title font-green-seagreen">List of Uploaded Tenders</h3>
			</div>
			<div class="modal-body">
				<div class="table-responsive">
					<table class="table table-condensed table-bordered table-striped">
						<thead>
							<tr>
								<th>Ref#</th>
								<th>Work Id</th>
								<th>Last Dt and Time of Submission</th>
								<th>Opening Dt. and Time</th>
								<th>Category</th>
								<th>Classification</th>
								<th>Name of the Work</th>
								<th>Contract Period (Months)</th>
								<th>Estimated Project Cost</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@forelse($listOfUploadeTenders as $listOfUploadeTender)
							<tr>
								<td>
			                        <input type="hidden" name="Id" value="{{$listOfUploadeTender->Id}}"/>
									 {{$listOfUploadeTender->ReferenceNo}}
								</td>
								<td>
									 {{$listOfUploadeTender->EtlTenderWorkId}}
								</td>
								<td>
									 {{convertDateTimeToClientFormat($listOfUploadeTender->LastDateAndTimeOfSubmission)}}
								</td>
								<td>
			                        {{convertDateTimeToClientFormat($listOfUploadeTender->TenderOpeningDateAndTime)}}
								</td>
								<td>
									{{$listOfUploadeTender->Category}}
								</td>
								<td>
			                        {{$listOfUploadeTender->Classification}}
								</td>
								<td>
			                        {{$listOfUploadeTender->NameOfWork}}
								</td>
								<td>
									{{$listOfUploadeTender->ContractPeriod}}
								</td>
								<td>
									{{$listOfUploadeTender->ProjectEstimateCost}}
								</td>
								<td>
									<a href="{{URL::to('cinet/biddingform/'.$listOfUploadeTender->Id)}}" class="btn btn-xs green-seagreen">Add Bids</a>
								</td>
							</tr>
							@empty
							<tr>
								<td colspan="10" class="font-red text-center">No tender has been uploaded</td>
							</tr>
							@endforelse
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-offset-2 col-md-8">
		<div class="portlet light bordered">
			<div class="portlet-body">
				<div class="row">
					<div class="col-md-12 text-center">
						<a href="{{URL::to('cinet/biddingform')}}" class="btn green-seagreen btn-lg">Continue to Bidding Report</a> <strong>or</strong> <a href="#addbidsuploadedtender" data-toggle="modal" class="btn blue-hoki btn-lg">Add Bids aganist a Uploaded Tender</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop