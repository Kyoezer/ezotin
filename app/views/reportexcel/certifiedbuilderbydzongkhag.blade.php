<html>
    <body>
        <div>
            <table>
                <thead>
                <tr>
                    <th colspan="15">Consultants by Dzongkhag{{($dzongkhag != "")?" ($dzongkhag)":""}} </th>
                </tr>
                <tr style="border-top: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;">
               
                </tr>
                <tr style="border-bottom: 1px solid #DDDDDD;">
                  
                  <th>SF1(Masonry)</th>
                  <th>SF2(Construction Carpentry)</th>
                  <th>SF3(Plumbing)</th>
                  <th>SF4(Electrical)</th>
                  <th>SF5(Weilding & Fabrication)</th>
                  <th>SF6(Painting)</th>
                 
                  <th>Total</th>
                  <th></th>
                </tr>
                </thead>
                <tbody>
                <?php $sum1 = $sum2 = $sum3 = $sum4 = $sum5 = $sum6 = $sum7 =  0; ?>
                @foreach($reportData as $data)
                <tr>
                    <td>{{$data->Dzongkhag}}</td>
                   
                    <td class="text-right">{{$data->CountSF1}}<?php $sum1 += $data->CountSF1; ?></td>
                    <td class="text-right">{{$data->CountSF2}}<?php $sum2 += $data->CountSF2; ?></td>
                    <td class="text-right">{{$data->CountSF3}}<?php $sum3 += $data->CountSF3; ?></td>
                    <td class="text-right">{{$data->CountSF4}}<?php $sum4 += $data->CountSF4; ?></td>
                    <td class="text-right">{{$data->CountSF5}}<?php $sum5 += $data->CountSF5; ?></td>
                    <td class="text-right">{{$data->CountSF6}}<?php $sum6 += $data->CountSF6; ?></td>
                    <td class="text-right">{{$data->CountSF1+$data->CountSF2+$data->CountSF3+$data->CountSF4+$data->CountSF5+$data->CountSF6}}<?php $sum7 += ($data->CountSF1+$data->CountSF2+$data->CountSF3+$data->CountSF4+$data->CountSF5+$data->CountSF6); ?></td>
                    
                </tr>
                       
                @endforeach
                <tr>
                <td class="bold">Total</td>
                <td class="text-right bold">{{$sum1}}</td>
                <td class="text-right bold">{{$sum2}}</td>
                <td class="text-right bold">{{$sum3}}</td>
                <td class="text-right bold">{{$sum4}}</td>
                <td class="text-right bold">{{$sum5}}</td>
                <td class="text-right bold">{{$sum6}}</td>
                <td class="text-right bold">{{$sum7}}</td>
               
            </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
