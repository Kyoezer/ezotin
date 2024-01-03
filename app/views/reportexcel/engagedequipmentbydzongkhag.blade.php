<html>
    <body>
        <div>
            <table>
                <thead>
                <tr>
                    <th colspan="2">Engaged Equipments by Dzongkhag</th>
                </tr>
                @if($date != '')
                    <tr>
                        <th><i>As on Date:</i>&nbsp;{{convertDateToClientFormat($date)}}</th>
                    </tr>
                @endif
                <tr>
                    <th colspan="4"></th>
                </tr>
                <tr>
                    <th style="font-size: 8.5pt;">Sl.No.</th>
                    <th style="font-size: 8.5pt;">Equipment Name</th>
                    @foreach($dzongkhags as $dzongkhag)
                        <th style="font-size: 8.5pt;">{{$dzongkhag->NameEn}}</th>
                    @endforeach
                    <th style="font-size: 8.5pt;">Total</th>
                </tr>
                </thead>
                <tbody>
                <?php $count = 1; ?>
                @forelse($equipmentList as $equipment)
                    <?php $rowTotal = 0; ?>
                    <tr>
                        <td  style="font-size: 8.5pt;">{{$count}}</td>
                        <td  style="font-size: 8.5pt;">{{$equipment->Name}}</td>
                        @foreach($dzongkhags as $dzongkhag)
                            <td  style="font-size: 8.5pt;" class="text-right">{{number_format($equipmentDetail[$equipment->Id][$dzongkhag->Id])}}</td>
                            <?php $rowTotal += (int)$equipmentDetail[$equipment->Id][$dzongkhag->Id]; ?>
                        @endforeach
                        <td  style="font-size: 8.5pt;" class="text-right">{{number_format($rowTotal)}}</td>
                    </tr>
                    <?php $count++ ?>
                @empty
                    <tr><td  style="font-size: 8.5pt;" colspan="23" class="font-red text-center">No data to display</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </body>
</html>
