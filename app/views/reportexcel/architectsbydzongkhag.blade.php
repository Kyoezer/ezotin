<html>
    <body>
        <div>
            <table>
                <thead>
                <tr>
                    <th colspan="2">Architects by Dzongkhag{{($dzongkhag != "")?" ($dzongkhag)":""}} </th>
                </tr>
                <tr>
                    <th class="text-center">Dzongkhag</th>
                    <th class="text-center">No. of Architects</th>
                </tr>
                </thead>
                <tbody>
                    <?php $sum1 = 0; ?>
                    @foreach($reportData as $data)
                        <tr>
                            <td>{{$data->Dzongkhag}}</td>
                            <td class="text-right">{{$data->NoOfArchitects}}<?php $sum1+=$data->NoOfArchitects; ?></td>
                        </tr>
                    @endforeach
                    <tr>
                        <td><b>Total</b></td>
                        <td><b>{{$sum1}}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
