@extends('reportsmaster')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
<?php 
    $route="contractorrpt.listofcontractorswithworkinhand";
?>
   
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
			<i class="fa fa-cogs"></i>List of Surveyor  &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route($route,$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route($route,$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		</div>
	</div>
	<div class="portlet-body flip-scroll">
        @if(Input::get('export') != 'print')
            <?php 
                $url = 'contractorrpt/listofcontractorsotparticipatinganywork'; 
                $route="contractorrpt.listofcontractorsotparticipatinganywork";
            ?>
        {{Form::open(array('url'=>$url,'method'=>'get'))}}
		<div class="form-body">
			<div class="row">
                <div class="col-lg-12">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">CDB No</label>
                            <input type="text" name="CDBNo" value="{{Input::get('CDBNo')}}" class="form-control" />
                        </div>
                    </div>
                    
                     
                    @if(!Input::has('export'))
                        <div class="col-md-2">
                            <label class="control-label">&nbsp;</label>
                            <div class="btn-set">
                                <button type="submit" class="btn blue-hoki btn-sm">Search</button>
                                <a href="{{Request::url()}}" class="btn grey-cascade btn-sm">Clear</a>
                            </div>
                        </div>
                    @endif
                </div>
			</div>
		</div>
        {{Form::close()}}
        @else
            @foreach(Input::all() as $key=>$value)
                @if($key != 'export')
                    <b>{{$key}}: {{$value}}</b><br>
                @endif
            @endforeach
            <br/>
        @endif
		<table class="table table-bordered table-striped table-condensed flip-content" id="contractorhumanresource">
			<thead class="flip-content">
				<tr>
                    <th>
                        Sl.No.
                    </th>
					<th class="order">
						 CDB No.
					</th>
					<th>
						Name
					</th>
					
				</tr>
			</thead>
			<tbody>
            @forelse($contractorList as $contractor)
				<tr>
                    <td>{{$start++}}</td>
                    <td>{{$contractor->CDBNo}}</td>
                    <td>{{$contractor->NameOfFirm}}</td>
				</tr>
                @empty
                <tr>
                    <td colspan="14" class="font-red text-center">No data to display!</td>
                </tr>
                @endforelse
			</tbody>
		</table>
        <?php pagination($noOfPages,Input::all(),Input::get('page'),$route); ?>
	</div>
</div>
@stop