<html>
    <body>
        <div>
            <table>
                <thead>
                <tr>
                    <th colspan="2">Specialized Firm Service Wise Summary</th>
                </tr>
                <tr>
                    <th colspan="4"></th>
                </tr>
                <tr>
                <tr>
                    <th>Specialized Firm Service</th>
                    <th>Number of Specialized Firm</th>
                </tr>
                </thead>
                <tbody>
                <?php $total = 0; ?>
                @forelse($reportData as $data)
                    <tr>
                        <td>{{$data->Code}}-{{$data->Name}}</td>
                        <td class="text-right">{{$data->NoOfSpecializedfirm}}<?php $total += $data->NoOfSpecializedfirm;?></td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="font-red text-center">No data to display</td></tr>
                @endforelse
                @if(!empty($reportData))
                    <tr>
                        <td class="bold text-right">Total</td>
                        <td class="text-right">{{$total}}</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </body>
</html>
