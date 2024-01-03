<html>
    <body>
        <div>
            <table>
                <thead>
                <tr>
                    <th colspan="2">LD Report</th>
                </tr>
                @if($cdbNo != '--')
                    <tr>
                        <th><i>CDB No.: </i>&nbsp;{{$cdbNo}}</th>
                    </tr>
                @endif
                @if($workId != '--')
                    <tr>
                        <th><i>Work Id: </i>&nbsp;{{$workId}}</th>
                    </tr>
                @endif

                <tr>
                    <th colspan="4"></th>
                </tr>
                <tr>
                    <th>
                        Sl.No.
                    </th>
                    <th>
                        Firm
                    </th>
                    <th>Name of Work</th>
                    <th>WorkId</th>
                    <th>Amount</th>
                    <th class="">
                        Start Date
                    </th>
                    <th class="">
                        End Date
                    </th>
                    <th class="">
                        LD No. of Days
                    </th>
                    <th class="">
                        LD Amount
                    </th>
                    <th class="">
                        Remarks
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php $count = 1; ?>
                @forelse($ldWorks as $data)
                    <tr>
                        <td>{{$count++}}</td>
                        <td>{{$data->Contractors}}</td>
                        <td>{{$data->NameOfWork}}</td>
                        <td>{{$data->WorkId}}</td>
                        @if($data->CmnWorkExecutionStatusId == CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                            <td>
                                {{$data->AwardedAmount}}
                            </td>
                            <td>
                                {{convertDateToClientFormat($data->ActualStartDate)}}
                            </td>
                            <td>
                                {{convertDateToClientFormat($data->ActualEndDate)}}
                            </td>
                        @else
                            <td>
                                {{$data->ContractPriceFinal}}
                            </td>
                            <td>
                                {{convertDateToClientFormat($data->CommencementDateFinal)}}
                            </td>
                            <td>
                                {{convertDateToClientFormat($data->CompletionDateFinal)}}
                            </td>
                        @endif
                        <td>{{$data->LDNoOfDays}}</td>
                        <td>{{$data->LDAmount}}</td>
                        <td>{{$data->Remarks}}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center font-red">No data to display!</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </body>
</html>
