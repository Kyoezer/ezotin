@extends('websitemaster')
@section('main-content')
<div class="row">
	<div class="col-md-12">
		<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
			<!-- Indicators -->
			<ol class="carousel-indicators">
				<?php $slindicators=0;?>
				@foreach($sliderImages as $sliderImage)
					<?php if($slindicators==0){ ?>
						<li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
					<?php } else{ ?>
						<li data-target="#carousel-example-generic" data-slide-to="{{$slindicators}}"></li>
					<?php } ?>
					<?php $slindicators++;?>
				@endforeach
			</ol>
			<!-- Wrapper for slides -->
			<div class="carousel-inner">
				<?php $sl=0;?>
				@foreach($sliderImages as $sliderImage)
					@if($sl == 0)
						<div class="item active">
					@else
						<div class="item">
					@endif
						<div style="background:url(<?php echo asset($sliderImage->ImageUpload) ?>) center center; background-size:cover;" class="slider-size">
							<div class="carousel-caption">
								<h4>{{ $sliderImage->Title }}</h4>
							</div>
						</div>
					</div>
					<?php $sl++; ?>
				@endforeach
			</div>
		<!-- Controls -->
			<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
				<span class="glyphicon glyphicon-chevron-left"></span>
			</a>
			<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
				<span class="glyphicon glyphicon-chevron-right"></span>
			</a>
		</div>
	</div>
</div>

