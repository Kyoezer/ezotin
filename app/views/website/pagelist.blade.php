@extends('master')
@section('content')

<div class="row">
	<div class="col-md-12 col-xs-12 col-sm-12">
		<h4 class="head-title">View Pages</h4>
		<div class="table-responsive">
			<table class="table table-striped table-condensed table-hover">
				<thead>
					<tr>
						<td><b>Page Name</b></td>
						<td><b>Content</b></td>
						<td>&nbsp;</td>
					</tr>
				</thead>

				<tbody>
					@foreach ($pages_list as $plist)
						{{ Form::open(array('action' => 'WebPostPageController@editPage', 'method' => 'post')) }}
							<tr>
								<td>{{{ $plist->Title }}}</td>
								<td>{{{ strip_tags(Str::limit($plist->Content, 100, '...')) }}}</td>
								<td>
									<input type="hidden" value="{{ $plist->Id }}" name="PageId" id="PageId">
									<input type="submit" value="Edit" class="btn btn-info">
								</td>
							</tr>
						{{ Form::close() }}
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>

@stop
