<html>
    <body>
        <table>
            <thead>
            <tr>
                <th colspan="13">List of Contractor with work</th>
            </tr>
            @foreach($parametersForPrint as $key=>$value)
                <tr>
                    <th>{{$key}}:</th><th> {{$value}}</th>
                </tr>
            @endforeach
            <tr><th colspan="13"></th></tr>
            <tr>
                <th>
                    Sl.No.
                </th>
             
                <th>
                    CDBNo
                </th>
                <th>
                    Dzongkhag
                </th>
                <th class="">
                  Class
                </th>
            
                      
            </tr>
            </thead>
            <tbody>
            <?php $start = 1; ?>
            @forelse($contractorLists as $contractor)
                  <tr>
                    <td>{{$start++}}</td>
                    <td>{{$contractor->CDBNo}}</td>
                    <td>{{$contractor->Dzongkhag}}</td>
                    <td>{{$contractor->classification}}</td>
                 
                   
       
                </tr>
                <?php $start++; ?>
            @empty
                <tr>
                    <td colspan="14" class="text-center font-red">No data to display!</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </body>
</html>
