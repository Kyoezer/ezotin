<html>
    <body>
        <table>
            <thead>
            <tr>
                <th colspan="3">Equipment Summary</th>
            </tr>
            @if($dzongkhag != '')
                <tr>
                    <th><i>Dzongkhag: </i>&nbsp;{{$dzongkhag}}</th>
                </tr>
            @endif
            @if($isRegistered != '')
                <tr>
                    <th><i>Type:</i>&nbsp;{{$isRegistered}}</th>
                </tr>
            @endif
            <tr><th colspan="3"></th></tr>
            <tr>
            <tr>
                <th class="order">
                    Sl. No.
                </th>
                <th>Type</th>
                <th>
                    Equipment Name
                </th>
                <th>
                    Quantity
                </th>
            </tr>
            </thead>
            <tbody>
            <?php $count = 1; $total = 0; ?>
            @forelse($equipmentGroups as $equipmentGroup)
                <tr>
                    <td>{{$count}}</td>
                    <td>{{$equipmentGroup->Type}}</td>
                    <td>{{$equipmentGroup->Name}}</td>
                    <td class="text-right">{{$equipmentGroup->Quantity}}<?php $total+=$equipmentGroup->Quantity; ?></td>
                </tr>
                <?php $count++; ?>
            @empty
                <tr>
                    <td colspan="4" class="text-center font-red">No data to display</td>
                </tr>
            @endforelse
            @if(count($equipmentGroups) > 0)
                <tr>
                    <td class="text-right bold">Total: </td>
                    <td></td>
                    <td></td>
                    <td class="bold text-right">{{$total}}</td>
                </tr>
            @endif
            </tbody>
        </table>
    </body>
</html>
