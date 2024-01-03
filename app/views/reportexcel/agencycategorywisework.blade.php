<html>
    <body>
        <div>
            <table>
                <thead class="flip-content">
                <tr>
                    <th rowspan="4">Sl#</th>
                    <th rowspan="4" style="border-right: 1px solid #DDDDDD; vertical-align: middle;">Agency</th>
                    <th>Awarded(W1)</th>
                    <th>Awarded(W2)</th>
                    <th>Awarded(W3)</th>
                    <th>Awarded(W4)</th>
                    <th>Completed(W1)</th>
                    <th>Completed(W2)</th>
                    <th>Completed(W3)</th>
                    <th>Completed(W4)</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                <?php $rowCount = 1;$rowTotal = $total1 = $total2 = $total3 = $total4 = $total5 = $total6 = $total7 = $total8 = 0; $count = 0; ?>
                @foreach($reportData as $data)
                    <tr>
                        <td>{{$rowCount++}}</td>
                        <td>{{$data->Agency}}</td>
                        <td class="text-right">{{$data->W1Awarded}}<?php $total1 += $data->W1Awarded; ?></td>
                        <td class="text-right">{{$data->W2Awarded}}<?php $total2 += $data->W2Awarded; ?></td>
                        <td class="text-right">{{$data->W3Awarded}}<?php $total3 += $data->W3Awarded; ?></td>
                        <td class="text-right">{{$data->W4Awarded}}<?php $total4 += $data->W4Awarded; ?></td>
                        <td class="text-right">{{$data->W1Completed}}<?php $total5 += $data->W1Completed; ?></td>
                        <td class="text-right">{{$data->W2Completed}}<?php $total6 += $data->W2Completed; ?></td>
                        <td class="text-right">{{$data->W3Completed}}<?php $total7 += $data->W3Completed; ?></td>
                        <td class="text-right">{{$data->W4Completed}}<?php $total8 += $data->W4Completed; ?></td>
                        <td class="text-right"><?php $rowTotal+=$data->W1Awarded+$data->W2Awarded+$data->W3Awarded+$data->W4Awarded+$data->W1Completed+$data->W2Completed+$data->W3Completed+$data->W4Completed; ?>{{$data->W1Awarded+$data->W2Awarded+$data->W3Awarded+$data->W4Awarded+$data->W1Completed+$data->W2Completed+$data->W3Completed+$data->W4Completed}}</td>
                    </tr>
                @endforeach
                <tr>
                    <td class="bold text-right" colspan="2">Total</td>
                    <td class="text-right">{{$total1}}</td>
                    <td class="text-right">{{$total2}}</td>
                    <td class="text-right">{{$total3}}</td>
                    <td class="text-right">{{$total4}}</td>
                    <td class="text-right">{{$total5}}</td>
                    <td class="text-right">{{$total6}}</td>
                    <td class="text-right">{{$total7}}</td>
                    <td class="text-right">{{$total8}}</td>
                    <td class="text-right">{{$rowTotal}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
