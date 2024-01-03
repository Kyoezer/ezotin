<html>
    <body>
        <div>
            <table>
                <thead>
                <tr>
                    <th colspan="2">Specialized Trade by Dzongkhag{{($dzongkhag != "")?" ($dzongkhag)":""}} </th>
                </tr>
                <tr>
                        <th class="text-center">Dzongkhag</th>
                        <th class="text-center">No. of Specialized Trade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $sum1 = 0; ?>
                    @foreach($reportData as $data)
                    <tr>
                            <td>{{$data->Dzongkhag}}</td>
                            <td class="text-right">{{$data->NoOfSpecializedtrade}}<?php $sum1+=$data->NoOfSpecializedtrade; ?></td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="bold">Total</td>
                        <td class="text-right bold large-total">{{$sum1}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
