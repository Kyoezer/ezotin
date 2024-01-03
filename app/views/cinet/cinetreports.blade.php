@extends('horizontalmenumaster')
@section('pagescripts')
    {{ HTML::script('assets/global/scripts/etool.js') }}
@stop
@section('content')
    <input type="hidden" name="URL" value="{{CONST_APACHESITELINK}}"/>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>Reports
		</div>
	</div>
	<div class="portlet-body">
        <div class="container etoolreport-container">
            @if(in_array(1,$cinetReports))
            <div class="row">
                <div class="col-md-3">1. Contractor's Work in Hand</div>
                <div class="col-md-8">
                    <div class="form-group">
                        <div class="col-md-6">
                            <input name="parameter" type="hidden" value="CDBNo"/>
                            <div class="col-md-9"><input type="text" class="form-control input-sm" placeholder="CDBNo"/></div>
                            <div class="col-md-3"><a href="{{URL::to('cinet/contractorworkinhand')}}" class="btn btn-sm blue-hoki">View</a></div>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            @endif
            @if(in_array(2,$cinetReports))
            <div class="row">
                <div class="col-md-3">2. Contractor's Human Resource</div>
                <div class="col-md-8">
                    <div class="form-group">
                        <div class="col-md-6">
                            <input name="parameter" type="hidden" value="CDBNo"/>
                            <div class="col-md-9"><input type="text" class="form-control input-sm" placeholder="CDBNo"/></div>
                            <div class="col-md-3"><a href="{{URL::to('cinet/contractorhumanresource')}}" class="btn btn-sm blue-hoki">View</a></div>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            @endif
            @if(in_array(3,$cinetReports))
            <div class="row">
                <div class="col-md-3">3. Contractor's Equipment Details</div>
                <div class="col-md-8">
                    <div class="form-group">
                        <div class="col-md-6">
                            <input name="parameter" type="hidden" value="CDBNo"/>
                            <div class="col-md-9"><input type="text" class="form-control input-sm" placeholder="CDBNo"/></div>
                            <div class="col-md-3"><a href="{{URL::to('cinet/contractorequipment')}}" class="btn btn-sm blue-hoki">View</a></div>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            @endif
            @if(in_array(4,$cinetReports))
            <div class="row">
                <div class="col-md-3">4. Contractor's Information</div>
                <div class="col-md-8">
                    <div class="form-group">
                        <div class="col-md-6">
                            <input name="parameter" type="hidden" value="CDBNo"/>
                            <div class="col-md-9"><input type="text" class="form-control input-sm" placeholder="CDBNo"/></div>
                            <div class="col-md-3"><a href="{{URL::to('cinet/contractorinfo')}}" class="btn btn-sm blue-hoki">View</a></div>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            @endif
            @if(in_array(5,$cinetReports))
            <div class="row">
                <div class="col-md-3">5. Human Resource Check</div>
                <div class="col-md-8">
                    <div class="form-group">
                        <div class="col-md-6">
                            <input name="parameter" type="hidden" value="CIDNo"/>
                            <div class="col-md-9"><input type="text" class="form-control input-sm" placeholder="CID No (Human)"/></div>
                            <div class="col-md-3"><a href="{{URL::to('cinet/hrcheck')}}" class="btn btn-sm blue-hoki">View</a></div>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            @endif
            @if(in_array(6,$cinetReports))
            <div class="row">
                <div class="col-md-3">6. Equipment Check</div>
                <div class="col-md-8">
                    <div class="form-group">
                        <div class="col-md-6">
                            <input name="parameter" type="hidden" value="RegistrationNo"/>
                            <div class="col-md-9"><input type="text" class="form-control input-sm" placeholder="Registration No. (Equipment)"/></div>
                            <div class="col-md-3"><a href="{{URL::to('cinet/equipmentcheck')}}" class="btn btn-sm blue-hoki">View</a></div>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            @endif
        </div>
	</div>
</div>
@stop