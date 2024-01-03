<html>
    <body>
        <div>
            <table>
                <thead>
                <tr>
                    <th colspan="15">Contractor's Work in Hand</th>
                </tr>
                </thead>
                <tbody>
                @foreach($singleContractor as $contractor)
                    <tr>
                        <td>Name of Firm: {{$contractor->NameOfFirm}}</td>
                        <td>CDB No.: {{$contractor->CDBNo}}</td>
                    </tr>
                    <tr>
                        <td>W1: {{$contractor->Classification1}}</td>
                        <td>W2: {{$contractor->Classification2}}</td>
                    </tr>
                    <tr>
                        <td>W3: {{$contractor->Classification3}}</td>
                        <td>W4: {{$contractor->Classification4}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <table>
                <thead class="flip-content">
                <tr>
                    <th>Sl.No.</th>
                    <th>Year</th>
                    <th>Agency</th>
                    <th>Work Id</th>
                    <th>Work Name</th>
                    <th>Category</th>
                    <th>Awarded Amount</th>
                    <th>Final Amount</th>
                    <th>Dzongkhag</th>
                    <th>Status</th>
                    <th>APS scoring</th>
                    <th>APS (100)</th>
                    <th>Remarks</th>
                </tr>
                </thead>
                <tbody>
                <?php $count = 1; $awardedAmount = 0;?>
                @forelse($workDetails as $workDetail)
                    <tr>
                        <td>{{$count}}</td>
                        <td>{{$workDetail->Year}}</td>
                        <td>{{$workDetail->Agency}}</td>
                        <td>{{$workDetail->WorkId}}</td>
                        <td>{{{$workDetail->NameOfWork}}}</td>
                        <td>{{$workDetail->Category}}</td>
                        <td>{{$workDetail->AwardedAmount}}</td>
                        <td>{{$workDetail->FinalAmount}}</td>
                        <td>{{$workDetail->Dzongkhag}}</td>
                        <td>{{$workDetail->Status}}<?php $awardedAmount+=($workDetail->ReferenceNo == 3001)?1:0;?></td>
                        <td>
                            <?php if((int)$workDetail->APS == 100) {
                                $points = 10;
                            }
                            if(((int)$workDetail->APS < 100) && ((int)$workDetail->APS >= 50)) {
                                $points = 10 - (ceil((100 - (int)$workDetail->APS) / 5));
                            }
                            if((int)$workDetail->APS < 50){
                                $points = 0;
                            }
                            ?>
                            {{$points}}
                        </td>
                        <td>{{$workDetail->APS}}</td>
                        <td>{{$workDetail->Remarks}}</td>
                    </tr>
                    <?php $count++ ?>
                @empty
                    <tr><td colspan="12" class="font-red text-center">No data to display</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if(!empty($workDetails))
            <h5><strong>No. of Works in Hand: </strong>{{$awardedAmount}}</h5>
        @endif
    </body>
</html>
