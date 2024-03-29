@extends('websitemaster')
@section('main-content')

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
<!-- <div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3539.722387384766!2d89.62557841422984!3d27.477900842146443!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39e1941f7758618d%3A0xd07b29e422ca1755!2sConstruction+Development+Board!5e0!3m2!1sen!2sbt!4v1559630933723!5m2!1sen!2sbt"
	width="100%" height="480"></iframe>
	</div>
</div> -->

	<!-- <div class="row">
	<div class="col-md-6 col-sm-12 col-xs-12"> 
		 <h4 class="text-primary"><strong>We welcome your feedback on our service delivery here</strong></h4>
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
	</div>  -->
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<h4 class="text-primary"><strong>Contact Us</strong></h4>
			<b>Construction Development Board, Thonsel Lam</b><br />
			<b>Tel. No.:</b> +975-02-321948 <br />
			<b>Fax No.:</b> 321989 <br />
			<b>Post Box No.:</b> 1349<br />
			<b>Email:</b> cdbgroup@cdb.gov.bt
		</div>
		<!-- <img src="{{asset('uploads/cdb_location.jpg')}}" width="480" style="padding-left: 100px;"/> -->
	</div>
@stop
