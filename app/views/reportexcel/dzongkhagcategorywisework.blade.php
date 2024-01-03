<html>
    <body>
        <div>
            <table>
                <thead>
                <tr>
                    <th colspan="2">Dzongkhag Wise Category Wise Work Awarded and Completed </th>
                </tr>
                @if($fromDate != '')
                    <tr>
                        <th><i>From Date:</i>&nbsp;{{$fromDate}}</th>
                    </tr>
                @endif
                @if($toDate != '')
                    <tr>
                        <th><i>To Date:</i>&nbsp;{{$toDate}}</th>
                    </tr>
                @endif
                <tr>
                    <th colspan="4"></th>
                </tr>
                <tr style="border-bottom: 1px solid #DDDDDD;">
                    <th>Dzongkhag (Site)</th>
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
                <?php $rowTotal = $total1 = $total2 = $total3 = $total4 = $total5 = $total6 = $total7 = $total8 = 0; $count = 0; ?>
                @foreach($dzongkhags as $dzongkhag)
                    @if(isset($reportData[$dzongkhag->Id][0]->W1Awarded))
                        <?php $w1Awarded = $reportData[$dzongkhag->Id][0]->W1Awarded; ?>
                        <?php $w2Awarded = $reportData[$dzongkhag->Id][0]->W2Awarded; ?>
                        <?php $w3Awarded = $reportData[$dzongkhag->Id][0]->W3Awarded; ?>
                        <?php $w4Awarded = $reportData[$dzongkhag->Id][0]->W4Awarded; ?>
                        <?php $w1Completed= isset($reportData2[$dzongkhag->Id][0]->W1Completed)?$reportData2[$dzongkhag->Id][0]->W1Completed:0; ?>
                        <?php $w2Completed= isset($reportData2[$dzongkhag->Id][0]->W2Completed)?$reportData2[$dzongkhag->Id][0]->W2Completed:0; ?>
                        <?php $w3Completed= isset($reportData2[$dzongkhag->Id][0]->W3Completed)?$reportData2[$dzongkhag->Id][0]->W3Completed:0; ?>
                        <?php $w4Completed= isset($reportData2[$dzongkhag->Id][0]->W4Completed)?$reportData2[$dzongkhag->Id][0]->W4Completed:0; ?>
                        <tr>
                            <td>{{$dzongkhag->NameEn}}</td>
                            <td class="text-right">{{$w1Awarded}}<?php $total1 += $w1Awarded; ?></td>
                            <td class="text-right">{{$w2Awarded}}<?php $total2 += $w2Awarded; ?></td>
                            <td class="text-right">{{$w3Awarded}}<?php $total3 += $w3Awarded; ?></td>
                            <td class="text-right">{{$w4Awarded}}<?php $total4 += $w4Awarded; ?></td>
                            <td class="text-right">{{$w1Completed}}<?php $total5 += $w1Completed; ?></td>
                            <td class="text-right">{{$w2Completed}}<?php $total6 += $w2Completed; ?></td>
                            <td class="text-right">{{$w3Completed}}<?php $total7 += $w3Completed; ?></td>
                            <td class="text-right">{{$w4Completed}}<?php $total8 += $w4Completed; ?></td>
                            <td class="text-right"><?php $rowTotal+=$w1Awarded+$w2Awarded+$w3Awarded+$w4Awarded+$w1Completed+$w2Completed+$w3Completed+$w4Completed; ?>{{$w1Awarded+$w2Awarded+$w3Awarded+$w4Awarded+$w1Completed+$w2Completed+$w3Completed+$w4Completed}}</td>
                        </tr>
                    @endif
                @endforeach
                <tr>
                    <td class="bold text-right">Total</td>
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
