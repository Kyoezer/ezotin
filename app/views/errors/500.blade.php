@extends('master')
@section('content')
 <div class="error-page">
	<h2 class="headline text-info">500 :(</h2>
	<div class="error-content">
	    <h3><i class="fa fa-warning text-yellow"></i> Oops! Something went wrong.</h3>
	    <p>
	        We will work on fixing that right away.
	        Meanwhile, you may <a href="{{URL::to('dashboard')}}">return to dashboard</a>
	    </p>
	</div><!-- /.error-content -->
</div><!-- /.error-page -->
@stop