@extends('websitemaster')
@section('main-content')
<div class="row">
<div class="col-md-12">
{{ Form::open(array('url' =>Request::URL(),'role'=>'form','method'=>'get', 'class'=>'form-group')) }}
<h4 class="text-primary"><strong><i class="fa fa-list"></i> List of Specialized Trades</strong></h4>
<div class="alert alert-info">
		<p>Search specialized trades by selecting relevant parameters. If you select more than one parameter, the result will be combination of selected parameters.</p>
	</div>
<div class="row">
	<div class="col-md-2">
		<div class="form-group">
			<label class="control-label">SP No.:</label>
			<input type="text" name="SPNo" class="form-control" value="{{Input::get('SPNo')}}">
		</div>
	</div>
    <div class="col-md-4">
        <label class="control-label">Name</label>
       <input type="text" name="SpecializedTradeName" class="form-control" value="{{Input::get('SpecializedTradeName')}}">
	</div>
	<div class="col-md-2">
        <div class="form-group">
            <label class="control-label">No. of Rows</label>
            {{Form::select('Limit',array(10=>10,20=>20,30=>30,50=>50,'All'=>'All'),Input::has('Limit')?Input::get('Limit'):20,array('class'=>'form-control'))}}
        </div>
    </div>
    <div class="col-md-2">
    	<label>|</label>
		<div class="btn-set">
			<button type="submit" class="btn btn-primary">Search</button>
			<a href="{{Request::url()}}" class="btn btn-danger">Clear</a>
		</div>
	</div>
</div>
{{Form::close()}}
<div class="row">
	<div class="col-md-12 col-xs-12 col-sm-12 table-responsive">
		<table class="table table-bordered table-striped table-hover table-condensed flip-content">
			<thead class="flip-content">
				<tr>
                    <th>
                        Sl.No.
                    </th>
					<th class="order">
						 SP No.
					</th>
					<th>
						Name
					</th>				
					<th>
						Country
					</th>
					<th class="">
						 Dzongkhag
					</th>
					<th class="">
						Gewog
					</th>
                    <th class="">
                        Village
                    </th>
                    <th>
                    Category
					</th>
                    <th>
                        Expiry Date
                    </th>
                    <th>Status</th>
				</tr>
			</thead>
			<tbody>
			<?php $slNo=1; ?>
            @forelse($specializedTradeLists as $specializedTradeList)
				<tr>
                    <td>{{$slNo}}</td>
                    <td>{{$specializedTradeList->SPNo}}</td>
                    <td>{{$specializedTradeList->Name}}</td>                   
                    <td>{{$specializedTradeList->Country}}</td>
                    <td>{{$specializedTradeList->Dzongkhag}}</td>
                    <td>{{$specializedTradeList->Gewog}}</td>
                    <td>{{$specializedTradeList->Village}}</td>
                    <td>{{$specializedTradeList->Category}}</td>
                    <td>{{$specializedTradeList->RegistrationExpiryDate}}</td>
                    <td>{{$specializedTradeList->Status}}</td>
				</tr>



				<?php $slNo++;?>

				
				@empty
				<tr>
					<td class="font-red text-center" colspan="6" style="color:#FE0000;">No data to display</td>
				</tr>
				@endforelse
			</tbody>
		</table>
	</div>
</div>
</div>
@stop