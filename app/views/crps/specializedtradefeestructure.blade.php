@extends('master')
@section('content')
    <div class="col-md-10">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-green-seagreen">
                    <i class="fa fa-gift"></i>
                    <span class="caption-subject">Specialized Trade's Fee Structure</span>
                </div>
            </div>
            <div class="portlet-body form">
                {{ Form::open(array('url' => 'all/savefeestructure','role'=>'form'))}}
                {{Form::hidden('RedirectUrl','specializedtrade/editfeestructure')}}
                <table class="table table-condensed table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>First Renewal Amount</th>
                            <th>After First Renewal Amount</th>
                            <th>Validity (if applicable)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($feeDetails as $feeDetail)
                            <?php $randomKey = randomString(); ?>
                            <tr>
                                <td>
                                    <input type="hidden" name="CrpService[{{$randomKey}}][Id]" value="{{$feeDetail->Id}}">
                                    {{$feeDetail->Name}}
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="CrpService[{{$randomKey}}][SpecializedTradeFirstRenewAmount]" value="{{$feeDetail->SpecializedTradeFirstRenewAmount}}"/>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="CrpService[{{$randomKey}}][SpecializedTradeAfterFirstRenewAmount]" value="{{$feeDetail->SpecializedTradeAfterFirstRenewAmount}}"/>
                                </td>
                                <td>
                                    <input type="text" @if(!($feeDetail->ReferenceNo == 1 || $feeDetail->ReferenceNo == 2))disabled="disabled"@endif class="form-control @if($feeDetail->ReferenceNo == 1 || $feeDetail->ReferenceNo == 2){{'required'}}@endif" name="CrpService[{{$randomKey}}][SpecializedTradeValidity]" value="{{$feeDetail->SpecializedTradeValidity}}"/>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>



                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn green">Save</button>
                                <a href="{{URL::to("master/feestructure")}}" class="btn red">Cancel</a>
                            </div>
                        </div>
                    </div>
                {{Form::close()}}
            </div>

        </div>
    </div>
    <div class="clearfix"></div>
@stop