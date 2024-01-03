<html>
    <body>
        <div>
            <table>
                <thead>
                <tr>
                    <th colspan="2">Hindrance Report</th>
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
                        No. of Days
                    </th>
                    <th class="">
                        Remarks
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php $count = 1; ?>
                @forelse($hindranceWorks as $data)
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
                        <td>{{$data->HindranceNoOfDays}}</td>
                        <td>{{$data->Remarks}}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center font-red">No data to display!</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </body>
</html>
