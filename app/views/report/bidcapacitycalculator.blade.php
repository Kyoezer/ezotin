@extends('reportsmaster')
@section('pagescripts')
    {{HTML::script('assets/global/scripts/report.js')}}
@stop
@section('content')
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
                <i class="fa fa-cogs"></i>Bid Capacity Calculator
            </div>
        </div>
        <div class="portlet-body flip-scroll">
            <form role="form" class="form" method="POST" action="{{URL::to('etoolrpt/calculatebidcapacity')}}">
                <div class="form-body">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">Contractor/Firm</label>
                            <div class="ui-widget">
                                <input type="hidden" class="contractor-id" name="ContractorId" value="{{Input::get('ContractorId')}}"/>
                                <input type="text" name="Contractor" value="{{Input::get('Contractor')}}" class="form-control input-sm contractor-autocomplete"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" for="CDBNo">Start Date</label>
                            <input type="text" name="FromDate" value="{{Input::get('FromDate')}}" class="form-control input-sm date-picker required">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" for="CDBNo">End Date</label>
                            <input type="text" name="ToDate" value="{{Input::get('ToDate')}}" class="form-control input-sm date-picker required">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" for="QuotedAmount">Quoted Amount</label>
                            <input type="hidden" value="1" name="Submit">
                            <input type="text" id="QuotedAmount" name="QuotedAmount" value="{{Input::get('QuotedAmount')}}" class="form-control input-sm required">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="">&nbsp;</label>
                        <div class="btn-set">
                            <button type="submit" class="btn btn-sm blue">Calculate</button>
                            <a href="{{URL::to('etoolrpt/bidcapacitycalculator')}}" class="btn btn-sm red">Clear</a>
                        </div>
                    </div>
                </div>
            </form>
            <div class="clearfix"></div>
            @if(Input::has('Submit'))
                <div class="col-md-6">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr style="background: #dedede;">
                                <th>A</th>
                                <th>N</th>
                                <th>B</th>
                                <th>BID CAPACITY</th>
                            </tr>
                        </thead>
                    <tbody>
                        <tr>
                            <td>{{number_format($bidCapacityArray['A'],3)}}</td>
                            <td>{{$bidCapacityArray['N']}}</td>
                            <td>{{number_format($bidCapacityArray['B'],3)}}</td>
                            <td>{{$result}}</td>
                        </tr>
                    </tbody>
                </table>
                </div><div class="clearfix"></div>
            @endif
        </div>
    </div>
@stop