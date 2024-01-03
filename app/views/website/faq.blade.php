@extends('websitemaster')
@section('main-content')
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<h4 class="text-primary"><strong>FAQ (Frequently Asked Questions)</strong></h4>
		<div class="panel-group" id="accordion">
			<?php $sl=1; ?>
			@foreach($faqDetails as $faqDetail)
				<div class="panel panel-info">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#{{$faqDetail->Id}}">{{HTML::decode($sl.'. '.$faqDetail->Question)}}</a>
						</h4>
					</div>
					<div id="{{$faqDetail->Id}}" class="panel-collapse collapse">
						<div class="panel-body">
							{{HTML::decode($faqDetail->Answer)}}
						</div>
					</div>
				</div>
				<?php $sl++; ?>
			@endforeach
		</div>
	</div>

@stop