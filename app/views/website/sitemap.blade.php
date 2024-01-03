@extends('websitemaster')
@section('main-content')
<div class="row">
	<div class="col-md-12">
		<div class="col-md-3">
			<h5><strong>WHO IS WHO</strong></h5>
			<a href="{{URL::to("web/boardmembers")}}">Board Members</a><br>
			<a href="{{URL::to("web/cdbsecretariat")}}">CDB Secretariat</a><br>
			<a href="{{URL::to("web/organogram")}}">Organogram</a><br><br>

			<h5><Strong>FAQ/FEEDBACK</Strong></h5>
			<a href="{{URL::to("web/feedback")}}">Feedback Forms</a><br>
			<a href="{{URL::to("web/faq")}}">FAQ</a><br><br>

			<h5><Strong>EZOTIN</Strong></h5>
			<a href="{{URL::to("ezhotin/home/1")}}">CRPS</a><br>
			<a href="{{URL::to("ezhotin/home/2")}}">E-TOOL</a><br>
			<a href="{{URL::to("ezhotin/home/3")}}">CINET</a><br>
			<a href="{{URL::to("ezhotin/home/4")}}">Registration Services</a><br>

		</div>
		<div class="col-md-3">
			<h5><strong>LISTS AND REPORTS</strong></h5>
			<a href="{{URL::to("web/listofarbitrators")}}">List of Arbitrators</a><br>
			<a href="{{URL::to("web/listofcontractors")}}">List of Contractors</a><br>
			<a href="{{URL::to("web/listofconsultants")}}">List of Consultants</a><br>
			<a href="{{URL::to("web/listofarchitects")}}">List of Architects</a><br>
			<a href="{{URL::to("web/listofengineers")}}">List of Engineers</a><br>
			<a href="{{URL::to("web/listofspecializedtrades")}}">List of Specialized Traders</a><br><br>

			<h5><Strong>OTHER LINKS</Strong></h5>
			<a href="{{URL::to("web/aboutus")}}">About Us</a><br>
			<a href="{{URL::to("web/tenderlist")}}">Tenders</a><br>
			<a href="{{URL::to("web/contactus")}}">Contact Us</a><br>
			<a href="{{URL::to("web/photogallery")}}">Photo Gallery</a><br>
			<a href="{{URL::to("web/viewforum")}}">Forum</a><br>
			<a href="{{URL::to("web/arbitrationforum")}}">Arbitration Forum</a>
			<br><br>

		</div>
		<div class="col-md-3">
			<h5><strong>SERVICES</strong></h5>
			<a href="{{URL::to("web/arbitration")}}">Arbitration</a><br>
			<a href="{{URL::to("web/listoftrainings")}}">Trainings</a><br>
			<a href="{{URL::to("web/eventcalendar")}}">Event Calendar</a><br>
			<a href="{{URL::to("web/contractorregistrationdetails")}}">Contractor Registration</a><br>
			<a href="{{URL::to("web/consultantregistrationdetails")}}">Consultant Registration</a><br>
			<a href="{{URL::to("web/architectregistrationdetails")}}">Architect Registration</a><br>
			<a href="{{URL::to("web/specializedtraderegistrationdetails")}}">Specialized Trade Registration</a><br>
		</div>
		<div class="col-md-2">
		<h5><strong>DOWNLOADS</strong></h5>
		<a href="{{URL::to("web/downloads")}}">All Downloads</a><br>
		<a href="{{URL::to("web/optionsdownloads/75d0d214-2adb-11e5-ac49-080027dcfac6")}}">Contractor Registration</a><br>
		<a href="{{URL::to("web/optionsdownloads/faa6b09d-2adc-11e5-ac49-080027dcfac6")}}">Consultant Registration</a><br>
		<a href="{{URL::to("web/optionsdownloads/28f3f3ad-2add-11e5-ac49-080027dcfac6")}}">Architect Registration</a><br>
		<a href="{{URL::to("web/optionsdownloads/52390c56-2add-11e5-ac49-080027dcfac6")}}">Specialized Trade Registration</a><br>
		<a href="{{URL::to("web/optionsdownloads/7173ffc1-2add-11e5-ac49-080027dcfac6")}}">Arbitration Services</a><br>
		<a href="{{URL::to("web/optionsdownloads/c39a1e6d-2ade-11e5-ac49-080027dcfac6")}}">Construction Excellence Award</a><br>
		<a href="{{URL::to("web/optionsdownloads/05899391-23c7-11e6-9911-c81f66edb959")}}">Council of Engineers</a><br>
		<a href="{{URL::to("web/optionsdownloads/6e6e76fc-2ef3-11e6-8ab3-c81f66edb959")}}">Rules and Regulations</a><br>
		<a href="{{URL::to("web/optionsdownloads/7dfad74f-3b83-11e6-9728-c81f66edb959")}}">Construction Industry Information</a><br>
		<a href="{{URL::to("web/optionsdownloads/df9d80f8-3d0c-11e6-9728-c81f66edb959")}}">e-Tool Training Documents</a><br>
	</div>

	</div>
</div>
@stop