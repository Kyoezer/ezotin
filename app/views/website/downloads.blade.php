@extends('websitemaster')
@section('main-content')
<div class="row">
<div class="col-md-12">
<h4 class="text-primary"><strong>{{$downloadTitle}}</strong></h4>
<div class="row">
	<div class="col-md-12">
		<div class="table-responsive">
			<table class="table table-bordered table-striped table-hover table-responsive">
				<thead>
					<tr class="success">
						<th>Sl#</th>
						<th>Category</th>
						<th>File Name</th>
						<th>Word</th>
						<th>PDF</th>
						<th>Other</th>
					</tr>
				</thead>
				<tbody>
					<?php $curCategory=''; ?>
					@forelse ($downloadDetails as $downloadDetail)
						<tr>
							<td>{{ $slno++ }}</td>
							<td>
								@if($curCategory!=$downloadDetail->CategoryName)
									<strong><span class="text-primary">{{$downloadDetail->CategoryName}}</span></strong>
								@endif
							</td>
							<td>{{$downloadDetail->FileName}}</td>
							@if(!empty($downloadDetail->Word) && !empty($downloadDetail->PDF) && !empty($downloadDetail->PDF))
								<td>
									<a href="{{ asset($downloadDetail->Word) }}" class="btn btn-xs btn-primary" target="_blank">Word <span class="fa fa-download"></span></a>
								</td>
								<td>
									<a href="{{ asset($downloadDetail->PDF) }}" class="btn btn-xs btn-danger" target="_blank">PDF <span class="fa fa-download"></span></a>
								</td>
								<td>
									<a href="{{ asset($downloadDetail->Other) }}" class="btn btn-xs btn-success" target="_blank">Other <span class="fa fa-download"></span></a>
								</td>
							@elseif(!empty($downloadDetail->Word))
								<td>
									<a href="{{ asset($downloadDetail->Word) }}" class="btn btn-xs btn-primary" target="_blank">Word <span class="fa fa-download"></span></a>
								</td>
								<td>-</td>
								<td>-</td>
							@elseif(!empty($downloadDetail->PDF))
								<td>-</td> 
								<td>
									<a href="{{ asset($downloadDetail->PDF) }}" class="btn btn-xs btn-danger" target="_blank">PDF <span class="fa fa-download"></span></a>
								</td>
								<td>-</td>
							@else
								<td>-</td> 
								<td>-</td>
								<td>
									<a href="{{ asset($downloadDetail->Other) }}" class="btn btn-xs btn-success" target="_blank">Download <span class="fa fa-download"></span></a>
								</td>	
							@endif	
						</tr>
						<?php $curCategory=$downloadDetail->CategoryName; ?>
					@empty
						<tr>
							<td colspan="5" class="text-center">No data to display</td>
						</tr>	
					@endforelse
				</tbody>
			</table>
		</div>	
	</div>
</div>
</div>
@stop