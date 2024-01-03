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
        <div class="container cbreport-container">
            <div class="row">
                <div class="col-md-3">1. Certified Builder's Work in Hand</div>
                <div class="col-md-8">
                    <div class="form-group">
                        <div class="col-md-6">
                            <input name="parameter" type="hidden" value="CDBNo"/>
                            <div class="col-md-9"><input type="text" class="form-control input-sm" placeholder="CDBNo"/></div>
                            <div class="col-md-3"><a href="{{URL::to('cb/certifiedbuilderworkinhand')}}" class="btn btn-sm blue-hoki">View</a></div>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-md-3">2. Certified Builder's Human Resource</div>
                <div class="col-md-8">
                    <div class="form-group">
                        <div class="col-md-6">
                            <input name="parameter" type="hidden" value="CDBNo"/>
                            <div class="col-md-9"><input type="text" class="form-control input-sm" placeholder="CDBNo"/></div>
                            <div class="col-md-3"><a href="{{URL::to('cb/certifiedbuilderhumanresource')}}" class="btn btn-sm blue-hoki">View</a></div>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-md-3">3. Certified Builder's Equipment Details</div>
                <div class="col-md-8">
                    <div class="form-group">
                        <div class="col-md-6">
                            <input name="parameter" type="hidden" value="CDBNo"/>
                            <div class="col-md-9"><input type="text" class="form-control input-sm" placeholder="CDBNo"/></div>
                            <div class="col-md-3"><a href="{{URL::to('cb/certifiedbuilderequipment')}}" class="btn btn-sm blue-hoki">View</a></div>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-md-3">4. Certified Builder's Information</div>
                <div class="col-md-8">
                    <div class="form-group">
                        <div class="col-md-6">
                            <input name="parameter" type="hidden" value="CDBNo"/>
                            <div class="col-md-9"><input type="text" class="form-control input-sm" placeholder="CDBNo"/></div>
                            <div class="col-md-3"><a href="{{URL::to('cb/certifiedbuilderinfo')}}" class="btn btn-sm blue-hoki">View</a></div>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-md-3">5. Human Resource Check</div>
                <div class="col-md-8">
                    <div class="form-group">
                        <div class="col-md-6">
                            <input name="parameter" type="hidden" value="CIDNo"/>
                            <div class="col-md-9"><input type="text" class="form-control input-sm" placeholder="CID No (Human)"/></div>
                            <div class="col-md-3"><a href="{{URL::to('cb/hrcheck')}}" class="btn btn-sm blue-hoki">View</a></div>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-md-3">6. Equipment Check</div>
                <div class="col-md-8">
                    <div class="form-group">
                        <div class="col-md-6">
                            <input name="parameter" type="hidden" value="RegistrationNo"/>
                            <div class="col-md-9"><input type="text" class="form-control input-sm" placeholder="Registration No. (Equipment)"/></div>
                            <div class="col-md-3"><a href="{{URL::to('cb/equipmentcheck')}}" class="btn btn-sm blue-hoki">View</a></div>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
        </div>
	</div>
</div>
@stop