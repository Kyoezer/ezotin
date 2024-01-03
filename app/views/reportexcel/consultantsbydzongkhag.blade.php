<html>
    <body>
        <div>
            <table>
                <thead>
                <tr>
                    <th colspan="15">Consultants by Dzongkhag{{($dzongkhag != "")?" ($dzongkhag)":""}} </th>
                </tr>
                <tr style="border-top: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;">
                    <th></th>
                    <th colspan="4"><center>A</center></th>
                    <th colspan="8"><center>C</center></th>
                    <th colspan="8"><center>E</center></th>
                    <th>Total</th>
                </tr>
                <tr style="border-bottom: 1px solid #DDDDDD;">
                    <th>Dzongkhag</th>
                    <th>A1</th>
                    <th>A2</th>
                    <th>A3</th>
                    <th>Total</th>
                    <th>C1</th>
                    <th>C2</th>
                    <th>C3</th>
                    <th>C4</th>
                    <th>C5</th>
                    <th>C6</th>
                    <th>C7</th>
                    <th>Total</th>
                    <th>E1</th>
                    <th>E2</th>
                    <th>E3</th>
                    <th>E4</th>
                    <th>E5</th>
                    <th>E6</th>
                    <th>E7</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                <?php $sum1 = $sum2 = $sum3 = $sum4 = $sum5 = $sum6 = $sum7 = $sum8 = $sum9 = $sum10 = $sum11 = 0; ?>
                <?php $sum12 = $sum13 = $sum14 = $sum15 = $sum16 = $sum17 = $sum18 = $sum19 = $sum20 = $sum21 = 0; ?>
                @foreach($reportData as $data)
                    <tr>
                        <td>{{$data->Dzongkhag}}</td>
                        <td class="text-right">{{$data->CountA1}}<?php $sum1 += $data->CountA1; ?></td>
                        <td class="text-right">{{$data->CountA2}}<?php $sum2 += $data->CountA2; ?></td>
                        <td class="text-right">{{$data->CountA3}}<?php $sum3 += $data->CountA3; ?></td>
                        <td class="text-right">{{$data->CountA1+$data->CountA2+$data->CountA3}}<?php $sum4 += ($data->CountA1+$data->CountA2+$data->CountA3); ?></td>
                        <td class="text-right">{{$data->CountC1}}<?php $sum5 += $data->CountC1; ?></td>
                        <td class="text-right">{{$data->CountC2}}<?php $sum6 += $data->CountC2; ?></td>
                        <td class="text-right">{{$data->CountC3}}<?php $sum7 += $data->CountC3; ?></td>
                        <td class="text-right">{{$data->CountC4}}<?php $sum8 += $data->CountC4; ?></td>
                        <td class="text-right">{{$data->CountC5}}<?php $sum9 += $data->CountC5; ?></td>
                        <td class="text-right">{{$data->CountC6}}<?php $sum10 += $data->CountC6; ?></td>
                        <td class="text-right">{{$data->CountC7}}<?php $sum11 += $data->CountC7; ?></td>
                        <td class="text-right">{{$data->CountC1+$data->CountC2+$data->CountC3+$data->CountC4+$data->CountC5+$data->CountC6+$data->CountC7}}<?php $sum12 += ($data->CountC1+$data->CountC2+$data->CountC3+$data->CountC4+$data->CountC5+$data->CountC6+$data->CountC7); ?></td>
                        <td class="text-right">{{$data->CountE1}}<?php $sum13 += $data->CountE1; ?></td>
                        <td class="text-right">{{$data->CountE2}}<?php $sum14 += $data->CountE2; ?></td>
                        <td class="text-right">{{$data->CountE3}}<?php $sum15 += $data->CountE3; ?></td>
                        <td class="text-right">{{$data->CountE4}}<?php $sum16 += $data->CountE4; ?></td>
                        <td class="text-right">{{$data->CountE5}}<?php $sum17 += $data->CountE5; ?></td>
                        <td class="text-right">{{$data->CountE6}}<?php $sum18 += $data->CountE6; ?></td>
                        <td class="text-right">{{$data->CountE7}}<?php $sum19 += $data->CountE7; ?></td>
                        <td class="text-right">{{$data->CountE1+$data->CountE2+$data->CountE3+$data->CountE4+$data->CountE5+$data->CountE6+$data->CountE7}}<?php $sum20 += ($data->CountE1+$data->CountE2+$data->CountE3+$data->CountE4+$data->CountE5+$data->CountE6+$data->CountE7); ?></td>
                        <td class="text-right">{{$data->CountA1+$data->CountA2+$data->CountA3+$data->CountC1+$data->CountC2+$data->CountC3+$data->CountC4+$data->CountC5+$data->CountC6+$data->CountC7+$data->CountE1+$data->CountE2+$data->CountE3+$data->CountE4+$data->CountE5+$data->CountE6+$data->CountE7}}<?php $sum21 += ($data->CountA1+$data->CountA2+$data->CountA3+$data->CountC1+$data->CountC2+$data->CountC3+$data->CountC4+$data->CountC5+$data->CountC6+$data->CountC7+$data->CountE1+$data->CountE2+$data->CountE3+$data->CountE4+$data->CountE5+$data->CountE6+$data->CountE7); ?></td>
                    </tr>
                @endforeach
                <tr>
                    <td><b>Total</b></td>
                    <td><b>{{$sum1}}</b></td>
                    <td><b>{{$sum2}}</b></td>
                    <td><b>{{$sum3}}</b></td>
                    <td><b>{{$sum4}}</b></td>
                    <td><b>{{$sum5}}</b></td>
                    <td><b>{{$sum6}}</b></td>
                    <td><b>{{$sum7}}</b></td>
                    <td><b>{{$sum8}}</b></td>
                    <td><b>{{$sum9}}</b></td>
                    <td><b>{{$sum10}}</b></td>
                    <td><b>{{$sum11}}</b></td>
                    <td><b>{{$sum12}}</b></td>
                    <td><b>{{$sum13}}</b></td>
                    <td><b>{{$sum14}}</b></td>
                    <td><b>{{$sum15}}</b></td>
                    <td><b>{{$sum16}}</b></td>
                    <td><b>{{$sum17}}</b></td>
                    <td><b>{{$sum18}}</b></td>
                    <td><b>{{$sum19}}</b></td>
                    <td><b>{{$sum20}}</b></td>
                    <td><b>{{$sum21}}</b></td>
                </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
