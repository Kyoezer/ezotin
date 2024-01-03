<html>
    <body>
        <div>
            <table>
                <thead>
                <tr>
                    <th colspan="15">Contractors by Dzongkhag{{($dzongkhag != "")?" ($dzongkhag)":""}} </th>
                </tr>
                <tr >
                    <th>Dzongkhag</th>
                    <th>W1 (L)</th>
                    <th>W1 (M)</th>
                    <th>W1 (S)</th>
                    <th>W1 (Total)</th>
                    <th>W2</th>
                    <th>W3 (L)</th>
                    <th>W3 (M)</th>
                    <th>W3 (S)</th>
                    <th>W3 (Total)</th>
                    <th>W4 (L)</th>
                    <th>W4 (M)</th>
                    <th>W4 (S)</th>
                    <th>W4 (Total)</th>
                    <th>Grand Total</th>
                </tr>
                </thead>
                <tbody>
                <?php $sum1 = $sum2 = $sum3=$sum4=$sum5=$sum6=$sum7=$sum8=$sum9=$sum10=$sum11=$sum12=$sum13=$sum14 = 0; ?>
                @foreach($reportData as $data)
                    <tr>
                        <td>{{$data->NameEn}}</td>
                        <td class="text-right">{{$data->W1L}}<?php $sum1 += $data->W1L; ?></td>
                        <td class="text-right">{{$data->W1M}}<?php $sum2 += $data->W1M; ?></td>
                        <td class="text-right">{{$data->W1S}}<?php $sum3 += $data->W1S; ?></td>
                        <td class="text-right">{{$data->W1Total}}<?php $sum4 += $data->W1Total; ?></td>
                        <td class="text-right">{{$data->W2R}}<?php $sum5 += $data->W2R; ?></td>
                        <td class="text-right">{{$data->W3L}}<?php $sum6 += $data->W3L; ?></td>
                        <td class="text-right">{{$data->W3M}}<?php $sum7 += $data->W3M; ?></td>
                        <td class="text-right">{{$data->W3S}}<?php $sum8 += $data->W3S; ?></td>
                        <td class="text-right">{{$data->W3Total}}<?php $sum9 += $data->W3Total; ?></td>
                        <td class="text-right">{{$data->W4L}}<?php $sum10 +=$data->W4L;  ?></td>
                        <td class="text-right">{{$data->W4M}}<?php $sum11 +=$data->W4M;  ?></td>
                        <td class="text-right">{{$data->W4S}}<?php $sum12 +=$data->W4S;  ?></td>
                        <td class="text-right">{{$data->W4Total}}<?php $sum13 +=$data->W4Total;  ?></td>
                        <td class="text-right">{{($data->W1Total+$data->W3Total+$data->W4Total+$data->W2R)}}<?php $sum14 += ($data->W1Total+$data->W3Total+$data->W4Total+$data->W2R);?></td>
                    </tr>
                @endforeach
                <tr>
                    <td><b>Total</b></td>
                    <td>{{$sum1}}</td>
                    <td>{{$sum2}}</td>
                    <td>{{$sum3}}</td>
                    <td>{{$sum4}}</td>
                    <td>{{$sum5}}</td>
                    <td>{{$sum6}}</td>
                    <td>{{$sum7}}</td>
                    <td>{{$sum8}}</td>
                    <td>{{$sum9}}</td>
                    <td>{{$sum10}}</td>
                    <td>{{$sum11}}</td>
                    <td>{{$sum12}}</td>
                    <td>{{$sum13}}</td>
                    <td>{{$sum14}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
