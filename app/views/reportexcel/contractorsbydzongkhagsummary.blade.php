<html>
    <body>
        <div>
            <table>
                <thead>
                <tr>
                    <th colspan="15">Contractors by Dzongkhag (Summary)</th>
                </tr>
                <tr>
                    <th>Dzongkhag</th>
                    <th>Large</th>
                    <th>Medium</th>
                    <th>Small</th>
                    <th>Registered</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                <?php $largeTotal = $mediumTotal = $smallTotal = $registeredTotal = $rowTotal = $grandTotal = 0; ?>
                @foreach($reportData as $data)
                    <tr>
                        <td>{{$data->Dzongkhag}}</td>
                        <td class="text-right">{{$data->Large}}</td><?php $largeTotal+=(int)$data->Large; ?>
                        <td class="text-right">{{$data->Medium}}</td><?php $mediumTotal+=(int)$data->Medium; ?>
                        <td class="text-right">{{$data->Small}}</td><?php $smallTotal+=(int)$data->Small; ?>
                        <td class="text-right">{{$data->Registered}}</td><?php $registeredTotal+=(int)$data->Registered; ?>
                        <?php $rowTotal = (int)$data->Large+ (int)$data->Medium+ (int)$data->Small+(int)$data->Registered; ?>
                        <td class="text-right">{{$rowTotal}}</td>
                    </tr>
                    <?php $grandTotal += $rowTotal; $rowTotal = 0; ?>
                @endforeach
                <tr>
                    <td class="bold">Total</td>
                    <td class="text-right bold large-total">{{$largeTotal}}</td>
                    <td class="text-right bold medium-total">{{$mediumTotal}}</td>
                    <td class="text-right bold small-total">{{$smallTotal}}</td>
                    <td class="text-right bold registered-total">{{$registeredTotal}}</td>
                    <td class="text-right bold ">{{$grandTotal}}</td
                </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