<?php $language=Session::get('language');
    // die($language);
    if($language == 'english'){
        ?>

<div class="hr"></div>
<div class="row">
	<div class="col-md-4">
		<h4 class="text-primary"><i class="fa fa-bullhorn"></i> News/Announcements <a href="{{URL::to('web/listofcirculars/news')}}" class="btn btn-primary btn-sm pull-right">View All</a></h4>
		<ul class="list-group" style="font-size: 9pt;">
			<?php $curDate = date('Y-m-d G:i:s'); ?>
			@foreach($listOfNews as $newsDetails)
				<?php 
					$newsDate = date_format(date_create($newsDetails->CreatedOn),'Y-m-d'); 
					$dateDiff = date_diff(date_create($curDate),date_create($newsDate));
					// if($dateDiff->noOfDays())
					$news = strip_tags(htmlspecialchars_decode($newsDetails->Content));
				?>
			<li class="list-group-item">
				<b>{{strip_tags(htmlspecialchars_decode($newsDetails->Title))}}  &nbsp;<iframe src="https://www.facebook.com/plugins/share_button.php?href={{URL::to('web/circulardetails/'.$newsDetails->Id)}}&layout=button_count&size=small&mobile_iframe=true&width=88&height=20&appId" width="88" height="20" style="border:none;overflow:hidden;margin-bottom:-4px;" scrolling="no" frameborder="0" allowTransparency="true"></iframe></b><br>
				{{substr(strip_tags($news), 0,120)}}...<a href="{{URL::to('web/circulardetails/'.$newsDetails->Id)}}" class="btn btn-xs">  Read More @if($dateDiff->format('%a') <= 7 && ($newsDate <= $curDate)) <span style="color: red;">&nbsp;&nbsp;<img src="{{asset('assets/global/img/new.gif')}}"/></span> @endif</a></li>
			@endforeach
		</ul>
	</div>
	<div class="col-md-4">
		<h4 class="text-primary"><i class="fa fa-book"></i> Notifications/Circulars <a href="{{URL::to('web/listofcirculars/notifications')}}" class="btn btn-primary btn-sm pull-right">View All</a></h4>
		<ul class="list-group" style="font-size: 9pt;">
			@foreach($listOfNotifications as $notificationDetail)
				<?php
					$newsDate = date_format(date_create($notificationDetail->CreatedOn),'Y-m-d');
					$dateDiff = date_diff(date_create($curDate),date_create($newsDate));
					// if($dateDiff->noOfDays())
				?>
				<li class="list-group-item">
					<b>{{strip_tags($notificationDetail->Title)}}</b><br>
					{{ HTML::decode(Str::limit(strip_tags(htmlspecialchars_decode($notificationDetail->Content)), 120, '...')) }}...<a href="{{URL::to('web/circulardetails/'.$notificationDetail->Id)}}" class="btn btn-xs"> Read More @if($dateDiff->format('%a') <= 7 && ($newsDate <= $curDate)) <span style="color: red;">&nbsp;&nbsp;<img src="{{asset('assets/global/img/new.gif')}}"/></span> @endif</a></li>
			@endforeach
		</ul>
	</div>
	<div class="col-md-4">
			<h4 class="text-primary"><i class="fa fa-book"></i> Advertisements <a href="{{URL::to('web/alladvertisements')}}" class="btn btn-primary btn-sm pull-right">View All</a></h4>
			<ul class="list-group" style="font-size: 9pt;">
				@foreach($listOfAdvertisements as $advertisement)
					<?php
					$adDate = date_format(date_create($advertisement->CreatedOn),'Y-m-d');
					$adDateDiff = date_diff(date_create($curDate),date_create($adDate));
					// if($dateDiff->noOfDays())
					?>
					<li class="list-group-item">
						<b>{{HTML::decode($advertisement->Title)}}</b><br>
						{{substr(strip_tags(html_entity_decode($advertisement->Content)), 0,120)}}...<a href="{{URL::to('web/advertisementdetails/'.$advertisement->Id)}}" class="btn btn-xs"> Read More @if($adDateDiff->format('%a') <= 7 && ($adDate <= $curDate)) <span style="color: red;">&nbsp;&nbsp;<img src="{{asset('assets/global/img/new.gif')}}"/></span> @endif</a></li>
				@endforeach
			</ul>
	</div>
	
</div>

	<!-- </div> -->
	<!-- <div class="col-md-4">
		<h4 class="text-primary"><i class="fa fa-cog"></i> Track Your Application</h4>
		{{ Form::open(array('action'=>'TrackApplicationController@trackApplication', 'method'=>'GET')) }}
			<div class="form-group">
				<label for="ApplicantType">Applicant Type</label>
				<select name="ApplicantType" class="form-control required">
					<option value="">---SELECT ONE---</option>
					<option value="1">Contractor</option>
					<option value="2">Consultant</option>
					<option value="3">Architect</option>
					<option value="4">Engineer</option>
					<option value="5">Specialized Trades</option>
				</select>
			</div>
			<div class="form-group">
				<label for="CIDNo">CID No. / Application No.</label>
				<input type="text" class="form-control required" name="ApplicationReference" placeholder="CID Number or Application No.">
			</div>
			<input type="submit" class="btn btn-primary" value="Track Application">
<a href="{{URL::to('https://www.citizenservices.gov.bt/construction-services/')}}" class="btn btn-sm btn-primary"> Click here to track your application</a> -->
	
<!-- </div> -->
	<div class="clearfix"></div>
	@if((int)$bottomToggle == 1)
	<div class="row">
		<div class="col-md-4">
			<div style="position: relative; width: 300px; margin-left: 16px; margin-top: -20px;">
				<h4 class="text-primary"><i class="fa fa-film"></i> TV Spot <a href="{{URL::to('web/allvideos')}}" class="btn btn-primary btn-sm pull-right">View All</a></h4>
				@if(count($video)>0)
					<video id=0 controls width=300 height=240 >
						<source src="{{asset($video[0]->OggVideoPath)}}" type='video/ogg; codecs="theora, vorbis"'/>
						<source src="{{asset($video[0]->WebmVideoPath)}}" type='video/webm' >
						<source src="{{asset($video[0]->Mp4VideoPath)}}" type='video/mp4'>
						<p>Video is not visible, most likely your browser does not support HTML5 video</p>
					</video>
				@endif
			</div>
		</div>	
		<div class="col-md-4" style="margin-top: 20px;">
				<div class="fb-page" data-href="https://www.facebook.com/CDBBHUTAN" data-tabs="timeline" data-height="240" data-small-header="true" data-adapt-container-width="true" data-hide-cover="true" data-show-facepile="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/CDBBHUTAN"><a href="https://www.facebook.com/CDBBHUTAN">Construction Development Board - CDB</a></blockquote></div>
			</div>
		</div>
		<div class="col-md-4">
			<h4 class="text-primary"> <i class="fa fa-bell"></i> Tender Anouncements <a href="{{URL::to('web/tenderlist')}}" class="btn btn-primary btn-sm pull-right">View All</a></h4>
			<ul class="list-group" style="font-size: 9pt;">
				@foreach($listOfTenders as $listOfTender)
					<li class="list-group-item">
						<i class="fa fa-bullhorn"></i> {{{substr(strip_tags($listOfTender->NameOfWork),0,180)}}}... <a href="{{URL::to('web/webtenderdetails/'.$listOfTender->Id)}}" class="btn btn-xs">View Details</a>
					</li>
				@endforeach
			</ul>
			{{ Form::close() }}
			<h4 class="text-primary"><i class="fa fa-tags"></i> Feedback <a href="{{URL::to('web/viewforum')}}" class="btn btn-primary btn-sm pull-right">View All</a></h4>
			<ul class="list-group">
				@foreach($forums as $forum)
					<li class="list-group-item">{{{substr(html_entity_decode($forum->topic),0,60)}}}...<a href="{{ URL::to('web/detailforum')}}/{{ $forum->id }}" class="btn btn-xs"> View </a></li>
				@endforeach
			</ul>
		</div>
	</div>
	@endif
	
<?php }else if ($language == 'dzongkha') {?>

<div class="hr"></div>
<div class="row">
	<div class="col-md-4">
		<h4 class="text-primary"><i class="fa fa-bullhorn"></i> <span class="dzo">གནས་ཚུལ/ཁྱབ༌བསྒྲགས།</span> <a href="{{URL::to('web/listofcirculars/news')}}" class="btn btn-primary btn-sm pull-right" id="foot"> ག་ར་ལྷག </a></h4>
		<ul class="list-group" style="font-size: 9pt;">
			<?php $curDate = date('Y-m-d G:i:s'); ?>
			@foreach($listOfNews as $newsDetails)
				<?php 
					$newsDate = date_format(date_create($newsDetails->CreatedOn),'Y-m-d'); 
					$dateDiff = date_diff(date_create($curDate),date_create($newsDate));
					// if($dateDiff->noOfDays())
					$news = strip_tags(htmlspecialchars_decode($newsDetails->Content));
				?>
			<li class="list-group-item">
				<b>{{strip_tags(htmlspecialchars_decode($newsDetails->Title))}}  &nbsp;<iframe src="https://www.facebook.com/plugins/share_button.php?href={{URL::to('web/circulardetails/'.$newsDetails->Id)}}&layout=button_count&size=small&mobile_iframe=true&width=88&height=20&appId" width="88" height="20" style="border:none;overflow:hidden;margin-bottom:-4px;" scrolling="no" frameborder="0" allowTransparency="true"></iframe></b><br>
				{{substr(strip_tags($news), 0,120)}}...<a href="{{URL::to('web/circulardetails/'.$newsDetails->Id)}}" class="btn btn-xs">  Read More @if($dateDiff->format('%a') <= 7 && ($newsDate <= $curDate)) <span style="color: red;">&nbsp;&nbsp;<img src="{{asset('assets/global/img/new.gif')}}"/></span> @endif</a></li>
			@endforeach
		</ul>
	</div>
	<div class="col-md-4">
		<h4 class="text-primary"><i class="fa fa-book"></i><span class="dzo"> གསལ་བསྒྲགས/ཁྱབ་བསྒྲགས།</span><a href="{{URL::to('web/listofcirculars/notifications')}}" class="btn btn-primary btn-sm pull-right" id="foot"> ག་ར་ལྷག </a></h4>
		<ul class="list-group" style="font-size: 9pt;">
			@foreach($listOfNotifications as $notificationDetail)
				<?php
					$newsDate = date_format(date_create($notificationDetail->CreatedOn),'Y-m-d');
					$dateDiff = date_diff(date_create($curDate),date_create($newsDate));
					// if($dateDiff->noOfDays())
				?>
				<li class="list-group-item">
					<b>{{strip_tags($notificationDetail->Title)}}</b><br>
					{{ HTML::decode(Str::limit(strip_tags(htmlspecialchars_decode($notificationDetail->Content)), 120, '...')) }}...<a href="{{URL::to('web/circulardetails/'.$notificationDetail->Id)}}" class="btn btn-xs"> Read More @if($dateDiff->format('%a') <= 7 && ($newsDate <= $curDate)) <span style="color: red;">&nbsp;&nbsp;<img src="{{asset('assets/global/img/new.gif')}}"/></span> @endif</a></li>
			@endforeach
		</ul>
	</div>
	<div class="col-md-4">
			<h4 class="text-primary"><i class="fa fa-book"></i></i><span class="dzo"> གསལ་བསྒྲགས། </span><a href="{{URL::to('web/alladvertisements')}}" class="btn btn-primary btn-sm pull-right" id="foot"> ག་ར་ལྷག </a></h4>
			<ul class="list-group" style="font-size: 9pt;">
				@foreach($listOfAdvertisements as $advertisement)
					<?php
					$adDate = date_format(date_create($advertisement->CreatedOn),'Y-m-d');
					$adDateDiff = date_diff(date_create($curDate),date_create($adDate));
					// if($dateDiff->noOfDays())
					?>
					<li class="list-group-item">
						<b>{{HTML::decode($advertisement->Title)}}</b><br>
						{{substr(strip_tags(html_entity_decode($advertisement->Content)), 0,120)}}...<a href="{{URL::to('web/advertisementdetails/'.$advertisement->Id)}}" class="btn btn-xs"> Read More @if($adDateDiff->format('%a') <= 7 && ($adDate <= $curDate)) <span style="color: red;">&nbsp;&nbsp;<img src="{{asset('assets/global/img/new.gif')}}"/></span> @endif</a></li>
				@endforeach
			</ul>
	</div>
	
</div>

	<!-- </div> -->
	<!-- <div class="col-md-4">
		<h4 class="text-primary"><i class="fa fa-cog"></i> Track Your Application</h4>
		{{ Form::open(array('action'=>'TrackApplicationController@trackApplication', 'method'=>'GET')) }}
			<div class="form-group">
				<label for="ApplicantType">Applicant Type</label>
				<select name="ApplicantType" class="form-control required">
					<option value="">---SELECT ONE---</option>
					<option value="1">Contractor</option>
					<option value="2">Consultant</option>
					<option value="3">Architect</option>
					<option value="4">Engineer</option>
					<option value="5">Specialized Trades</option>
				</select>
			</div>
			<div class="form-group">
				<label for="CIDNo">CID No. / Application No.</label>
				<input type="text" class="form-control required" name="ApplicationReference" placeholder="CID Number or Application No.">
			</div>
			<input type="submit" class="btn btn-primary" value="Track Application">
<a href="{{URL::to('https://www.citizenservices.gov.bt/construction-services/')}}" class="btn btn-sm btn-primary"> Click here to track your application</a> -->
	
<!-- </div> -->
	<div class="clearfix"></div>
	@if((int)$bottomToggle == 1)
	<div class="row">
		<div class="col-md-4">
			<div style="position: relative; width: 300px; margin-left: 16px; margin-top: -20px;">
				<h4 class="text-primary"><i class="fa fa-film"></i><span class="dzo"> རྒྱང་མཐོང། </span><a href="{{URL::to('web/allvideos')}}" class="btn btn-primary btn-sm pull-right" id="foot"> ག་ར་ལྷག </a></h4>
				@if(count($video)>0)
					<video id=0 controls width=300 height=240 >
						<source src="{{asset($video[0]->OggVideoPath)}}" type='video/ogg; codecs="theora, vorbis"'/>
						<source src="{{asset($video[0]->WebmVideoPath)}}" type='video/webm' >
						<source src="{{asset($video[0]->Mp4VideoPath)}}" type='video/mp4'>
						<p>Video is not visible, most likely your browser does not support HTML5 video</p>
					</video>
				@endif
			</div>
		</div>	
		<div class="col-md-4" style="margin-top: 20px;">
				<div class="fb-page" data-href="https://www.facebook.com/CDBBHUTAN" data-tabs="timeline" data-height="240" data-small-header="true" data-adapt-container-width="true" data-hide-cover="true" data-show-facepile="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/CDBBHUTAN"><a href="https://www.facebook.com/CDBBHUTAN">Construction Development Board - CDB</a></blockquote></div>
			</div>
		</div>
		<div class="col-md-4">
			<h4 class="text-primary"> <i class="fa fa-bell"></i><span class="dzo"> ཁས་ལེན་གནས་གོང་ གསལ་བསྒྲགས། </span><a href="{{URL::to('web/tenderlist')}}" class="btn btn-primary btn-sm pull-right" id="foot"> ག་ར་ལྷག </a></h4>
			<ul class="list-group" style="font-size: 9pt;">
				@foreach($listOfTenders as $listOfTender)
					<li class="list-group-item">
						<i class="fa fa-bullhorn"></i> {{{substr(strip_tags($listOfTender->NameOfWork),0,180)}}}... <a href="{{URL::to('web/webtenderdetails/'.$listOfTender->Id)}}" class="btn btn-xs"id="foot"> ག་ར་ལྷག </a>
					</li>
				@endforeach
			</ul>
			{{ Form::close() }}
			<h4 class="text-primary"><i class="fa fa-tags"></i><span class="dzo"> གྲོས་གནས་དོན་ཚན། </span><a href="{{URL::to('web/viewforum')}}" class="btn btn-primary btn-sm pull-right" id="foot"> ག་ར་ལྷག </a></h4>
			<ul class="list-group">
				@foreach($forums as $forum)
					<li class="list-group-item">{{{substr(html_entity_decode($forum->topic),0,60)}}}...<a href="{{ URL::to('web/detailforum')}}/{{ $forum->id }}" class="btn btn-xs"> View </a></li>
				@endforeach
			</ul>
		</div>
	</div>
	@endif
<?php }?>
	<style>
		.dzo{
			font-size: 27px;
			font-weight: bold;
		}
		#foot {
			line-height: 10px;
            font-size: 22px;
			padding-bottom: 4mm;
        }


	</style>



@stop
