<html>
    <body>
        <div>
            <table>
                <thead>
                <tr><th colspan="6">No. of Personnel in Construction Industry</th></tr>
                <tr>
                    <th colspan="7"></th>
                </tr>
                <tr>
                    <th>Designation</th>
                    <th>Employed in Contractors</th>
                    <th>Employed in Consultants</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                <?php $total = $contractorTotal = $consultantTotal = 0;?>
                @foreach($reportData as $data)
                    <tr>
                        <td>{{$data->Designation}}</td>
                        <td class="text-right">{{number_format($data->ContractorNumber)}}<?php $contractorTotal+= doubleval($data->ContractorNumber); ?></td>
                        <td class="text-right">{{number_format($data->ConsultantNumber)}}<?php $consultantTotal+= doubleval($data->ConsultantNumber); ?></td>
                        <?php $rowTotal = (int)$data->ContractorNumber + (int)$data->ConsultantNumber; ?>
                        <td class="text-right">{{number_format($rowTotal)}}</td>
                        <?php $total+=$rowTotal; ?>
                    </tr>
                @endforeach
                <tr>
                    <td class="bold text-right">Total</td>
                    <td class="text-right">{{number_format($contractorTotal)}}</td>
                    <td class="text-right">{{number_format($consultantTotal)}}</td>
                    <td class="text-right">{{number_format($total)}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
