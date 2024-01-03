<html>
    <body>
        <div>
            <table>
                <thead>
                <tr>
                    <th colspan="2">Agency Wise No. of Works</th>
                </tr>
                @if($order != '')
                    <tr>
                        <th><i>Order:</i>&nbsp;{{($order=="ASC")?"Lowest First":"Highest First"}}</th>
                    </tr>
                @endif
                @if($limit != '')
                    <tr>
                        <th><i>No. of rows:</i>&nbsp;{{$limit}}</th>
                    </tr>
                @endif
                <tr>
                    <th colspan="3"></th>
                </tr>
                <tr>
                    <th>Sl.No.</th>
                    <th>Agency</th>
                    <th>Number of Works</th>
                </tr>
                </thead>
                <tbody>
                <?php $count = 1; ?>
                @forelse($reportData as $data)
                    <tr>
                        <td>{{$count}}</td>
                        <td>{{$data->Agency}}</td>
                        <td>{{$data->NoOfWorks}}</td>
                    </tr>
                    <?php $count++ ?>
                @empty
                    <tr><td colspan="3" class="font-red text-center">No data to display</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </body>
</html>
