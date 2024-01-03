<html>
    <body>
        <div>
            <table>
                <thead>
                <tr>
                    <th colspan="2">Etool and CiNet focal persons</th>
                </tr>
                @if($type != '')
                    <tr>
                        <th><i>Type: </i>&nbsp;{{(Input::get('Type') == 'All')?"All":(Input::get('Type')==7)?"CiNet":"Etool"}}</th>
                    </tr>
                @endif
                <tr>
                    <th colspan="4"></th>
                </tr>
                <tr>
                    <th>Name</th>
                    <th>Phone No.</th>
                    <th>Email Address</th>
                    <th>Agency</th>
                    <th>Type</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @forelse($reportData as $data)
                    <tr>
                        <td>{{$data->FullName}}</td>
                        <td>{{$data->ContactNo}}</td>
                        <td>{{$data->Email}}</td>
                        <td>{{$data->Agency}}</td>
                        <td>@if($data->ReferenceNo == 7){{"CiNet"}}@endif @if($data->ReferenceNo==8){{"Etool"}}@endif</td>
                        <td>@if($data->Status == 1){{"Active"}}@endif @if($data->Status == 0){{"Inactive"}}@endif</td>

                    </tr>
                @empty

                    <tr><td colspan="5" class="font-red text-center">No data to display</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </body>
</html>
