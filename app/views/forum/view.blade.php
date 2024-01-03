@extends('master')

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered" id="form_wizard_1">
			<div class="portlet-body form">
				<div class="form-wizard">
					<div class="form-body">
						<div class="portlet box blue-madison">
							<div class="portlet-body">
								<div class="row">
									@foreach($data as $datas)
									<div class="col-md-12">
										<div class="row">
											<div class="col-md-6">
												<h1>{{ $datas->topic}}</h1><br/>
											</div>
											<div class="col-md-12">
												<br/>
													{{$datas->content}}
											</div>
											<div class="col-md-1">
											</div>
											<div class="col-md-11">
												<br/>

												@forelse($comment as $comments)
												<div class="note note-success">
													{{ $comments->comments }}
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
		 											<br/>
													<small><i class="fa fa-clock-o"></i> {{$diffC}} <i class="fa fa-comments"></i> {{ $comments->name }}</small>
													<a href="{{ URL::to('forum/comment/approve')}}/{{ $comments->id }}" style="float: right;" class="btn btn-primary">Approve it</a> . 
													<a href="{{ URL::to('forum/comment/disapprove')}}/{{ $comments->id }}" style="float: right;" class="btn red btn-primary">disapprove</a>

												</div>
												@empty
												no comments
												@endforelse

											</div>
											<div class="col-md-3">
											<a href="{{URL::to('forum/topic')}}" class="btn blue"><i class="m-icon-swapleft m-icon-white"></i>&nbsp;Back</a>
											</div>
										</div>
									</div>
									@endforeach
								</div>
							</div>	
						</div>	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop