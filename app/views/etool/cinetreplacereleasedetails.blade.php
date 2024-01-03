@extends('master')
@section('content')
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
                <i class="fa fa-cogs"></i>Replace HR/Equipment for Work Id: <?=$WorkId?>
            </div>
        </div>
        <div class="portlet-body flip-scroll">
            @if(Input::get('export')!='print')
            {{Form::open(array('url'=>Request::url(),'method'=>'post'))}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Work Id</label>
                            <input type="text" class="form-control input-sm" name="WorkId" value="{{$WorkId}}"/>
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
            @endif
            @if(isset($tenderDetails[0]->TenderId) && $tenderDetails[0]->TenderId)
            <h4 class="bold">Work Details</h4>
            <table class="table table-bordered table-condensed">
                <tbody>
                    @foreach($tenderDetails as $tenderDetail)
                        <tr>
                            <td class="bold text-right">Name of work:</td>
                            <td>{{$tenderDetail->NameOfWork}}</td>
                            <td class="bold text-right">Work ID:</td>
                            <td>{{$tenderDetail->ReferenceNo}}</td>
                            <td class="bold text-right">Procuring Agency:</td>
                            <td>{{$tenderDetail->ProcuringAgency}}</td>
                        </tr>
                        <tr>
                            <td class="bold text-right">Dzongkhag:</td>
                            <td>{{$tenderDetail->Dzongkhag}}</td>
                            <td class="bold text-right">Contract Period:</td>
                            <td>{{$tenderDetail->ContractPeriod." month(s)"}}</td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <h4 class="bold">Individuals Equipment Details</h4>
                    <table class="table table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th>Sl.No.</th>
                                <th>Equipment Name</th>
                                <th>Registration No.</th>
                                <th>Type</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1; ?>
                            @foreach($contractorEquipments as $contractorEquipment)
                                <tr>
                                    <td>{{$count}}</td>
                                    <td>{{$contractorEquipment->Name}}</td>
                                    <td>{{$contractorEquipment->RegistrationNo}}</td>
                                    <td>{{($contractorEquipment->OwnedOrHired == 2)?"Hired":"Owned"}}</td>
                                    <td class="text-center"><a href="{{URL::to("etoolsysadm/replaceCitnetEquipment/$contractorEquipment->Id")}}" >
                                    Replace</a> | <a href="{{URL::to("etoolsysadm/releaseCinetequipment/$contractorEquipment->Id")}}" >Release</a></td>
                                </tr>
                                <?php $count++; ?>
                            @endforeach
                        </tbody>
                    </table>

                <h4 class="bold">Individuals Human Resource Details</h4>
                    <table class="table table-condensed table-bordered">
                        <thead>
                       
                        <tr>
                            <th>Sl.No.</th>
                            <th>HR Name</th>
                            <th>CID No.</th>
                            <th>Designation</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $count = 1; ?>
                        @foreach($contractorHRs as $contractorHR)
                            <tr>
                                <td>{{$count}}</td>
                                <td>{{$contractorHR->Name}}</td>
                                <td>{{$contractorHR->CIDNo}}</td>
                                <td>{{$contractorHR->designation}}</td>
                                <td class="text-center"><a href="{{URL::to("etoolsysadm/replaceCinetHr/$contractorHR->Id")}}" class="">Replace</a> |
                                 <a href="{{URL::to("etoolsysadm/releaseCinethr/$contractorHR->Id")}}" class="">Release</a></td>
                            </tr>
                            <?php $count++; ?>
                        @endforeach
                        </tbody>
                    </table>
                <h4 class="bold">Contractor Details</h4>
                <?php $count = 1; ?>
            @endif
        </div>
    </div>
@stop