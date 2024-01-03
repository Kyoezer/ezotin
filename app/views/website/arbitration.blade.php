@extends('websitemaster')
@section('main-content')
<div class="row">
	<div class="col-md-12">
		<h4 class="text-primary"><strong>About Arbitration</strong></h4>

		<p class="text-justify">Contractual disputes are common in the construction industry. Unfortunately, most of
			these disputes are have been resolved only resolved in court, causing an over-crowded judicial system and
			lengthy delays in settlements.</p>

		<p class="text-justify">The CDB, in its capacity as "a bridging organization between the private and public
			sectors" offers itself as a neutral third party in dispute settlement. It does this by providing an optional
			arbitration service to all participants in the construction industry for the resolution of contractual
			disputes. Such disputes may be between one or more contractors, contractors and their sub-contractors,
			contractors and government agencies, and between joint venture partners and between any other service
			providers registered with CDB.</p>

		<p class="text-justify">The system is not mandatory and is solely provided for the benefit of the participants
			in the construction industry as a speedy and effective means of resolving disputes. To adopt this system,
			contractual parties may either insert an appropriate clause in their contracts or, following a dispute,
			submit the dispute by agreement to the arbitrators.</p>

		<p class="text-justify">To this end and in line with provisions of Alternative Dispute Resolution Act 2013, the
			CDB has developed formulated a system for arbitration that is described in the "Constitution, Rules and
			Arbitration Procedures of the Construction Arbitration Committee , Timeline for Arbitration, ToR for
			Arbitrators and Code of Ethics for Arbitrators" which is included in this website. All requests applications
			for arbitration shall be processed and disputes shall be resolved in accordance with the system for
			arbitration complying provisions of Alternative Dispute resolution Act 2013.</p>

		<p class="text-justify">CDB does not arbitrate disputes itself; it merely facilitates the selection of qualified
			arbitrators by the parties themselves through the establishment of an independent and competent List of
			trained and certified Arbitrators (membership of which is open and transparent) and provides a mechanism and
			procedure by which such a selection may be made.</p>

		<p class="text-justify">CDB further provides a formalized mechanism and procedure by which the institutional
			arbitration takes place through the provision of simple forms to be completed by the parties which will set
			in motion a procedure which is clear and well-defined, enabling all parties to have full confidence in the
			system.</p>

		<p class="text-justify">The administrative tasks of the Construction Arbitration Committee Arbitral Tribunal and
			all clerical matters arising out of the conduct of arbitration shall be carried out by the CDB Secretariat
			and expenses therefore shall be paid to the CDB according to the fees prescribed in the Rules. All other
			costs of the arbitration will be borne by the parties in accordance with the decision of the
			arbitrators.</p>


	@if(count($arbitrations) > 0)
			<?php $count = 1; ?>
				<br>
			<h4 class="text-primary"><strong>About Arbitration</strong></h4>
		@endif
		@foreach($arbitrations as $arbitration)
			<h4>{{$count}}. {{$arbitration->Title}}</h4>
			<p class="text-justify">
				{{html_entity_decode($arbitration->Content)}} <br>
				@if($arbitration->ImageUpload)
					<br>
				<img src="{{asset($arbitration->ImageUpload)}}" height="200"/>
					<br><br>
				@endif
				@if($arbitration->Attachment)
					<a href="{{asset($arbitration->Attachment)}}" >Attachment</a>
					<br><br>
				@endif
			</p>
			<?php $count++; ?>
		@endforeach
	</div>
@stop