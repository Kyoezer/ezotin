@extends('reportsmaster')
@section('pagescripts')
    {{HTML::script('assets/global/scripts/report.js')}}
@stop
@section('content')
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
                <i class="fa fa-cogs"></i>Work History &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route('etoolrpt.agencyworkhistory',$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route('etoolrpt.agencyworkhistory',$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
            </div>
        </div>
        <div class="portlet-body flip-scroll">
            @if(Input::get('export') != 'print')
            {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
            <div class="note note-info">Please select at least one parameter to view results</div>
            <div class="form-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Year</label>
                            <select class="form-control select2me" name="Year">
                                <option value="">---SELECT ONE---</option>
                                <?php $yearStart = 2000; $yearEnd = (int)date('Y') + 10; ?>
                                @for($i = $yearStart; $i<=$yearEnd; $i++)
                                    <option value="{{$i}}" @if(Input::get('Year')==$i)selected="selected"@endif>{{$i}}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">Work Start From</label>
                        <div class="input-icon right">
                            <i class="fa fa-calendar"></i>
                            <input type="text" name="FromDate" class="form-control input-sm datepicker" value="{{Input::get('FromDate')}}" readonly="readonly" placeholder="">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">To</label>
                        <div class="input-icon right">
                            <i class="fa fa-calendar"></i>
                            <input type="text" name="ToDate" class="form-control input-sm datepicker" value="{{Input::get('ToDate')}}" readonly="readonly" placeholder="">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Status</label>
                            <select class="form-control select2me" name="Status">
                                <option value="">All</option>
                                <option value="3001" @if(Input::get('Status') == '3001')selected="selected"@endif>Awarded</option>
                                <option value="3003" @if(Input::get('Status') == '3003')selected="selected"@endif>Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Classification</label>
                            <select class="form-control select2me" name="classification">
                                <option value="">---SELECT ONE---</option>
                                @foreach($classifications as $classification)
                                    <option value="{{$classification->classification}}" @if($classification->classification == Input::get('classification'))selected @endif>{{$classification->classification}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Category</label>
                            <select class="form-control select2me" name="Category">
                                <option value="">---SELECT ONE---</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->ProjectCategory}}" @if($category->ProjectCategory == Input::get('Category'))selected @endif>{{$category->ProjectCategory}}</option>
                                @endforeach
                            </select>
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
            {{Form::close()}}
            @else
                @foreach(Input::all() as $key=>$value)
                    @if($key != 'export')
                        <b>{{$key}}: {{$value}}</b><br>
                    @endif
                @endforeach
                    <br/>
            @endif
            <table class="table table-bordered table-striped table-condensed flip-content" id="">
                <thead class="flip-content">
                    <tr>
                        <th>Sl#</th>
                        <th>Year</th>
                        <th>Work Id</th>
                        <th>Name of Work</th>
                        <th>Description</th>
                        <th>Final Amount</th>
                        <th>CDB No.</th>
                        <th>Contractor</th>
                        <th>Category</th>
                        <th>Classification</th>
                        <th>Dzongkhag</th>
                        <th>Work Status</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($reportData as $data)
                    <tr>
                        <td>{{$start++}}</td>
                        <td>{{$data->Year}}</td>
                        <td>{{$data->WorkId}}</td>
                        <td>{{$data->NameOfWork}}</td>
                        <td>{{$data->DescriptionOfWork}}</td>
                        <td>{{$data->FinalAmount}}</td>
                        <td>{{$data->CDBNo}}</td>
                        <td>{{$data->Contractor}}</td>
                        <td>{{$data->ProjectCategory}}</td>
                        <td>{{$data->classification}}</td>
                        <td>{{$data->Dzongkhag}}</td>
                        <td>{{$data->WorkStatus}}</td>
                    </tr>
                @empty

                    <tr><td colspan="11" class="font-red text-center">No data to display</td></tr>
                @endforelse
                </tbody>
            </table>
            <?php pagination($noOfPages,Input::all(),Input::get('page'),"etoolrpt.selfworkhistory"); ?>
        </div>
    </div>
@stop