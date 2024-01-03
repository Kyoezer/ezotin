@extends('websitemaster')
@section('main-content')
<h4 class="text-primary"><strong>Forums</strong></h4>
<div class="table-responsive">
	<?php 
		$i = 0;
	?>
	@forelse($forum as $forums)
	<div class="panel-heading">
		<h4 class="panel-title">
			<b><a  href="{{ URL::to('web/detailforum') }}">{{ ++$i }}. {{ $forums->topic }}</a></b>
			@if(Session::has('ArbitratorUserName'))
				<div class="pull-right">
					<a href="{{URL::to('web/arbitratorlogout')}}">
						<i class="fa fa-power-off"></i> Log Out </a>
				</div>
			@endif
		</h4>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

		<?php 
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
		<p>{{ $forums->content }}</p>


			@forelse($comment as $comments)
			<?php 
				$datediffC = date_diff(date_create($comments->CreatedOn),date_create(date('Y-m-d G:i:s')));
				$diffInMonthsC = $datediffC->format("%m");
				$diffInDaysC = $datediffC->format("%a");
				$diffInHoursC = $datediffC->format("%h");
				$diffInMinutesC = $datediffC->format("%i");

				if((int)$diffInMonthsC > 0){
					$diffC = $datediffC->format("%m months, %d days");
				}else{
					if((int)$diffInDaysC > 0){
						$diffC = $datediffC->format("%a days");
					}else{
						if((int)$diffInHoursC > 0){
							$diffC = $datediffC->format("%h hours");
						}else{
							$diffC = $datediffC->format("%i minutes");
						}
					}
				}
		 ?> 
			<blockquote>
				<span style="color: #41A85F; font-family: Georgia; font-style: normal; font-size: 12px;">
					{{ $comments->comments }}
					<small><i class="fa fa-clock-o"></i> {{$diffC}} <i class="fa fa-comments"></i> {{ $comments->name }}</small>
				</span>
			</blockquote>
			@empty
				<p><b>No Comments</b></p>
			@endforelse
			<div class="col-md-6">
				<h4> Leave Your Comments</h4>
					{{ Form::open(array('url' => 'web/postcomments','role'=>'form'))}}
						<input type="hidden" name="forum_id" value="{{$forums->id}}"/>
						<input type="text" value="{{{Session::has('ArbitratorUserName')?Session::get('ArbitratorUserName'):''}}}" class="form-control required" @if(Session::has('ArbitratorUserName'))readonly="readonly"@endif name="name" placeholder="Name"/>
						<br/>
						<textarea class="form-control required" name="comments" placeholder="Comments"></textarea><br/>
						<button type="submit" class="btn btn-primary"> Comment </button>
					{{ Form::close() }}
			</div>
	</div>
	@empty
		No articles
	@endforelse

</div>
@stop