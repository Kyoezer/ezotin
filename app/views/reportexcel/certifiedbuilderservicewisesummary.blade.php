<html>
    <body>
        <div>
            <table>
                <thead>
                <tr>
                    <th colspan="2">Certified Builder Service Wise Summary</th>
                </tr>
                <tr>
                    <th colspan="4"></th>
                </tr>
                <tr>
                <tr>
                    <th>Certified Builder Service</th>
                    <th>Number of Certified Builder</th>
                </tr>
                </thead>
                <tbody>
                <?php $total = 0; ?>
                @forelse($reportData as $data)
                    <tr>
                        <td>{{$data->Code}}-{{$data->Name}}</td>
                        <td class="text-right">{{$data->NoOfCertifiedBuilder}}<?php $total += $data->NoOfCertifiedBuilder;?></td>
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
