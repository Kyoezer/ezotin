@extends('homepagemaster')
@section('content')
<div class="row">
	<div class="col-md-offset-2 col-md-8">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption font-red">
					<i class="fa fa-cogs"></i>
					<span class="caption-subject">Warning!</span>
				</div>
			</div>
			<div class="portlet-body">
				<blockquote>
					<p class="text-justify">
						Seems like you have already reapplied your application or your reapplication link has expired. Your reapplication link will be active for 30 days from the day of the rejection. Contact Construction Development Board for assistance.
					</p>
				</blockquote>
				<div class="form-actions">
					<div class="row">
						<div class="col-md-12">
							<a href="{{URL::to('/')}}" class="btn red">Back to Website <i class="fa fa-times"></i></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop