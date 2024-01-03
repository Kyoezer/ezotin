<html>
    <body>
        <table>
            <thead>
            <tr>
                <th colspan="12">List of Non Bhutanese Contractors</th>
            </tr>
         
         
            <tr><th colspan="12"></th></tr>
            <tr>
                    <th>
                        Sl.No.
                    </th>
					<th class="order">
						 CDB No.
					</th>
					<th>
						Firm/Name
					</th>
					<th class="">
						 Proprietor Name
					</th>
					<th class="">
						 Gender
					</th>
					<th class="">
						 Address
					</th>
					<th class="">
						 Country
					</th>
					<th>
						Dzongkhag
					</th>
					<th>
						Tel.No.
					</th>
					
					<th class="">
						 Mobile No.
					</th>
					<th class="">
						W1
					</th>
                    <th class="">
                        W2
                    </th>
                    <th class="">
                        W3
                    </th>
                    <th class="">
                        W4
                    </th>
                    <th>
                        Expiry Date
                    </th>
                    <th>Status</th>

                    <th>Refresher Course <br> Modules Attended</th>

				</tr>
            </thead>
            <tbody>
            <?php $count = 1; ?>
            @forelse($contractorList as $contractor )
            <tr>
                    <td>{{$count++}}</td>
                    <td>{{$contractor->CDBNo}}</td>
                    <td>{{$contractor->NameOfFirm}}</td>
                    <td>{{$contractor->ownerName}}</td>
                    <td>{{$contractor->gender}}</td>
                    <td>{{$contractor->Address}}</td>
                    <td>{{$contractor->Country}}</td>
                    <td>{{$contractor->Dzongkhag}}</td>
                    <td>{{$contractor->TelephoneNo}}</td>
                    <td>{{$contractor->MobileNo}}</td>
                    <td>{{$contractor->Classification1}}</td>
                    <td>{{$contractor->Classification2}}</td>
                    <td>{{$contractor->Classification3}}</td>
                    <td>{{$contractor->Classification4}}</td>
                    <td>{{convertDateTimeToClientFormat($contractor->ExpiryDate)}}</td>
                    <td>{{$contractor->Status}}</td>

                    <td>@if(isset($contractor->RefresherCourseModules)){{$contractor->RefresherCourseModules}}@endif</td>

				</tr>
                <?php $count++; ?>
            @empty
                <tr>
                    <td colspan="12" class="text-center font-red">No data to display!</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </body>
</html>
