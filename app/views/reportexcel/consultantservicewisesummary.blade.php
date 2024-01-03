<html>
    <body>
        <div>
            <table>
                <thead>
                <tr>
                    <th colspan="2">Consultant Service Wise Summary</th>
                </tr>
                <tr>
                    <th colspan="4"></th>
                </tr>
                <tr>
                <tr>
                    <th>Consultant Service</th>
                    <th>Number of Consultants</th>
                </tr>
                </thead>
                <tbody>
                <?php $total = 0; ?>
                @forelse($reportData as $data)
                    <tr>
                        <td>{{$data->Code}}</td>
                        <td class="text-right">{{$data->NoOfConsultants}}<?php $total += $data->NoOfConsultants;?></td>
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
