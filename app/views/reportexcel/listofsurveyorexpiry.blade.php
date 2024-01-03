<html>
    <body>
        <table>
            <thead>
            <tr>
                <th colspan="12">List of Surveyor nearing expiry</th>
            </tr>
            <tr>
					<th class="order">
						 CDB No.
					</th>
					<th>
						Name
					</th>
					<th class="">
						 CID No.
					</th>
					<th class="">
						 Sector
					</th>
					<th>
						Country
					</th>
					<th class="">
						 Dzongkhag
					</th>
					<th class="">
						Gewog
					</th>
                    <th class="">
                        Village
                    </th>
                    <th class="">
                        Email
                    </th>
                    <th class="">
                        Mobile No.
                    </th>
                    <th>
                        Expiry Date
                    </th>
                    <th>Status</th>
                    </tr>
            </thead>
            <tbody>
            <?php $count=1; ?>
            @forelse($surveyorList as $surveyor)
				<tr>
                    <td>{{$surveyor->ARNo}}</td>
                    <td>{{$surveyor->ArchitectName}}</td>
                    <td>{{$surveyor->CIDNo}}</td>
                    <td>{{$surveyor->Sector}}</td>
                    <td>{{$surveyor->Country}}</td>
                    <td>{{$surveyor->Dzongkhag}}</td>
                    <td>{{$surveyor->Gewog}}</td>
                    <td>{{$surveyor->Village}}</td>
                    <td>{{$surveyor->Email}}</td>
                    <td>{{$surveyor->MobileNo}}</td>
                    <td>{{$surveyor->ExpiryDate}}</td>
                    <td>{{$surveyor->Status}}</td>
				</tr>
                @empty
                <tr>
                    <td colspan="14" class="font-red text-center">No data to display!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </body>
</html>
