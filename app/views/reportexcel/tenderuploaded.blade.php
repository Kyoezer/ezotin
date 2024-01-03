<html>
    <body>
        <div>
            <table>
                <thead>
                <tr>
                    <th colspan="2">Tender Uploaded</th>
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
                    <th>Name of Work</th>
                    <th>Agency</th>
                    <th>Uploaded Date</th>
                    <th>Last Date of Submission</th>
                </tr>
                </thead>
                <tbody>
                <?php $count = 1; ?>
                @forelse($reportData as $data)
                    <tr>
                        <td>{{$count}}</td>
                        <td>{{strip_tags($data->NameOfWork, '<br><br/><p><ul><li><ol>');}}</td>
                        <td>{{$data->Agency}}</td>
                        <td>{{convertDateToClientFormat($data->UploadedDate)}}</td>
                        <td>{{convertDateToClientFormat($data->LastDateOfSubmission)}}</td>
                    </tr>
                    <?php $count++; ?>
                @empty
                    <tr><td colspan="5" class="font-red text-center">No data to display</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </body>
</html>
