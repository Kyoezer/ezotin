<html>
    <body>
        <div>
            <table>
                <thead>
                <tr>
                    <th colspan="2">Work Id (Report)</th>
                </tr>
                @if($procuringAgency != '')
                    <tr>
                        <th><i>Agency: </i>&nbsp;{{$procuringAgency}}</th>
                    </tr>
                @endif
                @if($fromDate != '')
                    <tr>
                        <th><i>From Date:</i>&nbsp;{{$fromDate}}</th>
                    </tr>
                @endif
                @if($toDate != '')
                    <tr>
                        <th><i>To Date: </i>&nbsp;{{$toDate}}</th>
                    </tr>
                @endif
                <tr>
                    <th><i>Source: </i>&nbsp;{{($tenderSource == 0)?'All':(($tenderSource == 1)?"Etool":"CiNET")}}</th>
                </tr>
                <tr>
                    <th colspan="4"></th>
                </tr>
                <tr>
                    <th>Sl. No.</th>
                    <th>Work Id</th>
                    <th>Name of Work</th>
                    <th>Duration (in months)</th>
                    <th>Class</th>
                    <th>Category</th>
                </tr>
                </thead>
                <tbody>
                <?php $count = 1; ?>
                @forelse($reportData as $data)
                    <tr>
                        <td>{{$count}}</td>
                        <td>{{$data->WorkId}}</td>
                        <td>{{strip_tags($data->NameOfWork, '<br><br/><p><ul><li><ol>');}}</td>
                        <td>{{$data->ContractPeriod}}</td>
                        <td>{{$data->Class}}</td>
                        <td>{{$data->Category}}</td>
                    </tr>
                    <?php $count++; ?>
                @empty
                    <tr><td colspan="6" class="font-red text-center">No data to display</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </body>
</html>
