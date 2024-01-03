<html>
    <body>
        <div>
            <table>
                <thead>
                <tr>
                    <th colspan="2">List of Engaged Hr</th>
                </tr>
         
                <tr>
                    <th colspan="4"></th>
                </tr>
                <tr>
                        <th>Sl.No.</th>
                        <th>CDB No</th>
                        <th>Name</th>
                        <th>CIDNo</th>
                        <th>Designation</th>
                  
                        <th>Work Id</th>
                  
                        <th>Agency Name</th>
                    </tr>
                </thead>
                <tbody>
                <?php $count = 1; ?>
                @forelse($reportData as $data)
                <tr>
                            <td>{{$count}}</td>
                            <td>{{$data->CDBNo}}</td>
                            <td>{{$data->hrName}}</td>
                            <td>{{$data->cidNo}}</td>
                            <td>{{$data->Designation}}</td>
                          
                            <td>{{$data->WorkId}}</td>
                          
                            <td>{{$data->procuringAgency}}</td>
                        </tr>
                    <?php $count++ ?>
                @empty
                    <tr><td colspan="4" class="font-red text-center">No data to display</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </body>
</html>
