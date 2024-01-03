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
										{{ Form::open(array('url' => 'forum/save-topic','role'=>'form','files'=>true))}}
										<div class="row">
											<div class="col-md-12">
												<div class="form-group">
													<label class="control-label">Topic</label>
													<input type="hidden" name="id" value="{{$datas->id}}"/>
													<input type="text" value="{{ $datas->topic}}" class="form-control required" name="topic"/>
												</div>
											</div>
											
											<div class="col-md-12">
												<div class="form-group">
													<label class="control-label">Content</label>
													<textarea class="form-control required" name="content">{{$datas->content}}</textarea>
												</div>
											</div>
										</div>
										<div class="row">
					                        <div class="col-md-6">
					                            <div class="btn-group">
					                                <button type="submit" class="btn green btn-sm"> Update </button>
					                            </div>
					                        </div>
					                    </div>
					                	{{ Form::close() }}
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