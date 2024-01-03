@extends('websitemaster')
@section('main-content')
<!-- <h4 class="text-primary"><strong>Forums</strong></h4> -->
<!-- <div class="table-responsive">
	?php 
		$i = 0;
	?>
	@forelse($forum as $forums)
	<div class="panel-heading">
		<h4 class="panel-title">
			<b><a  href="{{ URL::to('web/detailforum')}}/{{ $forums->id }}">{{ ++$i }}. {{ $forums->topic }}</a></b>
		</h4>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

		?php 
				$datediff = date_diff(date_create($forums->CreatedOn),date_create(date('Y-m-d G:i:s')));
				$diffInMonths = $datediff->format("%m");
				$diffInDays = $datediff->format("%a");
				$diffInHours = $datediff->format("%h");
				$diffInMinutes = $datediff->format("%i");

				if((int)$diffInMonths > 0){
					$diff = $datediff->format("%m months, %d days");
				}else{
					if((int)$diffInDays > 0){
						$diff = $datediff->format("%a days");
					}else{
						if((int)$diffInHours > 0){
							$diff = $datediff->format("%h hours");
						}else{
							$diff = $datediff->format("%i minutes");
						}
					}
				}
		 ?> 
		<small><i class="fa fa-clock-o"></i> {{$diff}} </small> 
		<small> <i class="fa  fa-comments"></i> {{ $forums->CommentCount }}</small>
	</div>
	@empty
	ddd
	
	@endforelse 
</div> -->


<?php
	include public_path()."/captcha/simple-php-captcha.php";
?>
<?php
	$_SESSION['captcha'] = simple_php_captcha(
        array(
            'min_font_size' => 24,
            'max_font_size' => 24,
        )
    );
	$imgSrc = $_SESSION['captcha']['image_src'];
	$indexOfQuestionMark = strpos($imgSrc,'?');
	$captchaUrl = substr($imgSrc,$indexOfQuestionMark+1,strlen($imgSrc));
?>


<div class="row">
	   <div class="col-md-6 col-sm-12 col-xs-12"> 
		 <h4 class="text-primary"><strong>We welcome your feedback on our service delivery here.</strong></h4>
			{{Form::open(array('action'=>'WebsiteContactUsController@contactUsMail', 'method'=>'POST'))}}
			<div class="form-group">
				<label for="name">Name:</label>
				<input type="text" id="name" class="form-control required" name="FullName" placeholder="Full Name">
			</div>
			<div class="form-group">
				<label for="Subject">Subject: </label>
				<select name="Subject" id="Subject" class="form-control required" >
					<option value="">Pick one </option>
					<option value="arbitration@cdb.gov.bt">Arbitration</option>
					<option value="registration@cdb.gov.bt">Registration</option>
					<option value="webmaster@cdb.gov.bt">IT</option>
					<option value="promotion@cdb.gov.bt">Others</option>
				</select>
			</div>
			<div class="form-group">
				<label for="email">Email:</label>
				<input type="email" id="email" class="form-control required email" name="Email" placeholder="Email">
			</div>
			<div class="form-group">
				<label for="message">Message:</label>
				<textarea name="Message" id="message" class="form-control required" rows="10"></textarea>
			</div>
			<div class="col-md-4 text-center">
				<img src="{{URL::to('/')."/captcha/simple-php-captcha.php?".$captchaUrl}}"/>
			</div><div class="clearfix"></div><br/>
			<div class="form-group">
				<div class="col-md-4">
					<input type="text" name="CaptchaAnswer" class="form-control">
				</div>
			</div><div class="clearfix"></div>
          <br>
			<div class="form-group">
				&nbsp;&nbsp;&nbsp;
				<input type="submit" class="btn btn-success" value="Submit">
				<input type="button" class="btn btn-danger" onClick="window.location.reload();" value="Clear">
			</div>
		  {{ Form::close() }}
	</div> 

@stop