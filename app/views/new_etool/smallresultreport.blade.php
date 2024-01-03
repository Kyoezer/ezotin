@extends('horizontalmenumaster')
@section('content')
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cogs"></i>Contractors Scoring
            </div>
        </div>
        <div class="portlet-body flip-scroll" style="overflow-x: scroll;">
            <table class="table table-bordered table-condensed">
                <tbody>
                    @foreach($tenderDetails as $tenderDetail)
                    <tr>
                        <td class="bold">Work ID:</td>
                        <td>{{$tenderDetail->WorkId}}</td>
                        <td class="bold">Class & Category</td>
                        <td>{{$tenderDetail->ClassCategory}}</td>
                        <td class="bold">Estimated Project Cost</td>
                        <td>{{$tenderDetail->ProjectEstimateCost}}</td>
                        <td class="bold">Name of work</td>
                        <td>{{$tenderDetail->NameOfWork}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <h4>Contractors who Bid:</h4>
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>CDB No.</th>
                        <th>Quoted Amount</th>
                        <th>Negotiated Amount</th>
                        <th>(+)(-) Estimation %</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 1; ?>
                    @foreach($bidContractors as $bidContractor)
                    <tr>
                        <td>{{"$bidContractor->NameOfFirm (CDB No. $bidContractor->CDBNo)"}}</td>
                        <td>{{$bidContractor->FinancialBidQuoted}}</td>
                        <td>{{$bidContractor->AwardedAmount}}</td>
                        <td>{{round((($bidContractor->FinancialBidQuoted-$tenderDetail->ProjectEstimateCost)/$tenderDetail->ProjectEstimateCost * 100),2)}}</td>
                        <td>@if((bool)$bidContractor->AwardedAmount){{$tenderDetail->Status}}@else{{"L$count"}}@endif</td>
                    </tr>
                    <?php $count++; ?>
                    @endforeach
                </tbody>
            </table>
            <h4>Evaluation Committee:</h4>
            <table class="table table-bordered table-condensed">
                <thead>
                <tr>
                    <th>Sl.No</th>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Signature</th>
                </tr>
                </thead>
                <tbody>
                <?php $count = 1; ?>
                @forelse($evaluationCommittee as $member)
                <tr>
                    <td>{{$count}}</td>
                    <td>{{$member->Name}}</td>
                    <td>{{$member->Designation}}</td>
                    <td></td>
                </tr>
                    <?php $count++; ?>
                @empty
                    <tr>
                        <td colspan="4" class="font-red text-center">No data to display</td>
                    </tr>
                @endforelse
                <tr><td>Remarks:</td>
                    <td colspan="3"></td>
                </tr>
                </tbody>
            </table>

            <h4>Tender Committee:</h4>
            <table class="table table-bordered table-condensed">
                <thead>
                <tr>
                    <th>Sl.No</th>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Signature</th>
                </tr>
                </thead>
                <tbody>
                <?php $count = 1; ?>
                @forelse($tenderCommittee as $member2)
                    <tr>
                        <td>{{$count}}</td>
                        <td>{{$member2->Name}}</td>
                        <td>{{$member2->Designation}}</td>
                        <td></td>
                    </tr>
                    <?php $count++; ?>
                @empty
                    <tr>
                        <td colspan="4" class="font-red text-center">No data to display</td>
                    </tr>
                @endforelse
                <tr><td>Remarks:</td>
                    <td colspan="3"></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@stop