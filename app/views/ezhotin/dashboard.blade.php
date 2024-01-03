@extends('master')
@section('content')


	<a href="{{URL::to('ezhotin/individualtaskreport')}}" class="btn btn-large red"><i class="fa fa-bar-chart-o"></i> Individual Task Report</a> <br><br>
	<div class="row">
	<div class="col-md-12">
		<div class="portlet solid bordered grey-cararra">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-comments"></i>News/Notices
				</div>
			</div>
			<div class="portlet-body" id="chats">
				<ul class="chats">
					@forelse($newsAndNotifications as $newsAndNotification)
					<li class="in">
						<img class="avatar" alt="" src="{{asset('assets/cdb/layout/img/avatar.png')}}"/>
						<div class="message">
							<span class="arrow">
							</span>
							<a href="#" class="name">
							Administrator </a>
							<span class="datetime">
							on {{convertDateToClientFormat($newsAndNotification->Date)}} </span>
							<span class="body">
							{{html_entity_decode($newsAndNotification->Message)}}
						</div>
					</li>
					@empty
						<li class="font-red">No News/Notices to display</li>
					@endforelse
				</ul>
			</div>
		</div>
		<!-- END PORTLET-->
	</div>
</div>
@stop