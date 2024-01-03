@extends('homepagemaster')
@section('content')
<div class="row">
	<div class="col-md-offset-2 col-md-8">
		<div class="portlet light bordered">
			<div class="portlet-body">
				<h3 class="font-green-seagreen">Your application has been submitted!</h3>
				<blockquote>
					<h4 class="bold">Application No. {{$applicationNo}}</h4>
					<p class="text-justify">
						Thank you for applying for registration with Construction Development Board (CDB). Your application is under process. When it is approved, you will receive an email and SMS to the given email address and mobile number. You can track your application at <a href="">www.cdb.gov.bt/trackapplication</a> using your CID No. or Application No. If you want to print your application, click on the print button below. 
					</p>
				</blockquote>
				<a href="{{URL::to($linkToPrint)}}" class="btn btn-success btn-lg" target="_blank"><i class="fa fa-print"></i> Print</a>
				<a href="{{URL::to('web/index')}}" class="btn grey-cascade btn-lg"><i class="fa fa-home"></i> Home</a>
			</div>
		</div>
	</div>
</div>
@stop