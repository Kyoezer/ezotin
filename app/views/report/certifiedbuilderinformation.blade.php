@extends('reportsmaster')
@section('pagescripts')
    {{HTML::script('assets/global/scripts/report.js')}}
@stop
@section('content')
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cogs"></i>CertifiedBuilder's Information
            </div>
        </div>
        <div class="portlet-body flip-scroll">
            {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">CDB No.</label>
                            <input name="CDBNo" class="form-control" type="text" value="{{Input::get('CDBNo')}}"/>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">&nbsp;</label>
                        <div class="btn-set">
                            <button type="submit" class="btn blue-hoki btn-sm">Search</button>
                            <a href="{{Request::url()}}" class="btn grey-cascade btn-sm">Clear</a>
                        </div>
                    </div>
                </div>
            </div>
            {{Form::close()}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed flip-content">
                    <thead class="flip-content">
                    <tr>
                        <th>
                            CDB No.
                        </th>
                        <th>
                            Ownership Type
                        </th>
                        <th>
                            Name of Firm
                        </th>
                        <th>
                            Country
                        </th>
                        <th>
                            Dzongkhag
                        </th>
                        <th>
                            Mobile#
                        </th>
                        <th>
                            Tel#
                        </th>
                        <th>
                            Email
                        </th>
                        <th>Status</th>
                        <th>Certificate</th>
                        <th>
                            Action
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($reportData as $contractorList)
                        <tr>
                            <td>
                                {{$contractorList->CDBNo}}
                            </td>
                            <td>
                                {{$contractorList->OwnershipType}}
                            </td>
                            <td>
                                {{$contractorList->NameOfFirm}}
                            </td>
                            <td>
                                {{$contractorList->Country}}
                            </td>
                            <td>
                                {{$contractorList->Dzongkhag}}
                            </td>
                            <td>
                                {{$contractorList->MobileNo}}
                            </td>
                            <td>
                                {{$contractorList->TelephoneNo}}
                            </td>
                            <td>
                                {{$contractorList->Email}}
                            </td>
                            <td >
                            {{$contractorList->Status}}
					</td>
                            <td>
                            @if((int)$contractorList->StatusReference == 12003)
                                <a href="{{URL::to('certifiedbuilder/certificate/'.$contractorList->Id)}}" class="btn default btn-xs blue" target='_blank'><i class="fa fa-edit"></i> View/Print</a>
                            @endif
                            </td>
                            <td>
                              
                                    <a href="{{URL::to('certifiedbuilder/viewprintdetails/'.$contractorList->Id)}}" class="btn default btn-xs green-seagreen" target='_blank'><i class="fa fa-edit"></i> View/Print</a>
                               
                                   
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="font-red text-center" colspan="11">No data to display</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop