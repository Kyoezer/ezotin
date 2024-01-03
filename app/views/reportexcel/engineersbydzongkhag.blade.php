<html>
    <body>
        <div>
            <table>
                <thead>
                <tr>
                    <th colspan="2">Engineers by Dzongkhag{{($dzongkhag != "")?" ($dzongkhag)":""}} </th>
                </tr>
                <tr>
                    <th class="text-center">Dzongkhag</th>
                    <th class="text-center">No. of Engineers</th>
                </tr>
                </thead>
                <tbody>
                    <?php $sum1 = 0; ?>
                    @foreach($reportData as $data)
                        <tr>
                            <td>{{$data->Dzongkhag}}</td>
                            <td class="text-right">{{$data->NoOfEngineers}}<?php $sum1+=$data->NoOfEngineers; ?></td>
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
