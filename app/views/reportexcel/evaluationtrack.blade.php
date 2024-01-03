<html>
    <body>
        <div>
            <table>
                <thead>
               
                <tr>
                            <th>Sl. No.</th>
                            <th>Work Id</th>
                            <th>Name of Work</th>
                            <th>Agency</th>
                 
                            <th>Operation Time</th>
                            <th>Operation</th>
                        </tr>
                </thead>
                <tbody>
                <?php $count = 1; $totalAmount = 0; ?>
                @forelse($reportData as $data)
                <tr>
                            <td>{{$count}}</td>
                            <td>{{$data->WorkId}}</td>
                            <td>{{strip_tags($data->NameOfWork, '<br><br/><p><ul><li><ol>');}}</td>
                            <td>{{$data->procuringAgency}}</td>
                            <td>{{$data->OperationTime}}</td>
                            <td>{{$data->Operation}}</td>
                          
                      
                        </tr>
                    <?php $count++; ?>
                @empty
                    <tr><td colspan="11" class="font-red text-center">No data to display</td></tr>
                @endforelse
                <tr><td colspan="7" class="bold text-right">Total</td><td>{{number_format($totalAmount,2)}}</td><td colspan="3"></td></tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
