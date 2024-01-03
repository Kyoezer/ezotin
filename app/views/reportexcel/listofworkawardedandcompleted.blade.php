<html>
    <body>
        <table>
            <thead>
            <tr>
                <th colspan="13">List of Contractor with service avail</th>
            </tr>
            @foreach($parametersForPrint as $key=>$value)
                <tr>
                    <th>{{$key}}:</th><th> {{$value}}</th>
                </tr>
            @endforeach
            <tr><th colspan="13"></th></tr>
            <tr>
                <th>
                    Sl.No.
                </th>
                <th class="order">
                Work Start Date
                </th>
                <th class="order">
                Classification
                </th>
                <th>
                Project Category
                </th>
                <th>
                Ontime Completion Score
                </th>
                <th class="">
                Quality of Execution Score
                </th>
                <th class="">
                Work Completion Date
                </th>
                <th class="">
                WorkId
                </th>   
                <th class="">
                Name of Work
                </th>   
                <th class="">
                Actual End Date
                </th>   
                <th class="">
                Awarded Amount
                </th>  
                <th class="">
                Bid Amount
                </th>    
                <th class="">
                Evaluated Amount
                </th>    
                <th class="">
                 Procuring Agency

                </th>    
                <th class="">
                Work Status
                </th>    
                <th class="">
                LD Imposed
                </th>    
                <th class="">
                LD No. of Days
                </th>    
                <th class="">
                LD Amount
                </th>    
                <th class="">
                Hindrance
                </th>    
                <th class="">
                Hindrance No of Days
                </th> 
                <th class="">
                Final Amount
                </th> 
                <th class="">
                CostOv
                </th> 
                <th class="">
                Completion Date Final
                </th> 
                <th class="">
                Name_exp_23
                </th> 
            </tr>
            </thead>
            <tbody>
            <?php $count = 1; ?>
            @forelse($contractorLists as $contractor)
            <tr>
                    <td>{{$count}}</td>
                    <td>{{$contractor->WorkStartDate}}</td>
                    <td>{{$contractor->classification}}</td>
                    <td>{{$contractor->ProjectCategory}}</td>
                    <td>{{$contractor->OntimeCompletionScore}}</td>
                    <td>{{$contractor->QualityOfExecutionScore}}</td>
                    <td>{{$contractor->WorkCompletionDate}}</td>
                    <td>{{$contractor->WorkId}}</td>
                    <td>{{$contractor->NameOfWork}}</td>
                    <td>{{$contractor->ActualEndDate}}</td>
                    <td>{{$contractor->AwardedAmount}}</td>
                    <td>{{$contractor->BidAmount}}</td>
                    <td>{{$contractor->EvaluatedAmount}}</td>
                    <td>{{$contractor->ProcuringAgency}}</td>
                    <td>{{$contractor->WorkStatus}}</td>
                    <td>{{$contractor->LDImposed}}</td>
                    <td>{{$contractor->LDNoOfDays}}</td>
                    <td>{{$contractor->LDAmount}}</td>
                    <td>{{$contractor->Hindrance}}</td>
                    <td>{{$contractor->HindranceNoOfDays}}</td>
                    <td>{{$contractor->FinalAmount}}</td>
                    <td>{{$contractor->CostOv}}</td>
                    <td>{{$contractor->CompletionDateFinal}}</td>
                    <td>{{$contractor->Name_exp_23}}</td>
       
                </tr>
                <?php $count++; ?>
            @empty
                <tr>
                    <td colspan="14" class="text-center font-red">No data to display!</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </body>
</html>
