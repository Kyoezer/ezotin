@extends('master')
@section('content')
    <div class="portlet light bordered col-md-12">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-gift"></i> Change Expiry Date
            </div>
        </div>
        <div class="portlet-body form">
            <div class="row">
                <div class="col-md-6 table-responsive">
                    <table class="table table-condensed">
                        <tbody>
                        <tr>
                            <td colspan="2" class="font-blue-madison bold warning">General Info</td>
                        </tr>
                        <tr>
                            <td><strong>CDB No.</strong></td>
                            <td>{{{$generalInformation[0]->CDBNo}}}</td>
                        </tr>
                        <tr>
                            <td><strong>Ownership Type</strong></td>
                            <td>{{{$generalInformation[0]->OwnershipType}}}</td>
                        </tr>
                        <tr>
                            <td><strong>Company Name</strong></td>
                            <td>{{{$generalInformation[0]->NameOfFirm}}}</td>
                        </tr>
                        <tr>
                            <td><strong>Country</strong></td>
                            <td>{{{$generalInformation[0]->Country}}}</td>
                        </tr>
                        <tr>
                            <td><strong>Application Date</strong></td>
                            <td>{{convertDateToClientFormat($generalInformation[0]->ApplicationDate)}}</td>
                        </tr>
                        <tr>
                            <td><strong>Registration Expiry Date</strong></td>
                            <td>{{convertDateToClientFormat($generalInformation[0]->RegistrationExpiryDate)}}</td>
                        </tr>
                        <tr>
                            <td colspan="2" class="font-blue-madison bold warning">Registered Address</td>
                        </tr>
                        <tr>
                            <td><strong>Dzongkhag</strong></td>
                            <td>{{$generalInformation[0]->RegisteredDzongkhag}}</td>
                        </tr>
                        <tr>
                            <td><strong>Village</strong></td>
                            <td>{{$generalInformation[0]->Village}}</td>
                        </tr>
                        <tr>
                            <td><strong>Gewog</strong></td>
                            <td>{{$generalInformation[0]->Gewog}}</td>
                        </tr>
                        <tr>
                            <td><strong>Address</strong></td>
                            <td>{{$generalInformation[0]->RegisteredAddress}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6 table-responsive">
                    <table class="table table-condensed">
                        <tbody>
                        <tr>
                            <td colspan="2" class="font-blue-madison bold warning">Correspondence Address</td>
                        </tr>
                        <tr>
                            <td><strong>Dzongkhag</strong></td>
                            <td>{{{$generalInformation[0]->Dzongkhag}}}</td>
                        </tr>
                        <tr>
                            <td><strong>Address</strong></td>
                            <td>{{{$generalInformation[0]->Address}}}</td>
                        </tr>
                        <tr>
                            <td><strong>Email</strong></td>
                            <td>{{{$generalInformation[0]->Email}}}</td>
                        </tr>
                        <tr>
                            <td><strong>Telephone No.</strong></td>
                            <td>{{{$generalInformation[0]->TelephoneNo}}}</td>
                        </tr>
                        <tr>
                            <td><strong>Mobile No.</strong></td>
                            <td>{{{$generalInformation[0]->MobileNo}}}</td>
                        </tr>
                        <tr>
                            <td><strong>Fax No.</strong></td>
                            <td>{{{$generalInformation[0]->FaxNo}}}</td>
                        </tr>
                        @if(isset($incorporationOwnershipTypes) && (int)$generalInformation[0]->OwnershipTypeReferenceNo!=14001)
                            <tr>
                                <td colspan="2" class="font-blue-madison bold warning">Attachments</td>
                            </tr>
                            @foreach($incorporationOwnershipTypes as $incorporationOwnershipType)
                                <tr>
                                    <td colspan="2">
                                        <i class="fa fa-check"></i> <a href="{{URL::to($incorporationOwnershipType->DocumentPath)}}" target="_blank">{{$incorporationOwnershipType->DocumentName}}</a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
        </div>

        <form class="form-inline" method="post" action="{{URL::to('contractor/changeexpirydate')}}">
            <div class="form-group">
                <label for="" class="control-label">New Expiry Date:</label>
                <input type="hidden" name="Id" value="{{$id}}">
                <input type="text" name="RegistrationExpiryDate" class="datepicker form-control input-sm">
                <button type="submit" class="btn green btn-sm">Update</button>
            </div>
        </form>

    </div>
@stop